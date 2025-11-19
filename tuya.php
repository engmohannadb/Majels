<?php
// Process form after popup submit
$tuya_id = "";
$tuya_secret = "";
$has_credentials = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tuya_id = trim($_POST["tuya_id"] ?? "");
    $tuya_secret = trim($_POST["tuya_secret"] ?? "");

    if ($tuya_id !== "" && $tuya_secret !== "") {
        $has_credentials = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tuya Integration</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body class="bg-light">

<div class="container py-5">

    <h1 class="text-center mb-4">Tuya Integration</h1>

    <?php if ($has_credentials): ?>
        <div class="alert alert-success">
            ✅ Tuya credentials received successfully!
        </div>

        <div class="card p-3 shadow-sm">
            <h5>🔐 Your Tuya Credentials</h5>
            <p><strong>Access ID:</strong> <?= htmlspecialchars($tuya_id) ?></p>
            <p><strong>Access Secret:</strong> <?= htmlspecialchars($tuya_secret) ?></p>

            <hr>
            <p>Now you can continue building your API calls to Tuya from here...</p>
        </div>
    <?php endif; ?>

</div>

<!-- Modal -->
<div class="modal fade <?= $has_credentials ? '' : 'show' ?>" id="tuyaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" 
     style="<?= $has_credentials ? '' : 'display:block;' ?>">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">Enter Tuya API Credentials</h5>
            </div>
            <form method="POST">
            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Access ID</label>
                    <input type="text" name="tuya_id" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Access Secret</label>
                    <input type="text" name="tuya_secret" class="form-control" required>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Continue</button>
            </div>
            </form>
        </div>
    </div>
</div>

<?php if (!$has_credentials): ?>
<script>
// Auto-open modal when page loads
var myModal = new bootstrap.Modal(document.getElementById('tuyaModal'));
myModal.show();
</script>
<?php endif; ?>

</body>
</html>
