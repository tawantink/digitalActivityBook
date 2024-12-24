<?php
// เชื่อมต่อฐานข้อมูล
include 'configCon.php';

// ตรวจสอบว่ามีการส่งข้อมูลมาไหม
if (isset($_POST['data'])) {
    $data = $_POST['data'];

    // เตรียมคำสั่ง SQL สำหรับการบันทึกข้อมูล
    $stmt = $conn->prepare("INSERT INTO qrcode_data (data) VALUES (?)");
    $stmt->bind_param("s", $data); // "s" หมายถึง string
    $stmt->execute();

    // ตรวจสอบว่าบันทึกสำเร็จหรือไม่
    if ($stmt->affected_rows > 0) {
        echo "Data saved successfully: " . $data;
    } else {
        echo "Failed to save data.";
    }

    $stmt->close();
} else {
    echo "No data received.";
}

$conn->close();
?>
