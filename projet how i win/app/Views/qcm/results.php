<?php
/**
 * VUE DES RÉSULTATS DU QCM - HOW I WIN MY HOME V1
 * 
 * Affichage des résultats du QCM
 * avec redirection vers la lettre de motivation
 */

// Récupération des données depuis le contrôleur
$result = $data['result'] ?? [];
$listing = $data['listing'] ?? [];
$questions = $data['questions'] ?? [];
$redirectDelay = $data['redirectDelay'] ?? 5;
?>

<div class="qcm-results-page">
    <div class="qcm-results-container">
        <!-- En-tête des résultats -->
        <div class="results-header">
            <h1 class="results-title">
                <i class="fas fa-chart-line"></i>
                Résultats de votre QCM
            </h1>
            <p class="results-subtitle">
                Questionnaire terminé pour l'annonce : 
                <strong><?= htmlspecialchars($listing['title'] ?? 'Annonce') ?></strong>
            </p>
        </div>
        
        <!-- Affichage du score -->
        <div class="score-display">
            <?php if (isset($result['score'])): ?>
                <div class="score-circle <?= ($result['pourcentage'] ?? 0) >= 50 ? 'success' : 'failed' ?>">
                    <div class="score-value"><?= number_format($result['pourcentage'] ?? 0, 0) ?></div>
                    <div class="score-max">/100</div>
                </div>
                
                <div class="score-status">
                    <?php if (($result['pourcentage'] ?? 0) >= 50): ?>
                        <h2 class="status-success">
                            <i class="fas fa-check-circle"></i>
                            Félicitations !
                        </h2>
                        <p class="status-message">Vous avez réussi le questionnaire de qualification.</p>
                    <?php else: ?>
                        <h2 class="status-failed">
                            <i class="fas fa-times-circle"></i>
                            Dommage...
                        </h2>
                        <p class="status-message">Votre score n'est pas suffisant pour continuer.</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="score-error">
                    <h2>
                        <i class="fas fa-exclamation-triangle"></i>
                        Erreur
                    </h2>
                    <p>Impossible de récupérer vos résultats.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Détails des résultats -->
        <?php if (isset($result) && !empty($result)): ?>
            <div class="results-details">
                <div class="details-card">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Détails de votre participation
                    </h3>
                    
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="detail-label">Date de passage :</span>
                            <span class="detail-value">
                                <?= date('d/m/Y à H:i', strtotime($result['date_passage'] ?? 'now')) ?>
                            </span>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label">Temps passé :</span>
                            <span class="detail-value">
                                <?php 
                                $timeSpent = $result['time_spent'] ?? 0;
                                if ($timeSpent > 0) {
                                    $minutes = floor($timeSpent / 60);
                                    $seconds = $timeSpent % 60;
                                    echo sprintf('%02d:%02d', $minutes, $seconds);
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </span>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label">Questions répondues :</span>
                            <span class="detail-value">
                                <?= $result['total_questions'] ?? 0 ?>
                            </span>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label">Seuil de réussite :</span>
                            <span class="detail-value">50/100</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Prochaines étapes -->
        <div class="next-steps">
            <div class="steps-card">
                <h3 class="section-title">
                    <i class="fas fa-route"></i>
                    Prochaines étapes
                </h3>
                
                <?php if (($result['pourcentage'] ?? 0) >= 50): ?>
                    <!-- Succès - Redirection vers la lettre -->
                    <div class="steps-timeline">
                        <div class="step-item completed">
                            <div class="step-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="step-content">
                                <h4>QCM réussi</h4>
                                <p>Vous avez obtenu un score suffisant pour continuer</p>
                            </div>
                        </div>
                        
                        <div class="step-item current">
                            <div class="step-icon">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="step-content">
                                <h4>Lettre de motivation</h4>
                                <p>Rédigez votre lettre de motivation pour convaincre le jury</p>
                                <div class="step-actions">
                                    <a href="/game/create-letter?listing_id=<?= $listing['id'] ?? '' ?>" class="btn btn-primary btn-large">
                                        <i class="fas fa-edit"></i>
                                        Créer ma lettre de motivation
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-icon">
                                <i class="fas fa-gavel"></i>
                            </div>
                            <div class="step-content">
                                <h4>Évaluation du jury</h4>
                                <p>Votre lettre sera évaluée anonymement</p>
                            </div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="step-content">
                                <h4>Résultat final</h4>
                                <p>Découvrez si vous êtes le gagnant !</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="success-actions">
                        <div class="actions-card">
                            <h4 class="actions-title">
                                <i class="fas fa-play"></i>
                                Commencer maintenant
                            </h4>
                            
                            <div class="actions-buttons">
                                <a href="/letter/create?listing_id=<?= $listing['id'] ?? '' ?>" class="btn btn-primary btn-large">
                                    <i class="fas fa-edit"></i>
                                    Rédiger ma lettre
                                </a>
                                
                                <a href="/listings/view?id=<?= $listing['id'] ?? '' ?>" class="btn btn-outline btn-large">
                                    <i class="fas fa-eye"></i>
                                    Voir l'annonce
                                </a>
                                
                                <a href="/qcm?listing_id=<?= $listing['id'] ?? '' ?>" class="btn btn-outline btn-large">
                                    <i class="fas fa-redo"></i>
                                    Repasser le QCM
                                </a>
                            </div>
                            
                            <!-- Redirection automatique supprimée - L'utilisateur choisit quand continuer -->
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Échec - Options de retry -->
                    <div class="failure-options">
                        <div class="options-card">
                            <h4 class="options-title">
                                <i class="fas fa-lightbulb"></i>
                                Que faire maintenant ?
                            </h4>
                            
                            <div class="options-list">
                                <div class="option-item">
                                    <div class="option-icon">
                                        <i class="fas fa-redo"></i>
                                    </div>
                                    <div class="option-content">
                                        <h5>Repasser le QCM</h5>
                                        <p>Vous pouvez repasser le questionnaire après un délai de 24h</p>
                                        <a href="/qcm?listing_id=<?= $listing['id'] ?? '' ?>" class="btn btn-primary">
                                            <i class="fas fa-redo"></i>
                                            Repasser le QCM
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="option-item">
                                    <div class="option-icon">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <div class="option-content">
                                        <h5>Voir d'autres annonces</h5>
                                        <p>Découvrez d'autres biens immobiliers disponibles</p>
                                        <a href="/listings" class="btn btn-outline">
                                            <i class="fas fa-search"></i>
                                            Voir les annonces
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="option-item">
                                    <div class="option-icon">
                                        <i class="fas fa-question-circle"></i>
                                    </div>
                                    <div class="option-content">
                                        <h5>Besoin d'aide ?</h5>
                                        <p>Consultez notre guide ou contactez notre support</p>
                                        <a href="/help" class="btn btn-outline">
                                            <i class="fas fa-question-circle"></i>
                                            Centre d'aide
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Informations importantes -->
        <div class="important-info">
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="info-content">
                    <h4>Informations importantes</h4>
                    <ul class="info-list">
                        <?php if (($result['pourcentage'] ?? 0) >= 50): ?>
                            <li>Votre score de <?= number_format($result['pourcentage'] ?? 0, 0) ?>/100 vous permet de continuer</li>
                            <li>Vous avez 7 jours pour rédiger votre lettre de motivation</li>
                            <li>La lettre doit être originale et personnelle</li>
                            <li>Le jury évaluera anonymement votre candidature</li>
                        <?php else: ?>
                            <li>Un score minimum de 50/100 est requis pour continuer</li>
                            <li>Vous pouvez repasser le QCM après 24h</li>
                            <li>Préparez-vous mieux pour la prochaine tentative</li>
                            <li>Consultez notre guide de préparation</li>
                        <?php endif; ?>
                        <li>Pour toute question, contactez notre support</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script de redirection automatique SUPPRIMÉ - Plus de timer automatique -->
