<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/show_events.php
include 'configCon.php';

// คำสั่ง SQL เพื่อดึงข้อมูลจากตาราง events และเรียงลำดับตามวันที่จากน้อยไปมาก
$sql = "SELECT event_id, event_date, event_name, event_descrip, event_point FROM events ORDER BY event_date ASC";
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
        <h1>ข้อมูลกิจกรรมทั้งหมด</h1>
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
                        echo "<td>" . htmlspecialchars($row["event_descrip"]) . "</td>";
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