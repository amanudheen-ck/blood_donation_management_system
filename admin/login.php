<?php
require_once __DIR__ . '/includes/auth.php';

if (!empty($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim($_POST['email']);
    $stmt = mysqli_prepare($conn, "SELECT admin_id, name, password FROM admins WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($admin && password_verify($_POST['password'], $admin['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['name'];
        header("Location: index.php");
        exit();
    }
    $error = "Invalid email or password.";
}
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body style="background:#f8f9fa">
<div class="container bg-white p-4 rounded shadow" style="max-width:400px;margin-top:80px">
    <h3 class="text-danger text-center mb-3">Admin Login</h3>
    <?php if ($error) echo "<div class='alert alert-danger'>" . e($error) . "</div>"; ?>
    <form method="POST">
        <?php echo csrf_field(); ?>
        <input class="form-control mb-3" type="email" name="email" placeholder="Email" required>
        <input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
        <button class="btn btn-danger w-100">Login</button>
    </form>
    <div class="text-center mt-3"><a href="../index.php">Back to site</a></div>
</div></body></html>
