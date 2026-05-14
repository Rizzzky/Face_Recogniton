const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const hasil = document.getElementById('hasil');
const loadingIndicator = document.getElementById('loading-indicator');
const cameraErrorPlaceholder = document.getElementById('camera-error-placeholder');

let modelsLoaded = false;
let faceMatcher = null;
let labeledFaceDescriptors = [];
let fullPersonData = [];

// Test mode untuk simulate deteksi tanpa camera
let testModeActive = false;
let testDetectionInterval = null;

/* ============================= */
/* INIT CAMERA FIRST (PRIORITY) */
/* ============================= */
function initializeCamera() {
  if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({ 
      video: { 
        width: { ideal: 1280 },
        height: { ideal: 720 },
        facingMode: 'user'
      } 
    })
      .then(stream => {
        video.srcObject = stream;
        video.onloadedmetadata = function() {
          video.play();
          console.log('Camera started successfully');
          // Sembunyikan error placeholder saat kamera berhasil
          if (cameraErrorPlaceholder) {
            cameraErrorPlaceholder.classList.add('hidden');
          }
          if (loadingIndicator) {
            loadingIndicator.style.opacity = '0';
          }
        };
      })
      .catch(err => {
        console.error('Kamera error:', err);
        
        // Tampilkan camera error placeholder
        if (cameraErrorPlaceholder) {
          cameraErrorPlaceholder.classList.remove('hidden');
        }
        
        // Sembunyikan loading indicator tapi tetap tampilkan area video
        if (loadingIndicator) {
          loadingIndicator.style.display = 'none';
        }
        
        // Tampilkan pesan error yang informatif
        let errorMsg = '❌ Kamera tidak bisa diakses';
        let solution = '';
        
        // Check if using HTTP (non-secure)
        const isHTTP = window.location.protocol === 'http:';
        const httpsNote = isHTTP ? '<p style="color: #f59e0b; font-size: 11px; margin: 8px 0 0 0;"><strong>⚠️ Catatan:</strong> Anda menggunakan HTTP (non-secure). Beberapa browser memerlukan HTTPS untuk akses kamera. Hubungi administrator untuk setup HTTPS.</p>' : '';
        
        if (err.name === 'NotAllowedError') {
          errorMsg = '🔒 Izin kamera ditolak';
          solution = '<strong>Solusi:</strong><br>1. Buka Settings browser > Privasi > Izin kamera<br>2. Tambahkan localhost ke whitelist kamera<br>3. Refresh halaman ini';
        } else if (err.name === 'NotFoundError') {
          errorMsg = '📷 Kamera tidak ditemukan';
          solution = '<strong>Solusi:</strong><br>1. Colokkan kamera eksternal<br>2. Atau gunakan perangkat dengan kamera built-in';
        } else if (err.name === 'NotReadableError') {
          errorMsg = '⚠️ Kamera sedang digunakan aplikasi lain';
          solution = '<strong>Solusi:</strong><br>1. Tutup aplikasi lain yang menggunakan kamera<br>2. Refresh halaman ini';
        } else if (err.name === 'SecurityError') {
          errorMsg = '🔐 Akses kamera diblokir';
          solution = '<strong>Solusi:</strong><br>1. Gunakan HTTPS atau localhost<br>2. Buka browser console untuk detail error';
        }
        
        hasil.innerHTML = `
          <div style="padding: 16px; background-color: #fee2e2; border-left: 4px solid #dc2626; border-radius: 6px;">
            <h3 style="color: #dc2626; font-size: 16px; margin: 0 0 12px 0;">${errorMsg}</h3>
            <p style="color: #991b1b; font-size: 13px; line-height: 1.6; margin: 0 0 12px 0;">
              ${solution}
            </p>
            <button onclick="retryCameraAccess()" style="background-color: #dc2626; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; margin-top: 8px;">
              🔄 Coba Lagi
            </button>
            <p style="color: #991b1b; font-size: 11px; margin: 8px 0 0 0;">
              <em>Error: ${err.name}</em>
            </p>
            ${httpsNote}
          </div>
        `;
      });
  } else {
    alert("Browser Anda tidak mendukung akses kamera");
    if (cameraErrorPlaceholder) {
      cameraErrorPlaceholder.classList.remove('hidden');
    }
    if (loadingIndicator) {
      loadingIndicator.style.display = 'none';
    }
  }
}

// Start camera immediately on page load
document.addEventListener('DOMContentLoaded', initializeCamera);

