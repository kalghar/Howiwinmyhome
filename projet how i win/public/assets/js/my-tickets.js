/**
 * MY TICKETS - HOW I WIN MY HOME V1
 * 
 * Gestion des tickets de l'utilisateur
 * 
 * @author How I Win My Home Team
 * @version 1.0.0
 * @since 2024-12-09
 */

document.addEventListener('DOMContentLoaded', function() {
    // Gestion des onglets personnalisés
    const tabButtons = document.querySelectorAll('.my-tickets-tab-button');
    const tabPanes = document.querySelectorAll('.my-tickets-tab-pane');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-tab');
            
            // Retirer la classe active de tous les boutons
            tabButtons.forEach(btn => btn.classList.remove('active'));
            // Ajouter la classe active au bouton cliqué
            this.classList.add('active');
            
            // Retirer la classe active de tous les panes
            tabPanes.forEach(pane => {
                pane.classList.remove('active');
            });
            
            // Ajouter la classe active au pane ciblé
            const targetPane = document.getElementById(targetId);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });
    
    // Gestion des actions sur les cartes de tickets
    const ticketActions = document.querySelectorAll('.my-tickets-card-button');
    ticketActions.forEach(button => {
        button.addEventListener('click', function(e) {
            // Animation de clic
            this.classList.add('clicked');
            setTimeout(() => {
                this.classList.remove('clicked');
            }, 200);
        });
    });
    
    // Gestion des hover effects sur les cartes
    const ticketCards = document.querySelectorAll('.my-tickets-card');
    ticketCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('hover');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('hover');
        });
    });
    
    // Animation des badges de comptage
    const badges = document.querySelectorAll('.my-tickets-badge');
    badges.forEach(badge => {
        const count = parseInt(badge.textContent);
        if (!isNaN(count) && count > 0) {
            animateBadge(badge, count);
        }
    });
    
    // Fonction d'animation des badges
    function animateBadge(element, finalCount) {
        let currentCount = 0;
        const increment = Math.ceil(finalCount / 20);
        const interval = setInterval(() => {
            currentCount += increment;
            if (currentCount >= finalCount) {
                currentCount = finalCount;
                clearInterval(interval);
            }
            element.textContent = currentCount;
        }, 50);
    }
    
    // Gestion des actions rapides
    const quickActions = document.querySelectorAll('.my-tickets-action');
    quickActions.forEach(action => {
        action.addEventListener('click', function() {
            // Animation de clic
            this.classList.add('clicked');
            setTimeout(() => {
                this.classList.remove('clicked');
            }, 200);
        });
    });
    
    // Gestion des états vides
    const emptyStates = document.querySelectorAll('.my-tickets-empty-state');
    emptyStates.forEach(state => {
        // Animation d'apparition
        setTimeout(() => {
            state.classList.add('show');
        }, 500);
    });
    
    // Gestion des alertes d'action requise
    const alertSection = document.querySelector('.my-tickets-alert');
    if (alertSection) {
        // Animation d'apparition de l'alerte
        setTimeout(() => {
            alertSection.classList.add('show');
        }, 300);
        
        // Auto-masquage après 10 secondes
        setTimeout(() => {
            alertSection.classList.add('hide');
            setTimeout(() => {
                alertSection.style.display = 'none';
            }, 300);
        }, 10000);
    }
    
    // Gestion des tableaux (pour l'historique)
    const tableRows = document.querySelectorAll('.my-tickets-table tbody tr');
    tableRows.forEach((row, index) => {
        setTimeout(() => {
            row.classList.add('animate');
        }, index * 50);
    });
    
    // Gestion des filtres de statut (si présents)
    const statusFilters = document.querySelectorAll('.status-filter');
    statusFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            const status = this.dataset.status;
            filterTicketsByStatus(status);
        });
    });
    
    // Fonction de filtrage par statut
    function filterTicketsByStatus(status) {
        const ticketCards = document.querySelectorAll('.my-tickets-card');
        
        ticketCards.forEach(card => {
            const cardStatus = card.dataset.status;
            
            if (status === 'all' || cardStatus === status) {
                card.style.display = 'block';
                card.classList.add('show');
            } else {
                card.style.display = 'none';
                card.classList.remove('show');
            }
        });
    }
    
    // Auto-refresh des données toutes les 3 minutes
    setInterval(() => {
        // TODO: Implémenter le refresh automatique des données
        console.log('Auto-refresh des tickets utilisateur');
    }, 180000); // 3 minutes
});
