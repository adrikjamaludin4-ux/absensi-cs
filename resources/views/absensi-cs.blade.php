<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Absensi CS | Presensi Cleaning Service</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


<body class="min-h-screen bg-slate-100 text-slate-800" onload="startCamera()">

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
                        <h1 class="font-bold tracking-wide">Presensi Cleaning Service</h1>
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

        <!-- KONTEN KANAN -->
        <main class="min-w-0 flex-1">

            <!-- HEADER -->
            <header class="flex items-center justify-between border-b border-slate-200 bg-white px-6 py-4">
                <div>
                    <h2 class="font-bold text-lg text-blue-950">Absensi Petugas</h2>
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

                        <div class="bg-blue-900 px-6 py-8 text-white">
                            <p class="text-sm font-medium text-blue-200">
                                Presensi Cleaning Service
                            </p>

                            <h1 class="mt-2 text-2xl font-bold">
                                Verifikasi Absensi (GPS & Foto Selfie)
                            </h1>

                            <p class="mt-2 text-sm text-blue-100">
                                Pastikan Anda berada di area kerja Presensi Cleaning Service dan wajah terlihat jelas.
                            </p>
                        </div>

                        <div class="space-y-6 p-6">

                            <div class="rounded-2xl bg-blue-50 p-5">
                                <p class="text-sm font-semibold text-blue-900">
                                    Petugas Cleaning Service
                                </p>

                                <p class="mt-1 text-lg font-bold">
                                    {{ Auth::user()->name }}
                                </p>

                                <p class="mt-1 text-xs text-blue-700">
                                    Radius maksimal absensi: 50 meter
                                </p>
                            </div>

                            <!-- Bagian Kamera & Pratinjau Foto -->
                            <div class="rounded-2xl border border-slate-200 p-5 text-center">
                                <h2 class="font-bold text-slate-900 mb-3 text-left">
                                    Kamera Selfie Wajah
                                </h2>

                                <div
                                    class="relative w-full max-w-md mx-auto aspect-video bg-slate-900 rounded-xl overflow-hidden shadow-inner flex items-center justify-center">
                                    <!-- Elemen Video untuk Preview Kamera -->
                                    <video id="video" autoplay playsinline class="w-full h-full object-cover"></video>

                                    <!-- Elemen Canvas tersembunyi untuk proses capture foto -->
                                    <canvas id="canvas" class="hidden"></canvas>

                                    <!-- Hasil Tangkapan Foto (Preview) -->
                                    <img id="photo-preview" class="hidden w-full h-full object-cover"
                                        alt="Preview Foto">
                                </div>

                                <div class="mt-4 flex justify-center gap-3">
                                    <button type="button" id="capture-btn" onclick="takeSnapshot()"
                                        class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-700 shadow">
                                        📸 Ambil Foto Selfie
                                    </button>
                                    <button type="button" id="retake-btn" onclick="retakePhoto()"
                                        class="hidden rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-amber-700 shadow">
                                        🔄 Foto Ulang
                                    </button>
                                </div>
                            </div>

                            <!-- Informasi GPS -->
                            <div class="rounded-2xl border border-slate-200 p-5">
                                <h2 class="font-bold text-slate-900">
                                    Informasi GPS
                                </h2>

                                <div class="mt-4 space-y-3 text-sm">
                                    <div class="flex justify-between gap-3">
                                        <span class="text-slate-500">Latitude</span>
                                        <span id="latitude" class="font-semibold">Belum tersedia</span>
                                    </div>

                                    <div class="flex justify-between gap-3">
                                        <span class="text-slate-500">Longitude</span>
                                        <span id="longitude" class="font-semibold">Belum tersedia</span>
                                    </div>

                                    <div class="flex justify-between gap-3">
                                        <span class="text-slate-500">Akurasi GPS</span>
                                        <span id="accuracy" class="font-semibold">Belum tersedia</span>
                                    </div>

                                    <div class="flex justify-between gap-3">
                                        <span class="text-slate-500">Jarak ke kantor</span>
                                        <span id="distance" class="font-semibold">Belum tersedia</span>
                                    </div>
                                </div>
                            </div>

                            <div id="status"
                                class="rounded-xl bg-slate-100 p-4 text-center text-sm font-semibold text-slate-600">
                                Kamera aktif. Silakan ambil foto selfie, lalu klik Kirim Absensi.
                            </div>

                            <button type="button" id="submit-btn" onclick="processAttendance()" disabled
                                class="w-full rounded-xl bg-blue-800 px-5 py-4 font-bold text-white transition hover:bg-blue-900 disabled:opacity-50 shadow-md">
                                Kirim Absensi (GPS & Selfie)
                            </button>

                        </div>
                    </div>

                    <p class="mt-5 text-center text-xs text-slate-500">
                        © 2026 Presensi Cleaning Service - Sistem Informasi Absensi
                    </p>

                </div>
            </div>
        </main>
    </div>

    <!-- Script JavaScript Kamera & GPS -->
    <script>
        let mediaStream = null;
        let capturedBlob = null;
        let currentLatitude = null;
        let currentLongitude = null;
        let currentAccuracy = null;

        // 1. Fungsi Mengaktifkan Kamera Depan secara Otomatis saat Halaman Dimuat
        async function startCamera() {
            const video = document.getElementById('video');
            const status = document.getElementById('status');

            try {
                mediaStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } },
                    audio: false
                });
                video.srcObject = mediaStream;
                status.textContent = 'Kamera aktif. Silakan posisikan wajah Anda lalu klik "Ambil Foto Selfie".';
                status.className = 'rounded-xl bg-blue-100 p-4 text-center text-sm font-semibold text-blue-800';
            } catch (error) {
                console.error('Gagal akses kamera:', error);
                status.textContent = 'Gagal mengakses kamera. Pastikan izin kamera diberikan pada browser.';
                status.className = 'rounded-xl bg-red-100 p-4 text-center text-sm font-semibold text-red-800';
            }
        }

        // 2. Fungsi Mengambil Foto (Snapshot)
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

                // Sembunyikan video, tampilkan hasil foto preview
                video.classList.add('hidden');
                preview.classList.remove('hidden');

                // Matikan stream kamera untuk hemat resource
                if (mediaStream) {
                    mediaStream.getTracks().forEach(track => track.stop());
                }

                captureBtn.classList.add('hidden');
                retakeBtn.classList.remove('hidden');
                submitBtn.disabled = false; // Tombol kirim sekarang aktif!

                document.getElementById('status').textContent = 'Foto selfie berhasil diambil. Sekarang klik "Kirim Absensi (GPS & Selfie)".';
                document.getElementById('status').className = 'rounded-xl bg-green-100 p-4 text-center text-sm font-semibold text-green-800';
            }, 'image/jpeg', 0.85);
        }

        // 3. Fungsi Mengulang Foto
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

        // 4. Proses Utama Pengiriman Absensi (GPS + Foto)
        function processAttendance() {
            const status = document.getElementById('status');
            const submitBtn = document.getElementById('submit-btn');

            if (!capturedBlob) {
                alert('Silakan ambil foto selfie terlebih dahulu.');
                return;
            }

            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung GPS.');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Mendeteksi Lokasi & Mengirim Data...';
            status.textContent = 'Sedang mengambil koordinat GPS perangkat...';

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    currentLatitude = position.coords.latitude;
                    currentLongitude = position.coords.longitude;
                    currentAccuracy = position.coords.accuracy;

                    document.getElementById('latitude').textContent = currentLatitude.toFixed(8);
                    document.getElementById('longitude').textContent = currentLongitude.toFixed(8);
                    document.getElementById('accuracy').textContent = currentAccuracy.toFixed(2) + ' meter';

                    // Kirim ke Backend menggunakan FormData
                    sendDataToBackend();
                },
                function (error) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Kirim Absensi (GPS & Selfie)';

                    const messages = {
                        1: 'Izin lokasi ditolak. Izinkan akses lokasi pada browser.',
                        2: 'Lokasi tidak tersedia. Periksa GPS perangkat Anda.',
                        3: 'Waktu pengambilan lokasi habis. Silakan coba lagi.'
                    };
                    status.textContent = messages[error.code] || 'Gagal mengambil lokasi GPS.';
                    status.className = 'rounded-xl bg-red-100 p-4 text-center text-sm font-semibold text-red-800';
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        }

        // 5. Fungsi Kirim Data via Fetch FormData
        function sendDataToBackend() {
            const status = document.getElementById('status');
            const submitBtn = document.getElementById('submit-btn');

            let formData = new FormData();
            formData.append('latitude', currentLatitude);
            formData.append('longitude', currentLongitude);
            formData.append('accuracy', currentAccuracy);
            formData.append('photo', capturedBlob, 'selfie.jpg');

            status.textContent = 'Memvalidasi lokasi dan menyimpan absensi ke server...';

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
                        throw new Error(data.message || 'Terjadi kesalahan pada server.');
                    }
                    return { status: response.status, body: data };
                })
                .then(result => {
                    document.getElementById('distance').textContent =
                        result.body.distance !== null ? result.body.distance + ' meter' : 'Tidak dapat dihitung';

                    status.textContent = result.body.message;

                    if (result.body.success) {
                        status.className = 'rounded-xl bg-green-100 p-4 text-center text-sm font-semibold text-green-800';
                        submitBtn.textContent = 'Absensi Berhasil Direkam!';
                    } else {
                        status.className = 'rounded-xl bg-red-100 p-4 text-center text-sm font-semibold text-red-800';
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Coba Kirim Ulang Absensi';
                    }
                })
                .catch(error => {
                    status.textContent = error.message;
                    status.className = 'rounded-xl bg-red-100 p-4 text-center text-sm font-semibold text-red-800';
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Coba Kirim Ulang Absensi';
                });
        }
    </script>

</body>

</html>