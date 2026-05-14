<!-- Header Section -->
<div class="mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            Input Data Penunggu Pasien
        </h1>
        <p class="text-gray-600 dark:text-gray-300">
            Tambahkan data penunggu pasien baru dengan mengisi form di bawah dan ambil foto dari kamera.
        </p>
    </div>
</div>

<!-- Main Form Container -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form Section -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
            <form id="formPenunggu" class="space-y-6">
                
                <!-- Row 1: No RM & Nama Pasien -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            No Rekam Medik
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="no_rm"
                            placeholder="Cth: RM-2024001"
                            required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Nama Pasien
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama_pasien"
                            placeholder="Cth: Budi Santoso"
                            required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        >
                    </div>
                </div>

                <!-- Row 2: Nama Penunggu & Nama Ruangan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Nama Penunggu
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama_penunggu"
                            placeholder="Cth: Siti Nurhaliza"
                            required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Nama Ruangan
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama_ruangan"
                            placeholder="Cth: Ruang A-101"
                            required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        >
                    </div>
                </div>

                <!-- Tanggal Masuk -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Tanggal Masuk
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="date"
                        name="tanggal_masuk"
                        required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    >
                </div>

                <!-- Hidden input for photo -->
                <input type="hidden" name="foto_base64" id="foto_base64">

                <!-- Action Buttons -->
                <div class="flex items-center gap-3 pt-4">
                    <button
                        type="button"
                        onclick="ambilFotoDanSimpan()"
                        class="flex-1 inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-200 transform hover:shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-blue-500/50"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Simpan Data & Foto
                    </button>
                    <a href="index.php?page=dashboard" class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Camera Section -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm sticky top-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Foto Penunggu Pasien
            </h3>
            
            <!-- Video Container -->
            <div class="relative mb-4 bg-gray-900 rounded-xl overflow-hidden aspect-video flex items-center justify-center">
                <video
                    id="video"
                    autoplay
                    playsinline
                    class="w-full h-full object-cover"
                ></video>
                <div id="camera-loading" class="absolute inset-0 bg-gray-900 flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
                        <p class="text-white text-sm">Menginisialisasi kamera...</p>
                    </div>
                </div>
            </div>

            <!-- Canvas for capture (hidden) -->
            <canvas
                id="canvas"
                style="display:none;"
            ></canvas>

            <!-- Camera Status -->
            <div id="camera-status" class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3 mb-4">
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                    <p class="text-sm text-green-700 dark:text-green-300 font-medium">Kamera siap</p>
                </div>
            </div>

            <!-- Help Text -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <p class="text-xs text-blue-700 dark:text-blue-300 font-medium mb-2">💡 Tips:</p>
                <ul class="text-xs text-blue-600 dark:text-blue-400 space-y-1">
                    <li>✓ Posisikan wajah di tengah frame</li>
                    <li>✓ Pastikan pencahayaan cukup baik</li>
                    <li>✓ Klik tombol "Simpan Data & Foto"</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const fotoBase64 = document.getElementById('foto_base64');
const formPenunggu = document.getElementById('formPenunggu');
const cameraLoading = document.getElementById('camera-loading');
const cameraStatus = document.getElementById('camera-status');

// Initialize camera
function initializeCamera() {
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ 
            video: { 
                facingMode: 'user',
                width: { ideal: 640 },
                height: { ideal: 480 }
            } 
        })
            .then(function(stream) {
                video.srcObject = stream;
                video.onloadedmetadata = function() {
                    video.play();
                    if (cameraLoading) {
                        cameraLoading.style.display = 'none';
                    }
                };
            })
            .catch(function(err) {
                console.error(err);
                const errorMsg = err.name === 'NotAllowedError' 
                    ? 'Izin kamera ditolak. Periksa pengaturan browser Anda.' 
                    : 'Kamera tidak bisa diakses: ' + err.message;
                
                if (cameraStatus) {
                    cameraStatus.innerHTML = `
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                            <p class="text-sm text-red-700 dark:text-red-300 font-medium">${errorMsg}</p>
                        </div>
                    `;
                    cameraStatus.classList.remove('bg-green-50', 'dark:bg-green-900/20', 'border-green-200', 'dark:border-green-800', 'text-green-700', 'dark:text-green-300');
                    cameraStatus.classList.add('bg-red-50', 'dark:bg-red-900/20', 'border-red-200', 'dark:border-red-800', 'text-red-700', 'dark:text-red-300');
                }
                alert(errorMsg);
            });
    } else {
        alert("Browser Anda tidak mendukung akses kamera");
        if (cameraStatus) {
            cameraStatus.innerHTML = `
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                    <p class="text-sm text-red-700 dark:text-red-300 font-medium">Browser tidak mendukung kamera</p>
                </div>
            `;
        }
    }
}

// Capture photo and submit
function ambilFotoDanSimpan() {
    // Validate form first
    if (!formPenunggu.checkValidity()) {
        alert('Harap isi semua field yang diperlukan');
        return;
    }

    try {
        const ctx = canvas.getContext('2d');
        
        // Set canvas dimensions to match video
        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;
        
        // Draw image from camera
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Convert to base64
        const imageData = canvas.toDataURL('image/png');

        // Collect form data
        const formData = new FormData(formPenunggu);
        formData.append('foto_base64', imageData);

        // Show loading state
        const submitBtn = document.querySelector('button[onclick="ambilFotoDanSimpan()"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<div class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></div>Menyimpan...';

        // Submit via AJAX
        fetch('pasien/proses_simpan.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;

            if (data.success) {
                alert('✅ Data berhasil disimpan!');
                // Redirect
                window.location.href = data.redirect;
            } else {
                alert('❌ Gagal menyimpan: ' + data.message);
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan: ' + error.message);
        });

    } catch (error) {
        console.error('Error capturing photo:', error);
        alert('❌ Gagal mengambil foto. Silakan coba lagi.');
    }
}

// Stop camera on page unload
window.addEventListener('beforeunload', function() {
    if (video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop());
    }
});

// Initialize camera when page loads
document.addEventListener('DOMContentLoaded', initializeCamera);
</script>