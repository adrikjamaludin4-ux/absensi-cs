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
                        Silakan masuk menggunakan nama, sandi, dan peran akun Anda.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="role" class="mb-2 block text-sm font-semibold">
                            Login Sebagai
                        </label>
                        <select
                            id="role"
                            name="role"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-100"
                        >
                            <option value="">Pilih peran akun</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="satpam" {{ old('role') == 'satpam' ? 'selected' : '' }}>Satpam</option>
                            <option value="cs" {{ old('role') == 'cs' ? 'selected' : '' }}>Customer Service (CS)</option>
                        </select>
                    </div>

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

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-blue-700 px-5 py-3.5 font-semibold text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-200"
                    >
                        Masuk ke Sistem <span class="ml-1">→</span>
                    </button>
                </form>

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
