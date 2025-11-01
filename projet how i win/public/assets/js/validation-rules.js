/**
 * RÈGLES DE VALIDATION HARMONISÉES - HOW I WIN MY HOME
 * ========================================
 * 
 * Ce fichier contient les règles de validation côté client
 * qui correspondent exactement aux validations côté serveur PHP
 * 
 * AUTEUR : How I Win My Home Team
 * VERSION : 1.0.0
 * DATE : Décembre 2024
 */

// ========================================
// RÈGLES DE VALIDATION COMMUNES
// ========================================

const ValidationRules = {
    
    // ========================================
    // VALIDATION DES UTILISATEURS
    // ========================================
    
    user: {
        first_name: {
            required: true,
            minLength: 2,
            maxLength: 50,
            pattern: /^[a-zA-ZÀ-ÿ\s\-']+$/,
            message: 'Le prénom doit contenir entre 2 et 50 caractères (lettres uniquement)'
        },
        last_name: {
            required: true,
            minLength: 2,
            maxLength: 50,
            pattern: /^[a-zA-ZÀ-ÿ\s\-']+$/,
            message: 'Le nom doit contenir entre 2 et 50 caractères (lettres uniquement)'
        },
        email: {
            required: true,
            pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            message: 'Format d\'email invalide'
        },
        password: {
            required: true,
            minLength: 8,
            maxLength: 255,
            pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/,
            message: 'Le mot de passe doit contenir au moins 8 caractères avec majuscule, minuscule, chiffre et caractère spécial'
        },
        password_confirm: {
            required: true,
            match: 'password',
            message: 'Les mots de passe ne correspondent pas'
        }
    },
    
    // ========================================
    // VALIDATION DES ANNONCES
    // ========================================
    
    listing: {
        title: {
            required: true,
            minLength: 5,
            maxLength: 200,
            message: 'Le titre doit contenir entre 5 et 200 caractères'
        },
        description: {
            required: true,
            minLength: 20,
            maxLength: 2000,
            message: 'La description doit contenir entre 20 et 2000 caractères'
        },
        price: {
            required: true,
            min: 1000,
            max: 10000000,
            pattern: /^\d+(\.\d{1,2})?$/,
            message: 'Le prix doit être entre 1 000€ et 10 000 000€'
        },
        ticket_price: {
            required: true,
            min: 1,
            max: 1000,
            pattern: /^\d+(\.\d{1,2})?$/,
            message: 'Le prix du ticket doit être entre 1€ et 1 000€'
        },
        tickets_needed: {
            required: true,
            min: 10,
            max: 200000,
            pattern: /^\d+$/,
            message: 'Le nombre de tickets doit être entre 10 et 200 000'
        },
        property_type: {
            required: true,
            options: ['appartement', 'maison', 'studio', 'loft', 'autre'],
            message: 'Type de bien invalide'
        },
        property_size: {
            required: true,
            min: 10,
            max: 1000,
            pattern: /^\d+$/,
            message: 'La surface doit être entre 10 et 1 000 m²'
        },
        rooms: {
            required: true,
            min: 1,
            max: 20,
            pattern: /^\d+$/,
            message: 'Le nombre de pièces doit être entre 1 et 20'
        },
        bedrooms: {
            required: true,
            min: 0,
            max: 10,
            pattern: /^\d+$/,
            message: 'Le nombre de chambres doit être entre 0 et 10'
        },
        address: {
            required: true,
            minLength: 5,
            maxLength: 200,
            message: 'L\'adresse doit contenir entre 5 et 200 caractères'
        },
        city: {
            required: true,
            minLength: 2,
            maxLength: 100,
            pattern: /^[a-zA-ZÀ-ÿ\s\-',]+$/,
            message: 'La ville doit contenir entre 2 et 100 caractères'
        },
        postal_code: {
            required: true,
            pattern: /^\d{5}$/,
            message: 'Le code postal doit contenir exactement 5 chiffres'
        }
    },
    
    // ========================================
    // VALIDATION DES LETTRES
    // ========================================
    
    letter: {
        titre: {
            required: false,
            maxLength: 200,
            message: 'Le titre ne peut pas dépasser 200 caractères'
        },
        contenu: {
            required: true,
            minLength: 100,
            maxLength: 5000,
            message: 'La lettre doit contenir entre 100 et 5 000 caractères'
        }
    },
    
    // ========================================
    // VALIDATION DES DOCUMENTS
    // ========================================
    
    document: {
        allowedTypes: [
            'application/pdf',
            'image/jpeg',
            'image/jpg', 
            'image/png',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ],
        maxSize: 10 * 1024 * 1024, // 10MB
        message: 'Format de fichier non autorisé (PDF, JPG, PNG, DOC, DOCX uniquement, max 10MB)'
    },
    
    // ========================================
    // VALIDATION DES FORMULAIRES ADMIN
    // ========================================
    
    admin: {
        reject_reason: {
            required: true,
            minLength: 10,
            maxLength: 500,
            message: 'La raison du rejet doit contenir entre 10 et 500 caractères'
        }
    }
};

// ========================================
// FONCTIONS DE VALIDATION
// ========================================

/**
 * Valide un champ selon les règles définies
 * @param {string} fieldName - Nom du champ
 * @param {string} value - Valeur à valider
 * @param {string} category - Catégorie de validation (user, listing, etc.)
 * @param {Object} formData - Données complètes du formulaire (pour les validations croisées)
 * @returns {Object} Résultat de la validation
 */
function validateField(fieldName, value, category, formData = {}) {
    const rules = ValidationRules[category]?.[fieldName];
    
    if (!rules) {
        return { valid: true, message: '' };
    }
    
    const trimmedValue = value.trim();
    
    // Validation requise
    if (rules.required && !trimmedValue) {
        return { valid: false, message: 'Ce champ est obligatoire' };
    }
    
    // Si le champ n'est pas requis et est vide, c'est valide
    if (!rules.required && !trimmedValue) {
        return { valid: true, message: '' };
    }
    
    // Validation de longueur minimale
    if (rules.minLength && trimmedValue.length < rules.minLength) {
        return { valid: false, message: `Minimum ${rules.minLength} caractères` };
    }
    
    // Validation de longueur maximale
    if (rules.maxLength && trimmedValue.length > rules.maxLength) {
        return { valid: false, message: `Maximum ${rules.maxLength} caractères` };
    }
    
    // Validation de valeur minimale
    if (rules.min !== undefined && parseFloat(trimmedValue) < rules.min) {
        return { valid: false, message: `Valeur minimale : ${rules.min}` };
    }
    
    // Validation de valeur maximale
    if (rules.max !== undefined && parseFloat(trimmedValue) > rules.max) {
        return { valid: false, message: `Valeur maximale : ${rules.max}` };
    }
    
    // Validation par pattern
    if (rules.pattern && !rules.pattern.test(trimmedValue)) {
        return { valid: false, message: rules.message || 'Format invalide' };
    }
    
    // Validation par options
    if (rules.options && !rules.options.includes(trimmedValue)) {
        return { valid: false, message: rules.message || 'Option invalide' };
    }
    
    // Validation de correspondance (ex: confirmation de mot de passe)
    if (rules.match && formData[rules.match] !== trimmedValue) {
        return { valid: false, message: rules.message || 'Les valeurs ne correspondent pas' };
    }
    
    return { valid: true, message: '' };
}

/**
 * Valide un fichier uploadé
 * @param {File} file - Fichier à valider
 * @returns {Object} Résultat de la validation
 */
function validateFile(file) {
    const rules = ValidationRules.document;
    
    // Vérifier le type de fichier
    if (!rules.allowedTypes.includes(file.type)) {
        return { valid: false, message: rules.message };
    }
    
    // Vérifier la taille
    if (file.size > rules.maxSize) {
        return { valid: false, message: 'Le fichier est trop volumineux (max 10MB)' };
    }
    
    return { valid: true, message: '' };
}

/**
 * Valide un formulaire complet
 * @param {HTMLFormElement} form - Formulaire à valider
 * @param {string} category - Catégorie de validation
 * @returns {Object} Résultat de la validation
 */
function validateForm(form, category) {
    const formData = new FormData(form);
    const errors = {};
    let isValid = true;
    
    // Convertir FormData en objet
    const data = {};
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    // Valider chaque champ
    const fields = form.querySelectorAll('input, textarea, select');
    fields.forEach(field => {
        if (field.name && ValidationRules[category]?.[field.name]) {
            const result = validateField(field.name, field.value, category, data);
            if (!result.valid) {
                errors[field.name] = result.message;
                isValid = false;
            }
        }
    });
    
    return {
        valid: isValid,
        errors: errors,
        message: isValid ? '' : 'Veuillez corriger les erreurs'
    };
}

// ========================================
// EXPORT POUR UTILISATION
// ========================================

// Exporter les fonctions pour utilisation dans d'autres fichiers
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        ValidationRules,
        validateField,
        validateFile,
        validateForm
    };
}
