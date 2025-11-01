/**
 * JAVASCRIPT ADMIN PENDING LISTINGS - HOW I WIN MY HOME V1
 * 
 * Gestion des interactions pour la page d'administration des annonces en attente
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin Pending Listings JS chargé');
    
    // ===== GESTION DES MODALES D'IMAGES =====
    initImageModals();
    
    // ===== GESTION DES ACTIONS D'ADMINISTRATION =====
    initListingActions();
    
    // ===== GESTION DES DOCUMENTS =====
    initDocumentActions();
});

/**
 * Initialise les modales d'images
 */
function initImageModals() {
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const openButtons = document.querySelectorAll('.js-open-image-modal');
    const closeButtons = document.querySelectorAll('.js-close-image-modal');
    
    // Ouvrir la modale d'image
    openButtons.forEach(button => {
        button.addEventListener('click', function() {
            const imageSrc = this.getAttribute('data-image-src');
            if (imageSrc && modalImage) {
        modalImage.src = imageSrc;
        imageModal.classList.remove('modal-hidden');
                document.body.style.overflow = 'hidden';
            }
        });
    });
    
    // Fermer la modale d'image
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
        imageModal.classList.add('modal-hidden');
            document.body.style.overflow = '';
        });
    });
    
    // Fermer avec Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !imageModal.classList.contains('modal-hidden')) {
            imageModal.classList.add('modal-hidden');
            document.body.style.overflow = '';
        }
    });
}

/**
 * Initialise les actions d'administration des annonces
 */
function initListingActions() {
    // Boutons d'approbation
    const approveButtons = document.querySelectorAll('.js-approve-listing');
    approveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const listingId = this.getAttribute('data-listing-id');
    if (listingId) {
                approveListing(listingId);
            }
        });
    });
    
    // Boutons de rejet
    const rejectButtons = document.querySelectorAll('.js-reject-listing');
    rejectButtons.forEach(button => {
        button.addEventListener('click', function() {
            const listingId = this.getAttribute('data-listing-id');
            if (listingId) {
                showRejectModal(listingId);
            }
        });
    });
    
    // Boutons de visualisation
    const viewButtons = document.querySelectorAll('.js-view-full-listing');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const listingId = this.getAttribute('data-listing-id');
            if (listingId) {
                viewFullListing(listingId);
            }
        });
    });
}

/**
 * Initialise les actions sur les documents
 */
function initDocumentActions() {
    // Boutons de visualisation de documents
    const viewDocumentButtons = document.querySelectorAll('.js-view-document');
    viewDocumentButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filePath = this.getAttribute('data-file-path');
            if (filePath) {
                viewDocument(filePath);
            }
        });
    });
    
    // Boutons de téléchargement de documents
    const downloadButtons = document.querySelectorAll('.js-download-document');
    downloadButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filePath = this.getAttribute('data-file-path');
            if (filePath) {
                downloadDocument(filePath);
            }
        });
    });
}

/**
 * Approuve une annonce
 */
function approveListing(listingId) {
    if (!confirm('Êtes-vous sûr de vouloir approuver cette annonce ?')) {
        return;
    }
    
    console.log('Approbation de l\'annonce:', listingId);
    
    // Afficher un indicateur de chargement
    const button = document.querySelector(`[data-listing-id="${listingId}"].js-approve-listing`);
    if (button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Approbation...';
        button.disabled = true;
    }
    
    // Envoyer la requête d'approbation
    fetch('/admin/approve-listing', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            listing_id: listingId,
            csrf_token: getCsrfToken()
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Annonce approuvée avec succès !', 'success');
            // Retirer l'annonce de la liste
            const listingCard = document.querySelector(`[data-listing-id="${listingId}"]`);
            if (listingCard) {
                listingCard.style.opacity = '0.5';
                listingCard.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    listingCard.remove();
                }, 300);
            }
        } else {
            showNotification(data.message || 'Erreur lors de l\'approbation', 'error');
            // Restaurer le bouton
            if (button) {
                button.innerHTML = originalText;
                button.disabled = false;
            }
        }
    })
    .catch(error => {
        console.error('Erreur lors de l\'approbation:', error);
        showNotification('Erreur lors de l\'approbation', 'error');
        // Restaurer le bouton
        if (button) {
            button.innerHTML = originalText;
            button.disabled = false;
        }
    });
}

