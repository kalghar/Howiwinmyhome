/**
 * APP.JS - HOW I WIN MY HOME V1
 * ========================================
 *
 * FICHIER JAVASCRIPT PRINCIPAL
 * Gestion des fonctionnalités principales de l'application
 *
 * AUTEUR : How I Win My Home Team
 * VERSION : 1.0.0
 * DATE : 2025-08-17
 * ========================================
 */

// ========================================
// GESTION DES BARRES DE PROGRESSION
// ========================================

// Initialiser les barres de progression avec attribut data-progress
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.progress-fill[data-progress]');
    progressBars.forEach(bar => {
        const progress = bar.getAttribute('data-progress');
        if (progress) {
            bar.style.width = progress + '%';
        }
    });
});

// ========================================
// GESTIONNAIRE DES FORMULAIRES
// ========================================

class FormManager {
    constructor() {
        this.forms = new Map();
        this.init();
    }
    
    init() {
        this.bindEvents();
    }
    
    bindEvents() {
        document.addEventListener('submit', (e) => {
            if (e.target.tagName === 'FORM') {
                this.handleSubmit(e);
            }
        });
        
        // Validation en temps réel
        document.addEventListener('input', (e) => {
            if (e.target.matches('input, textarea, select')) {
                this.validateField(e.target);
            }
        });
    }
    
    handleSubmit(e) {
        const form = e.target;
        
        // Ignorer les formulaires avec data-no-ajax
        if (form.hasAttribute('data-no-ajax')) {
            return; // Laisser le formulaire se soumettre normalement
        }
        
        e.preventDefault();
        const formId = form.id || `form-${Date.now()}`;
        
        if (this.validateForm(form)) {
            this.submitForm(form, formId);
        }
    }
    
    validateForm(form) {
        let isValid = true;
        const fields = form.querySelectorAll('input, textarea, select');
        
        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    /**
     * Détermine la catégorie de validation basée sur le formulaire
     * @param {HTMLFormElement} form - Formulaire
     * @returns {string} Catégorie de validation
     */
    getValidationCategory(form) {
        if (!form) return null;
        
        // Déterminer la catégorie basée sur l'ID ou l'action du formulaire
        const formId = form.id;
        const formAction = form.action;
        
        if (formId.includes('register') || formId.includes('login') || formAction.includes('auth/')) {
            return 'user';
        } else if (formId.includes('listing') || formAction.includes('listings/')) {
            return 'listing';
        } else if (formId.includes('letter') || formAction.includes('letters/')) {
            return 'letter';
        } else if (formId.includes('admin') || formAction.includes('admin/')) {
            return 'admin';
        }
        
        return null;
    }
    
    /**
     * Récupère les données du formulaire
     * @param {HTMLFormElement} form - Formulaire
     * @returns {Object} Données du formulaire
     */
    getFormData(form) {
        const formData = new FormData(form);
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        return data;
    }
    
    validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        
        this.removeFieldError(field);
        
        // Déterminer la catégorie de validation basée sur le formulaire
        const form = field.closest('form');
        const category = this.getValidationCategory(form);
        
        // Utiliser les règles harmonisées si disponibles
        if (category && typeof validateField === 'function') {
            const result = validateField(field.name, value, category, this.getFormData(form));
            if (!result.valid) {
                this.showFieldError(field, result.message);
                isValid = false;
            }
        } else {
            // Validation de base (fallback)
            if (field.hasAttribute('required') && !value) {
                this.showFieldError(field, 'Ce champ est requis');
                isValid = false;
            }
            
            // Validation email
            if (field.type === 'email' && value && !this.isValidEmail(value)) {
                this.showFieldError(field, 'Email invalide');
                isValid = false;
            }
            
            // Validation longueur minimale
            if (field.hasAttribute('minlength')) {
                const minLength = parseInt(field.getAttribute('minlength'));
                if (value.length < minLength) {
                    this.showFieldError(field, `Minimum ${minLength} caractères`);
                    isValid = false;
                }
            }
        }
        
        // Validation des mots de passe pour l'inscription
        if (field.name === 'password' || field.name === 'password_confirm') {
            const passwordField = field.name === 'password' ? field : field.form.querySelector('input[name="password"]');
            const confirmField = field.name === 'password_confirm' ? field : field.form.querySelector('input[name="password_confirm"]');
            
            if (passwordField && confirmField && passwordField.value && confirmField.value) {
                if (passwordField.value !== confirmField.value) {
                    this.showFieldError(confirmField, 'Les mots de passe ne correspondent pas');
                    isValid = false;
                }
            }
        }
        
        // Mise à jour visuelle
        field.classList.toggle('error', !isValid);
        
        return isValid;
    }
    
    showFieldError(field, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = message;
        errorDiv.id = `error-${field.id || field.name}`;
        
        field.parentNode.appendChild(errorDiv);
    }
    
    removeFieldError(field) {
        const errorId = `error-${field.id || field.name}`;
        const existingError = document.getElementById(errorId);
        if (existingError) {
            existingError.remove();
        }
    }
    
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    async submitForm(form, formId) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Envoi en cours...';
        
        try {
            const formData = new FormData(form);
            const response = await fetch(form.action || window.location.href, {
                method: form.method || 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    form.reset();
                }
            }
        } catch (error) {
            // Gestion d'erreur silencieuse
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    }
    
    
}

// ========================================
// CLASSE PRINCIPALE DE L'APPLICATION
// ========================================

class App {
    constructor() {
        this.managers = {};
        this.init();
    }
    
    init() {
        // Initialiser le gestionnaire de formulaires
        this.managers.form = new FormManager();
        
        // Rendre le gestionnaire accessible globalement
        window.formManager = this.managers.form;
        
        // Initialiser les fonctionnalités spécifiques aux pages
        this.initPageSpecificFeatures();
    }
    
    initPageSpecificFeatures() {
        const page = document.body.dataset.page || 'home';
        
        switch (page) {
            case 'home':
                this.initHomePage();
                break;
            case 'auth':
                this.initAuthPage();
                break;
            case 'dashboard':
                this.initDashboardPage();
                break;
            default:
                this.initGenericPage();
        }
    }
    
    initHomePage() {
        // Fonctionnalités spécifiques à la page d'accueil
    }
    
    initAuthPage() {
        // Fonctionnalités spécifiques aux pages d'authentification
    }
    
    initDashboardPage() {
        // Fonctionnalités spécifiques au tableau de bord
    }
    
    initGenericPage() {
        // Fonctionnalités génériques pour les autres pages
    }
}

// ========================================
// INITIALISATION QUAND LE DOM EST PRÊT
// ========================================

document.addEventListener('DOMContentLoaded', () => {
    window.app = new App();
});

// ========================================
// GESTION DES ERREURS GLOBALES
// ========================================

// Gestion des erreurs gérée par global-events.js
