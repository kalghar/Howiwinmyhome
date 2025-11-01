/**
 * LETTER.JS - HOW I WIN MY HOME V1
 * ========================================
 *
 * JavaScript pour la page de création de lettre de motivation
 *
 * AUTEUR : How I Win My Home Team
 * VERSION : 1.0.0
 * DATE : 2025-08-17
 * ========================================
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Letter JavaScript chargé');
    
    // Éléments du formulaire
    const letterForm = document.getElementById('letterForm');
    const letterTextarea = document.getElementById('letter');
    const previewBtn = document.querySelector('.preview-letter-btn');
    const submitBtn = document.querySelector('button[type="submit"]');
    
    // Compteur de caractères
    const charCounter = document.getElementById('charCounter');
    const maxLength = 2000;
    
    if (letterTextarea && charCounter) {
        // Mettre à jour le compteur
        function updateCharCounter() {
            const currentLength = letterTextarea.value.length;
            const remaining = maxLength - currentLength;
            
            charCounter.textContent = `${currentLength}/${maxLength} caractères`;
            
            // Changer la couleur selon le nombre de caractères restants
            if (remaining < 100) {
                charCounter.style.color = '#ef4444'; // Rouge
            } else if (remaining < 300) {
                charCounter.style.color = '#f59e0b'; // Orange
            } else {
                charCounter.style.color = '#10b981'; // Vert
            }
        }
        
        // Écouter les changements dans le textarea
        letterTextarea.addEventListener('input', updateCharCounter);
        
        // Initialiser le compteur
        updateCharCounter();
    }
    
    // Validation du formulaire
    if (letterForm) {
        letterForm.addEventListener('submit', function(e) {
            const letterContent = letterTextarea.value.trim();
            
            // Validation de la longueur
            if (letterContent.length < 100) {
                e.preventDefault();
                showNotification('La lettre doit contenir au moins 100 caractères.', 'error');
                letterTextarea.focus();
                return false;
            }
            
            if (letterContent.length > maxLength) {
                e.preventDefault();
                showNotification(`La lettre ne peut pas dépasser ${maxLength} caractères.`, 'error');
                letterTextarea.focus();
                return false;
            }
            
            // Validation du contenu
            if (letterContent.length < 200) {
                if (!confirm('Votre lettre est assez courte. Êtes-vous sûr de vouloir la soumettre ?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            // Désactiver le bouton de soumission
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Soumission en cours...';
            }
        });
    }
    
    // Bouton de prévisualisation
    if (previewBtn) {
        previewBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const letterContent = letterTextarea.value.trim();
            
            if (letterContent.length < 50) {
                showNotification('Veuillez écrire au moins quelques mots avant de prévisualiser.', 'warning');
                return;
            }
            
            // Créer une modal de prévisualisation
            showPreviewModal(letterContent);
        });
    }
    
    // Fonction de prévisualisation
    function showPreviewModal(content) {
        // Créer l'overlay
        const overlay = document.createElement('div');
        overlay.className = 'preview-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        `;
        
        // Créer le contenu de la modal
        const modal = document.createElement('div');
        modal.style.cssText = `
            background: white;
            border-radius: 12px;
            max-width: 800px;
            width: 100%;
            max-height: 80vh;
            overflow: hidden;
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
        `;
        
        modal.innerHTML = `
            <div style="padding: 24px; border-bottom: 1px solid #e5e7eb;">
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: #111827;">
                    <i class="fas fa-eye"></i> Prévisualisation de votre lettre
                </h3>
            </div>
            <div style="padding: 24px; max-height: 60vh; overflow-y: auto;">
                <div style="
                    background: #f9fafb;
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    padding: 20px;
                    font-family: 'Inter', sans-serif;
                    line-height: 1.6;
                    white-space: pre-wrap;
                    word-wrap: break-word;
                ">${content}</div>
            </div>
            <div style="padding: 24px; border-top: 1px solid #e5e7eb; display: flex; gap: 12px; justify-content: flex-end;">
                <button class="btn btn-outline" onclick="this.closest('.preview-overlay').remove()">
                    <i class="fas fa-times"></i> Fermer
                </button>
                <button class="btn btn-primary" onclick="this.closest('.preview-overlay').remove(); document.getElementById('letter').focus();">
                    <i class="fas fa-edit"></i> Continuer l'édition
                </button>
            </div>
        `;
        
        overlay.appendChild(modal);
        document.body.appendChild(overlay);
        
        // Fermer en cliquant sur l'overlay
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                overlay.remove();
            }
        });
        
        // Fermer avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                overlay.remove();
            }
        });
    }
    
    // Fonction de notification
    function showNotification(message, type = 'info') {
        // Créer la notification
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 1001;
            max-width: 400px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        
        // Couleur selon le type
        switch (type) {
            case 'success':
                notification.style.background = '#10b981';
                break;
            case 'error':
                notification.style.background = '#ef4444';
                break;
            case 'warning':
                notification.style.background = '#f59e0b';
                break;
            default:
                notification.style.background = '#3b82f6';
        }
        
        notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animation d'entrée
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Supprimer après 5 secondes
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }
    
    console.log('Letter JavaScript initialisé avec succès');
    
    // ======================================== */
    // FONCTIONNALITÉS POUR LA PAGE VIEW-LETTER */
    // ======================================== */
    
    // Éléments spécifiques à la page view-letter
    const viewSubmitBtn = document.getElementById('submit-letter-btn');
    const viewDeleteBtn = document.getElementById('delete-letter-btn');
    const submitModal = document.getElementById('submit-letter-modal');
    const deleteModal = document.getElementById('delete-letter-modal');
    
    // Si on est sur la page view-letter, initialiser les modals
    if (submitModal || deleteModal) {
        initializeViewLetterModals();
    }
    
    /**
     * Initialise les modals pour la page view-letter
     */
    function initializeViewLetterModals() {
        console.log('Initialisation des modals view-letter');
        
        // Boutons d'ouverture
        if (viewSubmitBtn) {
            viewSubmitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openModal('submit');
            });
        }
        
        if (viewDeleteBtn) {
            viewDeleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openModal('delete');
            });
        }
        
        // Boutons de confirmation
        const confirmSubmitBtn = document.getElementById('confirm-submit-btn');
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
        
        if (confirmSubmitBtn) {
            confirmSubmitBtn.addEventListener('click', function() {
                submitLetter();
            });
        }
        
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', function() {
                deleteLetter();
            });
        }
        
        // Boutons d'annulation
        const cancelSubmitBtn = document.getElementById('cancel-submit-btn');
        const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
        
        if (cancelSubmitBtn) {
            cancelSubmitBtn.addEventListener('click', function() {
                closeModal('submit');
            });
        }
        
        if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', function() {
                closeModal('delete');
            });
        }
        
        // Boutons de fermeture
        const submitModalClose = submitModal?.querySelector('.modal-close');
        const deleteModalClose = deleteModal?.querySelector('.modal-close');
        
        if (submitModalClose) {
            submitModalClose.addEventListener('click', function() {
                closeModal('submit');
            });
        }
        
        if (deleteModalClose) {
            deleteModalClose.addEventListener('click', function() {
                closeModal('delete');
            });
        }
        
        // Fermer en cliquant sur l'overlay
        if (submitModal) {
            submitModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal('submit');
                }
            });
        }
        
        if (deleteModal) {
            deleteModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal('delete');
                }
            });
        }
        
        // Fermer avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (submitModal?.classList.contains('active')) {
                    closeModal('submit');
                } else if (deleteModal?.classList.contains('active')) {
                    closeModal('delete');
                }
            }
        });
    }
    
    /**
     * Ouvre un modal
     */
    function openModal(type) {
        console.log('Ouverture du modal:', type);
        
        // Fermer tous les autres modals
        closeAllModals();
        
        const modal = document.getElementById(`${type}-letter-modal`);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    
    /**
     * Ferme un modal
     */
    function closeModal(type) {
        console.log('Fermeture du modal:', type);
        
        const modal = document.getElementById(`${type}-letter-modal`);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
    
    /**
     * Ferme tous les modals
     */
    function closeAllModals() {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.classList.remove('active');
        });
        document.body.style.overflow = '';
    }
    
    /**
     * Soumet la lettre
     */
    function submitLetter() {
        console.log('Soumission de la lettre');
        
        // Désactiver le bouton
        const confirmSubmitBtn = document.getElementById('confirm-submit-btn');
        if (confirmSubmitBtn) {
            confirmSubmitBtn.disabled = true;
            confirmSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Soumission...';
        }
        
        // Créer un formulaire de soumission
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/game/submit-letter';
        
        const letterIdInput = document.createElement('input');
        letterIdInput.type = 'hidden';
        letterIdInput.name = 'letter_id';
        letterIdInput.value = getLetterId();
        
        form.appendChild(letterIdInput);
        document.body.appendChild(form);
        form.submit();
    }
    
    /**
     * Supprime la lettre
     */
    function deleteLetter() {
        console.log('Suppression de la lettre');
        
        // Désactiver le bouton
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.disabled = true;
            confirmDeleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';
        }
        
        // Créer un formulaire de suppression
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/game/delete-letter';
        
        const letterIdInput = document.createElement('input');
        letterIdInput.type = 'hidden';
        letterIdInput.name = 'letter_id';
        letterIdInput.value = getLetterId();
        
        form.appendChild(letterIdInput);
        document.body.appendChild(form);
        form.submit();
    }
    
    /**
     * Récupère l'ID de la lettre depuis l'URL
     */
    function getLetterId() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('id') || '';
    }
});
