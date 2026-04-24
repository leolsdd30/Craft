<section class="auth-page">
    <div class="auth-container">
        <div class="auth-card card">
            <div class="card-body" style="padding: var(--space-2xl);">
                <div class="text-center" style="margin-bottom: var(--space-2xl);">
                    <a href="<?= url('') ?>" class="navbar-brand" style="justify-content: center; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 36 36" fill="none">
                            <rect width="36" height="36" rx="8" fill="currentColor"/>
                            <path d="M10 26V14l8-6 8 6v12H20v-6h-4v6H10z" fill="white"/>
                        </svg>
                        Craft
                    </a>
                    <h1 style="font-size: var(--text-2xl); font-weight: 700; margin-bottom: var(--space-xs);"><?= __('auth.register_title') ?></h1>
                    <p class="text-muted"><?= __('auth.register_subtitle') ?></p>
                </div>

                <form action="<?= url('register') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="role" value="homeowner">

                    <div class="form-group">
                        <label class="form-label" for="name"><?= __('auth.name') ?></label>
                        <input type="text" id="name" name="name" class="form-control" value="<?= old('name') ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email"><?= __('auth.email') ?></label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone"><?= __('auth.phone') ?></label>
                        <input type="tel" id="phone" name="phone" class="form-control" value="<?= old('phone') ?>" placeholder="05XXXXXXXX">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password"><?= __('auth.password') ?></label>
                        <input type="password" id="password" name="password" class="form-control" required minlength="6">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation"><?= __('auth.confirm_password') ?></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: var(--space-md);">
                        <?= __('auth.register_btn') ?>
                    </button>
                </form>

                <div class="text-center" style="margin-top: var(--space-lg);">
                    <p class="text-muted text-sm">
                        <?= __('auth.or_register_as') ?> <a href="<?= url('register/craftsman') ?>"><?= __('auth.craftsman') ?></a>
                    </p>
                    <p class="text-muted text-sm" style="margin-top: var(--space-sm);">
                        <?= __('auth.has_account') ?> <a href="<?= url('login') ?>"><?= __('nav.login') ?></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.auth-page { display: flex; align-items: center; justify-content: center; min-height: calc(100vh - var(--navbar-height) - 100px); padding: var(--space-2xl) var(--space-md); }
.auth-container { width: 100%; max-width: 440px; }
.auth-card { border: none; box-shadow: var(--shadow-lg); }
</style>
