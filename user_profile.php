<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/user_profile.php
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

$stmt->close();
$conn->close();
?>
<?php include('layout.php'); ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile of <?= htmlspecialchars($user['username']) ?></title>
</head>
<body>
    <div class="container">
        <h1>Profile of <?= htmlspecialchars($user['username']) ?></h1>
        <?php if ($user['avatar']): ?>
            <p><img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar" width="100"></p>
        <?php endif; ?>
        <p><strong>รหัสนักศึกษา :</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>ชื่อ-นามสกุล :</strong> <?= htmlspecialchars($user['fullname']) ?></p>
        <p><strong>ชั้นปี :</strong> <?= htmlspecialchars($user['class']) ?></p>
        <p><strong>จำนวนแต้มกิจกรรม :</strong> <?= htmlspecialchars($user['points']) ?></p>
        <?php if (!empty($user['std_qr_code'])): ?>
            <p><strong>QR Code:</strong><br>
            <img src="/pj/digitalActivityBook/<?= htmlspecialchars($user['std_qr_code']) ?>" alt="QR Code" width="100">
            </p>
        <?php endif; ?>
        <a href="edit_user.php?id=<?= $user_id ?>" class="btn btn-primary">แก้ไขข้อมูล</a>
    </div>
</body>
</html>
<?php include('footer.php'); ?>