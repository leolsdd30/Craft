<!DOCTYPE html>
<html lang="<?= Lang::current() ?>" dir="<?= Lang::direction() ?>" data-theme="<?= currentTheme() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Craft</title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
    <div style="display:flex;align-items:center;justify-content:center;min-height:100vh;text-align:center;padding:var(--space-2xl);">
        <div>
            <h1 style="font-size:6rem;font-weight:800;color:var(--color-primary);margin-bottom:var(--space-md);">404</h1>
            <h2 style="font-size:var(--text-2xl);margin-bottom:var(--space-md);"><?= __('errors.404_title') ?></h2>
            <p class="text-muted" style="margin-bottom:var(--space-xl);"><?= __('errors.404_message') ?></p>
            <a href="<?= url('') ?>" class="btn btn-primary btn-lg"><?= __('errors.go_home') ?></a>
        </div>
    </div>
</body>
</html>
