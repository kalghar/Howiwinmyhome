/**
 * VALIDATION EN TEMPS RÉEL - HOW I WIN MY HOME
 * ========================================
 * 
 * Gestionnaire de validation en temps réel pour améliorer l'expérience utilisateur
 * 
 * AUTEUR : How I Win My Home Team
 * VERSION : 1.0.0
 * DATE : Décembre 2024
 */

// ========================================
// GESTIONNAIRE DE VALIDATION EN TEMPS RÉEL
// ========================================

class RealTimeValidator {
    constructor() {
        this.validators = new Map();
        this.init();
    }
    
    /**
     * Initialise la validation en temps réel
     */
    init() {
        // Attendre que le DOM soit chargé
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupValidation());
        } else {
            this.setupValidation();
        }
    }
    
    /**
     * Configure la validation pour tous les formulaires
     */
    setupValidation() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            this.setupFormValidation(form);
        });
    }
    
    /**
     * Configure la validation pour un formulaire spécifique
     * @param {HTMLFormElement} form - Formulaire à configurer
     */
    setupFormValidation(form) {
        const category = this.determineValidationCategory(form);
        if (!category) return;
        
        // Configurer la validation pour chaque champ
        const fields = form.querySelectorAll('input, textarea, select');
        fields.forEach(field => {
            this.setupFieldValidation(field, category, form);
        });
        
        // Validation au submit
        form.addEventListener('submit', (e) => {
            if (!this.validateFormBeforeSubmit(form, category)) {
                e.preventDefault();
            }
        });
    }
    
    /**
     * Détermine la catégorie de validation pour un formulaire
     * @param {HTMLFormElement} form - Formulaire
     * @returns {string|null} Catégorie de validation
     */
    determineValidationCategory(form) {
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
     * Configure la validation pour un champ spécifique
     * @param {HTMLElement} field - Champ à configurer
     * @param {string} category - Catégorie de validation
     * @param {HTMLFormElement} form - Formulaire parent
     */
    setupFieldValidation(field, category, form) {
        if (!field.name) return;
        
        // Validation en temps réel (après perte de focus)
        field.addEventListener('blur', () => {
            this.validateField(field, category, form);
        });
        
        // Validation pendant la saisie (avec délai)
        let timeout;
        field.addEventListener('input', () => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                this.validateField(field, category, form);
            }, 500); // Délai de 500ms
        });
        
        // Nettoyer les erreurs au focus
        field.addEventListener('focus', () => {
            this.clearFieldError(field);
        });
    }
    
    /**
     * Valide un champ et affiche les erreurs
     * @param {HTMLElement} field - Champ à valider
     * @param {string} category - Catégorie de validation
     * @param {HTMLFormElement} form - Formulaire parent
     */
    validateField(field, category, form) {
        if (typeof validateField !== 'function') return;
        
        // Récupérer les données du formulaire pour validation croisée
        const formData = new FormData(form);
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        
        const result = validateField(field.name, field.value, category, data);
        
        if (!result.valid) {
            this.showFieldError(field, result.message);
        } else {
            this.clearFieldError(field);
        }
    }
    
    /**
     * Affiche une erreur pour un champ
     * @param {HTMLElement} field - Champ
     * @param {string} message - Message d'erreur
     */
    showFieldError(field, message) {
        this.clearFieldError(field);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error real-time-error';
        errorDiv.textContent = message;
        errorDiv.style.color = '#dc3545';
        errorDiv.style.fontSize = '0.875rem';
        errorDiv.style.marginTop = '0.25rem';
        errorDiv.style.display = 'block';
        
        field.parentNode.insertBefore(errorDiv, field.nextSibling);
        field.style.borderColor = '#dc3545';
        field.classList.add('error');
    }
    
    /**
     * Supprime l'erreur d'un champ
     * @param {HTMLElement} field - Champ
     */
    clearFieldError(field) {
        const existingError = field.parentNode.querySelector('.real-time-error');
        if (existingError) {
            existingError.remove();
        }
        
        field.style.borderColor = '';
        field.classList.remove('error');
    }
    
    /**
     * Valide un formulaire avant soumission
     * @param {HTMLFormElement} form - Formulaire
     * @param {string} category - Catégorie de validation
     * @returns {boolean} True si valide
     */
    validateFormBeforeSubmit(form, category) {
        if (typeof validateForm !== 'function') return true;
        
        const result = validateForm(form, category);
        
        if (!result.valid) {
            // Afficher toutes les erreurs
            Object.keys(result.errors).forEach(fieldName => {
                const field = form.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    this.showFieldError(field, result.errors[fieldName]);
                }
            });
            
            // Faire défiler vers la première erreur
            const firstError = form.querySelector('.real-time-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            return false;
        }
        
        return true;
    }
}

// ========================================
// INITIALISATION AUTOMATIQUE
// ========================================

// Initialiser la validation en temps réel
const realTimeValidator = new RealTimeValidator();

// Exporter pour utilisation dans d'autres fichiers
if (typeof module !== 'undefined' && module.exports) {
    module.exports = RealTimeValidator;
}
