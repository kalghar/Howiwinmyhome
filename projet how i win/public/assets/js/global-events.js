/**
 * GLOBAL-EVENTS.JS - HOW I WIN MY HOME V1
 * ========================================
 *
 * FICHIER JAVASCRIPT DES ÉVÉNEMENTS GLOBAUX
 * Gestion des événements globaux et des interactions
 *
 * AUTEUR : How I Win My Home Team
 * VERSION : 1.0.0
 * DATE : 2025-08-17
 * ========================================
 */

// ========================================
// GESTIONNAIRE DES ÉVÉNEMENTS GLOBAUX
// ========================================

class GlobalEventManager {
    constructor() {
        this.events = new Map();
        this.init();
    }
    
    init() {
        this.setupGlobalEvents();
        this.setupKeyboardShortcuts();
        this.setupScrollEvents();
        this.setupResizeEvents();
    }
    
    setupGlobalEvents() {
        // Utilisation de la délégation d'événements pour de meilleures performances
        document.addEventListener('click', this.handleGlobalClick.bind(this), { passive: true });
        document.addEventListener('keydown', this.handleGlobalKeydown.bind(this), { passive: false });
        document.addEventListener('submit', this.handleGlobalSubmit.bind(this), { passive: false });
    }
    
    handleGlobalClick(e) {
        // Gestion des liens externes
        if (e.target.tagName === 'A' && e.target.hostname !== window.location.hostname) {
            e.target.setAttribute('target', '_blank');
            e.target.setAttribute('rel', 'noopener noreferrer');
        }
        
        // Gestion des liens internes avec ancres
        if (e.target.tagName === 'A' && e.target.hash) {
            e.preventDefault();
            this.smoothScrollToAnchor(e.target.hash);
        }
        
        // Gestion des boutons de retour en haut
        if (e.target.classList.contains('back-to-top')) {
            e.preventDefault();
            this.scrollToTop();
        }
    }
    
    handleGlobalKeydown(e) {
        // Raccourcis clavier globaux
        if (e.ctrlKey || e.metaKey) {
            switch (e.key) {
                case 'k':
                    e.preventDefault();
                    this.focusSearch();
                    break;
                case 's':
                    e.preventDefault();
                    this.savePage();
                    break;
            }
        }
        
        // Navigation au clavier
        if (e.key === 'Tab') {
            this.handleTabNavigation(e);
        }
    }
    
    handleGlobalSubmit(e) {
        // Validation globale des formulaires gérée par app.js FormManager
        // Pas de traitement supplémentaire nécessaire ici
    }
    
    setupKeyboardShortcuts() {
        // Raccourcis clavier pour l'accessibilité
        document.addEventListener('keydown', (e) => {
            // Alt + 1 : Aller à l'accueil
            if (e.altKey && e.key === '1') {
                e.preventDefault();
                window.location.href = '/';
            }
            
            // Alt + 2 : Aller aux annonces
            if (e.altKey && e.key === '2') {
                e.preventDefault();
                window.location.href = '/listings';
            }
            
            // Alt + 3 : Aller au contact
            if (e.altKey && e.key === '3') {
                e.preventDefault();
                window.location.href = '/contact';
            }
            
            // Alt + H : Aide
            if (e.altKey && e.key === 'h') {
                e.preventDefault();
                this.showHelp();
            }
        });
    }
    
