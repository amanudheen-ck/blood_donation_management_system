<?php
require_once __DIR__ . '/includes/auth.php';
require_admin();
$page_title = 'Camps';

// Detect the camps primary key so this works whatever it is named (id, camp_id, ...).
$pk_row = mysqli_fetch_assoc(mysqli_query($conn, "SHOW KEYS FROM camps WHERE Key_name = 'PRIMARY'"));
$pk = $pk_row ? $pk_row['Column_name'] : 'camp_id';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['camp_id'] ?? 0);

    if ($action === 'delete') {
        $stmt = mysqli_prepare($conn, "DELETE FROM camps WHERE `$pk` = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        flash("Camp deleted.");
    } elseif ($action === 'save') {
        $title = trim($_POST['title']);
        $location = trim($_POST['location']);
        $date = $_POST['date'];
        $desc = trim($_POST['description']);
        if ($title === '' || $location === '' || $date === '') {
            flash("Title, location and date are required.", 'danger');
            header("Location: camps.php" . ($id ? "?edit=$id" : ''));
            exit();
        }
        if ($id) {
            $stmt = mysqli_prepare($conn, "UPDATE camps SET title=?, location=?, `date`=?, description=? WHERE `$pk`=?");
            mysqli_stmt_bind_param($stmt, "ssssi", $title, $location, $date, $desc, $id);
            flash("Camp updated.");
        } else {
            $stmt = mysqli_prepare($conn, "INSERT INTO camps (title, location, `date`, description) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssss", $title, $location, $date, $desc);
            flash("Camp added.");
        }
        mysqli_stmt_execute($stmt);
    }
    header("Location: camps.php");
    exit();
}

$edit = null;
if (!empty($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM camps WHERE `$pk` = ?");
    mysqli_stmt_bind_param($stmt, "i", $eid);
    mysqli_stmt_execute($stmt);
    $edit = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}
$camps = mysqli_query($conn, "SELECT * FROM camps ORDER BY `date` DESC");

include 'includes/header.php';
?>
<h2 class="mb-3">Donation Camps</h2>
<div class="row g-4">
    <div class="col-md-4">
        <div class="bg-white p-3 rounded shadow-sm">
            <h5><?php echo $edit ? 'Edit Camp' : 'Add Camp'; ?></h5>
            <form method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="camp_id" value="<?php echo $edit ? (int)$edit[$pk] : 0; ?>">
                <input class="form-control mb-2" name="title" placeholder="Title" value="<?php echo e($edit['title'] ?? ''); ?>" required>
                <input class="form-control mb-2" name="location" placeholder="Location" value="<?php echo e($edit['location'] ?? ''); ?>" required>
                <input class="form-control mb-2" type="date" name="date" value="<?php echo e($edit['date'] ?? ''); ?>" required>
                <textarea class="form-control mb-2" name="description" rows="3" placeholder="Description"><?php echo e($edit['description'] ?? ''); ?></textarea>
                <button class="btn btn-danger"><?php echo $edit ? 'Update' : 'Add'; ?></button>
                <?php if ($edit): ?><a href="camps.php" class="btn btn-secondary">Cancel</a><?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table table-bordered table-striped bg-white">
            <thead><tr><th>Title</th><th>Location</th><th>Date</th><th width="140">Actions</th></tr></thead>
            <tbody>
            <?php while ($c = mysqli_fetch_assoc($camps)): ?>
                <tr>
                    <td><?php echo e($c['title']); ?></td>
                    <td><?php echo e($c['location']); ?></td>
                    <td><?php echo e($c['date']); ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="camps.php?edit=<?php echo (int)$c[$pk]; ?>">Edit</a>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Delete this camp?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="camp_id" value="<?php echo (int)$c[$pk]; ?>">
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
