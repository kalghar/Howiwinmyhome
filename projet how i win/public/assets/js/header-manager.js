/**
 * HEADER-MANAGER.JS - HOW I WIN MY HOME V1
 * ========================================
 *
 * FICHIER JAVASCRIPT POUR LA GESTION DU HEADER
 * Gestion du menu utilisateur, menu mobile et navigation
 *
 * AUTEUR : How I Win My Home Team
 * VERSION : 1.0.0
 * DATE : 2024-12-09
 * ========================================
 */

// ========================================
// GESTIONNAIRE DU HEADER
// ========================================

class HeaderManager {
    constructor() {
        this.isMenuOpen = false;
        this.isUserMenuOpen = false;
        this.init();
    }

    init() {
        this.setupMobileMenu();
        this.setupUserMenu();
        this.setupNavigation();
        this.setupScrollEffects();
        this.setupKeyboardNavigation();
    }

    // ========================================
    // MENU MOBILE
    // ========================================

    setupMobileMenu() {
        const mobileToggle = document.querySelector('.mobile-menu-toggle');
        const headerNav = document.querySelector('.header-nav');
        const headerActions = document.querySelector('.header-actions');

        if (!mobileToggle || !headerNav) return;

        // Toggle du menu mobile
        mobileToggle.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggleMobileMenu();
        });

        // Fermer le menu en cliquant à l'extérieur
        document.addEventListener('click', (e) => {
            if (this.isMenuOpen && !headerNav.contains(e.target) && !mobileToggle.contains(e.target)) {
                this.closeMobileMenu();
            }
        });

        // Fermer le menu en appuyant sur Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isMenuOpen) {
                this.closeMobileMenu();
            }
        });

        // Gestion du redimensionnement de la fenêtre
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768 && this.isMenuOpen) {
                this.closeMobileMenu();
            }
        });
    }

    toggleMobileMenu() {
        const mobileToggle = document.querySelector('.mobile-menu-toggle');
        const headerNav = document.querySelector('.header-nav');
        const headerActions = document.querySelector('.header-actions');

        if (this.isMenuOpen) {
            this.closeMobileMenu();
        } else {
            this.openMobileMenu();
        }
    }

    openMobileMenu() {
        const mobileToggle = document.querySelector('.mobile-menu-toggle');
        const headerNav = document.querySelector('.header-nav');
        const headerActions = document.querySelector('.header-actions');

        this.isMenuOpen = true;

        // Animation du bouton hamburger
        mobileToggle.classList.add('active');
        mobileToggle.setAttribute('aria-expanded', 'true');

        // Affichage du menu
        headerNav.classList.add('mobile-open');
        if (headerActions) {
            headerActions.classList.add('mobile-open');
        }

        // Empêcher le scroll du body
        document.body.style.overflow = 'hidden';

        // Focus sur le premier lien du menu
        const firstLink = headerNav.querySelector('.nav-link');
        if (firstLink) {
            setTimeout(() => firstLink.focus(), 100);
        }
    }

    closeMobileMenu() {
        const mobileToggle = document.querySelector('.mobile-menu-toggle');
        const headerNav = document.querySelector('.header-nav');
        const headerActions = document.querySelector('.header-actions');

        this.isMenuOpen = false;

        // Animation du bouton hamburger
        mobileToggle.classList.remove('active');
        mobileToggle.setAttribute('aria-expanded', 'false');

        // Masquage du menu
        headerNav.classList.remove('mobile-open');
        if (headerActions) {
            headerActions.classList.remove('mobile-open');
        }

        // Restaurer le scroll du body
        document.body.style.overflow = '';
    }

    // ========================================
    // MENU UTILISATEUR
    // ========================================

    setupUserMenu() {
        const userMenuToggle = document.querySelector('.user-menu-toggle-mascabanids');
        const userDropdown = document.querySelector('.user-dropdown-mascabanids');

        if (!userMenuToggle || !userDropdown) return;

        // Toggle du menu utilisateur
        userMenuToggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.toggleUserMenu();
        });

        // Fermer le menu en cliquant à l'extérieur
        document.addEventListener('click', (e) => {
            if (this.isUserMenuOpen && !userDropdown.contains(e.target) && !userMenuToggle.contains(e.target)) {
                this.closeUserMenu();
            }
        });

        // Fermer le menu en appuyant sur Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isUserMenuOpen) {
                this.closeUserMenu();
            }
        });

        // Gestion des liens du menu
        const dropdownLinks = userDropdown.querySelectorAll('.dropdown-link-mascabanids');
        dropdownLinks.forEach(link => {
            link.addEventListener('click', () => {
                this.closeUserMenu();
            });
        });
    }

    toggleUserMenu() {
        if (this.isUserMenuOpen) {
            this.closeUserMenu();
        } else {
            this.openUserMenu();
        }
    }

    openUserMenu() {
        const userMenuToggle = document.querySelector('.user-menu-toggle-mascabanids');
        const userDropdown = document.querySelector('.user-dropdown-mascabanids');

        this.isUserMenuOpen = true;

        // Animation du bouton
        userMenuToggle.classList.add('active');
        userMenuToggle.setAttribute('aria-expanded', 'true');

        // Affichage du menu
        userDropdown.classList.add('active');

        // Focus sur le premier lien du menu
        const firstLink = userDropdown.querySelector('.dropdown-link-mascabanids');
        if (firstLink) {
            setTimeout(() => firstLink.focus(), 100);
        }
    }

    closeUserMenu() {
        const userMenuToggle = document.querySelector('.user-menu-toggle-mascabanids');
        const userDropdown = document.querySelector('.user-dropdown-mascabanids');

        this.isUserMenuOpen = false;

        // Animation du bouton
        userMenuToggle.classList.remove('active');
        userMenuToggle.setAttribute('aria-expanded', 'false');

        // Masquage du menu
        userDropdown.classList.remove('active');
    }

    // ========================================
    // NAVIGATION
    // ========================================

    setupNavigation() {
        const navLinks = document.querySelectorAll('.nav-link');

        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                // Fermer le menu mobile si ouvert
                if (this.isMenuOpen) {
                    this.closeMobileMenu();
                }

                // Animation de clic
                this.animateNavLink(link);
            });
        });

        // Gestion des liens actifs
        this.updateActiveNavigation();
    }

    animateNavLink(link) {
        link.classList.add('clicked');
        setTimeout(() => {
            link.classList.remove('clicked');
        }, 200);
    }

    updateActiveNavigation() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');

        navLinks.forEach(link => {
            link.classList.remove('active');

            const linkPath = new URL(link.href).pathname;
            if (linkPath === currentPath ||
                (currentPath.startsWith(linkPath) && linkPath !== '/')) {
                link.classList.add('active');
            }
        });
    }

    // ========================================
    // EFFETS DE SCROLL
    // ========================================

    setupScrollEffects() {
        let lastScrollTop = 0;
        const header = document.querySelector('.main-header');

        if (!header) return;

        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            // Header sticky
            if (scrollTop > 100) {
                header.classList.add('sticky');
            } else {
                header.classList.remove('sticky');
            }

            // Auto-hide header sur scroll vers le bas (mobile uniquement)
            if (window.innerWidth <= 768) {
                if (scrollTop > lastScrollTop && scrollTop > 200) {
                    header.classList.add('header-hidden');
                } else {
                    header.classList.remove('header-hidden');
                }
            }

            lastScrollTop = scrollTop;
        });
    }

    // ========================================
    // NAVIGATION CLAVIER
    // ========================================

    setupKeyboardNavigation() {
        // Navigation au clavier dans le menu mobile
        document.addEventListener('keydown', (e) => {
            if (!this.isMenuOpen) return;

            const navLinks = document.querySelectorAll('.nav-link');
            const activeElement = document.activeElement;
            const currentIndex = Array.from(navLinks).indexOf(activeElement);

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    const nextIndex = (currentIndex + 1) % navLinks.length;
                    navLinks[nextIndex].focus();
                    break;

                case 'ArrowUp':
                    e.preventDefault();
                    const prevIndex = currentIndex > 0 ? currentIndex - 1 : navLinks.length - 1;
                    navLinks[prevIndex].focus();
                    break;

                case 'Home':
                    e.preventDefault();
                    navLinks[0].focus();
                    break;

                case 'End':
                    e.preventDefault();
                    navLinks[navLinks.length - 1].focus();
                    break;
            }
        });

        // Navigation au clavier dans le menu utilisateur
        document.addEventListener('keydown', (e) => {
            if (!this.isUserMenuOpen) return;

            const dropdownLinks = document.querySelectorAll('.dropdown-link-mascabanids');
            const activeElement = document.activeElement;
            const currentIndex = Array.from(dropdownLinks).indexOf(activeElement);

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    const nextIndex = (currentIndex + 1) % dropdownLinks.length;
                    dropdownLinks[nextIndex].focus();
                    break;

                case 'ArrowUp':
                    e.preventDefault();
                    const prevIndex = currentIndex > 0 ? currentIndex - 1 : dropdownLinks.length - 1;
                    dropdownLinks[prevIndex].focus();
                    break;
            }
        });
    }

    // ========================================
    // MÉTHODES UTILITAIRES
    // ========================================

    // Méthode pour fermer tous les menus
    closeAllMenus() {
        if (this.isMenuOpen) {
            this.closeMobileMenu();
        }
        if (this.isUserMenuOpen) {
            this.closeUserMenu();
        }
    }

    // Méthode pour obtenir l'état des menus
    getMenuStates() {
        return {
            mobileMenu: this.isMenuOpen,
            userMenu: this.isUserMenuOpen
        };
    }
}

