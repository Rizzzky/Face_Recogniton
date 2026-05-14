<!-- Header Section -->
<div class="mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            Pengenalan Wajah Penunggu
        </h1>
        <p class="text-gray-600 dark:text-gray-300">
            Scan wajah penunggu untuk mencatat waktu masuk atau keluar dari ruangan.
        </p>
    </div>
</div>

<!-- Camera Permission Info Banner -->
<div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 rounded-r-lg">
    <p class="text-sm text-blue-700 dark:text-blue-300"><strong>💡 Info:</strong> Browser memerlukan izin untuk mengakses kamera. Klik "Izinkan" saat dialog muncul, atau ubah pengaturan browser untuk memberikan akses kamera. <a href="CAMERA_ACCESS_GUIDE.md" target="_blank" class="underline font-semibold hover:text-blue-800 dark:hover:text-blue-200">Lihat panduan lengkap →</a></p>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Video Section -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
            <!-- Camera Feed -->
            <div class="relative bg-gray-900 rounded-xl overflow-hidden aspect-video mb-6 border-2 border-gray-700">
                <video 
                    id="video" 
                    autoplay 
                    muted 
                    playsinline
                    class="w-full h-full object-cover"
                ></video>
                <canvas 
                    id="canvas" 
                    class="absolute top-0 left-0 w-full h-full"
                ></canvas>
                
                <!-- Loading Indicator -->
                <div id="loading-indicator" class="absolute inset-0 bg-gradient-to-b from-gray-900 to-black flex items-center justify-center z-50 opacity-100 transition-opacity duration-300">
                    <div class="text-center">
                        <div class="mb-4">
                            <div class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto"></div>
                        </div>
                        <p id="loading-text" class="text-white text-sm font-medium mb-2">Menginisialisasi kamera...</p>
                        <p class="text-gray-400 text-xs mb-4">Memuat model face recognition</p>
                        <div class="mt-4 w-32 h-1 bg-gray-700 rounded-full mx-auto overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-500 to-purple-600 animate-pulse"></div>
                        </div>
                    </div>
                </div>

                <!-- Camera Error Placeholder (Hidden by default) -->
                <div id="camera-error-placeholder" class="absolute inset-0 bg-gradient-to-b from-gray-800 to-gray-900 flex items-center justify-center hidden z-40">
                    <div class="text-center px-6">
                        <div class="mb-4">
                            <svg class="w-16 h-16 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M7.08 6.06A9 9 0 1020.9 12"></path>
                            </svg>
                        </div>
                        <p class="text-gray-300 text-sm mb-2">Area Kamera</p>
                        <p class="text-gray-500 text-xs">Menunggu akses kamera...</p>
                    </div>
                </div>
            </div>

            <!-- Detection Status -->
            <div id="detection-status" class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                    <p class="text-sm text-blue-700 dark:text-blue-300 font-medium">Siap untuk pengenalan wajah</p>
                </div>
            </div>

            <!-- Results Display -->
            <div id="hasil" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 min-h-24 mb-6">
                <div style="display: flex; align-items: center; justify-content: center; height: 100%; text-center;">
                    <div>
                        <p style="color: #9CA3AF; font-size: 14px; margin: 0;">👤 Arahkan wajah ke kamera</p>
                        <p style="color: #D1D5DB; font-size: 12px; margin: 4px 0 0 0;">Sistem siap mendeteksi wajah penunggu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Control Panel -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm sticky top-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                Status Scan
            </h3>

            <!-- Status Selection -->
            <div class="space-y-3 mb-8">
                <!-- MASUK Button -->
                <button 
                    onclick="setStatus('MASUK')"
                    class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold px-6 py-4 rounded-xl transition-all duration-200 transform hover:shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-green-500/50 flex items-center justify-center"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-1m0-4V7a3 3 0 013-3h6a3 3 0 013 3v4"></path>
                    </svg>
                    Masuk
                </button>

                <!-- KELUAR Button -->
                <button 
                    onclick="setStatus('KELUAR')"
                    class="w-full bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white font-semibold px-6 py-4 rounded-xl transition-all duration-200 transform hover:shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-orange-500/50 flex items-center justify-center"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-1m0-4V7a3 3 0 013-3h6a3 3 0 013 3v4"></path>
                    </svg>
                    Keluar
                </button>
            </div>

            <!-- Current Status -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">STATUS SEKARANG:</p>
                <p id="current-status" class="text-2xl font-bold text-gray-900 dark:text-white">
                    -
                </p>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <p class="text-xs font-semibold text-blue-700 dark:text-blue-300 mb-2">💡 Panduan:</p>
                <ul class="text-xs text-blue-600 dark:text-blue-400 space-y-1">
                    <li>1. Arahkan wajah ke kamera</li>
                    <li>2. Tunggu deteksi selesai</li>
                    <li>3. Klik tombol Masuk/Keluar</li>
                    <li>4. Sistem otomatis merekam</li>
                </ul>
                <div class="flex gap-2 mt-3">
                    <button onclick="showDiagnostic()" style="background-color: #3b82f6; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 11px; flex: 1;">
                        🔍 Cek Data
                    </button>
                    <button onclick="startTestDetection()" style="background-color: #10b981; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 11px; flex: 1;">
                        ▶️ Test Demo
                    </button>
                    <button onclick="stopTestDetection()" style="background-color: #ef4444; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 11px; flex: 1;">
                        ⏹️ Stop
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="status_scan" value="">

