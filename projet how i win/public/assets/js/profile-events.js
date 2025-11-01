/**
 * GESTIONNAIRE D'ÉVÉNEMENTS PROFILE - HOW I WIN MY HOME
 * ===================================================
 * 
 * Ce fichier gère tous les événements de la page de profil utilisateur
 * en remplacement des onclick inline pour la sécurité.
 * 
 * FONCTIONNALITÉS :
 * - Gestion des modales (ouverture/fermeture)
 * - Gestion des formulaires (sauvegarde)
 * - Gestion des actions (suppression, téléchargement)
 * 
 * AUTEUR : How I Win My Home Team
 * VERSION : 1.0.0
 * DATE : Décembre 2024
 * ===================================================
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ========================================
    // GESTION DES MODALES
    // ========================================
    
    // Ouverture de la modale d'édition
    const openEditModalBtn = document.querySelector('.js-open-edit-modal');
    if (openEditModalBtn) {
        openEditModalBtn.addEventListener('click', function() {
            const modal = document.getElementById('editProfileModal');
            if (modal) {
                modal.style.display = 'flex';
                modal.classList.add('active');
                document.body.classList.add('modal-open');
            }
        });
    }
    
    // Fermeture de la modale d'édition
    const closeEditModalBtns = document.querySelectorAll('.js-close-edit-modal');
    closeEditModalBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = document.getElementById('editProfileModal');
            if (modal) {
                modal.style.display = 'none';
                modal.classList.remove('active');
                document.body.classList.remove('modal-open');
            }
        });
    });
    
    // Ouverture de la modale de changement de mot de passe
    const openChangePasswordModalBtn = document.querySelector('.js-open-change-password-modal');
    if (openChangePasswordModalBtn) {
        openChangePasswordModalBtn.addEventListener('click', function() {
            const modal = document.getElementById('changePasswordModal');
            if (modal) {
                modal.style.display = 'flex';
                modal.classList.add('active');
                document.body.classList.add('modal-open');
            }
        });
    }
    
    // Fermeture de la modale de changement de mot de passe
    const closeChangePasswordModalBtns = document.querySelectorAll('.js-close-change-password-modal');
    closeChangePasswordModalBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = document.getElementById('changePasswordModal');
            if (modal) {
                modal.style.display = 'none';
                modal.classList.remove('active');
                document.body.classList.remove('modal-open');
            }
        });
    });
    
    // Ouverture de la modale de suppression
    const openDeleteModalBtn = document.querySelector('.js-open-delete-modal');
    if (openDeleteModalBtn) {
        openDeleteModalBtn.addEventListener('click', function() {
            const modal = document.getElementById('deleteAccountModal');
            if (modal) {
                modal.style.display = 'flex';
                modal.classList.add('active');
                document.body.classList.add('modal-open');
            }
        });
    }
    
    // Fermeture de la modale de suppression
    const closeDeleteModalBtns = document.querySelectorAll('.js-close-delete-modal');
    closeDeleteModalBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = document.getElementById('deleteAccountModal');
            if (modal) {
                modal.style.display = 'none';
                modal.classList.remove('active');
                document.body.classList.remove('modal-open');
            }
        });
    });
    
    // ========================================
    // GESTION DES FORMULAIRES
    // ========================================
    
    // Sauvegarde du profil
    const saveProfileBtn = document.querySelector('.js-save-profile');
    if (saveProfileBtn) {
        saveProfileBtn.addEventListener('click', function() {
            // Appeler la fonction existante saveProfile()
            if (typeof saveProfile === 'function') {
                saveProfile();
            }
        });
    }
    
    // Sauvegarde du mot de passe
    const savePasswordBtn = document.querySelector('.js-save-password');
    if (savePasswordBtn) {
        savePasswordBtn.addEventListener('click', function() {
            // Appeler la fonction existante savePassword()
            if (typeof savePassword === 'function') {
                savePassword();
            }
        });
    }
    
    // Confirmation de suppression
    const confirmDeleteBtn = document.querySelector('.js-confirm-delete-account');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            // Appeler la fonction existante confirmDeleteAccount()
            if (typeof confirmDeleteAccount === 'function') {
                confirmDeleteAccount();
            }
        });
    }
    
    // ========================================
    // GESTION DES ACTIONS
    // ========================================
    
    // Téléchargement des données
    const downloadDataBtn = document.querySelector('.js-download-data');
    if (downloadDataBtn) {
        downloadDataBtn.addEventListener('click', function() {
            // Appeler la fonction existante downloadData()
            if (typeof downloadData === 'function') {
                downloadData();
            }
        });
    }
    
    // ========================================
    // FERMETURE DES MODALES AVEC ÉCHAP
    // ========================================
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Fermer toutes les modales ouvertes
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (modal.classList.contains('active')) {
                    modal.style.display = 'none';
                    modal.classList.remove('active');
                    document.body.classList.remove('modal-open');
                }
            });
        }
    });
    
    // ========================================
    // FERMETURE DES MODALES EN CLIQUANT À L'EXTÉRIEUR
    // ========================================
    
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
            e.target.classList.remove('active');
            document.body.classList.remove('modal-open');
        }
    });
    
});