/**
 * Affiche la modale de rejet
 */
function showRejectModal(listingId) {
    // Créer la modale de rejet si elle n'existe pas
    let rejectModal = document.getElementById('rejectModal');
    if (!rejectModal) {
        rejectModal = createRejectModal();
        document.body.appendChild(rejectModal);
    }
    
    // Remplir l'ID de l'annonce
    const listingIdInput = rejectModal.querySelector('#rejectListingId');
    if (listingIdInput) {
        listingIdInput.value = listingId;
    }
    
    // Afficher la modale
    rejectModal.classList.remove('modal-hidden');
    document.body.style.overflow = 'hidden';
    
    // Focus sur le champ de raison
    const reasonInput = rejectModal.querySelector('#rejectReason');
    if (reasonInput) {
        setTimeout(() => reasonInput.focus(), 100);
    }
}

/**
 * Crée la modale de rejet
 */
function createRejectModal() {
    const modal = document.createElement('div');
    modal.id = 'rejectModal';
    modal.className = 'modal modal-hidden';
    modal.innerHTML = `
        <div class="modal-overlay js-close-reject-modal"></div>
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">Rejeter l'annonce</h3>
                <button class="modal-close js-close-reject-modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="rejectForm">
                    <input type="hidden" id="rejectListingId" name="listing_id">
                    <div class="form-group">
                        <label for="rejectReason">Raison du rejet *</label>
                        <textarea id="rejectReason" name="reason" rows="4" 
                                  placeholder="Expliquez pourquoi cette annonce est rejetée..."
                                  required></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-outline js-close-reject-modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Rejeter l'annonce</button>
                    </div>
                </form>
            </div>
        </div>
    `;
    
    // Ajouter les événements
    const closeButtons = modal.querySelectorAll('.js-close-reject-modal');
    closeButtons.forEach(button => {
        button.addEventListener('click', closeRejectModal);
    });
    
    const form = modal.querySelector('#rejectForm');
    form.addEventListener('submit', handleRejectSubmit);
    
    return modal;
}

/**
 * Ferme la modale de rejet
 */
function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    if (modal) {
        modal.classList.add('modal-hidden');
        document.body.style.overflow = '';
        
        // Vider le formulaire
        const form = modal.querySelector('#rejectForm');
        if (form) {
            form.reset();
        }
    }
}

/**
 * Gère la soumission du formulaire de rejet
 */
function handleRejectSubmit(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const listingId = formData.get('listing_id');
    const reason = formData.get('reason');
    
    if (!reason.trim()) {
        alert('Veuillez indiquer une raison pour le rejet.');
        return;
    }
    
    if (reason.trim().length < 10) {
        alert('La raison du rejet doit contenir au moins 10 caractères.');
        return;
    }
    
    // Envoyer la requête de rejet
    fetch('/admin/reject-listing', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            listing_id: listingId,
            reason: reason,
            csrf_token: getCsrfToken()
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Annonce rejetée avec succès !', 'success');
            closeRejectModal();
            
            // Retirer l'annonce de la liste
            const listingCard = document.querySelector(`[data-listing-id="${listingId}"]`);
            if (listingCard) {
                listingCard.style.opacity = '0.5';
                listingCard.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    listingCard.remove();
                }, 300);
            }
        } else {
            showNotification(data.message || 'Erreur lors du rejet', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur lors du rejet:', error);
        showNotification('Erreur lors du rejet', 'error');
    });
}

/**
 * Visualise une annonce complète
 */
function viewFullListing(listingId) {
    window.open(`/admin/listing/${listingId}`, '_blank');
}

/**
 * Visualise un document
 */
function viewDocument(filePath) {
    window.open(`/uploads/documents/${filePath}`, '_blank');
}

/**
 * Télécharge un document
 */
function downloadDocument(filePath) {
    const link = document.createElement('a');
    link.href = `/uploads/documents/${filePath}`;
    link.download = filePath.split('/').pop();
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

/**
 * Obtient le token CSRF
 */
function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

/**
 * Affiche une notification
 */
function showNotification(message, type = 'info') {
    // Créer l'élément de notification
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Styles de base
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    // Animation d'entrée
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Suppression automatique
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}