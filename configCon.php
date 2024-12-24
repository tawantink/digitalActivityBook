<?php
$servername = "xefi550t7t6tjn36.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
$username = "vtbutzp63stromir"; // ชื่อผู้ใช้ MySQL ของคุณ
$password = "eacwlellzq8coyp1"; // รหัสผ่าน MySQL ของคุณ
$dbname = "llqkum01lyw1bhs6"; // ชื่อฐานข้อมูลของคุณ

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}
?>
