<?php
/* version 1.0 — Insert new unit (with owner_id from session) */

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include("Configuration/Header.php");
include("Configuration/DBInfoReader.php"); // defines $Connection (mysqli)

// ---------- Config ----------
$table = 'units';
$uploadDir = __DIR__ . '/uploads/units/';
// ----------------------------

// Create upload directory if it doesn't exist
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0755, true);
}

$alert = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $owner_id = $_SESSION['ID'] ?? null;

    if (!$owner_id) {
        $alert = ['type' => 'danger', 'text' => 'لم يتم التعرف على المستخدم. الرجاء تسجيل الدخول.'];
    } else {
        // Collect form data
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $total_members = intval($_POST['total_members'] ?? 0);
        $total_requests = intval($_POST['total_requests'] ?? 0);
        $pending_requests = intval($_POST['pending_requests'] ?? 0);

        $errors = [];

        if ($name === '') $errors[] = 'الرجاء إدخال اسم الوحدة.';
        if ($address === '') $errors[] = 'الرجاء إدخال عنوان الوحدة.';

        // ---- Image Upload ----
        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $file = $_FILES['image'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $newName = 'unit_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $dest = $uploadDir . $newName;
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $imagePath = 'uploads/units/' . $newName;
                } else {
                    $errors[] = 'تعذر حفظ الصورة. تحقق من صلاحيات المجلد.';
                }
            } else {
                $errors[] = 'حدث خطأ أثناء تحميل الصورة.';
            }
        }

        if (empty($errors)) {
            $sql = "INSERT INTO `$table` 
                (`name`, `description`, `address`, `owner_id`, `total_members`, `total_requests`, `pending_requests`, `created_at`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

            if ($stmt = $Connection->prepare($sql)) {
                $stmt->bind_param('sssiiii', $name, $description, $address, $owner_id, $total_members, $total_requests, $pending_requests);

                if ($stmt->execute()) {
                    $unit_id = $stmt->insert_id;

                    // Save image path if uploaded
                    if ($imagePath) {
                        $Connection->query("UPDATE `$table` SET image_path='" . $Connection->real_escape_string($imagePath) . "' WHERE id=$unit_id");
                    }

                    $alert = ['type' => 'success', 'text' => '✅ تم حفظ الوحدة بنجاح.'];
                } else {
                    $alert = ['type' => 'danger', 'text' => 'خطأ في الإدخال: ' . $stmt->error];
                }
                $stmt->close();
            } else {
                $alert = ['type' => 'danger', 'text' => 'فشل إعداد الاستعلام: ' . $Connection->error];
            }
        } else {
            $alert = ['type' => 'warning', 'text' => implode('<br>', $errors)];
        }
    }
}
?>

<section class="au-breadcrumb m-t-75">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <span class="au-breadcrumb-span">إضافة وحدة جديدة</span>
        </div>
    </div>
</section>

<div class="card">
    <div class="card-header">
        <strong>إضافة وحدة عقارية جديدة</strong>
    </div>
    <div class="card-body card-body">

        <?php if ($alert): ?>
        <div class="alert alert-<?php echo htmlspecialchars($alert['type']); ?>">
            <?php echo $alert['text']; ?>
        </div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" style="direction: rtl;">
            
            <!-- اسم الوحدة -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-control-label">اسم الوحدة</label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="name" placeholder="أدخل اسم الوحدة" class="form-control" required>
                </div>
            </div>

            <!-- الوصف -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-control-label">الوصف</label>
                </div>
                <div class="col-md-9">
                    <textarea name="description" placeholder="أدخل وصف الوحدة" class="form-control" rows="4"></textarea>
                </div>
            </div>

            <!-- العنوان -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-control-label">العنوان</label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="address" placeholder="أدخل العنوان الكامل" class="form-control" required>
                </div>
            </div>

            <!-- صورة الوحدة -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-control-label">صورة الوحدة</label>
                </div>
                <div class="col-md-9">
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
            </div>

            <!-- عدد الأعضاء -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-control-label">عدد الملاك / الأعضاء</label>
                </div>
                <div class="col-md-9">
                    <input type="number" name="total_members" placeholder="عدد الملاك" class="form-control" min="0">
                </div>
            </div>

            <!-- عدد الطلبات -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-control-label">عدد الطلبات الكلي</label>
                </div>
                <div class="col-md-9">
                    <input type="number" name="total_requests" placeholder="عدد الطلبات" class="form-control" min="0">
                </div>
            </div>

            <!-- الطلبات المعلقة -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-control-label">الطلبات المعلقة</label>
                </div>
                <div class="col-md-9">
                    <input type="number" name="pending_requests" placeholder="الطلبات المعلقة" class="form-control" min="0">
                </div>
            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-save"></i> حفظ
                </button>
                <button type="reset" class="btn btn-danger btn-sm">
                    <i class="fa fa-ban"></i> إعادة تعيين
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.card { margin: 20px; direction: rtl; }
.form-control-label { font-weight: bold; }
</style>

<?php include("Configuration/Footer.php"); ?>
