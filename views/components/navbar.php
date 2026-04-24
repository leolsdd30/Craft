<nav class="navbar" id="navbar">
    <div class="container">
        <!-- Brand -->
        <a href="<?= url('') ?>" class="navbar-brand">
            <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
                <rect width="36" height="36" rx="8" fill="currentColor"/>
                <path d="M10 26V14l8-6 8 6v12H20v-6h-4v6H10z" fill="white"/>
            </svg>
            Craft
        </a>

        <!-- Navigation Links -->
        <ul class="navbar-nav" id="navMenu">
            <li><a href="<?= url('') ?>" class="<?= ($currentPage ?? '') === 'home' ? 'active' : '' ?>"><?= __('nav.home') ?></a></li>
            <li><a href="<?= url('search') ?>" class="<?= ($currentPage ?? '') === 'search' ? 'active' : '' ?>"><?= __('nav.find_craftsmen') ?></a></li>
            <li><a href="<?= url('jobs') ?>" class="<?= ($currentPage ?? '') === 'jobs' ? 'active' : '' ?>"><?= __('nav.browse_jobs') ?></a></li>

            <?php if (Auth::check()): ?>
                <?php if (Auth::hasRole('homeowner')): ?>
                    <li><a href="<?= url('jobs/create') ?>"><?= __('nav.post_job') ?></a></li>
                <?php endif; ?>
                <li><a href="<?= url('bookings') ?>"><?= __('nav.my_bookings') ?></a></li>
                <li><a href="<?= url('messages') ?>" class="<?= ($currentPage ?? '') === 'messages' ? 'active' : '' ?>"><?= __('nav.messages') ?></a></li>
            <?php endif; ?>
        </ul>

        <!-- Actions -->
        <div class="navbar-actions">
            <!-- Language Toggle -->
            <a href="<?= url('lang/' . Lang::otherLang()) ?>" class="lang-toggle" title="Switch language">
                <?= Lang::otherLangLabel() ?>
            </a>

            <!-- Theme Toggle -->
            <button class="toggle-btn" id="themeToggle" title="Toggle theme" aria-label="Toggle dark mode">
                <svg id="sunIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="<?= isDarkMode() ? 'display:none' : '' ?>">
                    <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                </svg>
                <svg id="moonIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="<?= isDarkMode() ? '' : 'display:none' ?>">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                </svg>
            </button>

            <?php if (Auth::check()): ?>
                <!-- Notifications -->
                <a href="<?= url('notifications') ?>" class="toggle-btn notif-dot" title="<?= __('nav.notifications') ?>" id="notifBtn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                </a>

                <!-- User Menu -->
                <div class="user-menu" id="userMenu">
                    <button class="user-menu-trigger" id="userMenuTrigger">
                        <img src="<?= avatarUrl(Auth::user()['avatar'], Auth::id()) ?>" alt="<?= e(Auth::user()['name']) ?>" class="avatar avatar-sm">
                    </button>
                    <div class="user-dropdown" id="userDropdown">
                        <div class="dropdown-header">
                            <strong><?= e(Auth::user()['name']) ?></strong>
                            <span class="text-muted text-sm"><?= e(Auth::user()['email']) ?></span>
                        </div>
                        <div class="dropdown-divider"></div>
                        <?php if (Auth::hasRole('craftsman')): ?>
                            <a href="<?= url('profile/edit') ?>" class="dropdown-item"><?= __('nav.my_profile') ?></a>
                        <?php endif; ?>
                        <?php if (Auth::hasRole('admin')): ?>
                            <a href="<?= url('admin') ?>" class="dropdown-item"><?= __('nav.admin_panel') ?></a>
                        <?php endif; ?>
                        <div class="dropdown-divider"></div>
                        <a href="<?= url('logout') ?>" class="dropdown-item text-error"><?= __('nav.logout') ?></a>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= url('login') ?>" class="btn btn-ghost btn-sm"><?= __('nav.login') ?></a>
                <a href="<?= url('register') ?>" class="btn btn-primary btn-sm"><?= __('nav.register') ?></a>
            <?php endif; ?>

            <!-- Mobile Menu Button -->
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
        </div>
    </div>
</nav>
