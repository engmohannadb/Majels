<?php
include("Configuration/Header.php");
include("Configuration/DBInfoReader.php");

$message = ""; 
$current_user_id = 1; // TODO: استبدالها برقم المستخدم من الجلسة

// Get unit_id from GET
$unit_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Verify GET value
if ($unit_id <= 0) {
    echo "<div class='alert alert-danger m-4'>⚠️ رقم الوحدة غير موجود في الرابط.</div>";
    include("Configuration/Footer.php");
    exit;
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $request_title = isset($_POST['request_title']) ? trim($_POST['request_title']) : '';
    $request_text  = isset($_POST['request_text']) ? trim($_POST['request_text']) : '';

    // Basic validation
    if (empty($request_title) || empty($request_text)) {
        $message = "<div class='alert alert-danger m-4'>
            ⚠️ الرجاء ملء جميع الحقول المطلوبة (عنوان الطلب ونص الطلب).
        </div>";
    } else {

        // Insert request
        $query = "INSERT INTO requests 
                    (unit_id, user_id, request_title, request_text, status, request_date, last_update)
                  VALUES 
                    (?, ?, ?, ?, 'open', NOW(), NULL)";

        $stmt = mysqli_prepare($Connection, $query);

        if (!$stmt) {
            $message = "<div class='alert alert-danger m-4'>
                ❌ خطأ في قاعدة البيانات: " . mysqli_error($Connection) . "
            </div>";
        } else {

            mysqli_stmt_bind_param($stmt, "iiss",
                $unit_id, $current_user_id, $request_title, $request_text
            );

            if (mysqli_stmt_execute($stmt)) {
                $message = "<div class='alert alert-success m-4'>
                    ✅ تم إرسال طلب الصيانة بنجاح!
                </div>";
            } else {
                $message = "<div class='alert alert-danger m-4'>
                    ❌ فشل إدراج الطلب: " . mysqli_error($Connection) . "
                </div>";
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>

<div class="container mt-5" style="text-align:right;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-right">إضافة طلب صيانة جديد للوحدة رقم <?php echo $unit_id; ?></h2>
            <hr>

            <?php echo $message; ?>

            <form method="POST" action="" dir="rtl">

                <!-- Hidden unit_id -->
                <input type="hidden" name="unit_id" value="<?php echo $unit_id; ?>">

                <div class="form-group mb-3">
                    <label for="request_title">عنوان الطلب: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="request_title" name="request_title" required maxlength="255">
                </div>

                <div class="form-group mb-3">
                    <label for="request_text">تفاصيل الطلب: <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="request_text" name="request_text" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">إرسال الطلب</button>
            </form>
        </div>
		
    <div class="text-left mb-5">
        <a href="unitview.php?id=<?php echo $_GET["id"]; ?>" class="btn btn-secondary">العودة للوحدة</a>
    </div>
    </div>
</div>

<?php include("Configuration/Footer.php"); ?>
