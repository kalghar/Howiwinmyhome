/**
 * DASHBOARD.JS - HOW I WIN MY HOME V1
 * ========================================
 *
 * FICHIER JAVASCRIPT DU TABLEAU DE BORD
 * Gestion des interactions et animations
 *
 * AUTEUR : How I Win My Home Team
 * VERSION : 1.0.0
 * DATE : 2025-01-19
 * ========================================
 */

document.addEventListener('DOMContentLoaded', function() {
    
    
    
    // ========================================
    // GESTION DES MESSAGES FLASH
    // ========================================
    
    const successMessage = document.querySelector('.alert-success');
    const errorMessage = document.querySelector('.alert-error');
    
    if (successMessage) {
        showNotification(successMessage.textContent.trim(), 'success');
    }
    
    if (errorMessage) {
        showNotification(errorMessage.textContent.trim(), 'error');
    }
    
    // ========================================
    // FONCTIONS UTILITAIRES
    // ========================================
    
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
            <span>${message}</span>
        `;
        
        // Styles pour la notification
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            z-index: 1000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 400px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        `;
        
        if (type === 'success') {
            notification.style.background = '#10b981';
        } else {
            notification.style.background = '#ef4444';
        }
        
        document.body.appendChild(notification);
        
        // Animation d'entrée
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Suppression automatique
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    // ========================================
    // GESTION DES INTERACTIONS
    // ========================================
    
    // Effet hover sur les cartes
    const interactiveCards = document.querySelectorAll('.balance-card, .stat-card');
    interactiveCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // ========================================
    // INITIALISATION FINALE
    // ========================================
    
    console.log('Dashboard.js chargé avec succès');
});