// Add retry button functionality
window.retryCameraAccess = function() {
  // Reset elements
  const errorPlaceholder = document.getElementById('camera-error-placeholder');
  const loadingIndicator = document.getElementById('loading-indicator');
  
  if (errorPlaceholder) {
    errorPlaceholder.classList.add('hidden');
  }
  
  if (loadingIndicator) {
    loadingIndicator.style.display = 'block';
    loadingIndicator.style.opacity = '1';
  }
  
  // Try camera again
  initializeCamera();
};

// Diagnostic function
window.showDiagnostic = function() {
  const diagnosticMsg = `
📊 DIAGNOSTIC INFO
==================
Camera Status: ${video.srcObject ? '✅ ON' : '❌ OFF'}
Models Loaded: ${modelsLoaded ? '✅ YES' : '❌ NO'}
Labeled Descriptors: ${labeledFaceDescriptors.length} wajah

Fetching data from: pasien/get_data_penunggu.php
`;
  
  console.log(diagnosticMsg);
  
  // Fetch and display data status
  fetch('pasien/get_data_penunggu.php')
    .then(r => r.json())
    .then(data => {
      alert(
        `Status Database:\n\n` +
        `Total Data: ${data.total}\n` +
        `Descriptors Loaded: ${labeledFaceDescriptors.length}\n` +
        `Models: ${modelsLoaded ? 'Loaded' : 'Loading'}\n` +
        `Camera: ${video.srcObject ? 'Active' : 'Inactive'}\n\n` +
        `${data.error ? '❌ ERROR: ' + data.error : '✅ Data OK'}\n\n` +
        `${data.message || ''}`
      );
      console.table(data.data);
    })
    .catch(err => {
      alert('❌ Error fetching diagnostic:\n' + err.message);
      console.error(err);
    });
};

/* ============================= */
/* LOAD MODEL FACE API (BACKGROUND) */
/* ============================= */
function updateLoadingProgress(text) {
  const loadingText = document.getElementById('loading-text');
  if (loadingText) {
    loadingText.textContent = text;
  }
}

// Mulai load model di background setelah 1 detik (biarkan kamera load dulu)
setTimeout(() => {
  console.log('Starting model loading...');
  updateLoadingProgress('Memuat model face recognition...');
  
  Promise.all([
    faceapi.nets.ssdMobilenetv1.loadFromUri('assets/models'),
    faceapi.nets.faceLandmark68Net.loadFromUri('assets/models'),
    faceapi.nets.faceRecognitionNet.loadFromUri('assets/models')
  ]).then(() => {
    modelsLoaded = true;
    updateLoadingProgress('Memuat data wajah...');
    return loadLabeledImages();
  })
  .then(descriptors => {
    labeledFaceDescriptors = descriptors;
    updateLoadingProgress('Siap untuk scan...');
    startRecognitionLoop();
    
    // Sembunyikan loading setelah model siap
    setTimeout(() => {
      if (loadingIndicator) {
        loadingIndicator.style.opacity = '0';
        loadingIndicator.style.pointerEvents = 'none';
        setTimeout(() => {
          loadingIndicator.style.display = 'none';
        }, 300);
      }
    }, 1500);
    
    console.log('Models loaded successfully');
  })
  .catch(err => {
    console.error('Gagal load model:', err);
    if (loadingIndicator) {
      loadingIndicator.style.display = 'none';
    }
    hasil.innerHTML = '<div style="padding: 12px; background-color: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 6px;"><h4 style="color: #b45309; margin: 0 0 8px 0;">⚠️ Model face recognition gagal dimuat</h4><p style="color: #92400e; font-size: 13px; margin: 0;">Kamera tetap berfungsi, tapi deteksi wajah tidak tersedia. Periksa koneksi internet Anda.</p></div>';
  });
}, 1000);

/* ============================= */
/* LOAD DATA WAJAH DARI DATABASE (OPTIMIZED) */
/* ============================= */
let dataCache = null;
let lastCacheTime = 0;

