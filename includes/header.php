<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

$currentUser = getCurrentUserData($pdo);
$role = getUserRole();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' . APP_NAME : APP_NAME; ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- App Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url('css/style.css'); ?>">
</head>
<body>
    <div class="app-container">
        <?php if (isLoggedIn()): ?>
            <?php include __DIR__ . '/sidebar.php'; ?>
            <div class="main-wrapper">
                <header class="topbar">
                    <button class="topbar-toggle" id="menuToggleBtn" aria-label="Toggle Sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="topbar-title-wrap">
                        <h3 style="font-size:18px; font-weight:700; color:var(--primary-color);">
                            <?php echo $pageTitle ?? 'Dashboard'; ?>
                        </h3>
                    </div>
                    <div class="topbar-user">
                        <div class="user-avatar">
                            <?php 
                                $name = $currentUser['full_name'] ?? ($currentUser['doctor_name'] ?? ($currentUser['username'] ?? 'U'));
                                echo strtoupper(substr($name, 0, 1));
                            ?>
                        </div>
                        <div class="user-info">
                            <span class="user-name"><?php echo htmlspecialchars($name); ?></span>
                            <span class="user-role"><?php echo htmlspecialchars($role); ?></span>
                        </div>
                        <a href="<?php echo base_url('logout.php'); ?>" class="btn btn-secondary btn-sm" title="Logout" style="margin-left: 10px;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </header>
                <main class="content-body">
                    <?php displayFlash(); ?>
        <?php endif; ?>
