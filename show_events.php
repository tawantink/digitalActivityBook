<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/show_events.php
include 'configCon.php';

// รับค่าการค้นหาจากฟอร์ม
$search_date = isset($_GET['search_date']) ? $_GET['search_date'] : '';
$search_name = isset($_GET['search_name']) ? $_GET['search_name'] : '';

// คำสั่ง SQL เพื่อดึงข้อมูลจากตาราง events และเรียงลำดับตามวันที่จากน้อยไปมาก
$sql = "SELECT event_id, event_date, event_name, event_descrip, event_point FROM events WHERE 1=1";

if ($search_date) {
    $sql .= " AND event_date = '$search_date'";
}
if ($search_name) {
    $sql .= " AND event_name LIKE '%$search_name%'";
}

$sql .= " ORDER BY event_date ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<?php 
$title = "Activity Program";
include('layout.php'); 
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Program</title>
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
            window.location.href = 'event_detail.php?event_id=' + eventId;
        }
    </script>
</head>
<body>
    <div class="container">
        <br><h1>ข้อมูลกิจกรรมทั้งหมด</h1><hr><br>

        <!-- ฟอร์มค้นหา -->
        <form method="GET" action="show_events.php" class="form-inline d-flex align-items-center mb-4">
            <label for="search_date" class="mr-2">วันที่ :</label>&nbsp;&nbsp;
            <input type="date" id="search_date" name="search_date" value="<?= htmlspecialchars($search_date) ?>" class="form-control mr-2 w-25 me-4">
            <label for="search_name" class="mr-2">ชื่อกิจกรรม :</label>&nbsp;&nbsp;
            <input type="text" id="search_name" name="search_name" value="<?= htmlspecialchars($search_name) ?>" class="form-control mr-2 w-25 me-4">
            <button type="submit" class="btn btn-primary mr-2 me-4">ค้นหา</button>
            <a href="show_events.php" class="btn btn-secondary">เคลียร์</a>
        </form>

        <!-- แสดงผลการค้นหา -->
        <?php if ($search_date || $search_name): ?>
            <p>ผลการค้นหาสำหรับ: 
                <?php if ($search_date): ?>วันที่: <?php echo htmlspecialchars($search_date); ?><?php endif; ?>
                <?php if ($search_name): ?>, ชื่อกิจกรรม: <?php echo htmlspecialchars($search_name); ?><?php endif; ?>
            </p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>วันที่</th>
                    <th>ชื่อกิจกรรม</th>
                    <th>รายละเอียด</th>
                    <th>คะแนน</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $index = 1;

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $dateObj = DateTime::createFromFormat('Y-m-d', $row['event_date']);
                        $formattedDate = $dateObj->format('d/m/Y');

                        echo "<tr onclick='goToDetail(" . $row["event_id"] . ")'>"; // ใช้ onclick เรียกฟังก์ชัน
                        echo "<td>" . $index . "</td>";
                        echo "<td>" . $formattedDate . "</td>";
                        echo "<td>" . htmlspecialchars($row["event_name"]) . "</td>";
                        echo "<td class='text-start'>" . htmlspecialchars($row["event_descrip"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["event_point"]) . "</td>";
                        echo "<td>";
                        echo "<a href='delete_event.php?event_id=" . $row["event_id"] . "' class='action-btn delete-btn' onclick='return confirm(\"คุณต้องการลบกิจกรรมนี้หรือไม่?\")'>ลบ</a>";
                        echo "</td>";
                        echo "</tr>";

                        $index++;
                    }
                } else {
                    echo "<tr><td colspan='6'>ไม่มีข้อมูล</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
<?php include('footer.php'); ?>
</html>

<?php
$conn->close();
?>