async function loadLabeledImages() {
  try {
    const now = Date.now();
    // Cache data selama 5 menit
    if (dataCache && (now - lastCacheTime) < 300000) {
      return dataCache;
    }

    const response = await fetch('pasien/get_data_penunggu.php');
    const result = await response.json();

    // Handle error from server
    if (result.error) {
      console.error('Server error:', result.error);
      throw new Error(result.error);
    }

    fullPersonData = result.data || [];

    // Check if data is empty
    if (!result.data || result.data.length === 0) {
      console.warn('No face data available:', result.message);
      if (loadingIndicator) {
        loadingIndicator.style.display = 'none';
      }
      hasil.innerHTML = `
        <div style="padding: 16px; background-color: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 6px;">
          <h3 style="color: #b45309; font-size: 16px; margin: 0 0 12px 0;">⚠️ Tidak ada data wajah ditemukan</h3>
          <p style="color: #92400e; font-size: 13px; margin: 0 0 8px 0;">
            ${result.message || 'Tidak ada data penunggu yang tersedia di database'}<br><br>
            <strong>Solusi:</strong><br>
            1. Pastikan sudah ada data penunggu di menu "Input Penunggu"<br>
            2. Foto penunggu harus tersedia dan valid<br>
            3. Status penunggu harus aktif
          </p>
        </div>
      `;
      return [];
    }

    const descriptors = await Promise.all(
      result.data.map(async (item) => {
        try {
          // Validate item
          if (!item.label || !item.foto) {
            console.warn('Skipping invalid item:', item);
            return null;
          }

          const img = await faceapi.fetchImage(item.foto);

          const detection = await faceapi
            .detectSingleFace(img)
            .withFaceLandmarks()
            .withFaceDescriptor();

          if (!detection) {
            console.log('Wajah tidak terdeteksi pada:', item.label);
            return null;
          }

          return new faceapi.LabeledFaceDescriptors(
            item.label,
            [detection.descriptor]
          );
        } catch (error) {
          console.error('Gagal memproses wajah:', item.label, error);
          return null;
        }
      })
    );

    const validDescriptors = descriptors.filter(item => item !== null);
    
    if (validDescriptors.length === 0) {
      console.warn('No valid face descriptors found');
      if (loadingIndicator) {
        loadingIndicator.style.display = 'none';
      }
      hasil.innerHTML = `
        <div style="padding: 16px; background-color: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 6px;">
          <h3 style="color: #b45309; font-size: 16px; margin: 0 0 12px 0;">⚠️ Model wajah tidak dapat diproses</h3>
          <p style="color: #92400e; font-size: 13px; margin: 0;">
            Foto penunggu ada, tapi wajah tidak terdeteksi pada beberapa foto.<br>
            Gunakan foto dengan wajah yang jelas dan tidak blur.
          </p>
        </div>
      `;
      return [];
    }

    dataCache = validDescriptors;
    lastCacheTime = now;
    console.log('Data wajah berhasil dimuat:', validDescriptors.length, 'wajah');
    return validDescriptors;

  } catch (error) {
    console.error('Gagal mengambil data penunggu:', error);
    if (loadingIndicator) {
      loadingIndicator.style.display = 'none';
    }
    hasil.innerHTML = `
      <div style="padding: 16px; background-color: #fee2e2; border-left: 4px solid #dc2626; border-radius: 6px;">
        <h3 style="color: #dc2626; font-size: 16px; margin: 0 0 12px 0;">❌ Gagal memuat data penunggu</h3>
        <p style="color: #991b1b; font-size: 13px; margin: 0;">
          <strong>Error:</strong> ${error.message}<br><br>
          <strong>Periksa:</strong><br>
          1. Koneksi database sudah terhubung?<br>
          2. File "pasien/get_data_penunggu.php" dapat diakses?<br>
          3. Tabel "penunggu_pasien" sudah dibuat?<br>
          4. Ada data penunggu di database?
        </p>
      </div>
    `;
    return [];
  }
}

/* ============================= */
/* FACE RECOGNITION + RIWAYAT (OPTIMIZED) */
/* ============================= */
let statusScan = '';
let lastDetected = '';
let detectionActive = false;
let recognitionInterval = null;
let isRecognitionStarted = false;
let videoReady = false;

function setScanStatus(status) {
  statusScan = status;
}

video.addEventListener('play', () => {
  videoReady = true;
  startRecognitionLoop();
});

