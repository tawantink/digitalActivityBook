
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
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = "Admin Dashboard";
include('layout1.php');
?>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
            <p>You are logged in as Admin ID: <?php echo $_SESSION['admin_id']; ?></p>
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
                    $daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
                    $numberOfDays = date("t", $firstDayOfMonth);
                    $dateComponents = getdate($firstDayOfMonth);
                    $monthName = $dateComponents["month"];
                    $dayOfWeek = $dateComponents["wday"];
                
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
                
                        $currentDate = sprintf("%04d-%02d-%02d", $year, $month, $currentDay);
                
                        // ตรวจสอบว่าวันนี้มีกิจกรรมหรือไม่
                        if (in_array($currentDate, $event_dates)) {
                            echo "<td style='background-color: #FFD700;'><a href='events.php?date=$currentDate'>$currentDay*</a></td>";
                        } else {
                            echo "<td><a href='events.php?date=$currentDate'>$currentDay</a></td>";
                        }
                
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
                echo "<div style='text-align: center; margin-bottom: 20px;'>";
                echo "<a href='?month=$prevMonth&year=$prevYear'><button>Previous Month</button></a>";
                echo "<a href='?month=$nextMonth&year=$nextYear'><button>Next Month</button></a>";
                echo "</div>";

                
                // ตั้งค่าปีและเดือนปัจจุบัน
                $month = isset($_GET['month']) ? $_GET['month'] : date("m");
                $year = isset($_GET['year']) ? $_GET['year'] : date("Y");

                // สร้างปฏิทิน
                generateCalendar($month, $year, $event_dates);
                ?>

                <!-- JavaScript สำหรับแสดงฟอร์มเพิ่มงาน -->
                <script>
                function addEvent(date) {
                    const event = prompt(`Add an event for ${date}:`);
                    if (event) {
                        alert(`Event "${event}" added for ${date}`);
                        // คุณสามารถส่งข้อมูลไปยังเซิร์ฟเวอร์ด้วย AJAX หรือ Form ได้ที่นี่
                        console.log(`Date: ${date}, Event: ${event}`);
                    }
                }
                </script>
    </div>
</body>
<?php include('footer.php');?>
</html>