// ========================================
// GESTIONNAIRE DES STATISTIQUES DU FOOTER
// ========================================

class FooterStatsManager {
    constructor() {
        this.init();
    }

    init() {
        this.loadStats();
        this.setupRefresh();
    }

    async loadStats() {
        try {
            // Simulation de chargement des statistiques
            const stats = {
                total_listings: 89,
                total_winners: 156
            };

            this.updateStatsDisplay(stats);
        } catch (error) {
            console.error('Erreur lors du chargement des statistiques du footer:', error);
        }
    }

    updateStatsDisplay(stats) {
        const totalListingsElement = document.getElementById('total-listings');
        const totalWinnersElement = document.getElementById('total-winners');

        if (totalListingsElement) {
            totalListingsElement.textContent = stats.total_listings.toLocaleString();
        }

        if (totalWinnersElement) {
            totalWinnersElement.textContent = stats.total_winners.toLocaleString();
        }
    }

    setupRefresh() {
        // Rafraîchir les stats toutes les 10 minutes
        setInterval(() => {
            this.loadStats();
        }, 10 * 60 * 1000);
    }
}

// ========================================
// INITIALISATION
// ========================================

document.addEventListener('DOMContentLoaded', () => {
    // Initialiser les gestionnaires du header
    window.headerManager = new HeaderManager();
    window.footerStatsManager = new FooterStatsManager();

    console.log('🎯 Gestionnaires du header initialisés');
});

// ========================================
// GESTION DES ERREURS
// ========================================

window.addEventListener('error', (e) => {
    console.error('❌ Erreur dans le gestionnaire du header:', e.error);
});

// ========================================
// EXPORT DES CLASSES
// ========================================

if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        HeaderManager,
        FooterStatsManager
    };
}
