<?php
/**
 * Authentication & Session Management
 */
class Auth {

    /**
     * Attempt to login with email and password
     */
    public static function attempt(string $email, string $password): bool {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND is_active = 1 LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_avatar'] = $user['avatar'];

            return true;
        }

        return false;
    }

    /**
     * Check if user is logged in
     */
    public static function check(): bool {
        return isset($_SESSION['user_id']);
    }

    /**
     * Get current user data
     */
    public static function user(): ?array {
        if (!self::check()) {
            return null;
        }

        return [
            'id'     => $_SESSION['user_id'],
            'role'   => $_SESSION['user_role'],
            'name'   => $_SESSION['user_name'],
            'email'  => $_SESSION['user_email'],
            'avatar' => $_SESSION['user_avatar'] ?? null,
        ];
    }

    /**
     * Get current user ID
     */
    public static function id(): ?int {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Check if current user has specific role
     */
    public static function hasRole(string $role): bool {
        return self::check() && $_SESSION['user_role'] === $role;
    }

    /**
     * Logout the user
     */
    public static function logout(): void {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
    }

    /**
     * Hash a password using bcrypt
     */
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Register a new user
     * @return int The new user ID
     */
    public static function register(array $data): int {
        $db = getDB();

        $stmt = $db->prepare("
            INSERT INTO users (name, email, password, phone, role, preferred_lang)
            VALUES (:name, :email, :password, :phone, :role, :preferred_lang)
        ");

        $stmt->execute([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'password'       => self::hashPassword($data['password']),
            'phone'          => $data['phone'] ?? null,
            'role'           => $data['role'] ?? 'homeowner',
            'preferred_lang' => $data['preferred_lang'] ?? 'en',
        ]);

        return (int) $db->lastInsertId();
    }

    /**
     * Update user session data (after profile update)
     */
    public static function refreshSession(int $userId): void {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_avatar'] = $user['avatar'];
        }
    }
}
