<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

// Initialize search variables
$blood_type = "";
$location = "";
$donors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $blood_type = mysqli_real_escape_string($conn, $_POST['blood_type']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    $sql = "SELECT * FROM donors 
            WHERE blood_type='$blood_type' AND location LIKE '%$location%'";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        $donors = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find Donors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .card {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center mb-4">Search for Blood Donors</h2>

    <!-- Search Form -->
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="blood_type" class="form-label">Blood Type</label>
            <select name="blood_type" id="blood_type" class="form-select" required>
                <option value="">Select</option>
                <?php
                $blood_types = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                foreach ($blood_types as $type) {
                    $selected = ($blood_type == $type) ? "selected" : "";
                    echo "<option value='$type' $selected>$type</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" id="location" class="form-control" value="<?php echo htmlspecialchars($location); ?>" placeholder="Enter city or area" required>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-danger w-100">Search</button>
        </div>
    </form>

    <!-- Donors List -->
    <?php if (!empty($donors)): ?>
        <h4 class="mb-3">Matching Donors</h4>
        <?php foreach ($donors as $donor): ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($donor['name']); ?></h5>
                    <p class="card-text">
                        <strong>Blood Type:</strong> <?php echo htmlspecialchars($donor['blood_type']); ?><br>
                        <strong>Location:</strong> <?php echo htmlspecialchars($donor['location']); ?><br>
                        <strong>Phone:</strong> <?php echo htmlspecialchars($donor['phone']); ?><br>
                        <strong>Email:</strong> <?php echo htmlspecialchars($donor['email']); ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        <p class="text-center text-muted">No donors found for the selected blood type and location.</p>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary">Back to Home</a>
    </div>
</div>
</body>
</html>
