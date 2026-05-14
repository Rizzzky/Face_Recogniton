<script>
// Debug detection directly
console.log('=== DEBUG FACE DETECTION ===');

// Check if elements exist
console.log('Video element:', document.getElementById('video') ? '✅' : '❌');
console.log('Canvas element:', document.getElementById('canvas') ? '✅' : '❌');

// Wait for face-recognition.js to load
setTimeout(() => {
    console.log('\n=== SYSTEM STATUS ===');
    console.log('Models Loaded:', typeof modelsLoaded !== 'undefined' ? modelsLoaded : 'UNDEFINED');
    console.log('Labeled Descriptors:', typeof labeledFaceDescriptors !== 'undefined' ? labeledFaceDescriptors.length : 'UNDEFINED');
    console.log('Face Matcher:', typeof faceMatcher !== 'undefined' ? (faceMatcher ? 'Ready' : 'Not initialized') : 'UNDEFINED');
    
    // Check canvas visibility
    const canvas = document.getElementById('canvas');
    if (canvas) {
        console.log('\n=== CANVAS STATUS ===');
        console.log('Canvas display:', window.getComputedStyle(canvas).display);
        console.log('Canvas z-index:', window.getComputedStyle(canvas).zIndex);
        console.log('Canvas class:', canvas.className);
        console.log('Has active class:', canvas.classList.contains('active'));
    }
    
    // Check video status
    const video = document.getElementById('video');
    if (video) {
        console.log('\n=== VIDEO STATUS ===');
        console.log('Video srcObject:', video.srcObject ? 'Stream active' : 'No stream');
        console.log('Video readyState:', video.readyState); // 0=HAVE_NOTHING, 1=HAVE_METADATA, 2=HAVE_CURRENT_DATA, 3=HAVE_FUTURE_DATA, 4=HAVE_ENOUGH_DATA
        console.log('Video playing:', !video.paused);
    }
}, 3000);

// Add detection log every 2 seconds
let detectionLogCount = 0;
const detectionInterval = setInterval(() => {
    detectionLogCount++;
    
    const canvas = document.getElementById('canvas');
    const video = document.getElementById('video');
    
    if (canvas && video && detectionLogCount % 2 === 0) {
        console.log(`\n[Detection Check #${detectionLogCount}]`);
        console.log('Canvas active:', canvas.classList.contains('active'));
        console.log('Video playing:', !video.paused);
        console.log('Time:', new Date().toLocaleTimeString());
    }
    
    if (detectionLogCount > 30) {
        clearInterval(detectionInterval); // Stop after 60 seconds
        console.log('\n=== Debug logging stopped ===');
    }
}, 1000);
</script>
