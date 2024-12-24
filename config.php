<?php
$host = 'localhost'; // ชื่อโฮสต์
$db   = 'araiwa'; // ชื่อฐานข้อมูล
$user = 'dab_ac'; // ชื่อผู้ใช้ MySQL
$pass = 'Gwfj[WyZW[6II9pi'; // รหัสผ่าน MySQL
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
