<?php
/**
 * Application Configuration
 */

// ============================================
// Environment
// ============================================
define('APP_NAME', 'Craft');
define('APP_URL', 'http://localhost/craft');
define('APP_DEBUG', true); // Set false in production
define('APP_VERSION', '1.0.0');

// ============================================
// Paths
// ============================================
define('ROOT_PATH', dirname(__DIR__));
define('VIEWS_PATH', ROOT_PATH . '/views');
define('UPLOAD_DIR', ROOT_PATH . '/assets/uploads');
define('LANG_DIR', ROOT_PATH . '/lang');

// ============================================
// Upload Limits
// ============================================
define('MAX_AVATAR_SIZE', 2 * 1024 * 1024);    // 2MB
define('MAX_PORTFOLIO_SIZE', 5 * 1024 * 1024);  // 5MB
define('MAX_JOB_IMAGE_SIZE', 3 * 1024 * 1024);  // 3MB
define('MAX_PORTFOLIO_ITEMS', 10);
define('MAX_JOB_IMAGES', 5);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

// ============================================
// Session Security
// ============================================
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', 1);
ini_set('session.gc_maxlifetime', 3600); // 1 hour

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================
// Default Timezone
// ============================================
date_default_timezone_set('Africa/Algiers');

// ============================================
// Error Reporting
// ============================================
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
