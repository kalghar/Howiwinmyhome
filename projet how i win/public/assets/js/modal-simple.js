/**
 * GESTIONNAIRE DE MODALES SIMPLE
 * ========================================
 * 
 * Gestionnaire de modales simple et direct
 * qui ne dépend d'aucune autre classe
 */

// Fonctions pour gérer les modales
function openModal(type) {
    closeAllModals();

    const modal = document.querySelector(`[data-modal-type="${type}"]`);
    if (modal) {
        // S'assurer que les formulaires sont vides
        const forms = modal.querySelectorAll('form');
        forms.forEach(form => {
            form.reset();

            // Stocker le texte original des boutons de soumission
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.dataset.originalText) {
                submitBtn.dataset.originalText = submitBtn.textContent;
            }
        });

        // Supprimer tous les messages d'erreur
        const errorMessages = modal.querySelectorAll('.modal-message-error, .modal-message-success, .field-error');
        errorMessages.forEach(msg => msg.remove());

        modal.classList.add('active');
        // Ne pas forcer display: flex, laisser le CSS gérer
        document.body.style.overflow = 'hidden';
    }
}

function closeModal() {
    const activeModal = document.querySelector('.modal-overlay.active, .modal-overlay-mascabanids.active');
    if (activeModal) {
        activeModal.classList.remove('active');
        // Ne pas forcer display: none, laisser le CSS gérer

        // Vider tous les formulaires dans la modale
        const forms = activeModal.querySelectorAll('form');
        forms.forEach(form => {
            form.reset();
        });

        // Supprimer tous les messages d'erreur
        const errorMessages = activeModal.querySelectorAll('.modal-message-error, .modal-message-success, .field-error');
        errorMessages.forEach(msg => msg.remove());

        document.body.style.overflow = '';
    }
}

function closeAllModals() {
    const modals = document.querySelectorAll('.modal-overlay, .modal-overlay-mascabanids');
    modals.forEach(modal => {
        modal.classList.remove('active');
        // Ne pas forcer display, laisser le CSS gérer
        if (modal.classList.contains('modal-document')) {
            // Pour les modales admin, utiliser les classes CSS
            modal.classList.add('modal-hidden');
        }

        // Vider tous les formulaires dans la modale
        const forms = modal.querySelectorAll('form');
        forms.forEach(form => {
            form.reset();
        });

        // Supprimer tous les messages d'erreur
        const errorMessages = modal.querySelectorAll('.modal-message-error, .modal-message-success, .field-error');
        errorMessages.forEach(msg => msg.remove());
    });
    document.body.style.overflow = '';
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function () {
    // S'assurer que tous les modals sont fermés au chargement
    closeAllModals();
});

// Gestion des événements
document.addEventListener('click', function (e) {
    // Boutons avec data-modal
    if (e.target.hasAttribute('data-modal')) {
        e.preventDefault();
        const modalType = e.target.dataset.modal;
        openModal(modalType);
        return;
    }

    // Boutons d'authentification du header
    if (e.target.hasAttribute('data-auth-action')) {
        e.preventDefault();
        const action = e.target.dataset.authAction;
        if (action === 'login' || action === 'register') {
            openModal(action);
        }
        return;
    }

    // Boutons de fermeture
    if (e.target.closest('.modal-close')) {
        e.preventDefault();
        closeModal();
        return;
    }

    // Clic à l'extérieur
    if (e.target.classList.contains('modal-overlay') || e.target.classList.contains('modal-overlay-mascabanids')) {
        closeModal();
        return;
    }
});

// Fermer avec Escape
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Gestion des formulaires dans les modales
document.addEventListener('submit', function (e) {
    // PRIORITÉ ABSOLUE: Ignorer les formulaires de dépôt
    if (e.target.action && e.target.action.includes('process-deposit')) {
        return; // Ne pas intercepter du tout
    }

    // PRIORITÉ 2: Ignorer les formulaires avec data-no-ajax
    if (e.target.hasAttribute('data-no-ajax')) {
        return; // Ne pas intercepter du tout
    }

    // PRIORITÉ 3: Ignorer les formulaires avec la classe deposit-form
    if (e.target.classList.contains('deposit-form')) {
        return; // Ne pas intercepter du tout
    }

    // Vérifier si c'est un formulaire dans une modale
    if (e.target.closest('.modal-overlay') || e.target.closest('.modal-overlay-mascabanids')) {
        e.preventDefault();
        handleModalFormSubmit(e.target);
    }
});

