
<?php
session_start();

// Redirect to login.php if user is not logged in
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit();
}

?>
                

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>نظام مجلس مفتوح المصدر</title>

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/fontawesome-7.0.1/css/all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="css/aos.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="css/swiper-bundle-11.2.10.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.css" rel="stylesheet" media="all">

    <!-- Leaflet CSS-->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="page-wrapper">
        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar2">
            <div class="logo">
                <a href="main.php">
                    <img src="images/icon/logo-white.png" alt="Cool Admin" />
                </a>
            </div>
            <div class="menu-sidebar2__content js-scrollbar1">
                <div class="account2">
                    <div class="image img-cir img-120">
                        <img src="images/icon/avatar-big-01.jpg" alt="John Doe" />
                    </div>
                    <h4 class="name"><?php // If logged in, show user info and menu

echo 'مرحبا ' . htmlspecialchars($_SESSION['Name']) ;
?> </h4>
                </div>
                <nav class="navbar-sidebar2">
                    <ul class="list-unstyled navbar__list">
			<li>
    <a href="main.php">
        <i class="fas fa-home"></i> الرئيسية
    </a>
</li>

<li class="active has-sub">
    <a class="js-arrow" href="#">
        <i class="fas fa-building"></i> الوحدات
        <span class="arrow">
            <i class="fas fa-angle-down"></i>
        </span>
    </a>
    <ul class="list-unstyled navbar__sub-list js-sub-list">
      
     <?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include("Configuration/DBInfoReader.php"); // defines $Connection (mysqli)

// تأكد من وجود المالك في الجلسة
$owner_id = $_SESSION['ID'] ?? null;

if ($owner_id) {
    $sql = "SELECT id, name FROM units WHERE owner_id = ? ORDER BY id DESC";
    if ($stmt = $Connection->prepare($sql)) {
        $stmt->bind_param("i", $owner_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<li>
                        <a href="unitview.php?id=' . htmlspecialchars($row['id']) . '">
                            <i class="fas fa-building"></i> ' . htmlspecialchars($row['name']) . '
                        </a>
                      </li>';
            }
        } else {
            echo '<li><span>لا توجد وحدات مرتبطة حالياً.</span></li>';
        }

        $stmt->close();
    } else {
        echo '<li><span>حدث خطأ أثناء جلب البيانات.</span></li>';
    }
} else {
    echo '<li><span>الرجاء تسجيل الدخول لعرض الوحدات الخاصة بك.</span></li>';
}
?>
		<li>
            <a href="add-unit.php">
                <i class="fas fa-plus-circle"></i> إضافة وحدة
            </a>
        </li>
    
    </ul>
</li>

<li>
    <a href="annoncument.php">
        <i class="fas fa-bullhorn"></i> الاعلانات
    </a>
</li>

<li>
    <a href="meeting.php">
        <i class="fas fa-handshake"></i> الاجتماعات
    </a>
</li>

<li>
    <a href="aihelper.php">
        <i class="fas fa-robot"></i> مساعد الذكاء الاصطناعي
    </a>
</li>
<li>
    <a href="opendata.php">
        <i class="fas fa-database"></i> البيانات المفتوحة
    </a>
</li>

<li>
    <a href="plugins.php" >
        <i class="fas fa-chart-line"></i> الاضافات - plugins
    </a>
</li>

<!---
<li>
    <a href="reports.php">
        <i class="fas fa-chart-line"></i> التقارير
    </a>
</li>
--->
<li>
    <a href="login.php?go=logout">
        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
    </a>
</li>

						
                      
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container2">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop2">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap2">
                            <div class="logo d-block d-lg-none">
                                <a href="#">
                                    <img src="images/icon/logo-white.png" alt="CoolAdmin" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <aside class="menu-sidebar2 js-right-sidebar d-block d-lg-none">
                <div class="logo">
                    <a href="#">
                        <img src="images/icon/logo-white.png" alt="Cool Admin" />
                    </a>
                </div>
                <div class="menu-sidebar2__content js-scrollbar2">
              </div>
			 
            </aside>
            <!-- END HEADER DESKTOP-->
			
		 <br><br>