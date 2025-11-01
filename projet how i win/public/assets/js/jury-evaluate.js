/**
 * JURY EVALUATE LETTERS - HOW I WIN MY HOME V1
 * 
 * Gestion de l'évaluation des lettres de motivation
 * 
 * @author How I Win My Home Team
 * @version 1.0.0
 * @since 2024-12-09
 */

document.addEventListener('DOMContentLoaded', function() {
    const evaluationForms = document.querySelectorAll('.evaluation-form-content');
    const saveAllBtn = document.getElementById('save-all-evaluations-btn');
    
    // Gestion des formulaires d'évaluation
    evaluationForms.forEach(form => {
        const letterId = form.dataset.letterId;
        const scoreInputs = form.querySelectorAll('.score-input');
        const totalScoreElement = document.getElementById(`total-score-${letterId}`);
        const resetBtn = form.querySelector('.reset-evaluation-btn');
        
        // Calcul automatique du score total
        scoreInputs.forEach(input => {
            input.addEventListener('input', function() {
                updateTotalScore(form, totalScoreElement);
            });
        });
        
        // Réinitialisation du formulaire
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                resetForm(form, totalScoreElement);
            });
        }
        
        // Soumission du formulaire
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            submitEvaluation(form, letterId);
        });
    });
    
    // Sauvegarde de toutes les évaluations
    if (saveAllBtn) {
        saveAllBtn.addEventListener('click', function() {
            saveAllEvaluations();
        });
    }
    
    // Fonction de mise à jour du score total
    function updateTotalScore(form, totalElement) {
        const scoreInputs = form.querySelectorAll('.score-input');
        let total = 0;
        
        scoreInputs.forEach(input => {
            const value = parseInt(input.value) || 0;
            total += value;
        });
        
        if (totalElement) {
            totalElement.textContent = total;
            
            // Changement de couleur selon le score
            if (total >= 80) {
                totalElement.className = 'total-value excellent';
            } else if (total >= 60) {
                totalElement.className = 'total-value good';
            } else if (total >= 40) {
                totalElement.className = 'total-value average';
            } else {
                totalElement.className = 'total-value poor';
            }
        }
    }
    
    // Fonction de réinitialisation du formulaire
    function resetForm(form, totalElement) {
        const scoreInputs = form.querySelectorAll('.score-input');
        const commentsTextarea = form.querySelector('.comments-textarea');
        
        scoreInputs.forEach(input => {
            input.value = '';
        });
        
        if (commentsTextarea) {
            commentsTextarea.value = '';
        }
        
        if (totalElement) {
            totalElement.textContent = '0';
            totalElement.className = 'total-value';
        }
        
        // Notification
        if (window.App && window.App.getManager('notifications')) {
            window.App.getManager('notifications').show(
                'Formulaire réinitialisé',
                'info'
            );
        }
    }
    
    // Fonction de soumission d'une évaluation
    function submitEvaluation(form, letterId) {
        const formData = new FormData(form);
        const evaluationData = {
            letter_id: letterId,
            criteria: {},
            comments: formData.get('comments') || '',
            total_score: 0
        };
        
        // Récupération des scores par critère
        const scoreInputs = form.querySelectorAll('.score-input');
        scoreInputs.forEach(input => {
            const criterionId = input.name.replace('criterion_', '');
            const score = parseInt(input.value) || 0;
            evaluationData.criteria[criterionId] = score;
            evaluationData.total_score += score;
        });
        
        // Validation du score total
        if (evaluationData.total_score === 0) {
            if (window.App && window.App.getManager('notifications')) {
                window.App.getManager('notifications').show(
                    'Veuillez attribuer au moins un point à cette lettre',
                    'warning'
                );
            }
            return;
        }
        
        // Envoi de l'évaluation
        fetch('/api/jury/evaluate-letter', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(evaluationData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mise à jour de l'interface
                const letterCard = form.closest('.letter-card');
                if (letterCard) {
                    const statusBadge = letterCard.querySelector('.status-badge');
                    if (statusBadge) {
                        statusBadge.className = 'status-badge evaluated';
                        statusBadge.innerHTML = '<i class="fas fa-star"></i> Évaluée';
                    }
                }
                
                // Notification de succès
                if (window.App && window.App.getManager('notifications')) {
                    window.App.getManager('notifications').show(
                        'Évaluation sauvegardée avec succès !',
                        'success'
                    );
                }
                
                // Mise à jour du compteur
                updateProgress();
                
                // Désactivation du formulaire
                form.classList.add('evaluated');
                const submitBtn = form.querySelector('.submit-evaluation-btn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-check"></i> Évaluée';
                }
            } else {
                throw new Error(data.message || 'Erreur lors de la sauvegarde');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            
            // Notification d'erreur
            if (window.App && window.App.getManager('notifications')) {
                window.App.getManager('notifications').show(
                    error.message || 'Erreur lors de la sauvegarde',
                    'error'
                );
            }
        });
    }
    
    // Fonction de sauvegarde de toutes les évaluations
    function saveAllEvaluations() {
        const pendingForms = document.querySelectorAll('.evaluation-form-content:not(.evaluated)');
        
        if (pendingForms.length === 0) {
            if (window.App && window.App.getManager('notifications')) {
                window.App.getManager('notifications').show(
                    'Toutes les évaluations ont déjà été sauvegardées',
                    'info'
                );
            }
            return;
        }
        
        // Confirmation
        if (!confirm(`Voulez-vous sauvegarder les ${pendingForms.length} évaluation(s) restante(s) ?`)) {
            return;
        }
        
        // Sauvegarde de chaque évaluation
        let savedCount = 0;
        pendingForms.forEach(form => {
            const letterId = form.dataset.letterId;
            submitEvaluation(form, letterId);
            savedCount++;
        });
        
        // Notification
        if (window.App && window.App.getManager('notifications')) {
            window.App.getManager('notifications').show(
                `${savedCount} évaluation(s) en cours de sauvegarde...`,
                'info'
            );
        }
    }
    
    // Fonction de mise à jour du progrès
    function updateProgress() {
        const evaluatedCards = document.querySelectorAll('.letter-card .evaluation-form.evaluated');
        const totalCards = document.querySelectorAll('.letter-card');
        const progressText = document.querySelector('.progress-text');
        const progressFill = document.querySelector('.progress-fill');
        
        if (progressText && progressFill) {
            const evaluatedCount = evaluatedCards.length;
            const totalCount = totalCards.length;
            const percentage = totalCount > 0 ? (evaluatedCount / totalCount) * 100 : 0;
            
            progressText.textContent = `${evaluatedCount} / ${totalCount} lettres évaluées`;
            progressFill.style.width = `${percentage}%`;
        }
    }
    
    // Animation des éléments
    const letterCards = document.querySelectorAll('.letter-card');
    letterCards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('animate');
        }, index * 100);
    });
});
