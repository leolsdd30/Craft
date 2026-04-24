<!-- ============================================
     Hero Section
     ============================================ -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title"><?= __('home.hero_title') ?></h1>
            <p class="hero-subtitle"><?= __('home.hero_subtitle') ?></p>

            <!-- Search Bar -->
            <form class="hero-search" action="<?= url('search') ?>" method="GET">
                <div class="hero-search-inner">
                    <svg class="search-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="q" class="hero-search-input" placeholder="<?= __('home.search_placeholder') ?>">
                    <select name="category" class="hero-search-select">
                        <option value=""><?= __('home.all_categories') ?></option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= e(Lang::current() === 'ar' ? $cat['name_ar'] : $cat['name_en']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary btn-lg hero-search-btn"><?= __('home.search_btn') ?></button>
                </div>
            </form>

            <!-- Stats -->
            <div class="hero-stats">
                <div class="hero-stat">
                    <span class="hero-stat-number">150+</span>
                    <span class="hero-stat-label"><?= __('home.stats_craftsmen') ?></span>
                </div>
                <div class="hero-stat-divider"></div>
                <div class="hero-stat">
                    <span class="hero-stat-number">500+</span>
                    <span class="hero-stat-label"><?= __('home.stats_jobs') ?></span>
                </div>
                <div class="hero-stat-divider"></div>
                <div class="hero-stat">
                    <span class="hero-stat-number">1,200+</span>
                    <span class="hero-stat-label"><?= __('home.stats_reviews') ?></span>
                </div>
                <div class="hero-stat-divider"></div>
                <div class="hero-stat">
                    <span class="hero-stat-number">48</span>
                    <span class="hero-stat-label"><?= __('home.stats_wilayas') ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     Popular Services
     ============================================ -->
<section class="section" style="padding: var(--space-4xl) 0;">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title"><?= __('home.popular_services') ?></h2>
            <p class="section-subtitle"><?= __('home.hero_subtitle') ?></p>
        </div>

        <div class="services-grid">
            <?php foreach ($categories as $cat): ?>
            <a href="<?= url('search?category=' . $cat['id']) ?>" class="service-card">
                <div class="service-icon">
                    <?php
                    $icons = [
                        'plumbing'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 12h4m-2-2v4m10-7v14m-4-4h8m-4-4h4"/></svg>',
                        'electrical'=> '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13 2L3 14h8l-1 8 10-12h-8l1-8z"/></svg>',
                        'painting'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 3H5a2 2 0 00-2 2v2a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zM12 9v12M8 21h8"/></svg>',
                        'carpentry' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M12 6v12M2 12h20"/></svg>',
                        'masonry'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/><line x1="1" y1="16" x2="23" y2="16"/><line x1="12" y1="4" x2="12" y2="10"/><line x1="6" y1="10" x2="6" y2="16"/><line x1="18" y1="10" x2="18" y2="16"/><line x1="12" y1="16" x2="12" y2="20"/></svg>',
                        'welding'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>',
                        'ac'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="10" rx="2"/><path d="M6 17c.7 2 2 3 4 3m8-3c-.7 2-2 3-4 3m-2-3v3"/></svg>',
                        'cleaning'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l1 7h4l-3 5 1 8h-6l1-8-3-5h4l1-7z"/></svg>',
                        'gardening' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22V8M5 12s-2-3 0-6c3 0 5 2 7 6m0 0c2-4 4-6 7-6 2 3 0 6 0 6"/></svg>',
                        'appliance' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4" y="2" width="16" height="20" rx="2"/><circle cx="12" cy="14" r="3"/><line x1="8" y1="6" x2="16" y2="6"/></svg>',
                    ];
                    echo $icons[$cat['icon']] ?? $icons['plumbing'];
                    ?>
                </div>
                <h3 class="service-name"><?= e(Lang::current() === 'ar' ? $cat['name_ar'] : $cat['name_en']) ?></h3>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================
     How It Works
     ============================================ -->
<section class="section how-it-works" style="padding: var(--space-4xl) 0; background: var(--color-bg-secondary);">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title"><?= __('home.how_it_works') ?></h2>
        </div>

        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3 class="step-title"><?= __('home.step1_title') ?></h3>
                <p class="step-desc"><?= __('home.step1_desc') ?></p>
            </div>
            <div class="step-connector">
                <svg width="40" height="2"><line x1="0" y1="1" x2="40" y2="1" stroke="var(--color-primary)" stroke-width="2" stroke-dasharray="6"/></svg>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h3 class="step-title"><?= __('home.step2_title') ?></h3>
                <p class="step-desc"><?= __('home.step2_desc') ?></p>
            </div>
            <div class="step-connector">
                <svg width="40" height="2"><line x1="0" y1="1" x2="40" y2="1" stroke="var(--color-primary)" stroke-width="2" stroke-dasharray="6"/></svg>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h3 class="step-title"><?= __('home.step3_title') ?></h3>
                <p class="step-desc"><?= __('home.step3_desc') ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     CTA Section
     ============================================ -->
<section class="section cta-section" style="padding: var(--space-4xl) 0;">
    <div class="container">
        <div class="cta-card">
            <div class="cta-content">
                <h2 class="cta-title"><?= __('home.get_started') ?></h2>
                <p class="cta-subtitle"><?= __('home.hero_subtitle') ?></p>
                <div class="cta-buttons">
                    <a href="<?= url('register') ?>" class="btn btn-primary btn-lg"><?= __('home.get_started') ?></a>
                    <a href="<?= url('register/craftsman') ?>" class="btn btn-outline btn-lg"><?= __('home.join_as_craftsman') ?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     Homepage Styles
     ============================================ -->
<style>
/* Hero */
.hero {
    padding: var(--space-4xl) 0 var(--space-3xl);
    background: linear-gradient(135deg, var(--color-primary-bg) 0%, var(--color-bg) 100%);
    text-align: center;
}
.hero-content { max-width: 800px; margin: 0 auto; }
.hero-title {
    font-family: var(--font-display);
    font-size: clamp(var(--text-3xl), 5vw, var(--text-5xl));
    font-weight: 800; line-height: var(--leading-tight);
    color: var(--color-text);
    margin-bottom: var(--space-lg);
}
[dir="rtl"] .hero-title { font-family: var(--font-arabic); }
.hero-subtitle {
    font-size: var(--text-lg); color: var(--color-text-secondary);
    line-height: var(--leading-relaxed);
    margin-bottom: var(--space-2xl);
}

/* Hero Search */
.hero-search { margin-bottom: var(--space-2xl); }
.hero-search-inner {
    display: flex; align-items: center;
    background: var(--color-surface);
    border: 2px solid var(--color-border);
    border-radius: var(--radius-lg);
    padding: 6px;
    box-shadow: var(--shadow-lg);
    transition: border-color var(--transition-fast);
}
.hero-search-inner:focus-within { border-color: var(--color-primary); }
.search-icon { margin: 0 var(--space-md); color: var(--color-text-muted); flex-shrink: 0; }
.hero-search-input {
    flex: 1; border: none; outline: none; padding: 14px 0;
    font-size: var(--text-base); font-family: inherit;
    background: transparent; color: var(--color-text); min-width: 0;
}
.hero-search-select {
    border: none; outline: none; padding: 14px var(--space-md);
    font-family: inherit; font-size: var(--text-sm);
    background: transparent; color: var(--color-text-secondary);
    border-left: 1px solid var(--color-border); cursor: pointer;
    appearance: none; min-width: 160px;
}
[dir="rtl"] .hero-search-select { border-left: none; border-right: 1px solid var(--color-border); }
.hero-search-btn { border-radius: var(--radius-md); padding: 14px 28px; white-space: nowrap; }

@media (max-width: 640px) {
    .hero-search-inner { flex-wrap: wrap; }
    .hero-search-select { border-left: none; border-top: 1px solid var(--color-border); width: 100%; }
    .hero-search-btn { width: 100%; }
    .search-icon { display: none; }
}

/* Stats */
.hero-stats {
    display: flex; justify-content: center; align-items: center;
    gap: var(--space-xl); flex-wrap: wrap;
}
.hero-stat { text-align: center; }
.hero-stat-number {
    display: block; font-family: var(--font-display);
    font-size: var(--text-2xl); font-weight: 800;
    color: var(--color-primary);
}
.hero-stat-label { font-size: var(--text-sm); color: var(--color-text-muted); }
.hero-stat-divider { width: 1px; height: 40px; background: var(--color-border); }
@media (max-width: 640px) { .hero-stat-divider { display: none; } }

/* Services Grid */
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: var(--space-lg);
}
.service-card {
    display: flex; flex-direction: column; align-items: center;
    padding: var(--space-xl) var(--space-md);
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    text-decoration: none;
    transition: all var(--transition-normal);
    cursor: pointer;
}
.service-card:hover {
    border-color: var(--color-primary);
    box-shadow: var(--shadow-md);
    transform: translateY(-4px);
}
.service-icon {
    width: 52px; height: 52px;
    display: flex; align-items: center; justify-content: center;
    background: var(--color-primary-bg);
    border-radius: var(--radius-md);
    margin-bottom: var(--space-md);
    color: var(--color-primary);
}
.service-icon svg { width: 28px; height: 28px; }
.service-name {
    font-size: var(--text-sm); font-weight: 600;
    color: var(--color-text); text-align: center;
}