function startRecognitionLoop() {
  if (isRecognitionStarted || !modelsLoaded || !videoReady || !labeledFaceDescriptors || labeledFaceDescriptors.length === 0) {
    return;
  }

  isRecognitionStarted = true;
  faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.5);

  const displaySize = {
    width: video.videoWidth || 640,
    height: video.videoHeight || 480
  };

  canvas.width = displaySize.width;
  canvas.height = displaySize.height;
  faceapi.matchDimensions(canvas, displaySize);

  recognitionInterval = setInterval(async () => {
    if (!faceMatcher) {
      console.warn('FaceMatcher not yet initialized');
      return;
    }

    const detections = await faceapi
      .detectAllFaces(video)
      .withFaceLandmarks()
      .withFaceDescriptors();

    const resizedDetections = faceapi.resizeResults(detections, displaySize);

    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    const results = resizedDetections.map(d =>
      faceMatcher.findBestMatch(d.descriptor)
    );

    if (detections.length > 0) {
      console.log(`Detections: ${detections.length}, Results: ${results.length}`);
    }

    if (results.length > 0) {
      canvas.classList.add('active');
      if (cameraErrorPlaceholder) {
        cameraErrorPlaceholder.style.display = 'none';
      }
    } else {
      canvas.classList.remove('active');
      if (cameraErrorPlaceholder) {
        cameraErrorPlaceholder.style.display = '';
      }
    }

    for (let i = 0; i < results.length; i++) {
      const result = results[i];
      const box = resizedDetections[i].detection.box;
      const landmarks = resizedDetections[i].landmarks;

      let boxColor, labelColor, backgroundColor;
      let confidence = result.distance;
      let displayLabel = result.label;
      let confidenceScore = 0;

      if (result.label !== 'unknown') {
        confidenceScore = Math.round(Math.max(0, (1 - Math.min(confidence, 1)) * 100));
        boxColor = '#00FF00';
        labelColor = '#000000';
        backgroundColor = 'rgba(0, 255, 0, 0.3)';
      } else {
        boxColor = '#FF0000';
        labelColor = '#FFFFFF';
        backgroundColor = 'rgba(255, 0, 0, 0.2)';
        displayLabel = 'TIDAK DIKENALI';
        confidenceScore = 0;
      }

      ctx.fillStyle = backgroundColor;
      ctx.fillRect(box.x, box.y, box.width, box.height);
      ctx.strokeStyle = boxColor;
      ctx.lineWidth = 3;
      ctx.strokeRect(box.x, box.y, box.width, box.height);

      const labelFontSize = 16;
      ctx.font = `bold ${labelFontSize}px Arial`;
      const textMetrics = ctx.measureText(displayLabel);
      const textWidth = textMetrics.width + 12;
      const textHeight = labelFontSize + 8;
      ctx.fillStyle = boxColor;
      ctx.fillRect(box.x, box.y - textHeight - 5, textWidth, textHeight);
      ctx.fillStyle = labelColor;
      ctx.textBaseline = 'top';
      ctx.fillText(displayLabel, box.x + 6, box.y - textHeight + 2);

      const confidenceText = `Confidence: ${confidenceScore}%`;
      ctx.font = `12px Arial`;
      const confMetrics = ctx.measureText(confidenceText);
      ctx.fillStyle = boxColor;
      ctx.fillRect(box.x, box.y + box.height + 5, confMetrics.width + 8, 20);
      ctx.fillStyle = '#FFFFFF';
      ctx.fillText(confidenceText, box.x + 4, box.y + box.height + 10);

      ctx.fillStyle = '#00FF00';
      ctx.globalAlpha = 0.5;
      if (landmarks && landmarks.positions) {
        landmarks.positions.forEach(point => {
          ctx.beginPath();
          ctx.arc(point.x, point.y, 2, 0, 2 * Math.PI);
          ctx.fill();
        });
      }
      ctx.globalAlpha = 1.0;

      if (result.label !== 'unknown') {
        const fullData = fullPersonData.find(d => d.label === result.label);

        if (fullData) {
          tampilkanData(fullData);

          if (!statusScan) continue;

          if (lastDetected !== fullData.label) {
            lastDetected = fullData.label;

            await fetch('pasien/simpan_riwayat.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body:
                `nama_penunggu=${encodeURIComponent(fullData.label)}` +
                `&nama_pasien=${encodeURIComponent(fullData.nama_pasien)}` +
                `&ruangan=${encodeURIComponent(fullData.ruangan)}` +
                `&status=${encodeURIComponent(statusScan)}`
            });

            setTimeout(() => {
              lastDetected = '';
            }, 5000);
          }
        }
      }
    }
  }, 300);
}

