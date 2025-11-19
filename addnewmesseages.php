<?php
include("Configuration/Header.php");
include("Configuration/DBInfoReader.php");

$message = ""; 
$current_user_id = 1; // TODO: استبدالها برقم المستخدم من الجلسة

// Get unit_id from GET
$unit_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if unit_id missing
if ($unit_id <= 0) {
    echo "<div class='alert alert-danger m-4'>⚠️ رقم الوحدة غير موجود في الرابط.</div>";
    include("Configuration/Footer.php");
    exit;
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $msg_text = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (empty($msg_text)) {
        $message = "<div class='alert alert-danger m-4'>
            ⚠️ الرجاء كتابة نص الرسالة.
        </div>";
    } else {

        // Insert new message
        $query = "INSERT INTO messages (unit_id, Sender, Read_Y_N, Message, Sending_Time)
                  VALUES (?, ?, 'N', ?, NOW())";

        $stmt = mysqli_prepare($Connection, $query);

        if (!$stmt) {
            $message = "<div class='alert alert-danger m-4'>
                ❌ خطأ في قاعدة البيانات: " . mysqli_error($Connection) . "
            </div>";
        } else {

            mysqli_stmt_bind_param($stmt, "iis",
                $unit_id, $current_user_id, $msg_text
            );

            if (mysqli_stmt_execute($stmt)) {
                $message = "<div class='alert alert-success m-4'>
                    ✅ تم إرسال الرسالة بنجاح!
                </div>";
            } else {
                $message = "<div class='alert alert-danger m-4'>
                    ❌ فشل إدراج الرسالة: " . mysqli_error($Connection) . "
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
            <h2 class="text-right">إرسال رسالة جديدة للوحدة رقم <?php echo $unit_id; ?></h2>
            <hr>

            <?php echo $message; ?>

            <form method="POST" action="" dir="rtl">

                <!-- Hidden unit_id -->
                <input type="hidden" name="unit_id" value="<?php echo $unit_id; ?>">

                <div class="form-group mb-3">
                    <label for="message">نص الرسالة: <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">إرسال الرسالة</button>
            </form>
        </div>
    </div>
</div>

<?php include("Configuration/Footer.php"); ?>