/* How It Works */
.steps-grid {
    display: flex; align-items: flex-start; justify-content: center;
    gap: var(--space-md); margin-top: var(--space-2xl);
}
.step-card {
    text-align: center; flex: 1; max-width: 280px;
    padding: var(--space-xl);
}
.step-number {
    width: 56px; height: 56px; margin: 0 auto var(--space-lg);
    display: flex; align-items: center; justify-content: center;
    background: var(--color-primary);
    color: white; font-size: var(--text-xl); font-weight: 800;
    border-radius: var(--radius-full);
}
.step-title { font-size: var(--text-lg); font-weight: 700; margin-bottom: var(--space-sm); color: var(--color-text); }
.step-desc { font-size: var(--text-sm); color: var(--color-text-secondary); line-height: var(--leading-relaxed); }
.step-connector { display: flex; align-items: center; padding-top: 44px; }
@media (max-width: 768px) {
    .steps-grid { flex-direction: column; align-items: center; }
    .step-connector { display: none; }
}

/* CTA */
.cta-card {
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
    border-radius: var(--radius-xl); padding: var(--space-3xl);
    text-align: center;
}
.cta-title { font-family: var(--font-display); font-size: var(--text-3xl); font-weight: 800; color: white; margin-bottom: var(--space-md); }
[dir="rtl"] .cta-title { font-family: var(--font-arabic); }
.cta-subtitle { font-size: var(--text-lg); color: rgba(255,255,255,0.85); margin-bottom: var(--space-xl); max-width: 500px; margin-left: auto; margin-right: auto; }
.cta-buttons { display: flex; justify-content: center; gap: var(--space-md); flex-wrap: wrap; }
.cta-buttons .btn-outline { border-color: white; color: white; }
.cta-buttons .btn-outline:hover { background: white; color: var(--color-primary); }
.cta-buttons .btn-primary { background: white; color: var(--color-primary); }
.cta-buttons .btn-primary:hover { background: rgba(255,255,255,0.9); }
</style>
