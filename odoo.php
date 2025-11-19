<?php
// PHP Backend Logic
// These variables will simulate reading credentials from a configuration file or database.
$odoo_id = "";
$odoo_secret = "";
$has_credentials = false;

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $odoo_id = trim($_POST["odoo_id"] ?? "");
    $odoo_secret = trim($_POST["odoo_secret"] ?? "");

    // Check if both fields were provided
    if ($odoo_id !== "" && $odoo_secret !== "") {
        $has_credentials = true;
        // In a real Odoo module, you would save these values to a configuration record here
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odoo Proptech Integration</title>

    <!-- Bootstrap 5 CDN for styling and modal functionality -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* Custom styling to ensure the page looks clean and responsive */
        body {
            background-color: #f8f9fa; /* Light gray background */
            font-family: 'Inter', sans-serif;
        }
        .container {
            max-width: 800px;
        }
    </style>
</head>
<body>

<div class="container py-5">

    <h1 class="text-center mb-5 text-dark">Odoo Proptech Integration</h1>

    <?php if ($has_credentials): ?>
        <!-- SUCCESS STATE -->
        <div class="alert alert-success border-0 shadow-sm rounded-3">
            <h4 class="alert-heading">✅ Credentials Received Successfully!</h4>
            <p>The Odoo module is now configured to securely communicate with your Proptech platform.</p>
        </div>

        <div class="card p-4 shadow-lg border-0 rounded-4 mt-4">
            <h5 class="card-title text-primary mb-3">🔐 Your Saved Credentials</h5>
            <div class="mb-3 p-3 bg-light rounded-3 border">
                <p class="mb-1 text-muted small">Access ID:</p>
                <code class="d-block fw-bold text-break"><?= htmlspecialchars($odoo_id) ?></code>
            </div>
            <div class="mb-3 p-3 bg-light rounded-3 border">
                <p class="mb-1 text-muted small">Access Secret:</p>
                <code class="d-block fw-bold text-break"><?= htmlspecialchars($odoo_secret) ?></code>
            </div>

            <hr>
            <p class="text-secondary small">These values are now available to the Odoo backend for making authenticated API calls.</p>
            
            <button type="button" class="btn btn-outline-secondary btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#odooModal">
                Re-enter Credentials
            </button>
        </div>

    <?php else: ?>
        <!-- REQUIRED CREDENTIALS STATE -->
        <div class="alert alert-warning border-0 shadow-sm rounded-3">
            <h4 class="alert-heading">⚠️ Configuration Required</h4>
            <p class="mb-0">Please enter your Odoo Proptech API credentials to activate the integration.</p>
        </div>
    <?php endif; ?>

</div>

<!-- Odoo Credentials Modal -->
<div class="modal fade" id="odooModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="odooModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-xl rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title" id="odooModalLabel">Enter Odoo Proptech API Credentials</h5>
                <!-- Note: The close button is removed because data-bs-backdrop="static" prevents closing without submission -->
            </div>
            <form method="POST">
            <div class="modal-body p-4">

                <div class="mb-3">
                    <label for="odoo-id" class="form-label fw-semibold">Access ID</label>
                    <input type="text" id="odoo-id" name="odoo_id" class="form-control form-control-lg rounded-3" required 
                           placeholder="e.g., sk_live_xxxxxxxxxxxx">
                </div>

                <div class="mb-3">
                    <label for="odoo-secret" class="form-label fw-semibold">Access Secret</label>
                    <input type="text" id="odoo-secret" name="odoo_secret" class="form-control form-control-lg rounded-3" required
                           placeholder="e.g., e7c6d5b4a3f2e1d0c9b8a7f6e5d4c3b2">
                </div>

                <p class="text-danger small mt-3">
                    * These credentials are required for the module to function.
                </p>

            </div>
            <div class="modal-footer d-flex justify-content-end border-top-0 p-4">
                <button type="submit" class="btn btn-primary btn-lg px-4 rounded-3 shadow">
                    Save and Continue
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

<?php if (!$has_credentials): ?>
<script>
    // JavaScript to automatically show the modal on page load if credentials are missing.
    document.addEventListener('DOMContentLoaded', function() {
        var odooModalElement = document.getElementById('odooModal');
        // Ensure modal only shows if the form hasn't been submitted successfully
        if (odooModalElement && <?php echo $has_credentials ? 'false' : 'true'; ?>) {
            var myModal = new bootstrap.Modal(odooModalElement);
            myModal.show();
        }
    });
</script>
<?php endif; ?>

</body>
</html>