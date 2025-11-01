/**
 * ADMINISTRATION LISTINGS - HOW I WIN MY HOME V1
 * 
 * JavaScript pour la gestion des annonces
 */

// Fonctions de gestion des annonces
function viewListing(listingId) {
    console.log('Voir annonce:', listingId);
    // Logique de visualisation
    alert(`Visualisation de l'annonce ${listingId}`);
}

function editListing(listingId) {
    console.log('Modifier annonce:', listingId);
    // Logique de modification
    alert(`Modification de l'annonce ${listingId}`);
}

function approveListing(listingId) {
    console.log('Approuver annonce:', listingId);
    if (confirm('Êtes-vous sûr de vouloir approuver cette annonce ?')) {
        // Logique d'approbation
        alert(`Annonce ${listingId} approuvée`);
    }
}

function rejectListing(listingId) {
    console.log('Rejeter annonce:', listingId);
    if (confirm('Êtes-vous sûr de vouloir rejeter cette annonce ?')) {
        // Logique de rejet
        alert(`Annonce ${listingId} rejetée`);
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Délégation d'événements pour les boutons d'action
    const listingsTable = document.querySelector('.admin-table');
    if (listingsTable) {
        listingsTable.addEventListener('click', function(event) {
            const button = event.target.closest('button[data-action]');
            if (button) {
                const action = button.getAttribute('data-action');
                const listingId = button.getAttribute('data-listing-id');
                
                switch(action) {
                    case 'view':
                        viewListing(listingId);
                        break;
                    case 'edit':
                        editListing(listingId);
                        break;
                    case 'approve':
                        approveListing(listingId);
                        break;
                    case 'reject':
                        rejectListing(listingId);
                        break;
                }
            }
        });
    }
});
