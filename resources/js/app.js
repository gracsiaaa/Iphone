import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuButton = document.querySelector('[data-mobile-menu-toggle]');

    const setMobileMenu = (open) => {
        if (!mobileMenu || !mobileMenuButton) return;

        mobileMenu.classList.toggle('hidden', !open);
        mobileMenuButton.setAttribute('aria-expanded', String(open));
        mobileMenuButton.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
    };

    mobileMenuButton?.addEventListener('click', () => {
        setMobileMenu(mobileMenu?.classList.contains('hidden'));
    });

    mobileMenu?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setMobileMenu(false));
    });

    const adminSidebar = document.getElementById('admin-sidebar');
    const adminOverlay = document.getElementById('admin-sidebar-overlay');
    const adminToggle = document.querySelector('[data-admin-sidebar-toggle]');
    const adminCloseButtons = document.querySelectorAll('[data-admin-sidebar-close]');

    const setAdminSidebar = (open) => {
        if (!adminSidebar || !adminOverlay || !adminToggle) return;

        adminSidebar.classList.toggle('is-open', open);
        adminOverlay.classList.toggle('is-open', open);
        adminToggle.setAttribute('aria-expanded', String(open));
        document.body.classList.toggle('overflow-hidden', open && window.innerWidth < 1024);
    };

    adminToggle?.addEventListener('click', () => {
        setAdminSidebar(!adminSidebar?.classList.contains('is-open'));
    });

    adminCloseButtons.forEach((button) => {
        button.addEventListener('click', () => setAdminSidebar(false));
    });

    adminSidebar?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 1024) setAdminSidebar(false);
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;
        setMobileMenu(false);
        setAdminSidebar(false);
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) setMobileMenu(false);
        if (window.innerWidth >= 1024) setAdminSidebar(false);
    });

    window.setTimeout(() => {
        document.querySelectorAll('[data-flash]').forEach((element) => element.remove());
    }, 5000);
});
