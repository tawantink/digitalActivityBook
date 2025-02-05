<?php
// เชื่อมต่อฐานข้อมูล
include 'configCon.php';

// ดึงวันที่ที่มีกิจกรรม
$sql = "SELECT DISTINCT event_date FROM events";
$result = $conn->query($sql);

if (!$result) {
    die("Error retrieving event dates: " . $conn->error);
}

// เก็บวันที่ที่มีกิจกรรมในอาเรย์
$event_dates = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $event_dates[] = $row['event_date'];
    }
}

// ปิดการเชื่อมต่อ
$conn->close();
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// ตั้งค่า Timezone (เช่น Asia/Bangkok สำหรับประเทศไทย)
date_default_timezone_set('Asia/Bangkok');
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = "Admin Dashboard";
include('layout1.php');
?>
<body>
    <div class="container"><br>
        <h1>Admin Dashboard</h1>
        <hr>
        <!-- การแสดงวันที่ปัจจุบันแบบเรียลไทม์ -->
        <p id="current-date" style="font-size: 18px; font-weight: bold; color: #007BFF;"></p>
        <br>
        <?php
        // รับค่าจาก URL (query string) หรือใช้เดือนและปีปัจจุบันเป็นค่าเริ่มต้น
        $month = isset($_GET['month']) && intval($_GET['month']) >= 1 && intval($_GET['month']) <= 12 ? intval($_GET['month']) : date("m");
        $year = isset($_GET['year']) && is_numeric($_GET['year']) ? intval($_GET['year']) : date("Y");

        // คำนวณเดือนก่อนหน้าและถัดไป
        $prevMonth = $month - 1;
        $prevYear = $year;
        $nextMonth = $month + 1;
        $nextYear = $year;

        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear--;
        }

        if ($nextMonth > 12) {
            $nextMonth = 1;
            $nextYear++;
        }

        // ฟังก์ชันสร้างปฏิทิน
        function generateCalendar($month, $year, $event_dates) {
            $daysOfWeek = ["อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัสบดี", "ศุกร์", "เสาร์"];
            $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
            $numberOfDays = date("t", $firstDayOfMonth);
            $dateComponents = getdate($firstDayOfMonth);
            $monthName = $dateComponents["month"];
            $dayOfWeek = $dateComponents["wday"];
            $currentDate = date("Y-m-d");

            // สร้างหัวปฏิทิน
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<caption>$monthName $year</caption>";
            echo "<tr>";
            foreach ($daysOfWeek as $day) {
                echo "<th>$day</th>";
            }
            echo "</tr><tr>";

            // แสดงช่องว่างก่อนวันแรกของเดือน
            if ($dayOfWeek > 0) {
                echo str_repeat("<td></td>", $dayOfWeek);
            }

            // สร้างวันที่
            $currentDay = 1;
            while ($currentDay <= $numberOfDays) {
                if ($dayOfWeek == 7) {
                    $dayOfWeek = 0;
                    echo "</tr><tr>";
                }

                $formattedDate = sprintf("%04d-%02d-%02d", $year, $month, $currentDay);

                // ตรวจสอบว่าวันนี้มีกิจกรรมหรือไม่
                $cellStyle = "";
                if ($formattedDate == $currentDate) {
                    $cellStyle = "background-color: #5735FFFF; color: #FFF;"; // ไฮไลท์วันที่ปัจจุบัน
                } elseif (in_array($formattedDate, $event_dates)) {
                    $cellStyle = "background-color: #FFD700;";
                }

                echo "<td style='$cellStyle'><a href='events.php?date=$formattedDate' style='color: inherit;'>$currentDay</a></td>";

                $currentDay++;
                $dayOfWeek++;
            }

            // เติมช่องว่างในแถวสุดท้าย
            if ($dayOfWeek != 7) {
                $remainingDays = 7 - $dayOfWeek;
                echo str_repeat("<td></td>", $remainingDays);
            }

            echo "</tr>";
            echo "</table>";
        }

        // ปุ่มเปลี่ยนเดือน
        echo "<div style='text-align: center;'>";
        echo "<a href='?month=$prevMonth&year=$prevYear'><button class='button1'>เดือนก่อนหน้า</button></a>";
        echo "<a href='?month=$nextMonth&year=$nextYear'><button class='button2'>เดือนถัดไป</button></a>";
        echo "</div>";

        // สร้างปฏิทิน
        generateCalendar($month, $year, $event_dates);
        ?>
    </div>

    <!-- JavaScript สำหรับการแสดงวันที่ปัจจุบัน -->
    <script>
        function updateCurrentDate() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById("current-date").innerText = "วันนี้ : " + now.toLocaleDateString('th-Thai', options);
        }
        updateCurrentDate();
        setInterval(updateCurrentDate, 1000); // อัปเดตทุกวินาที
    </script>
</body>
<?php include('footer.php'); ?>
</html>
