<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/edit_user.php
include 'configCon.php';

// รับค่าจาก URL
$user_id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$user_id) {
    echo "Invalid request! The 'id' parameter is missing.";
    exit;
}

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

// เมื่อผู้ใช้กดปุ่มบันทึก
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $class = $_POST['class'];
    $std_qr_code = $_POST['std_qr_code'];
    $Ac_point = $_POST['Ac_point'];

    // อัปเดตข้อมูลผู้ใช้ในฐานข้อมูล
    $sql_update = "UPDATE users SET username = ?, fullname = ?, class = ?, std_qr_code = ?, Ac_point = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssii", $username, $fullname, $class, $std_qr_code, $Ac_point, $user_id);
    if ($stmt_update->execute()) {
        echo "บันทึกข้อมูลสำเร็จ!";
        header("Location: user_profile.php?id=" . $user_id); // กลับไปหน้าโปรไฟล์ผู้ใช้
        exit;
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
}

$stmt->close();
$conn->close();
?>
<?php include('layout.php'); ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลผู้ใช้</title>
</head>
<body>
    <div class="container">
        <h1>แก้ไขข้อมูลผู้ใช้</h1>
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br>
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required><br>
            <label for="class">Class:</label>
            <input type="text" id="class" name="class" value="<?= htmlspecialchars($user['class']) ?>" required><br>
            <label for="std_qr_code">Student QR Code URL:</label>
            <input type="text" id="std_qr_code" name="std_qr_code" value="<?= htmlspecialchars($user['std_qr_code']) ?>"><br>
            <label for="Ac_point">Activity Points:</label>
            <input type="number" id="Ac_point" name="Ac_point" value="<?= htmlspecialchars($user['Ac_point']) ?>" required><br>
            <button type="submit">บันทึก</button>
        </form>
    </div>
</body>
</html>
<?php include('footer.php'); ?>