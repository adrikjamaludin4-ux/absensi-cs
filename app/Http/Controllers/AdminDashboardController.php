<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\User;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Rekap Presensi, Ketidakhadiran, dan Penugasan Rutin Petugas Cleaning Service.
     */
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', Carbon::now('Asia/Jakarta')->toDateString());

        // Ambil seluruh user dengan role Cleaning Service (cs)
        $csUsers = User::where('role', 'cs')->get();
        $totalCs = $csUsers->count();

        // 1. Data Absensi
        $attendances = Attendance::with('user')
            ->whereHas('user', function ($q) {
                $q->where('role', 'cs');
            })
            ->where('date', $selectedDate)
            ->orderBy('clock_in', 'asc')
            ->get();

        // 2. Data Pengajuan Izin/Ketidakhadiran
        $leaveRequests = LeaveRequest::with('user')
            ->where('date', $selectedDate)
            ->get();

        $allLeaveRequests = LeaveRequest::with('user')
            ->orderBy('date', 'desc')
            ->take(20)
            ->get();

        // 3. Data Penugasan & Pengecekan Rutin
        $tasks = Task::with('assignedUser')->where('is_active', true)->get();
        $totalActiveTasks = $tasks->count();

        $taskSubmissionsToday = TaskSubmission::with(['task', 'user'])
            ->where('tanggal', $selectedDate)
            ->latest()
            ->get();

        $completedTasksCount = $taskSubmissionsToday->count();

        // Statistik Presensi Real-time
        $hadirCount = $attendances->whereNotNull('clock_in')->count();
        $pulangCount = $attendances->whereNotNull('clock_out')->count();
        $izinCount = $leaveRequests->count();
        $terlambatCount = $attendances->where('status_masuk', 'Terlambat')->count();
        $belumHadirCount = max(0, $totalCs - $hadirCount - $izinCount);

        // Rekap per Petugas (Presensi + Progres Tugas Rutin)
        $userAttendanceMap = $attendances->keyBy('user_id');
        $userLeaveMap = $leaveRequests->keyBy('user_id');
        $userSubmissionsGrouped = $taskSubmissionsToday->groupBy('user_id');

        $rekapPetugas = $csUsers->map(function ($user) use ($userAttendanceMap, $userLeaveMap, $userSubmissionsGrouped, $totalActiveTasks) {
            $att = $userAttendanceMap->get($user->id);
            $leave = $userLeaveMap->get($user->id);
            $userDoneTasks = $userSubmissionsGrouped->get($user->id, collect())->count();

            return [
                'user' => $user,
                'attendance' => $att,
                'leave' => $leave,
                'has_clock_in' => $att && !empty($att->clock_in),
                'has_clock_out' => $att && !empty($att->clock_out),
                'tasks_completed' => $userDoneTasks,
                'total_tasks' => $totalActiveTasks,
            ];
        });

        return view('dashboard', compact(
            'selectedDate',
            'attendances',
            'leaveRequests',
            'allLeaveRequests',
            'totalCs',
            'hadirCount',
            'pulangCount',
            'izinCount',
            'terlambatCount',
            'belumHadirCount',
            'rekapPetugas',
            'tasks',
            'totalActiveTasks',
            'taskSubmissionsToday',
            'completedTasksCount'
        ));
    }
}
