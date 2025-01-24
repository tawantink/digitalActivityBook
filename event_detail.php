<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/event_detail.php
include 'configCon.php';

// รับค่าจาก URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id) {
    echo "Invalid request! The 'id' parameter is missing.";
    exit;
}

// ดึงข้อมูลกิจกรรมจากฐานข้อมูล
$sql = "SELECT * FROM events WHERE id = ?";
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
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' https://unpkg.com; connect-src 'self'; img-src 'self'; style-src 'self' 'unsafe-inline'; media-src 'self'">
    <title><?= $title ?></title>
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <style>
        #reader {
            width: 100%;
            max-width: 600px;
            margin: auto;
            display: none; /* ซ่อนกล้องไว้ก่อน */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>รายละเอียดกิจกรรม : <?= htmlspecialchars($event['event_name']) ?></h1>
        <p><strong>วันที่จัดกิจกรรม:</strong> <?= (new DateTime($event['event_date']))->format('d/m/Y') ?></p>
        <p><strong>รายละเอียด:</strong> <?= htmlspecialchars($event['event_descrip']) ?></p>
        <p><strong>แต้มกิจกรรม:</strong> <?= htmlspecialchars($event['event_point']) ?></p>
        <a href="edit_event.php?id=<?= $event['id'] ?>&redirect=event_detail.php?id=<?= $event['id'] ?>" class="btn btn-primary">แก้ไข</a>
        <a href="delete_event.php?id=<?= $event['id'] ?>" class="btn btn-danger" onclick="return confirm('คุณต้องการลบกิจกรรมนี้หรือไม่?')">ลบ</a>
        <a href="show_events.php" class="btn btn-secondary">Close</a>

        <!-- ปุ่มสำหรับเปิดกล้อง -->
        <h2>สแกน QR Code</h2>
        <button id="start-scan" class="btn btn-success">เปิดกล้องเพื่อสแกน QR Code</button>
        <div id="reader"></div>
        <p id="result"></p>
    </div>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Handle the scanned code as you like, for example:
            document.getElementById('result').innerText = `Scanned result: ${decodedText}`;
        }

        function onScanFailure(error) {
            // Handle scan failure, usually better to ignore and keep scanning.
            console.warn(`QR error = ${error}`);
        }

        document.getElementById('start-scan').addEventListener('click', function() {
            document.getElementById('reader').style.display = 'block';
            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", { fps: 10, qrbox: 250 });
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });
    </script>
</body>
<?php include('footer.php'); ?>
</html>