<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Absensi CS | Presensi Cleaning Service</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-800" @if($timeStatus['can_action']) onload="startCamera()" @endif>

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
                        <span>▦</span>
                        Dashboard
                    </a>

                    <a href="{{ route('absensi.cs') }}"
                        class="flex items-center gap-3 rounded-xl bg-orange-500 px-4 py-3 font-medium text-white">
                        <span>◷</span>
                        Absensi
                    </a>

                    <a href="{{ route('izin.cs') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>📝</span>
                        Ajukan Izin / Sakit
                    </a>

                    <a href="{{ route('jadwal.kerja.cs') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>▤</span>
                        Jadwal Kerja
                    </a>

                    <a href="{{ route('riwayat.absensi.cs') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>&#128197;</span>
                        Riwayat Absensi
                    </a>

                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-blue-100 transition hover:bg-blue-900">
                        <span>👤</span>
                        Profil Saya
                    </a>
                </nav>
            </div>

            <div class="mt-auto p-5">
                <div class="rounded-2xl border border-blue-800 bg-blue-900/60 p-4">
                    <p class="text-sm font-semibold">Pelayanan Terbaik!</p>
                    <p class="mt-1 text-xs leading-5 text-blue-200">
                        Pastikan GPS dan Kamera depan aktif saat melakukan presensi.
                    </p>
                </div>
            </div>

        </aside>

        <!-- KONTEN KANAN -->
        <main class="min-w-0 flex-1">

            <!-- HEADER -->
            <header class="flex items-center justify-between border-b border-slate-200 bg-white px-6 py-4">
                <div>
                    <h2 class="font-bold text-lg text-blue-950">Verifikasi Absensi Petugas</h2>
                    <p class="text-xs text-slate-500">Sistem Informasi Presensi Cleaning Service</p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-bold">{{ Auth::user()->name }}</p>
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

            <!-- AREA KONTEN -->
            <div class="px-4 py-8 md:px-8">
                <div class="mx-auto max-w-4xl">

                    <div class="overflow-hidden rounded-3xl bg-white shadow-lg">

                        <!-- HEADER CARD -->
                        <div class="bg-gradient-to-r from-blue-950 via-blue-900 to-indigo-900 px-6 py-8 text-white">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-bold uppercase tracking-wider text-orange-300">
                                    @if ($timeStatus['action_type'] === 'masuk')
                                        Sesi Presensi Masuk (05.00 - 05.30 WIB)
                                    @elseif ($timeStatus['action_type'] === 'pulang')
                                        Sesi Presensi Pulang (15.00 - 18.00 WIB)
                                    @else
                                        Status Presensi
                                    @endif
                                </span>

                                <span class="text-xs text-blue-200 font-mono">
                                    Waktu Server: {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s') }} WIB
                                </span>
                            </div>

                            <h1 class="mt-3 text-2xl font-bold">
                                Verifikasi Absensi (GPS & Foto Selfie)
                            </h1>

                            <p class="mt-1 text-sm text-blue-200">
                                Pastikan Anda berada dalam radius area kerja dan wajah terlihat jelas di kamera.
                            </p>
                        </div>

                        <div class="space-y-6 p-6">

                            <!-- JIKA WAKTU PRESENSI SEDANG TIDAK AKTIF -->
                            @if (!$timeStatus['can_action'])
                                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-center">
                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 text-3xl">
                                        ⏳
                                    </div>
                                    <h3 class="mt-4 text-xl font-bold text-amber-900">
                                        {{ $timeStatus['banner_title'] }}
                                    </h3>
                                    <p class="mt-2 text-sm text-amber-800 max-w-md mx-auto">
                                        {{ $timeStatus['banner_message'] }}
                                    </p>

                                    <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
                                        <a href="{{ route('dashboard.cs') }}"
                                            class="rounded-xl bg-blue-900 px-6 py-3 text-sm font-bold text-white transition hover:bg-blue-800">
                                            ← Kembali ke Dashboard
                                        </a>

                                        <a href="{{ route('izin.cs') }}"
                                            class="rounded-xl border border-amber-300 bg-white px-5 py-3 text-sm font-semibold text-amber-800 transition hover:bg-amber-50">
                                            📝 Ajukan Izin / Sakit
                                        </a>
                                    </div>
                                </div>
                            @else
                                <!-- INFORMASI PETUGAS -->
                                <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-blue-50 p-5">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-blue-700">
                                            Petugas Cleaning Service
                                        </p>
                                        <p class="mt-1 text-lg font-bold text-blue-950">
                                            {{ Auth::user()->name }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-white px-4 py-2 shadow-sm text-right">
                                        <p class="text-xs text-slate-500">Sesi Presensi Aktif</p>
                                        <p class="text-sm font-bold text-emerald-700 uppercase">
                                            {{ $timeStatus['action_type'] === 'masuk' ? 'Presensi Masuk (05.00 - 05.30 WIB)' : 'Presensi Pulang (15.00 - 18.00 WIB)' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- KAMERA & FOTO SELFIE -->
                                <div class="rounded-2xl border border-slate-200 p-5 text-center">
                                    <h2 class="font-bold text-slate-900 mb-3 text-left">
                                        Kamera Selfie Wajah
                                    </h2>

                                    <div
                                        class="relative w-full max-w-md mx-auto aspect-video bg-slate-900 rounded-2xl overflow-hidden shadow-inner flex items-center justify-center">
                                        <video id="video" autoplay playsinline class="w-full h-full object-cover"></video>
                                        <canvas id="canvas" class="hidden"></canvas>
                                        <img id="photo-preview" class="hidden w-full h-full object-cover" alt="Preview Foto">
                                    </div>

                                    <div class="mt-4 flex justify-center gap-3">
                                        <button type="button" id="capture-btn" onclick="takeSnapshot()"
                                            class="rounded-xl bg-emerald-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-emerald-700 shadow">
                                            📸 Ambil Foto Selfie
                                        </button>
                                        <button type="button" id="retake-btn" onclick="retakePhoto()"
                                            class="hidden rounded-xl bg-amber-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-amber-700 shadow">
                                            🔄 Foto Ulang
                                        </button>
                                    </div>
                                </div>

                                <!-- INFORMASI GPS -->
                                <div class="rounded-2xl border border-slate-200 p-5">
                                    <h2 class="font-bold text-slate-900">
                                        Informasi Koordinat GPS
                                    </h2>

                                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                        <div class="flex justify-between border-b pb-2">
                                            <span class="text-slate-500">Latitude</span>
                                            <span id="latitude" class="font-semibold text-slate-800">Menunggu GPS...</span>
                                        </div>

                                        <div class="flex justify-between border-b pb-2">
                                            <span class="text-slate-500">Longitude</span>
                                            <span id="longitude" class="font-semibold text-slate-800">Menunggu GPS...</span>
                                        </div>

                                        <div class="flex justify-between border-b pb-2">
                                            <span class="text-slate-500">Akurasi GPS</span>
                                            <span id="accuracy" class="font-semibold text-slate-800">Menunggu GPS...</span>
                                        </div>

                                        <div class="flex justify-between border-b pb-2">
                                            <span class="text-slate-500">Jarak ke Area Kerja</span>
                                            <span id="distance" class="font-semibold text-slate-800">Dihitung saat verifikasi</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- STATUS KOTAK -->
                                <div id="status"
                                    class="rounded-xl bg-blue-50 p-4 text-center text-sm font-semibold text-blue-800">
                                    Kamera aktif. Silakan posisikan wajah dan klik "Ambil Foto Selfie".
                                </div>

                                <!-- TOMBOL SUBMIT -->
                                <button type="button" id="submit-btn" onclick="processAttendance()" disabled
                                    class="w-full rounded-2xl bg-blue-900 px-5 py-4 font-bold text-white transition hover:bg-blue-950 disabled:opacity-40 shadow-lg">
                                    {{ $timeStatus['action_type'] === 'masuk' ? 'Kirim Presensi Masuk (GPS & Selfie)' : 'Kirim Presensi Pulang (GPS & Selfie)' }}
                                </button>
                            @endif

                        </div>
                    </div>

                    <p class="mt-5 text-center text-xs text-slate-500">
                        © {{ date('Y') }} Presensi Cleaning Service - Sistem Monitoring Presensi Petugas
                    </p>

                </div>
            </div>
        </main>
    </div>

    <!-- Script JavaScript Kamera & GPS -->
    @if ($timeStatus['can_action'])
    <script>
        let mediaStream = null;
        let capturedBlob = null;
        let currentLatitude = null;
        let currentLongitude = null;
        let currentAccuracy = null;

        // 1. Mengaktifkan Kamera Depan
        async function startCamera() {
            const video = document.getElementById('video');
            const status = document.getElementById('status');

            try {
                mediaStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } },
                    audio: false
                });
                video.srcObject = mediaStream;
                status.textContent = 'Kamera aktif. Posisikan wajah Anda lalu klik "Ambil Foto Selfie".';
                status.className = 'rounded-xl bg-blue-100 p-4 text-center text-sm font-semibold text-blue-800';
            } catch (error) {
                console.error('Gagal akses kamera:', error);
                status.textContent = 'Gagal mengakses kamera. Pastikan izin kamera aktif pada peramban Anda.';
                status.className = 'rounded-xl bg-red-100 p-4 text-center text-sm font-semibold text-red-800';
            }
        }

        // 2. Mengambil Snapshot Foto
        function takeSnapshot() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const preview = document.getElementById('photo-preview');
            const captureBtn = document.getElementById('capture-btn');
            const retakeBtn = document.getElementById('retake-btn');
            const submitBtn = document.getElementById('submit-btn');

            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;

            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            canvas.toBlob(function (blob) {
                capturedBlob = blob;
                preview.src = URL.createObjectURL(blob);

                video.classList.add('hidden');
                preview.classList.remove('hidden');

                if (mediaStream) {
                    mediaStream.getTracks().forEach(track => track.stop());
                }

                captureBtn.classList.add('hidden');
                retakeBtn.classList.remove('hidden');
                submitBtn.disabled = false;

                document.getElementById('status').textContent = 'Foto selfie berhasil diambil. Klik tombol kirim di bawah.';
                document.getElementById('status').className = 'rounded-xl bg-emerald-100 p-4 text-center text-sm font-semibold text-emerald-800';
            }, 'image/jpeg', 0.85);
        }

        // 3. Ulang Foto
        function retakePhoto() {
            const video = document.getElementById('video');
            const preview = document.getElementById('photo-preview');
            const captureBtn = document.getElementById('capture-btn');
            const retakeBtn = document.getElementById('retake-btn');
            const submitBtn = document.getElementById('submit-btn');

            capturedBlob = null;
            preview.classList.add('hidden');
            video.classList.remove('hidden');

            retakeBtn.classList.add('hidden');
            captureBtn.classList.remove('hidden');
            submitBtn.disabled = true;

            startCamera();
        }

        // 4. Deteksi GPS & Kirim Data
        function processAttendance() {
            const status = document.getElementById('status');
            const submitBtn = document.getElementById('submit-btn');

            if (!capturedBlob) {
                alert('Silakan ambil foto selfie terlebih dahulu.');
                return;
            }

            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung layanan lokasi GPS.');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Mendeteksi Koordinat GPS...';
            status.textContent = 'Sedang mencari lokasi akurat perangkat Anda...';
            status.className = 'rounded-xl bg-blue-100 p-4 text-center text-sm font-semibold text-blue-800';

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    currentLatitude = position.coords.latitude;
                    currentLongitude = position.coords.longitude;
                    currentAccuracy = position.coords.accuracy;

                    document.getElementById('latitude').textContent = currentLatitude.toFixed(8);
                    document.getElementById('longitude').textContent = currentLongitude.toFixed(8);
                    document.getElementById('accuracy').textContent = currentAccuracy.toFixed(2) + ' meter';

                    sendDataToBackend();
                },
                function (error) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Coba Kirim Ulang Presensi';

                    const messages = {
                        1: 'Izin akses GPS ditolak. Silakan aktifkan izin lokasi di browser.',
                        2: 'Sinyal GPS tidak ditemukan. Pastikan GPS aktif.',
                        3: 'Waktu pencarian GPS habis. Silakan coba lagi.'
                    };
                    status.textContent = messages[error.code] || 'Gagal mengambil koordinat lokasi.';
                    status.className = 'rounded-xl bg-red-100 p-4 text-center text-sm font-semibold text-red-800';
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        }

        // 5. Mengirim Data ke Backend
        function sendDataToBackend() {
            const status = document.getElementById('status');
            const submitBtn = document.getElementById('submit-btn');

            let formData = new FormData();
            formData.append('latitude', currentLatitude);
            formData.append('longitude', currentLongitude);
            formData.append('accuracy', currentAccuracy);
            formData.append('photo', capturedBlob, 'selfie.jpg');

            submitBtn.textContent = 'Menyimpan Presensi ke Server...';

            fetch('{{ route("cs.check-location") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok && response.status !== 422) {
                        throw new Error(data.message || 'Terjadi kesalahan sistem.');
                    }
                    return { status: response.status, body: data };
                })
                .then(result => {
                    if (result.body.distance !== undefined && result.body.distance !== null) {
                        document.getElementById('distance').textContent = result.body.distance + ' meter';
                    }

                    status.textContent = result.body.message;

                    if (result.body.success) {
                        status.className = 'rounded-xl bg-emerald-100 p-4 text-center text-sm font-semibold text-emerald-800';
                        submitBtn.textContent = '✅ Berhasil! Mengalihkan...';
                        setTimeout(() => {
                            window.location.href = "{{ route('dashboard.cs') }}";
                        }, 1500);
                    } else {
                        status.className = 'rounded-xl bg-red-100 p-4 text-center text-sm font-semibold text-red-800';
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Coba Kirim Ulang';
                    }
                })
                .catch(error => {
                    status.textContent = error.message;
                    status.className = 'rounded-xl bg-red-100 p-4 text-center text-sm font-semibold text-red-800';
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Coba Kirim Ulang';
                });
        }
    </script>
    @endif

</body>

</html>
