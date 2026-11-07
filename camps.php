<?php
include 'db_connect.php';

// Fetch all camps ordered by upcoming date
$result = mysqli_query($conn, "SELECT * FROM camps ORDER BY date ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upcoming Blood Donation Camps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .camp-card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .camp-card h4 {
            color: #dc3545;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Upcoming Blood Donation Camps</h2>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($camp = mysqli_fetch_assoc($result)): ?>
            <div class="camp-card">
                <h4><?php echo htmlspecialchars($camp['title']); ?></h4>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($camp['location']); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($camp['date']); ?></p>
                <p><?php echo htmlspecialchars($camp['description']); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center">No upcoming camps at the moment.</p>
    <?php endif; ?>
    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary">Back to Home</a>
    </div>
</div>
</body>
</html>
