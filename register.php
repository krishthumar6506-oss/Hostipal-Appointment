<?php
$pageTitle = "Patient Registration";
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = sanitize($_POST['full_name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $gender = sanitize($_POST['gender'] ?? 'Male');
    $age = intval($_POST['age'] ?? 0);
    $address = sanitize($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($fullName) || empty($email) || empty($phone) || empty($password)) {
        $error = "Please fill in all required fields.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if email already registered
        $stmt = $pdo->prepare("SELECT patient_id FROM patient WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = "An account with this email address already exists.";
        } else {
            // Hash password and insert record
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO patient (full_name, email, phone, gender, age, address, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$fullName, $email, $phone, $gender, $age, $address, $hashedPassword])) {
                setFlash('success', 'Registration successful! You can now log in.');
                header('Location: ' . base_url('login.php?role=patient'));
                exit();
            } else {
                $error = "Failed to register account. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Signup - <?php echo APP_NAME; ?></title>
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
            padding: 30px 20px;
        }
        .register-card {
            background: #ffffff;
            width: 100%;
            max-width: 650px;
            border-radius: var(--radius-md);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
    </style>
</head>
<body>

    <div class="register-card">
        <div style="padding: 30px; text-align: center; border-bottom: 1px solid var(--border-color);">
            <a href="index.php" style="display:inline-flex; align-items:center; gap:8px; font-size:22px; font-weight:800; color:var(--primary-color);">
                <i class="fas fa-heartbeat" style="color:var(--accent-color);"></i> <?php echo APP_SHORT_NAME; ?> Care
            </a>
            <h2 style="font-size:20px; margin-top:10px; color:var(--primary-color);">Patient Registration</h2>
        </div>

        <div style="padding: 30px;">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" class="validate-form">
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="full_name" class="form-control" placeholder="John Doe" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phone Number *</label>
                        <input type="text" name="phone" class="form-control" placeholder="9123456789" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Gender *</label>
                        <select name="gender" class="form-select" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Age *</label>
                        <input type="number" name="age" class="form-control" placeholder="30" min="1" max="120" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Address *</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Full Home Address" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; padding:12px; margin-top:10px;">
                    <i class="fas fa-user-check"></i> Register Account
                </button>
            </form>

            <div style="margin-top:20px; text-align:center; font-size:13px; color:var(--text-muted);">
                Already have an account? <a href="login.php" style="font-weight:600;">Login Here</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
