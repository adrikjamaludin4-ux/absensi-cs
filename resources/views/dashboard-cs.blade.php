<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard CS | Presensi Cleaning Service</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-800">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside class="hidden w-64 shrink-0 flex-col bg-blue-950 text-white md:flex">

            <!-- Logo -->
            <div class="border-b border-blue-900 px-6 py-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-500 text-xl font-bold">
                        CS
                    </div>
                    <div>
                        <h1 class="font-bold tracking-wide">Presensi CS</h1>
                        <p class="text-xs text-blue-200">Presensi Cleaning Service</p>
                    </div>
                </div>
            </div>

            <!-- Menu -->
            <div class="px-5 py-6">
                <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-blue-300">
                    Menu Cleaning Service
                </p>

                <nav class="space-y-2">
                    <a href="{{ route('dashboard.cs') }}"
                        class="flex items-center gap-3 rounded-xl bg-blue-800 px-4 py-3 font-medium text-white">
                        <span>▦</span> Dashboard
                    </a>

                    <a href="{{ route('absensi.cs') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>◷</span> Absensi
                    </a>

                    <a href="{{ route('izin.cs') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>📝</span> Ajukan Izin / Sakit
                    </a>

                    <a href="{{ route('jadwal.kerja.cs') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>▤</span> Jadwal Kerja
                    </a>

                    <a href="{{ route('riwayat.absensi.cs') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>&#128197;</span> Riwayat Absensi
                    </a>

                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>👤</span> Profil Saya
                    </a>
                </nav>
            </div>

            <!-- Jam Digital WIB di Sidebar -->
            <div class="mt-auto p-5">
                <div class="rounded-2xl border border-blue-800 bg-blue-900/60 p-4 text-center">
                    <p class="text-xs text-blue-300 uppercase tracking-widest font-semibold">Waktu Server (WIB)</p>
                    <p id="live-sidebar-clock" class="mt-1 text-2xl font-mono font-bold text-orange-400">
                        {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s') }}
                    </p>
                    <p class="mt-1 text-xs text-blue-200">
                        {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d M Y') }}
                    </p>
                </div>
            </div>

        </aside>

        <!-- KONTEN UTAMA -->
        <main class="min-w-0 flex-1">

            <!-- HEADER -->
            <header class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 bg-white px-5 py-5 md:px-8">
                <div>
                    <p class="text-sm text-slate-500">Sistem Informasi Presensi Cleaning Service</p>
                    <h2 class="mt-1 text-xl font-bold text-blue-950 md:text-2xl">
                        Dashboard Cleaning Service
                    </h2>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-xl p-2 transition hover:bg-blue-50">
                        <div class="hidden text-right sm:block">
                            <p class="text-sm font-semibold text-blue-950">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500">Petugas Cleaning Service</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-orange-100 font-bold uppercase text-orange-700">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                            Keluar
                        </button>
                    </form>
                </div>
            </header>

            <!-- ISI DASHBOARD -->
            <div class="space-y-6 p-5 md:p-8">

                <!-- NOTIFIKASI SUCCESS / ERROR -->
                @if (session('success'))
                    <div class="rounded-2xl bg-emerald-100 p-4 border border-emerald-200 text-emerald-800 font-semibold text-sm flex items-center gap-3">
                        <span class="text-xl">✅</span>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                <!-- BANNER NOTIFIKASI DINAMIS BERDASARKAN WAKTU SEKARANG (WIB) -->
                @if ($timeStatus['banner_type'] === 'active')
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4 rounded-3xl bg-gradient-to-r from-emerald-600 to-teal-700 p-6 text-white shadow-lg">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-3xl">
                                ⏰
                            </div>
                            <div>
                                <span class="rounded-full bg-emerald-400/30 px-3 py-1 text-xs font-bold uppercase tracking-wider text-emerald-100">
                                    WAKTU PRESENSI AKTIF (WIB)
                                </span>
                                <h3 class="mt-1 text-xl font-bold md:text-2xl">{{ $timeStatus['banner_title'] }}</h3>
                                <p class="text-sm text-emerald-100 mt-1">{{ $timeStatus['banner_message'] }}</p>
                            </div>
                        </div>
                        <a href="{{ route('absensi.cs') }}"
                            class="shrink-0 rounded-2xl bg-white px-6 py-3.5 text-sm font-bold text-emerald-800 shadow-md transition hover:bg-emerald-50 hover:scale-105">
                            {{ $timeStatus['action_type'] === 'masuk' ? 'Presensi Masuk Sekarang →' : 'Presensi Pulang Sekarang →' }}
                        </a>
                    </div>
                @elseif ($timeStatus['banner_type'] === 'leave')
                    <div class="flex items-center gap-4 rounded-3xl bg-gradient-to-r from-purple-700 to-indigo-800 p-6 text-white shadow-lg">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-3xl">
                            📝
                        </div>
                        <div>
                            <span class="rounded-full bg-purple-300/30 px-3 py-1 text-xs font-bold uppercase tracking-wider text-purple-100">
                                PENGAJUAN KETIDAKHADIRAN TERCATAT
                            </span>
                            <h3 class="mt-1 text-xl font-bold">{{ $timeStatus['banner_title'] }}</h3>
                            <p class="text-sm text-purple-100 mt-1">{{ $timeStatus['banner_message'] }}</p>
                        </div>
                    </div>
                @elseif ($timeStatus['banner_type'] === 'success')
                    <div class="flex items-center gap-4 rounded-3xl bg-gradient-to-r from-blue-700 to-indigo-800 p-6 text-white shadow-lg">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-3xl">
                            ✅
                        </div>
                        <div>
                            <span class="rounded-full bg-blue-300/30 px-3 py-1 text-xs font-bold uppercase tracking-wider text-blue-100">
                                PRESENSI TERCATAT
                            </span>
                            <h3 class="mt-1 text-xl font-bold">{{ $timeStatus['banner_title'] }}</h3>
                            <p class="text-sm text-blue-100 mt-1">{{ $timeStatus['banner_message'] }}</p>
                        </div>
                    </div>
                @elseif ($timeStatus['banner_type'] === 'waiting')
                    <div class="flex items-center gap-4 rounded-3xl bg-gradient-to-r from-amber-500 to-orange-600 p-6 text-white shadow-lg">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-3xl">
                            ⏳
                        </div>
                        <div>
                            <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-bold uppercase tracking-wider text-amber-100">
                                INFORMASI JADWAL
                            </span>
                            <h3 class="mt-1 text-xl font-bold">{{ $timeStatus['banner_title'] }}</h3>
                            <p class="text-sm text-amber-100 mt-1">{{ $timeStatus['banner_message'] }}</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-4 rounded-3xl bg-gradient-to-r from-slate-700 to-slate-800 p-6 text-white shadow-lg">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-3xl">
                            🔒
                        </div>
                        <div>
                            <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-bold uppercase tracking-wider text-slate-300">
                                DI LUAR JAM PRESENSI
                            </span>
                            <h3 class="mt-1 text-xl font-bold">{{ $timeStatus['banner_title'] }}</h3>
                            <p class="text-sm text-slate-200 mt-1">{{ $timeStatus['banner_message'] }}</p>
                        </div>
                    </div>
                @endif

                <!-- KARTU INFORMASI STATUS REAL-TIME -->
                <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                    <!-- Jam Masuk -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Jam Masuk (05.00-05.30)</p>
                            <span class="rounded-xl bg-blue-50 p-2 text-lg">🌅</span>
                        </div>
                        <h3 class="mt-3 text-2xl font-bold {{ $timeStatus['attendance'] && $timeStatus['attendance']->clock_in ? 'text-emerald-600' : 'text-slate-700' }}">
                            {{ $timeStatus['attendance'] && $timeStatus['attendance']->clock_in ? substr($timeStatus['attendance']->clock_in, 0, 5) . ' WIB' : 'Belum Absen' }}
                        </h3>
                        <p class="mt-1 text-xs text-slate-500">
                            Status: <span class="font-semibold">{{ $timeStatus['attendance']->status_masuk ?? ($timeStatus['leave_request'] ? $timeStatus['leave_request']->kategori : 'Belum Hadir') }}</span>
                        </p>
                    </div>

                    <!-- Jam Pulang -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Jam Pulang (15.00-18.00)</p>
                            <span class="rounded-xl bg-orange-50 p-2 text-lg">🌇</span>
                        </div>
                        <h3 class="mt-3 text-2xl font-bold {{ $timeStatus['attendance'] && $timeStatus['attendance']->clock_out ? 'text-blue-600' : 'text-slate-700' }}">
                            {{ $timeStatus['attendance'] && $timeStatus['attendance']->clock_out ? substr($timeStatus['attendance']->clock_out, 0, 5) . ' WIB' : 'Belum Pulang' }}
                        </h3>
                        <p class="mt-1 text-xs text-slate-500">
                            Status: <span class="font-semibold">{{ $timeStatus['attendance']->status_pulang ?? '-' }}</span>
                        </p>
                    </div>

                    <!-- Shift Kerja CS -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Jadwal Shift CS</p>
                            <span class="rounded-xl bg-purple-50 p-2 text-lg">📋</span>
                        </div>
                        <h3 class="mt-3 text-lg font-bold text-blue-950">
                            Shift Pagi - Sore
                        </h3>
                        <p class="mt-1 text-xs text-slate-500">
                            Masuk: 05.00-05.30 | Pulang: 15.00-18.00
                        </p>
                    </div>

                    <!-- Validasi GPS & Radius -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Radius Presensi</p>
                            <span class="rounded-xl bg-emerald-50 p-2 text-lg">📍</span>
                        </div>
                        <h3 class="mt-3 text-lg font-bold text-blue-950">
                            Maks. {{ config('attendance.radius', 50) }} Meter
                        </h3>
                        <p class="mt-1 text-xs text-slate-500">
                            Wajib GPS Akurat & Foto Selfie
                        </p>
                    </div>

                </section>

                <!-- AKSI PRESENSI CEPAT & PENGAJUAN IZIN -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-blue-950">
                                Menu Aksi Petugas Cleaning Service
                            </h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Lakukan presensi saat jam operasional atau ajukan ketidakhadiran jika berhalangan.
                            </p>
                        </div>

                        <span class="rounded-full {{ $timeStatus['can_action'] ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }} px-3 py-1 text-xs font-bold">
                            {{ $timeStatus['can_action'] ? '● Pintu Presensi Terbuka' : '○ Pintu Presensi Ditutup' }}
                        </span>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2">

                        <!-- Kartu Presensi Kamera -->
                        <div class="rounded-2xl border {{ $timeStatus['can_action'] ? 'border-emerald-200 bg-emerald-50/40' : 'border-slate-200 bg-slate-50' }} p-5 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-3">
                                    <div class="rounded-xl {{ $timeStatus['can_action'] ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-500' }} p-3 text-2xl">
                                        📸
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900">
                                            @if ($timeStatus['action_type'] === 'masuk')
                                                Presensi Masuk (05.00 - 05.30 WIB)
                                            @elseif ($timeStatus['action_type'] === 'pulang')
                                                Presensi Pulang (15.00 - 18.00 WIB)
                                            @elseif ($timeStatus['action_type'] === 'menunggu_pulang')
                                                Presensi Pulang (Dibuka 15.00 WIB)
                                            @else
                                                Presensi Hari Ini Selesai
                                            @endif
                                        </h4>
                                        <p class="text-sm text-slate-500">
                                            Verifikasi GPS radius lokasi dan ambil foto selfie langsung.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if ($timeStatus['can_action'])
                                <a href="{{ route('absensi.cs') }}"
                                    class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3.5 text-sm font-bold text-white shadow-md transition hover:bg-emerald-700">
                                    <span>📍</span>
                                    Buka Kamera & Lakukan {{ $timeStatus['action_type'] === 'masuk' ? 'Presensi Masuk' : 'Presensi Pulang' }}
                                </a>
                            @else
                                <div class="mt-6">
                                    <button disabled
                                        class="flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-xl bg-slate-300 px-5 py-3.5 text-sm font-bold text-slate-600">
                                        <span>🔒</span>
                                        Presensi Tidak Tersedia Saat Ini
                                    </button>
                                    <p class="mt-2 text-center text-xs text-slate-500">
                                        {{ $timeStatus['banner_message'] }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Kartu Ajukan Izin / Sakit -->
                        <div class="rounded-2xl border border-slate-200 bg-blue-50/50 p-5 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-3">
                                    <div class="rounded-xl bg-blue-100 text-blue-700 p-3 text-2xl">
                                        📝
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900">
                                            Ajukan Izin / Sakit / Mendesak
                                        </h4>
                                        <p class="text-sm text-slate-500">
                                            Berhalangan hadir bertugas? Kirimkan permohonan izin kepada Admin.
                                        </p>
                                    </div>
                                </div>
                                <ul class="mt-4 text-xs text-slate-600 space-y-1.5 list-disc list-inside">
                                    <li>Pilihan kategori: Izin, Sakit, atau Keperluan Mendesak.</li>
                                    <li>Lampirkan surat dokter jika sakit (format foto/PDF).</li>
                                    <li>Status izin akan langsung terdata pada dashboard Admin.</li>
                                </ul>
                            </div>

                            <a href="{{ route('izin.cs') }}"
                                class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-blue-900 px-5 py-3.5 text-sm font-bold text-white shadow-md transition hover:bg-blue-950">
                                <span>📄</span>
                                Isi Formulir Ketidakhadiran
                            </a>
                        </div>

                    </div>
                </section>

                <!-- LINK CEPAT KE RIWAYAT ABSENSI -->
                <section class="rounded-2xl border border-blue-100 bg-blue-50 p-5 flex items-center justify-between gap-4 shadow-sm">
                    <div>
                        <h3 class="font-bold text-blue-950">📅 Riwayat Presensi Anda</h3>
                        <p class="mt-1 text-sm text-slate-500">Lihat seluruh catatan kehadiran, filter per bulan, dan preview foto selfie.</p>
                    </div>
                    <a href="{{ route('riwayat.absensi.cs') }}"
                       class="shrink-0 rounded-xl bg-blue-900 px-5 py-2.5 text-sm font-bold text-white shadow transition hover:bg-blue-950">
                        Lihat Riwayat →
                    </a>
                </section>

                <!-- FOOTER -->
                <footer class="pb-3 text-center text-xs text-slate-500">
                    © {{ date('Y') }} Presensi Cleaning Service · Sistem Informasi Absensi & Monitoring Petugas
                </footer>

            </div>
        </main>
    </div>

    <!-- Script Jam Digital Realtime -->
    <script>
        function updateClock() {
            const clockEl = document.getElementById('live-sidebar-clock');
            if (clockEl) {
                const now = new Date();
                const h = String(now.getHours()).padStart(2, '0');
                const m = String(now.getMinutes()).padStart(2, '0');
                const s = String(now.getSeconds()).padStart(2, '0');
                clockEl.textContent = `${h}:${m}:${s}`;
            }
        }
        setInterval(updateClock, 1000);
    </script>
</body>

</html>
