<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/add_term_form.php
include 'configCon.php';

// ดึงค่าปีสูงสุดจากตาราง settings
$sql = "SELECT MAX(year) AS max_year FROM settings";
$result = $conn->query($sql);
$max_year = $result->num_rows > 0 ? $result->fetch_assoc()['max_year'] : null;

if ($max_year !== null) {
    // ตรวจสอบว่าในปีการศึกษาสูงสุดมีครบทั้งสองเทอมหรือไม่
    $sql = "SELECT term FROM settings WHERE year = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $max_year);
    $stmt->execute();
    $result = $stmt->get_result();

    $terms = [];
    while ($row = $result->fetch_assoc()) {
        $terms[] = $row['term'];
    }

    if (in_array(1, $terms) && in_array(2, $terms)) {
        // ถ้ามีครบทั้งสองเทอมแล้ว ให้กำหนดปีถัดไปและเทอม 1
        $next_year = $max_year + 1;
        $next_term = 1;
    } else {
        // ถ้ายังไม่ครบ ให้กำหนดเทอมที่ยังไม่มีในปีการศึกษาสูงสุด
        $next_year = $max_year;
        $next_term = in_array(1, $terms) ? 2 : 1;
    }

    $stmt->close();
} else {
    // ถ้าไม่มีข้อมูลปีการศึกษาในฐานข้อมูล ให้กำหนดปีการศึกษาเอง
    $next_year = '';
    $next_term = 1;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มภาคเรียน/ปีการศึกษา</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @font-face {
            font-family: ma;
            src: url(static/Athiti-Regular.woff);
        }

        * {
            font-family: ma;
        }
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            margin-bottom: 30px;
        }
        .form-group label {
            font-size: 18px;
        }
        .btn {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container w-25">
        <h2>เพิ่มภาคเรียน/ปีการศึกษา</h2>
        <form id="add-term-form" method="post" action="add_new_term.php">
            <div class="form-group">
                <label for="new-term">ภาคเรียน:</label>
                <input type="text" id="new-term" name="new_term" class="form-control" value="<?= htmlspecialchars($next_term) ?>" readonly>
            </div>
            <div class="form-group">
                <label for="new-year">ปีการศึกษา:</label>
                <input type="number" id="new-year" name="year" class="form-control" value="<?= htmlspecialchars($next_year) ?>" <?= $next_year === '' ? '' : 'readonly' ?> placeholder="ตัวอย่าง: 2567">
            </div>
            <div class="form-group">
                <label for="new-activity-points">แต้มกิจกรรมที่ใช้เป็นเกณฑ์ในการผ่าน:</label>
                <input type="number" id="new-activity-points" name="activity_points" class="form-control" placeholder="ตัวอย่าง: 50">
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success w-50">บันทึก</button>
                <a href="admin_dashboard.php" class="btn btn-secondary w-50 ml-2">ยกเลิก</a>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>