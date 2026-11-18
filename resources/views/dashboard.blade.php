<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Rekap Presensi & Penugasan Cleaning Service</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-800 antialiased">
<div class="min-h-screen md:flex">

    <!-- SIDEBAR -->
    <aside class="flex w-full flex-col bg-blue-900 text-white md:min-h-screen md:w-64 md:shrink-0">
        <div class="flex items-center gap-3 border-b border-blue-800 px-6 py-6">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-500 text-2xl text-white font-bold">
                🏢
            </div>
            <div>
                <h1 class="text-lg font-bold">Presensi CS</h1>
                <p class="text-xs text-blue-200">Presensi Cleaning Service • Monitoring</p>
            </div>
        </div>

        <nav class="flex flex-wrap gap-2 p-4 md:flex-1 md:flex-col">
            <p class="hidden px-3 pb-2 pt-3 text-xs font-semibold uppercase tracking-widest text-blue-300 md:block">
                Menu Administrator
            </p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 rounded-xl bg-blue-800 px-4 py-3 font-semibold text-white shadow-sm">
                <span>▦</span> Dashboard & Rekap
            </a>

            <a href="{{ route('admin.tasks.index') }}"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-800">
                <span>📋</span> Manajemen Penugasan
            </a>

            <a href="#monitoring"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-800">
                <span>◷</span> Data Presensi CS
            </a>

            <a href="#rekap-tugas"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-800">
                <span>📸</span> Laporan Bukti Tugas
            </a>

            <a href="#pengajuan-izin"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-800">
                <span>📝</span> Data Izin / Sakit
            </a>

            <a href="{{ route('profile.edit') }}"
               class="block rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-800">
                <span>👤</span> Profil Saya
            </a>

            <div class="mt-auto hidden rounded-2xl border border-blue-800 bg-blue-950 p-4 md:block">
                <p class="text-sm font-semibold">Sistem Presensi & Tugas</p>
                <p class="mt-1 text-xs leading-5 text-blue-300">
                    Monitoring kehadiran real-time & pengecekan fasilitas rutin.
                </p>
                <div class="mt-3 flex items-center gap-2 text-xs text-emerald-400">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Database Aktif (MariaDB)
                </div>
            </div>
        </nav>
    </aside>

    <!-- KONTEN UTAMA -->
    <div class="min-w-0 flex-1">

        <!-- HEADER -->
        <header class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 bg-white px-5 py-5 md:px-8">
            <div>
                <p class="text-sm text-slate-500">Sistem Informasi Presensi & Monitoring Cleaning Service</p>
                <h2 class="mt-1 text-xl font-bold text-slate-800 md:text-2xl">
                    Dashboard Monitoring Presensi & Penugasan
                </h2>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 rounded-xl p-2 transition hover:bg-slate-50">
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500">Administrator</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-orange-100 font-bold uppercase text-orange-600">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                        Keluar
                    </button>
                </form>
            </div>
        </header>

        <main class="mx-auto max-w-7xl space-y-7 p-5 md:p-8">

            <!-- FILTER TANGGAL & CETAK -->
            <section class="flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-white p-5 border border-slate-200 shadow-sm">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Filter Data Presensi & Penugasan</h3>
                    <p class="text-xs text-slate-500">Menampilkan rekaman tanggal: <strong class="text-blue-900">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}</strong></p>
                </div>

                <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2">
                        <label for="date" class="text-xs font-semibold text-slate-600">Pilih Tanggal:</label>
                        <input type="date" id="date" name="date" value="{{ $selectedDate }}"
                            class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <button type="submit"
                        class="rounded-xl bg-blue-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-900 shadow">
                        🔍 Tampilkan
                    </button>

                    <a href="{{ route('dashboard') }}"
                        class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Hari Ini
                    </a>

                    <a href="{{ route('admin.tasks.index') }}"
                        class="rounded-xl bg-blue-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-950 shadow">
                        📋 Kelola Penugasan
                    </a>

                    <button type="button" onclick="window.print()"
                        class="rounded-xl bg-orange-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-orange-600 shadow">
                        🖨️ Cetak Rekap
                    </button>
                </form>
            </section>

            <!-- STATISTIK REAL-TIME (PRESENSI & TUGAS) -->
            <section>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">

                    <!-- Total CS -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-lg text-blue-700 font-bold">
                                👥
                            </div>
                            <span class="text-[10px] font-bold uppercase text-slate-400">TOTAL CS</span>
                        </div>
                        <p class="mt-3 text-[11px] text-slate-500 font-semibold uppercase">Petugas Terdaftar</p>
                        <div class="mt-1 flex items-end justify-between">
                            <h4 class="text-2xl font-bold text-slate-800">{{ $totalCs }}</h4>
                            <span class="text-xs font-medium text-blue-700">Orang</span>
                        </div>
                    </div>

                    <!-- Hadir (Masuk) -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-lg text-emerald-700 font-bold">
                                🌅
                            </div>
                            <span class="text-[10px] font-bold uppercase text-emerald-600">MASUK</span>
                        </div>
                        <p class="mt-3 text-[11px] text-slate-500 font-semibold uppercase">Presensi Masuk</p>
                        <div class="mt-1 flex items-end justify-between">
                            <h4 class="text-2xl font-bold text-emerald-600">{{ $hadirCount }}</h4>
                            <span class="text-xs font-medium text-emerald-600">
                                {{ $totalCs > 0 ? round(($hadirCount / $totalCs) * 100) : 0 }}%
                            </span>
                        </div>
                    </div>

                    <!-- Selesai Pulang -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-100 text-lg text-indigo-700 font-bold">
                                🌇
                            </div>
                            <span class="text-[10px] font-bold uppercase text-indigo-600">PULANG</span>
                        </div>
                        <p class="mt-3 text-[11px] text-slate-500 font-semibold uppercase">Presensi Pulang</p>
                        <div class="mt-1 flex items-end justify-between">
                            <h4 class="text-2xl font-bold text-indigo-700">{{ $pulangCount }}</h4>
                            <span class="text-xs font-medium text-indigo-700">Petugas</span>
                        </div>
                    </div>

                    <!-- Izin / Sakit -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-100 text-lg text-purple-700 font-bold">
                                📝
                            </div>
                            <span class="text-[10px] font-bold uppercase text-purple-600">IZIN / SAKIT</span>
                        </div>
                        <p class="mt-3 text-[11px] text-slate-500 font-semibold uppercase">Ketidakhadiran</p>
                        <div class="mt-1 flex items-end justify-between">
                            <h4 class="text-2xl font-bold text-purple-700">{{ $izinCount }}</h4>
                            <span class="text-xs font-medium text-purple-700">Petugas</span>
                        </div>
                    </div>

                    <!-- Belum Hadir -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-lg text-amber-700 font-bold">
                                ⚠️
                            </div>
                            <span class="text-[10px] font-bold uppercase text-amber-700">BELUM MASUK</span>
                        </div>
                        <p class="mt-3 text-[11px] text-slate-500 font-semibold uppercase">Belum Absen</p>
                        <div class="mt-1 flex items-end justify-between">
                            <h4 class="text-2xl font-bold text-amber-700">{{ $belumHadirCount }}</h4>
                            <span class="text-xs font-medium text-amber-700">Petugas</span>
                        </div>
                    </div>

                    <!-- Tugas Selesai Hari Ini -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-teal-100 text-lg text-teal-700 font-bold">
                                📋
                            </div>
                            <span class="text-[10px] font-bold uppercase text-teal-600">TUGAS SELESAI</span>
                        </div>
                        <p class="mt-3 text-[11px] text-slate-500 font-semibold uppercase">Laporan Pengecekan</p>
                        <div class="mt-1 flex items-end justify-between">
                            <h4 class="text-2xl font-bold text-teal-700">{{ $completedTasksCount }}</h4>
                            <span class="text-xs font-medium text-teal-700">Laporan</span>
                        </div>
                    </div>

                </div>
            </section>

            <!-- TABEL MONITORING REKAP PRESENSI & PROGRES TUGAS REAL-TIME -->
            <section id="monitoring" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 p-5 md:p-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Rekap Kehadiran & Progres Penugasan Cleaning Service</h3>
                        <p class="mt-1 text-xs text-slate-500">
                            Sinkronisasi status presensi (WIB), validasi foto selfie, koordinat GPS, serta progres tugas harian.
                        </p>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">
                        ● Sinkronisasi Real-time
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[950px] text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-600 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Nama Petugas CS</th>
                                <th class="px-6 py-4 font-semibold">Tanggal</th>
                                <th class="px-6 py-4 font-semibold">Jam Masuk</th>
                                <th class="px-6 py-4 font-semibold">Jam Pulang</th>
                                <th class="px-6 py-4 font-semibold">Foto Selfie</th>
                                <th class="px-6 py-4 font-semibold">Status Kehadiran</th>
                                <th class="px-6 py-4 font-semibold text-center">Progres Tugas Hari Ini</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($rekapPetugas as $item)
                                @php
                                    $user = $item['user'];
                                    $att = $item['attendance'];
                                    $leave = $item['leave'];
                                    $tasksCompleted = $item['tasks_completed'];
                                    $totalTasks = $item['total_tasks'];
                                @endphp
                                <tr class="transition hover:bg-blue-50/40">
                                    <!-- Nama Petugas -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-orange-100 font-bold text-orange-700">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800">{{ $user->name }}</p>
                                                <p class="text-xs text-slate-400">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Tanggal -->
                                    <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d M Y') }}
                                    </td>

                                    <!-- Jam Masuk -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($leave)
                                            <span class="rounded-full bg-purple-50 px-2.5 py-1 text-xs font-bold text-purple-700">
                                                {{ $leave->kategori }}
                                            </span>
                                        @elseif ($att && $att->clock_in)
                                            <div class="flex items-center gap-1.5 text-emerald-700 font-bold font-mono">
                                                <span>🌅</span> {{ $att->clock_in }} WIB
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 italic">Belum absen</span>
                                        @endif
                                    </td>

                                    <!-- Jam Pulang -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($leave)
                                            <span class="text-xs text-purple-600 italic">Tidak bertugas</span>
                                        @elseif ($att && $att->clock_out)
                                            <div class="flex items-center gap-1.5 text-blue-700 font-bold font-mono">
                                                <span>🌇</span> {{ $att->clock_out }} WIB
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 italic">Belum absen</span>
                                        @endif
                                    </td>

                                    <!-- Foto Selfie -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            @if ($leave && $leave->lampiran_url)
                                                <button type="button" onclick="showPhotoModal('{{ $leave->lampiran_url }}', 'Surat/Bukti {{ $leave->kategori }} - {{ $user->name }}')"
                                                    class="rounded-lg bg-purple-50 px-2.5 py-1 text-xs font-bold text-purple-700 hover:bg-purple-100 border border-purple-200">
                                                    📄 Surat Bukti
                                                </button>
                                            @endif

                                            @if ($att && $att->foto_masuk_url)
                                                <button type="button" onclick="showPhotoModal('{{ $att->foto_masuk_url }}', 'Foto Selfie Masuk - {{ $user->name }}')"
                                                    class="group relative inline-block">
                                                    <img src="{{ $att->foto_masuk_url }}" alt="Foto Masuk"
                                                        class="h-9 w-9 rounded-lg object-cover border border-emerald-300 shadow-sm transition group-hover:scale-110">
                                                    <span class="absolute -bottom-1 -right-1 rounded bg-emerald-600 px-1 text-[8px] font-bold text-white">In</span>
                                                </button>
                                            @endif

                                            @if ($att && $att->foto_pulang_url)
                                                <button type="button" onclick="showPhotoModal('{{ $att->foto_pulang_url }}', 'Foto Selfie Pulang - {{ $user->name }}')"
                                                    class="group relative inline-block">
                                                    <img src="{{ $att->foto_pulang_url }}" alt="Foto Pulang"
                                                        class="h-9 w-9 rounded-lg object-cover border border-blue-300 shadow-sm transition group-hover:scale-110">
                                                    <span class="absolute -bottom-1 -right-1 rounded bg-blue-600 px-1 text-[8px] font-bold text-white">Out</span>
                                                </button>
                                            @endif

                                            @if ((!$att || (!$att->foto_masuk_url && !$att->foto_pulang_url)) && (!$leave || !$leave->lampiran_url))
                                                <span class="text-xs text-slate-400 italic">-</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Status Kehadiran -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($leave)
                                            <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-bold text-purple-800">
                                                📝 {{ $leave->kategori }}
                                            </span>
                                        @elseif ($att && $att->clock_out)
                                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-800">
                                                ✓ Selesai Pulang
                                            </span>
                                        @elseif ($att && $att->clock_in)
                                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">
                                                ✓ Hadir (Bertugas)
                                            </span>
                                        @else
                                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">
                                                ! Belum Hadir
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Progres Tugas Hari Ini -->
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="inline-flex items-center gap-2">
                                            <span class="rounded-xl px-3 py-1 text-xs font-bold
                                                {{ $tasksCompleted > 0 ? 'bg-teal-100 text-teal-800' : 'bg-slate-100 text-slate-600' }}">
                                                {{ $tasksCompleted }} Tugas Selesai
                                            </span>
                                            <a href="#rekap-tugas" class="text-xs text-blue-700 hover:underline">
                                                Lihat Foto ↗
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                                        Tidak ditemukan petugas Cleaning Service yang terdaftar di sistem.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- REKAPITULASI PENUGASAN & BUKTI FOTO PENGERJAAN RUANGAN -->
            <section id="rekap-tugas" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 p-5 md:p-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Galeri Bukti Pengerjaan Fasilitas & Ruangan</h3>
                        <p class="mt-1 text-xs text-slate-500">
                            Foto bukti hasil pengecekan rutin (toilet, ruang meeting zoom, lobby, dll.) pada tanggal terpilih.
                        </p>
                    </div>

                    <a href="{{ route('admin.tasks.index') }}"
                       class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-100 transition">
                        Kelola Jadwal Tugas →
                    </a>
                </div>

                <div class="p-6">
                    @if ($taskSubmissionsToday->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($taskSubmissionsToday as $sub)
                                <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50 hover:shadow-md transition">
                                    <div class="relative aspect-video bg-slate-900 cursor-pointer"
                                         onclick="showPhotoModal('{{ $sub->photo_proof_url }}', '{{ $sub->task->title ?? 'Tugas' }} - {{ $sub->user->name ?? 'CS' }}')">
                                        <img src="{{ $sub->photo_proof_url }}" alt="Bukti Pengerjaan" class="w-full h-full object-cover">
                                        <span class="absolute top-2 right-2 rounded-lg bg-emerald-600 px-2 py-0.5 text-[10px] font-bold text-white shadow">
                                            ✓ {{ $sub->status }}
                                        </span>
                                    </div>
                                    <div class="p-4">
                                        <h4 class="font-bold text-xs text-slate-800 truncate" title="{{ $sub->task->title ?? '-' }}">
                                            {{ $sub->task->title ?? 'Pengecekan Fasilitas' }}
                                        </h4>
                                        <p class="text-[11px] text-blue-900 font-semibold mt-1">
                                            👤 {{ $sub->user->name ?? 'Petugas CS' }}
                                        </p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">
                                            ⏰ {{ $sub->jam_submit }}
                                        </p>
                                        @if ($sub->notes)
                                            <p class="text-[11px] text-slate-600 bg-white p-2 rounded-lg border border-slate-200 mt-2 italic">
                                                "{{ $sub->notes }}"
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 text-center text-slate-400 text-xs">
                            Belum ada laporan pengerjaan tugas ruangan yang masuk pada tanggal {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}.
                        </div>
                    @endif
                </div>
            </section>

            <!-- TABEL PENGAJUAN KETIDAKHADIRAN (IZIN / SAKIT / MENDESAK) -->
            <section id="pengajuan-izin" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 p-5 md:p-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Daftar Pengajuan Ketidakhadiran (Izin / Sakit / Mendesak)</h3>
                        <p class="mt-1 text-xs text-slate-500">
                            Monitoring surat izin, sakit, dan alasan ketidakhadiran petugas Cleaning Service.
                        </p>
                    </div>
                    <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-bold text-purple-800">
                        {{ $allLeaveRequests->count() }} Pengajuan Terdata
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[750px] text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-600 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Nama Petugas</th>
                                <th class="px-6 py-4 font-semibold">Tanggal Izin</th>
                                <th class="px-6 py-4 font-semibold">Kategori</th>
                                <th class="px-6 py-4 font-semibold">Alasan / Keterangan</th>
                                <th class="px-6 py-4 font-semibold">Bukti Surat / Lampiran</th>
                                <th class="px-6 py-4 font-semibold">Waktu Pengajuan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($allLeaveRequests as $lreq)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 font-bold text-slate-800">
                                        {{ $lreq->user->name ?? 'Petugas' }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($lreq->date)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-bold
                                            {{ $lreq->kategori === 'Sakit' ? 'bg-red-100 text-red-800' : ($lreq->kategori === 'Izin' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ $lreq->kategori }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-700 max-w-sm">
                                        {{ $lreq->alasan }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($lreq->lampiran_url)
                                            <button type="button" onclick="showPhotoModal('{{ $lreq->lampiran_url }}', 'Surat Bukti {{ $lreq->kategori }} - {{ $lreq->user->name ?? '' }}')"
                                                class="rounded-xl bg-purple-50 px-3 py-1.5 text-xs font-semibold text-purple-700 hover:bg-purple-100 border border-purple-200">
                                                Lihat Bukti ↗
                                            </button>
                                        @else
                                            <span class="text-slate-400 italic text-xs">Tanpa lampiran</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500 whitespace-nowrap">
                                        {{ $lreq->created_at ? $lreq->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') : '-' }} WIB
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-xs">
                                        Belum ada data pengajuan ketidakhadiran dari petugas Cleaning Service.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <footer class="pb-3 text-center text-xs text-slate-400">
                © {{ date('Y') }} Sistem Absensi dan Monitoring Petugas Presensi Cleaning Service
            </footer>

        </main>
    </div>
</div>

<!-- MODAL PREVIEW FOTO / DOKUMEN BUKTI -->
<div id="photoModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/75 p-4" onclick="closePhotoModal()">
    <div class="relative max-w-lg w-full bg-white rounded-2xl overflow-hidden shadow-2xl" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
            <h4 id="photoModalTitle" class="font-bold text-slate-800 text-sm">Preview Lampiran</h4>
            <button type="button" onclick="closePhotoModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">×</button>
        </div>
        <div class="p-4 bg-slate-900 flex items-center justify-center">
            <img id="photoModalImg" src="" alt="Lampiran" class="max-h-[70vh] w-auto rounded-lg object-contain">
        </div>
        <div class="p-3 text-right bg-slate-50 border-t border-slate-200">
            <button type="button" onclick="closePhotoModal()" class="rounded-xl bg-slate-200 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-300">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function showPhotoModal(url, title) {
        document.getElementById('photoModalImg').src = url;
        document.getElementById('photoModalTitle').textContent = title || 'Preview Lampiran';
        document.getElementById('photoModal').classList.remove('hidden');
    }

    function closePhotoModal() {
        document.getElementById('photoModal').classList.add('hidden');
    }
</script>

</body>
</html>
