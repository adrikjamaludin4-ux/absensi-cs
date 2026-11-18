<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Rekap Presensi Cleaning Service</title>
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

            <a href="#monitoring"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-800">
                <span>◷</span> Data Presensi CS
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
                <p class="text-sm font-semibold">Sistem Presensi</p>
                <p class="mt-1 text-xs leading-5 text-blue-300">
                    Monitoring kehadiran real-time petugas Cleaning Service.
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
                <p class="text-sm text-slate-500">Sistem Informasi Presensi Cleaning Service</p>
                <h2 class="mt-1 text-xl font-bold text-slate-800 md:text-2xl">
                    Dashboard Rekap Petugas Cleaning Service
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
                    <h3 class="text-base font-bold text-slate-800">Filter Data Presensi & Ketidakhadiran</h3>
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

                    <button type="button" onclick="window.print()"
                        class="rounded-xl bg-orange-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-orange-600 shadow">
                        🖨️ Cetak Rekap
                    </button>
                </form>
            </section>

            <!-- STATISTIK REAL-TIME -->
            <section>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">

                    <!-- Total CS -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-xl text-blue-700 font-bold">
                                👥
                            </div>
                            <span class="text-xs font-semibold uppercase text-slate-400">TOTAL CS</span>
                        </div>
                        <p class="mt-4 text-xs text-slate-500 font-semibold uppercase">Total Petugas CS</p>
                        <div class="mt-1 flex items-end justify-between">
                            <h4 class="text-3xl font-bold text-slate-800">{{ $totalCs }}</h4>
                            <span class="text-xs font-medium text-blue-700">Petugas</span>
                        </div>
                    </div>

                    <!-- Hadir (Masuk) -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-xl text-emerald-700 font-bold">
                                🌅
                            </div>
                            <span class="text-xs font-semibold uppercase text-emerald-600">SUDAH MASUK</span>
                        </div>
                        <p class="mt-4 text-xs text-slate-500 font-semibold uppercase">Presensi Masuk</p>
                        <div class="mt-1 flex items-end justify-between">
                            <h4 class="text-3xl font-bold text-emerald-600">{{ $hadirCount }}</h4>
                            <span class="text-xs font-medium text-emerald-600">
                                {{ $totalCs > 0 ? round(($hadirCount / $totalCs) * 100) : 0 }}%
                            </span>
                        </div>
                    </div>

                    <!-- Selesai Pulang -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-xl text-indigo-700 font-bold">
                                🌇
                            </div>
                            <span class="text-xs font-semibold uppercase text-indigo-600">SUDAH PULANG</span>
                        </div>
                        <p class="mt-4 text-xs text-slate-500 font-semibold uppercase">Presensi Pulang</p>
                        <div class="mt-1 flex items-end justify-between">
                            <h4 class="text-3xl font-bold text-indigo-700">{{ $pulangCount }}</h4>
                            <span class="text-xs font-medium text-indigo-700">Petugas</span>
                        </div>
                    </div>

                    <!-- Izin / Sakit -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-xl text-purple-700 font-bold">
                                📝
                            </div>
                            <span class="text-xs font-semibold uppercase text-purple-600">IZIN / SAKIT</span>
                        </div>
                        <p class="mt-4 text-xs text-slate-500 font-semibold uppercase">Ketidakhadiran</p>
                        <div class="mt-1 flex items-end justify-between">
                            <h4 class="text-3xl font-bold text-purple-700">{{ $izinCount }}</h4>
                            <span class="text-xs font-medium text-purple-700">Petugas</span>
                        </div>
                    </div>

                    <!-- Belum Hadir -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-xl text-amber-700 font-bold">
                                ⚠️
                            </div>
                            <span class="text-xs font-semibold uppercase text-amber-700">BELUM MASUK</span>
                        </div>
                        <p class="mt-4 text-xs text-slate-500 font-semibold uppercase">Belum Absen</p>
                        <div class="mt-1 flex items-end justify-between">
                            <h4 class="text-3xl font-bold text-amber-700">{{ $belumHadirCount }}</h4>
                            <span class="text-xs font-medium text-amber-700">Petugas</span>
                        </div>
                    </div>

                </div>
            </section>

            <!-- TABEL MONITORING REKAP REAL-TIME -->
            <section id="monitoring" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 p-5 md:p-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Rekap Riwayat Presensi Cleaning Service</h3>
                        <p class="mt-1 text-xs text-slate-500">
                            Data real-time jam masuk (05.00-05.30 WIB), jam pulang (15.00-18.00 WIB), verifikasi lokasi GPS & foto selfie.
                        </p>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">
                        ● Sinkronisasi Database Real-time
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[850px] text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-600 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Nama Petugas CS</th>
                                <th class="px-6 py-4 font-semibold">Tanggal</th>
                                <th class="px-6 py-4 font-semibold">Jam Masuk</th>
                                <th class="px-6 py-4 font-semibold">Jam Pulang</th>
                                <th class="px-6 py-4 font-semibold">Validasi Foto Selfie</th>
                                <th class="px-6 py-4 font-semibold">Validasi Lokasi (GPS)</th>
                                <th class="px-6 py-4 font-semibold">Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($rekapPetugas as $item)
                                @php
                                    $user = $item['user'];
                                    $att = $item['attendance'];
                                    $leave = $item['leave'];
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

                                    <!-- Validasi Foto Selfie -->
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
                                                        class="h-10 w-10 rounded-lg object-cover border border-emerald-300 shadow-sm transition group-hover:scale-110">
                                                    <span class="absolute -bottom-1 -right-1 rounded bg-emerald-600 px-1 text-[9px] font-bold text-white">In</span>
                                                </button>
                                            @endif

                                            @if ($att && $att->foto_pulang_url)
                                                <button type="button" onclick="showPhotoModal('{{ $att->foto_pulang_url }}', 'Foto Selfie Pulang - {{ $user->name }}')"
                                                    class="group relative inline-block">
                                                    <img src="{{ $att->foto_pulang_url }}" alt="Foto Pulang"
                                                        class="h-10 w-10 rounded-lg object-cover border border-blue-300 shadow-sm transition group-hover:scale-110">
                                                    <span class="absolute -bottom-1 -right-1 rounded bg-blue-600 px-1 text-[9px] font-bold text-white">Out</span>
                                                </button>
                                            @endif

                                            @if ((!$att || (!$att->foto_masuk_url && !$att->foto_pulang_url)) && (!$leave || !$leave->lampiran_url))
                                                <span class="text-xs text-slate-400 italic">-</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Validasi Lokasi (GPS) -->
                                    <td class="px-6 py-4 text-xs">
                                        @if ($leave)
                                            <p class="text-xs text-purple-700 max-w-xs truncate" title="{{ $leave->alasan }}">
                                                Alasan: {{ $leave->alasan }}
                                            </p>
                                        @elseif ($att && ($att->lokasi_masuk || $att->latitude))
                                            <div>
                                                <p class="font-mono text-slate-700 font-semibold">
                                                    📍 {{ $att->lokasi_masuk ?: ($att->latitude . ', ' . $att->longitude) }}
                                                </p>
                                                @if ($att->distance !== null)
                                                    <p class="text-[11px] text-emerald-700">
                                                        Jarak: {{ round((float) $att->distance, 1) }}m dari area kantor
                                                    </p>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic">Belum ada lokasi</span>
                                        @endif
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
                                                ✓ Hadir (Sedang Bertugas)
                                            </span>
                                        @else
                                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">
                                                ! Belum Hadir
                                            </span>
                                        @endif
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

                <div class="border-t border-slate-100 px-6 py-4 flex flex-wrap items-center justify-between text-xs text-slate-500">
                    <p>Total {{ $totalCs }} petugas terdaftar.</p>
                    <p>Waktu server: {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s') }} WIB</p>
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
