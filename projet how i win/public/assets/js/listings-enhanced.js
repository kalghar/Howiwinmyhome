/**
 * LISTINGS-ENHANCED.JS - HOW I WIN MY HOME V1
 * ========================================
 *
 * FICHIER JAVASCRIPT DE LA PAGE DES ANNONCES
 * Parfaitement aligné avec la vue PHP et le CSS
 * Fonctionnalités optimisées et modernes
 *
 * AUTEUR : How I Win My Home Team
 * VERSION : 2.0.0
 * DATE : 2025-08-17
 * ========================================
 */

// ========================================
// INITIALISATION
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Listings Enhanced JS v2.0 initialisé');
    
    // Initialisation des composants
    initFilters();
    initListingCards();
    initProgressBars();
    initAnimations();
    initResponsive();
    initAccessibility();
});

// ========================================
// GESTION DES FILTRES
// ========================================

function initFilters() {
    const filterInputs = document.querySelectorAll('.filter-input');
    const filterSelects = document.querySelectorAll('.filter-select');
    const filterToggle = document.querySelector('.filter-toggle');
    const filtersSection = document.querySelector('.filters-section');
    
    // Toggle des filtres sur mobile
    if (filterToggle && filtersSection) {
        filterToggle.addEventListener('click', function() {
            const isVisible = filtersSection.style.display !== 'none';
            filtersSection.style.display = isVisible ? 'none' : 'block';
            filterToggle.innerHTML = isVisible ? 
                '<i class="fas fa-filter"></i> Afficher les filtres' : 
                '<i class="fas fa-times"></i> Masquer les filtres';
        });
        
        // Masquer les filtres par défaut sur mobile
        if (window.innerWidth <= 768) {
            filtersSection.style.display = 'none';
            filterToggle.innerHTML = '<i class="fas fa-filter"></i> Afficher les filtres';
        }
    }
    
    // Validation en temps réel des filtres
    filterInputs.forEach(input => {
        input.addEventListener('input', function() {
            validateFilterInput(this);
        });
        
        input.addEventListener('blur', function() {
            validateFilterInput(this);
        });
        
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyFilters();
            }
        });
    });
    
    // Gestion des selects
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Auto-application des filtres sur changement
            if (this.value) {
                applyFilters();
            }
        });
    });
}

function validateFilterInput(input) {
    const value = input.value.trim();
    const type = input.type;
    
    // Supprimer les classes d'erreur précédentes
    input.classList.remove('error', 'success');
    
    // Validation selon le type
    if (type === 'number') {
        if (value && !isNaN(value) && parseFloat(value) >= 0) {
            input.classList.add('success');
        } else if (value) {
            input.classList.add('error');
        }
    } else if (type === 'text' || type === 'search') {
        if (value.length >= 2) {
            input.classList.add('success');
        } else if (value.length === 1) {
            input.classList.add('error');
        }
    }
}

function applyFilters() {
    const filterInputs = document.querySelectorAll('.filter-input');
    const filterSelects = document.querySelectorAll('.filter-select');
    const filters = {};
    
    // Collecter les valeurs des filtres
    filterInputs.forEach(input => {
        if (input.value.trim()) {
            filters[input.name || input.id] = input.value.trim();
        }
    });
    
    filterSelects.forEach(select => {
        if (select.value) {
            filters[select.name || select.id] = select.value;
        }
    });
    
    // Simulation de l'application des filtres
    console.log('🔍 Filtres appliqués:', filters);
    
    // Animation de chargement
    showLoadingState();
    
    // Simuler un délai de traitement
    setTimeout(() => {
        hideLoadingState();
        showSuccessMessage('Filtres appliqués avec succès !');
        
        // Ici, vous pourriez faire un appel AJAX pour filtrer les résultats
        // filterListings(filters);
    }, 800);
}

// ========================================
// GESTION DES CARTES D'ANNONCES
// ========================================

function initListingCards() {
    const listingCards = document.querySelectorAll('.listing-card');
    
    listingCards.forEach(card => {
        // Animation au survol
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
        
        // Clic sur la carte
        card.addEventListener('click', function(e) {
            // Éviter le clic si on clique sur un bouton ou lien
            if (e.target.tagName === 'BUTTON' || e.target.tagName === 'A' || 
                e.target.closest('button') || e.target.closest('a')) {
                return;
            }
            
            // Navigation vers la page de détail
            const listingId = this.dataset.listingId;
            if (listingId) {
                window.location.href = `/listings/view?id=${listingId}`;
            }
        });
        
        // Gestion du clavier pour l'accessibilité
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
        
        // Rendre la carte focusable
        card.setAttribute('tabindex', '0');
        card.setAttribute('role', 'button');
        card.setAttribute('aria-label', 'Voir les détails de cette annonce');
    });
}

// ========================================
// BARRES DE PROGRESSION
// ========================================

