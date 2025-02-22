<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/add_new_term.php
include 'configCon.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_term = filter_input(INPUT_POST, 'new_term', FILTER_SANITIZE_STRING);
    $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
    $activity_points = filter_input(INPUT_POST, 'activity_points', FILTER_VALIDATE_INT);

    if ($new_term && $year !== false && $activity_points !== false) {
        // ตรวจสอบว่ามีเทอมนี้อยู่แล้วหรือไม่
        $sql = "SELECT * FROM settings WHERE term = ? AND year = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_term, $year);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "ภาคเรียนนี้มีอยู่แล้ว";
        } else {
            // เพิ่มเทอมใหม่และแต้มกิจกรรม
            $sql = "INSERT INTO settings (term, year, activity_points) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $new_term, $year, $activity_points);

            if ($stmt->execute()) {
                echo "เพิ่มภาคเรียนและแต้มกิจกรรมสำเร็จ!";
                // เปลี่ยนเส้นทางกลับไปยังหน้า admin_dashboard.php
                header("Location: admin_dashboard.php");
                exit();
            } else {
                echo "เกิดข้อผิดพลาดในการเพิ่มภาคเรียนและแต้มกิจกรรม: " . $stmt->error;
            }
        }

        $stmt->close();
    } else {
        echo "ข้อมูลไม่ถูกต้อง!";
    }
}

$conn->close();
?>