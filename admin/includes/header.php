<?php $current = basename($_SERVER['PHP_SELF']); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($page_title ?? 'Admin'); ?> - Blood Donation Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .table th { background-color: #dc3545; color: #fff; }
        .stat-card h2 { color: #dc3545; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-danger">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav me-auto">
                <?php
                $links = ['index.php' => 'Dashboard', 'donors.php' => 'Donors', 'requests.php' => 'Requests', 'camps.php' => 'Camps'];
                foreach ($links as $file => $label) {
                    $active = ($current === $file) ? 'active fw-bold' : '';
                    echo "<li class='nav-item'><a class='nav-link $active' href='$file'>$label</a></li>";
                }
                ?>
            </ul>
            <span class="navbar-text me-3">Hi, <?php echo e($_SESSION['admin_name'] ?? 'Admin'); ?></span>
            <a href="../index.php" class="btn btn-sm btn-outline-light me-2">View Site</a>
            <a href="logout.php" class="btn btn-sm btn-dark">Logout</a>
        </div>
    </div>
</nav>
<div class="container my-4">
<?php echo flash(); ?>
