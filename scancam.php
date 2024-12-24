<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
</head>
<body>
    <h1>QR Code Scanner</h1>

    <!-- แสดงวีดีโอจากกล้อง -->
    <div id="reader" style="width: 600px; height: 400px;"></div>

    <!-- แสดงผล QR Code ที่สแกน -->
    <p>Result: <span id="result"></span></p>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // แสดงผล QR Code ที่สแกนได้
            document.getElementById("result").innerText = decodedText;
        }

        function onScanError(errorMessage) {
            // ถ้ามีข้อผิดพลาดจะส่งมาที่นี่
            console.warn(errorMessage);
        }

        // เริ่มการสแกน QR Code โดยใช้กล้อง webcam
        const html5QrCode = new Html5Qrcode("reader");
        
        html5QrCode.start(
            { facingMode: "user" },  // ใช้กล้อง webcam
            {
                fps: 10,  // จำนวนเฟรมต่อวินาที
                qrbox: 250  // ขนาดกล่องที่ใช้สแกน
            },
            onScanSuccess,  // ฟังก์ชันที่ใช้เมื่อสแกนสำเร็จ
            onScanError    // ฟังก์ชันที่ใช้เมื่อเกิดข้อผิดพลาด
        ).catch(err => {
            console.error("Error starting QR code scanner: ", err);
        });
    </script>
</body>
<?php include('footer.php');?>
<?php include('layout.php');?>
</html>
