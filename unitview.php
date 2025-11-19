<?php
/* version 0.6 — dynamic unit view (units + events) */

include("Configuration/Header.php");
include("Configuration/DBInfoReader.php");

// ✅ Validate and sanitize the ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger m-4'>معرف الوحدة غير صالح</div>";
    include("Configuration/Footer.php");
    exit();
}

$unit_id = intval($_GET['id']);

// ✅ Fetch unit information with owner data
$query = "SELECT 
            u.*, 
            ow.Name AS OwnerName,
            ow.Email AS OwnerEmail,
            ow.Picture_Filename AS OwnerPicture
          FROM units u
          LEFT JOIN users ow ON u.owner_id = ow.ID
          WHERE u.id = ? 
          LIMIT 1";

$stmt = mysqli_prepare($Connection, $query);
if (!$stmt) {
    die("<div class='alert alert-danger m-4'>خطأ في قاعدة البيانات: " . mysqli_error($Connection) . "</div>");
}
mysqli_stmt_bind_param($stmt, "i", $unit_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$unit = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$unit) {
    echo "<div class='alert alert-warning m-4'>لم يتم العثور على الوحدة المطلوبة</div>";
    include("Configuration/Footer.php");
    exit();
}
?>

<!-- =================== BREADCRUMB =================== -->
<section class="au-breadcrumb m-t-75">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="au-breadcrumb-content">
                        <div class="au-breadcrumb-left">
                            <ul class="list-unstyled list-inline au-breadcrumb__list">
                                <li class="list-inline-item active">
                                    <a href="#">وحدة <?php echo htmlspecialchars($unit['name']); ?></a>
                                </li>
                                <li class="list-inline-item seprate"></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =================== MAIN CONTENT =================== -->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">

            <!-- Header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">ملخص <?php echo htmlspecialchars($unit['name']); ?></h2>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row m-t-25">

                <!-- إجمالي الأعضاء -->
                <div class="col-sm-6 col-lg-3">
                    <div class="overview-item overview-item--c1">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="icon"><i class="zmdi zmdi-accounts"></i></div>
                                <div class="text">
                                    <h2><?php echo htmlspecialchars($unit['total_members'] ?? 0); ?></h2>
                                    <span>إجمالي الأعضاء</span>
                                </div>
                            </div>
                            <div class="overview-chart"><canvas id="widgetChart1"></canvas></div>
                        </div>
                    </div>
                </div>

                <!-- إجمالي الطلبات -->
                <div class="col-sm-6 col-lg-3">
                    <div class="overview-item overview-item--c2">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="icon"><i class="zmdi zmdi-assignment"></i></div>
                                <div class="text">
                                    <h2><?php echo htmlspecialchars($unit['total_requests'] ?? 0); ?></h2>
                                    <span>إجمالي الطلبات</span>
                                </div>
                            </div>
                            <div class="overview-chart"><canvas id="widgetChart2"></canvas></div>
                        </div>
                    </div>
                </div>

                <!-- الطلبات المعلقة -->
                <div class="col-sm-6 col-lg-3">
                    <div class="overview-item overview-item--c3">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="icon"><i class="zmdi zmdi-time-restore"></i></div>
                                <div class="text">
                                    <h2><?php echo htmlspecialchars($unit['pending_requests'] ?? 0); ?></h2>
                                    <span>الطلبات المعلقة</span>
                                </div>
                            </div>
                            <div class="overview-chart"><canvas id="widgetChart3"></canvas></div>
                        </div>
                    </div>
                </div>

                <!-- المالك -->
                <div class="col-sm-6 col-lg-3">
                    <div class="overview-item overview-item--c4">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="text">
                                    <h2><?php echo htmlspecialchars($unit['OwnerName'] ?? 'غير معروف'); ?></h2>
                                    <span>اسم المنسق</span>
                                </div>
                            </div>
                            <div class="overview-chart"><canvas id="widgetChart4"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- تفاصيل الوحدة -->
      <div class="row mt-4">
    <div class="col-lg-6">

    <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
        
        <!-- نفس ستايل الهيدر -->
        <div class="au-card-title" style="background-image:url('images/bg-title-02.jpg');">
            <div class="bg-overlay bg-overlay--blue"></div>
            <h3><i class="zmdi zmdi-home"></i> تفاصيل الوحدة</h3>
        </div>

        <div class="au-task js-list-load">

            <div class="au-task__title"><p>معلومات الوحدة</p></div>

            <div class="au-task-list p-3">

                <p><strong>الوصف:</strong> 
                    <?php echo nl2br(htmlspecialchars($unit['description'] ?? 'لا يوجد وصف.')); ?>
                </p>

                <p><strong>العنوان:</strong> 
                    <?php echo htmlspecialchars($unit['address'] ?? 'غير محدد'); ?>
                </p>

                <p><strong>تاريخ الإنشاء:</strong> 
                    <?php echo htmlspecialchars($unit['created_at'] ?? 'غير متوفر'); ?>
                </p>

                <!-- خريطة -->
                <div class="embed-responsive embed-responsive-4by3 mt-3">
                    <?php
                        $location = $unit['location'] ?? 'Riyadh, Saudi Arabia';
                        $encoded_location = urlencode($location);
                        $map_url = "https://maps.google.com/maps?q=" . $encoded_location . "&t=&z=15&ie=UTF8&iwloc=&output=embed";
                    ?>

                    <iframe
                        class="embed-responsive-item"
                        src="<?php echo htmlspecialchars($map_url); ?>"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        style="border:0; width:100%; height:400px;">
                    </iframe>
                </div>

            </div>
        </div>
    </div>

