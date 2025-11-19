<?php
include("Configuration/Header.php");
include("Configuration/DBInfoReader.php");

$message = ""; 
$current_user_id = 1; // TODO: replace with session user id

// Get unit_id from GET
$unit_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if unit_id missing
if ($unit_id <= 0) {
    echo "<div class='alert alert-danger m-4'>⚠️ رقم الوحدة غير موجود في الرابط.</div>";
    include("Configuration/Footer.php");
    exit;
}

// 1. Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve other data
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $event_date = isset($_POST['event_date']) ? trim($_POST['event_date']) : '';

    // Basic validation
    if (empty($title) || empty($event_date)) {
        $message = "<div class='alert alert-danger m-4'>
            ⚠️ الرجاء ملء العنوان وتاريخ الحدث.
        </div>";
    } else {

        // Prepared insert
        $query = "INSERT INTO events (unit_id, title, description, event_date, created_by, created_at)
                  VALUES (?, ?, ?, ?, ?, NOW())";

        $stmt = mysqli_prepare($Connection, $query);

        if (!$stmt) {
            $message = "<div class='alert alert-danger m-4'>
                ❌ خطأ في قاعدة البيانات: " . mysqli_error($Connection) . "
            </div>";
        } else {

            mysqli_stmt_bind_param($stmt, "isssi",
                $unit_id, $title, $description, $event_date, $current_user_id
            );

            if (mysqli_stmt_execute($stmt)) {
                $message = "<div class='alert alert-success m-4'>
                    ✅ تم إنشاء الحدث بنجاح!
                </div>";
            } else {
                $message = "<div class='alert alert-danger m-4'>
                    ❌ فشل إدراج الحدث: " . mysqli_error($Connection) . "
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
            <h2 class="text-right">إنشاء حدث جديد للوحدة رقم <?php echo $unit_id; ?></h2>
            <hr>

            <?php echo $message; ?>

            <form method="POST" action="" dir="rtl">

                <!-- Hidden unit_id -->
                <input type="hidden" name="unit_id" value="<?php echo $unit_id; ?>">

                <div class="form-group mb-3">
                    <label for="title">عنوان الحدث: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" required maxlength="255">
                </div>

                <div class="form-group mb-3">
                    <label for="description">وصف الحدث:</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>

                <div class="form-group mb-3">
                    <label for="event_date">تاريخ ووقت الحدث: <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control" id="event_date" name="event_date" required>
                </div>

                <button type="submit" class="btn btn-primary">إضافة الحدث</button>
            </form>
        </div>
    </div>
</div>

<?php include("Configuration/Footer.php"); ?>
