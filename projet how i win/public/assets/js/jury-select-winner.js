/**
 * JURY SELECT WINNER - HOW I WIN MY HOME V1
 * 
 * Gestion de la sélection du gagnant final
 * 
 * @author How I Win My Home Team
 * @version 1.0.0
 * @since 2024-12-09
 */

document.addEventListener('DOMContentLoaded', function() {
    const selectWinnerBtns = document.querySelectorAll('.select-winner-btn');
    const autoSelectBtn = document.getElementById('auto-select-winner-btn');
    const manualSelectBtn = document.getElementById('manual-select-winner-btn');
    const notifyWinnerBtn = document.getElementById('notify-winner-btn');
    const changeWinnerBtn = document.getElementById('change-winner-btn');
    const selectModal = document.getElementById('select-winner-modal');
    
    let selectedCandidate = null;
    
    // Sélection manuelle du gagnant
    selectWinnerBtns.forEach(button => {
        button.addEventListener('click', function() {
            const candidateId = this.dataset.candidateId;
            const candidateName = this.dataset.candidateName;
            
            selectedCandidate = {
                id: candidateId,
                name: candidateName
            };
            
            document.getElementById('candidate-name-modal').textContent = candidateName;
            selectModal.style.display = 'block';
            selectModal.classList.add('active');
        });
    });
    
    // Sélection automatique du gagnant
    if (autoSelectBtn) {
        autoSelectBtn.addEventListener('click', function() {
            const firstCandidate = document.querySelector('.candidate-row');
            if (firstCandidate) {
                const candidateId = firstCandidate.dataset.candidateId;
                const candidateName = firstCandidate.querySelector('.candidate-name').textContent;
                
                selectedCandidate = {
                    id: candidateId,
                    name: candidateName
                };
                
                document.getElementById('candidate-name-modal').textContent = candidateName;
                selectModal.style.display = 'block';
                selectModal.classList.add('active');
            }
        });
    }
    
    // Sélection manuelle (ouverture de la modal)
    if (manualSelectBtn) {
        manualSelectBtn.addEventListener('click', function() {
            // La sélection se fait en cliquant sur les boutons individuels
            if (window.App && window.App.getManager('notifications')) {
                window.App.getManager('notifications').show(
                    'Cliquez sur le bouton "Sélectionner" à côté du candidat de votre choix',
                    'info'
                );
            }
        });
    }
    
    // Fermeture de la modal
    const closeButtons = document.querySelectorAll('.modal-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            selectModal.classList.remove('active');
            setTimeout(() => {
                selectModal.style.display = 'none';
            }, 300);
        });
    });
    
    // Fermeture par clic sur l'overlay
    if (selectModal) {
        const overlay = selectModal.querySelector('.modal-overlay');
        if (overlay) {
            overlay.addEventListener('click', function() {
                selectModal.classList.remove('active');
                setTimeout(() => {
                    selectModal.style.display = 'none';
                }, 300);
            });
        }
    }
    
    // Confirmation de sélection
    const confirmSelectBtn = document.querySelector('.confirm-select-btn');
    if (confirmSelectBtn) {
        confirmSelectBtn.addEventListener('click', function() {
            if (selectedCandidate) {
                selectWinner(selectedCandidate.id);
            }
        });
    }
    
    // Annulation de sélection
    const cancelSelectBtn = document.querySelector('.cancel-select-btn');
    if (cancelSelectBtn) {
        cancelSelectBtn.addEventListener('click', function() {
            selectModal.classList.remove('active');
            setTimeout(() => {
                selectModal.style.display = 'none';
            }, 300);
        });
    }
    
    // Notification du gagnant
    if (notifyWinnerBtn) {
        notifyWinnerBtn.addEventListener('click', function() {
            notifyWinner();
        });
    }
    
    // Changement du gagnant
    if (changeWinnerBtn) {
        changeWinnerBtn.addEventListener('click', function() {
            changeWinner();
        });
    }
    
    // Fonction de sélection du gagnant
    function selectWinner(candidateId) {
        fetch(`/api/jury/select-winner/${candidateId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Fermeture de la modal
                selectModal.classList.remove('active');
                setTimeout(() => {
                    selectModal.style.display = 'none';
                }, 300);
                
                // Notification de succès
                if (window.App && window.App.getManager('notifications')) {
                    window.App.getManager('notifications').show(
                        'Gagnant sélectionné avec succès !',
                        'success'
                    );
                }
                
                // Rechargement de la page
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                throw new Error(data.message || 'Erreur lors de la sélection');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            
            // Notification d'erreur
            if (window.App && window.App.getManager('notifications')) {
                window.App.getManager('notifications').show(
                    error.message || 'Erreur lors de la sélection',
                    'error'
                );
            }
        });
    }
    
    // Fonction de notification du gagnant
    function notifyWinner() {
        fetch('/api/jury/notify-winner', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Notification de succès
                if (window.App && window.App.getManager('notifications')) {
                    window.App.getManager('notifications').show(
                        'Gagnant notifié avec succès !',
                        'success'
                    );
                }
            } else {
                throw new Error(data.message || 'Erreur lors de la notification');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            
            // Notification d'erreur
            if (window.App && window.App.getManager('notifications')) {
                window.App.getManager('notifications').show(
                    error.message || 'Erreur lors de la notification',
                    'error'
                );
            }
        });
    }
    
    // Fonction de changement du gagnant
    function changeWinner() {
        if (confirm('Êtes-vous sûr de vouloir changer le gagnant ? Cette action réouvrira le concours.')) {
            fetch('/api/jury/change-winner', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Notification de succès
                    if (window.App && window.App.getManager('notifications')) {
                        window.App.getManager('notifications').show(
                            'Gagnant changé avec succès. Le concours est réouvert.',
                            'success'
                        );
                    }
                    
                    // Rechargement de la page
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Erreur lors du changement');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                
                // Notification d'erreur
                if (window.App && window.App.getManager('notifications')) {
                    window.App.getManager('notifications').show(
                        error.message || 'Erreur lors du changement',
                        'error'
                    );
                }
            });
        }
    }
    
    // Animation des éléments
    const summaryItems = document.querySelectorAll('.summary-item');
    summaryItems.forEach((item, index) => {
        setTimeout(() => {
            item.classList.add('animate');
        }, index * 100);
    });
    
    const candidateRows = document.querySelectorAll('.candidate-row');
    candidateRows.forEach((row, index) => {
        setTimeout(() => {
            row.classList.add('animate');
        }, index * 50);
    });
});
