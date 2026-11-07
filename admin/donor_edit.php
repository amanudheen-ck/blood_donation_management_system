<?php
require_once __DIR__ . '/includes/auth.php';
require_admin();
$page_title = 'Edit Donor';

$id = (int)($_GET['id'] ?? $_POST['donor_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $location = trim($_POST['location']);
    $bt = $_POST['blood_type'];
    $last = $_POST['last_donation'] !== '' ? $_POST['last_donation'] : null;

    if ($name === '' || !in_array($bt, BLOOD_TYPES, true)) {
        flash("Name and a valid blood type are required.", 'danger');
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE donors SET name=?, phone=?, location=?, blood_type=?, last_donation=? WHERE donor_id=?");
        mysqli_stmt_bind_param($stmt, "sssssi", $name, $phone, $location, $bt, $last, $id);
        mysqli_stmt_execute($stmt);
        flash("Donor updated.");
        header("Location: donors.php");
        exit();
    }
}

$stmt = mysqli_prepare($conn, "SELECT * FROM donors WHERE donor_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$d = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$d) { flash("Donor not found.", 'danger'); header("Location: donors.php"); exit(); }

include 'includes/header.php';
?>
<div class="bg-white p-4 rounded shadow-sm mx-auto" style="max-width:560px">
    <h3 class="mb-3">Edit Donor #<?php echo (int)$d['donor_id']; ?></h3>
    <p class="text-muted">Email: <?php echo e($d['email']); ?></p>
    <form method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="donor_id" value="<?php echo (int)$d['donor_id']; ?>">
        <div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="name" value="<?php echo e($d['name']); ?>" required></div>
        <div class="mb-3"><label class="form-label">Phone</label><input class="form-control" name="phone" value="<?php echo e($d['phone']); ?>" required></div>
        <div class="mb-3"><label class="form-label">Location</label><input class="form-control" name="location" value="<?php echo e($d['location']); ?>" required></div>
        <div class="mb-3"><label class="form-label">Blood Type</label>
            <select class="form-select" name="blood_type" required>
                <?php foreach (BLOOD_TYPES as $t) echo "<option " . ($d['blood_type'] === $t ? 'selected' : '') . ">" . e($t) . "</option>"; ?>
            </select></div>
        <div class="mb-3"><label class="form-label">Last Donation</label><input type="date" class="form-control" name="last_donation" value="<?php echo e($d['last_donation']); ?>"></div>
        <button class="btn btn-danger">Save</button>
        <a href="donors.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
