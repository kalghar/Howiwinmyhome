/**
 * ADMINISTRATION USERS - HOW I WIN MY HOME V1
 * 
 * JavaScript pour la gestion des utilisateurs
 * Version optimisée - utilise les classes existantes
 */

// Variables globales
let currentUserId = null;

// Fonctions de gestion des utilisateurs
function viewUser(userId) {
    console.log('Voir utilisateur:', userId);
    currentUserId = userId;
    // TODO: Implémenter la visualisation détaillée
    alert(`Visualisation de l'utilisateur ${userId}`);
}

function editUser(userId) {
    console.log('Modifier utilisateur:', userId);
    currentUserId = userId;
    // TODO: Implémenter la modification
    alert(`Modification de l'utilisateur ${userId}`);
}

function deleteUser(userId) {
    console.log('Supprimer utilisateur:', userId);
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) {
        // TODO: Implémenter l'appel AJAX
        console.log(`Utilisateur ${userId} supprimé`);
        // Recharger la page ou supprimer l'élément
        location.reload();
    }
}

// Initialisation de la page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin users page loaded - Version optimisée');
    
    // Délégation d'événements pour les boutons d'action
    const adminTable = document.querySelector('.admin-table');
    if (adminTable) {
        adminTable.addEventListener('click', function(event) {
            const button = event.target.closest('button[data-action]');
            if (!button) return;
            
            const action = button.getAttribute('data-action');
            const userId = button.getAttribute('data-user-id');
            
            if (!userId) return;
            
            switch (action) {
                case 'view':
                    viewUser(userId);
                    break;
                case 'edit':
                    editUser(userId);
                    break;
                case 'delete':
                    deleteUser(userId);
                    break;
                default:
                    console.log('Action non reconnue:', action);
            }
        });
    }
    
    console.log('Gestionnaires d\'événements initialisés');
});