function initProgressBars() {
    const progressBars = document.querySelectorAll('.progress-fill');
    
    progressBars.forEach(bar => {
        const targetWidth = bar.dataset.width || '0';
        
        // Animation de la barre de progression
        setTimeout(() => {
            bar.style.width = targetWidth + '%';
        }, 500);
        
        // Ajouter une classe pour l'animation
        bar.classList.add('animate-progress');
    });
}

// ========================================
// GESTION DES ANIMATIONS
// ========================================

function initAnimations() {
    // Animation des statistiques
    animateStats();
    
    // Animation des cartes au scroll
    initScrollAnimations();
    
    // Animation des filtres
    animateFilters();
}

function animateStats() {
    const statItems = document.querySelectorAll('.stat-item');
    
    statItems.forEach((item, index) => {
        setTimeout(() => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                item.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 100);
        }, index * 200);
    });
}

function animateFilters() {
    const filterGroups = document.querySelectorAll('.filter-group');
    
    filterGroups.forEach((group, index) => {
        group.style.opacity = '0';
        group.style.transform = 'translateX(-20px)';
        
        setTimeout(() => {
            group.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
            group.style.opacity = '1';
            group.style.transform = 'translateX(0)';
        }, index * 150);
    });
}

function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observer les cartes d'annonces
    const listingCards = document.querySelectorAll('.listing-card');
    listingCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        observer.observe(card);
    });
}

// ========================================
// GESTION RESPONSIVE
// ========================================

function initResponsive() {
    // Gestion du redimensionnement de la fenêtre
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            handleResize();
        }, 250);
    });
    
    // Gestion initiale
    handleResize();
}

function handleResize() {
    const filterToggle = document.querySelector('.filter-toggle');
    const filtersSection = document.querySelector('.filters-section');
    
    if (window.innerWidth <= 768) {
        // Mode mobile
        if (filterToggle && filtersSection) {
            filterToggle.style.display = 'block';
            if (filtersSection.style.display === '') {
                filtersSection.style.display = 'none';
            }
        }
        
        // Ajuster la grille des annonces
        adjustGridLayout();
    } else {
        // Mode desktop
        if (filterToggle && filtersSection) {
            filterToggle.style.display = 'none';
            filtersSection.style.display = 'block';
        }
    }
}

function adjustGridLayout() {
    const listingsGrid = document.querySelector('.listings-grid');
    if (listingsGrid) {
        const cardWidth = window.innerWidth <= 480 ? '100%' : '1fr';
        listingsGrid.style.gridTemplateColumns = `repeat(auto-fill, minmax(${cardWidth}, 1fr))`;
    }
}

// ========================================
// ACCESSIBILITÉ
// ========================================

function initAccessibility() {
    // Gestion des raccourcis clavier
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + F pour ouvrir les filtres
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            const filtersSection = document.querySelector('.filters-section');
            if (filtersSection) {
                filtersSection.scrollIntoView({ behavior: 'smooth' });
                const firstInput = filtersSection.querySelector('input');
                if (firstInput) {
                    firstInput.focus();
                }
            }
        }
        
        // Échap pour fermer les modales (si elles existent)
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });
    
    // Amélioration de la navigation au clavier
    const focusableElements = document.querySelectorAll(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );
    
    focusableElements.forEach(element => {
        element.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });
}

// ========================================
// UTILITAIRES
// ========================================

function showLoadingState() {
    const submitBtn = document.querySelector('.filters-form button[type="submit"]');
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Application...';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.7';
    }
}

function hideLoadingState() {
    const submitBtn = document.querySelector('.filters-form button[type="submit"]');
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="fas fa-search"></i> Appliquer les filtres';
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
    }
}

function showSuccessMessage(message) {
    // Créer un toast de succès
    const toast = document.createElement('div');
    toast.className = 'success-toast';
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'polite');
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--success-color);
        color: white;
        padding: var(--spacing-md) var(--spacing-lg);
        border-radius: var(--border-radius-md);
        box-shadow: var(--shadow-lg);
        z-index: 1000;
        animation: slideInRight 0.3s ease-out;
        max-width: 300px;
        word-wrap: break-word;
    `;
    
    document.body.appendChild(toast);
    
    // Supprimer le toast après 3 secondes
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

function closeAllModals() {
    // Fermer toutes les modales ouvertes (si elles existent)
    const modals = document.querySelectorAll('.modal.show, .modal[style*="display: block"]');
    modals.forEach(modal => {
        modal.style.display = 'none';
        modal.classList.remove('show');
    });
}

// ========================================
// GESTION DES ERREURS
// ========================================

// Gestion des erreurs supprimée - gérée par global-events.js

// ========================================
// PERFORMANCE ET OPTIMISATIONS
// ========================================

// Debounce pour les événements de redimensionnement
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Optimisation des animations
const optimizedResize = debounce(handleResize, 250);
window.addEventListener('resize', optimizedResize);

// Lazy loading des images (si nécessaire)
function initLazyLoading() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
}

// ========================================
// EXPORT DES FONCTIONS (si nécessaire)
// ========================================

if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initFilters,
        initListingCards,
        initProgressBars,
        applyFilters,
        initAnimations,
        initResponsive,
        initAccessibility
    };
}
