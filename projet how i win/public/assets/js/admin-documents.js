/**
 * ADMINISTRATION DOCUMENTS - HOW I WIN MY HOME V1
 * 
 * JavaScript pour la gestion des documents administratifs
 * Version reconstruite - modales fonctionnelles
 */

// Variables globales
let currentDocumentId = null;

// Fonctions de gestion des modales
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('modal-hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('modal-hidden');
        document.body.style.overflow = '';
    }
}

function closeAllModals() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.classList.add('modal-hidden');
    });
    document.body.style.overflow = '';
}

// Fonction pour ouvrir la modale de consultation
function viewDocument(documentId) {
    currentDocumentId = documentId;
    
    // Charger les données du document
    loadDocumentData(documentId);
    
    // Ouvrir la modale
    openModal('documentModal');
}

// Fonction pour charger les données du document
function loadDocumentData(documentId) {
    // Simuler le chargement des données
    // En réalité, ceci ferait un appel AJAX
    const documentData = {
        id: documentId,
        name: 'Document d\'identité',
        user: 'Jean Dupont',
        date: new Date().toLocaleDateString('fr-FR'),
        size: '2.3 MB',
        type: 'Pièce d\'identité',
        status: 'En attente'
    };
    
    // Mettre à jour les éléments de la modale
    const elements = {
        documentId: document.getElementById('documentId'),
        documentUser: document.getElementById('documentUser'),
        documentDate: document.getElementById('documentDate'),
        documentSize: document.getElementById('documentSize'),
        documentType: document.getElementById('documentType')
    };
    
    if (elements.documentId) elements.documentId.textContent = documentData.id;
    if (elements.documentUser) elements.documentUser.textContent = documentData.user;
    if (elements.documentDate) elements.documentDate.textContent = documentData.date;
    if (elements.documentSize) elements.documentSize.textContent = documentData.size;
    if (elements.documentType) elements.documentType.textContent = documentData.type;
    
    // Mettre à jour les data-document-id des boutons
    const buttons = document.querySelectorAll('#documentModal [data-action]');
    buttons.forEach(button => {
        button.setAttribute('data-document-id', documentId);
    });
}

// Fonction pour ouvrir la modale de rejet
function rejectDocument(documentId) {
    currentDocumentId = documentId;
    openModal('rejectModal');
}

// Fonction pour confirmer le rejet
function confirmReject() {
    const reason = document.getElementById('rejectReason').value;
    const message = document.getElementById('rejectMessage').value;
    
    if (!reason) {
        alert('Veuillez sélectionner une raison de rejet.');
        return;
    }
    
    console.log('Rejet du document:', currentDocumentId, 'Raison:', reason, 'Message:', message);
    
    // Ici, on ferait l'appel AJAX pour rejeter le document
    // Pour l'instant, on simule juste la fermeture de la modale
    
    closeModal('rejectModal');
    
    // Réinitialiser le formulaire
    document.getElementById('rejectForm').reset();
    
    // Optionnel : recharger la liste des documents
    // location.reload();
}

// Fonctions d'action sur les documents
function downloadDocument(documentId) {
    console.log('Téléchargement du document:', documentId);
    // TODO: Implémenter le téléchargement
}

function verifyDocument(documentId) {
    console.log('Validation du document:', documentId);
    // TODO: Implémenter la validation
}

// Initialisation de la page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin documents page loaded - Version reconstruite');
    
    // Gestion des boutons d'action dans la liste
    const documentsQueue = document.querySelector('.documents-queue');
    if (documentsQueue) {
        documentsQueue.addEventListener('click', function(event) {
            const button = event.target.closest('[data-action]');
            if (!button) return;
            
            const action = button.getAttribute('data-action');
            const documentId = button.getAttribute('data-document-id');
            
            if (!documentId) return;
            
            switch (action) {
                case 'view':
                    viewDocument(documentId);
                    break;
                case 'download':
                    downloadDocument(documentId);
                    break;
                case 'verify':
                    verifyDocument(documentId);
                    break;
                case 'reject':
                    rejectDocument(documentId);
                    break;
                default:
                    console.log('Action non reconnue:', action);
            }
        });
    }
    
    // Gestion des boutons dans les modales
    document.addEventListener('click', function(event) {
        const button = event.target.closest('[data-action]');
        if (!button) return;
        
        const action = button.getAttribute('data-action');
        
        switch (action) {
            case 'close':
                closeAllModals();
                break;
            case 'download':
                if (currentDocumentId) downloadDocument(currentDocumentId);
                break;
            case 'verify':
                if (currentDocumentId) verifyDocument(currentDocumentId);
                closeModal('documentModal');
                break;
            case 'reject':
                if (currentDocumentId) rejectDocument(currentDocumentId);
                closeModal('documentModal');
                break;
            case 'confirm-reject':
                confirmReject();
                break;
        }
    });
    
    // Fermer les modales en cliquant sur l'overlay
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            closeAllModals();
        }
    });
    
    // Fermer les modales avec la touche Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAllModals();
        }
    });
    
    console.log('Gestionnaires d\'événements initialisés');
});