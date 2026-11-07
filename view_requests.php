<?php
// Connect to the database
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood Requests</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .table th {
            background-color: #dc3545;
            color: #fff;
        }
        .status-pending {
            color: #ffc107; /* yellow */
            font-weight: bold;
        }
        .status-approved {
            color: #28a745; /* green */
            font-weight: bold;
        }
        .status-rejected {
            color: #dc3545; /* red */
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Blood Requests</h2>
        <?php
        // Fetch all requests
        $sql = "SELECT * FROM requests ORDER BY date_requested DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo '<table class="table table-bordered">';
            echo '<thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Blood Type</th>
                        <th>Location</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Date Requested</th>
                    </tr>
                  </thead><tbody>';
            while ($row = mysqli_fetch_assoc($result)) {
                $statusClass = strtolower($row['status']);
                echo "<tr>
                        <td>{$row['request_id']}</td>
                        <td>{$row['requester_name']}</td>
                        <td>{$row['blood_type']}</td>
                        <td>{$row['location']}</td>
                        <td>{$row['phone']}</td>
                        <td class='status-{$statusClass}'>" . ucfirst($row['status']) . "</td>
                        <td>{$row['date_requested']}</td>
                      </tr>";
            }
            echo '</tbody></table>';
        } else {
            echo '<p class="text-center text-muted">No blood requests found.</p>';
        }
        ?>
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>
</body>
</html>
