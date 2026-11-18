
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | Presensi Cleaning Service</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="hidden w-64 flex-shrink-0 flex-col bg-blue-900 text-white md:flex">
        <div class="border-b border-blue-800 px-6 py-6">
            <h1 class="text-xl font-extrabold">
                Presensi Cleaning Service
            </h1>
            <p class="mt-1 text-sm text-blue-200">Sistem Absensi Petugas</p>
        </div>

        <nav class="flex-1 space-y-2 px-4 py-6">
            <a href="{{ auth()->user()->role === 'admin' ? route('dashboard') : (auth()->user()->role === 'satpam' ? route('dashboard.satpam') : route('dashboard.cs')) }}"
               class="block rounded-xl px-4 py-3 text-blue-100 hover:bg-blue-800">
                Dashboard
            </a>

            <a href="{{ route('profile.edit') }}"
               class="block rounded-xl bg-orange-500 px-4 py-3 font-semibold text-white">
                Profil Saya
            </a>
        </nav>

        <div class="border-t border-blue-800 px-6 py-4 text-xs text-blue-200">
            © 2026 Presensi Cleaning Service
        </div>
    </aside>

    <!-- Konten -->
    <main class="min-w-0 flex-1">
        <header class="flex flex-wrap items-center justify-between gap-4 bg-white px-5 py-5 shadow-sm md:px-8">
            <div>
                <h2 class="text-2xl font-bold text-blue-900">Profil Saya</h2>
                <p class="mt-1 text-sm text-slate-500">Kelola informasi akun Anda</p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold hover:bg-slate-100">
                    Keluar
                </button>
            </form>
        </header>

        <div class="mx-auto max-w-4xl space-y-6 p-5 md:p-8">

            <!-- Kartu informasi akun -->
            <section class="overflow-hidden rounded-2xl bg-white shadow-sm">
                <div class="bg-blue-800 p-6 text-white">
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-400 text-2xl font-bold text-blue-950">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">{{ auth()->user()->name }}</h3>
                            <p class="mt-1 text-sm text-blue-100">{{ auth()->user()->email }}</p>
                            <span class="mt-2 inline-block rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">
                                {{ strtoupper(auth()->user()->role) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 p-6 sm:grid-cols-2">
                    <div>
                        <p class="text-sm text-slate-500">Nama Pengguna</p>
                        <p class="mt-1 font-semibold">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Email</p>
                        <p class="mt-1 font-semibold">{{ auth()->user()->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Peran Akun</p>
                        <p class="mt-1 font-semibold">{{ strtoupper(auth()->user()->role) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Bergabung Sejak</p>
                        <p class="mt-1 font-semibold">
                            {{ auth()->user()->created_at?->format('d-m-Y') ?? '-' }}
                        </p>
                    </div>
                </div>
            </section>

            <!-- Form edit profil Breeze -->
            <section class="rounded-2xl bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold text-blue-900">Informasi Profil</h3>
                <p class="mt-1 text-sm text-slate-500">
                    Perbarui nama dan alamat email akun Anda.
                </p>

                <div class="mt-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </section>

            <!-- Form ubah password Breeze -->
            <section class="rounded-2xl bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold text-blue-900">Keamanan Akun</h3>
                <p class="mt-1 text-sm text-slate-500">
                    Ganti password secara berkala untuk menjaga keamanan akun.
                </p>

                <div class="mt-6">
                    @include('profile.partials.update-password-form')
                </div>
            </section>

        </div>
    </main>
</div>

</body>
</html>
