<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Ketidakhadiran | Presensi Cleaning Service</title>
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
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>▦</span> Dashboard
                    </a>

                    <a href="{{ route('absensi.cs') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>◷</span> Absensi
                    </a>

                    <a href="{{ route('izin.cs') }}"
                        class="flex items-center gap-3 rounded-xl bg-orange-500 px-4 py-3 font-medium text-white shadow-sm">
                        <span>📝</span> Ajukan Izin / Sakit
                    </a>

                    <a href="{{ route('jadwal.kerja.cs') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>▤</span> Jadwal Kerja
                    </a>

                    <a href="{{ route('riwayat.absensi.cs') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>&#128197;</span>
                        Riwayat Absensi
                    </a>

                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>👤</span> Profil Saya
                    </a>
                </nav>
            </div>

            <div class="mt-auto p-5">
                <div class="rounded-2xl border border-blue-800 bg-blue-900/60 p-4">
                    <p class="text-sm font-semibold">Informasi Izin</p>
                    <p class="mt-1 text-xs leading-5 text-blue-200">
                        Ajukan izin sebelum jam kerja dimulai dan lampirkan surat bukti jika sakit.
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
                        Pengajuan Ketidakhadiran Petugas
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

            <div class="space-y-6 p-5 md:p-8 max-w-5xl mx-auto">

                <!-- NOTIFIKASI SUCCESS / ERROR -->
                @if (session('success'))
                    <div class="rounded-2xl bg-emerald-100 p-4 border border-emerald-200 text-emerald-800 font-semibold text-sm flex items-center gap-3">
                        <span class="text-xl">✅</span>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div class="rounded-2xl bg-red-100 p-4 border border-red-200 text-red-800 font-semibold text-sm flex items-center gap-3">
                        <span class="text-xl">⚠️</span>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @if (isset($errors) && $errors->any())
                    <div class="rounded-2xl bg-red-50 p-4 border border-red-200 text-red-700 text-sm">
                        <p class="font-bold mb-1">Terdapat kesalahan pengisian formulir:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- FORM PENGAJUAN -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <h3 class="text-lg font-bold text-blue-950">Formulir Ketidakhadiran (Izin / Sakit / Mendesak)</h3>
                        <p class="text-xs text-slate-500 mt-1">
                            Gunakan formulir ini jika Anda berhalangan hadir bertugas pada jam kerja operasional.
                        </p>
                    </div>

                    <form action="{{ route('izin.cs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        <!-- Tanggal Izin -->
                        <div>
                            <label for="date" class="block text-sm font-semibold text-slate-700 mb-1">
                                Tanggal Tidak Hadir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="date" name="date" required
                                value="{{ old('date', \Carbon\Carbon::now('Asia/Jakarta')->toDateString()) }}"
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">
                        </div>

                        <!-- Kategori Ketidakhadiran -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Kategori Ketidakhadiran <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label class="flex items-center gap-3 p-3.5 border rounded-2xl cursor-pointer hover:bg-slate-50 transition border-slate-300">
                                    <input type="radio" name="kategori" value="Izin" {{ old('kategori', 'Izin') === 'Izin' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                    <div>
                                        <p class="font-bold text-sm text-slate-800">Izin</p>
                                        <p class="text-[11px] text-slate-500">Urusan keperluan pribadi</p>
                                    </div>
                                </label>

                                <label class="flex items-center gap-3 p-3.5 border rounded-2xl cursor-pointer hover:bg-slate-50 transition border-slate-300">
                                    <input type="radio" name="kategori" value="Sakit" {{ old('kategori') === 'Sakit' ? 'checked' : '' }}
                                        class="h-4 w-4 text-red-600 focus:ring-red-500">
                                    <div>
                                        <p class="font-bold text-sm text-slate-800">Sakit</p>
                                        <p class="text-[11px] text-slate-500">Disertai surat dokter / obat</p>
                                    </div>
                                </label>

                                <label class="flex items-center gap-3 p-3.5 border rounded-2xl cursor-pointer hover:bg-slate-50 transition border-slate-300">
                                    <input type="radio" name="kategori" value="Keperluan Mendesak" {{ old('kategori') === 'Keperluan Mendesak' ? 'checked' : '' }}
                                        class="h-4 w-4 text-amber-600 focus:ring-amber-500">
                                    <div>
                                        <p class="font-bold text-sm text-slate-800">Keperluan Mendesak</p>
                                        <p class="text-[11px] text-slate-500">Darurat / keluarga</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Alasan / Keterangan -->
                        <div>
                            <label for="alasan" class="block text-sm font-semibold text-slate-700 mb-1">
                                Alasan / Keterangan Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea id="alasan" name="alasan" rows="3" required
                                placeholder="Jelaskan alasan berhalangan hadir secara singkat dan jelas..."
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">{{ old('alasan') }}</textarea>
                        </div>

                        <!-- Upload Lampiran / Surat Bukti -->
                        <div>
                            <label for="lampiran" class="block text-sm font-semibold text-slate-700 mb-1">
                                Lampiran Bukti (Opsional - Surat Dokter / Bukti Foto)
                            </label>
                            <input type="file" id="lampiran" name="lampiran" accept="image/*,application/pdf"
                                class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-[11px] text-slate-400 mt-1">Format file: JPG, PNG, atau PDF (Maksimal 3 MB).</p>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="pt-3 flex flex-wrap items-center gap-3">
                            <button type="submit"
                                class="rounded-2xl bg-blue-900 px-6 py-3.5 text-sm font-bold text-white shadow-md transition hover:bg-blue-950">
                                📤 Kirim Pengajuan Ketidakhadiran
                            </button>
                            <a href="{{ route('dashboard.cs') }}"
                                class="rounded-2xl border border-slate-200 px-5 py-3.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>

                <!-- RIWAYAT PENGAJUAN IZIN CS -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    <h3 class="text-base font-bold text-blue-950 mb-4">Riwayat Pengajuan Ketidakhadiran Anda</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[550px] text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50 text-slate-600 text-xs uppercase">
                                    <th class="px-4 py-3 font-semibold">Tanggal</th>
                                    <th class="px-4 py-3 font-semibold">Kategori</th>
                                    <th class="px-4 py-3 font-semibold">Alasan</th>
                                    <th class="px-4 py-3 font-semibold">Bukti</th>
                                    <th class="px-4 py-3 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($leaveRequests as $req)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3.5 font-medium text-slate-800 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($req->date)->translatedFormat('d F Y') }}
                                        </td>
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            <span class="rounded-full px-2.5 py-1 text-xs font-bold
                                                {{ $req->kategori === 'Sakit' ? 'bg-red-100 text-red-800' : ($req->kategori === 'Izin' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                                {{ $req->kategori }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 text-slate-600 text-xs max-w-xs truncate">
                                            {{ $req->alasan }}
                                        </td>
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            @if ($req->lampiran_url)
                                                <a href="{{ $req->lampiran_url }}" target="_blank"
                                                    class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-blue-700 hover:bg-blue-50">
                                                    Lihat Dokumen ↗
                                                </a>
                                            @else
                                                <span class="text-slate-400 italic text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                                                {{ $req->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-slate-400 text-xs">
                                            Belum ada pengajuan ketidakhadiran yang diajukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- FOOTER -->
                <footer class="pb-3 text-center text-xs text-slate-500">
                    © {{ date('Y') }} Presensi Cleaning Service · Sistem Informasi Absensi & Monitoring
                </footer>

            </div>
        </main>
    </div>

</body>

</html>
