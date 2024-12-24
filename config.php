<?php
$host = 'xefi550t7t6tjn36.cbetxkdyhwsb.us-east-1.rds.amazonaws.com'; // ชื่อโฮสต์
$db   = 'llqkum01lyw1bhs6'; // ชื่อฐานข้อมูล
$user = 'vtbutzp63stromir'; // ชื่อผู้ใช้ MySQL
$pass = 'eacwlellzq8coyp1'; // รหัสผ่าน MySQL
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
