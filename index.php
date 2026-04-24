<?php
/**
 * Craft Platform - Front Controller
 * All requests route through this file
 */

// Load configuration
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';

// Load core classes
require_once __DIR__ . '/core/Helpers.php';
require_once __DIR__ . '/core/Lang.php';
require_once __DIR__ . '/core/Validator.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/Logger.php';
require_once __DIR__ . '/core/Router.php';

// Initialize logger and register error handler
Logger::init();
Logger::registerErrorHandler();

// Initialize language
Lang::init();

// Initialize router and define routes
$router = new Router();

// ============================================
// Public Routes
// ============================================
$router->get('', 'HomeController', 'index');
$router->get('home', 'HomeController', 'index');
$router->get('search', 'HomeController', 'search');
$router->get('craftsman/{id}', 'ProfileController', 'show');

// Auth Routes
$router->get('login', 'AuthController', 'loginForm');
$router->post('login', 'AuthController', 'login');
$router->get('register', 'AuthController', 'registerForm');
$router->post('register', 'AuthController', 'register');
$router->get('register/craftsman', 'AuthController', 'registerCraftsmanForm');
$router->post('register/craftsman', 'AuthController', 'registerCraftsman');
$router->get('logout', 'AuthController', 'logout');

// Language & Theme
$router->get('lang/{lang}', 'HomeController', 'switchLang');
$router->get('theme/{theme}', 'HomeController', 'switchTheme');

// ============================================
// Authenticated Routes
// ============================================

// Jobs
$router->get('jobs', 'JobController', 'index');
$router->get('jobs/create', 'JobController', 'create');
$router->post('jobs/store', 'JobController', 'store');
$router->get('jobs/{id}', 'JobController', 'show');
$router->get('jobs/{id}/edit', 'JobController', 'edit');
$router->post('jobs/{id}/update', 'JobController', 'update');
$router->post('jobs/{id}/delete', 'JobController', 'delete');

// Applications
$router->post('jobs/{id}/apply', 'ApplicationController', 'store');
$router->post('applications/{id}/accept', 'ApplicationController', 'accept');
$router->post('applications/{id}/reject', 'ApplicationController', 'reject');

// Bookings
$router->get('bookings', 'BookingController', 'index');
$router->get('bookings/create/{craftsman_id}', 'BookingController', 'create');
$router->post('bookings/store', 'BookingController', 'store');
$router->get('bookings/{id}', 'BookingController', 'show');
$router->post('bookings/{id}/status', 'BookingController', 'updateStatus');

// Reviews
$router->post('reviews/store', 'ReviewController', 'store');

// Messages
$router->get('messages', 'MessageController', 'index');
$router->get('messages/{id}', 'MessageController', 'chat');
$router->post('messages/send', 'MessageController', 'send');
$router->get('messages/start/{user_id}', 'MessageController', 'startConversation');

// Profile
$router->get('profile/edit', 'ProfileController', 'edit');
$router->post('profile/update', 'ProfileController', 'update');
$router->post('profile/avatar', 'ProfileController', 'updateAvatar');
$router->post('profile/portfolio/upload', 'ProfileController', 'uploadPortfolio');
$router->post('profile/portfolio/{id}/delete', 'ProfileController', 'deletePortfolio');

// Notifications
$router->get('notifications', 'NotificationController', 'index');
$router->post('notifications/{id}/read', 'NotificationController', 'markRead');
$router->post('notifications/read-all', 'NotificationController', 'markAllRead');

// ============================================
// Admin Routes
// ============================================
$router->get('admin', 'AdminController', 'dashboard');
$router->get('admin/dashboard', 'AdminController', 'dashboard');
$router->get('admin/users', 'AdminController', 'users');
$router->post('admin/users/{id}/toggle', 'AdminController', 'toggleUser');
$router->get('admin/jobs', 'AdminController', 'jobs');
$router->post('admin/jobs/{id}/delete', 'AdminController', 'deleteJob');
$router->get('admin/categories', 'AdminController', 'categories');
$router->post('admin/categories/store', 'AdminController', 'storeCategory');
$router->post('admin/categories/{id}/update', 'AdminController', 'updateCategory');
$router->post('admin/categories/{id}/delete', 'AdminController', 'deleteCategory');
$router->get('admin/verifications', 'AdminController', 'verifications');
$router->post('admin/verifications/{id}/approve', 'AdminController', 'approve');
$router->post('admin/verifications/{id}/reject', 'AdminController', 'reject');

// ============================================
// API Routes (AJAX)
// ============================================
$router->get('api/messages', 'MessageController', 'apiGetMessages');
$router->get('api/notifications', 'NotificationController', 'apiGetNotifications');
$router->get('api/locations/communes', 'HomeController', 'apiGetCommunes');
$router->get('api/search', 'HomeController', 'apiSearch');

// Dispatch the request
$router->dispatch();
