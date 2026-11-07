<?php
// Start the session if not already started
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Blood Donation Management System</title>
    <!-- Bootstrap CDN for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .hero {
            background-color: #dc3545;
            color: white;
            padding: 60px 20px;
            text-align: center;
        }
        .hero h1 {
            font-size: 3rem;
        }
        .card:hover {
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            transform: scale(1.03);
            transition: 0.3s ease-in-out;
        }
        .features .card {
            height: 100%;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Blood Donation System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="register.php"></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="camps.php"></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="request_blood.php"></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Donate Blood, Save Lives</h1>
        <p>Join our community of heroes. Register as a donor today!</p>
        <a href="register.php" class="btn btn-light btn-lg mt-3">Register Now</a>
    </div>

    <!-- Features Section -->
    <div class="container my-5">
        <div class="row text-center g-4 features">
            <div class="col-md-3">
                <a href="request_blood.php" class="text-decoration-none text-dark">
                    <div class="card p-3">
                        <h4>Ready To Donate</h4>
                        <p>Submit blood requests easily.</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="find_donors.php" class="text-decoration-none text-dark">
                    <div class="card p-3">
                        <h4>Find Donors</h4>
                        <p>Search for matching donors by location and blood type.</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="view_requests.php" class="text-decoration-none text-dark">
                    <div class="card p-3">
                        <h4>View Requests</h4>
                        <p>View all active blood requests and contact them.</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="camps.php" class="text-decoration-none text-dark">
                    <div class="card p-3">
                        <h4>Donation Camps</h4>
                        <p>View upcoming blood donation camps near you.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-danger text-white text-center py-3">
        &copy; <?php echo date("Y"); ?> Online Blood Donation Management System
        | <a href="admin/login.php" class="text-white">Admin</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
