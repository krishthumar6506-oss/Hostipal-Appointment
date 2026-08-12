<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/auth.php';

$messageSent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    if (!empty($name) && !empty($email) && !empty($message)) {
        $messageSent = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - <?php echo APP_NAME; ?></title>
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
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php" style="font-weight:600; color:var(--primary-color);">Contact</a></li>
            <li><a href="login.php" class="btn btn-secondary btn-sm"><i class="fas fa-sign-in-alt"></i> Login</a></li>
            <li><a href="register.php" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i> Patient Signup</a></li>
        </ul>
    </nav>

    <div style="background:var(--primary-color); color:#fff; padding:50px 40px; text-align:center;">
        <h1 style="font-size:36px; font-weight:800;">Contact Us</h1>
        <p style="opacity:0.9;">We are here to assist you 24 hours a day, 7 days a week.</p>
    </div>

    <main style="max-width:1100px; margin:50px auto; padding:0 20px;">
        <div class="dashboard-grid" style="grid-template-columns: 1fr 1.5fr;">
            
            <!-- Contact Info Sidebar -->
            <div class="card">
                <div class="card-header"><h3 class="card-title">Hospital Helpdesk</h3></div>
                <div class="card-body" style="line-height:2;">
                    <p><i class="fas fa-map-marker-alt" style="color:var(--primary-light); width:25px;"></i> 100 Hospital Drive, Medical District</p>
                    <p><i class="fas fa-phone" style="color:var(--primary-light); width:25px;"></i> +1 (800) 555-MEDCARE</p>
                    <p><i class="fas fa-envelope" style="color:var(--primary-light); width:25px;"></i> support@medcarehospital.com</p>
                    <p><i class="fas fa-clock" style="color:var(--primary-light); width:25px;"></i> Emergency: 24/7 Open</p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="card">
                <div class="card-header"><h3 class="card-title">Send Us a Message</h3></div>
                <div class="card-body">
                    <?php if ($messageSent): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle alert-icon"></i>
                            <span>Thank you! Your message has been sent successfully. We will get back to you shortly.</span>
                        </div>
                    <?php endif; ?>

                    <form action="contact.php" method="POST" class="validate-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Your Name</label>
                                <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Your Email</label>
                                <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="Appointment Inquiry" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="5" placeholder="How can we help you?" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send Message</button>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <footer style="background:var(--primary-color); color:#cbd5e0; text-align:center; padding:30px; margin-top:auto;">
        <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All Rights Reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
