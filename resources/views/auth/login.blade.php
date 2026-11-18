<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SAMSAT Ponorogo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">
    <div class="grid min-h-screen lg:grid-cols-2">

        <!-- BAGIAN KIRI -->
        <section class="relative hidden overflow-hidden bg-blue-800 p-12 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="absolute -right-20 -top-20 h-80 w-80 rounded-full border-[45px] border-white/10"></div>
            <div class="absolute -bottom-20 -left-16 h-72 w-72 rounded-full bg-orange-500/20"></div>

            <div class="relative z-10 flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-2xl text-blue-800">
                    🏢
                </div>
                <div>
                    <h1 class="text-xl font-bold">SAMSAT PONOROGO</h1>
                    <p class="text-sm text-blue-200">Sistem Informasi Absensi</p>
                </div>
            </div>

            <div class="relative z-10 max-w-lg">
                <div class="mb-6 inline-flex rounded-full border border-blue-400/40 bg-blue-700 px-4 py-2 text-sm text-blue-100">
                    Sistem Absensi dan Monitoring Petugas
                </div>

                <h2 class="text-4xl font-bold leading-tight xl:text-5xl">
                    Disiplin, Profesional, dan Terpantau.
                </h2>

                <p class="mt-6 max-w-md leading-7 text-blue-100">
                    Kelola kehadiran, jadwal kerja, dan monitoring petugas
                    Customer Service serta Satpam secara terstruktur dalam satu sistem.
                </p>

                <div class="mt-8 flex flex-wrap gap-3 text-sm">
                    <span class="rounded-lg bg-white/10 px-4 py-3">✓ Monitoring Kehadiran</span>
                    <span class="rounded-lg bg-white/10 px-4 py-3">⌖ Validasi Lokasi</span>
                    <span class="rounded-lg bg-white/10 px-4 py-3">◷ Jadwal Kerja</span>
                </div>
            </div>

            <p class="relative z-10 text-sm text-blue-200">
                © 2026 SAMSAT Ponorogo
            </p>
        </section>

        <!-- BAGIAN KANAN: FORM LOGIN -->
        <main class="flex items-center justify-center px-5 py-10 sm:px-10">
            <div class="w-full max-w-md">

                <div class="mb-8 flex items-center gap-3 lg:hidden">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-800 text-2xl text-white">
                        🏢
                    </div>
                    <div>
                        <h1 class="font-bold text-blue-800">SAMSAT PONOROGO</h1>
                        <p class="text-xs text-slate-500">Sistem Informasi Absensi</p>
                    </div>
                </div>

                <div class="mb-8">
                    <div class="mb-4 inline-flex rounded-xl bg-orange-100 p-3 text-2xl">
                        🔐
                    </div>
                    <h2 class="text-3xl font-bold tracking-tight">Selamat Datang!</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Silakan masuk menggunakan nama pengguna dan sandi akun Anda.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- TAB SWITCHER --}}
                @php
                    $activeTab = old('role', 'cs') === 'admin' ? 'admin' : 'cs';
                @endphp

                <div class="mb-6">
                    <div class="flex rounded-xl border border-slate-200 bg-slate-100 p-1">
                        <button
                            type="button"
                            id="tab-cs"
                            onclick="switchTab('cs')"
                            class="tab-btn flex-1 rounded-lg py-2.5 text-sm font-semibold transition-all duration-200
                                {{ $activeTab === 'cs' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}"
                        >
                            🧹 Cleaning Service
                        </button>
                        <button
                            type="button"
                            id="tab-admin"
                            onclick="switchTab('admin')"
                            class="tab-btn flex-1 rounded-lg py-2.5 text-sm font-semibold transition-all duration-200
                                {{ $activeTab === 'admin' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}"
                        >
                            🛡️ Administrator
                        </button>
                    </div>
                </div>

                {{-- TAB BADGE INFO --}}
                <div id="badge-cs" class="{{ $activeTab === 'cs' ? '' : 'hidden' }} mb-5 flex items-center gap-2 rounded-xl bg-blue-50 border border-blue-100 px-4 py-3">
                    <span class="text-lg">🧹</span>
                    <div>
                        <p class="text-xs font-semibold text-blue-800">Login sebagai Cleaning Service</p>
                        <p class="text-xs text-blue-500">Akses absensi dan jadwal kebersihan</p>
                    </div>
                </div>
                <div id="badge-admin" class="{{ $activeTab === 'admin' ? '' : 'hidden' }} mb-5 flex items-center gap-2 rounded-xl bg-orange-50 border border-orange-100 px-4 py-3">
                    <span class="text-lg">🛡️</span>
                    <div>
                        <p class="text-xs font-semibold text-orange-800">Login sebagai Administrator</p>
                        <p class="text-xs text-orange-500">Akses penuh ke seluruh sistem manajemen</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Hidden role input, diisi oleh JS tab --}}
                    <input type="hidden" id="role-input" name="role" value="{{ old('role', 'cs') }}">

                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold">
                            Nama Pengguna
                        </label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Masukkan nama pengguna"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label for="password" class="text-sm font-semibold">
                                Sandi
                            </label>
                        </div>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan sandi"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    {{-- 2 tombol statis (Tailwind butuh class hadir di HTML saat build) --}}
                    <button
                        type="submit"
                        id="btn-cs"
                        class="w-full rounded-xl bg-blue-700 px-5 py-3.5 font-semibold text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-200"
                    >
                        Masuk ke Sistem <span class="ml-1">→</span>
                    </button>
                    <button
                        type="submit"
                        id="btn-admin"
                        class="hidden w-full rounded-xl bg-orange-600 px-5 py-3.5 font-semibold text-white shadow-lg shadow-orange-600/20 transition hover:bg-orange-700 focus:outline-none focus:ring-4 focus:ring-orange-200"
                    >
                        Masuk ke Sistem <span class="ml-1">→</span>
                    </button>
                </form>

                <script>
                    function switchTab(tab) {
                        const roleInput  = document.getElementById('role-input');
                        const tabCs      = document.getElementById('tab-cs');
                        const tabAdmin   = document.getElementById('tab-admin');
                        const badgeCs    = document.getElementById('badge-cs');
                        const badgeAdmin = document.getElementById('badge-admin');
                        const btnCs      = document.getElementById('btn-cs');
                        const btnAdmin   = document.getElementById('btn-admin');

                        const activeClass   = ['bg-white', 'text-blue-700', 'shadow-sm'];
                        const inactiveClass = ['text-slate-500', 'hover:text-slate-700'];

                        if (tab === 'cs') {
                            roleInput.value = 'cs';
                            // Tab CS aktif
                            tabCs.classList.add(...activeClass);
                            tabCs.classList.remove(...inactiveClass);
                            // Tab Admin non-aktif
                            tabAdmin.classList.remove(...activeClass);
                            tabAdmin.classList.add(...inactiveClass);
                            // Badge
                            badgeCs.classList.remove('hidden');
                            badgeAdmin.classList.add('hidden');
                            // Tombol
                            btnCs.classList.remove('hidden');
                            btnAdmin.classList.add('hidden');
                        } else {
                            roleInput.value = 'admin';
                            // Tab Admin aktif
                            tabAdmin.classList.add(...activeClass);
                            tabAdmin.classList.remove(...inactiveClass);
                            // Tab CS non-aktif
                            tabCs.classList.remove(...activeClass);
                            tabCs.classList.add(...inactiveClass);
                            // Badge
                            badgeAdmin.classList.remove('hidden');
                            badgeCs.classList.add('hidden');
                            // Tombol
                            btnAdmin.classList.remove('hidden');
                            btnCs.classList.add('hidden');
                        }
                    }

                    // Inisialisasi saat halaman load
                    document.addEventListener('DOMContentLoaded', function () {
                        switchTab('{{ $activeTab }}');
                    });
                </script>

                <div class="mt-6 rounded-xl border border-yellow-200 bg-yellow-50 p-4">
                    <p class="text-sm font-semibold text-yellow-900">Informasi keamanan</p>
                    <p class="mt-1 text-xs leading-5 text-yellow-800">
                        Gunakan akun sesuai peran dan jangan membagikan sandi kepada orang lain.
                    </p>
                </div>

                <p class="mt-8 text-center text-xs text-slate-400">
                    © 2026 Sistem Absensi dan Monitoring SAMSAT Ponorogo
                </p>
            </div>
        </main>
    </div>
</body>
</html>
