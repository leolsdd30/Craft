<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <a href="<?= url('') ?>" class="navbar-brand" style="margin-bottom: var(--space-md); display: inline-flex;">
                    <svg width="28" height="28" viewBox="0 0 36 36" fill="none">
                        <rect width="36" height="36" rx="8" fill="currentColor"/>
                        <path d="M10 26V14l8-6 8 6v12H20v-6h-4v6H10z" fill="white"/>
                    </svg>
                    Craft
                </a>
                <p class="text-muted" style="max-width: 320px; line-height: var(--leading-relaxed);">
                    <?= __('footer.about_text') ?>
                </p>
            </div>
            <div>
                <h4 class="footer-title"><?= __('footer.quick_links') ?></h4>
                <ul class="footer-links">
                    <li><a href="<?= url('') ?>"><?= __('nav.home') ?></a></li>
                    <li><a href="<?= url('search') ?>"><?= __('nav.find_craftsmen') ?></a></li>
                    <li><a href="<?= url('jobs') ?>"><?= __('nav.browse_jobs') ?></a></li>
                    <li><a href="<?= url('register/craftsman') ?>"><?= __('home.join_as_craftsman') ?></a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-title"><?= __('footer.support') ?></h4>
                <ul class="footer-links">
                    <li><a href="#"><?= __('footer.contact_us') ?></a></li>
                    <li><a href="#"><?= __('footer.privacy') ?></a></li>
                    <li><a href="#"><?= __('footer.terms') ?></a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <?= __('footer.copyright') ?>
        </div>
    </div>
</footer>
