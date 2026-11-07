<?php
require_once __DIR__ . '/includes/auth.php';
require_admin();
$page_title = 'Dashboard';

function count_of($conn, $sql) {
    $r = mysqli_query($conn, $sql);
    return $r ? (int)mysqli_fetch_row($r)[0] : 0;
}
$donors   = count_of($conn, "SELECT COUNT(*) FROM donors");
$requests = count_of($conn, "SELECT COUNT(*) FROM requests");
$pending  = count_of($conn, "SELECT COUNT(*) FROM requests WHERE status = 'Pending'");
$camps    = count_of($conn, "SELECT COUNT(*) FROM camps");

$by_type = mysqli_query($conn, "SELECT blood_type, COUNT(*) AS c FROM donors GROUP BY blood_type ORDER BY blood_type");
$recent  = mysqli_query($conn, "SELECT * FROM requests ORDER BY date_requested DESC LIMIT 5");

include 'includes/header.php';
?>
<h2 class="mb-4">Dashboard</h2>
<div class="row g-3 mb-4">
    <?php
    $cards = [['Donors', $donors, 'donors.php'], ['Total Requests', $requests, 'requests.php'],
              ['Pending Requests', $pending, 'requests.php?status=Pending'], ['Camps', $camps, 'camps.php']];
    foreach ($cards as [$label, $n, $href]) {
        echo "<div class='col-md-3'><a href='$href' class='text-decoration-none text-dark'>
              <div class='card stat-card text-center p-3'><h2>$n</h2><div>$label</div></div></a></div>";
    }
    ?>
</div>
<div class="row g-4">
    <div class="col-md-4">
        <h5>Donors by Blood Type</h5>
        <table class="table table-bordered bg-white">
            <thead><tr><th>Type</th><th>Donors</th></tr></thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($by_type)): ?>
                <tr><td><?php echo e($row['blood_type']); ?></td><td><?php echo (int)$row['c']; ?></td></tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-8">
        <h5>Recent Requests</h5>
        <table class="table table-bordered bg-white">
            <thead><tr><th>Name</th><th>Blood</th><th>Location</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
            <?php while ($r = mysqli_fetch_assoc($recent)): ?>
                <tr><td><?php echo e($r['requester_name']); ?></td><td><?php echo e($r['blood_type']); ?></td>
                    <td><?php echo e($r['location']); ?></td><td><?php echo e($r['status']); ?></td>
                    <td><?php echo e($r['date_requested']); ?></td></tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