function handleModalFormSubmit(form) {
    // PRIORITÉ ABSOLUE: Ignorer complètement les formulaires de dépôt
    if (form.action && form.action.includes('process-deposit')) {
        return; // Ne rien faire du tout
    }

    // PRIORITÉ 2: Ignorer les formulaires avec data-no-ajax
    if (form.hasAttribute('data-no-ajax')) {
        return; // Ne rien faire du tout
    }

    // PRIORITÉ 3: Ignorer les formulaires avec la classe deposit-form
    if (form.classList.contains('deposit-form')) {
        return; // Ne rien faire du tout
    }

    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.dataset.originalText || submitBtn.textContent;



    // Désactiver le bouton et afficher le loading
    submitBtn.disabled = true;
    submitBtn.textContent = 'Envoi en cours...';

    // Supprimer les messages d'erreur précédents
    clearModalMessages(form);

    // Validation côté client pour l'inscription
    if (form.id === 'register-form' || form.querySelector('input[name="firstname"]')) {
        const password = form.querySelector('input[name="password"]').value;
        const passwordConfirm = form.querySelector('input[name="password_confirm"]').value;

        if (password !== passwordConfirm) {
            showModalMessage(form, 'Les mots de passe ne correspondent pas', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
            return;
        }
    }


    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur de réponse du serveur: ' + response.status);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                return response.text().then(text => {
                    throw new Error('Réponse non-JSON reçue du serveur: ' + text.substring(0, 100));
                });
            }

            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Succès
                showModalMessage(form, data.message, 'success');

                // Redirection si spécifiée
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else {
                    // Fermer la modale après 2 secondes
                    setTimeout(() => {
                        closeModal();
                    }, 2000);
                }
            } else {
                // Erreur - NE PAS CHANGER LE BOUTON
                showModalMessage(form, data.message, 'error');

                // Afficher les erreurs de champs spécifiques
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        showFieldError(form, field, data.errors[field]);
                    });
                }

                // Réactiver le bouton immédiatement en cas d'erreur
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                return; // Sortir de la fonction pour éviter le finally
            }
        })
        .catch(error => {
            // Message d'erreur adapté selon le type de formulaire
            const isRegisterForm = form.id === 'register-form' || form.querySelector('input[name="firstname"]');
            const errorMessage = isRegisterForm
                ? 'Une erreur est survenue lors de l\'inscription. Vérifiez vos informations et réessayez.'
                : 'Une erreur est survenue lors de la connexion. Vérifiez vos identifiants et réessayez.';
            showModalMessage(form, errorMessage, 'error');

            // Réactiver le bouton immédiatement en cas d'erreur
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        })
        .finally(() => {
            // Réactiver le bouton seulement si pas déjà fait
            if (submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
}

function showModalMessage(form, message, type) {
    const modal = form.closest('.modal-overlay, .modal-overlay-mascabanids');
    const modalBody = modal.querySelector('.modal-body');

    // Supprimer les messages précédents
    const existingMessage = modalBody.querySelector('.modal-message');
    if (existingMessage) {
        existingMessage.remove();
    }

    // Créer le nouveau message
    const messageDiv = document.createElement('div');
    messageDiv.className = `modal-message modal-message-${type}`;
    messageDiv.textContent = message;

    // Insérer le message au début du modal-body
    modalBody.insertBefore(messageDiv, modalBody.firstChild);
}

function clearModalMessages(form) {
    const modal = form.closest('.modal-overlay, .modal-overlay-mascabanids');
    const modalBody = modal.querySelector('.modal-body');

    // Supprimer les messages généraux
    const messages = modalBody.querySelectorAll('.modal-message');
    messages.forEach(msg => msg.remove());

    // Supprimer les erreurs de champs
    const fieldErrors = modalBody.querySelectorAll('.field-error');
    fieldErrors.forEach(error => error.remove());

    // Supprimer les classes d'erreur des champs
    const errorFields = modalBody.querySelectorAll('.form-input.error');
    errorFields.forEach(field => field.classList.remove('error'));
}

function showFieldError(form, fieldName, message) {
    const field = form.querySelector(`[name="${fieldName}"]`);
    if (field) {
        field.classList.add('error');

        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = message;

        field.parentNode.appendChild(errorDiv);
    }
}
