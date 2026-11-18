<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\User;
use Carbon\Carbon;

class AdminTaskController extends Controller
{
    /**
     * Menampilkan daftar tugas pengecekan rutin dan formulir penugasan.
     */
    public function index(Request $request)
    {
        $intervalFilter = $request->input('interval', 'semua');
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $query = Task::with(['assignedUser', 'submissions' => function ($q) use ($today) {
            $q->where('tanggal', $today);
        }]);

        if (in_array($intervalFilter, ['harian', 'mingguan', 'bulanan'])) {
            $query->where('interval_type', $intervalFilter);
        }

        $tasks = $query->orderBy('created_at', 'desc')->get();
        $csUsers = User::where('role', 'cs')->get();

        // Statistik Tugas
        $totalTasks = Task::count();
        $harianCount = Task::where('interval_type', 'harian')->count();
        $mingguanCount = Task::where('interval_type', 'mingguan')->count();
        $bulananCount = Task::where('interval_type', 'bulanan')->count();

        // Riwayat submission pengerjaan hari ini
        $submissionsToday = TaskSubmission::with(['task', 'user'])
            ->where('tanggal', $today)
            ->latest()
            ->get();

        return view('admin-tasks', compact(
            'tasks',
            'csUsers',
            'intervalFilter',
            'totalTasks',
            'harianCount',
            'mingguanCount',
            'bulananCount',
            'submissionsToday',
            'today'
        ));
    }

    /**
     * Menyimpan tugas baru yang dibuat Administrator.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'location' => ['nullable', 'string', 'max:255'],
            'interval_type' => ['required', 'in:harian,mingguan,bulanan'],
            'target_time' => ['required', 'string', 'max:100'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ], [
            'title.required' => 'Nama tugas/pengecekan wajib diisi.',
            'interval_type.required' => 'Tipe interval wajib dipilih.',
            'target_time.required' => 'Target waktu pengerjaan wajib diisi.',
        ]);

        Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'] ?? null,
            'interval_type' => $validated['interval_type'],
            'target_time' => $validated['target_time'],
            'assigned_to' => !empty($validated['assigned_to']) ? $validated['assigned_to'] : null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.tasks.index')->with('success', 'Tugas pengecekan baru berhasil ditambahkan!');
    }

    /**
     * Memperbarui data tugas pengecekan.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'location' => ['nullable', 'string', 'max:255'],
            'interval_type' => ['required', 'in:harian,mingguan,bulanan'],
            'target_time' => ['required', 'string', 'max:100'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'] ?? null,
            'interval_type' => $validated['interval_type'],
            'target_time' => $validated['target_time'],
            'assigned_to' => !empty($validated['assigned_to']) ? $validated['assigned_to'] : null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.tasks.index')->with('success', 'Data tugas berhasil diperbarui!');
    }

    /**
     * Menghapus tugas pengecekan.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('admin.tasks.index')->with('success', 'Tugas berhasil dihapus!');
    }
}
