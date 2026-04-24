<?php
/**
 * Logger - Error and Event Logging
 * Writes to logs/app.log with timestamps and severity levels
 */
class Logger {
    private static string $logFile = '';
    
    // Log levels
    const DEBUG   = 'DEBUG';
    const INFO    = 'INFO';
    const WARNING = 'WARNING';
    const ERROR   = 'ERROR';
    const CRITICAL = 'CRITICAL';

    /**
     * Initialize logger with log file path
     */
    public static function init(): void {
        $logDir = ROOT_PATH . '/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        self::$logFile = $logDir . '/app.log';
    }

    /**
     * Write a log entry
     */
    public static function log(string $level, string $message, array $context = []): void {
        if (empty(self::$logFile)) {
            self::init();
        }

        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
        $uri = $_SERVER['REQUEST_URI'] ?? 'N/A';
        $userId = $_SESSION['user_id'] ?? 'guest';
        
        $entry = "[{$timestamp}] [{$level}] [{$ip}] [User:{$userId}] [{$uri}] {$message}";
        
        if (!empty($context)) {
            $entry .= ' | Context: ' . json_encode($context, JSON_UNESCAPED_UNICODE);
        }
        
        $entry .= PHP_EOL;
        
        file_put_contents(self::$logFile, $entry, FILE_APPEND | LOCK_EX);
    }

    /**
     * Log debug message
     */
    public static function debug(string $message, array $context = []): void {
        if (APP_DEBUG) {
            self::log(self::DEBUG, $message, $context);
        }
    }

    /**
     * Log info message
     */
    public static function info(string $message, array $context = []): void {
        self::log(self::INFO, $message, $context);
    }

    /**
     * Log warning
     */
    public static function warning(string $message, array $context = []): void {
        self::log(self::WARNING, $message, $context);
    }

    /**
     * Log error
     */
    public static function error(string $message, array $context = []): void {
        self::log(self::ERROR, $message, $context);
    }

    /**
     * Log critical error
     */
    public static function critical(string $message, array $context = []): void {
        self::log(self::CRITICAL, $message, $context);
    }

    /**
     * Log an exception with full trace
     */
    public static function exception(\Throwable $e, string $extraMessage = ''): void {
        $message = $extraMessage ? "{$extraMessage} | " : '';
        $message .= get_class($e) . ': ' . $e->getMessage();
        
        self::log(self::ERROR, $message, [
            'file'  => $e->getFile() . ':' . $e->getLine(),
            'trace' => array_slice(explode("\n", $e->getTraceAsString()), 0, 5),
        ]);
    }

    /**
     * Register as PHP error handler
     * Call this once in index.php to catch all PHP errors
     */
    public static function registerErrorHandler(): void {
        set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline) {
            $levels = [
                E_WARNING     => self::WARNING,
                E_NOTICE      => self::INFO,
                E_USER_ERROR  => self::ERROR,
                E_USER_WARNING => self::WARNING,
                E_USER_NOTICE => self::INFO,
            ];
            $level = $levels[$errno] ?? self::ERROR;
            self::log($level, "PHP [{$errno}]: {$errstr}", [
                'file' => "{$errfile}:{$errline}",
            ]);
            return false; // Let PHP's default handler continue
        });

        set_exception_handler(function (\Throwable $e) {
            self::exception($e, 'Uncaught Exception');
            if (APP_DEBUG) {
                echo "<pre>Uncaught: " . htmlspecialchars($e->getMessage()) . "\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            } else {
                http_response_code(500);
                include VIEWS_PATH . '/errors/404.php'; // Use generic error page
            }
        });
    }
}
