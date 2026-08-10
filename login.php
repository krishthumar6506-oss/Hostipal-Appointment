<?php
$pageTitle = "Login";
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    switch (getUserRole()) {
        case 'admin': header('Location: ' . base_url('admin/index.php')); exit();
        case 'doctor': header('Location: ' . base_url('doctor/index.php')); exit();
        case 'patient': header('Location: ' . base_url('patient/index.php')); exit();
    }
}

$error = '';
$selectedRole = $_GET['role'] ?? 'patient';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = sanitize($_POST['role'] ?? 'patient');
    $identity = sanitize($_POST['identity'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($identity) || empty($password)) {
        $error = "Please provide both username/email and password.";
    } else {
        if ($role === 'patient') {
            $stmt = $pdo->prepare("SELECT * FROM patient WHERE email = ?");
            $stmt->execute([$identity]);
            $user = $stmt->fetch();
            $idCol = 'patient_id';
        } elseif ($role === 'doctor') {
            $stmt = $pdo->prepare("SELECT * FROM doctor WHERE email = ?");
            $stmt->execute([$identity]);
            $user = $stmt->fetch();
            $idCol = 'doctor_id';
        } elseif ($role === 'admin') {
            $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ? OR email = ?");
            $stmt->execute([$identity, $identity]);
            $user = $stmt->fetch();
            $idCol = 'admin_id';
        } else {
            $user = null;
        }

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user[$idCol];
            $_SESSION['role'] = $role;
            $_SESSION['user_name'] = $user['full_name'] ?? ($user['doctor_name'] ?? $user['username']);

            setFlash('success', 'Welcome back, ' . $_SESSION['user_name'] . '!');

            if ($role === 'patient') header('Location: ' . base_url('patient/index.php'));
            elseif ($role === 'doctor') header('Location: ' . base_url('doctor/index.php'));
            elseif ($role === 'admin') header('Location: ' . base_url('admin/index.php'));
            exit();
        } else {
            $error = "Invalid credentials or incorrect role selected.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - <?php echo APP_NAME; ?></title>
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
        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 440px;
            border-radius: var(--radius-md);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .login-header {
            padding: 30px 30px 20px 30px;
            text-align: center;
        }
        .role-tabs {
            display: flex;
            background: #edf2f7;
            padding: 4px;
            border-radius: var(--radius-sm);
            margin-bottom: 25px;
        }
        .role-tab {
            flex: 1;
            padding: 10px 0;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            border: none;
            background: none;
            cursor: pointer;
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            transition: var(--transition);
        }
        .role-tab.active {
            background: #ffffff;
            color: var(--primary-color);
            box-shadow: var(--shadow-sm);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <a href="index.php" style="display:inline-flex; align-items:center; gap:8px; font-size:22px; font-weight:800; color:var(--primary-color);">
                <i class="fas fa-hospital-symbol" style="color:var(--accent-color);"></i> <?php echo APP_SHORT_NAME; ?> Care
            </a>
            <p style="font-size:14px; color:var(--text-muted); margin-top:8px;">Sign in to access your portal</p>
        </div>

        <div style="padding: 0 30px 30px 30px;">
            <?php displayFlash(); ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="validate-form">
                <input type="hidden" name="role" id="selectedRoleInput" value="<?php echo htmlspecialchars($selectedRole); ?>">

                <!-- Role Selector Tabs -->
                <div class="role-tabs">
                    <button type="button" class="role-tab <?php echo ($selectedRole === 'patient') ? 'active' : ''; ?>" onclick="setRole('patient')">
                        <i class="fas fa-user"></i> Patient
                    </button>
                    <button type="button" class="role-tab <?php echo ($selectedRole === 'doctor') ? 'active' : ''; ?>" onclick="setRole('doctor')">
                        <i class="fas fa-user-md"></i> Doctor
                    </button>
                    <button type="button" class="role-tab <?php echo ($selectedRole === 'admin') ? 'active' : ''; ?>" onclick="setRole('admin')">
                        <i class="fas fa-user-shield"></i> Admin
                    </button>
                </div>

                <div class="form-group">
                    <label class="form-label" id="identityLabel">
                        <?php echo ($selectedRole === 'admin') ? 'Username or Email' : 'Email Address'; ?>
                    </label>
                    <input type="text" name="identity" id="identityInput" class="form-control" placeholder="Enter your credentials" required>
                </div>

                <div class="form-group">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                        <label class="form-label" style="margin-bottom:0;">Password</label>
                        <a href="forgot-password.php" style="font-size:12px;">Forgot Password?</a>
                    </div>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; padding:12px; margin-top:10px;">
                    <i class="fas fa-sign-in-alt"></i> Log In
                </button>
            </form>

            <div style="margin-top:20px; text-align:center; font-size:13px; color:var(--text-muted);">
                Don't have a patient account? <a href="register.php" style="font-weight:600;">Register Here</a>
            </div>

            <!-- Demo Login Credentials Panel for Quick Testing -->
            <div style="margin-top:25px; padding:12px; background:#f7fafc; border:1px dashed var(--border-color); border-radius:var(--radius-sm); font-size:11px; color:var(--text-muted);">
                <strong>Default Demo Credentials (Password: <code>password123</code>):</strong><br>
                • <b>Admin:</b> <code>admin</code><br>
                • <b>Doctor:</b> <code>sarah.jenkins@hospital.com</code><br>
                • <b>Patient:</b> <code>john@example.com</code>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function setRole(role) {
            $('#selectedRoleInput').val(role);
            $('.role-tab').removeClass('active');
            event.currentTarget.classList.add('active');

            if (role === 'admin') {
                $('#identityLabel').text('Username or Email');
                $('#identityInput').attr('placeholder', 'admin');
            } else if (role === 'doctor') {
                $('#identityLabel').text('Doctor Email');
                $('#identityInput').attr('placeholder', 'doctor@hospital.com');
            } else {
                $('#identityLabel').text('Patient Email Address');
                $('#identityInput').attr('placeholder', 'patient@example.com');
            }
        }
    </script>
</body>
</html>
