<?php
/**
 * VUE DE CONSULTATION D'UNE LETTRE - HOW I WIN MY HOME V1
 * 
 * Interface de consultation de lettre de motivation
 * avec options de gestion
 */

// Récupération des données depuis le contrôleur
$letter = $data['letter'] ?? [];
$listing = $data['listing'] ?? [];
$qcmResult = $data['qcmResult'] ?? [];
$evaluation = $data['evaluation'] ?? [];
$canEdit = $data['canEdit'] ?? false;
?>

<div class="letter-view-page">
    <div class="letter-view-container">
        <!-- En-tête de consultation -->
        <div class="view-header">
            <div class="header-content">
                <h1 class="view-title">
                    <i class="fas fa-file-alt"></i>
                    Votre Lettre de Motivation
                </h1>
                <p class="view-subtitle">
                    Consultez et gérez votre candidature pour cette annonce
                </p>
            </div>
            
            <div class="letter-status">
                <?php
                $statusClass = '';
                $statusText = '';
                $statusIcon = '';
                
                switch($letter['status'] ?? 'unknown') {
                    case 'draft':
                        $statusClass = 'draft';
                        $statusText = 'Brouillon';
                        $statusIcon = 'fas fa-edit';
                        break;
                    case 'submitted':
                        $statusClass = 'submitted';
                        $statusText = 'Soumise';
                        $statusIcon = 'fas fa-paper-plane';
                        break;
                    case 'evaluated':
                        $statusClass = 'evaluated';
                        $statusText = 'Évaluée';
                        $statusIcon = 'fas fa-star';
                        break;
                    case 'winner':
                        $statusClass = 'winner';
                        $statusText = 'Gagnant !';
                        $statusIcon = 'fas fa-trophy';
                        break;
                    default:
                        $statusClass = 'unknown';
                        $statusText = 'Inconnu';
                        $statusIcon = 'fas fa-question-circle';
                }
                ?>
                
                <span class="status-badge status-<?= $statusClass ?>">
                    <i class="<?= $statusIcon ?>"></i>
                    <?= $statusText ?>
                </span>
            </div>
        </div>
        
        <!-- Informations sur l'annonce -->
        <div class="listing-section">
            <div class="listing-card">
                <h3 class="section-title">
                    <i class="fas fa-home"></i>
                    Annonce concernée
                </h3>
                
                <div class="listing-content">
                    <div class="listing-image">
                        <?php if (!empty($listing['image'])): ?>
                            <img src="/uploads/<?= htmlspecialchars($listing['image']) ?>" 
                                 alt="<?= htmlspecialchars($listing['titre']) ?>"
                                 class="listing-img">
                        <?php else: ?>
                            <div class="no-image">
                                <i class="fas fa-home"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="listing-details">
                        <h4 class="listing-title"><?= htmlspecialchars($listing['titre'] ?? $listing['title'] ?? 'Annonce') ?></h4>
                        <p class="listing-description">
                            <?= htmlspecialchars($listing['description']) ?>
                        </p>
                        
                        <div class="listing-meta">
                            <div class="meta-row">
                                <span class="meta-label">Adresse :</span>
                                <span class="meta-value">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= htmlspecialchars($listing['address'] ?? 'Non spécifiée') ?>
                                </span>
                            </div>
                            
                            <div class="meta-row">
                                <span class="meta-label">Prix total :</span>
                                <span class="meta-value price">
                                    <i class="fas fa-euro-sign"></i>
                                    <?= number_format($listing['prix_total'] ?? 0, 0, ',', ' ') ?> €
                                </span>
                            </div>
                            
                            <div class="meta-row">
                                <span class="meta-label">Prix du ticket :</span>
                                <span class="meta-value ticket-price">
                                    <i class="fas fa-ticket-alt"></i>
                                    <?= number_format($listing['ticket_price'] ?? 0, 0, ',', ' ') ?> €
                                </span>
                            </div>
                            
                            <div class="meta-row">
                                <span class="meta-label">Ville :</span>
                                <span class="meta-value">
                                    <i class="fas fa-city"></i>
                                    <?= htmlspecialchars($listing['city'] ?? 'Non spécifiée') ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Résultats du QCM -->
        <?php if (!empty($qcmResult)): ?>
            <div class="qcm-section">
                <div class="qcm-card">
                    <h3 class="section-title">
                        <i class="fas fa-question-circle"></i>
                        Résultats du questionnaire QCM
                    </h3>
                    
                    <div class="qcm-results">
                        <div class="result-item">
                            <span class="result-label">Score obtenu :</span>
                            <span class="result-value score">
                                <?= $qcmResult['score'] ?? 0 ?> / 100
                            </span>
                        </div>
                        
                        <div class="result-item">
                            <span class="result-label">Temps de réponse :</span>
                            <span class="result-value time">
                                <?= $qcmResult['temps_reponse'] ?? 0 ?> minutes
                            </span>
                        </div>
                        
                        <div class="result-item">
                            <span class="result-label">Statut :</span>
                            <span class="result-value status">
                                <?php if (($qcmResult['score'] ?? 0) >= 50): ?>
                                    <span class="status-success">
                                        <i class="fas fa-check-circle"></i>
                                        Réussi
                                    </span>
                                <?php else: ?>
                                    <span class="status-failed">
                                        <i class="fas fa-times-circle"></i>
                                        Échoué
                                    </span>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Contenu de la lettre -->
        <div class="letter-section">
            <div class="letter-card">
                <h3 class="section-title">
                    <i class="fas fa-file-alt"></i>
                    Contenu de votre lettre
                </h3>
                
                <div class="letter-content">
                    <div class="letter-header">
                        <h4 class="letter-title"><?= htmlspecialchars($letter['titre'] ?? 'Lettre de motivation') ?></h4>
                        <div class="letter-meta">
                            <span class="meta-item">
                                <i class="fas fa-calendar"></i>
                                Créée le <?= date('d/m/Y à H:i', strtotime($letter['date_creation'])) ?>
                            </span>
                            
                            <?php if (($letter['date_modification'] ?? null)): ?>
                                <span class="meta-item">
                                    <i class="fas fa-edit"></i>
                                    Modifiée le <?= date('d/m/Y à H:i', strtotime($letter['date_modification'])) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="letter-body">
                        <div class="letter-text">
                            <?= nl2br(htmlspecialchars($letter['contenu'])) ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($letter['motivations'])): ?>
                        <div class="letter-motivations">
                            <h5 class="motivations-title">Vos motivations :</h5>
                            <div class="motivations-list">
                                <?php foreach (explode("\n", $letter['motivations']) as $motivation): ?>
                                    <?php if (trim($motivation)): ?>
                                        <div class="motivation-item">
                                            <i class="fas fa-heart"></i>
                                            <?= htmlspecialchars(trim($motivation)) ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Évaluation du jury -->
        <?php if (!empty($evaluation) && $letter['status'] !== 'draft'): ?>
            <div class="evaluation-section">
                <div class="evaluation-card">
                    <h3 class="section-title">
                        <i class="fas fa-star"></i>
                        Évaluation du jury
                    </h3>
                    
                    <div class="evaluation-content">
                        <div class="evaluation-score">
                            <div class="score-display">
                                <span class="score-number"><?= $evaluation['note'] ?? 0 ?></span>
                                <span class="score-max">/ 100</span>
                            </div>
                            <div class="score-label">Note globale</div>
                        </div>
                        
                        <?php if (!empty($evaluation['commentaires'])): ?>
                            <div class="evaluation-comments">
                                <h5 class="comments-title">Commentaires du jury :</h5>
                                <div class="comments-text">
                                    <?= nl2br(htmlspecialchars($evaluation['commentaires'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($evaluation['criteria'])): ?>
                            <div class="evaluation-criteria">
                                <h5 class="criteria-title">Détail des critères :</h5>
                                <div class="criteria-list">
                                    <?php foreach ($evaluation['criteria'] as $criterion): ?>
                                        <div class="criterion-item">
                                            <span class="criterion-name"><?= htmlspecialchars($criterion['name']) ?></span>
                                            <span class="criterion-score"><?= $criterion['score'] ?> / 20</span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Actions -->
        <div class="actions-section">
            <div class="actions-card">
                <h3 class="actions-title">
                    <i class="fas fa-cogs"></i>
                    Actions disponibles
                </h3>
                
                <div class="actions-buttons">
                    <a href="/letter/my-letters" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Retour à mes lettres
                    </a>
                    
                    <?php if ($canEdit && $letter['status'] === 'draft'): ?>
                        <a href="/letter/edit?id=<?= $letter['id'] ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i>
                            Modifier la lettre
                        </a>
                        
                        <button type="button" class="btn btn-success submit-letter-btn" 
                                data-letter-id="<?= $letter['id'] ?>">
                            <i class="fas fa-paper-plane"></i>
                            Soumettre la lettre
                        </button>
                    <?php endif; ?>
                    
                    <?php if ($letter['status'] === 'draft'): ?>
                        <button type="button" class="btn btn-danger delete-letter-btn" 
                                data-letter-id="<?= $letter['id'] ?>">
                            <i class="fas fa-trash"></i>
                            Supprimer la lettre
                        </button>
                    <?php endif; ?>
                    
                    <a href="/listings/view?id=<?= $listing['id'] ?>" class="btn btn-outline">
                        <i class="fas fa-eye"></i>
                        Voir l'annonce
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Section informations supplémentaires -->
        <div class="info-section">
            <div class="info-card">
                <div class="info-header">
                    <h3 class="info-title">
                        <i class="fas fa-info-circle"></i>
                        Informations du concours
                    </h3>
                </div>
                
                <div class="info-content">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="info-details">
                            <h4>Date limite</h4>
                            <p>Le concours se termine le <?= date('d/m/Y', strtotime('+30 days')) ?></p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="info-details">
                            <h4>Participants</h4>
                            <p><?= rand(50, 200) ?> personnes participent à ce concours</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="info-details">
                            <h4>Gagnant</h4>
                            <p>Le gagnant sera annoncé le <?= date('d/m/Y', strtotime('+45 days')) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de soumission -->
<div id="submit-letter-modal" class="modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3>Confirmer la soumission</h3>
            <button type="button" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir soumettre cette lettre de motivation ?</p>
            <div class="warning-box">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="warning-content">
                    <strong>Attention :</strong> Une fois soumise, votre lettre ne pourra plus être modifiée.
                    Elle sera transmise au jury pour évaluation.
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-outline cancel-submit-btn">
                Annuler
            </button>
            <button type="button" class="btn btn-success confirm-submit-btn">
                <i class="fas fa-paper-plane"></i>
                Soumettre définitivement
            </button>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="delete-letter-modal" class="modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3>Confirmer la suppression</h3>
            <button type="button" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir supprimer cette lettre de motivation ?</p>
            <div class="warning-box">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="warning-content">
                    <strong>Attention :</strong> Cette action est irréversible et supprimera définitivement votre lettre.
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-outline cancel-delete-btn">
                Annuler
            </button>
            <button type="button" class="btn btn-danger confirm-delete-btn">
                <i class="fas fa-trash"></i>
                Supprimer définitivement
            </button>
        </div>
    </div>
</div>

<!-- Script de gestion des actions -->
<!-- Le JavaScript est maintenant géré par le fichier letter-view-manager.js -->
