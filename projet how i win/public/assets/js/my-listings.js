/**
 * MY LISTINGS - HOW I WIN MY HOME V1
 * 
 * Gestion des annonces de l'utilisateur
 * 
 * @author How I Win My Home Team
 * @version 1.0.0
 * @since 2024-12-09
 */

document.addEventListener('DOMContentLoaded', function() {
    // Animation des éléments
    const statItems = document.querySelectorAll('.stat-item');
    statItems.forEach((item, index) => {
        setTimeout(() => {
            item.classList.add('animate');
        }, index * 100);
    });
    
    const listingCards = document.querySelectorAll('.listing-card');
    listingCards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('animate');
        }, index * 50);
    });
    
    // Gestion des actions sur les cartes d'annonces
    const listingActions = document.querySelectorAll('.listing-actions .btn');
    listingActions.forEach(button => {
        button.addEventListener('click', function(e) {
            // Animation de clic
            this.classList.add('clicked');
            setTimeout(() => {
                this.classList.remove('clicked');
            }, 200);
        });
    });
    
    // Gestion des hover effects
    const listingCardsHover = document.querySelectorAll('.listing-card');
    listingCardsHover.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('hover');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('hover');
        });
    });
    
    // Gestion des statistiques animées
    const statNumbers = document.querySelectorAll('.stat-number');
    statNumbers.forEach(stat => {
        const finalValue = parseInt(stat.textContent);
        if (!isNaN(finalValue)) {
            animateNumber(stat, 0, finalValue, 1000);
        }
    });
    
    // Fonction d'animation des nombres
    function animateNumber(element, start, end, duration) {
        const startTime = performance.now();
        
        function updateNumber(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const currentValue = Math.floor(start + (end - start) * progress);
            element.textContent = currentValue.toLocaleString();
            
            if (progress < 1) {
                requestAnimationFrame(updateNumber);
            }
        }
        
        requestAnimationFrame(updateNumber);
    }
    
    // Gestion des filtres (si présents)
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            filterListings(filter);
        });
    });
    
    // Fonction de filtrage des annonces
    function filterListings(filter) {
        const listingCards = document.querySelectorAll('.listing-card');
        
        listingCards.forEach(card => {
            const status = card.dataset.status;
            
            if (filter === 'all' || status === filter) {
                card.style.display = 'block';
                card.classList.add('show');
            } else {
                card.style.display = 'none';
                card.classList.remove('show');
            }
        });
        
        // Mise à jour du compteur
        updateListingCount();
    }
    
    // Fonction de mise à jour du compteur
    function updateListingCount() {
        const visibleCards = document.querySelectorAll('.listing-card.show');
        const countElement = document.querySelector('.listings-count .count-text');
        
        if (countElement) {
            const count = visibleCards.length;
            countElement.textContent = `${count} annonce${count > 1 ? 's' : ''} trouvée${count > 1 ? 's' : ''}`;
        }
    }
    
    // Gestion des actions rapides
    const quickActions = document.querySelectorAll('.header-actions .btn');
    quickActions.forEach(action => {
        action.addEventListener('click', function() {
            // Animation de clic
            this.classList.add('clicked');
            setTimeout(() => {
                this.classList.remove('clicked');
            }, 200);
        });
    });
    
    // Auto-refresh des données toutes les 5 minutes
    setInterval(() => {
        // TODO: Implémenter le refresh automatique des données
        console.log('Auto-refresh des annonces utilisateur');
    }, 300000); // 5 minutes
});
