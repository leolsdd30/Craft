<!DOCTYPE html>
<html lang="<?= Lang::current() ?>" dir="<?= Lang::direction() ?>" data-theme="<?= currentTheme() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($metaDescription ?? __('home.hero_subtitle')) ?>">
    <title><?= e($pageTitle ?? 'Craft') ?> — Craft</title>

    <!-- Styles -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/components.css') ?>">
    <meta name="app-url" content="<?= APP_URL ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= asset('images/logo.svg') ?>">
</head>
<body>
    <!-- Navbar -->
    <?php include VIEWS_PATH . '/components/navbar.php'; ?>

    <!-- Flash Messages -->
    <div class="main-content">
        <div class="container">
            <?php if ($successMsg = flash('success')): ?>
                <div class="alert alert-success" id="flash-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <?= e($successMsg) ?>
                </div>
            <?php endif; ?>

            <?php if ($errorMsg = flash('error')): ?>
                <div class="alert alert-error" id="flash-error">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <?= e($errorMsg) ?>
                </div>
            <?php endif; ?>

            <?php if ($errors = flash('errors')): ?>
                <div class="alert alert-error">
                    <div>
                        <?php foreach ($errors as $field => $msg): ?>
                            <div><?= e($msg) ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Page Content -->
        <?= $content ?>
    </div>

    <!-- Footer -->
    <?php include VIEWS_PATH . '/components/footer.php'; ?>

    <!-- Scripts -->
    <script src="<?= asset('js/app.js') ?>"></script>
    <?php if (isset($scripts)): ?>
        <?php foreach ((array)$scripts as $script): ?>
            <script src="<?= asset('js/' . $script) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
