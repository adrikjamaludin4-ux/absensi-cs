<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | SAMSAT Ponorogo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-800 antialiased">
<div class="min-h-screen md:flex">

    <!-- SIDEBAR -->
    <aside class="flex w-full flex-col bg-blue-800 text-white md:min-h-screen md:w-64 md:shrink-0">
        <div class="flex items-center gap-3 border-b border-blue-700 px-6 py-6">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-2xl text-blue-800">
                🏢
            </div>
            <div>
                <h1 class="text-lg font-bold">SAMSAT</h1>
                <p class="text-xs text-blue-200">Ponorogo • Monitoring</p>
            </div>
        </div>

        <nav class="flex flex-wrap gap-2 p-4 md:flex-1 md:flex-col">
            <p class="hidden px-3 pb-2 pt-3 text-xs font-semibold uppercase tracking-widest text-blue-300 md:block">
                Menu Utama
            </p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 rounded-xl bg-white px-4 py-3 font-semibold text-blue-800 shadow-sm">
                <span>▦</span> Dashboard
            </a>

            <a href="#monitoring"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-700">
                <span>◷</span> Data Absensi
            </a>

            <a href="#monitoring"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-700">
                <span>♙</span> Data Petugas
            </a>

            <a href="#jadwal"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-700">
                <span>▤</span> Jadwal Kerja
            </a>

            <a href="#lokasi"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-700">
                <span>⌖</span> Lokasi Absensi
            </a>

            <a href="#laporan"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-700">
                <span>♧</span> Laporan
            </a>

            <!-- MENU PROFIL -->
            
<a href="{{ route('profile.edit') }}"
   class="block rounded-xl px-4 py-3 text-blue-100 hover:bg-blue-800">
    Profil Saya
