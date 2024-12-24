<?php
session_start(); // เริ่มต้นเซสชัน

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // หากไม่ได้ล็อกอินให้กลับไปที่หน้า login
    exit;
}

require 'config.php'; // เชื่อมต่อกับฐานข้อมูล

$user_id = $_SESSION['user_id']; // ดึงข้อมูล user_id จากเซสชัน

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit;
}

// ตรวจสอบพาธรูปภาพ
$profileImagePath = !empty($user['std_qr_code']) ? $user['std_qr_code'] : 'qrcode/default.png';
$profileImageURL = !empty($user['avatar']) ? $user['avatar'] : 'https://example.com/images/default.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = "Profile of " . htmlspecialchars($user['username']);
include('layout.php');
?>
<body>
    <?php //include('header.php');?>
    <div class="row-1">
        <div class="col">
            <h1>Welcome, <?php echo htmlspecialchars($user['fullname']); ?>!</h1>
            <p>Your ID: <?php echo htmlspecialchars($user['id']); ?></p>
            <p>Your Username: <?php echo htmlspecialchars($user['username']); ?></p>
        </div>
    </div>
    <div class="row-11">
        <div class="col">
            <!--/* From Uiverse.io by vamsidevendrakumar */--> 
            <br><br>
            <div class="card m-3">
                <div class="card-inner">
                    <div class="card-front">
                        <img src="<?php echo htmlspecialchars($profileImageURL); ?>" alt="Profile Image" style="max-width:200px;border-radius: 10%;">
                    </div>
                    <div class="card-back">
                        <div class="cards mx-5">
                            <img src="<?php echo htmlspecialchars($profileImagePath); ?>" alt="Profile qrcode" style="width: 250px;height: 250px;border-radius: 10%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include('footer.php');?>
</html>
