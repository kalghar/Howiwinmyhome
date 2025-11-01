/**
 * JURY DASHBOARD - HOW I WIN MY HOME V1
 * 
 * Gestion du tableau de bord du jury
 * 
 * @author How I Win My Home Team
 * @version 1.0.0
 * @since 2024-12-09
 */

document.addEventListener('DOMContentLoaded', function() {
    // Animation des éléments au chargement
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('animate');
        }, index * 100);
    });
    
    const actionCards = document.querySelectorAll('.action-card');
    actionCards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('animate');
        }, index * 150);
    });
    
    const listingItems = document.querySelectorAll('.listing-item');
    listingItems.forEach((item, index) => {
        setTimeout(() => {
            item.classList.add('animate');
        }, index * 50);
    });
    
    const evaluationItems = document.querySelectorAll('.evaluation-item');
    evaluationItems.forEach((item, index) => {
        setTimeout(() => {
            item.classList.add('animate');
        }, index * 50);
    });
    
    // Gestion des barres de progression
    const progressBars = document.querySelectorAll('.evaluation-progress-fill');
    progressBars.forEach(bar => {
        const width = bar.dataset.width || 0;
        setTimeout(() => {
            bar.style.width = width + '%';
        }, 500);
    });
    
    // Gestion des actions rapides
    const quickActions = document.querySelectorAll('.action-card');
    quickActions.forEach(action => {
        action.addEventListener('mouseenter', function() {
            this.classList.add('hover');
        });
        
        action.addEventListener('mouseleave', function() {
            this.classList.remove('hover');
        });
    });
    
    // Gestion des liens rapides
    const quickLinks = document.querySelectorAll('.quick-link');
    quickLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Animation de clic
            this.classList.add('clicked');
            setTimeout(() => {
                this.classList.remove('clicked');
            }, 200);
        });
    });
    
    // Mise à jour en temps réel des statistiques (si WebSocket disponible)
    if (typeof WebSocket !== 'undefined') {
        // TODO: Implémenter la mise à jour en temps réel via WebSocket
        console.log('WebSocket support détecté - mise à jour temps réel disponible');
    }
    
    // Gestion des notifications
    const notificationElements = document.querySelectorAll('[data-notification]');
    notificationElements.forEach(element => {
        element.addEventListener('click', function() {
            const message = this.dataset.notification;
            if (window.App && window.App.getManager('notifications')) {
                window.App.getManager('notifications').show(message, 'info');
            }
        });
    });
    
    // Auto-refresh des données toutes les 5 minutes
    setInterval(() => {
        // TODO: Implémenter le refresh automatique des données
        console.log('Auto-refresh des données du dashboard');
    }, 300000); // 5 minutes
});
