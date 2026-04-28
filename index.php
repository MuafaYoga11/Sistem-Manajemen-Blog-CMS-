<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Manajemen Blog (CMS) — Dashboard admin untuk mengelola penulis, artikel, dan kategori.">
    <title>Sistem Manajemen Blog (CMS)</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-wrapper">

    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main" id="appMain">
            <?php include 'includes/sections.php'; ?>
        </main>
    </div>

</div>

<!-- Modals -->
<?php include 'includes/modals.php'; ?>

<!-- Toast Notifications -->
<div class="toast-container" id="toastContainer"></div>

<!-- Scripts -->
<script src="assets/js/script.js"></script>

</body>
</html>