/* ============================= */
/* TAMPILKAN DATA PENUNGGU */
/* ============================= */
function tampilkanData(found) {
  hasil.innerHTML = `
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 16px; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
      <div style="display: flex; gap: 16px;">
        ${found.foto ? `
          <div style="flex-shrink: 0;">
            <img src="${found.foto}" style="width: 80px; height: 80px; border-radius: 8px; object-fit: cover; border: 3px solid white;">
          </div>
        ` : ''}
        <div style="flex: 1;">
          <h3 style="margin: 0 0 8px 0; font-size: 18px; font-weight: bold;">✓ TERDETEKSI</h3>
          <div style="background: rgba(255,255,255,0.2); padding: 8px 12px; border-radius: 6px; margin-bottom: 8px;">
            <p style="margin: 0 0 6px 0; font-size: 12px; opacity: 0.9;">Nama Penunggu</p>
            <p style="margin: 0; font-size: 16px; font-weight: bold;">${found.label}</p>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
            <div style="background: rgba(255,255,255,0.2); padding: 8px 12px; border-radius: 6px;">
              <p style="margin: 0 0 4px 0; font-size: 11px; opacity: 0.9;">Pasien</p>
              <p style="margin: 0; font-size: 13px; font-weight: 600;">${found.nama_pasien}</p>
            </div>
            <div style="background: rgba(255,255,255,0.2); padding: 8px 12px; border-radius: 6px;">
              <p style="margin: 0 0 4px 0; font-size: 11px; opacity: 0.9;">Ruangan</p>
              <p style="margin: 0; font-size: 13px; font-weight: 600;">${found.ruangan}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;
}

/* ============================= */
/* TEST MODE: Simulate Detection for Testing */
/* ============================= */

// Fetch actual data from database and simulate detection
window.startTestDetection = async function() {
  console.log('Starting test detection mode...');
  
  if (!labeledFaceDescriptors || labeledFaceDescriptors.length === 0) {
    alert('❌ No labeled data available. Please wait for data to load.');
    return;
  }
  
  testModeActive = true;
  const canvas = document.getElementById('canvas');
  const video = document.getElementById('video');
  
  if (!canvas) {
    alert('Canvas not found!');
    return;
  }
  
  // Set canvas size
  const displaySize = {
    width: 640,
    height: 480
  };
  
  canvas.width = displaySize.width;
  canvas.height = displaySize.height;
  canvas.classList.add('active');
  
  // Get data from database
  let testData = [];
  try {
    const response = await fetch('pasien/get_data_penunggu.php');
    const data = await response.json();
    testData = data.data || [];
  } catch (error) {
    console.error('Failed to fetch test data:', error);
    alert('❌ Failed to fetch data for test mode');
    return;
  }
  
  if (testData.length === 0) {
    alert('❌ No visitor data available');
    return;
  }
  
  let currentIndex = 0;
  let detectionCount = 0;
  
  // Simulate detection every 2 seconds
  testDetectionInterval = setInterval(() => {
    detectionCount++;
    
    if (!testModeActive) {
      clearInterval(testDetectionInterval);
      return;
    }
    
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Get current data item
    const item = testData[currentIndex % testData.length];
    
    // Create simulated detections with varying positions
    const detections = [];
    
    // Primary detection in center-left
    detections.push({
      x: 80 + (currentIndex % 3) * 30,
      y: 100,
      width: 180,
      height: 220,
      label: item.label,
      nama_pasien: item.nama_pasien,
      ruangan: item.ruangan,
      confidence: 90 + Math.random() * 8
    });
    
    // Secondary detection (optional) in center-right - randomly show
    if (Math.random() > 0.7 && testData.length > 1) {
      const nextItem = testData[(currentIndex + 1) % testData.length];
      detections.push({
        x: 380,
        y: 150,
        width: 160,
        height: 200,
        label: nextItem.label,
        nama_pasien: nextItem.nama_pasien,
        ruangan: nextItem.ruangan,
        confidence: 75 + Math.random() * 15
      });
    }
    
    // Draw all detections
    detections.forEach((detection, idx) => {
      const isFirst = idx === 0;
      const boxColor = isFirst ? '#00FF00' : '#FFAA00';
      const bgColor = isFirst ? 'rgba(0, 255, 0, 0.2)' : 'rgba(255, 170, 0, 0.2)';
      
      // Draw filled rectangle
      ctx.fillStyle = bgColor;
      ctx.fillRect(detection.x, detection.y, detection.width, detection.height);
      
      // Draw border
      ctx.strokeStyle = boxColor;
      ctx.lineWidth = 3;
      ctx.strokeRect(detection.x, detection.y, detection.width, detection.height);
      
      // Draw label background and text
      const labelFontSize = 14;
      ctx.font = `bold ${labelFontSize}px Arial`;
      const textMetrics = ctx.measureText(detection.label);
      const textWidth = textMetrics.width + 12;
      const textHeight = labelFontSize + 8;
      
      ctx.fillStyle = boxColor;
      ctx.fillRect(detection.x, detection.y - textHeight - 5, textWidth, textHeight);
      
      ctx.fillStyle = detection.label === 'TIDAK DIKENALI' ? '#FFFFFF' : '#000000';
      ctx.textBaseline = 'top';
      ctx.fillText(detection.label, detection.x + 6, detection.y - textHeight + 2);
      
      // Draw confidence score
      const confText = `${Math.round(detection.confidence)}%`;
      ctx.font = '11px Arial';
      const confMetrics = ctx.measureText(confText);
      ctx.fillStyle = boxColor;
      ctx.fillRect(detection.x, detection.y + detection.height + 5, confMetrics.width + 8, 18);
      ctx.fillStyle = '#FFFFFF';
      ctx.fillText(confText, detection.x + 4, detection.y + detection.height + 8);
      
      // Show data on first detection
      if (idx === 0) {
        tampilkanData({
          label: detection.label,
          nama_pasien: detection.nama_pasien,
          ruangan: detection.ruangan
        });
      }
    });
    
    // Move to next person every 5 detections
    if (detectionCount % 5 === 0) {
      currentIndex = (currentIndex + 1) % testData.length;
    }
    
    console.log(`Test detection #${detectionCount}: ${detections[0].label}`);
  }, 2000); // Update every 2 seconds
  
  alert(`✅ Test detection started!\n\nShowing ${testData.length} visitors with simulated face detection.\n\nClick "Stop Test" to stop simulation.`);
};

