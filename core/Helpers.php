<?php
/**
 * Global Helper Functions
 */

/**
 * Escape output to prevent XSS
 */
function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate full URL
 */
function url(string $path = ''): string {
    return APP_URL . '/' . ltrim($path, '/');
}

/**
 * Generate asset URL
 */
function asset(string $path): string {
    return APP_URL . '/assets/' . ltrim($path, '/');
}

/**
 * Generate upload URL
 */
function upload_url(string $path): string {
    return APP_URL . '/assets/uploads/' . ltrim($path, '/');
}

/**
 * Redirect to a URL
 */
function redirect(string $path): void {
    header("Location: " . url($path));
    exit;
}

/**
 * Get CSRF token
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Generate CSRF hidden input field
 */
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Verify CSRF token
 */
function verify_csrf(string $token): bool {
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get flash message and clear it
 */
function flash(string $key) {
    $value = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $value;
}

/**
 * Get old input value (after validation failure redirect)
 */
function old(string $key, $default = ''): string {
    $value = $_SESSION['flash']['old_input'][$key] ?? $default;
    return e((string) $value);
}

/**
 * Rate limiting for AJAX endpoints
 */
function rateLimit(string $key, int $maxAttempts = 30, int $windowSeconds = 60): void {
    $now = time();
    $sessionKey = 'rate_limit_' . $key;

    if (!isset($_SESSION[$sessionKey])) {
        $_SESSION[$sessionKey] = [];
    }

    // Clean expired attempts
    $_SESSION[$sessionKey] = array_filter(
        $_SESSION[$sessionKey],
        fn($t) => $t > $now - $windowSeconds
    );

    if (count($_SESSION[$sessionKey]) >= $maxAttempts) {
        http_response_code(429);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Too many requests. Please wait.']);
        exit;
    }

    $_SESSION[$sessionKey][] = $now;
}

/**
 * Format date according to current language
 */
function formatDate(string $date, string $format = 'M d, Y'): string {
    return date($format, strtotime($date));
}

/**
 * Format price in DZD
 */
function formatPrice($amount): string {
    if ($amount === null) return '-';
    return number_format((float) $amount, 0, '.', ',') . ' ' . __('common.currency');
}

/**
 * Get user avatar URL or default
 */
function avatarUrl(?string $avatar, int $userId = 0): string {
    if ($avatar && file_exists(UPLOAD_DIR . '/users/' . $userId . '/' . $avatar)) {
        return upload_url('users/' . $userId . '/' . $avatar);
    }
    return asset('images/default-avatar.png');
}

/**
 * Time ago helper (e.g., "2 hours ago")
 */
function timeAgo(string $datetime): string {
    $now = new DateTime();
    $past = new DateTime($datetime);
    $diff = $now->diff($past);

    if ($diff->y > 0) return $diff->y . ' ' . __('time.years_ago');
    if ($diff->m > 0) return $diff->m . ' ' . __('time.months_ago');
    if ($diff->d > 0) return $diff->d . ' ' . __('time.days_ago');
    if ($diff->h > 0) return $diff->h . ' ' . __('time.hours_ago');
    if ($diff->i > 0) return $diff->i . ' ' . __('time.minutes_ago');
    return __('time.just_now');
}

/**
 * Truncate text with ellipsis
 */
function truncate(string $text, int $length = 100): string {
    if (mb_strlen($text) <= $length) return e($text);
    return e(mb_substr($text, 0, $length)) . '...';
}

/**
 * Secure file upload handler
 * @return string|false The saved filename or false on failure
 */
function uploadFile(array $file, string $destination, int $maxSize = 2097152): string|false {
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    // Check file size
    if ($file['size'] > $maxSize) {
        return false;
    }

    // Validate MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, ALLOWED_IMAGE_TYPES)) {
        return false;
    }

    // Generate random filename
    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $ext = $extensions[$mime] ?? 'jpg';
    $filename = bin2hex(random_bytes(16)) . '.' . $ext;

    // Create directory if not exists
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }

    // Move file
    if (move_uploaded_file($file['tmp_name'], $destination . '/' . $filename)) {
        return $filename;
    }

    return false;
}

/**
 * Get the current theme (light/dark)
 */
function currentTheme(): string {
    return $_SESSION['theme'] ?? $_COOKIE['theme'] ?? 'light';
}

/**
 * Check if dark mode is active
 */
function isDarkMode(): bool {
    return currentTheme() === 'dark';
}
