/**
 * QCM JavaScript - HOW I WIN MY HOME V1
 * 
 * Gère la logique du questionnaire de qualification
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('QCM JavaScript chargé');
    
    // Éléments du DOM
    const qcmForm = document.querySelector('.qcm-form');
    const timerElement = document.querySelector('.qcm-timer');
    const progressElement = document.querySelector('.qcm-progress');
    const submitButton = document.querySelector('.btn-qcm-primary');
    
    // Configuration
    const timeLimit = 600; // 10 minutes en secondes
    let timeRemaining = timeLimit;
    let timerInterval;
    
    // Initialiser le timer
    if (timerElement) {
        startTimer();
    }
    
    // Gérer les réponses
    const choiceOptions = document.querySelectorAll('.choice-option');
    choiceOptions.forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                updateProgress();
            }
        });
    });
    
    // Gérer la soumission du formulaire
    if (qcmForm) {
        qcmForm.addEventListener('submit', function(e) {
            // Vérifier que toutes les questions sont répondues
            const totalQuestions = document.querySelectorAll('input[type="radio"]').length / 3;
            const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
            
            if (answeredQuestions < totalQuestions) {
                e.preventDefault();
                showNotification('Veuillez répondre à toutes les questions avant de soumettre.', 'error');
                return;
            }
            
            // Si toutes les questions sont répondues, laisser la soumission normale
            console.log('✅ QCM: Toutes les questions répondues, soumission normale autorisée');
        });
    }
    
    /**
     * Démarrer le timer
     */
    function startTimer() {
        timerInterval = setInterval(() => {
            timeRemaining--;
            updateTimerDisplay();
            
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                autoSubmit();
            }
        }, 1000);
        
        updateTimerDisplay();
    }
    
    /**
     * Mettre à jour l'affichage du timer
     */
    function updateTimerDisplay() {
        if (!timerElement) return;
        
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        timerElement.textContent = `Temps restant : ${timeString}`;
        
        // Changer la couleur si moins de 2 minutes
        if (timeRemaining <= 120) {
            timerElement.classList.add('warning');
        }
    }
    
    /**
     * Mettre à jour la progression
     */
    function updateProgress() {
        if (!progressElement) return;
        
        const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
        const totalQuestions = document.querySelectorAll('input[type="radio"]').length / 3; // 3 choix par question
        
        // Mettre à jour les éléments de progression
        const progressItems = progressElement.querySelectorAll('.qcm-progress-item');
        progressItems.forEach(item => {
            if (item.textContent.includes('questions répondues')) {
                item.innerHTML = `<i class="fas fa-check-circle"></i> ${answeredQuestions} / ${totalQuestions} questions répondues`;
            }
            if (item.textContent.includes('Progression')) {
                const percentage = Math.round((answeredQuestions / totalQuestions) * 100);
                item.innerHTML = `<i class="fas fa-chart-line"></i> Progression : ${percentage}%`;
            }
        });
    }
    
    /**
     * Soumettre le QCM automatiquement
     */
    function autoSubmit() {
        console.log('Soumission automatique du QCM');
        showNotification('Temps écoulé ! Le questionnaire est soumis automatiquement.', 'warning');
        
        // Soumettre le formulaire normalement
        if (qcmForm) {
            qcmForm.submit();
        }
    }
    
    /**
     * Soumettre le QCM
     */
    function submitQCM() {
        if (!qcmForm) return;
        
        const formData = new FormData(qcmForm);
        const answers = {};
        
        // Collecter les réponses
        for (let [key, value] of formData.entries()) {
            if (key.startsWith('answers[')) {
                const questionId = key.match(/answers\[(\d+)\]/)[1];
                answers[questionId] = value;
            }
        }
        
        // Vérifier que toutes les questions sont répondues
        const totalQuestions = document.querySelectorAll('input[type="radio"]').length / 3;
        const answeredQuestions = Object.keys(answers).length;
        
        if (answeredQuestions < totalQuestions) {
            showNotification('Veuillez répondre à toutes les questions avant de soumettre.', 'error');
            return;
        }
        
        // Désactiver le bouton de soumission
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Soumission en cours...';
        }
        
        // Soumettre le formulaire
        qcmForm.submit();
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
        
        .qcm-notification {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
    `;
    document.head.appendChild(style);
    
    // Initialiser la progression
    updateProgress();
    
    console.log('QCM JavaScript initialisé avec succès');
});
