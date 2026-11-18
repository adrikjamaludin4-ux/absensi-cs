<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Kerja | SAMSAT Ponorogo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-800">

@php
    $user = auth()->user();
@endphp

<div class="min-h-screen flex">

    <!-- SIDEBAR -->
    
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
            Menu Cleaning Service
        </p>

        <nav class="space-y-2">

            <a href="{{ route('dashboard.cs') }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                <span>▦</span>
                Dashboard
            </a>

            <a href="{{ route('absensi.cs') }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                <span>◷</span>
                Absensi
            </a>

            <a href="{{ route('jadwal.kerja.cs') }}"
                class="flex items-center gap-3 rounded-xl bg-orange-500 px-4 py-3 font-medium text-white">
                <span>▤</span>
                Jadwal Kerja
            </a>

            <a href="#riwayat"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                <span>▤</span>
                Riwayat Absensi
            </a>

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
    <main class="flex-1 min-w-0">

        <!-- HEADER -->
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center">
            <div>
                <h2 class="font-bold text-lg">Jadwal Kerja</h2>
                <p class="text-xs text-slate-500">Sistem Absensi SAMSAT Ponorogo</p>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm font-bold">{{ $user->name }}</p>
                    <p class="text-xs text-slate-500">Cleaning Service</p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="rounded-lg bg-red-50 px-3 py-2 text-sm font-semibold text-red-600 hover:bg-red-100">
                        Keluar
                    </button>
                </form>
            </div>
        </header>

        <div class="p-6 md:p-8">

            <!-- JUDUL -->
            <div class="mb-8">
                <p class="text-sm font-semibold text-orange-500">INFORMASI PETUGAS</p>
                <h1 class="text-3xl font-bold text-blue-950 mt-2">
                    Jadwal Kerja Saya
                </h1>
                <p class="text-slate-500 mt-2">
                    Lihat informasi hari dan jam kerja Cleaning Service.
                </p>
            </div>

            <!-- INFORMASI JADWAL -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl">
                        ◷
                    </div>
                    <p class="text-sm text-slate-500 mt-4">Jam Masuk</p>
                    <h2 class="text-2xl font-bold text-blue-950 mt-1">07.30 WIB</h2>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl">
                        ◷
                    </div>
                    <p class="text-sm text-slate-500 mt-4">Jam Pulang</p>
                    <h2 class="text-2xl font-bold text-blue-950 mt-1">15.00 WIB</h2>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                    <div class="w-12 h-12 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center text-2xl">
                        ▤
                    </div>
                    <p class="text-sm text-slate-500 mt-4">Hari Kerja</p>
                    <h2 class="text-2xl font-bold text-blue-950 mt-1">Senin - Jumat</h2>
                </div>

            </div>

            <!-- TABEL JADWAL -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-lg font-bold text-blue-950">Rincian Jadwal Mingguan</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Informasi jadwal kerja petugas Cleaning Service.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-blue-950 text-white">
                            <tr>
                                <th class="px-6 py-4">Hari</th>
                                <th class="px-6 py-4">Jam Masuk</th>
                                <th class="px-6 py-4">Jam Pulang</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 font-semibold">{{ $hari }}</td>
                                    <td class="px-6 py-4">07.30 WIB</td>
                                    <td class="px-6 py-4">15.00 WIB</td>
                                    <td class="px-6 py-4">
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            Hari Kerja
                                        </span>
                                    </td>
                                </tr>
                            @endforeach

                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-semibold">Sabtu</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">
                                    <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-semibold">
                                        Libur
                                    </span>
                                </td>
                            </tr>

                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-semibold">Minggu</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">
                                    <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-semibold">
                                        Libur
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="mt-6 rounded-xl bg-orange-50 border border-orange-200 p-5">
                <p class="font-bold text-orange-800">Perhatian!</p>
                <p class="text-sm text-orange-700 mt-1">
                    Jadwal di atas merupakan contoh sementara. Pastikan jadwal
                    kerja sesuai dengan ketentuan resmi SAMSAT Ponorogo.
                </p>
            </div>

        </div>
    </main>
</div>

</body>
</html>
