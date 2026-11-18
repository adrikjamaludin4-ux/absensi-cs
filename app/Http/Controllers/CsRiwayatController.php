<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Carbon\Carbon;

class CsRiwayatController extends Controller
{
    /**
     * Halaman Riwayat Presensi Cleaning Service.
     * Menampilkan statistik bulanan, filter bulan/status, tabel paginasi.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $now  = Carbon::now('Asia/Jakarta');

        // ── Filter Bulan & Tahun ──────────────────────────────────────────
        $filterBulan = $request->input('bulan', $now->month);
        $filterTahun = $request->input('tahun', $now->year);
        $filterStatus = $request->input('status', 'semua'); // semua|hadir|terlambat|izin|sakit|mendesak|alpha

        // ── Query Dasar: semua absensi milik user yang login ─────────────
        $query = Attendance::where('user_id', $user->id)
            ->whereYear('date', $filterTahun)
            ->whereMonth('date', $filterBulan)
            ->orderBy('date', 'desc');

        // ── Terapkan Filter Status ────────────────────────────────────────
        if ($filterStatus !== 'semua') {
            $statusMap = [
                'hadir'     => ['Hadir'],
                'terlambat' => ['Terlambat'],
                'izin'      => ['Izin', 'izin'],
                'sakit'     => ['Sakit', 'sakit'],
                'mendesak'  => ['Keperluan Mendesak', 'mendesak'],
                'alpha'     => [null, ''],
            ];

            if ($filterStatus === 'alpha') {
                // Alpha = tidak ada clock_in & bukan izin/sakit
                $query->whereNull('clock_in')
                      ->whereNotIn('status_masuk', ['Izin', 'Sakit', 'Keperluan Mendesak'])
                      ->whereNotIn('status', ['izin', 'sakit', 'mendesak']);
            } elseif (isset($statusMap[$filterStatus])) {
                $query->where(function ($q) use ($statusMap, $filterStatus) {
                    $q->whereIn('status_masuk', $statusMap[$filterStatus])
                      ->orWhereIn('status', $statusMap[$filterStatus]);
                });
            }
        }

        // ── Paginasi 10 baris per halaman ─────────────────────────────────
        $riwayat = $query->paginate(10)->withQueryString();

        // ── Statistik Bulan Berjalan ──────────────────────────────────────
        $allBulanIni = Attendance::where('user_id', $user->id)
            ->whereYear('date', $filterTahun)
            ->whereMonth('date', $filterBulan)
            ->get();

        $totalHadir     = $allBulanIni->whereIn('status_masuk', ['Hadir'])->count()
                        + $allBulanIni->where('status', 'hadir')->whereIn('status_masuk', ['Hadir'])->count();
        // deduplicate: gunakan kombinasi status_masuk
        $totalHadir     = $allBulanIni->filter(fn($r) =>
                            in_array($r->status_masuk, ['Hadir']) ||
                            ($r->clock_in && !in_array($r->status_masuk, ['Terlambat', 'Izin', 'Sakit', 'Keperluan Mendesak']))
                          )->count();
        $totalTerlambat = $allBulanIni->where('status_masuk', 'Terlambat')->count();
        $totalIzin      = $allBulanIni->filter(fn($r) =>
                            in_array($r->status_masuk, ['Izin', 'Sakit', 'Keperluan Mendesak']) ||
                            in_array($r->status, ['izin', 'sakit', 'mendesak'])
                          )->count();
        $totalAlpha     = $allBulanIni->filter(fn($r) =>
                            !$r->clock_in &&
                            !in_array($r->status_masuk, ['Izin', 'Sakit', 'Keperluan Mendesak']) &&
                            !in_array($r->status, ['izin', 'sakit', 'mendesak'])
                          )->count();

        // ── Ambil Pengajuan Izin Bulan Ini ───────────────────────────────
        $leavesBulanIni = LeaveRequest::where('user_id', $user->id)
            ->whereYear('date', $filterTahun)
            ->whereMonth('date', $filterBulan)
            ->get();

        // ── Daftar Tahun untuk Dropdown Filter ───────────────────────────
        $firstYear = Attendance::where('user_id', $user->id)->min('date');
        $startYear = $firstYear ? Carbon::parse($firstYear)->year : $now->year;
        $tahunOptions = range($startYear, $now->year);

        return view('riwayat-absensi-cs', compact(
            'riwayat',
            'filterBulan',
            'filterTahun',
            'filterStatus',
            'totalHadir',
            'totalTerlambat',
            'totalIzin',
            'totalAlpha',
            'leavesBulanIni',
            'tahunOptions',
        ));
    }
}
