<?php
/**
 * Language / Internationalization (i18n)
 * Supports English (LTR) and Arabic (RTL)
 */
class Lang {
    private static array $translations = [];
    private static string $currentLang = 'en';
    private static array $supportedLangs = ['en', 'ar'];

    /**
     * Initialize language from session/cookie/default
     */
    public static function init(): void {
        // Priority: URL param > Session > Cookie > Default
        if (isset($_GET['lang']) && in_array($_GET['lang'], self::$supportedLangs)) {
            self::$currentLang = $_GET['lang'];
        } elseif (isset($_SESSION['lang'])) {
            self::$currentLang = $_SESSION['lang'];
        } elseif (isset($_COOKIE['lang'])) {
            self::$currentLang = $_COOKIE['lang'];
        }

        // Persist choice
        $_SESSION['lang'] = self::$currentLang;
        setcookie('lang', self::$currentLang, time() + (365 * 24 * 3600), '/');

        // Load translation file
        $langFile = LANG_DIR . '/' . self::$currentLang . '.php';
        if (file_exists($langFile)) {
            self::$translations = require $langFile;
        }
    }

    /**
     * Set language
     */
    public static function set(string $lang): void {
        if (in_array($lang, self::$supportedLangs)) {
            self::$currentLang = $lang;
            $_SESSION['lang'] = $lang;
            setcookie('lang', $lang, time() + (365 * 24 * 3600), '/');

            $langFile = LANG_DIR . '/' . $lang . '.php';
            if (file_exists($langFile)) {
                self::$translations = require $langFile;
            }
        }
    }

    /**
     * Get translation by dot-notation key
     * Example: Lang::get('nav.home') => 'Home'
     */
    public static function get(string $key, array $replace = []): string {
        $keys = explode('.', $key);
        $value = self::$translations;

        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $key; // Return key if translation not found
            }
        }

        if (!is_string($value)) {
            return $key;
        }

        // Replace placeholders like :name
        foreach ($replace as $placeholder => $replacement) {
            $value = str_replace(':' . $placeholder, $replacement, $value);
        }

        return $value;
    }

    /**
     * Get current language code
     */
    public static function current(): string {
        return self::$currentLang;
    }

    /**
     * Check if current language is RTL
     */
    public static function isRtl(): bool {
        return self::$currentLang === 'ar';
    }

    /**
     * Get text direction
     */
    public static function direction(): string {
        return self::isRtl() ? 'rtl' : 'ltr';
    }

    /**
     * Get the other available language (for toggle)
     */
    public static function otherLang(): string {
        return self::$currentLang === 'en' ? 'ar' : 'en';
    }

    /**
     * Get other language label
     */
    public static function otherLangLabel(): string {
        return self::$currentLang === 'en' ? 'العربية' : 'English';
    }

    /**
     * Get font family based on current language
     */
    public static function fontFamily(): string {
        return self::isRtl() ? 'var(--font-arabic)' : 'var(--font-primary)';
    }
}

/**
 * Global translation helper function
 * Usage: __('nav.home') or __('greeting', ['name' => 'Ahmed'])
 */
function __(string $key, array $replace = []): string {
    return Lang::get($key, $replace);
}
