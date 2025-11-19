<?php
session_start();
include("Configuration/DBInfoReader.php");

$error = "";

// Handle classic login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_type']) && $_POST['login_type'] == 'classic') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "يرجى إدخال البريد الإلكتروني وكلمة المرور.";
    } else {
        $hashedPassword = md5($password);

        $query = "SELECT * FROM users WHERE Email = ? LIMIT 1";
        $stmt = mysqli_prepare($Connection, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if ($row['Password'] === $hashedPassword) {
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

// Handle Google login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_type']) && $_POST['login_type'] == 'google') {
    $id_token = $_POST['id_token'];

    // Verify token using Google API endpoint
    $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . $id_token;
    $response = file_get_contents($url);
    $user_data = json_decode($response, true);

    if (isset($user_data['email'])) {
        $email = $user_data['email'];
        $name = $user_data['name'] ?? $user_data['email'];

        // Check if user exists
        $query = "SELECT * FROM users WHERE Email = ? LIMIT 1";
        $stmt = mysqli_prepare($Connection, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['ID'] = $row['ID'];
            $_SESSION['Name'] = $row['Name'];
            $_SESSION['Email'] = $row['Email'];
        } else {
            // Insert new user
            $insert = "INSERT INTO users (Name, Email, Password) VALUES (?, ?, '')";
            $stmt2 = mysqli_prepare($Connection, $insert);
            mysqli_stmt_bind_param($stmt2, "ss", $name, $email);
            mysqli_stmt_execute($stmt2);

            $_SESSION['ID'] = mysqli_insert_id($Connection);
            $_SESSION['Name'] = $name;
            $_SESSION['Email'] = $email;
        }

        header("Location: main.php");
        exit();
    } else {
        $error = "فشل تسجيل الدخول عبر Google.";
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
    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <style>
        * { font-family: "Tajawal"; }
        .error-msg { color: red; text-align: center; margin-bottom: 15px; }

        .video-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover; z-index: -1;
        }
        body, .page-wrapper, .page-content--bge5 { background: transparent !important; }
        .login-content { background: rgba(255,255,255,0.85); border-radius:12px; backdrop-filter: blur(4px); }
    </style>
</head>
<body>

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

                        <!-- Classic Login -->
                        <form action="" method="post">
                            <input type="hidden" name="login_type" value="classic">
                            <div class="form-group">
                                <label>عنوان البريد الإلكتروني</label>
                                <input class="au-input au-input--full" type="email" name="email" placeholder="البريد الإلكتروني" required>
                            </div>
                            <div class="form-group">
                                <label>كلمة المرور</label>
                                <input class="au-input au-input--full" type="password" name="password" placeholder="كلمة المرور" required>
                            </div>
                            <div class="login-checkbox">
                                <label><input type="checkbox" name="remember"> تذكرني</label>
                            </div>
                            <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">تسجيل الدخول</button>
                        </form>

                        <hr>

                        <!-- Google Login -->
                        <div id="g_id_onload"
                             data-client_id="YOUR_GOOGLE_CLIENT_ID.apps.googleusercontent.com"
                             data-login_uri=""
                             data-auto_prompt="false">
                        </div>
                        <div class="g_id_signin" data-type="standard" data-shape="rectangular" data-theme="outline"
                             data-width="300" data-height="50" data-text="signin_with" data-size="large"
                             data-logo_alignment="left"></div>

                        <script>
                            function handleCredentialResponse(response) {
                                // Send token to server
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = '';

                                const inputType = document.createElement('input');
                                inputType.type = 'hidden';
                                inputType.name = 'login_type';
                                inputType.value = 'google';
                                form.appendChild(inputType);

                                const inputToken = document.createElement('input');
                                inputToken.type = 'hidden';
                                inputToken.name = 'id_token';
                                inputToken.value = response.credential;
                                form.appendChild(inputToken);

                                document.body.appendChild(form);
                                form.submit();
                            }

                            window.onload = function() {
                                google.accounts.id.initialize({
                                    client_id: "290947476547-indqe507ov2iouee0vr1im9s6n2dgjc6.apps.googleusercontent.com",
                                    callback: handleCredentialResponse
                                });
                                google.accounts.id.renderButton(
                                    document.querySelector(".g_id_signin"),
                                    { theme: "outline", size: "large", shape: "rectangular", text: "signin_with" }
                                );
                                google.accounts.id.prompt(); // show the One Tap prompt
                            }
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="vendor/bootstrap-5.3.8.bundle.min.js"></script>
</body>
</html>
