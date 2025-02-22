<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/admin_dashboard.php
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

// ดึงเทอมและปีการศึกษาจากตาราง settings และเรียงลำดับจากมากไปน้อยด้วย year
$sql = "SELECT DISTINCT term, year FROM settings ORDER BY year DESC, term DESC";
$result = $conn->query($sql);

$terms = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $terms[] = $row['term'] . '/' . $row['year'];
    }
}

// กำหนดเทอมและปีการศึกษาปัจจุบัน
$current_term = isset($_GET['term']) ? $_GET['term'] : (count($terms) > 0 ? $terms[0] : '');

// ตรวจสอบรูปแบบของ $current_term ก่อนทำการแยกสตริง
if (strpos($current_term, '/') !== false) {
    list($current_term_value, $current_year) = explode('/', $current_term);
} else {
    $current_term_value = '';
    $current_year = '';
}

$sql = "SELECT activity_points FROM settings WHERE term = ? AND year = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $current_term_value, $current_year); // ใช้ประเภท "ss" สำหรับ term และ year
$stmt->execute();
$result = $stmt->get_result();
$activity_points = $result->num_rows > 0 ? $result->fetch_assoc()['activity_points'] : 0;

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
        <!-- แสดง dropdown สำหรับเลือกเทอมและปีการศึกษา -->
        <form method="get" action="admin_dashboard.php" class="mb-4 d-flex align-items-center">
            <label style="font-size: 30px;">ภาคเรียน/ปีการศึกษา :&nbsp;&nbsp;</label>
            <div style="font-size: 30px;" class="form-group mr-3">
                <select class="form-control" id="term" name="term" onchange="this.form.submit()" style="width: auto;">
                    <?php foreach ($terms as $term): ?>
                        <option value="<?= htmlspecialchars($term) ?>" <?= $term == $current_term ? 'selected' : '' ?>><?= htmlspecialchars($term) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- แสดงแต้มกิจกรรม -->
            <label style="font-size: 30px;">&nbsp;&nbsp;แต้มกิจกรรมผ่านเกณฑ์ประจำภาคเรียน :&nbsp;&nbsp;</label>
            <div class="form-group mr-3">
                <span style="font-size: 30px;" id="current-activity-points"><?= htmlspecialchars($activity_points) ?></span>
                &nbsp;&nbsp;
                <button type="button" class="btn btn-warning btn-sm mb-2" onclick="toggleEditForm()">แก้ไขแต้มกิจกรรม</button>
                <!-- ปุ่มเพิ่มภาคเรียน/ปีการศึกษา -->
                <a href="add_term_form.php" class="btn btn-success btn-sm mb-2">เพิ่มภาคเรียน/ปีการศึกษา</a>
            </div>
        </form>
        <!-- ฟอร์มแก้ไขแต้มกิจกรรม (ซ่อนโดยค่าเริ่มต้น) -->
        <form id="edit-activity-points-form" method="post" action="save_activity_points.php" style="display: none; margin-top: 10px;">แก้ไขแต้มกิจกรรม :
            <input type="hidden" name="term" value="<?= htmlspecialchars($current_term_value) ?>">
            <input type="hidden" name="year" value="<?= htmlspecialchars($current_year) ?>">
            <input type="number" id="new-activity-points" name="activity_points" class="form-control" value="<?= htmlspecialchars($activity_points) ?>" style="width: 100px; display: inline;">
            <button type="submit" class="btn btn-primary btn-sm">บันทึก</button>
        </form>
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

        function toggleEditForm() {
            const form = document.getElementById('edit-activity-points-form');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        function updateActivityPoints() {
            const newPoints = document.getElementById('new-activity-points').value;
            const term = document.getElementById('current-term').value;

            if (newPoints === "" || isNaN(newPoints)) {
                alert("กรุณากรอกแต้มกิจกรรมที่ถูกต้อง");
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "save_activity_points.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('current-activity-points').innerText = newPoints;
                    toggleEditForm();
                }
            };
            xhr.send("term=" + term + "&year=" + document.getElementById('current-year').value + "&activity_points=" + newPoints);
        }

        function toggleAddTermForm() {
            const form = document.getElementById('add-term-form');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
<?php include('footer.php'); ?>
</html>