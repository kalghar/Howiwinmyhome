/**
 * TICKET BUY PAGE JAVASCRIPT - HOW I WIN MY HOME V1
 * 
 * Gère la logique d'achat de tickets
 */

document.addEventListener('DOMContentLoaded', function () {
    console.log('Ticket-buy.js chargé avec succès');

    const purchaseForm = document.querySelector('.purchase-form-content');

    if (purchaseForm) {
        console.log('Formulaire d\'achat trouvé');

        // Gérer la soumission du formulaire
        purchaseForm.addEventListener('submit', function (e) {
            console.log('🎫 Soumission du formulaire d\'achat de ticket');
            console.log('🎫 Formulaire a data-no-ajax:', this.hasAttribute('data-no-ajax'));
            console.log('🎫 Action du formulaire:', this.action);

            // TEMPORAIRE : Empêcher la soumission pour voir les logs
            // e.preventDefault(); // COMMENTÉ POUR PERMETTRE LA SOUMISSION
            console.log('🔒 Soumission empêchée temporairement pour debug');
            console.log('🔒 Données du formulaire:', new FormData(this));

            // Vérifier que le formulaire a data-no-ajax
            if (this.hasAttribute('data-no-ajax')) {
                console.log('✅ Formulaire avec data-no-ajax, soumission normale autorisée');
                console.log('🔓 Pour soumettre vraiment, commentez la ligne e.preventDefault()');
                return; // Laisser le formulaire se soumettre normalement
            }

            // Si pas de data-no-ajax, empêcher la soumission par défaut
            console.log('❌ Formulaire sans data-no-ajax, soumission empêchée');
        });

        // Ajouter un listener sur le bouton pour debug
        const submitButton = purchaseForm.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.addEventListener('click', function (e) {
                console.log('🔘 Bouton "Confirmer l\'achat" cliqué');
                console.log('🔘 Type du bouton:', this.type);
                console.log('🔘 Formulaire parent:', this.form);
            });
        }
    }
});
