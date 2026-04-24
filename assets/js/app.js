/**
 * Craft Platform - Main JavaScript
 * Theme toggle, mobile menu, user dropdown, flash messages
 */
document.addEventListener('DOMContentLoaded', () => {
    initThemeToggle();
    initMobileMenu();
    initUserDropdown();
    initFlashMessages();
});

/* ============================================
   Theme Toggle (Light/Dark)
   ============================================ */
function initThemeToggle() {
    const btn = document.getElementById('themeToggle');
    const sunIcon = document.getElementById('sunIcon');
    const moonIcon = document.getElementById('moonIcon');
    if (!btn) return;

    btn.addEventListener('click', () => {
        const html = document.documentElement;
        const current = html.getAttribute('data-theme');
        const next = current === 'dark' ? 'light' : 'dark';

        html.setAttribute('data-theme', next);
        document.cookie = `theme=${next};path=/;max-age=${365*24*3600}`;

        // Update session via AJAX
        fetch(`${APP_URL}/theme/${next}`).catch(() => {});

        // Toggle icons
        if (sunIcon && moonIcon) {
            sunIcon.style.display = next === 'dark' ? 'none' : '';
            moonIcon.style.display = next === 'dark' ? '' : 'none';
        }
    });
}

/* ============================================
   Mobile Menu Toggle
   ============================================ */
function initMobileMenu() {
    const btn = document.getElementById('mobileMenuBtn');
    const menu = document.getElementById('navMenu');
    if (!btn || !menu) return;

    btn.addEventListener('click', () => {
        menu.classList.toggle('open');
    });

    // Close on outside click
    document.addEventListener('click', (e) => {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove('open');
        }
    });
}

/* ============================================
   User Dropdown Menu
   ============================================ */
function initUserDropdown() {
    const trigger = document.getElementById('userMenuTrigger');
    const dropdown = document.getElementById('userDropdown');
    if (!trigger || !dropdown) return;

    trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('open');
    });

    document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
        }
    });
}

/* ============================================
   Auto-hide Flash Messages
   ============================================ */
function initFlashMessages() {
    const flashes = document.querySelectorAll('.alert');
    flashes.forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-10px)';
            setTimeout(() => el.remove(), 300);
        }, 5000);
    });
}

/* ============================================
   Global APP_URL for JS
   ============================================ */
const APP_URL = document.querySelector('meta[name="app-url"]')?.content || '';
