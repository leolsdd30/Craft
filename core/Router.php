<?php
/**
 * Simple URL Router
 * Maps URL patterns to Controller::method pairs
 */
class Router {
    private array $routes = [];

    /**
     * Register a GET route
     */
    public function get(string $path, string $controller, string $method): void {
        $this->addRoute('GET', $path, $controller, $method);
    }

    /**
     * Register a POST route
     */
    public function post(string $path, string $controller, string $method): void {
        $this->addRoute('POST', $path, $controller, $method);
    }

    /**
     * Add route to the routes array
     */
    private function addRoute(string $httpMethod, string $path, string $controller, string $method): void {
        // Convert {param} to regex named groups
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'httpMethod' => $httpMethod,
            'pattern'    => $pattern,
            'controller' => $controller,
            'method'     => $method,
        ];
    }

    /**
     * Dispatch the current request to the matching route
     */
    public function dispatch(): void {
        // Get the URL from query string (set by .htaccess)
        $url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';
        $httpMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['httpMethod'] !== $httpMethod) {
                continue;
            }

            if (preg_match($route['pattern'], $url, $matches)) {
                // Extract named parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Load and instantiate controller
                $controllerName = $route['controller'];
                $controllerFile = ROOT_PATH . '/controllers/' . $controllerName . '.php';

                if (!file_exists($controllerFile)) {
                    $this->notFound("Controller file not found: {$controllerName}");
                    return;
                }

                require_once $controllerFile;

                if (!class_exists($controllerName)) {
                    $this->notFound("Controller class not found: {$controllerName}");
                    return;
                }

                $controller = new $controllerName();
                $methodName = $route['method'];

                if (!method_exists($controller, $methodName)) {
                    $this->notFound("Method not found: {$controllerName}::{$methodName}");
                    return;
                }

                // Call the controller method with parameters
                call_user_func_array([$controller, $methodName], $params);
                return;
            }
        }

        // No route matched
        $this->notFound();
    }

    /**
     * Show 404 page
     */
    private function notFound(string $debugMessage = ''): void {
        http_response_code(404);
        if (APP_DEBUG && $debugMessage) {
            $_SESSION['debug_message'] = $debugMessage;
        }
        include VIEWS_PATH . '/errors/404.php';
        exit;
    }
}
