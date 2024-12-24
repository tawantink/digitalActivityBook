<?php
// การเชื่อมต่อฐานข้อมูล
require 'configCon.php';

// ตรวจสอบว่าผู้ใช้เลือกห้องเรียนอะไร (ค่าเริ่มต้นเป็น "ปวส.2/1")
$selected_class = isset($_POST['class']) ? $_POST['class'] : 'ปวส.2/1';

// ตรวจสอบว่าค่าที่ส่งมานั้นถูกต้อง
$valid_classes = ['ปวส.2/1', 'ปวส.2/2', 'ปวส.2/3'];
if (!in_array($selected_class, $valid_classes)) {
    $selected_class = 'ปวส.2/1';
}

// ใช้ prepared statement เพื่อดึงข้อมูลจากฐานข้อมูล
$stmt = $conn->prepare("SELECT id, username, fullname, Ac_point FROM users WHERE class = ?");
$stmt->bind_param("s", $selected_class); // กำหนดค่าของ $selected_class
$stmt->execute();
$result = $stmt->get_result();

// ตรวจสอบว่าได้รับค่าจากฟอร์มเพิ่มคะแนนหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_points'])) {
    // ตรวจสอบว่ามีค่าพิเศษเพิ่มคะแนนและการส่งฟอร์มมีค่าที่ถูกต้อง
    if (!empty($_POST['additional_points']) && isset($_POST['student_id'])) {
        $student_id = $_POST['student_id'];
        $additional_points = $_POST['additional_points'];
        
        // เพิ่มคะแนนกิจกรรมให้กับนักศึกษา
        $update_stmt = $conn->prepare("UPDATE users SET Ac_point = Ac_point + ? WHERE id = ?");
        $update_stmt->bind_param("ii", $additional_points, $student_id);
        $update_stmt->execute();
        $update_stmt->close();

        // หลังจากอัปเดตแล้วรีเฟรชหน้าเพื่อป้องกันการส่งฟอร์มซ้ำ
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Student Name</title>
    <link rel="icon" href="static/ytc.png" type="image/x-icon">
    <link rel="shortcut icon" href="static/ytc.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/tooltip.css">
    <link rel="stylesheet" href="css/footer.css">
    <style>
        @font-face {
            font-family: ma;
            src: url(static/Athiti-Regular.woff);
        }

        * {
            font-family: ma;
        }
        /* กำหนดสไตล์ให้กับตาราง */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            text-align: center;
        }
        tr:hover {
            background-color: #B9B9B9FF;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>รายชื่อนักศึกษา</h1>
        
        <!-- ฟอร์มเลือกห้องเรียน -->
        <form method="POST" action="">
            <label for="class">เลือกห้องเรียน:</label>
            <select name="class" id="class" onchange="this.form.submit()">
                <option value="ปวส.2/1" <?php if ($selected_class == 'ปวส.2/1') echo 'selected'; ?>>ปวส.2/1</option>
                <option value="ปวส.2/2" <?php if ($selected_class == 'ปวส.2/2') echo 'selected'; ?>>ปวส.2/2</option>
                <option value="ปวส.2/3" <?php if ($selected_class == 'ปวส.2/3') echo 'selected'; ?>>ปวส.2/3</option>
            </select>
        </form>

        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">ลำดับ</th>
                    <th style="width: 200px;">ชื่อผู้ใช้</th>
                    <th style="width: 500px;">ชื่อเต็ม</th>
                    <th style="width: 100px;">แต้มกิจกรรม</th>
                    <th style="width: 150px;">เพิ่มคะแนน</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $serial_number = 1; // กำหนดตัวแปรนับลำดับเริ่มต้น
                if ($result->num_rows > 0) {
                    // แสดงผลข้อมูลในแต่ละแถว
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td style='text-align: center;'>{$serial_number}</td> <!-- ใช้ตัวแปรลำดับแทน id -->
                            <td>{$row['username']}</td>
                            <td>{$row['fullname']}</td>
                            <td style='text-align: center;'>{$row['Ac_point']}</td>
                            <td>
                                <!-- ฟอร์มเพิ่มคะแนนกิจกรรม -->
                                <form method='POST' action=''>
                                    <input type='hidden' name='student_id' value='{$row['id']}'>
                                    <input style='width: 80px;' type='number' name='additional_points' min='1' required>
                                    <button type='submit' name='add_points' class='btn btn-success btn-sm'>เพิ่มคะแนน</button>
                                </form>
                            </td>
                        </tr>";
                        $serial_number++; // เพิ่มค่าลำดับ
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
// ปิดการเชื่อมต่อฐานข้อมูล
$stmt->close();
$conn->close();
?>
