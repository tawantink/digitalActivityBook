<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/edit_event.php
include 'configCon.php';

// รับค่าจาก URL
$id = isset($_GET['event_id']) ? $_GET['event_id'] : null;
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'events.php'; // รับพารามิเตอร์ redirect เพื่อระบุหน้าที่ต้องการกลับไป
if (!$id) {
    die("Invalid request! The 'event_id' parameter is missing.");
}

// ดึงข้อมูลกิจกรรมจากฐานข้อมูล
$sql = "SELECT * FROM events WHERE event_id = ?";
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
    $sql_update = "UPDATE events SET event_date = ?, event_name = ?, event_descrip = ?, event_point = ? WHERE event_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssii", $event_date, $event_name, $event_descrip, $event_point, $id);
    if ($stmt_update->execute()) {
        header("Location: event_detail.php?event_id=$id");
        exit();
    } else {
        echo "<div class='alert alert-danger'>เกิดข้อผิดพลาดในการบันทึกข้อมูล</div>";
    }
    $stmt_update->close();
}

$stmt->close();
$conn->close();

$title = "แก้ไขกิจกรรม: " . htmlspecialchars($event['event_name']);
include('layout.php');
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>แก้ไขกิจกรรม : <?= htmlspecialchars($event['event_name']) ?></h1>
        <hr>
        <form method="post">
            <div class="form-group">
                <label for="event_date">วันที่จัดกิจกรรม</label>
                <input type="date" class="form-control" id="event_date" name="event_date" value="<?= htmlspecialchars($event['event_date']) ?>" required>
            </div>
            <div class="form-group">
                <label for="event_name">ชื่อกิจกรรม</label>
                <input type="text" class="form-control" id="event_name" name="event_name" value="<?= htmlspecialchars($event['event_name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="event_descrip">รายละเอียด</label>
                <textarea class="form-control" id="event_descrip" name="event_descrip" rows="4" required><?= htmlspecialchars($event['event_descrip']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="event_point">แต้มกิจกรรม</label>
                <input type="number" class="form-control" id="event_point" name="event_point" value="<?= htmlspecialchars($event['event_point']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">บันทึก</button>
            <a href="<?= htmlspecialchars($redirect) ?>" class="btn btn-secondary">ยกเลิก</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<?php include('footer.php'); ?>
</html>