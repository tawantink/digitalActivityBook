<?php
// อัปเดตรหัสผ่านทั้งหมดในตาราง users
require 'config.php'; // เชื่อมต่อกับฐานข้อมูล

$newPassword = '1234'; // รหัสผ่านใหม่ที่ต้องการแฮช

// แฮชรหัสผ่านใหม่
$passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

// อัปเดตรหัสผ่านในฐานข้อมูลสำหรับทุกผู้ใช้
$stmt = $pdo->prepare("UPDATE admin SET admin_password = ?");
$stmt->execute([$passwordHash]);

echo "Password updated for all admin successfully.";
?>
