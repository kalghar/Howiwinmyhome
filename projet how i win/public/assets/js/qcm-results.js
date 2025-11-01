/**
 * QCM Results JavaScript - HOW I WIN MY HOME V1
 * 
 * Gère la logique de la page de résultats QCM
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('QCM Results JavaScript chargé');
    
    // Éléments du DOM
    const retryButton = document.querySelector('.btn-retry-qcm');
    const viewListingsButton = document.querySelector('.btn-view-listings');
    const helpButton = document.querySelector('.btn-help');
    
    // Gérer le bouton "Repasser le QCM"
    if (retryButton) {
        retryButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Vérifier si l'utilisateur peut repasser le QCM
            const canRetry = this.dataset.canRetry === 'true';
            
            if (!canRetry) {
                showNotification('Vous devez attendre 24h avant de repasser le QCM.', 'warning');
                return;
            }
            
            // Redirection vers le QCM
            const listingId = this.dataset.listingId;
            if (listingId) {
                window.location.href = `/game/qcm?listing_id=${listingId}`;
            }
        });
    }
    
    // Gérer le bouton "Voir les annonces"
    if (viewListingsButton) {
        viewListingsButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '/listings';
        });
    }
    
    // Gérer le bouton d'aide
    if (helpButton) {
        helpButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '/help';
        });
    }
    
    // Animation d'entrée pour les éléments
    animateElements();
    
    /**
     * Animer les éléments de la page
     */
    function animateElements() {
        const elements = document.querySelectorAll('.result-card, .result-details, .result-actions');
        
        elements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                element.style.transition = 'all 0.6s ease';
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, index * 200);
        });
    }
    
    /**
     * Afficher une notification
     */
    function showNotification(message, type = 'info') {
        // Créer l'élément de notification
        const notification = document.createElement('div');
        notification.className = `qcm-notification qcm-${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : type === 'warning' ? 'exclamation-circle' : 'info-circle'}"></i>
            ${message}
        `;
        
        // Ajouter les styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 1000;
            animation: slideIn 0.3s ease;
            max-width: 400px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        `;
        
        // Styles selon le type
        if (type === 'error') {
            notification.style.background = 'linear-gradient(135deg, #e74c3c, #c0392b)';
        } else if (type === 'warning') {
            notification.style.background = 'linear-gradient(135deg, #f39c12, #e67e22)';
        } else {
            notification.style.background = 'linear-gradient(135deg, #3498db, #2980b9)';
        }
        
        // Ajouter au DOM
        document.body.appendChild(notification);
        
        // Supprimer après 5 secondes
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }
    
    // Ajouter les styles d'animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
    
    console.log('QCM Results JavaScript initialisé avec succès');
});