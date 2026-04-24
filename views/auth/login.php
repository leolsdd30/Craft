<section class="auth-page">
    <div class="auth-container">
        <div class="auth-card card">
            <div class="card-body" style="padding: var(--space-2xl);">
                <!-- Header -->
                <div class="text-center" style="margin-bottom: var(--space-2xl);">
                    <a href="<?= url('') ?>" class="navbar-brand" style="justify-content: center; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 36 36" fill="none">
                            <rect width="36" height="36" rx="8" fill="currentColor"/>
                            <path d="M10 26V14l8-6 8 6v12H20v-6h-4v6H10z" fill="white"/>
                        </svg>
                        Craft
                    </a>
                    <h1 style="font-size: var(--text-2xl); font-weight: 700; margin-bottom: var(--space-xs);"><?= __('auth.login_title') ?></h1>
                    <p class="text-muted"><?= __('auth.login_subtitle') ?></p>
                </div>

                <!-- Form -->
                <form action="<?= url('login') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label class="form-label" for="email"><?= __('auth.email') ?></label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= old('email') ?>" placeholder="example@email.com" required autofocus>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password"><?= __('auth.password') ?></label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: var(--space-md);">
                        <?= __('auth.login_btn') ?>
                    </button>
                </form>

                <!-- Links -->
                <div class="text-center" style="margin-top: var(--space-xl);">
                    <p class="text-muted text-sm">
                        <?= __('auth.no_account') ?>
                        <a href="<?= url('register') ?>"><?= __('nav.register') ?></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.auth-page {
    display: flex; align-items: center; justify-content: center;
    min-height: calc(100vh - var(--navbar-height) - 100px);
    padding: var(--space-2xl) var(--space-md);
}
.auth-container { width: 100%; max-width: 440px; }
.auth-card { border: none; box-shadow: var(--shadow-lg); }
</style>
