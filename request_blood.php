<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name       = $_POST['name'];
    $blood_type = $_POST['blood_type'];
    $location   = $_POST['location'];
    $phone      = $_POST['phone']; // New phone field

    $sql = "INSERT INTO requests (requester_name, blood_type, location, phone) 
            VALUES ('$name', '$blood_type', '$location', '$phone')";
    if (mysqli_query($conn, $sql)) {
        echo "<div style='color:green; text-align:center;'>
                Request submitted successfully.<br>
                <a href='index.php'>Go to Homepage</a>
              </div>";
    } else {
        echo "<div style='color:red; text-align:center;'>
                Error: " . mysqli_error($conn) . "
              </div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood Request Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .request-container {
            max-width: 500px;
            margin: 50px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
        }
        .request-container h3 {
            color: #dc3545;
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-request {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="request-container">
    <h3>Request Blood</h3>
    <form method="POST" action="request_blood.php">
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Requester Name" required>
        </div>
        <div class="mb-3">
            <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
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
        <button type="submit" class="btn btn-danger btn-request">Submit Request</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
