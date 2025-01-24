<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/events.php
include 'configCon.php';

// รับค่าจาก URL
$date = isset($_GET['date']) ? $_GET['date'] : null;
if (!$date) {
    echo "Invalid request! The 'date' parameter is missing.";
    exit;
}

// เปลี่ยนรูปแบบวันที่จาก yyyy-mm-dd เป็น dd/mm/yyyy
$dateObj = DateTime::createFromFormat('Y-m-d', $date);
$formattedDate = $dateObj->format('d/m/Y');

// ตั้งค่าตัวแปร title
$title = "กิจกรรมประจำวันที่ $formattedDate";

include('layout.php');
?>

<div class="container">
    <?php
    // ดึงข้อมูลกิจกรรมจากฐานข้อมูล
    $sql = "SELECT id, event_name, event_descrip, event_point FROM events WHERE event_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    // แสดงกิจกรรมในรูปแบบตาราง
    echo "<h2>กิจกรรมประจำวันที่ $formattedDate</h2>";
    // ปุ่มเพิ่มกิจกรรม
    echo "<br><a href='add_event.php?date=" . htmlspecialchars($date) . "'><button class='cta'><span><svg height='24' width='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path d='M0 0h24v24H0z' fill='none'></path><path d='M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z' fill='currentColor'></path></svg>Create</span></button></a> <br><br>";

    if ($result->num_rows > 0) {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-bordered'>";
        echo "<thead class='thead-light'>";
        echo "<tr>
                <th>ลำดับ</th>
                <th>กิจกรรม</th>
                <th>รายละเอียด</th>
                <th>แต้มกิจกรรม</th>
                <th>จัดการ</th>
            </tr>";
        echo "</thead>";
        echo "<tbody>";

        // เริ่มต้นลำดับ
        $index = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr onclick='goToDetail(" . $row["id"] . ")'>";
            echo "<td>" . $index++ . "</td>";
            echo "<td>" . htmlspecialchars($row['event_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['event_descrip']) . "</td>";
            echo "<td>" . htmlspecialchars($row['event_point']) . "</td>";
            echo "<td>";
            echo "<a href='delete_event.php?id=" . $row["id"] . "' class='action-btn delete-btn' onclick='return confirm(\"คุณต้องการลบกิจกรรมนี้หรือไม่?\")'>ลบ</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else {
        echo "No events found for this date.";
    }

    $stmt->close();
    $conn->close();
    ?>
</div>

<!-- รวมไฟล์ Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- รวมไฟล์ ButtonPlus CSS -->
<link rel="stylesheet" href="css/buttonplus.css">

<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    table, th, td {
        border: 1px solid black;
    }
    th, td {
        padding: 8px;
        text-align: center;
    }
    th {
        background-color: #f2f2f2;
    }
    tr {
        cursor: pointer; /* ให้ทั้งแถวสามารถกดได้ */
    }
    tr:hover {
        background-color: #B9B9B9FF; /* เปลี่ยนสีเมื่อ hover */
    }
    .action-btn {
        padding: 5px 10px;
        margin: 0 2px;
        border: none;
        cursor: pointer;
        border-radius: 3px;
    }
    .delete-btn {
        background-color: #f44336;
        color: white;
    }
</style>

<script>
    // ฟังก์ชันสำหรับนำผู้ใช้ไปยังหน้ารายละเอียดเมื่อคลิกที่แถว
    function goToDetail(eventId) {
        window.location.href = 'event_detail.php?id=' + eventId;
    }
</script>

<?php include('footer.php'); ?>