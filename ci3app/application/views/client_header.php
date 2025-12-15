<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        body { padding-top: 20px; }
        .navbar-brand { font-weight: bold; }
        .modal[aria-hidden="true"] { visibility: hidden; }
        .modal[aria-hidden="false"] { visibility: visible; }
    </style>

    <script>
    <?php if (isset($_SESSION['toast_message'])): ?>
        toastr.options = {
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };
        toastr["<?= $_SESSION['toast_type'] ?>"]("<?= $_SESSION['toast_message'] ?>");
        <?php unset($_SESSION['toast_message'], $_SESSION['toast_type']); ?>
    <?php endif; ?>
    </script>
    </head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a id="navbarTitle" class="navbar-brand" href="#">CLIENT MANAGEMENT</a>
        <button class="btn btn-outline-primary ml-auto" id="toggleViewBtn" onclick="toggleView()">
            <?php 
            $current_url = uri_string();
            if (strpos($current_url, 'client_contact') !== false): ?>
                Client Details
            <?php else: ?>
                Client Contact Details
            <?php endif; ?>
        </button>
    </nav>

    <div class="container mt-4">

<script>
function toggleView() {
    const currentUrl = window.location.href;
    if (currentUrl.includes('client_contact')) {
        window.location.href = '<?php echo base_url('client'); ?>';
    } else {
        window.location.href = '<?php echo base_url('client_contact'); ?>';
    }
}
</script>