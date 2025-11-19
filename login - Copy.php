<?php

if (isset($_GET['go'])) {
    // بدء الجلسة إذا لم تكن قد بدأت
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // مسح جميع متغيرات الجلسة
    $_SESSION = array();

    // تدمير الجلسة بالكامل
    session_destroy();

    // إعادة التوجيه إلى صفحة تسجيل الدخول
    header("Location: index.html");
    exit();
}
session_start();
include("Configuration/DBInfoReader.php");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize input
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "يرجى إدخال البريد الإلكتروني وكلمة المرور.";
    } else {
        // Hash password (if your DB uses MD5)
        $hashedPassword = md5($password);

        // Prepare query safely
        $query = "SELECT * FROM users WHERE Email = ? LIMIT 1";
        $stmt = mysqli_prepare($Connection, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if ($row['Password'] === $hashedPassword) {
                // ✅ Login successful
                $_SESSION['ID'] = $row['ID'];
                $_SESSION['Name'] = $row['Name'];
                $_SESSION['Email'] = $row['Email'];

                header("Location: main.php");
                exit();
            } else {
                $error = "كلمة المرور غير صحيحة.";
            }
        } else {
            $error = "البريد الإلكتروني غير مسجل.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>تسجيل الدخول</title>

    <!-- CSS includes -->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/fontawesome-7.0.1/css/all.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: "Tajawal"; }
        .error-msg { color: red; text-align: center; margin-bottom: 15px; }
  
    * { font-family: "Tajawal"; }

    .video-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1; /* behind everything */
    }

    /* Make page background transparent so video shows */
    .page-content--bge5, 
    .page-wrapper,
    body {
        background: transparent !important;
    }

    /* Style login box to appear over video */
    .login-content {
        background: rgba(255, 255, 255, 0.85); 
        border-radius: 12px;
        backdrop-filter: blur(4px);
    }
</style>
</head>

<body>

    <!-- Video Background -->
    <video class="video-bg" autoplay muted loop playsinline>
        <source src="assets/images/backgrounds/banner-video.mp4" type="video/mp4">
    </video>
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="#"><img src="images/icon/logo.png" alt="Logo"></a>
                        </div>
                        <div class="login-form">
                            <?php if (!empty($error)): ?>
                                <div class="error-msg"><?php echo $error; ?></div>
                            <?php endif; ?>

                            <form action="" method="post">
                                <div class="form-group">
                                    <label>عنوان البريد الإلكتروني</label>
                                    <input class="au-input au-input--full" type="email" name="email" placeholder="البريد الإلكتروني" required>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label>كلمة المرور</label>
                                    <input class="au-input au-input--full" type="password" name="password" placeholder="كلمة المرور" required>
                                </div>
                                <br>
                                <div class="login-checkbox">
                                    <label><input type="checkbox" name="remember"> تذكرني</label>
                                </div>
                                <br>
                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">تسجيل الدخول</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS includes -->
    <script src="vendor/bootstrap-5.3.8.bundle.min.js"></script>
</body>
</html>
