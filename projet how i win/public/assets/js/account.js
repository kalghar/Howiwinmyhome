/**
 * JavaScript pour la page de dépôt
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Account.js chargé avec succès');
    
    // ========================================
    // GESTION DES MONTANTS PRÉDÉFINIS
    // ========================================
    
    const presetButtons = document.querySelectorAll('.preset-btn');
    const amountInput = document.getElementById('amount');
    
    if (presetButtons.length > 0 && amountInput) {
        presetButtons.forEach(button => {
            button.addEventListener('click', function() {
                const amount = this.dataset.amount;
                amountInput.value = amount;
                
                // Mise à jour visuelle
                presetButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Animation
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });
    }
    
    // ========================================
    // GESTION DU FORMULAIRE DE DÉPÔT
    // ========================================
    
    const depositForm = document.getElementById('deposit-form');
    if (depositForm) {
        depositForm.addEventListener('submit', function(e) {
            const amount = parseFloat(amountInput?.value || 0);
            
            // Validation côté client
            if (amount <= 0) {
                e.preventDefault();
                showNotification('Le montant doit être supérieur à 0€', 'error');
                return false;
            }
            
            if (amount > 1000) {
                e.preventDefault();
                showNotification('Le montant maximum est de 1000€', 'error');
                return false;
            }
            
            // Confirmation
            if (!confirm(`Êtes-vous sûr de vouloir ajouter ${amount}€ à votre compte ?`)) {
                e.preventDefault();
                return false;
            }
            
            // Désactivation du bouton pendant le traitement
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
            }
            
            // Laisser le formulaire se soumettre normalement
            return true;
        });
    }
    
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
});

/**
 * Afficher une notification
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
    
    // Ajouter les styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        animation: slideIn 0.3s ease;
    `;
    
    // Ajouter l'animation CSS
    if (!document.querySelector('#notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            .notification-content {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
        `;
        document.head.appendChild(style);
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
