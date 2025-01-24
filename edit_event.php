<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/edit_event.php
include 'configCon.php';

// รับค่าจาก URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'events.php'; // รับพารามิเตอร์ redirect เพื่อระบุหน้าที่ต้องการกลับไป
if (!$id) {
    die("Invalid request! The 'id' parameter is missing.");
}

// ดึงข้อมูลกิจกรรมจากฐานข้อมูล
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    die("ไม่พบข้อมูลกิจกรรม");
}

// เมื่อผู้ใช้กดปุ่มบันทึก
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_date = $_POST['event_date'];
    $event_name = $_POST['event_name'];
    $event_descrip = $_POST['event_descrip'];
    $event_point = $_POST['event_point'];

    // อัปเดตข้อมูลกิจกรรมในฐานข้อมูล
    $sql_update = "UPDATE events SET event_date = ?, event_name = ?, event_descrip = ?, event_point = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssii", $event_date, $event_name, $event_descrip, $event_point, $id);
    if ($stmt_update->execute()) {
        echo "บันทึกข้อมูลสำเร็จ!";
        header("Location: " . $redirect . "?date=" . $event_date); // กลับไปหน้าที่ระบุในพารามิเตอร์ redirect พร้อมกับวันที่ของกิจกรรม
        exit;
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
}

$conn->close();
?>
<?php include('layout.php'); ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขกิจกรรม</title>
</head>
<body>
    <div class="container">
        <h1>แก้ไขกิจกรรม</h1>
        <form method="POST">
            <label for="event_date">วันที่จัดกิจกรรม:</label>
            <input type="date" id="event_date" name="event_date" value="<?= htmlspecialchars($event['event_date']) ?>" required><br>
            <label for="event_name">ชื่อกิจกรรม:</label>
            <input type="text" id="event_name" name="event_name" value="<?= htmlspecialchars($event['event_name']) ?>" required><br>
            <label for="event_descrip">รายละเอียด:</label>
            <textarea id="event_descrip" name="event_descrip" required><?= htmlspecialchars($event['event_descrip']) ?></textarea><br>
            <label for="event_point">แต้มกิจกรรม:</label>
            <input type="number" id="event_point" name="event_point" value="<?= htmlspecialchars($event['event_point']) ?>" required><br>
            <button type="submit">บันทึก</button>
        </form>
    </div>
</body>
</html>