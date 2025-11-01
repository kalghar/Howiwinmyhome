/**
 * GESTION DES FONCTIONNALITÉS D'ADMINISTRATION
 * HOW I WIN MY HOME
 * ========================================
 *
 * Ce fichier gère toutes les interactions JavaScript
 * nécessaires pour l'interface d'administration.
 *
 * FONCTIONNALITÉS :
 * - Gestion des modales de rejet d'annonces
 * - Validation des formulaires d'administration
 * - Interactions utilisateur pour la modération
 *
 * AUTEUR : How I Win My Home Team
 * VERSION : 2.0.0
 * DATE : 2025-08-14
 * ========================================
 */

// ========================================
// GESTION DES MODALES
// ========================================

/**
 * Affiche la modale de rejet d'annonce
 * @param {number} listingId - ID de l'annonce à rejeter
 */
function showRejectModal(listingId) {
    console.log('showRejectModal appelé avec listingId:', listingId);
    document.getElementById('rejectListingId').value = listingId;
    const modal = document.getElementById('rejectModal');
    console.log('Modal trouvé:', modal);
    if (modal) {
        console.log('Classes avant:', modal.className);
        modal.classList.remove('modal-hidden');
        console.log('Classes après:', modal.className);
    }
    
    // Focus sur le champ de raison
    setTimeout(() => {
        document.getElementById('rejectReason').focus();
    }, 100);
}

/**
 * Ferme la modale de rejet d'annonce
 */
function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    if (modal) {
        modal.classList.add('modal-hidden');
    }
    document.getElementById('rejectReason').value = '';
}

/**
 * Approuve une annonce
 * @param {number} listingId - ID de l'annonce à approuver
 */
function approveListing(listingId) {
    if (confirm('Êtes-vous sûr de vouloir approuver cette annonce ?')) {
        // Ici vous pouvez ajouter l'appel AJAX pour approuver l'annonce
        console.log('Approbation de l\'annonce:', listingId);
        // Pour l'instant, on affiche juste un message
        alert('Annonce approuvée avec succès !');
    }
}

/**
 * Soumet le formulaire de rejet
 */
function submitReject() {
    const form = document.getElementById('rejectForm');
    const formData = new FormData(form);
    
    // Validation basique
    const reason = document.getElementById('rejectReason').value;
    if (!reason) {
        alert('Veuillez sélectionner une raison de rejet.');
        return;
    }
    
    if (confirm('Êtes-vous sûr de vouloir rejeter cette annonce ?')) {
        // Ici vous pouvez ajouter l'appel AJAX pour rejeter l'annonce
        console.log('Rejet de l\'annonce:', formData.get('listing_id'));
        console.log('Raison:', reason);
        console.log('Commentaire:', formData.get('comment'));
        
        // Pour l'instant, on affiche juste un message
        alert('Annonce rejetée avec succès !');
        closeRejectModal();
    }
}

// ========================================
// GESTION DES ÉVÉNEMENTS
// ========================================

// Gestion des boutons de rejet d'annonce
document.addEventListener('DOMContentLoaded', function() {
    // Boutons de rejet d'annonce
    const rejectButtons = document.querySelectorAll('.reject-listing-btn');
    rejectButtons.forEach(button => {
        button.addEventListener('click', function() {
            const listingId = this.getAttribute('data-listing-id');
            showRejectModal(listingId);
        });
    });
    
    // Gestion des boutons d'action des annonces
    const actionButtons = document.querySelectorAll('[data-action]');
    actionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            const listingId = this.getAttribute('data-listing-id');
            
            switch(action) {
                case 'view':
                    window.location.href = `/admin/listing/${listingId}`;
                    break;
                case 'edit':
                    // TODO: Implémenter l'édition
                    alert('Fonction d\'édition à implémenter');
                    break;
                case 'approve':
                    approveListing(listingId);
                    break;
                case 'reject':
                    showRejectModal(listingId);
                    break;
            }
        });
    });
    
    // Boutons de fermeture de la modale
    const closeButtons = document.querySelectorAll('[data-action="close-modal"]');
    closeButtons.forEach(button => {
        button.addEventListener('click', closeRejectModal);
    });
    
    // Bouton de soumission du rejet
    const submitRejectButton = document.querySelector('[data-action="submit-reject"]');
    if (submitRejectButton) {
        submitRejectButton.addEventListener('click', submitReject);
    }
    
    // Validation du formulaire de rejet
    const rejectForm = document.querySelector('#rejectForm');
    if (rejectForm) {
        rejectForm.addEventListener('submit', validateRejectForm);
    }
});

// Fermer le modal en cliquant à l'extérieur
window.addEventListener('click', function(event) {
    const modal = document.getElementById('rejectModal');
    if (event.target === modal) {
        closeRejectModal();
    }
});

// Fermer le modal avec la touche Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('rejectModal');
        if (modal && !modal.classList.contains('modal-hidden')) {
            closeRejectModal();
        }
    }
});

// ========================================
// GESTION DES BOUTONS D'ACTION
// ========================================

// Attacher les événements aux boutons d'approbation
document.addEventListener('DOMContentLoaded', function() {
    // Boutons d'approbation
    document.querySelectorAll('.approve-listing-btn').forEach(button => {
        button.addEventListener('click', function() {
            const listingId = this.getAttribute('data-listing-id');
            approveListing(listingId);
        });
    });
});

// ========================================
// VALIDATION DES FORMULAIRES
// ========================================

/**
 * Valide le formulaire de rejet d'annonce
 * @param {Event} event - Événement de soumission
 * @returns {boolean} - True si valide, false sinon
 */
function validateRejectForm(event) {
    const reason = document.getElementById('rejectReason').value.trim();
    
    // Validation de base
    if (!reason) {
        alert('Veuillez indiquer une raison pour le rejet de cette annonce.');
        event.preventDefault();
        return false;
    }
    
    if (reason.length < 10) {
        alert('La raison du rejet doit contenir au moins 10 caractères.');
        event.preventDefault();
        return false;
    }
    
    return true;
}
