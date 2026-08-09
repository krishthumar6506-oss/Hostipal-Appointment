<?php
$pageTitle = "Home";
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// Fetch top doctors to feature on public homepage
$stmt = $pdo->query("SELECT * FROM doctor ORDER BY experience DESC LIMIT 4");
$doctors = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Caring for Your Health</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Public Navigation Bar -->
    <nav class="public-nav">
        <a href="index.php" class="public-brand">
            <i class="fas fa-heartbeat" style="color:var(--accent-color);"></i>
            <span><?php echo APP_SHORT_NAME; ?> Care</span>
        </a>
        <ul class="public-menu">
            <li><a href="index.php" style="font-weight:600; color:var(--primary-color);">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if (isLoggedIn()): ?>
                <?php 
                $dashboardLink = 'patient/index.php';
                if (getUserRole() === 'doctor') $dashboardLink = 'doctor/index.php';
                if (getUserRole() === 'admin') $dashboardLink = 'admin/index.php';
                ?>
                <li><a href="<?php echo $dashboardLink; ?>" class="btn btn-primary btn-sm"><i class="fas fa-columns"></i> My Dashboard</a></li>
            <?php else: ?>
                <li><a href="login.php" class="btn btn-secondary btn-sm"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="register.php" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i> Patient Signup</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <h1 class="hero-title">Your Health is Our Top Priority</h1>
        <p class="hero-subtitle">Book appointments with world-class specialist doctors quickly, securely, and seamlessly online.</p>
        <div style="display:flex; justify-content:center; gap:15px; flex-wrap:wrap;">
            <a href="register.php" class="btn btn-primary" style="background:#ffffff; color:var(--primary-color); padding:14px 28px; font-size:16px;">
                <i class="fas fa-calendar-alt"></i> Book Appointment Now
            </a>
            <a href="login.php" class="btn btn-secondary" style="background:rgba(255,255,255,0.2); color:#ffffff; padding:14px 28px; font-size:16px;">
                <i class="fas fa-user-lock"></i> Portal Login
            </a>
        </div>
    </section>

    <!-- Key Services Highlights -->
    <section style="padding: 60px 40px; max-width: 1200px; margin: 0 auto;">
        <h2 style="text-align: center; color: var(--primary-color); margin-bottom: 40px; font-size: 28px;">Our Medical Services</h2>
        <div class="dashboard-grid">
            <div class="card" style="padding:25px; text-align:center;">
                <i class="fas fa-heartbeat" style="font-size:40px; color:#e53e3e; margin-bottom:15px;"></i>
                <h3 style="margin-bottom:10px;">Cardiology</h3>
                <p style="color:var(--text-muted); font-size:14px;">Comprehensive cardiac care, routine ECGs, and advanced heart health monitoring.</p>
            </div>
            <div class="card" style="padding:25px; text-align:center;">
                <i class="fas fa-brain" style="font-size:40px; color:#805ad5; margin-bottom:15px;"></i>
                <h3 style="margin-bottom:10px;">Neurology</h3>
                <p style="color:var(--text-muted); font-size:14px;">Expert diagnosis and treatment for neurological disorders and headaches.</p>
            </div>
            <div class="card" style="padding:25px; text-align:center;">
                <i class="fas fa-baby" style="font-size:40px; color:#319795; margin-bottom:15px;"></i>
                <h3 style="margin-bottom:10px;">Pediatrics</h3>
                <p style="color:var(--text-muted); font-size:14px;">Dedicated medical care for infants, children, and adolescents.</p>
            </div>
            <div class="card" style="padding:25px; text-align:center;">
                <i class="fas fa-bone" style="font-size:40px; color:#dd6b20; margin-bottom:15px;"></i>
                <h3 style="margin-bottom:10px;">Orthopedics</h3>
                <p style="color:var(--text-muted); font-size:14px;">Specialized joint care, bone fracture treatment, and physical rehabilitation.</p>
            </div>
        </div>
    </section>

    <!-- Top Featured Doctors Showcase -->
    <section style="background:#ffffff; padding:60px 40px; border-top:1px solid var(--border-color);">
        <div style="max-width:1200px; margin:0 auto;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
                <div>
                    <h2 style="color:var(--primary-color); font-size:28px;">Meet Our Top Doctors</h2>
                    <p style="color:var(--text-muted);">Experienced specialists dedicated to your wellness.</p>
                </div>
                <a href="register.php" class="btn btn-secondary"><i class="fas fa-arrow-right"></i> View All Doctors</a>
            </div>

            <div class="dashboard-grid">
                <?php foreach ($doctors as $doc): ?>
                    <div class="card" style="margin-bottom:0;">
                        <div class="card-body" style="text-align:center; padding:30px 20px;">
                            <div class="user-avatar" style="width:70px; height:70px; font-size:24px; margin:0 auto 15px auto;">
                                <?php echo strtoupper(substr($doc['doctor_name'], 4, 1)); ?>
                            </div>
                            <h4 style="font-size:18px; color:var(--primary-color);"><?php echo htmlspecialchars($doc['doctor_name']); ?></h4>
                            <span class="badge badge-approved" style="margin:8px 0;"><?php echo htmlspecialchars($doc['specialization']); ?></span>
                            <p style="font-size:13px; color:var(--text-muted);"><?php echo htmlspecialchars($doc['qualification']); ?> • <?php echo $doc['experience']; ?> Yrs Exp.</p>
                            <a href="login.php" class="btn btn-primary btn-sm" style="width:100%; margin-top:15px;">
                                Book Appointment
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background:var(--primary-color); color:#cbd5e0; text-align:center; padding:30px; margin-top:auto;">
        <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All Rights Reserved.</p>
        <p style="font-size:12px; margin-top:5px; opacity:0.7;">Emergency Contact: +1 (800) 555-MEDCARE | Location: 100 Hospital Drive, Healthcare City</p>
    </footer>

</body>
</html>
