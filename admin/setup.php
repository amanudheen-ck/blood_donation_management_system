<?php
// ONE-TIME SETUP: creates the `admins` table and the first admin account.
// It locks itself once an admin exists. DELETE THIS FILE after use.
require_once __DIR__ . '/includes/auth.php';

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$count = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM admins"))[0];
$msg = '';

if ($count > 0) {
    $msg = "<div class='alert alert-warning'>Setup is already complete. Delete <code>admin/setup.php</code> and <a href='login.php'>log in</a>.</div>";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 8) {
        $msg = "<div class='alert alert-danger'>Enter a name, a valid email and a password of at least 8 characters.</div>";
    } else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hash);
        mysqli_stmt_execute($stmt);
        $count = 1;
        $msg = "<div class='alert alert-success'>Admin created. Now <strong>delete admin/setup.php</strong> and <a href='login.php'>log in</a>.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Admin Setup</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body style="background:#f8f9fa">
<div class="container bg-white p-4 rounded shadow-sm" style="max-width:420px;margin-top:70px">
    <h3 class="text-danger text-center mb-3">Admin Setup</h3>
    <?php echo $msg; ?>
    <?php if ($count == 0): ?>
    <form method="POST">
        <?php echo csrf_field(); ?>
        <input class="form-control mb-3" name="name" placeholder="Name" required>
        <input class="form-control mb-3" type="email" name="email" placeholder="Email" required>
        <input class="form-control mb-3" type="password" name="password" placeholder="Password (min 8 chars)" required>
        <button class="btn btn-danger w-100">Create Admin</button>
    </form>
    <?php endif; ?>
</div></body></html>
