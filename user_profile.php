<?php
// filepath: /c:/xampp/htdocs/pj/digitalActivityBook/user_profile.php
include 'configCon.php';

// รับค่าจาก URL
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
if (!$user_id) {
    echo "Invalid request! The 'id' parameter is missing.";
    exit;
}

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

$stmt->close();

// ดึงข้อมูลกิจกรรมจากตาราง event_history และ events
$sql = "SELECT e.event_name, e.event_point, eh.check_status 
        FROM history eh 
        JOIN events e ON eh.event_id = e.event_id
        WHERE eh.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$events_result = $stmt->get_result();

$events = [];
$new_points = 0;
while ($row = $events_result->fetch_assoc()) {
    $events[] = $row;
    if ($row['check_status'] === 'true' || $row['check_status'] == 1) {
        $new_points += $row['event_point']; // คำนวณเฉพาะแต้มใหม่
    }
}

$stmt->close();

// อัปเดตคะแนนรวม (เฉพาะแต้มใหม่)
$update_sql = "UPDATE users SET points = ? WHERE user_id = ?";
$updated_points = $new_points; // ใช้แต้มที่คำนวณใหม่แทนการบวกซ้ำ
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("ii", $updated_points, $user_id);
$update_stmt->execute();
$update_stmt->close();

// ดึงค่า activity_points จากตาราง settings สำหรับปีและเทอมที่สูงสุด
$sql = "SELECT activity_points FROM settings ORDER BY year DESC, term DESC LIMIT 1";
$result_settings = $conn->query($sql);
$activity_points = $result_settings->num_rows > 0 ? $result_settings->fetch_assoc()['activity_points'] : 0;

$conn->close();
?>

<?php 
$title = "ข้อมูล : " . htmlspecialchars($user['username']);

include('layout.php'); ?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile of <?= htmlspecialchars($user['username']) ?></title>
    <style>
        .status-true {
            color: green;
        }
        .status-false {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container"><br>
        <h1>ข้อมูลกิจกรรมของ <?= htmlspecialchars($user['fullname']) ?></h1>
        <hr><br>
        <div class="row">
            <div class="col-4">
                <?php if ($user['avatar']): ?>
                <p><img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar" width="100"></p>
                <?php endif; ?>
                <p><strong>รหัสนักศึกษา :</strong> <?= htmlspecialchars($user['username']) ?></p>
                <p><strong>ชื่อ-นามสกุล :</strong> <?= htmlspecialchars($user['fullname']) ?></p>
                <p><strong>ชั้นปี :</strong> <?= htmlspecialchars($user['class']) ?></p>
                <p><strong>แผนกวิชา :</strong> <?= htmlspecialchars($user['department']) ?></p>
                <p><strong>จำนวนแต้มกิจกรรม :</strong> <?= htmlspecialchars($updated_points) ?></p>
                <p><strong>สถานะกิจกรรม :</strong> 
                    <?php if ($updated_points >= $activity_points): ?>
                        <span style="color: green; font-size: 40px;"><b>ผ่าน</b></span>
                    <?php else: ?>
                        <span style="color: red; font-size: 40px;"><b>ไม่ผ่าน</b></span>
                    <?php endif; ?>
                </p>
            </div>
            <div class="col-8">
                <h2>กิจกรรมวิทยาลัยที่เข้าร่วม</h2>
                <?php if (count($events) > 0): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>ชื่อกิจกรรม</th>
                                <th>แต้มกิจกรรม</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($events as $index => $event): ?>
                                <tr class="text-center">
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($event['event_name']) ?></td>
                                    <td><?= htmlspecialchars($event['event_point']) ?></td>
                                    <td class="<?= $event['check_status'] ? 'status-true' : 'status-false' ?>">
                                        <?= $event['check_status'] ? 'เข้าร่วมแล้ว' : 'ยังไม่เข้าร่วม' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <p><strong>แต้มกิจกรรมรวมทั้งหมด:</strong> <?= $updated_points ?></p>
                <?php else: ?>
                <p>ยังไม่มีกิจกรรมที่เข้าร่วม</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php include('footer.php'); ?>