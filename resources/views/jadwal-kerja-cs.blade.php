<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Kerja & Penugasan Rutin | Presensi Cleaning Service</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-800">

@php
    $user = auth()->user();
@endphp

<div class="min-h-screen flex">

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

                <a href="{{ route('jadwal.kerja.cs') }}"
                    class="flex items-center gap-3 rounded-xl bg-orange-500 px-4 py-3 font-medium text-white shadow-sm">
                    <span>📋</span> Jadwal & Penugasan
                </a>

                <a href="{{ route('izin.cs') }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                    <span>📝</span> Ajukan Izin / Sakit
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

        <!-- Informasi Sidebar -->
        <div class="mt-auto p-5">
            <div class="rounded-2xl border border-blue-800 bg-blue-900/60 p-4">
                <p class="text-sm font-semibold">Tugas Pengecekan Rutin</p>
                <p class="mt-1 text-xs leading-5 text-blue-200">
                    Pastikan mengambil foto bukti setelah membersihkan fasilitas (toilet, ruang meeting zoom, dll.).
                </p>
            </div>
        </div>

    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 min-w-0">

        <!-- HEADER -->
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center">
            <div>
                <h2 class="font-bold text-lg text-blue-950">Jadwal & Penugasan Rutin</h2>
                <p class="text-xs text-slate-500">Sistem Informasi Presensi Cleaning Service</p>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-xl p-1.5 transition hover:bg-blue-50">
                    <div class="hidden sm:block text-right">
                        <p class="text-sm font-bold text-blue-950">{{ $user->name }}</p>
                        <p class="text-xs text-slate-500">Cleaning Service</p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-orange-100 font-bold uppercase text-orange-700">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        Keluar
                    </button>
                </form>
            </div>
        </header>

        <div class="p-5 md:p-8 space-y-6 max-w-7xl mx-auto">

            <!-- NOTIFIKASI SUCCESS / ERROR -->
            @if (session('success'))
                <div class="rounded-2xl bg-emerald-100 p-4 border border-emerald-200 text-emerald-800 font-semibold text-sm flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="rounded-2xl bg-red-50 p-4 border border-red-200 text-red-700 text-sm">
                    <p class="font-bold mb-1">Gagal mengirimkan laporan:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- BANNER PROGRES PENUGASAN HARI INI -->
            <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900 p-6 text-white shadow-lg md:p-8">
                <div class="relative z-10 max-w-2xl">
                    <span class="inline-flex rounded-full bg-white/20 px-3 py-1 text-xs font-bold uppercase tracking-wider text-orange-300">
                        PENUGASAN & PENGECEKAN RUTIN
                    </span>

                    <h1 class="mt-3 text-2xl font-bold md:text-3xl">
                        Daftar Tugas Pengecekan Hari Ini
                    </h1>

                    <p class="mt-2 text-sm leading-6 text-blue-100">
                        Selesaikan tugas pengecekan fasilitas (toilet, ruang zoom, dll.) sesuai jadwal yang ditentukan Administrator dan unggah foto bukti pengerjaan.
                    </p>

                    <div class="mt-5 flex flex-wrap items-center gap-4">
                        <div class="rounded-2xl bg-white/10 px-4 py-2 border border-white/20">
                            <p class="text-[11px] text-blue-200 uppercase font-semibold">Tugas Selesai</p>
                            <p class="text-xl font-bold text-emerald-400">{{ $completedTodayCount }} / {{ $totalActiveTasks }}</p>
                        </div>

                        <div class="rounded-2xl bg-white/10 px-4 py-2 border border-white/20">
                            <p class="text-[11px] text-blue-200 uppercase font-semibold">Tugas Belum Selesai</p>
                            <p class="text-xl font-bold text-amber-300">{{ $pendingCount }}</p>
                        </div>

                        <div class="rounded-2xl bg-white/10 px-4 py-2 border border-white/20">
                            <p class="text-[11px] text-blue-200 uppercase font-semibold">Waktu Sekarang</p>
                            <p class="text-xl font-bold text-white font-mono">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }} WIB</p>
                        </div>
                    </div>
                </div>

                <div class="absolute -right-8 -top-12 h-52 w-52 rounded-full border-[30px] border-white/5"></div>
            </section>

            <!-- FILTER INTERVAL TUGAS -->
            <section class="flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-white p-5 border border-slate-200 shadow-sm">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Filter Jadwal Penugasan</h3>
                    <p class="text-xs text-slate-500">Pilih kategori interval tugas pengecekan.</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('jadwal.kerja.cs', ['interval' => 'semua']) }}"
                       class="rounded-xl px-4 py-2 text-xs font-bold transition {{ $intervalFilter === 'semua' ? 'bg-blue-900 text-white shadow' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Semua Tugas
                    </a>
                    <a href="{{ route('jadwal.kerja.cs', ['interval' => 'harian']) }}"
                       class="rounded-xl px-4 py-2 text-xs font-bold transition {{ $intervalFilter === 'harian' ? 'bg-blue-900 text-white shadow' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Pengecekan Harian
                    </a>
                    <a href="{{ route('jadwal.kerja.cs', ['interval' => 'mingguan']) }}"
                       class="rounded-xl px-4 py-2 text-xs font-bold transition {{ $intervalFilter === 'mingguan' ? 'bg-blue-900 text-white shadow' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Tugas Mingguan
                    </a>
                    <a href="{{ route('jadwal.kerja.cs', ['interval' => 'bulanan']) }}"
                       class="rounded-xl px-4 py-2 text-xs font-bold transition {{ $intervalFilter === 'bulanan' ? 'bg-blue-900 text-white shadow' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Tugas Bulanan
                    </a>
                </div>
            </section>

            <!-- DAFTAR KARTU PENUGASAN -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse ($tasks as $task)
                    @php
                        $submission = $submissionsToday->get($task->id);
                        $isCompleted = !is_null($submission);
                    @endphp
                    <div class="rounded-3xl border {{ $isCompleted ? 'border-emerald-200 bg-white shadow-sm' : 'border-slate-200 bg-white shadow-md hover:border-blue-400' }} p-6 flex flex-col justify-between transition">
                        <div>
                            <!-- Header Kartu -->
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <span class="rounded-full px-2.5 py-1 text-[11px] font-bold uppercase
                                    {{ $task->interval_type === 'harian' ? 'bg-blue-100 text-blue-800' : ($task->interval_type === 'mingguan' ? 'bg-purple-100 text-purple-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ $task->interval_type }}
                                </span>

                                @if ($isCompleted)
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800 flex items-center gap-1">
                                        <span>✓</span> Selesai
                                    </span>
                                @else
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800 flex items-center gap-1">
                                        <span>⏳</span> Belum Selesai
                                    </span>
                                @endif
                            </div>

                            <!-- Judul & Lokasi -->
                            <h3 class="font-bold text-lg text-slate-900">{{ $task->title }}</h3>

                            @if ($task->location)
                                <p class="text-xs font-semibold text-blue-900 mt-1 flex items-center gap-1">
                                    <span>📍</span> {{ $task->location }}
                                </p>
                            @endif

                            <div class="mt-3 bg-slate-50 rounded-2xl p-3 border border-slate-100 space-y-1.5 text-xs text-slate-600">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400">Target Waktu:</span>
                                    <span class="font-bold text-slate-800">⏰ {{ $task->target_time }}</span>
                                </div>
                                @if ($task->description)
                                    <div class="pt-1 border-t border-slate-200 text-slate-500 text-[11px]">
                                        {{ $task->description }}
                                    </div>
                                @endif
                            </div>

                            <!-- Riwayat Submission Jika Sudah Selesai -->
                            @if ($isCompleted)
                                <div class="mt-4 rounded-2xl bg-emerald-50/80 p-3.5 border border-emerald-200">
                                    <div class="flex items-center justify-between text-xs text-emerald-900 font-semibold mb-2">
                                        <span>Diselesaikan Pukul:</span>
                                        <span class="font-mono">{{ $submission->jam_submit }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $submission->photo_proof_url }}" alt="Bukti Foto"
                                            class="h-14 w-14 rounded-xl object-cover border border-emerald-300 shadow-sm cursor-pointer"
                                            onclick="showPhotoModal('{{ $submission->photo_proof_url }}', 'Bukti: {{ $task->title }}')">
                                        <div class="text-[11px] text-emerald-800">
                                            <p class="font-bold">Laporan Terkirim</p>
                                            @if ($submission->notes)
                                                <p class="text-slate-600 italic truncate max-w-[170px]">"{{ $submission->notes }}"</p>
                                            @else
                                                <p class="text-slate-400 italic">Tanpa catatan</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="mt-6 pt-4 border-t border-slate-100">
                            @if ($isCompleted)
                                <button type="button" onclick="openSubmitModal({{ json_encode($task) }})"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                                    🔄 Kirim Laporan Tambahan / Update
                                </button>
                            @else
                                <button type="button" onclick="openSubmitModal({{ json_encode($task) }})"
                                    class="w-full rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition shadow-md flex items-center justify-center gap-2">
                                    <span>📸</span> Selesaikan Tugas & Kirim Bukti
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200 text-slate-400 text-sm">
                        Tidak ada tugas pengecekan aktif untuk kategori yang dipilih saat ini.
                    </div>
                @endforelse
            </section>

            <!-- FOOTER -->
            <footer class="pb-3 text-center text-xs text-slate-400">
                © {{ date('Y') }} Presensi Cleaning Service · Sistem Manajemen Penugasan & Absensi Petugas
            </footer>

        </div>
    </main>
</div>

<!-- MODAL FORM LAPORAN BUKTI PENGERJAAN TUGAS -->
<div id="submitModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/75 p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-5">
            <div>
                <span class="rounded-full bg-blue-100 text-blue-800 px-2.5 py-0.5 text-[10px] font-bold uppercase">Laporan Pengerjaan</span>
                <h3 id="modal_task_title" class="font-bold text-lg text-slate-900 mt-1">Selesaikan Tugas</h3>
            </div>
            <button type="button" onclick="closeSubmitModal()" class="text-slate-400 hover:text-slate-600 text-2xl font-bold">×</button>
        </div>

        <form id="submitTaskForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Informasi Tugas Singkat -->
            <div class="rounded-2xl bg-blue-50/70 p-3.5 border border-blue-100 text-xs text-blue-900">
                <p><strong class="text-blue-950">Lokasi:</strong> <span id="modal_task_location">-</span></p>
                <p class="mt-1"><strong class="text-blue-950">Target Waktu:</strong> <span id="modal_task_target">-</span></p>
            </div>

            <!-- Upload Foto Bukti Pengerjaan -->
            <div>
                <label for="photo_proof" class="block text-xs font-bold uppercase text-slate-700 mb-1">
                    Foto Bukti Pengerjaan (Real-time) <span class="text-red-500">*</span>
                </label>
                <input type="file" id="photo_proof" name="photo_proof" accept="image/*" capture="environment" required
                    onchange="previewProofImage(event)"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-[11px] text-slate-400 mt-1">Ambil foto langsung kamera atau pilih file (JPG, PNG maksimal 3MB).</p>

                <!-- Preview Gambar -->
                <div id="imagePreviewContainer" class="hidden mt-3 rounded-2xl overflow-hidden aspect-video bg-slate-900 border border-slate-300">
                    <img id="imagePreview" src="" alt="Pratinjau Foto" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Catatan / Keterangan -->
            <div>
                <label for="notes" class="block text-xs font-bold uppercase text-slate-700 mb-1">
                    Catatan / Kondisi Fasilitas (Opsional)
                </label>
                <textarea id="notes" name="notes" rows="2"
                    placeholder="Contoh: Toilet telah dibersihkan, sabun dan tisu sudah diisi ulang."
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600"></textarea>
            </div>

            <!-- Waktu Tercatat Otomatis -->
            <div class="text-[11px] text-slate-400 text-right">
                Waktu kirim akan dicatat secara otomatis (WIB).
            </div>

            <div class="pt-3 flex justify-end gap-2 border-t border-slate-200">
                <button type="button" onclick="closeSubmitModal()"
                    class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                    Batal
                </button>
                <button type="submit"
                    class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-700 shadow-md">
                    📤 Kirim Laporan Bukti
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL PREVIEW FOTO -->
<div id="photoModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/75 p-4" onclick="closePhotoModal()">
    <div class="relative max-w-lg w-full bg-white rounded-2xl overflow-hidden shadow-2xl" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
            <h4 id="photoModalTitle" class="font-bold text-slate-800 text-sm">Bukti Pengerjaan</h4>
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
    function openSubmitModal(task) {
        document.getElementById('submitTaskForm').action = '/cs/tasks/' + task.id + '/submit';
        document.getElementById('modal_task_title').textContent = task.title;
        document.getElementById('modal_task_location').textContent = task.location || 'Area Kerja';
        document.getElementById('modal_task_target').textContent = task.target_time;

        document.getElementById('notes').value = '';
        document.getElementById('photo_proof').value = '';
        document.getElementById('imagePreviewContainer').classList.add('hidden');

        document.getElementById('submitModal').classList.remove('hidden');
    }

    function closeSubmitModal() {
        document.getElementById('submitModal').classList.add('hidden');
    }

    function previewProofImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('imagePreviewContainer').classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function showPhotoModal(url, title) {
        document.getElementById('photoModalImg').src = url;
        document.getElementById('photoModalTitle').textContent = title || 'Bukti Pengerjaan';
        document.getElementById('photoModal').classList.remove('hidden');
    }

    function closePhotoModal() {
        document.getElementById('photoModal').classList.add('hidden');
    }
</script>

</body>
</html>
