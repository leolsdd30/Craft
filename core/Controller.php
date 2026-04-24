<?php
/**
 * Base Controller
 * All controllers extend this class
 */
class Controller {

    /**
     * Render a view with the main layout
     */
    protected function view(string $viewPath, array $data = [], string $layout = 'app'): void {
        // Extract data to make variables available in views
        extract($data);

        // Capture the view content
        ob_start();
        $viewFile = VIEWS_PATH . '/' . str_replace('.', '/', $viewPath) . '.php';
        
        if (!file_exists($viewFile)) {
            throw new Exception("View not found: {$viewPath}");
        }
        
        include $viewFile;
        $content = ob_get_clean();

        // Include the layout
        $layoutFile = VIEWS_PATH . '/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            include $layoutFile;
        } else {
            echo $content;
        }
    }

    /**
     * Render a view without layout (for AJAX partials)
     */
    protected function partial(string $viewPath, array $data = []): void {
        extract($data);
        include VIEWS_PATH . '/' . str_replace('.', '/', $viewPath) . '.php';
    }

    /**
     * Send JSON response
     */
    protected function json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Redirect to a URL
     */
    protected function redirect(string $path, array $flash = []): void {
        // Store flash messages
        foreach ($flash as $key => $value) {
            $_SESSION['flash'][$key] = $value;
        }

        $url = APP_URL . '/' . ltrim($path, '/');
        header("Location: {$url}");
        exit;
    }

    /**
     * Require authentication
     */
    protected function requireAuth(): void {
        if (!Auth::check()) {
            $this->redirect('login', ['error' => __('auth.login_required')]);
        }
    }

    /**
     * Require specific role
     */
    protected function requireRole(string $role): void {
        $this->requireAuth();
        if (Auth::user()['role'] !== $role) {
            http_response_code(403);
            include VIEWS_PATH . '/errors/403.php';
            exit;
        }
    }

    /**
     * Require any of the specified roles
     */
    protected function requireAnyRole(array $roles): void {
        $this->requireAuth();
        if (!in_array(Auth::user()['role'], $roles)) {
            http_response_code(403);
            include VIEWS_PATH . '/errors/403.php';
            exit;
        }
    }

    /**
     * Verify CSRF token
     */
    protected function verifyCsrf(): void {
        $token = $_POST['csrf_token'] ?? '';
        if (!verify_csrf($token)) {
            $this->redirect('', ['error' => __('errors.invalid_request')]);
        }
    }

    /**
     * Get POST input safely
     */
    protected function input(string $key, $default = null) {
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET parameter safely
     */
    protected function query(string $key, $default = null) {
        return $_GET[$key] ?? $default;
    }

    /**
     * Check if request is AJAX
     */
    protected function isAjax(): bool {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