// Stop test detection
window.stopTestDetection = function() {
  if (testDetectionInterval) {
    clearInterval(testDetectionInterval);
    testDetectionInterval = null;
  }
  testModeActive = false;
  
  const canvas = document.getElementById('canvas');
  if (canvas) {
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    canvas.classList.remove('active');
  }
  
  console.log('✅ Test detection stopped');
  alert('✅ Test detection stopped');
};

// Quick test - draw static boxes
window.testBoundingBox = function() {
  const canvas = document.getElementById('canvas');
  const video = document.getElementById('video');
  
  if (!canvas) {
    alert('Canvas not found!');
    return;
  }
  
  // Set canvas size
  const displaySize = {
    width: video.videoWidth || 640,
    height: video.videoHeight || 480
  };
  
  canvas.width = displaySize.width;
  canvas.height = displaySize.height;
  
  // Show canvas
  canvas.classList.add('active');
  
  const ctx = canvas.getContext('2d');
  
  // Clear canvas
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  
  // Draw test bounding box
  const testBoxes = [
    { x: 100, y: 80, width: 150, height: 180, label: 'Test Person 1', confidence: 95 },
    { x: 350, y: 120, width: 140, height: 160, label: 'Test Person 2', confidence: 87 }
  ];
  
  testBoxes.forEach(box => {
    // Draw filled rectangle
    ctx.fillStyle = 'rgba(0, 255, 0, 0.2)';
    ctx.fillRect(box.x, box.y, box.width, box.height);
    
    // Draw border
    ctx.strokeStyle = '#00FF00';
    ctx.lineWidth = 3;
    ctx.strokeRect(box.x, box.y, box.width, box.height);
    
    // Draw label
    const labelFontSize = 16;
    ctx.font = `bold ${labelFontSize}px Arial`;
    const textMetrics = ctx.measureText(box.label);
    const textWidth = textMetrics.width + 12;
    const textHeight = labelFontSize + 8;
    
    ctx.fillStyle = '#00FF00';
    ctx.fillRect(box.x, box.y - textHeight - 5, textWidth, textHeight);
    
    ctx.fillStyle = '#000000';
    ctx.textBaseline = 'top';
    ctx.fillText(box.label, box.x + 6, box.y - textHeight + 2);
    
    // Draw confidence
    const confText = `Confidence: ${box.confidence}%`;
    ctx.font = '12px Arial';
    const confMetrics = ctx.measureText(confText);
    ctx.fillStyle = '#00FF00';
    ctx.fillRect(box.x, box.y + box.height + 5, confMetrics.width + 8, 20);
    ctx.fillStyle = '#FFFFFF';
    ctx.fillText(confText, box.x + 4, box.y + box.height + 10);
  });
  
  console.log('✅ Test bounding boxes drawn on canvas');
};

window.testBoxing = window.testBoundingBox; // Alias untuk kemudahan
