<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskSubmission;
use Carbon\Carbon;

class CsTaskController extends Controller
{
    /**
     * Menampilkan daftar tugas pengecekan aktif untuk Cleaning Service.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $intervalFilter = $request->input('interval', 'semua');

        $query = Task::where('is_active', true)
            ->where(function ($q) use ($user) {
                $q->whereNull('assigned_to')
                  ->orWhere('assigned_to', $user->id);
            });

        if (in_array($intervalFilter, ['harian', 'mingguan', 'bulanan'])) {
            $query->where('interval_type', $intervalFilter);
        }

        $tasks = $query->orderBy('created_at', 'asc')->get();

        // Ambil submission pengerjaan hari ini oleh user ini
        $submissionsToday = TaskSubmission::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->get()
            ->keyBy('task_id');

        // Statistik pengerjaan
        $totalActiveTasks = $tasks->count();
        $completedTodayCount = $tasks->filter(function ($t) use ($submissionsToday) {
            return isset($submissionsToday[$t->id]);
        })->count();
        $pendingCount = max(0, $totalActiveTasks - $completedTodayCount);

        return view('jadwal-kerja-cs', compact(
            'tasks',
            'submissionsToday',
            'intervalFilter',
            'totalActiveTasks',
            'completedTodayCount',
            'pendingCount',
            'today'
        ));
    }

    /**
     * Menyimpan laporan bukti pengerjaan tugas oleh Cleaning Service.
     */
    public function submit(Request $request, Task $task)
    {
        $validated = $request->validate([
            'photo_proof' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:3072'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'photo_proof.required' => 'Foto bukti pengerjaan wajib diunggah.',
            'photo_proof.image' => 'File bukti harus berupa gambar.',
            'photo_proof.max' => 'Ukuran foto bukti maksimal 3 MB.',
        ]);

        $user = auth()->user();
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
        $timeStr = $now->format('H:i:s') . ' WIB';

        // Simpan foto bukti pengerjaan
        $file = $request->file('photo_proof');
        $filename = 'proof_task_' . $task->id . '_user_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $photoPath = $file->storeAs('task_proofs', $filename, 'public');

        // Simpan submission
        TaskSubmission::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'tanggal' => $today,
            'jam_submit' => $timeStr,
            'status' => 'Selesai',
            'photo_proof' => $photoPath,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('jadwal.kerja.cs')->with('success', 'Laporan bukti pengerjaan "' . $task->title . '" berhasil dikirim pada ' . $timeStr . '!');
    }
}
