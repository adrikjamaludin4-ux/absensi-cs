<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AttendanceLocationService;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceLocationController extends Controller
{
    /**
     * Tampilan Dashboard Petugas Cleaning Service.
     */
    public function dashboardCs(AttendanceLocationService $locationService)
    {
        $user = auth()->user();
        $timeStatus = $locationService->getTimeStatusForUser($user->id);

        $history = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->take(10)
            ->get();

        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        return view('dashboard-cs', compact('timeStatus', 'history', 'leaveRequests'));
    }

    /**
     * Tampilan Halaman Absensi GPS & Foto Selfie CS.
     */
    public function absensiCs(AttendanceLocationService $locationService)
    {
        $user = auth()->user();
        $timeStatus = $locationService->getTimeStatusForUser($user->id);

        return view('absensi-cs', compact('timeStatus'));
    }

    /**
     * Tampilan Form Pengajuan Ketidakhadiran (Izin, Sakit, Keperluan Mendesak).
     */
    public function ajukanIzinView()
    {
        $user = auth()->user();
        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get();

        return view('ajukan-izin-cs', compact('leaveRequests'));
    }

    /**
     * Menyimpan Data Pengajuan Ketidakhadiran CS.
     */
    public function ajukanIzinStore(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'kategori' => ['required', 'in:Izin,Sakit,Keperluan Mendesak'],
            'alasan' => ['required', 'string', 'max:1000'],
            'lampiran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:3072'],
        ], [
            'date.required' => 'Tanggal ketidakhadiran wajib dipilih.',
            'kategori.required' => 'Kategori ketidakhadiran wajib dipilih.',
            'alasan.required' => 'Alasan wajib diisi.',
            'lampiran.max' => 'Ukuran file lampiran maksimal 3 MB.',
            'lampiran.mimes' => 'Format file lampiran harus berupa JPG, PNG, atau PDF.',
        ]);

        $user = auth()->user();

        // Cek apakah sudah mengajukan untuk tanggal yang sama
        $exists = LeaveRequest::where('user_id', $user->id)
            ->where('date', $validated['date'])
            ->first();

        if ($exists) {
            return back()->with('error', 'Anda sudah pernah mengajukan ketidakhadiran untuk tanggal tersebut.');
        }

        // Upload lampiran jika ada
        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $filename = 'lampiran_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $lampiranPath = $file->storeAs('lampiran_izin', $filename, 'public');
        }

        // Simpan ke tabel leave_requests
        $leaveRequest = LeaveRequest::create([
            'user_id' => $user->id,
            'date' => $validated['date'],
            'kategori' => $validated['kategori'],
            'alasan' => $validated['alasan'],
            'lampiran' => $lampiranPath,
            'status' => 'Disetujui',
        ]);

        // Rekam atau sinkronkan juga ke tabel attendances agar tercatat di rekap
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $validated['date'])
            ->first();

        if ($attendance) {
            $attendance->update([
                'status' => strtolower($validated['kategori']),
                'status_masuk' => $validated['kategori'],
            ]);
        } else {
            Attendance::create([
                'user_id' => $user->id,
                'date' => $validated['date'],
                'status' => strtolower($validated['kategori']),
                'status_masuk' => $validated['kategori'],
            ]);
        }

        return redirect()->route('dashboard.cs')->with('success', 'Pengajuan ketidakhadiran (' . $validated['kategori'] . ') berhasil dikirim dan dicatat!');
    }

    /**
     * Memproses Pengiriman Absensi GPS dan Foto Selfie (Masuk & Pulang).
     */
    public function checkLocation(
        Request $request,
        AttendanceLocationService $locationService
    ) {
        // 1. Validasi data GPS dan Foto Selfie dari browser
        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['required', 'numeric', 'min:0'],
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $user = auth()->user();

        // 2. Cek status batasan jam presensi CS
        $timeStatus = $locationService->getTimeStatusForUser($user->id);

        if (!$timeStatus['can_action']) {
            return response()->json([
                'success' => false,
                'message' => $timeStatus['banner_message'] ?: 'Waktu presensi tidak diperbolehkan saat ini.',
            ], 422);
        }

        // 3. Periksa radius lokasi kantor menggunakan Haversine formula
        $result = $locationService->checkLocation(
            (float) $validated['latitude'],
            (float) $validated['longitude'],
            (float) $validated['accuracy']
        );

        if (!$result['valid']) {
            return response()->json([
                'success' => false,
                'distance' => $result['distance'],
                'message' => $result['message'],
            ], 422);
        }

        // 4. Simpan foto selfie ke storage public/attendances
        $action = $timeStatus['action_type']; // 'masuk' atau 'pulang'
        $photo = $request->file('photo');
        $filename = "cs_{$user->id}_{$action}_" . time() . '.' . $photo->getClientOriginalExtension();
        $photoPath = $photo->storeAs('attendances', $filename, 'public');

        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
        $nowTime = $now->toTimeString();
        $coordinateText = $validated['latitude'] . ', ' . $validated['longitude'];

        if ($action === 'masuk') {
            // Simpan Absen Masuk baru
            Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'clock_in' => $nowTime,
                'foto_masuk' => $photoPath,
                'lokasi_masuk' => $coordinateText,
                'status_masuk' => 'Hadir',
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'accuracy' => $validated['accuracy'],
                'distance' => $result['distance'],
                'photo_path' => $photoPath,
                'status_face' => 'Verified (Selfie)',
                'status' => 'hadir',
            ]);

            $message = 'Presensi Masuk berhasil direkam pada pukul ' . substr($nowTime, 0, 5) . ' WIB!';
        } else {
            // Update Absen Pulang
            $attendance = $timeStatus['attendance'];
            if ($attendance) {
                $attendance->update([
                    'clock_out' => $nowTime,
                    'foto_pulang' => $photoPath,
                    'lokasi_pulang' => $coordinateText,
                    'status_pulang' => 'Pulang',
                ]);
            } else {
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $today,
                    'clock_out' => $nowTime,
                    'foto_pulang' => $photoPath,
                    'lokasi_pulang' => $coordinateText,
                    'status_masuk' => 'Tidak Hadir Pagi',
                    'status_pulang' => 'Pulang',
                    'latitude' => $validated['latitude'],
                    'longitude' => $validated['longitude'],
                    'accuracy' => $validated['accuracy'],
                    'distance' => $result['distance'],
                    'photo_path' => $photoPath,
                    'status_face' => 'Verified (Selfie)',
                    'status' => 'hadir',
                ]);
            }

            $message = 'Presensi Pulang berhasil direkam pada pukul ' . substr($nowTime, 0, 5) . ' WIB!';
        }

        return response()->json([
            'success' => true,
            'action' => $action,
            'distance' => $result['distance'],
            'message' => $message,
        ], 200);
    }
}