</div>

 

 <div class="col-lg-6">

    <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
        <div class="au-card-title" style="background-image:url('images/bg-title-01.jpg');">
            <div class="bg-overlay bg-overlay--blue"></div>
            <h3><i class="zmdi zmdi-assignment"></i> الطلبات الخاصة بالوحدة</h3>
        </div>

        <div class="au-task js-list-load">

            <!-- زر إضافة طلب -->
            <a href="addnewerequests.php?id=<?php echo $_GET['id']; ?>" 
               style="font-size: 38px; float: left; background-color: white;">
                <i class="fas fa-plus-circle fasicon"></i>
            </a>

            <div class="au-task__title"><p>آخر الطلبات</p></div>

            <div class="au-task-list js-scrollbar3">
                <?php
                // استعلام جلب الطلبات الخاصة بالوحدة
                $requests_sql = "SELECT id,request_title, request_text, status, request_date 
                                 FROM requests
                                 WHERE unit_id = ?
                                 ORDER BY request_date DESC";

                $requests_stmt = mysqli_prepare($Connection, $requests_sql);

                if ($requests_stmt) {

                    mysqli_stmt_bind_param($requests_stmt, "i", $unit_id);
                    mysqli_stmt_execute($requests_stmt);
                    $requests_result = mysqli_stmt_get_result($requests_stmt);

                    if (mysqli_num_rows($requests_result) > 0) {

                        while ($req = mysqli_fetch_assoc($requests_result)) {

                            // تحويل الحالة إلى عربي + ألوان
                            $status = $req['status'];
                            $status_text = "";
                            $status_color = "";

                            switch ($status) {
                                case "open": $status_text = "مفتوح"; $status_color = "primary"; break;
                                case "pending": $status_text = "قيد الانتظار"; $status_color = "warning"; break;
                                case "in review": $status_text = "قيد المراجعة"; $status_color = "info"; break;
                                case "in progress": $status_text = "قيد التنفيذ"; $status_color = "success"; break;
                                case "hold": $status_text = "معلق"; $status_color = "secondary"; break;
                                case "done": $status_text = "مكتمل"; $status_color = "success"; break;
                                case "canceled": $status_text = "ملغي"; $status_color = "danger"; break;
                                default: $status_text = $status; $status_color = "dark";
                            }

                            echo '
                            <div class="au-task__item au-task__item--' . $status_color . '">
                                <div class="au-task__item-inner">
                                   <a href="viewrequest.php?rid=' . htmlspecialchars($req['id']) . '"> <h5 class="task">' . htmlspecialchars($req['request_title']) . '</h5>

                                    <span class="time">' . htmlspecialchars($req['request_date']) . '</span>

                                    <p class="mt-2 text-muted small">' . htmlspecialchars($req['request_text']) . '</p>

                                    <span class="badge bg-' . $status_color . '" 
                                          style="font-size:13px; padding:6px 10px;">
                                        ' . $status_text . '
                                    </span></a>
                                </div>
                            </div>';
                        }

                    } else {
                        echo '<div class="p-3 text-center text-muted">لا توجد طلبات حالياً</div>';
                    }

                    mysqli_stmt_close($requests_stmt);

                } else {
                    echo '<div class="p-3 text-center text-danger">فشل تحميل الطلبات.</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
         


</div>

            <!-- الأحداث -->
            <div class="row mt-5">
                <div class="col-lg-6">

                    <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
                        <div class="au-card-title" style="background-image:url('images/bg-title-01.jpg');">
						
                            <div class="bg-overlay bg-overlay--blue"></div>
                            <h3><i class="zmdi zmdi-calendar"></i> الأحداث المرتبطة بالوحدة</h3>
                        </div>
                        <div class="au-task js-list-load">
						<a href="addnewevent.php?id=<?php echo $_GET['id']; ?>" style=" font-size: 38px;   float: left;   background-color: white;"><i class="fas fa-plus-circle fasicon"></i></a>
                       
						     <div class="au-task__title"><p>آخر الأحداث</p></div>
                            <div class="au-task-list js-scrollbar3">
                                <?php
                                $events_sql = "SELECT Title, Description, Event_Date 
                                               FROM events 
                                               WHERE Unit_ID = ? 
                                               ORDER BY Event_Date DESC 
                                               ";
                                $events_stmt = mysqli_prepare($Connection, $events_sql);
                                if ($events_stmt) {
                                    mysqli_stmt_bind_param($events_stmt, "i", $unit_id);
                                    mysqli_stmt_execute($events_stmt);
                                    $events_result = mysqli_stmt_get_result($events_stmt);

                                    if (mysqli_num_rows($events_result) > 0) {
                                        while ($event = mysqli_fetch_assoc($events_result)) {
                                            echo '
                                            <div class="au-task__item au-task__item--primary">
                                                <div class="au-task__item-inner">
                                                    <h5 class="task">' . htmlspecialchars($event['Title']) . '</h5>
                                                    <span class="time">' . htmlspecialchars($event['Event_Date']) . '</span>
                                                    <p class="mt-2 text-muted small">' . htmlspecialchars($event['Description']) . '</p>
                                                </div>
                                            </div>';
                                        }
                                    } else {
                                        echo '<div class="p-3 text-center text-muted">لا توجد أحداث حالياً</div>';
                                    }

                                    mysqli_stmt_close($events_stmt);
                                } else {
                                    echo '<div class="p-3 text-center text-danger">فشل تحميل الأحداث.</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
     <div class="col-lg-6">
    <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
        <div class="au-card-title" style="background-image:url('images/bg-title-02.jpg');">
            <div class="bg-overlay bg-overlay--blue"></div>
            <h3>
                <i class="zmdi zmdi-comment-text"></i> الرسائل 
            </h3>
        </div>
		<a href="addnewmesseages.php?id=<?php echo $_GET['id']; ?>" style=" font-size: 38px;   float: left;   background-color: white;"><i class="fas fasicon fa-plus-circle"></i></a>
         
        <div class="au-inbox-wrap js-inbox-wrap">
		              
            <div class="au-message js-list-load">
                <?php
                // ✅ Ensure DB connection is already included in header
                // Example: $Connection is available

                // Get the latest 10 unread messages
                $query = "
                    SELECT m.*, 
                           s.Name AS SenderName,
                           s.Picture_Filename AS SenderPicture
                    FROM messages m
                    LEFT JOIN users s ON m.Sender = s.ID
					WHERE m.unit_id = ".$_GET['id']."
                    ORDER BY m.Sending_Time DESC
                ";

                $result = mysqli_query($Connection, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    $unreadCount = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['Read_Y_N'] == 0) $unreadCount++;
                    }
                    mysqli_data_seek($result, 0); // reset pointer

                    echo '<div class="au-message__noti">
                            <p>لديك <span>' . $unreadCount . '</span> رسائل جديدة</p>
                          </div>';

                    echo '<div class="au-message-list">';
                    while ($row = mysqli_fetch_assoc($result)) {
                        $senderName = htmlspecialchars($row['SenderName'] ?? 'مستخدم');
                        $message = htmlspecialchars($row['Message']);
                        $time = date("Y-m-d H:i", strtotime($row['Sending_Time']));
                        $unreadClass = $row['Read_Y_N'] == 0 ? 'unread' : '';

                        echo '
                        <div class="au-message__item ' . $unreadClass . '">
                            <div class="au-message__item-inner">
                                <div class="au-message__item-text">
                              
                                    <div class="text">
                                        <h5 class="name">' . $senderName . '</h5>
                                        <p>' . $message . '</p>
                                    </div>
                                </div>
                                <div class="au-message__item-time">
                                    <span>' . $time . '</span>
                                </div>
                            </div>
                        </div>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="p-3 text-center text-muted">لا توجد رسائل بعد.</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
         
		   </div>

        </div>
    </div>
</div>

<?php include("Configuration/Footer.php"); ?>
