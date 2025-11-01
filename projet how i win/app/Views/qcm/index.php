<?php
/**
 * VUE DU QCM - HOW I WIN MY HOME V1
 * 
 * Interface du questionnaire chronométré
 * pour qualifier les participants
 */

// Récupération des données depuis le contrôleur
$listing = $data['listing'] ?? [];
$questions = $data['questions'] ?? [];
$totalQuestions = count($questions);
$listingId = $data['listingId'] ?? '';
$qcmResult = $data['qcmResult'] ?? null;
$errors = $data['errors'] ?? [];
?>

<div class="qcm-page">
    <div class="qcm-container">
        <!-- En-tête du QCM -->
        <div class="qcm-header">
            <div class="header-content">
                <h1 class="qcm-title">
                    <i class="fas fa-question-circle"></i>
                    Questionnaire de Qualification
                </h1>
                <p class="qcm-description">
                    Répondez aux questions ci-dessous pour continuer votre participation.<br>
                    Vous avez <strong>10 minutes</strong> pour terminer le questionnaire.
                </p>
            </div>
            
            <!-- Chronomètre -->
            <div class="qcm-timer">
                <div class="timer-display">
                    <span class="timer-label">Temps restant :</span>
                    <span class="timer-value" id="timer">10:00</span>
                </div>
                <div class="timer-progress">
                    <div class="timer-bar" id="timerBar"></div>
                </div>
            </div>
        </div>
        
        <!-- Informations sur l'annonce -->
        <div class="listing-info">
            <div class="info-card">
                <div class="info-header">
                    <h3>Annonce : <?= htmlspecialchars($listing['titre'] ?? 'Annonce') ?></h3>
                    <div class="info-stats">
                        <span class="stat-item">
                            <i class="fas fa-question"></i>
                            <?= $totalQuestions ?> questions
                        </span>
                        <span class="stat-item">
                            <i class="fas fa-trophy"></i>
                            Score minimum : 50%
                        </span>
                        <span class="stat-item">
                            <i class="fas fa-clock"></i>
                            Temps limité : 10 min
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Instructions -->
        <div class="qcm-instructions">
            <div class="instructions-card">
                <h4>
                    <i class="fas fa-info-circle"></i>
                    Instructions importantes
                </h4>
                <ul class="instructions-list">
                    <li>Lisez attentivement chaque question avant de répondre</li>
                    <li>Une seule réponse par question est autorisée</li>
                    <li>Le questionnaire se soumet automatiquement à la fin du temps</li>
                    <li>Vous ne pouvez pas revenir en arrière une fois le questionnaire soumis</li>
                    <li>Un score de 50% minimum est requis pour continuer</li>
                </ul>
            </div>
        </div>
        
        <!-- Formulaire du QCM -->
        <form id="qcmForm" class="qcm-form" method="POST" action="/game/process-qcm-answers" data-no-ajax="true" novalidate>
            <input type="hidden" name="listing_id" value="<?= htmlspecialchars($listingId) ?>">
            <input type="hidden" name="time_spent" id="timeSpent" value="0">
            
            <!-- Questions -->
            <div class="questions-container">
                <?php if (!empty($questions)): ?>
                    <?php foreach ($questions as $index => $question): ?>
                        <div class="question-card" data-question="<?= $question['id'] ?>">
                            <div class="question-header">
                                <span class="question-number">Question <?= $index + 1 ?></span>
                                <span class="question-category"><?= htmlspecialchars($question['categorie'] ?? 'Général') ?></span>
                            </div>
                            
                            <div class="question-content">
                                <h3 class="question-text"><?= htmlspecialchars($question['question_text'] ?? 'Question non disponible') ?></h3>
                                
                                <div class="question-choices">
                                    <label class="choice-option">
                                        <input type="radio" 
                                               name="answers[<?= $question['id'] ?>]" 
                                               value="1" 
                                               required>
                                        <span class="choice-radio"></span>
                                        <span class="choice-text"><?= htmlspecialchars($question['answer_a'] ?? 'Choix A') ?></span>
                                    </label>
                                    
                                    <label class="choice-option">
                                        <input type="radio" 
                                               name="answers[<?= $question['id'] ?>]" 
                                               value="2" 
                                               required>
                                        <span class="choice-radio"></span>
                                        <span class="choice-text"><?= htmlspecialchars($question['answer_b'] ?? 'Choix B') ?></span>
                                    </label>
                                    
                                    <label class="choice-option">
                                        <input type="radio" 
                                               name="answers[<?= $question['id'] ?>]" 
                                               value="3" 
                                               required>
                                        <span class="choice-radio"></span>
                                        <span class="choice-text"><?= htmlspecialchars($question['answer_c'] ?? 'Choix C') ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-questions">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <h3>Aucune question disponible</h3>
                            <p>Il n'y a actuellement aucune question pour ce questionnaire.</p>
                            <a href="/listings" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i>
                                Retour aux annonces
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Actions du formulaire -->
            <?php if (!empty($questions)): ?>
            <div class="form-actions">
                <div class="actions-info">
                    <span class="questions-progress">
                        <span id="answeredCount">0</span> / <?= $totalQuestions ?> questions répondues
                    </span>
                    <span class="time-warning" id="timeWarning" class="time-warning-hidden">
                        <i class="fas fa-exclamation-triangle"></i>
                        Temps restant : <span id="timeWarningText"></span>
                    </span>
                </div>
                
                <div class="actions-buttons">
                    <button type="button" class="btn btn-outline" data-action="reset-form">
                        <i class="fas fa-undo"></i>
                        Recommencer
                    </button>
                    
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        Soumettre le questionnaire
                    </button>
                </div>
            </div>
            <?php endif; ?>
        </form>
        
        <!-- Barre de progression -->
        <div class="progress-bar-container">
            <div class="progress-info">
                <span>Progression : <span id="progressPercent">0</span>%</span>
                <span>Questions répondues : <span id="progressCount">0</span> / <?= $totalQuestions ?></span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
        </div>
    </div>
</div>

<!-- Script du QCM -->
<!-- Le JavaScript est maintenant géré par le fichier qcm-enhanced.js -->
