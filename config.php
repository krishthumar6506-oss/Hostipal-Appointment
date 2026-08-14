<?php
// Global Application Configuration

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Application Info
define('APP_NAME', 'MedCare Hospital Management System');
define('APP_SHORT_NAME', 'MedCare');

// Database Credentials
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hospital_db');
define('DB_PORT', 3306);

// Base URL detection for clean redirects and assets
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));

// Calculate base path (up to hospital-management)
$base_dir = preg_replace('#/(admin|doctor|patient|includes|css|js|jquery|images).*$#', '', $script_name);
$base_dir = rtrim($base_dir, '/');

define('BASE_URL', $protocol . $domain . $base_dir . '/');

/**
 * Helper function to generate full URL
 */
function base_url($path = '') {
    return BASE_URL . ltrim($path, '/');
}

/**
 * Helper function for HTML escaping
 */
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
