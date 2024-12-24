<?php
// รวมไฟล์ configCon.php เพื่อเชื่อมต่อฐานข้อมูล
include 'configCon.php';

// คำสั่ง SQL เพื่อดึงข้อมูลจากตาราง events
$sql = "SELECT id, event_date, event_name, event_descrip, event_point FROM events";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<?php include('layout.php'); ?>
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
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ข้อมูลกิจกรรมทั้งหมด</h1>
        <table>
            <thead>
                <tr>
                    <th style="text-align: center;">ลำดับ</th> <!-- เปลี่ยนจาก ID เป็นลำดับ -->
                    <th style="text-align: center;">วันที่</th>
                    <th style="text-align: center;">ชื่อกิจกรรม</th>
                    <th style="text-align: center;">รายละเอียด</th>
                    <th style="text-align: center;">คะแนน</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // เริ่มต้นลำดับ
                $index = 1;

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // แปลงวันที่จาก yyyy-mm-dd เป็น dd/mm/yyyy
                        $dateObj = DateTime::createFromFormat('Y-m-d', $row['event_date']);
                        $formattedDate = $dateObj->format('d/m/Y');

                        echo "<tr>";
                        echo "<td style='text-align: center;'>" . $index . "</td>"; // ใช้ลำดับแทน ID
                        echo "<td style='text-align: center;'>" . $formattedDate . "</td>"; // แสดงวันที่ในรูปแบบ dd/mm/yyyy
                        echo "<td>" . $row["event_name"] . "</td>";
                        echo "<td>" . $row["event_descrip"] . "</td>";
                        echo "<td style='text-align: center;'>" . $row["event_point"] . "</td>";
                        echo "</tr>";
                        $index++; // เพิ่มค่าลำดับในแต่ละรอบ
                    }
                } else {
                    echo "<tr><td colspan='5'>ไม่มีข้อมูล</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
<?php include('footer.php'); ?>
</html>

<?php
// ปิดการเชื่อมต่อ
$conn->close();
?>
