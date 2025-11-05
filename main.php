<?php
/* version 1.0 — Display all units for the logged-in owner */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include("Configuration/Header.php");
include("Configuration/DBInfoReader.php"); // defines $Connection (mysqli)

$owner_id = $_SESSION['ID'] ?? null;
$units = [];

if (!$owner_id) {
    echo '<div class="alert alert-danger m-4 text-center">الرجاء تسجيل الدخول لعرض الوحدات.</div>';
    include("Configuration/Footer.php");
    exit;
}

$sql = "SELECT id, name, description, image_path 
        FROM units 
        WHERE owner_id = ?
        ORDER BY id DESC";

if ($stmt = $Connection->prepare($sql)) {
    $stmt->bind_param('i', $owner_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $units[] = $row;
    }
    $stmt->close();
} else {
    echo '<div class="alert alert-danger m-4 text-center">خطأ في جلب البيانات: ' . htmlspecialchars($Connection->error) . '</div>';
    include("Configuration/Footer.php");
    exit;
}
?>

<div class="container mt-5 mb-5">
    <h3 class="mb-4 text-end">الوحدات العقارية تحت إدارتك</h3>
    <div class="row">
        <?php if (empty($units)): ?>
            <div class="col-12 text-center">
                <div class="alert alert-info">لا توجد وحدات مسجلة حالياً.</div>
            </div>
        <?php else: ?>
            <?php foreach ($units as $unit): ?>
                <div class="col-md-4 mb-4">
                    <a href="unitview.php?id=<?= htmlspecialchars($unit['id']) ?>" class="text-decoration-none text-dark">
                        <div class="card shadow-sm h-100">
                            <?php
                            $image = (!empty($unit['image_path']) && file_exists($unit['image_path']))
                                ? $unit['image_path']
                                : 'images/bg-title-01.jpg'; // fallback image
                            ?>
                            <img class="card-img-top" src="<?= htmlspecialchars($image) ?>" alt="صورة الوحدة">
                            <div class="card-body">
                                <h4 class="card-title mb-2 text-end"><?= htmlspecialchars($unit['name']) ?></h4>
                                <p class="card-text text-muted text-end">
                                    <?= htmlspecialchars(mb_strimwidth($unit['description'], 0, 100, '...')) ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
	text-align: center;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}
.card-img-top {
    height: 200px;
    object-fit: cover;
}
.mb-4 {
    margin-bottom: 1.5rem !important;
    text-align: center;
}
.row{
direction: rtl;
}
</style>

<?php
include("Configuration/Footer.php");
?>
