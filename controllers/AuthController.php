<?php
/**
 * Auth Controller
 * Login, Register, Logout
 */
class AuthController extends Controller {

    public function loginForm(): void {
        if (Auth::check()) {
            $this->redirect('');
        }
        $this->view('auth/login', [
            'pageTitle'   => __('auth.login_title'),
            'currentPage' => 'login',
        ]);
    }

    public function login(): void {
        $this->verifyCsrf();

        $email = trim($this->input('email', ''));
        $password = $this->input('password', '');

        // Rate limit login attempts
        rateLimit('login', 5, 300);

        $v = new Validator();
        $v->required('email', $email, __('auth.email'))
          ->email('email', $email, __('auth.email'))
          ->required('password', $password, __('auth.password'));

        if ($v->fails()) {
            $v->flashErrors();
            $this->redirect('login');
        }

        if (Auth::attempt($email, $password)) {
            // Redirect based on role
            $user = Auth::user();
            if ($user['role'] === 'admin') {
                $this->redirect('admin', ['success' => __('auth.login_success')]);
            }
            $this->redirect('', ['success' => __('auth.login_success')]);
        }

        $this->redirect('login', ['error' => __('auth.invalid_credentials')]);
    }

    public function registerForm(): void {
        if (Auth::check()) {
            $this->redirect('');
        }
        $this->view('auth/register', [
            'pageTitle'   => __('auth.register_title'),
            'currentPage' => 'register',
        ]);
    }

    public function register(): void {
        $this->verifyCsrf();

        $name     = trim($this->input('name', ''));
        $email    = trim($this->input('email', ''));
        $phone    = trim($this->input('phone', ''));
        $password = $this->input('password', '');
        $confirm  = $this->input('password_confirmation', '');
        $role     = $this->input('role', 'homeowner');

        $v = new Validator();
        $v->required('name', $name, __('auth.name'))
          ->minLength('name', $name, 2, __('auth.name'))
          ->maxLength('name', $name, 100, __('auth.name'))
          ->required('email', $email, __('auth.email'))
          ->email('email', $email, __('auth.email'))
          ->unique('email', $email, 'users', null, __('auth.email'))
          ->required('password', $password, __('auth.password'))
          ->minLength('password', $password, 6, __('auth.password'))
          ->confirmed('password', $password, $confirm, __('auth.password'))
          ->phone('phone', $phone, __('auth.phone'))
          ->in('role', $role, ['homeowner', 'craftsman']);

        if ($v->fails()) {
            $v->flashErrors();
            $this->redirect($role === 'craftsman' ? 'register/craftsman' : 'register');
        }

        $userId = Auth::register([
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'password' => $password,
            'role'     => $role,
            'preferred_lang' => Lang::current(),
        ]);

        // Auto-login after registration
        Auth::attempt($email, $password);

        if ($role === 'craftsman') {
            $this->redirect('profile/edit', ['success' => __('auth.register_success')]);
        }

        $this->redirect('', ['success' => __('auth.register_success')]);
    }

    public function registerCraftsmanForm(): void {
        if (Auth::check()) {
            $this->redirect('');
        }
        $this->view('auth/register-craftsman', [
            'pageTitle'   => __('auth.register_craftsman_title'),
            'currentPage' => 'register',
        ]);
    }

    public function registerCraftsman(): void {
        // Reuse same register logic with role=craftsman
        $_POST['role'] = 'craftsman';
        $this->register();
    }

    public function logout(): void {
        Auth::logout();
        $this->redirect('', ['success' => __('auth.logout_success')]);
    }
}