    setupScrollEvents() {
        let scrollTimeout;
        
        window.addEventListener('scroll', () => {
            // Gestion du bouton "retour en haut"
            this.handleScrollToTop();
            
            // Gestion de la navigation sticky
            this.handleStickyNavigation();
            
            // Détection de la fin de page
            this.handleEndOfPage();
            
            // Optimisation des performances de scroll
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                this.handleScrollEnd();
            }, 150);
        });
    }
    
    setupResizeEvents() {
        let resizeTimeout;
        
        window.addEventListener('resize', () => {
            // Optimisation des performances de redimensionnement
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                this.handleResizeEnd();
            }, 250);
        });
    }
    
    // ========================================
    // MÉTHODES UTILITAIRES
    // ========================================
    
    smoothScrollToAnchor(anchor) {
        const target = document.querySelector(anchor);
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }
    
    scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
    
    focusSearch() {
        const searchInput = document.querySelector('input[type="search"], .search-input');
        if (searchInput) {
            searchInput.focus();
        }
    }
    
    savePage() {
        if (navigator.share) {
            navigator.share({
                title: document.title,
                url: window.location.href
            });
        } else {
            // Fallback pour les navigateurs qui ne supportent pas l'API Share
            this.copyToClipboard(window.location.href);
        }
    }
    
    async copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            // Plus de notifications - gestion silencieuse
        } catch (err) {
            console.error('Erreur lors de la copie:', err);
        }
    }
    
    handleTabNavigation(e) {
        // Gestion de la navigation au clavier dans les modales
        const activeModal = document.querySelector('.modal-overlay.active');
        if (activeModal) {
            const focusableElements = activeModal.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            
            if (focusableElements.length > 0) {
                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];
                
                if (e.shiftKey && document.activeElement === firstElement) {
                    e.preventDefault();
                    lastElement.focus();
                } else if (!e.shiftKey && document.activeElement === lastElement) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }
        }
    }
    
    
    showHelp() {
        // Gestion silencieuse des raccourcis clavier
    }
    
    handleScrollToTop() {
        const backToTopBtn = document.querySelector('.back-to-top');
        if (backToTopBtn) {
            if (window.pageYOffset > 300) {
                backToTopBtn.style.display = 'block';
            } else {
                backToTopBtn.style.display = 'none';
            }
        }
    }
    
    handleStickyNavigation() {
        const header = document.querySelector('.main-header');
        if (header) {
            if (window.pageYOffset > 100) {
                header.classList.add('sticky');
            } else {
                header.classList.remove('sticky');
            }
        }
    }
    
    handleEndOfPage() {
        const scrollTop = window.pageYOffset;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        
        if (scrollTop + windowHeight >= documentHeight - 100) {
            this.loadMoreContent();
        }
    }
    
    handleScrollEnd() {
        // Actions à effectuer après la fin du scroll
        this.updateActiveNavigation();
    }
    
    handleResizeEnd() {
        // Actions à effectuer après la fin du redimensionnement
        this.updateLayout();
    }
    
    updateActiveNavigation() {
        // Mettre à jour la navigation active selon la position de scroll
        const sections = document.querySelectorAll('section[id], [id]');
        const navLinks = document.querySelectorAll('.nav-link');
        
        let currentSection = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            
            if (window.pageYOffset >= sectionTop - 200) {
                currentSection = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${currentSection}`) {
                link.classList.add('active');
            }
        });
    }
    
    updateLayout() {
        // Mettre à jour la mise en page selon la taille de l'écran
        const isMobile = window.innerWidth <= 768;
        const isTablet = window.innerWidth <= 1024;
        
        document.body.classList.toggle('mobile', isMobile);
        document.body.classList.toggle('tablet', isTablet && !isMobile);
        document.body.classList.toggle('desktop', !isMobile && !isTablet);
    }
    
    loadMoreContent() {
        // Charger plus de contenu si nécessaire (pagination infinie)
        const loadMoreBtn = document.querySelector('.load-more');
        if (loadMoreBtn && !loadMoreBtn.disabled) {
            loadMoreBtn.click();
        }
    }
}

// ========================================
// GESTIONNAIRE DES ERREURS GLOBALES
// ========================================

class GlobalErrorHandler {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupErrorHandling();
        this.setupUnhandledRejectionHandling();
    }
    
    setupErrorHandling() {
        window.addEventListener('error', (e) => {
            this.handleError(e.error || e.message, e.filename, e.lineno);
        });
    }
    
    setupUnhandledRejectionHandling() {
        window.addEventListener('unhandledrejection', (e) => {
            this.handleUnhandledRejection(e.reason);
        });
    }
    
    handleError(message, filename, lineno) {
        // Gestion d'erreur silencieuse pour la production
        this.reportError(message, filename, lineno);
    }
    
    // Méthode pour déterminer si une erreur est critique
    isCriticalError(message, filename) {
        // Liste des erreurs considérées comme critiques
        const criticalErrors = [
            'NetworkError',
            'TimeoutError',
            'DatabaseError',
            'AuthenticationError',
            'PermissionError'
        ];
        
        // Vérifier si le message contient une erreur critique
        return criticalErrors.some(errorType => 
            message && message.includes(errorType)
        );
    }
    
    handleUnhandledRejection(reason) {
        // Gestion d'erreur silencieuse pour la production
        this.reportError('Promesse rejetée: ' + reason);
    }
    
    reportError(message, filename, lineno) {
        // Ici vous pouvez envoyer l'erreur à un service de monitoring
        // comme Sentry, LogRocket, etc.
        if (window.gtag) {
            window.gtag('event', 'exception', {
                description: message,
                fatal: false
            });
        }
    }
}

// ========================================
// GESTIONNAIRE DES PERFORMANCES GLOBALES
// ========================================

class GlobalPerformanceManager {
    constructor() {
        this.metrics = {};
        this.init();
    }
    
    init() {
        this.measureCoreWebVitals();
        this.setupPerformanceMonitoring();
    }
    
    measureCoreWebVitals() {
        // Mesurer les Core Web Vitals
        if ('PerformanceObserver' in window) {
            // Largest Contentful Paint (LCP)
            const lcpObserver = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                const lastEntry = entries[entries.length - 1];
                this.metrics.lcp = lastEntry.startTime;
            });
            lcpObserver.observe({ entryTypes: ['largest-contentful-paint'] });
            
            // First Input Delay (FID)
            const fidObserver = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                entries.forEach(entry => {
                    this.metrics.fid = entry.processingStart - entry.startTime;
                });
            });
            fidObserver.observe({ entryTypes: ['first-input'] });
            
            // Cumulative Layout Shift (CLS)
            let clsValue = 0;
            const clsObserver = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                entries.forEach(entry => {
                    if (!entry.hadRecentInput) {
                        clsValue += entry.value;
                        this.metrics.cls = clsValue;
                    }
                });
            });
            clsObserver.observe({ entryTypes: ['layout-shift'] });
        }
    }
    
    setupPerformanceMonitoring() {
        // Mesurer le temps de chargement de la page
        window.addEventListener('load', () => {
            setTimeout(() => {
                this.measurePageLoadTime();
            }, 0);
        });
        
        // Mesurer les interactions utilisateur
        this.measureUserInteractions();
    }
    
    measurePageLoadTime() {
        if (window.performance && window.performance.timing) {
            const timing = window.performance.timing;
            
            this.metrics.pageLoad = timing.loadEventEnd - timing.navigationStart;
            this.metrics.domReady = timing.domContentLoadedEventEnd - timing.navigationStart;
            this.metrics.firstPaint = timing.responseStart - timing.navigationStart;
        }
    }
    
    measureUserInteractions() {
        // Mesurer le temps de réponse aux interactions
        let lastInteractionTime = Date.now();
        
        ['click', 'keydown', 'scroll', 'resize'].forEach(eventType => {
            document.addEventListener(eventType, () => {
                const currentTime = Date.now();
                const responseTime = currentTime - lastInteractionTime;
                
                if (!this.metrics.interactionResponse) {
                    this.metrics.interactionResponse = [];
                }
                
                this.metrics.interactionResponse.push({
                    type: eventType,
                    responseTime,
                    timestamp: currentTime
                });
                
                lastInteractionTime = currentTime;
            });
        });
    }
    
    getMetrics() {
        return this.metrics;
    }
    
    exportMetrics() {
        return {
            timestamp: new Date().toISOString(),
            url: window.location.href,
            userAgent: navigator.userAgent,
            metrics: this.metrics
        };
    }
}

// ========================================
// INITIALISATION
// ========================================

document.addEventListener('DOMContentLoaded', () => {
    // Initialiser les gestionnaires globaux
    window.globalEventManager = new GlobalEventManager();
    window.globalErrorHandler = new GlobalErrorHandler();
    window.globalPerformanceManager = new GlobalPerformanceManager();
});

// ========================================
// EXPORT DES CLASSES POUR UTILISATION EXTERNE
// ========================================

if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        GlobalEventManager,
        GlobalErrorHandler,
        GlobalPerformanceManager
    };
}
