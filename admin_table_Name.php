<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/admin_table_Name.php
include 'configCon.php';

// รับค่าการค้นหาจากฟอร์ม
$search_username = isset($_GET['search_username']) ? $_GET['search_username'] : '';
$search_class = isset($_GET['search_class']) ? $_GET['search_class'] : '';
$search_department = isset($_GET['search_department']) ? $_GET['search_department'] : '';

// ดึงค่าที่เป็นไปได้สำหรับ class และ department จากฐานข้อมูล
$class_options = $conn->query("SELECT DISTINCT class FROM users ORDER BY class");
$department_options = $conn->query("SELECT DISTINCT department FROM users ORDER BY department");

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$sql = "SELECT user_id, username, fullname, class, department, points FROM users WHERE 1=1";

if ($search_username) {
    $sql .= " AND username LIKE '%$search_username%'";
}
if ($search_class) {
    $sql .= " AND class = '$search_class'";
}
if ($search_department) {
    $sql .= " AND department = '$search_department'";
}

$result = $conn->query($sql);

// ดึงค่า activity_points จากตาราง settings สำหรับปีและเทอมที่สูงสุด
$sql = "SELECT activity_points FROM settings ORDER BY year DESC, term DESC LIMIT 1";
$result_settings = $conn->query($sql);
$activity_points = $result_settings->num_rows > 0 ? $result_settings->fetch_assoc()['activity_points'] : 0;

// นับจำนวนผู้ที่ผ่านและไม่ผ่านกิจกรรม
$pass_count = 0;
$fail_count = 0;
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
    if ($row['points'] >= $activity_points) {
        $pass_count++;
    } else {
        $fail_count++;
    }
}
?>

<?php
$title = "รายชื่อนักศึกษา";
include('layout.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/footer.css">
    <style>
        @font-face {
            font-family: ma;
            src: url(static/Athiti-Regular.woff);
        }

        * {
            font-family: ma;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .form-inline {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .form-inline label, .form-inline input, .form-inline select, .form-inline button {
            margin: 5px 0;
        }

        @media (max-width: 768px) {
            .form-inline label, .form-inline input, .form-inline select, .form-inline button {
                width: 100%;
                margin: 5px 0;
            }
        }

        tr {
            cursor: pointer; /* ให้ทั้งแถวสามารถกดได้ */
        }
        tr:hover {
            background-color: #B9B9B9FF; /* เปลี่ยนสีเมื่อ hover */
        }
    </style>
    <script>
        // ฟังก์ชันสำหรับนำผู้ใช้ไปยังหน้าโปรไฟล์เมื่อคลิกที่แถว
        function goToProfile(userId) {
            window.location.href = 'user_profile.php?user_id=' + userId;
        }
    </script>
</head>
<body>
    <div class="container"><br>
        <h1>รายชื่อนักศึกษา</h1>
        <hr>
        <div class="row">
            <div class="col-10">
            <!-- ฟอร์มค้นหา -->
                <form method="GET" action="admin_table_Name.php" class="form-inline">
                    <label for="search_username">รหัสนักศึกษา :</label>
                    <input type="text" id="search_username" name="search_username" value="<?php echo htmlspecialchars($search_username); ?>" class="form-control">
                    <label for="search_class">ชั้นปี :</label>
                    <select id="search_class" name="search_class" class="form-control">
                        <option value="">-- เลือกชั้นปี --</option>
                        <?php while ($row = $class_options->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($row['class']); ?>" <?php if ($row['class'] == $search_class) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($row['class']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <label for="search_department">แผนกวิชา :</label>
                    <select id="search_department" name="search_department" class="form-control">
                        <option value="">-- เลือกแผนกวิชา --</option>
                        <?php while ($row = $department_options->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($row['department']); ?>" <?php if ($row['department'] == $search_department) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($row['department']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                    <a href="admin_table_Name.php" class="btn btn-secondary">เคลียร์</a>
                </form>
            </div>
            <div class="col-2">
            <!-- ปุ่มเคลียร์แต้มกิจกรรม -->
                <form method="POST" action="clear_activity_points.php" class="mb-4">
                        <button type="submit" class="btn btn-danger mt-1" onclick="return confirm('คุณต้องการเคลียร์แต้มกิจกรรมและประวัติการเข้าร่วมกิจกรรมของนักศึกษาทุกคนหรือไม่?')">เคลียร์แต้มทั้งหมด</button>
                </form>
            </div>
        </div>
        <!-- แสดงผลการค้นหา -->
        <?php if ($search_username || $search_class || $search_department): ?>
            <p>ผลการค้นหาสำหรับ: 
                <?php if ($search_username): ?>รหัสนักศึกษา: <?php echo htmlspecialchars($search_username); ?><?php endif; ?>
                <?php if ($search_class): ?>, ชั้นปี: <?php echo htmlspecialchars($search_class); ?><?php endif; ?>
                <?php if ($search_department): ?>, แผนกวิชา: <?php echo htmlspecialchars($search_department); ?><?php endif; ?>
            </p>
        <?php endif; ?>

        <!-- สรุปยอดนักศึกษาที่ผ่านและไม่ผ่านกิจกรรม -->
        <div class="alert alert-info">
            นักศึกษาที่ผ่านกิจกรรม : <b><span style="color: green;"><?php echo $pass_count; ?></span></b>  คน &nbsp;&nbsp;
            นักศึกษาที่ไม่ผ่านกิจกรรม : <b><span style="color: red;"><?php echo $fail_count; ?></span></b>  คน
        </div>

        

        <?php if (count($users) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>ลำดับ</th>
                            <th>รหัสนักศึกษา</th>
                            <th>ชื่อ</th>
                            <th>ชั้นปี</th>
                            <th>แผนกวิชา</th>
                            <th>แต้มกิจกรรม</th>
                            <th>สถานะกิจกรรม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $index => $row): ?>
                            <tr class="text-center" onclick="goToProfile(<?php echo $row['user_id']; ?>)">
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td class="text-start"><?php echo htmlspecialchars($row['fullname']); ?></td>
                                <td><?php echo htmlspecialchars($row['class']); ?></td>
                                <td><?php echo htmlspecialchars($row['department']); ?></td>
                                <td><?php echo htmlspecialchars($row['points']); ?></td>
                                <td>
                                    <?php if ($row['points'] >= $activity_points): ?>
                                        <span style="color: green;">ผ่าน</span>
                                    <?php else: ?>
                                        <span style="color: red;">ไม่ผ่าน</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <br><br><br>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php include('footer.php'); ?>