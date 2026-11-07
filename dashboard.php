<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['donor_id'])) {
    header("Location: login.php");
    exit();
}

// Get donor details
$donor_id = $_SESSION['donor_id'];
$result = mysqli_query($conn, "SELECT * FROM donors WHERE donor_id = $donor_id");
$donor = mysqli_fetch_assoc($result);

// Get matching requests based on donor's blood type and location
$blood_type = $donor['blood_type'];
$location = $donor['location'];

$matching_requests = mysqli_query($conn, 
    "SELECT * FROM requests 
     WHERE blood_type = '$blood_type' 
     AND location = '$location' 
     AND status = 'Pending'"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .header {
            background-color: #dc3545;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .logout-btn {
            float: right;
            color: white;
            background: #343a40;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
        }
        .logout-btn:hover {
            background: #495057;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Welcome, <?php echo $donor['name']; ?>!</h1>
    <a href="index.php" class="logout-btn">Logout</a>
</div>

<div class="container mt-4">
    <h3>Your Profile</h3>
    <table class="table table-bordered">
        <tr><th>Name</th><td><?php echo $donor['name']; ?></td></tr>
        <tr><th>Email</th><td><?php echo $donor['email']; ?></td></tr>
        <tr><th>Phone</th><td><?php echo $donor['phone']; ?></td></tr>
        <tr><th>Blood Type</th><td><?php echo $donor['blood_type']; ?></td></tr>
        <tr><th>Location</th><td><?php echo $donor['location']; ?></td></tr>
        <tr><th>Last Donation</th><td><?php echo $donor['last_donation'] ? $donor['last_donation'] : 'N/A'; ?></td></tr>
    </table>
    <a href="update_profile.php" class="btn btn-primary">Update Profile</a>
</div>

<div class="container mt-5">
    <h3>Matching Blood Requests</h3>
    <?php if (mysqli_num_rows($matching_requests) > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Requester Name</th>
                    <th>Blood Type</th>
                    <th>Location</th>
                    <th>Date Requested</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($request = mysqli_fetch_assoc($matching_requests)): ?>
                    <tr>
                        <td><?php echo $request['requester_name']; ?></td>
                        <td><?php echo $request['blood_type']; ?></td>
                        <td><?php echo $request['location']; ?></td>
                        <td><?php echo $request['date_requested']; ?></td>
                        <td><?php echo $request['status']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No matching requests found in your area right now.</p>
    <?php endif; ?>
</div>

</body>
</html>
