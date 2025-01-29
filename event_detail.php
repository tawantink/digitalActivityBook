<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/event_detail.php
include 'configCon.php';

// รับค่าจาก URL
$id = isset($_GET['event_id']) ? $_GET['event_id'] : null;
if (!$id) {
    echo "Invalid request! The 'id' parameter is missing.";
    exit;
}

// ดึงข้อมูลกิจกรรมจากฐานข้อมูล
$sql = "SELECT * FROM events WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $event = $result->fetch_assoc();
} else {
    echo "Event not found.";
    exit;
}

$stmt->close();
$conn->close();

$title = "รายละเอียดกิจกรรม: " . htmlspecialchars($event['event_name']);
include('layout.php');
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        video, canvas {
            display: block;
            margin: 10px auto;
            max-width: 100%;
            height: auto;
        }
        .container {
            padding: 15px;
        }
        .btn {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>รายละเอียดกิจกรรม : <?= htmlspecialchars($event['event_name']) ?></h1>
        <p><strong>วันที่จัดกิจกรรม:</strong> <?= (new DateTime($event['event_date']))->format('d/m/Y') ?></p>
        <p><strong>รายละเอียด:</strong> <?= htmlspecialchars($event['event_descrip']) ?></p>
        <p><strong>แต้มกิจกรรม:</strong> <?= htmlspecialchars($event['event_point']) ?></p>
        <a href="edit_event.php?event_id=<?= $event['event_id'] ?>&redirect=event_detail.php?event_id=<?= $event['event_id'] ?>" class="btn btn-primary">แก้ไข</a>
        <a href="delete_event.php?event_id=<?= $event['event_id'] ?>" class="btn btn-danger" onclick="return confirm('คุณต้องการลบกิจกรรมนี้หรือไม่?')">ลบ</a>
        <a href="show_events.php" class="btn btn-secondary">Close</a>

        <!-- ปุ่มสำหรับเปิดกล้อง -->
        <h2>สแกน QR Code</h2>
        <button id="start-scan" class="btn btn-success">เปิดกล้องเพื่อสแกน QR Code</button>
        <video id="webcam" width="640" height="480" autoplay></video>
        <canvas id="qrCanvas" width="640" height="480" style="display: none;"></canvas>
        <p id="result">QR Code Data: <strong>None</strong></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
    <script>
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('qrCanvas');
        const context = canvas.getContext('2d');
        const result = document.getElementById('result');
        let scanning = false;

        function onScanSuccess(decodedText) {
            result.innerHTML = `QR Code Data: <strong>${decodedText}</strong>`;
            alert(`QR Code Scanned: ${decodedText}`);

            // ส่งข้อมูลไปยังเซิร์ฟเวอร์เพื่อบันทึกในฐานข้อมูล
            fetch('save_scan.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    event_id: <?= $event['event_id'] ?>,
                    username_id: decodedText
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('บันทึกข้อมูลสำเร็จ');
                } else {
                    alert('บันทึกข้อมูลไม่สำเร็จ: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            });
        }

        function scanQRCode() {
            if (!scanning) return;

            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                const qrCode = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "dontInvert",
                });

                if (qrCode) {
                    scanning = false;
                    onScanSuccess(qrCode.data);

                    setTimeout(() => {
                        scanning = true;
                        scanQRCode();
                    }, 3000);

                    return;
                }
            }
            requestAnimationFrame(scanQRCode);
        }

        document.getElementById('start-scan').addEventListener('click', function() {
            document.getElementById('webcam').style.display = 'block';
            scanning = true;
            navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
                .then(function (stream) {
                    video.srcObject = stream;
                    video.setAttribute("playsinline", true);
                    video.play();
                    scanQRCode();
                })
                .catch(function (error) {
                    console.error("Error accessing webcam: ", error);
                    alert("Could not access the webcam: " + error.message);
                });
        });
    </script>
</body>
<?php include('footer.php'); ?>
</html>