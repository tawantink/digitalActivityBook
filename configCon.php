<?php
$servername = "localhost";
$username = "dab_ac"; // ชื่อผู้ใช้ MySQL ของคุณ
$password = "Gwfj[WyZW[6II9pi"; // รหัสผ่าน MySQL ของคุณ
$dbname = "araiwa"; // ชื่อฐานข้อมูลของคุณ

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}
?>
