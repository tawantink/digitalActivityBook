<?php
// อัปเดตรหัสผ่านของ admin
require 'config.php'; // เชื่อมต่อฐานข้อมูล

$username = 'admin'; // ชื่อผู้ใช้ที่ต้องการอัปเดต
$newPassword = 'admin123456'; // รหัสผ่านใหม่

// สร้างแฮชของรหัสผ่าน
$passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

// อัปเดตรหัสผ่านในฐานข้อมูล
$stmt = $pdo->prepare("UPDATE admin SET admin_password = ? WHERE admin_username = ?");
$stmt->execute([$passwordHash, $username]);

echo "Password updated successfully!";
?>
