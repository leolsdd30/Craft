<?php
/**
 * Home Controller
 * Landing page, search, language/theme switching
 */
require_once ROOT_PATH . '/models/Category.php';
require_once ROOT_PATH . '/models/CraftsmanProfile.php';

class HomeController extends Controller {

    public function index(): void {
        $categoryModel = new Category();
        $categories = $categoryModel->where('is_active', 1);

        $this->view('home/index', [
            'pageTitle'   => __('nav.home'),
            'currentPage' => 'home',
            'categories'  => $categories,
        ]);
    }

    public function search(): void {
        $this->view('search/results', [
            'pageTitle'   => __('nav.find_craftsmen'),
            'currentPage' => 'search',
        ]);
    }

    public function switchLang(string $lang): void {
        Lang::set($lang);
        // Redirect back to previous page
        $referer = $_SERVER['HTTP_REFERER'] ?? url('');
        header("Location: {$referer}");
        exit;
    }

    public function switchTheme(string $theme): void {
        $allowed = ['light', 'dark'];
        if (in_array($theme, $allowed)) {
            $_SESSION['theme'] = $theme;
            setcookie('theme', $theme, time() + (365 * 24 * 3600), '/');
        }

        if ($this->isAjax()) {
            $this->json(['success' => true]);
        } else {
            $referer = $_SERVER['HTTP_REFERER'] ?? url('');
            header("Location: {$referer}");
            exit;
        }
    }

    public function apiGetCommunes(): void {
        $wilayaId = (int) $this->query('wilaya_id', 0);
        if ($wilayaId <= 0) {
            $this->json(['data' => []]);
        }

        $db = getDB();
        $stmt = $db->prepare("SELECT id, name_en, name_ar FROM communes WHERE wilaya_id = :wid ORDER BY name_en");
        $stmt->execute(['wid' => $wilayaId]);
        $this->json(['data' => $stmt->fetchAll()]);
    }

    public function apiSearch(): void {
        rateLimit('search', 30, 60);
        $q = trim($this->query('q', ''));
        $this->json(['data' => [], 'query' => $q]);
    }
}
