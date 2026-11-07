<?php
require_once __DIR__ . '/includes/auth.php';
require_admin();
$page_title = 'Donors';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    verify_csrf();
    $id = (int)$_POST['donor_id'];
    try {
        mysqli_begin_transaction($conn);
        // `donations` has a foreign key to donors, so remove its rows first.
        $stmt = mysqli_prepare($conn, "DELETE FROM donations WHERE donor_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $stmt = mysqli_prepare($conn, "DELETE FROM donors WHERE donor_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_commit($conn);
        flash("Donor deleted.");
    } catch (Throwable $ex) {
        mysqli_rollback($conn);
        flash("Could not delete donor: " . $ex->getMessage(), 'danger');
    }
    header("Location: donors.php");
    exit();
}

$q = trim($_GET['q'] ?? '');
$bt = $_GET['blood_type'] ?? '';
$sql = "SELECT * FROM donors WHERE 1=1";
$types = ''; $params = [];
if ($q !== '') {
    $sql .= " AND (name LIKE ? OR email LIKE ? OR location LIKE ? OR phone LIKE ?)";
    $like = "%$q%";
    array_push($params, $like, $like, $like, $like);
    $types .= 'ssss';
}
if (in_array($bt, BLOOD_TYPES, true)) {
    $sql .= " AND blood_type = ?";
    $params[] = $bt; $types .= 's';
}
$sql .= " ORDER BY donor_id DESC";
$stmt = mysqli_prepare($conn, $sql);
if ($params) mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

include 'includes/header.php';
?>
<h2 class="mb-3">Manage Donors</h2>
<form class="row g-2 mb-3" method="GET">
    <div class="col-md-5"><input class="form-control" name="q" value="<?php echo e($q); ?>" placeholder="Search name, email, phone, location"></div>
    <div class="col-md-3">
        <select class="form-select" name="blood_type">
            <option value="">All blood types</option>
            <?php foreach (BLOOD_TYPES as $t) echo "<option " . ($bt === $t ? 'selected' : '') . ">" . e($t) . "</option>"; ?>
        </select>
    </div>
    <div class="col-md-2"><button class="btn btn-danger w-100">Filter</button></div>
</form>
<div class="table-responsive">
<table class="table table-bordered table-striped bg-white">
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Blood</th><th>Location</th><th>Last Donation</th><th width="150">Actions</th></tr></thead>
    <tbody>
    <?php while ($d = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo (int)$d['donor_id']; ?></td>
            <td><?php echo e($d['name']); ?></td>
            <td><?php echo e($d['email']); ?></td>
            <td><?php echo e($d['phone']); ?></td>
            <td><?php echo e($d['blood_type']); ?></td>
            <td><?php echo e($d['location']); ?></td>
            <td><?php echo e($d['last_donation'] ?: 'N/A'); ?></td>
            <td>
                <a class="btn btn-sm btn-primary" href="donor_edit.php?id=<?php echo (int)$d['donor_id']; ?>">Edit</a>
                <form method="POST" class="d-inline" onsubmit="return confirm('Delete this donor and their donation records?');">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="donor_id" value="<?php echo (int)$d['donor_id']; ?>">
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</div>
<?php include 'includes/footer.php'; ?>
