<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <style>
        video, canvas {
            display: block;
            margin: 10px auto;
        }
    </style>
</head>
<body>
    <h1>QR Code Scanner</h1>
    <video id="webcam" width="640" height="480" autoplay></video>
    <canvas id="qrCanvas" width="640" height="480" style="display: none;"></canvas>
    <p id="result">QR Code Data: <strong>None</strong></p>

    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
    <script>
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('qrCanvas');
        const context = canvas.getContext('2d');
        const result = document.getElementById('result');
        let scanning = true;

        // Access the webcam
        navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
            .then(function (stream) {
                video.srcObject = stream;
                video.setAttribute("playsinline", true); // Required for iOS
                video.play();
                scanQRCode();
            })
            .catch(function (error) {
                console.error("Error accessing webcam: ", error);
                alert("Could not access the webcam: " + error.message);
            });
        // Scan QR
        function scanQRCode() {
            if (!scanning) return; // Stop scanning if a QR code is detected

            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                // Draw the video frame to the canvas
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Extract image data from the canvas
                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);

                // Decode the QR code
                const qrCode = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "dontInvert",
                });

                if (qrCode) {
                    scanning = false; // Stop further scanning
                    result.innerHTML = `QR Code Data: <strong>${qrCode.data}</strong>`;
                    alert(`QR Code Scanned: ${qrCode.data}`); // Show alert with QR code data

                    // Restart scanning after 3 seconds
                    setTimeout(() => {
                        scanning = true;
                        scanQRCode();
                    }, 3000);

                    return;
                }
            }
            // Keep scanning
            requestAnimationFrame(scanQRCode);
        }
    </script>
</body>
</html>
