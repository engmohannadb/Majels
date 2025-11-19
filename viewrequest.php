<?php
include("Configuration/Header.php");
include("Configuration/DBInfoReader.php");

$current_user_id = 1; // TODO: استبداله بمعرف المستخدم من الجلسة

// Get request ID
$request_id = isset($_GET['rid']) ? intval($_GET['rid']) : 0;

if ($request_id <= 0) {
    echo "<div class='alert alert-danger m-4'>⚠️ رقم الطلب غير صحيح.</div>";
    include("Configuration/Footer.php");
    exit;
}

// Handle POST for new comment or status change
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['new_comment'])) {
        $comment_text = trim($_POST['comment_text'] ?? '');
        if (!empty($comment_text)) {
            $insert_comment = "INSERT INTO request_comments (request_id, user_id, comment_text) VALUES (?, ?, ?)";
            $stmt_c = mysqli_prepare($Connection, $insert_comment);
            mysqli_stmt_bind_param($stmt_c, "iis", $request_id, $current_user_id, $comment_text);
            if (mysqli_stmt_execute($stmt_c)) {
                $message = "<div class='alert alert-success'>✅ تم إضافة التعليق بنجاح.</div>";
            } else {
                $message = "<div class='alert alert-danger'>❌ فشل إضافة التعليق: " . mysqli_error($Connection) . "</div>";
            }
            mysqli_stmt_close($stmt_c);
        } else {
            $message = "<div class='alert alert-warning'>⚠️ التعليق فارغ.</div>";
        }
    }

    if (isset($_POST['change_status'])) {
        $new_status = $_POST['status'] ?? '';
        $allowed_status = ['open','pending','in review','in progress','hold','done','canceled'];
        if (in_array($new_status, $allowed_status)) {
            $update_status = "UPDATE requests SET status=?, last_update=NOW() WHERE id=?";
            $stmt_s = mysqli_prepare($Connection, $update_status);
            mysqli_stmt_bind_param($stmt_s, "si", $new_status, $request_id);
            if (mysqli_stmt_execute($stmt_s)) {
                $message = "<div class='alert alert-success'>✅ تم تغيير حالة الطلب.</div>";
            } else {
                $message = "<div class='alert alert-danger'>❌ فشل تغيير الحالة: " . mysqli_error($Connection) . "</div>";
            }
            mysqli_stmt_close($stmt_s);
        }
    }
}

// Fetch request details
$query = "SELECT r.*, u.name AS user_name
          FROM requests r
          LEFT JOIN users u ON r.user_id = u.id
          WHERE r.id = ?";
$stmt = mysqli_prepare($Connection, $query);
mysqli_stmt_bind_param($stmt, "i", $request_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-warning m-4'>⚠️ لم يتم العثور على الطلب.</div>";
    include("Configuration/Footer.php");
    exit;
}
$request = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Map status text
function status_label($st) {
    switch ($st) {
        case "open": return ["مفتوح", "primary"];
        case "pending": return ["قيد الانتظار", "warning"];
        case "in review": return ["قيد المراجعة", "info"];
        case "in progress": return ["قيد التنفيذ", "success"];
        case "hold": return ["معلق", "secondary"];
        case "done": return ["مكتمل", "success"];
        case "canceled": return ["ملغي", "danger"];
        default: return [$st, "dark"];
    }
}
list($status_text, $status_color) = status_label($request["status"]);
?>

<div class="container mt-5" dir="rtl" style="text-align:right;">
    <?php echo $message; ?>

    <div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="m-0">📄 تفاصيل طلب الصيانة</h3>
        </div>
        <div class="card-body">
            <h4 class="mb-3"><?php echo htmlspecialchars($request["request_title"]); ?></h4>
            <p class="text-muted mb-1"><strong>الوحدة رقم:</strong> <?php echo $request["unit_id"]; ?></p>
            <p class="text-muted mb-1"><strong>المستخدم:</strong> <?php echo $request["user_name"] ?: "غير متوفر"; ?></p>
            <p class="text-muted mb-1"><strong>تاريخ الطلب:</strong> <?php echo $request["request_date"]; ?></p>
            <p class="text-muted mb-3"><strong>آخر تحديث:</strong> <?php echo $request["last_update"] ?: "—"; ?></p>
            <span class="badge bg-<?php echo $status_color; ?>" style="font-size:16px;padding:8px 12px;">
                الحالة: <?php echo $status_text; ?>
            </span>

            <hr>
            <h5>📌 تفاصيل الطلب:</h5>
            <p style="white-space:pre-line;"><?php echo nl2br(htmlspecialchars($request["request_text"])); ?></p>
        </div>
    </div>

    <!-- Form to change status -->
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="m-0">🔄 تغيير حالة الطلب</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="" dir="rtl">
                <div class="form-group mb-3">
                    <label for="status">اختر الحالة الجديدة:</label>
                    <select class="form-control" name="status" id="status" required>
                        <?php
                        $statuses = ['open'=>'مفتوح','pending'=>'قيد الانتظار','in review'=>'قيد المراجعة','in progress'=>'قيد التنفيذ','hold'=>'معلق','done'=>'مكتمل','canceled'=>'ملغي'];
                        foreach($statuses as $key=>$val){
                            $sel = ($request['status']==$key) ? "selected" : "";
                            echo "<option value='$key' $sel>$val</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="change_status" class="btn btn-primary">تغيير الحالة</button>
            </form>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="m-0">💬 التعليقات</h5>
        </div>
        <div class="card-body">

            <?php
            $comments_sql = "SELECT c.*, u.name AS user_name FROM request_comments c 
                             LEFT JOIN users u ON c.user_id=u.id 
                             WHERE c.request_id=? ORDER BY c.created_at ASC";
            $stmt_c = mysqli_prepare($Connection, $comments_sql);
            mysqli_stmt_bind_param($stmt_c, "i", $request_id);
            mysqli_stmt_execute($stmt_c);
            $comments_result = mysqli_stmt_get_result($stmt_c);

            if(mysqli_num_rows($comments_result)>0){
                while($comment = mysqli_fetch_assoc($comments_result)){
                    echo '<div class="mb-3 p-2 border rounded">';
                    echo '<strong>'.htmlspecialchars($comment["user_name"] ?: "غير معروف").'</strong> <small class="text-muted">('.$comment["created_at"].')</small>';
                    echo '<p class="mb-0">'.nl2br(htmlspecialchars($comment["comment_text"])).'</p>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-muted">لا توجد تعليقات بعد.</p>';
            }
            mysqli_stmt_close($stmt_c);
            ?>

            <hr>
            <!-- Add new comment -->
            <form method="POST" action="" dir="rtl">
                <div class="form-group mb-3">
                    <label for="comment_text">إضافة تعليق جديد:</label>
                    <textarea name="comment_text" id="comment_text" rows="3" class="form-control" required></textarea>
                </div>
                <button type="submit" name="new_comment" class="btn btn-success">إرسال التعليق</button>
            </form>

        </div>
    </div>

    <div class="text-left mb-5">
        <a href="unitview.php?id=<?php echo $request["unit_id"]; ?>" class="btn btn-secondary">العودة للوحدة</a>
    </div>
</div>

<?php include("Configuration/Footer.php"); ?>
