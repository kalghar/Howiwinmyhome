/**
 * MY-LETTERS.JS - HOW I WIN MY HOME V1
 * ========================================
 * 
 * Gestion de la page "Mes lettres de motivation"
 * - Actions sur les lettres
 * - Modal de confirmation
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('My-letters JavaScript chargé');
    
    // Modal de confirmation
    const confirmationModal = document.getElementById('confirmationModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    
    // Variables globales
    let currentLetterId = null;
    
    // Initialisation
    initializeActions();
    initializeModal();
    
    console.log('My-letters JavaScript initialisé avec succès');
    
    
    /**
     * Initialise les actions sur les lettres
     */
    function initializeActions() {
        // Boutons d'action
        const actionButtons = document.querySelectorAll('.action-btn');
        actionButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const action = this.dataset.action;
                const letterId = this.closest('.letter-row').dataset.letterId;
                
                if (action === 'view') {
                    viewLetter(letterId);
                } else if (action === 'edit') {
                    editLetter(letterId);
                } else if (action === 'delete') {
                    deleteLetter(letterId);
                } else if (action === 'duplicate') {
                    duplicateLetter(letterId);
                }
            });
        });
    }
    
    /**
     * Initialise le modal de confirmation
     */
    function initializeModal() {
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', function() {
                if (currentLetterId) {
                    confirmDelete(currentLetterId);
                }
            });
        }
        
        if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', function() {
                closeModal();
            });
        }
        
        // Fermer le modal en cliquant à l'extérieur
        if (confirmationModal) {
            confirmationModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
        }
        
        // Fermer avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && confirmationModal.classList.contains('active')) {
                closeModal();
            }
        });
    }
    
    
    /**
     * Affiche une lettre
     */
    function viewLetter(letterId) {
        console.log('Affichage de la lettre:', letterId);
        window.location.href = `/game/view-letter?id=${letterId}`;
    }
    
    /**
     * Édite une lettre
     */
    function editLetter(letterId) {
        console.log('Édition de la lettre:', letterId);
        window.location.href = `/game/edit-letter?id=${letterId}`;
    }
    
    /**
     * Supprime une lettre
     */
    function deleteLetter(letterId) {
        console.log('Suppression de la lettre:', letterId);
        currentLetterId = letterId;
        openModal();
    }
    
    /**
     * Duplique une lettre
     */
    function duplicateLetter(letterId) {
        console.log('Duplication de la lettre:', letterId);
        if (confirm('Êtes-vous sûr de vouloir dupliquer cette lettre ?')) {
            window.location.href = `/game/duplicate-letter?id=${letterId}`;
        }
    }
    
    /**
     * Ouvre le modal de confirmation
     */
    function openModal() {
        if (confirmationModal) {
            confirmationModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    
    /**
     * Ferme le modal de confirmation
     */
    function closeModal() {
        if (confirmationModal) {
            confirmationModal.classList.remove('active');
            document.body.style.overflow = '';
            currentLetterId = null;
        }
    }
    
    /**
     * Confirme la suppression
     */
    function confirmDelete(letterId) {
        console.log('Confirmation de suppression:', letterId);
        
        // Créer un formulaire de suppression
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/game/delete-letter';
        
        const letterIdInput = document.createElement('input');
        letterIdInput.type = 'hidden';
        letterIdInput.name = 'letter_id';
        letterIdInput.value = letterId;
        
        form.appendChild(letterIdInput);
        document.body.appendChild(form);
        form.submit();
    }
    
});
