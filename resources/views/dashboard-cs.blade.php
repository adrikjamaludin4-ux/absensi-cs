<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard CS | SAMSAT Ponorogo</title>
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
                        S
                    </div>

                    <div>
                        <h1 class="font-bold tracking-wide">SAMSAT</h1>
                        <p class="text-xs text-blue-200">PONOROGO</p>
                    </div>
                </div>
            </div>

            <!-- Menu -->
            <div class="px-5 py-6">
                <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-blue-300">
                    Menu Customer Service
                </p>

                <nav class="space-y-2">

                    <a href="{{ route('dashboard.cs') }}"
                        class="flex items-center gap-3 rounded-xl bg-blue-800 px-4 py-3 font-medium text-white">
                        <span>▦</span>
                        Dashboard
                    </a>

                    <a href="{{ route('absensi.cs') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>◷</span>
                        Absensi
                    </a>

                    <a href="{{ route('jadwal.kerja.cs') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>▤</span>
                        Jadwal Kerja
                    </a>

                    <a href="#riwayat"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>▤</span>
                        Riwayat Absensi
                    </a>

                    <!-- Profil Saya -->
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>👤</span>
                        Profil Saya
                    </a>

                </nav>
            </div>

            <!-- Informasi Sidebar -->
            <div class="mt-auto p-5">
                <div class="rounded-2xl border border-blue-800 bg-blue-900/60 p-4">
                    <p class="text-sm font-semibold">Pelayanan Terbaik!</p>
                    <p class="mt-1 text-xs leading-5 text-blue-200">
                        Berikan pelayanan yang ramah, cepat, dan profesional kepada masyarakat.
                    </p>
                </div>
            </div>

        </aside>

        <!-- KONTEN UTAMA -->
        <main class="min-w-0 flex-1">

            <!-- HEADER -->
            <header
                class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 bg-white px-5 py-5 md:px-8">

                <div>
                    <p class="text-sm text-slate-500">
                        Sistem Informasi Absensi
                    </p>

                    <h2 class="mt-1 text-xl font-bold text-blue-950 md:text-2xl">
                        Dashboard Customer Service
                    </h2>
                </div>

                <div class="flex items-center gap-3">

                    <!-- Nama pengguna menuju profil -->
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 rounded-xl p-2 transition hover:bg-blue-50">

                        <div class="hidden text-right sm:block">
                            <p class="text-sm font-semibold text-blue-950">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="text-xs text-slate-500">
                                Customer Service
                            </p>
                        </div>

                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-full bg-orange-100 font-bold uppercase text-orange-700">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                    </a>

                    <!-- Tombol Keluar -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                            class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                            Keluar
                        </button>
                    </form>

                </div>
            </header>

            <!-- ISI DASHBOARD -->
            <div class="space-y-6 p-5 md:p-8">

                <!-- SAMBUTAN -->
                <section
                    class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 p-6 text-white shadow-lg md:p-8">

                    <div class="relative z-10 max-w-2xl">

                        <span
                            class="inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-semibold text-orange-200">
                            AREA CUSTOMER SERVICE
                        </span>

                        <h3 class="mt-4 text-2xl font-bold md:text-3xl">
                            Selamat bertugas, {{ auth()->user()->name }}!
                        </h3>

                        <p class="mt-3 max-w-xl text-sm leading-6 text-blue-100 md:text-base">
                            Pantau jadwal kerja, lakukan absensi sesuai lokasi,
                            dan berikan pelayanan terbaik kepada masyarakat di SAMSAT Ponorogo.
                        </p>

                        <a href="{{ route('absensi.cs') }}"
                            class="mt-5 inline-flex rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white shadow transition hover:bg-orange-600">
                            Buka Menu Absensi →
                        </a>

                    </div>

                    <div class="absolute -right-10 -top-16 h-64 w-64 rounded-full border-[35px] border-white/5"></div>
                    <div class="absolute -bottom-24 right-24 h-48 w-48 rounded-full bg-orange-500/10"></div>

                </section>

                <!-- KARTU INFORMASI -->
                <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

                    <!-- Status Kehadiran -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-500">
                                Status Kehadiran
                            </p>

                            <span class="rounded-xl bg-blue-100 p-3 text-xl">
                                ◷
                            </span>
                        </div>

                        <h3 class="mt-4 text-xl font-bold text-blue-950">
                            Belum Absen
                        </h3>

                        <p class="mt-1 text-xs text-slate-500">
                            Status akan diperbarui setelah absensi tercatat.
                        </p>

                    </div>

                    <!-- Jadwal Shift -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-500">
                                Jadwal Shift
                            </p>

                            <span class="rounded-xl bg-orange-100 p-3 text-xl">
                                ▣
                            </span>
                        </div>

                        <h3 class="mt-4 text-xl font-bold text-blue-950">
                            Belum Diatur
                        </h3>

                        <p class="mt-1 text-xs text-slate-500">
                            Jadwal akan ditampilkan setelah ditentukan Admin.
                        </p>

                    </div>

                    <!-- Validasi Lokasi -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:col-span-2 xl:col-span-1">

                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-500">
                                Validasi Lokasi
                            </p>

                            <span class="rounded-xl bg-yellow-100 p-3 text-xl">
                                ⌖
                            </span>
                        </div>

                        <h3 class="mt-4 text-xl font-bold text-blue-950">
                            Belum Diperiksa
                        </h3>

                        <p class="mt-1 text-xs text-slate-500">
                            Lokasi akan diperiksa saat proses absensi.
                        </p>

                    </div>

                </section>

                <!-- ABSENSI -->
                <section id="absensi" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                    <div class="flex flex-wrap items-center justify-between gap-3">

                        <div>
                            <h3 class="text-lg font-bold text-blue-950">
                                Absensi Hari Ini
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Pastikan berada di area kerja sebelum melakukan absensi.
                            </p>
                        </div>

                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                            GPS & Selfie Aktif
                        </span>

                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2">

                        <!-- Absen Masuk -->
                        <div class="rounded-2xl border border-slate-200 p-5 flex flex-col justify-between">

                            <div class="flex items-center gap-3">
                                <div class="rounded-xl bg-blue-100 p-3 text-xl">
                                    ↘
                                </div>
                                <div>
                                    <h4 class="font-bold">
                                        Absen Masuk / Pulang
                                    </h4>
                                    <p class="text-sm text-slate-500">
                                        Catat kehadiran dengan verifikasi lokasi & foto.
                                    </p>
                                </div>
                            </div>

                            <a href="{{ route('absensi.cs') }}"
                                class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-blue-800 px-4 py-3 text-sm font-bold text-white transition hover:bg-blue-900">
                                <span>📍</span>
                                Buka Menu Verifikasi Absensi
                            </a>

                        </div>

                        <!-- Informasi Panduan Singkat -->
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 flex flex-col justify-center">
                            <h4 class="font-bold text-slate-800 mb-1">Ketentuan Absensi:</h4>
                            <ul class="text-xs text-slate-600 space-y-1 list-disc list-inside">
                                <li>Berada dalam radius maksimal 50 meter dari kantor SAMSAT Ponorogo.</li>
                                <li>Akurasi GPS perangkat memenuhi batas toleransi.</li>
                                <li>Wajib mengambil foto selfie langsung menggunakan kamera depan.</li>
                            </ul>
                        </div>

                    </div>

                </section>

                <!-- RIWAYAT ABSENSI -->
                <section id="riwayat" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                    <h3 class="text-lg font-bold text-blue-950">
                        Riwayat Absensi
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Ringkasan kehadiran Customer Service.
                    </p>

                    <div class="mt-5 overflow-x-auto">

                        <table class="w-full min-w-[500px] text-left text-sm">

                            <thead>
                                <tr class="border-b border-slate-200 text-slate-500">
                                    <th class="px-4 py-3 font-semibold">Tanggal</th>
                                    <th class="px-4 py-3 font-semibold">Jam Masuk</th>
                                    <th class="px-4 py-3 font-semibold">Jam Pulang</th>
                                    <th class="px-4 py-3 font-semibold">Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-500">
                                        Belum ada data riwayat absensi.
                                    </td>
                                </tr>
                            </tbody>

                        </table>

                    </div>

                </section>

                <!-- FOOTER -->
                <footer class="pb-3 text-center text-xs text-slate-500">
                    © {{ date('Y') }} SAMSAT Ponorogo ·
                    Sistem Informasi Absensi dan Monitoring Petugas
                </footer>

            </div>

        </main>

    </div>

</body>

</html>
```