<!-- Styles for smooth transitions and canvas -->
<style>
    #loading-indicator {
        transition: opacity 0.3s ease-out;
    }
    
    #video {
        background: #000;
        display: block;
    }

    #canvas {
        display: none;
        z-index: 50;
        cursor: crosshair;
        image-rendering: crisp-edges;
    }

    #canvas.active {
        display: block;
    }

    /* Hasil deteksi styling */
    #hasil {
        min-height: auto;
        transition: all 0.3s ease;
    }

    #hasil p {
        margin: 0;
    }

    /* Video container position relative untuk canvas overlay */
    .video-container {
        position: relative;
    }
</style>

<!-- Scripts -->
<script src="assets/js/face-api.min.js"></script>
<script src="assets/js/face-recognition.js"></script>

<script>
// Update current status display
function setStatus(status) {
    document.getElementById('status_scan').value = status;
    document.getElementById('current-status').textContent = status;
    
    if (typeof window.setScanStatus === 'function') {
        window.setScanStatus(status);
    }

    // Update status indicator
    const statusEl = document.getElementById('detection-status');
    if (status === 'MASUK') {
        statusEl.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <p class="text-sm text-green-700 dark:text-green-300 font-medium">Status: MASUK</p>
            </div>
        `;
        statusEl.className = 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6';
    } else if (status === 'KELUAR') {
        statusEl.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></div>
                <p class="text-sm text-orange-700 dark:text-orange-300 font-medium">Status: KELUAR</p>
            </div>
        `;
        statusEl.className = 'bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4 mb-6';
    }
}

// Hide loading when camera ready (optimized)
video.addEventListener('canplay', function() {
    console.log('Video ready - canplay event triggered');
    const loadingIndicator = document.getElementById('loading-indicator');
    if (loadingIndicator) {
        loadingIndicator.style.display = 'none';
    }
});

// Fallback: Hide loading after video starts playing
video.addEventListener('play', function() {
    console.log('Video playing');
    const loadingIndicator = document.getElementById('loading-indicator');
    if (loadingIndicator && loadingIndicator.style.display !== 'none') {
        setTimeout(() => {
            if (loadingIndicator && loadingIndicator.style.display !== 'none') {
                loadingIndicator.style.display = 'none';
            }
        }, 1000);
    }
});
</script>
