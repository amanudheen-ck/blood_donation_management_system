<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name       = $_POST['name'];
    $email      = $_POST['email'];
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone      = $_POST['phone'];
    $blood_type = $_POST['blood_type'];
    $location   = $_POST['location'];

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM donors WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<div style='color:red; text-align:center; margin-top:10px;'>
                This email is already registered. Try logging in instead.
              </div>";
    } else {
        $sql = "INSERT INTO donors (name, email, password, phone, blood_type, location) 
                VALUES ('$name', '$email', '$password', '$phone', '$blood_type', '$location')";
        if (mysqli_query($conn, $sql)) {
            echo "<div style='color:green; text-align:center; margin-top:10px;'>
                    Registration successful.
                  </div>";
            // Show the two buttons after success
            echo "
            <div style='text-align:center; margin-top:15px;'>
                <button onclick=\"window.location.href='index.php'\" class='btn btn-primary m-2'>Go to Home Page</button>
                <button onclick=\"window.location.href='login.php'\" class='btn btn-success m-2'>Go to Login Page</button>
            </div>
            ";
        } else {
            echo "<div style='color:red; text-align:center; margin-top:10px;'>
                    Error: " . mysqli_error($conn) . "
                  </div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donor Registration</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        button {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h3 class="text-center mb-4">Donor Registration</h3>
    <form method="POST" action="register.php">
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
        </div>
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="mb-3">
            <input type="text" name="phone" class="form-control" placeholder="Phone" required>
        </div>
        <div class="mb-3">
            <select name="blood_type" class="form-select" required>
                <option value="">Select Blood Type</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
            </select>
        </div>
        <div class="mb-3">
            <input type="text" name="location" class="form-control" placeholder="Location" required>
        </div>
        <button type="submit" class="btn btn-danger">Register</button>
    </form>
</div>

<!-- Bootstrap JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
