/**
 * ADMINISTRATION SETTINGS - HOW I WIN MY HOME V1
 * 
 * JavaScript pour la gestion des paramètres administratifs
 */

function resetSettings() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser tous les paramètres aux valeurs par défaut ?')) {
        // Réinitialiser le formulaire
        document.querySelector('.settings-form').reset();
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    const resetBtn = document.getElementById('reset-settings-btn');
    if (resetBtn) {
        resetBtn.addEventListener('click', resetSettings);
    }
});
