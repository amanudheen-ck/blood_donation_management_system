<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['donor_id'])) {
    header("Location: login.php");
    exit();
}

$donor_id = $_SESSION['donor_id'];

// Fetch donor details
$result = mysqli_query($conn, "SELECT * FROM donors WHERE donor_id = $donor_id");
$donor = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $blood_type = $_POST['blood_type']; // Get new blood type

    // Update password only if it's not empty
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE donors 
                SET name='$name', phone='$phone', location='$location', blood_type='$blood_type', password='$password' 
                WHERE donor_id=$donor_id";
    } else {
        $sql = "UPDATE donors 
                SET name='$name', phone='$phone', location='$location', blood_type='$blood_type' 
                WHERE donor_id=$donor_id";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<div style='color:green; text-align:center;'>Profile updated successfully.</div>";
        // Refresh donor data
        $result = mysqli_query($conn, "SELECT * FROM donors WHERE donor_id = $donor_id");
        $donor = mysqli_fetch_assoc($result);
    } else {
        echo "<div style='color:red; text-align:center;'>Error updating profile: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>
<div class="container">
    <h3 class="text-center">Update Profile</h3>
    <form method="POST" action="">
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo $donor['name']; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Phone:</label>
            <input type="text" name="phone" value="<?php echo $donor['phone']; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Location:</label>
            <input type="text" name="location" value="<?php echo $donor['location']; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Blood Type:</label>
            <select name="blood_type" class="form-control" required>
                <option value="">Select Blood Type</option>
                <?php
                $blood_types = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                foreach ($blood_types as $type) {
                    $selected = ($donor['blood_type'] == $type) ? "selected" : "";
                    echo "<option value='$type' $selected>$type</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label>New Password:</label>
            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
        <a href="dashboard.php" class="btn btn-secondary mt-2 w-100">Back to Dashboard</a>
    </form>
</div>
</body>
</html>
