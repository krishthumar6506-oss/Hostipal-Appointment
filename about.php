<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - <?php echo APP_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="public-nav">
        <a href="index.php" class="public-brand">
            <i class="fas fa-heartbeat" style="color:var(--accent-color);"></i>
            <span><?php echo APP_SHORT_NAME; ?> Care</span>
        </a>
        <ul class="public-menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php" style="font-weight:600; color:var(--primary-color);">About Us</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="login.php" class="btn btn-secondary btn-sm"><i class="fas fa-sign-in-alt"></i> Login</a></li>
            <li><a href="register.php" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i> Patient Signup</a></li>
        </ul>
    </nav>

    <div style="background:var(--primary-color); color:#fff; padding:50px 40px; text-align:center;">
        <h1 style="font-size:36px; font-weight:800;">About <?php echo APP_NAME; ?></h1>
        <p style="opacity:0.9; max-width:600px; margin:10px auto 0 auto;">Pioneering excellence in medical diagnostics, patient care, and seamless digital healthcare scheduling.</p>
    </div>

    <main style="max-width:1000px; margin:50px auto; padding:0 20px;">
        <div class="card">
            <div class="card-body" style="padding:40px; line-height:1.8;">
                <h2 style="color:var(--primary-color); margin-bottom:15px;">Our Mission</h2>
                <p style="margin-bottom:25px; color:var(--text-primary);">
                    At MedCare, our mission is to deliver world-class medical services with compassion and efficiency. 
                    We leverage modern web technologies to simplify appointment scheduling, minimize wait times, and connect patients directly with experienced specialists.
                </p>

                <div class="dashboard-grid" style="margin-top:30px;">
                    <div style="background:var(--bg-light); padding:20px; border-radius:var(--radius-sm);">
                        <i class="fas fa-user-shield" style="font-size:30px; color:var(--primary-light); margin-bottom:10px;"></i>
                        <h4>24/7 Availability</h4>
                        <p style="font-size:13px; color:var(--text-muted);">Access schedules and request consultation slots anytime anywhere.</p>
                    </div>
                    <div style="background:var(--bg-light); padding:20px; border-radius:var(--radius-sm);">
                        <i class="fas fa-stethoscope" style="font-size:30px; color:var(--accent-color); margin-bottom:10px;"></i>
                        <h4>Certified Specialists</h4>
                        <p style="font-size:13px; color:var(--text-muted);">Our medical board consists of top qualified practitioners across disciplines.</p>
                    </div>
                    <div style="background:var(--bg-light); padding:20px; border-radius:var(--radius-sm);">
                        <i class="fas fa-lock" style="font-size:30px; color:#38a169; margin-bottom:10px;"></i>
                        <h4>Secure Records</h4>
                        <p style="font-size:13px; color:var(--text-muted);">Your personal information and medical data are protected with modern encryption.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer style="background:var(--primary-color); color:#cbd5e0; text-align:center; padding:30px; margin-top:auto;">
        <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All Rights Reserved.</p>
    </footer>

</body>
</html>
