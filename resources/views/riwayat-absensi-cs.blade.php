<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Absensi | Presensi Cleaning Service</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-800">

    <div class="flex min-h-screen">

        <!-- ═══════════════════════════════════════════════════════════
             SIDEBAR
        ════════════════════════════════════════════════════════════ -->
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
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>&#9670;</span> Dashboard
                    </a>

                    <a href="{{ route('absensi.cs') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>&#9655;</span> Absensi
                    </a>

                    <a href="{{ route('izin.cs') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>&#128203;</span> Ajukan Izin / Sakit
                    </a>

                    <a href="{{ route('jadwal.kerja.cs') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>&#9644;</span> Jadwal & Penugasan
                    </a>

                    <!-- ACTIVE: Riwayat Absensi -->
                    <a href="{{ route('riwayat.absensi.cs') }}"
                        class="flex items-center gap-3 rounded-xl bg-blue-800 px-4 py-3 font-medium text-white">
                        <span>&#128197;</span> Riwayat Absensi
                    </a>

                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>&#128100;</span> Profil Saya
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

        <!-- ═══════════════════════════════════════════════════════════
             KONTEN UTAMA
        ════════════════════════════════════════════════════════════ -->
        <main class="min-w-0 flex-1">

            <!-- HEADER -->
            <header class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 bg-white px-5 py-5 md:px-8">
                <div>
                    <p class="text-sm text-slate-500">Sistem Informasi Presensi Cleaning Service</p>
                    <h2 class="mt-1 text-xl font-bold text-blue-950 md:text-2xl">
                        📅 Riwayat Presensi Saya
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

            <!-- ISI HALAMAN -->
            <div class="space-y-6 p-5 md:p-8">

                <!-- ── STATISTIK RINGKASAN BULAN INI ─────────────────────── -->
                <section>
                    <h3 class="mb-4 text-base font-bold text-slate-700">
                        Ringkasan Kehadiran &mdash;
                        {{ \Carbon\Carbon::createFromDate($filterTahun, $filterBulan, 1)->translatedFormat('F Y') }}
                    </h3>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">

                        <!-- Total Hadir -->
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 text-center shadow-sm">
                            <p class="text-3xl font-black text-emerald-600">{{ $totalHadir }}</p>
                            <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-emerald-700">Hari Hadir</p>
                        </div>

                        <!-- Total Terlambat -->
                        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-center shadow-sm">
                            <p class="text-3xl font-black text-amber-600">{{ $totalTerlambat }}</p>
                            <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-amber-700">Terlambat</p>
                        </div>

                        <!-- Total Izin / Sakit -->
                        <div class="rounded-2xl border border-purple-200 bg-purple-50 p-5 text-center shadow-sm">
                            <p class="text-3xl font-black text-purple-600">{{ $totalIzin }}</p>
                            <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-purple-700">Izin / Sakit</p>
                        </div>

                        <!-- Total Alpha -->
                        <div class="rounded-2xl border border-red-200 bg-red-50 p-5 text-center shadow-sm">
                            <p class="text-3xl font-black text-red-500">{{ $totalAlpha }}</p>
                            <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-red-600">Alpha / Tidak Tercatat</p>
                        </div>

                    </div>
                </section>

                <!-- ── FILTER PENCARIAN ───────────────────────────────────── -->
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <form method="GET" action="{{ route('riwayat.absensi.cs') }}"
                          class="flex flex-wrap items-end gap-4">

                        <!-- Filter Bulan -->
                        <div class="flex-1 min-w-[140px]">
                            <label class="mb-1 block text-xs font-semibold text-slate-600 uppercase tracking-wider">Bulan</label>
                            <select name="bulan"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                @foreach(range(1, 12) as $bln)
                                    <option value="{{ $bln }}" @selected($bln == $filterBulan)>
                                        {{ \Carbon\Carbon::createFromDate(null, $bln, 1)->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Tahun -->
                        <div class="flex-1 min-w-[110px]">
                            <label class="mb-1 block text-xs font-semibold text-slate-600 uppercase tracking-wider">Tahun</label>
                            <select name="tahun"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                @foreach($tahunOptions as $thn)
                                    <option value="{{ $thn }}" @selected($thn == $filterTahun)>{{ $thn }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Status -->
                        <div class="flex-1 min-w-[160px]">
                            <label class="mb-1 block text-xs font-semibold text-slate-600 uppercase tracking-wider">Status Kehadiran</label>
                            <select name="status"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                <option value="semua"     @selected($filterStatus === 'semua')>Semua Status</option>
                                <option value="hadir"     @selected($filterStatus === 'hadir')>✅ Hadir</option>
                                <option value="terlambat" @selected($filterStatus === 'terlambat')>⚠️ Terlambat</option>
                                <option value="izin"      @selected($filterStatus === 'izin')>📋 Izin</option>
                                <option value="sakit"     @selected($filterStatus === 'sakit')>🏥 Sakit</option>
                                <option value="mendesak"  @selected($filterStatus === 'mendesak')>🚨 Keperluan Mendesak</option>
                                <option value="alpha"     @selected($filterStatus === 'alpha')>❌ Alpha</option>
                            </select>
                        </div>

                        <!-- Tombol Terapkan -->
                        <div>
                            <button type="submit"
                                    class="rounded-xl bg-blue-900 px-6 py-2.5 text-sm font-bold text-white shadow transition hover:bg-blue-950">
                                Terapkan Filter
                            </button>
                        </div>

                        <!-- Tombol Reset -->
                        <div>
                            <a href="{{ route('riwayat.absensi.cs') }}"
                               class="inline-block rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                                Reset
                            </a>
                        </div>

                    </form>
                </section>

                <!-- ── TABEL RIWAYAT LENGKAP ──────────────────────────────── -->
                <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-4">
                        <div>
                            <h3 class="font-bold text-blue-950">Daftar Riwayat Presensi</h3>
                            <p class="mt-0.5 text-xs text-slate-500">
                                Menampilkan {{ $riwayat->firstItem() ?? 0 }}–{{ $riwayat->lastItem() ?? 0 }}
                                dari {{ $riwayat->total() }} catatan
                            </p>
                        </div>
                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                            {{ \Carbon\Carbon::createFromDate($filterTahun, $filterBulan, 1)->translatedFormat('F Y') }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[700px] text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50 text-slate-600">
                                    <th class="px-5 py-3.5 font-semibold">Tanggal & Hari</th>
                                    <th class="px-5 py-3.5 font-semibold">Jam Masuk</th>
                                    <th class="px-5 py-3.5 font-semibold">Jam Pulang</th>
                                    <th class="px-5 py-3.5 font-semibold">Foto Selfie</th>
                                    <th class="px-5 py-3.5 font-semibold">Lokasi GPS</th>
                                    <th class="px-5 py-3.5 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">

                                @forelse ($riwayat as $row)
                                    <tr class="transition hover:bg-slate-50">

                                        {{-- Tanggal & Hari --}}
                                        <td class="px-5 py-4">
                                            <p class="font-semibold text-slate-800">
                                                {{ \Carbon\Carbon::parse($row->date)->translatedFormat('d F Y') }}
                                            </p>
                                            <p class="text-xs text-slate-400">
                                                {{ \Carbon\Carbon::parse($row->date)->translatedFormat('l') }}
                                            </p>
                                        </td>

                                        {{-- Jam Masuk + Badge Status --}}
                                        <td class="px-5 py-4">
                                            @if ($row->clock_in)
                                                <p class="font-bold text-emerald-600">
                                                    {{ substr($row->clock_in, 0, 5) }} WIB
                                                </p>
                                                @if ($row->status_masuk === 'Terlambat')
                                                    <span class="mt-1 inline-block rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">
                                                        ⚠️ Terlambat
                                                    </span>
                                                @elseif ($row->status_masuk === 'Hadir')
                                                    <span class="mt-1 inline-block rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800">
                                                        ✅ Tepat Waktu
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>

                                        {{-- Jam Pulang --}}
                                        <td class="px-5 py-4">
                                            @if ($row->clock_out)
                                                <p class="font-bold text-blue-600">
                                                    {{ substr($row->clock_out, 0, 5) }} WIB
                                                </p>
                                                @if ($row->status_pulang)
                                                    <span class="mt-1 inline-block rounded-full bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-800">
                                                        {{ $row->status_pulang }}
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>

                                        {{-- Foto Selfie (klik untuk preview modal) --}}
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-2">
                                                @if ($row->foto_masuk_url)
                                                    <button type="button"
                                                            onclick="openPhotoModal('{{ $row->foto_masuk_url }}', 'Foto Masuk — {{ \Carbon\Carbon::parse($row->date)->translatedFormat("d F Y") }}')"
                                                            class="group relative overflow-hidden rounded-xl border-2 border-emerald-300 hover:border-emerald-500 transition">
                                                        <img src="{{ $row->foto_masuk_url }}"
                                                             alt="Foto Masuk"
                                                             class="h-12 w-12 object-cover">
                                                        <span class="absolute inset-0 flex items-center justify-center bg-emerald-900/60 opacity-0 group-hover:opacity-100 transition text-xs font-bold text-white">
                                                            Masuk
                                                        </span>
                                                    </button>
                                                @endif

                                                @if ($row->foto_pulang_url)
                                                    <button type="button"
                                                            onclick="openPhotoModal('{{ $row->foto_pulang_url }}', 'Foto Pulang — {{ \Carbon\Carbon::parse($row->date)->translatedFormat("d F Y") }}')"
                                                            class="group relative overflow-hidden rounded-xl border-2 border-blue-300 hover:border-blue-500 transition">
                                                        <img src="{{ $row->foto_pulang_url }}"
                                                             alt="Foto Pulang"
                                                             class="h-12 w-12 object-cover">
                                                        <span class="absolute inset-0 flex items-center justify-center bg-blue-900/60 opacity-0 group-hover:opacity-100 transition text-xs font-bold text-white">
                                                            Pulang
                                                        </span>
                                                    </button>
                                                @endif

                                                @if (!$row->foto_masuk_url && !$row->foto_pulang_url)
                                                    <span class="text-xs text-slate-400">Tidak ada foto</span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Lokasi GPS --}}
                                        <td class="px-5 py-4">
                                            @if ($row->lokasi_masuk)
                                                <a href="https://www.google.com/maps?q={{ urlencode($row->lokasi_masuk) }}"
                                                   target="_blank"
                                                   class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition">
                                                    📍 Masuk
                                                </a>
                                            @endif
                                            @if ($row->lokasi_pulang)
                                                <a href="https://www.google.com/maps?q={{ urlencode($row->lokasi_pulang) }}"
                                                   target="_blank"
                                                   class="mt-1 inline-flex items-center gap-1 rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition">
                                                    📍 Pulang
                                                </a>
                                            @endif
                                            @if (!$row->lokasi_masuk && !$row->lokasi_pulang)
                                                @if ($row->distance !== null)
                                                    <span class="text-xs text-slate-500">{{ number_format($row->distance, 0) }} m dari kantor</span>
                                                @else
                                                    <span class="text-xs text-slate-400">—</span>
                                                @endif
                                            @endif
                                        </td>

                                        {{-- Status Kehadiran Akhir --}}
                                        <td class="px-5 py-4">
                                            @php
                                                $st = $row->status_masuk ?? $row->status ?? '';
                                                $badgeClass = match(true) {
                                                    in_array($st, ['Hadir'])                      => 'bg-emerald-100 text-emerald-800',
                                                    in_array($st, ['Terlambat'])                  => 'bg-amber-100 text-amber-800',
                                                    in_array($st, ['Izin', 'izin'])               => 'bg-purple-100 text-purple-800',
                                                    in_array($st, ['Sakit', 'sakit'])             => 'bg-blue-100 text-blue-800',
                                                    in_array($st, ['Keperluan Mendesak','mendesak']) => 'bg-red-100 text-red-800',
                                                    default                                        => 'bg-slate-100 text-slate-600',
                                                };
                                                $icon = match(true) {
                                                    in_array($st, ['Hadir'])                       => '✅',
                                                    in_array($st, ['Terlambat'])                   => '⚠️',
                                                    in_array($st, ['Izin', 'izin'])                => '📋',
                                                    in_array($st, ['Sakit', 'sakit'])              => '🏥',
                                                    in_array($st, ['Keperluan Mendesak','mendesak']) => '🚨',
                                                    default                                         => '❓',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                                {{ $icon }} {{ $st ?: 'Alpha' }}
                                            </span>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-12 text-center">
                                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                                <span class="text-5xl">📭</span>
                                                <p class="font-semibold">Belum ada catatan presensi untuk periode ini.</p>
                                                <p class="text-sm">Coba ubah filter bulan atau tahun di atas.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    @if ($riwayat->hasPages())
                        <div class="border-t border-slate-100 px-6 py-4">
                            {{ $riwayat->links() }}
                        </div>
                    @endif

                </section>

                <!-- FOOTER -->
                <footer class="pb-3 text-center text-xs text-slate-500">
                    &copy; {{ date('Y') }} Presensi Cleaning Service &middot; Sistem Informasi Absensi &amp; Monitoring Petugas
                </footer>

            </div>
        </main>
    </div>

    <!-- ════════════════════════════════════════════════════════════════
         MODAL PREVIEW FOTO SELFIE
    ═══════════════════════════════════════════════════════════════════ -->
    <div id="photo-modal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-4 backdrop-blur-sm"
         onclick="closePhotoModal(event)">

        <div class="relative w-full max-w-lg rounded-3xl bg-white shadow-2xl overflow-hidden"
             onclick="event.stopPropagation()">

            <!-- Header Modal -->
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h4 id="modal-title" class="font-bold text-blue-950 text-sm truncate pr-4"></h4>
                <button onclick="closePhotoModal()"
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 transition text-lg font-bold">
                    &times;
                </button>
            </div>

            <!-- Gambar -->
            <div class="bg-slate-900 flex items-center justify-center min-h-[300px] max-h-[70vh] overflow-hidden">
                <img id="modal-img" src="" alt="Foto Selfie Presensi"
                     class="max-h-[70vh] w-full object-contain">
            </div>

            <!-- Footer Modal -->
            <div class="flex items-center justify-between border-t border-slate-100 px-6 py-3">
                <p class="text-xs text-slate-400">Foto Selfie Presensi Cleaning Service</p>
                <a id="modal-download" href="#" target="_blank"
                   class="rounded-xl bg-blue-900 px-4 py-2 text-xs font-bold text-white hover:bg-blue-950 transition">
                    Buka Full ↗
                </a>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════════════
         SCRIPTS
    ═══════════════════════════════════════════════════════════════════ -->
    <script>
        // ── Jam Realtime Sidebar ──────────────────────────────────────
        function updateClock() {
            const el = document.getElementById('live-sidebar-clock');
            if (el) {
                const now = new Date();
                const pad = n => String(n).padStart(2, '0');
                el.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
            }
        }
        setInterval(updateClock, 1000);

        // ── Modal Foto Selfie ─────────────────────────────────────────
        function openPhotoModal(url, title) {
            document.getElementById('modal-img').src = url;
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-download').href = url;
            const modal = document.getElementById('photo-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closePhotoModal(event) {
            if (event && event.target !== document.getElementById('photo-modal')) return;
            const modal = document.getElementById('photo-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Tutup modal dengan tombol Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('photo-modal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        });
    </script>

</body>

</html>
