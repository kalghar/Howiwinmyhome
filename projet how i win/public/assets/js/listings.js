/**
 * LISTINGS.JS - HOW I WIN MY HOME V1
 * 
 * Gestion des filtres et interactions de la page des annonces
 * 
 * @author How I Win My Home Team
 * @version 1.0.0
 * @since 2024-12-09
 */

document.addEventListener('DOMContentLoaded', function () {
    // Gestion des filtres
    const filterInputs = document.querySelectorAll('.filter-input-mascabanids');
    const filterSelects = document.querySelectorAll('.filter-select-mascabanids');
    const filterForm = document.querySelector('.filters-form-mascabanids');

    // Auto-submit du formulaire de filtres DÉSACTIVÉ
    // Pour éviter les rechargements automatiques trop fréquents
    // Les utilisateurs peuvent utiliser le bouton "Appliquer les filtres"
    /*
    filterInputs.forEach(input => {
        input.addEventListener('input', function () {
            // Debounce pour éviter trop de requêtes
            clearTimeout(this.filterTimeout);
            this.filterTimeout = setTimeout(() => {
                if (filterForm) {
                    filterForm.submit();
                }
            }, 500);
        });
    });
    */

    filterSelects.forEach(select => {
        select.addEventListener('change', function () {
            if (filterForm) {
                filterForm.submit();
            }
        });
    });

    // Gestion du bouton "Appliquer les filtres"
    const submitButton = document.querySelector('.btn-filter-primary-mascabanids');
    if (submitButton && filterForm) {
        submitButton.addEventListener('click', function (e) {
            e.preventDefault(); // Empêcher la soumission normale

            // Récupérer tous les champs du formulaire
            const formData = new FormData(filterForm);

            // Construire l'URL avec les paramètres
            let url = '/listings?';
            let params = [];

            for (let [key, value] of formData.entries()) {
                if (value.trim() !== '') {
                    params.push(key + '=' + encodeURIComponent(value));
                }
            }

            url += params.join('&');

            // Rediriger vers l'URL
            window.location.href = url;
        });
    }

    // Gestion des cartes d'annonces
    const listingCards = document.querySelectorAll('.listing-card');
    listingCards.forEach(card => {
        card.addEventListener('click', function (e) {
            // Éviter le clic si on clique sur un bouton ou lien
            if (e.target.tagName === 'BUTTON' || e.target.tagName === 'A' ||
                e.target.closest('button') || e.target.closest('a')) {
                return;
            }

            // Navigation vers la page de détail
            const listingId = this.dataset.listingId;
            if (listingId) {
                window.location.href = `/listings/view?id=${listingId}`;
            }
        });
    });

    // Animation des éléments
    const statItems = document.querySelectorAll('.stat-item');
    statItems.forEach((item, index) => {
        setTimeout(() => {
            item.classList.add('animate');
        }, index * 100);
    });

    const listingCardsAnimate = document.querySelectorAll('.listing-card');
    listingCardsAnimate.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('animate');
        }, index * 50);
    });

    // Gestion des barres de progression
    const progressBars = document.querySelectorAll('.progress-fill');
    progressBars.forEach(bar => {
        const width = bar.dataset.width || 0;
        setTimeout(() => {
            bar.style.width = width + '%';
        }, 500);
    });
});
