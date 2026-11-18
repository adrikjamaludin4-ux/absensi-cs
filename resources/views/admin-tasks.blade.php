<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Penugasan & Pengecekan Rutin | Admin</title>
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
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-800">
                <span>▦</span> Dashboard & Rekap
            </a>

            <a href="{{ route('admin.tasks.index') }}"
               class="flex items-center gap-3 rounded-xl bg-blue-800 px-4 py-3 font-semibold text-white shadow-sm">
                <span>📋</span> Manajemen Penugasan
            </a>

            <a href="{{ route('dashboard') }}#monitoring"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-800">
                <span>◷</span> Data Presensi CS
            </a>

            <a href="{{ route('dashboard') }}#pengajuan-izin"
               class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-800">
                <span>📝</span> Data Izin / Sakit
            </a>

            <a href="{{ route('profile.edit') }}"
               class="block rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-800">
                <span>👤</span> Profil Saya
            </a>

            <div class="mt-auto hidden rounded-2xl border border-blue-800 bg-blue-950 p-4 md:block">
                <p class="text-sm font-semibold">Sistem Penugasan Rutin</p>
                <p class="mt-1 text-xs leading-5 text-blue-300">
                    Atur jadwal berkala pengecekan toilet, ruang meeting zoom, dan area kerja.
                </p>
                <div class="mt-3 flex items-center gap-2 text-xs text-emerald-400">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sistem Aktif
                </div>
            </div>
        </nav>
    </aside>

    <!-- KONTEN UTAMA -->
    <div class="min-w-0 flex-1">

        <!-- HEADER -->
        <header class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 bg-white px-5 py-5 md:px-8">
            <div>
                <p class="text-sm text-slate-500">Sistem Informasi Presensi & Monitoring CS</p>
                <h2 class="mt-1 text-xl font-bold text-slate-800 md:text-2xl">
                    Manajemen Penugasan & Pengecekan Rutin
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

            <!-- NOTIFIKASI SUCCESS / ERROR -->
            @if (session('success'))
                <div class="rounded-2xl bg-emerald-100 p-4 border border-emerald-200 text-emerald-800 font-semibold text-sm flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="rounded-2xl bg-red-50 p-4 border border-red-200 text-red-700 text-sm">
                    <p class="font-bold mb-1">Gagal memproses formulir:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- STATISTIK PENUGASAN -->
            <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-xl text-blue-700 font-bold">
                            📋
                        </div>
                        <span class="text-xs font-semibold uppercase text-slate-400">TOTAL TUGAS</span>
                    </div>
                    <p class="mt-4 text-xs text-slate-500 font-semibold uppercase">Seluruh Jadwal Tugas</p>
                    <div class="mt-1 flex items-end justify-between">
                        <h4 class="text-3xl font-bold text-slate-800">{{ $totalTasks }}</h4>
                        <span class="text-xs font-medium text-blue-700">Penugasan</span>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-xl text-emerald-700 font-bold">
                            ☀️
                        </div>
                        <span class="text-xs font-semibold uppercase text-emerald-600">HARIAN</span>
                    </div>
                    <p class="mt-4 text-xs text-slate-500 font-semibold uppercase">Pengecekan Harian</p>
                    <div class="mt-1 flex items-end justify-between">
                        <h4 class="text-3xl font-bold text-emerald-700">{{ $harianCount }}</h4>
                        <span class="text-xs font-medium text-emerald-700">Tiap Hari / 2 Jam</span>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-xl text-purple-700 font-bold">
                            📅
                        </div>
                        <span class="text-xs font-semibold uppercase text-purple-600">MINGGUAN</span>
                    </div>
                    <p class="mt-4 text-xs text-slate-500 font-semibold uppercase">Pembersihan Mingguan</p>
                    <div class="mt-1 flex items-end justify-between">
                        <h4 class="text-3xl font-bold text-purple-700">{{ $mingguanCount }}</h4>
                        <span class="text-xs font-medium text-purple-700">Tugas</span>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-xl text-amber-700 font-bold">
                            🗓️
                        </div>
                        <span class="text-xs font-semibold uppercase text-amber-600">BULANAN</span>
                    </div>
                    <p class="mt-4 text-xs text-slate-500 font-semibold uppercase">Perawatan Bulanan</p>
                    <div class="mt-1 flex items-end justify-between">
                        <h4 class="text-3xl font-bold text-amber-700">{{ $bulananCount }}</h4>
                        <span class="text-xs font-medium text-amber-700">Tugas</span>
                    </div>
                </div>
            </section>

            <!-- KONTROL FILTER & TOMBOL TAMBAH TUGAS -->
            <section class="flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-white p-5 border border-slate-200 shadow-sm">
                <!-- Filter Tabs -->
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.tasks.index', ['interval' => 'semua']) }}"
                       class="rounded-xl px-4 py-2 text-xs font-bold transition {{ $intervalFilter === 'semua' ? 'bg-blue-900 text-white shadow' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Semua Tugas ({{ $totalTasks }})
                    </a>
                    <a href="{{ route('admin.tasks.index', ['interval' => 'harian']) }}"
                       class="rounded-xl px-4 py-2 text-xs font-bold transition {{ $intervalFilter === 'harian' ? 'bg-blue-900 text-white shadow' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Harian ({{ $harianCount }})
                    </a>
                    <a href="{{ route('admin.tasks.index', ['interval' => 'mingguan']) }}"
                       class="rounded-xl px-4 py-2 text-xs font-bold transition {{ $intervalFilter === 'mingguan' ? 'bg-blue-900 text-white shadow' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Mingguan ({{ $mingguanCount }})
                    </a>
                    <a href="{{ route('admin.tasks.index', ['interval' => 'bulanan']) }}"
                       class="rounded-xl px-4 py-2 text-xs font-bold transition {{ $intervalFilter === 'bulanan' ? 'bg-blue-900 text-white shadow' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Bulanan ({{ $bulananCount }})
                    </a>
                </div>

                <button type="button" onclick="openCreateModal()"
                    class="rounded-xl bg-orange-500 px-5 py-2.5 text-sm font-bold text-white shadow transition hover:bg-orange-600 flex items-center gap-2">
                    <span>+</span> Tambah Tugas Pengecekan
                </button>
            </section>

            <!-- TABEL DAFTAR TUGAS -->
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-5 md:p-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Daftar Jadwal Penugasan Rutin</h3>
                        <p class="text-xs text-slate-500 mt-1">Daftar instruksi tugas yang harus dikerjakan dan dilaporkan oleh Cleaning Service.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[850px] text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-600 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Nama Tugas & Deskripsi</th>
                                <th class="px-6 py-4 font-semibold">Lokasi / Ruangan</th>
                                <th class="px-6 py-4 font-semibold">Interval & Target Jam</th>
                                <th class="px-6 py-4 font-semibold">Petugas Ditugaskan</th>
                                <th class="px-6 py-4 font-semibold">Status Pengerjaan Hari Ini</th>
                                <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($tasks as $task)
                                @php
                                    $submissions = $task->submissions;
                                    $isCompletedToday = $submissions->count() > 0;
                                @endphp
                                <tr class="hover:bg-slate-50">
                                    <!-- Nama Tugas -->
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-800">{{ $task->title }}</p>
                                        @if ($task->description)
                                            <p class="text-xs text-slate-500 mt-0.5 max-w-xs">{{ $task->description }}</p>
                                        @endif
                                    </td>

                                    <!-- Lokasi -->
                                    <td class="px-6 py-4 text-xs font-semibold text-slate-700">
                                        📍 {{ $task->location ?? 'Area Kantor' }}
                                    </td>

                                    <!-- Interval & Target -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-bold
                                            {{ $task->interval_type === 'harian' ? 'bg-emerald-100 text-emerald-800' : ($task->interval_type === 'mingguan' ? 'bg-purple-100 text-purple-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ ucfirst($task->interval_type) }}
                                        </span>
                                        <p class="text-xs text-slate-600 font-medium mt-1">
                                            ⏰ {{ $task->target_time }}
                                        </p>
                                    </td>

                                    <!-- Petugas -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($task->assignedUser)
                                            <div class="flex items-center gap-2">
                                                <div class="flex h-7 w-7 items-center justify-center rounded-full bg-orange-100 text-xs font-bold text-orange-700">
                                                    {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}
                                                </div>
                                                <span class="text-xs font-bold text-slate-800">{{ $task->assignedUser->name }}</span>
                                            </div>
                                        @else
                                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                                Semua Petugas CS
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Status Hari Ini -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($isCompletedToday)
                                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">
                                                ✓ Selesai ({{ $submissions->count() }}x laporan)
                                            </span>
                                            <p class="text-[11px] text-slate-400 mt-1">Terakhir: {{ $submissions->first()->jam_submit }}</p>
                                        @else
                                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">
                                                ⚠️ Belum Dikerjakan
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" onclick="openEditModal({{ json_encode($task) }})"
                                                class="rounded-lg bg-blue-50 px-2.5 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100">
                                                ✏️ Edit
                                            </button>

                                            <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100">
                                                    🗑️ Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-400 text-xs">
                                        Belum ada jadwal penugasan yang dibuat. Silakan klik tombol "+ Tambah Tugas Pengecekan".
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- LOG BUKTI LAPORAN PENGERJAAN HARI INI -->
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-5 md:p-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Laporan Bukti Pengerjaan Hari Ini (Real-time)</h3>
                        <p class="text-xs text-slate-500 mt-1">Foto bukti pengerjaan yang diunggah oleh petugas Cleaning Service.</p>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">
                        {{ $submissionsToday->count() }} Laporan Masuk
                    </span>
                </div>

                <div class="p-6">
                    @if ($submissionsToday->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($submissionsToday as $sub)
                                <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50 hover:shadow-md transition">
                                    <div class="relative aspect-video bg-slate-900 cursor-pointer" onclick="showPhotoModal('{{ $sub->photo_proof_url }}', '{{ $sub->task->title ?? 'Tugas' }} - {{ $sub->user->name ?? 'CS' }}')">
                                        <img src="{{ $sub->photo_proof_url }}" alt="Bukti Pengerjaan" class="w-full h-full object-cover">
                                        <span class="absolute top-2 right-2 rounded-lg bg-emerald-600 px-2 py-0.5 text-[10px] font-bold text-white shadow">
                                            ✓ {{ $sub->status }}
                                        </span>
                                    </div>
                                    <div class="p-4">
                                        <h4 class="font-bold text-xs text-slate-800 truncate" title="{{ $sub->task->title ?? '-' }}">
                                            {{ $sub->task->title ?? 'Tugas' }}
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
                            Belum ada laporan bukti pengerjaan tugas yang diunggah untuk hari ini.
                        </div>
                    @endif
                </div>
            </section>

            <footer class="pb-3 text-center text-xs text-slate-400">
                © {{ date('Y') }} Sistem Presensi dan Manajemen Penugasan Cleaning Service
            </footer>

        </main>
    </div>
</div>

<!-- MODAL TAMBAH TUGAS -->
<div id="createModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/70 p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-5">
            <h3 class="font-bold text-lg text-slate-900">Tambah Jadwal Pengecekan / Tugas</h3>
            <button type="button" onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 text-2xl font-bold">×</button>
        </div>

        <form action="{{ route('admin.tasks.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Nama Tugas -->
            <div>
                <label for="create_title" class="block text-xs font-bold uppercase text-slate-700 mb-1">
                    Nama Tugas / Pengecekan <span class="text-red-500">*</span>
                </label>
                <input type="text" id="create_title" name="title" required
                    placeholder="Contoh: Pengecekan Toilet Lt. 1 / Ruang Zoom"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">
            </div>

            <!-- Lokasi / Ruangan -->
            <div>
                <label for="create_location" class="block text-xs font-bold uppercase text-slate-700 mb-1">
                    Lokasi Fasilitas / Ruangan
                </label>
                <input type="text" id="create_location" name="location"
                    placeholder="Contoh: Toilet Lantai 1, Ruang Rapat Zoom"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">
            </div>

            <!-- Interval & Target Jam -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="create_interval_type" class="block text-xs font-bold uppercase text-slate-700 mb-1">
                        Interval Tugas <span class="text-red-500">*</span>
                    </label>
                    <select id="create_interval_type" name="interval_type" required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">
                        <option value="harian">Harian (Rutin)</option>
                        <option value="mingguan">Mingguan</option>
                        <option value="bulanan">Bulanan</option>
                    </select>
                </div>

                <div>
                    <label for="create_target_time" class="block text-xs font-bold uppercase text-slate-700 mb-1">
                        Target Jam Pengerjaan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="create_target_time" name="target_time" required
                        placeholder="Contoh: Setiap 2 Jam Sekali / 08.00 WIB" value="Setiap 2 Jam Sekali"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">
                </div>
            </div>

            <!-- Petugas CS yang Ditugaskan -->
            <div>
                <label for="create_assigned_to" class="block text-xs font-bold uppercase text-slate-700 mb-1">
                    Petugas yang Ditugaskan
                </label>
                <select id="create_assigned_to" name="assigned_to"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">
                    <option value="">Semua Petugas Cleaning Service</option>
                    @foreach ($csUsers as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Pilih petugas khusus atau biarkan kosong agar berlaku untuk semua CS.</p>
            </div>

            <!-- Deskripsi / SOP -->
            <div>
                <label for="create_description" class="block text-xs font-bold uppercase text-slate-700 mb-1">
                    Deskripsi / Instruksi Pengecekan
                </label>
                <textarea id="create_description" name="description" rows="2"
                    placeholder="Contoh: Pastikan sabun, tisu terisi penuh, lantai bersih dan tidak licin."
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600"></textarea>
            </div>

            <div class="pt-3 flex justify-end gap-2 border-t border-slate-200">
                <button type="button" onclick="closeCreateModal()"
                    class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                    Batal
                </button>
                <button type="submit"
                    class="rounded-xl bg-blue-900 px-5 py-2 text-sm font-bold text-white hover:bg-blue-950 shadow">
                    Simpan Tugas
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT TUGAS -->
<div id="editModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/70 p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-5">
            <h3 class="font-bold text-lg text-slate-900">Edit Jadwal Penugasan</h3>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 text-2xl font-bold">×</button>
        </div>

        <form id="editForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_title" class="block text-xs font-bold uppercase text-slate-700 mb-1">
                    Nama Tugas / Pengecekan <span class="text-red-500">*</span>
                </label>
                <input type="text" id="edit_title" name="title" required
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">
            </div>

            <div>
                <label for="edit_location" class="block text-xs font-bold uppercase text-slate-700 mb-1">
                    Lokasi Fasilitas / Ruangan
                </label>
                <input type="text" id="edit_location" name="location"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="edit_interval_type" class="block text-xs font-bold uppercase text-slate-700 mb-1">
                        Interval Tugas <span class="text-red-500">*</span>
                    </label>
                    <select id="edit_interval_type" name="interval_type" required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">
                        <option value="harian">Harian (Rutin)</option>
                        <option value="mingguan">Mingguan</option>
                        <option value="bulanan">Bulanan</option>
                    </select>
                </div>

                <div>
                    <label for="edit_target_time" class="block text-xs font-bold uppercase text-slate-700 mb-1">
                        Target Jam Pengerjaan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="edit_target_time" name="target_time" required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">
                </div>
            </div>

            <div>
                <label for="edit_assigned_to" class="block text-xs font-bold uppercase text-slate-700 mb-1">
                    Petugas yang Ditugaskan
                </label>
                <select id="edit_assigned_to" name="assigned_to"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">
                    <option value="">Semua Petugas Cleaning Service</option>
                    @foreach ($csUsers as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="edit_description" class="block text-xs font-bold uppercase text-slate-700 mb-1">
                    Deskripsi / Instruksi Pengecekan
                </label>
                <textarea id="edit_description" name="description" rows="2"
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="h-4 w-4 rounded text-blue-600">
                <label for="edit_is_active" class="text-xs font-semibold text-slate-700">Tugas Aktif (Ditampilkan ke CS)</label>
            </div>

            <div class="pt-3 flex justify-end gap-2 border-t border-slate-200">
                <button type="button" onclick="closeEditModal()"
                    class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                    Batal
                </button>
                <button type="submit"
                    class="rounded-xl bg-blue-900 px-5 py-2 text-sm font-bold text-white hover:bg-blue-950 shadow">
                    Perbarui Tugas
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL PREVIEW FOTO BUKTI -->
<div id="photoModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/75 p-4" onclick="closePhotoModal()">
    <div class="relative max-w-lg w-full bg-white rounded-2xl overflow-hidden shadow-2xl" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
            <h4 id="photoModalTitle" class="font-bold text-slate-800 text-sm">Bukti Pengerjaan Tugas</h4>
            <button type="button" onclick="closePhotoModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">×</button>
        </div>
        <div class="p-4 bg-slate-900 flex items-center justify-center">
            <img id="photoModalImg" src="" alt="Bukti Foto" class="max-h-[70vh] w-auto rounded-lg object-contain">
        </div>
        <div class="p-3 text-right bg-slate-50 border-t border-slate-200">
            <button type="button" onclick="closePhotoModal()" class="rounded-xl bg-slate-200 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-300">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    function openEditModal(task) {
        document.getElementById('editForm').action = '/admin/tasks/' + task.id;
        document.getElementById('edit_title').value = task.title;
        document.getElementById('edit_location').value = task.location || '';
        document.getElementById('edit_interval_type').value = task.interval_type;
        document.getElementById('edit_target_time').value = task.target_time;
        document.getElementById('edit_assigned_to').value = task.assigned_to || '';
        document.getElementById('edit_description').value = task.description || '';
        document.getElementById('edit_is_active').checked = Boolean(task.is_active);

        document.getElementById('editModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function showPhotoModal(url, title) {
        document.getElementById('photoModalImg').src = url;
        document.getElementById('photoModalTitle').textContent = title || 'Bukti Pengerjaan Tugas';
        document.getElementById('photoModal').classList.remove('hidden');
    }
    function closePhotoModal() {
        document.getElementById('photoModal').classList.add('hidden');
    }
</script>

</body>
</html>
