/**
 * CONTACT.JS - HOW I WIN MY HOME V1
 * 
 * Gestion du formulaire de contact
 * 
 * @author How I Win My Home Team
 * @version 1.0.0
 * @since 2024-12-09
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!form || !submitBtn) return;
    
    // Validation en temps réel
    const inputs = form.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('error')) {
                validateField(this);
            }
        });
    });
    
    // Validation du formulaire
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            submitForm();
        }
    });
    
    // Validation d'un champ
    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        
        // Supprimer les classes d'erreur précédentes
        field.classList.remove('error', 'success');
        
        // Validation selon le type
        if (field.hasAttribute('required') && !value) {
            isValid = false;
        } else if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
            }
        }
        
        // Appliquer le style approprié
        if (isValid && value) {
            field.classList.add('success');
        } else if (!isValid) {
            field.classList.add('error');
        }
        
        return isValid;
    }
    
    // Validation du formulaire complet
    function validateForm() {
        let isValid = true;
        
        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });
        
        // Validation de la checkbox
        const privacyCheckbox = document.getElementById('privacy');
        if (!privacyCheckbox.checked) {
            privacyCheckbox.classList.add('error');
            isValid = false;
        } else {
            privacyCheckbox.classList.remove('error');
        }
        
        return isValid;
    }
    
    // Envoi du formulaire
    function submitForm() {
        // Afficher l'état de chargement
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span> Envoi en cours...';
        
        // Simuler l'envoi (remplacer par un vrai appel AJAX)
        setTimeout(() => {
            // Succès
            showSuccessMessage('Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
            
            // Réinitialiser le formulaire
            form.reset();
            inputs.forEach(input => {
                input.classList.remove('success', 'error');
            });
            
            // Restaurer le bouton
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Envoyer le message';
            
        }, 2000);
    }
    
    // Message de succès
    function showSuccessMessage(message) {
        const toast = document.createElement('div');
        toast.className = 'success-toast';
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'polite');
        toast.textContent = message;
        // Les styles sont maintenant dans contact.css
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('slide-out');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 5000);
    }
});
