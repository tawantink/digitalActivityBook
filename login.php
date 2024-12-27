<?php
require 'config.php'; // เชื่อมต่อกับฐานข้อมูล

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ตรวจสอบในตาราง admin ก่อน
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE admin_username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['admin_password'])) { // ใช้ password_verify
        session_start();
        $_SESSION['admin_id'] = $admin['admin_id'];
        header("Location: admin_dashboard.php"); // ไปหน้าสำหรับ Admin
        exit;
    }

    // หากไม่ใช่ admin ให้ตรวจสอบตาราง users
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) { // ใช้ password_verify
        session_start();
        $_SESSION['user_id'] = $user['id'];
        header("Location: profile.php?id=" . $user['id']);
        exit;
    } else {
        $error = "รหัสประจำตัว หรือ รหัสผ่าน ผิดพลาด";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = "DAB Login";
include('layout.php'); // เรียกใช้ Layout
?>
<body id="grad1">
    <div class="container">
        <div class="container-fluid">
            <center>
            <div class="card border-0 w-50 text-center mt-5 p-5" style="border-radius: 20px;background-color: #ffffff;box-shadow: -15px 5px 10px #0000005D;">
                <div class="align-self-center mb-2">
                    <img src="static/ytc.png" width="120" height="120">
                </div>
                <h3 class="mt-2 mb-3">Digital Activity Book</h3>
                <h5>วิทยาลัยเทคนิคยะลา</h5>
                <div class="align-self-center mt-4" id="nav-tabContent">
                    <form method="POST" action="login.php">
                        <div class="input-group flex-nowrap mx-auto">
                            <!--input/username-->
                            <div class="main">
                                <input required="" type="text" class="input" id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
                                <label>
                                    <span style="transition-delay:0ms;left:0px">ร</span>
                                    <span style="transition-delay:75ms;left:11px">หั</span>
                                    <span style="transition-delay:225ms;left:25px">ส</span>
                                    <span style="transition-delay:375ms;left:38px">ป</span>
                                    <span style="transition-delay:525ms;left:51px">ร</span>
                                    <span style="transition-delay:600ms;left:62px">ะ</span>
                                    <span style="transition-delay:675ms;left:73px">จ</span>
                                    <span style="transition-delay:750ms;left:86px">ำ</span>
                                    <span style="transition-delay:825ms;left:96px">ตั</span>
                                    <span style="transition-delay:900ms;left:110px">ว</span>
                                    <p style="position:absolute;left:-8px;top:-10px;font-size:24px;margin:10px;color:gray;transition:0.5s;pointer-events:none;">รหัสประจำตัว</p>
                                </label> 
                            </div>
                        </div>
                        <div class="input-group flex-nowrap mx-auto my-4">
                            <!--input/password-->
                            <div class="main">
                                <input required="" type="password" class="input" id="password" name="password">
                                <label>
                                    <span style="transition-delay:0ms;left:0px">ร</span>
                                    <span style="transition-delay:75ms;left:11px">หั</span>
                                    <span style="transition-delay:225ms;left:25px">ส</span>
                                    <span style="transition-delay:300ms;left:39px">ผ่</span>
                                    <span style="transition-delay:375ms;left:54px">า</span>
                                    <span style="transition-delay:450ms;left:64px">น</span>
                                    <p style="position:absolute;left:-8px;top:-10px;font-size:24px;margin:10px;color:gray;transition:0.5s;pointer-events:none;">รหัสผ่าน</p>
                                </label>
                            </div>
                        </div>
                        <?php if ($error): ?>
                            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
                        <?php endif; ?>
                        <center>
                            <button class="Btn mb-3">
                                <div class="sign">
                                    <svg viewBox="0 0 512 512">
                                        <path d="M217.9 105.9L340.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L217.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1L32 320c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM352 416l64 0c17.7 0 32-14.3 32-32l0-256c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l64 0c53 0 96 43 96 96l0 256c0 53-43 96-96 96l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32z"></path>
                                    </svg>
                                </div>
                                <div class="text">เข้าสู่ระบบ</div>
                            </button>
                        </center>
                    </form>
                </div>
            </div>
            </center>
            <!--<div class="card border-0 h-100 w-50 pt-2 mt-5" style="border-radius: 20px;background-color: #ffffff;box-shadow: -15px 5px 10px #0000005D">
                <div class="card-header border-0" style="background-color: #ffffff; border-radius: 20px;">
                    <div class="tooltip-container">
                                    <div class="icon">
                                        <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        width="50"
                                        height="50"
                                        >
                                        <path
                                            d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22c-5.518 0-10-4.482-10-10s4.482-10 10-10 10 4.482 10 10-4.482 10-10 10zm-1-16h2v6h-2zm0 8h2v2h-2z"
                                        ></path>
                                        </svg>
                                    </div>
                                    <div class="tooltip">
                                        <h6>นักเรียน-นักศึกษา</h6>
                                        <h7>รหัสประจำตัว = รหัสนักศึกษา</h7>
                                        <h7>รหัสผ่าน = วัน/เดือน/ปีเกิด</h7>
                                    </div>
                    </div>
                </div>
                <div class="card-body">
                    
                </div>
            </div>
            </div>-->
            <br><br><br><br>
        </div>
    </div>
</body>
</html>