</a>


            <div class="mt-auto hidden rounded-2xl border border-blue-700 bg-blue-900 p-4 md:block">
                <p class="text-sm font-semibold">Sistem Absensi</p>
                <p class="mt-1 text-xs leading-5 text-blue-200">
                    Monitoring kehadiran CS dan Satpam secara terpusat.
                </p>
                <div class="mt-3 flex items-center gap-2 text-xs text-blue-100">
                    <span class="h-2 w-2 rounded-full bg-yellow-400"></span>
                    Dashboard aktif
                </div>
            </div>
        </nav>
    </aside>

    <!-- KONTEN UTAMA -->
    <div class="min-w-0 flex-1">

        <!-- HEADER -->
        <header class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 bg-white px-5 py-5 md:px-8">
            <div>
                <p class="text-sm text-slate-500">Sistem Informasi Absensi</p>
                <h2 class="mt-1 text-xl font-bold text-slate-800 md:text-2xl">
                    Dashboard Monitoring
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
                        class="rounded-xl bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-800 transition hover:bg-blue-100">
                        Keluar
                    </button>
                </form>
            </div>
        </header>

        <main class="mx-auto max-w-7xl space-y-7 p-5 md:p-8">

            <!-- SAPAAN -->
            <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-700 to-blue-500 p-6 text-white shadow-lg md:p-8">
                <div class="relative z-10 max-w-2xl">
                    <p class="text-sm font-medium text-blue-100">
                        SISTEM MONITORING PETUGAS
                    </p>
                    <h3 class="mt-2 text-2xl font-bold md:text-3xl">
                        Selamat Datang, {{ auth()->user()->name }}!
                    </h3>
                    <p class="mt-3 text-sm leading-6 text-blue-100 md:text-base">
                        Pantau kehadiran, jadwal kerja, dan aktivitas petugas
                        Cleaning Service serta Satpam SAMSAT Ponorogo dalam satu dashboard.
                    </p>
                    <a href="#monitoring"
                       class="mt-5 inline-flex items-center gap-2 rounded-xl bg-orange-500 px-5 py-3 text-sm font-semibold text-white shadow transition hover:bg-orange-600">
                        Lihat Monitoring <span>→</span>
                    </a>
                </div>
                <div class="absolute -right-8 -top-12 h-52 w-52 rounded-full border-[28px] border-white/10"></div>
                <div class="absolute -bottom-20 right-24 h-48 w-48 rounded-full bg-yellow-400/20"></div>
            </section>

            <!-- STATISTIK -->
            <section>
                <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h3 class="text-lg font-bold">Ringkasan Kehadiran</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Gambaran data petugas hari ini
                        </p>
                    </div>
                    <span class="rounded-full bg-yellow-100 px-3 py-1.5 text-xs font-semibold text-yellow-800">
                        ● Data contoh
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-xl text-blue-700">♙</div>
                            <span class="text-xs font-medium text-slate-400">TOTAL</span>
                        </div>
                        <p class="mt-5 text-sm text-slate-500">Total Petugas</p>
                        <div class="mt-1 flex items-end justify-between">
                            <h4 class="text-3xl font-bold">32</h4>
                            <span class="text-sm font-medium text-blue-700">Orang</span>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-xl text-green-700">✓</div>
                            <span class="text-xs font-medium text-green-600">HADIR</span>
                        </div>
                        <p class="mt-5 text-sm text-slate-500">Petugas Hadir</p>
                        <div class="mt-1 flex items-end justify-between">
                            <h4 class="text-3xl font-bold">24</h4>
                            <span class="text-sm font-medium text-green-600">75%</span>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100 text-xl text-orange-600">◷</div>
                            <span class="text-xs font-medium text-orange-600">TERLAMBAT</span>
                        </div>
                        <p class="mt-5 text-sm text-slate-500">Terlambat</p>
                        <div class="mt-1 flex items-end justify-between">
                            <h4 class="text-3xl font-bold">3</h4>
                            <span class="text-sm font-medium text-orange-600">Petugas</span>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-100 text-xl text-yellow-700">!</div>
                            <span class="text-xs font-medium text-yellow-700">BELUM HADIR</span>
                        </div>
                        <p class="mt-5 text-sm text-slate-500">Belum Absen</p>
                        <div class="mt-1 flex items-end justify-between">
                            <h4 class="text-3xl font-bold">5</h4>
                            <span class="text-sm font-medium text-yellow-700">Petugas</span>
                        </div>
                    </div>

                </div>
            </section>

            <!-- MONITORING PETUGAS -->
            <section id="monitoring" class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 p-5 md:p-6">
                    <div>
                        <h3 class="text-lg font-bold">Monitoring Petugas</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Contoh status kehadiran petugas
                        </p>
                    </div>
                    <button type="button" onclick="window.print()"
                        class="rounded-xl bg-orange-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-orange-600">
                        Cetak Halaman
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[650px] text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Nama Petugas</th>
                                <th class="px-6 py-4 font-semibold">Jabatan</th>
                                <th class="px-6 py-4 font-semibold">Jam Masuk</th>
                                <th class="px-6 py-4 font-semibold">Lokasi</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="transition hover:bg-blue-50/50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">BS</div>
                                        <div>
                                            <p class="font-semibold text-slate-800">Budi Santoso</p>
                                            <p class="mt-1 text-xs text-slate-400">PET-001</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">Satpam</td>
                                <td class="px-6 py-4 font-medium">07:45</td>
                                <td class="px-6 py-4 text-slate-600">Area Samsat</td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-700">Hadir</span>
                                </td>
                            </tr>

                            <tr class="transition hover:bg-blue-50/50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-orange-100 font-bold text-orange-700">SW</div>
                                        <div>
                                            <p class="font-semibold text-slate-800">Siti Wulandari</p>
                                            <p class="mt-1 text-xs text-slate-400">PET-002</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">Cleaning Service</td>
                                <td class="px-6 py-4 font-medium">08:12</td>
                                <td class="px-6 py-4 text-slate-600">Area Samsat</td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full bg-yellow-100 px-3 py-1.5 text-xs font-semibold text-yellow-800">Terlambat</span>
                                </td>
                            </tr>

                            <tr class="transition hover:bg-blue-50/50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">AR</div>
                                        <div>
                                            <p class="font-semibold text-slate-800">Andi Rahmat</p>
                                            <p class="mt-1 text-xs text-slate-400">PET-003</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">Satpam</td>
                                <td class="px-6 py-4 text-slate-400">Belum absen</td>
                                <td class="px-6 py-4 text-slate-400">Belum tercatat</td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full bg-orange-100 px-3 py-1.5 text-xs font-semibold text-orange-700">Belum Hadir</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-100 px-6 py-4 text-xs text-slate-400">
                    Data di atas hanya contoh tampilan dan belum terhubung ke database.
                </div>
            </section>

            <!-- INFORMASI TAMBAHAN -->
            <section id="jadwal" class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div id="lokasi" class="flex items-start gap-4 rounded-2xl border border-blue-100 bg-blue-50 p-5">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-xl text-white">⌖</div>
                    <div>
                        <h4 class="font-bold text-blue-900">Validasi Lokasi GPS</h4>
                        <p class="mt-1 text-sm leading-6 text-blue-800">
                            Absensi direncanakan hanya dapat dilakukan dalam radius lokasi yang ditentukan.
                        </p>
                    </div>
                </div>

                <div id="laporan" class="flex items-start gap-4 rounded-2xl border border-yellow-200 bg-yellow-50 p-5">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-yellow-400 text-xl text-yellow-950">!</div>
                    <div>
                        <h4 class="font-bold text-yellow-900">Pengingat Kehadiran</h4>
                        <p class="mt-1 text-sm leading-6 text-yellow-800">
                            Pengingat jadwal dan notifikasi keterlambatan akan dikembangkan pada tahap berikutnya.
                        </p>
                    </div>
                </div>
            </section>

            <footer class="pb-3 text-center text-xs text-slate-400">
                © 2026 Sistem Absensi dan Monitoring Petugas SAMSAT Ponorogo
            </footer>

        </main>
    </div>
</div>
</body>
</html>
