<?php
$pageTitle = "My Profile";
require_once __DIR__ . '/../includes/header.php';
requireAuth('patient');

$patientId = $_SESSION['user_id'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = sanitize($_POST['full_name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $gender = sanitize($_POST['gender'] ?? 'Male');
    $age = intval($_POST['age'] ?? 0);
    $address = sanitize($_POST['address'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';

    if (empty($fullName) || empty($phone)) {
        $error = "Name and Phone number are required.";
    } else {
        if (!empty($newPassword)) {
            $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE patient SET full_name = ?, phone = ?, gender = ?, age = ?, address = ?, password = ? WHERE patient_id = ?");
            $stmt->execute([$fullName, $phone, $gender, $age, $address, $hashed, $patientId]);
        } else {
            $stmt = $pdo->prepare("UPDATE patient SET full_name = ?, phone = ?, gender = ?, age = ?, address = ? WHERE patient_id = ?");
            $stmt->execute([$fullName, $phone, $gender, $age, $address, $patientId]);
        }

        setFlash('success', 'Profile details updated successfully!');
        header('Location: ' . base_url('patient/profile.php'));
        exit();
    }
}

// Refresh user details
$patient = getCurrentUserData($pdo);
?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-id-card"></i> Personal Profile Settings</h3>
    </div>
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="profile.php" method="POST" class="validate-form">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($patient['full_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address (Read-only)</label>
                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($patient['email']); ?>" disabled style="background:#edf2f7;">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($patient['phone']); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select" required>
                        <option value="Male" <?php echo ($patient['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($patient['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo ($patient['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Age</label>
                    <input type="number" name="age" class="form-control" value="<?php echo htmlspecialchars($patient['age']); ?>" min="1" max="120" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Residential Address</label>
                <textarea name="address" class="form-control" rows="3" required><?php echo htmlspecialchars($patient['address']); ?></textarea>
            </div>

            <hr style="border:0; border-top:1px solid var(--border-color); margin:25px 0;">

            <div class="form-group">
                <label class="form-label">New Password (Leave blank to keep unchanged)</label>
                <input type="password" name="new_password" class="form-control" placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Profile Changes
            </button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
