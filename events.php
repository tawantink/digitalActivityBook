<?php
// รวมไฟล์ config.php เพื่อเชื่อมต่อฐานข้อมูล
include 'configCon.php';

// รับค่าจาก URL
$date = isset($_GET['date']) ? $_GET['date'] : null;

if (!$date) {
    echo "No date selected!";
    exit;
}

// เปลี่ยนรูปแบบวันที่จาก yyyy-mm-dd เป็น dd/mm/yyyy
$dateObj = DateTime::createFromFormat('Y-m-d', $date);
$formattedDate = $dateObj->format('d/m/Y');

// ตั้งค่าตัวแปร title
$title = "กิจกรรมประจำวันที่ $formattedDate";

?>
<?php include('layout.php'); ?>

<div class="container">
    <?php
    // ดึงข้อมูลกิจกรรมจากฐานข้อมูล
    $sql = "SELECT event_name, event_descrip, event_point FROM events WHERE event_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    // แสดงกิจกรรมในรูปแบบตาราง
    echo "<h2>กิจกรรมประจำวันที่ $formattedDate</h2>";
    // ปุ่มเพิ่มกิจกรรม
    echo "<br><a href='add_event.php?date=$date'><button>Add New Event</button></a> <br><br>";

    if ($result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr>
                <th>ลำดับ</th>
                <th>กิจกรรม</th>
                <th>รายละเอียด</th>
                <th>แต้มกิจกรรม</th>
            </tr>";

        // เริ่มต้นลำดับ
        $index = 1;

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $index . "</td>
                    <td>" . htmlspecialchars($row['event_name']) . "</td>
                    <td>" . htmlspecialchars($row['event_descrip']) . "</td>
                    <td>" . htmlspecialchars($row['event_point']) . "</td>
                </tr>";
            $index++; // เพิ่มลำดับ
        }
        echo "</table>";
    } else {
        echo "<p>No events found for this date.</p>";
    }

    // ปุ่มกลับไปยังปฏิทิน
    echo "<br><a href='admin_dashboard.php'><button>Back to Calendar</button></a>";

    // ปิดการเชื่อมต่อฐานข้อมูล
    $conn->close();
    ?>
</div>

<?php include('footer.php'); ?>
