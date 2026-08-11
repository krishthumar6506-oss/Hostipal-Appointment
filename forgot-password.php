<?php
$pageTitle = "Forgot Password";
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $role = sanitize($_POST['role'] ?? 'patient');

    if (empty($email) || empty($newPassword)) {
        $error = "Please fill in all fields.";
    } else {
        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
        
        if ($role === 'patient') {
            $stmt = $pdo->prepare("UPDATE patient SET password = ? WHERE email = ?");
        } elseif ($role === 'doctor') {
            $stmt = $pdo->prepare("UPDATE doctor SET password = ? WHERE email = ?");
        } else {
            $stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE email = ?");
        }

        $stmt->execute([$hashed, $email]);

        if ($stmt->rowCount() > 0) {
            setFlash('success', 'Your password has been successfully reset! Please log in.');
            header('Location: ' . base_url('login.php?role=' . $role));
            exit();
        } else {
            $error = "No matching account found with that email address.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - <?php echo APP_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a365d 0%, #2b6cb0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .reset-card {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            border-radius: var(--radius-md);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }
    </style>
</head>
<body>

    <div class="reset-card">
        <div style="text-align: center; margin-bottom: 25px;">
            <a href="index.php" style="font-size:22px; font-weight:800; color:var(--primary-color);">
                <i class="fas fa-key" style="color:var(--accent-color);"></i> Reset Password
            </a>
            <p style="font-size:13px; color:var(--text-muted); margin-top:5px;">Enter your email to update your account password</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle alert-icon"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <form action="forgot-password.php" method="POST" class="validate-form">
            <div class="form-group">
                <label class="form-label">Account Role</label>
                <select name="role" class="form-select" required>
                    <option value="patient">Patient</option>
                    <option value="doctor">Doctor</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Registered Email</label>
                <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; padding:12px;">
                <i class="fas fa-sync-alt"></i> Reset Password
            </button>
        </form>

        <div style="margin-top:20px; text-align:center; font-size:13px;">
            <a href="login.php"><i class="fas fa-arrow-left"></i> Back to Login</a>
        </div>
    </div>

</body>
</html>
