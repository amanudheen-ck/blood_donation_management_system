<?php
require_once __DIR__ . '/includes/auth.php';
require_admin();
$page_title = 'Requests';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = (int)$_POST['request_id'];
    $action = $_POST['action'] ?? '';
    if ($action === 'status' && in_array($_POST['status'], REQUEST_STATUSES, true)) {
        $stmt = mysqli_prepare($conn, "UPDATE requests SET status = ? WHERE request_id = ?");
        mysqli_stmt_bind_param($stmt, "si", $_POST['status'], $id);
        mysqli_stmt_execute($stmt);
        flash("Request #$id marked " . $_POST['status'] . ".");
    } elseif ($action === 'delete') {
        try {
            mysqli_begin_transaction($conn);
            // `donations` has a foreign key to requests, so remove its rows first.
            $stmt = mysqli_prepare($conn, "DELETE FROM donations WHERE request_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $stmt = mysqli_prepare($conn, "DELETE FROM requests WHERE request_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_commit($conn);
            flash("Request #$id deleted.");
        } catch (Throwable $ex) {
            mysqli_rollback($conn);
            flash("Could not delete request: " . $ex->getMessage(), 'danger');
        }
    }
    header("Location: requests.php" . (!empty($_GET['status']) ? '?status=' . urlencode($_GET['status']) : ''));
    exit();
}

$filter = $_GET['status'] ?? '';
if (in_array($filter, REQUEST_STATUSES, true)) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM requests WHERE status = ? ORDER BY date_requested DESC");
    mysqli_stmt_bind_param($stmt, "s", $filter);
} else {
    $stmt = mysqli_prepare($conn, "SELECT * FROM requests ORDER BY date_requested DESC");
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

include 'includes/header.php';
?>
<h2 class="mb-3">Blood Requests</h2>
<div class="mb-3">
    <a class="btn btn-sm <?php echo $filter === '' ? 'btn-danger' : 'btn-outline-danger'; ?>" href="requests.php">All</a>
    <?php foreach (REQUEST_STATUSES as $s): ?>
        <a class="btn btn-sm <?php echo $filter === $s ? 'btn-danger' : 'btn-outline-danger'; ?>" href="requests.php?status=<?php echo $s; ?>"><?php echo $s; ?></a>
    <?php endforeach; ?>
</div>
<div class="table-responsive">
<table class="table table-bordered table-striped bg-white">
    <thead><tr><th>ID</th><th>Name</th><th>Phone</th><th>Blood</th><th>Location</th><th>Status</th><th>Date</th><th width="260">Actions</th></tr></thead>
    <tbody>
    <?php while ($r = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo (int)$r['request_id']; ?></td>
            <td><?php echo e($r['requester_name']); ?></td>
            <td><?php echo e($r['phone']); ?></td>
            <td><?php echo e($r['blood_type']); ?></td>
            <td><?php echo e($r['location']); ?></td>
            <td><?php echo e($r['status']); ?></td>
            <td><?php echo e($r['date_requested']); ?></td>
            <td>
                <form method="POST" class="d-flex gap-1">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="request_id" value="<?php echo (int)$r['request_id']; ?>">
                    <select name="status" class="form-select form-select-sm">
                        <?php foreach (REQUEST_STATUSES as $s) echo "<option " . (strcasecmp($r['status'], $s) === 0 ? 'selected' : '') . ">$s</option>"; ?>
                    </select>
                    <button name="action" value="status" class="btn btn-sm btn-primary">Update</button>
                    <button name="action" value="delete" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this request and any linked donation records?');">Del</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</div>
<?php include 'includes/footer.php'; ?>
