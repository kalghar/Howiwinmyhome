<?php
/**
 * VUE D'ÉVALUATION DES LETTRES - HOW I WIN MY HOME V1
 * 
 * Interface d'évaluation des lettres de motivation
 * par le jury (anonymisées)
 */

// Récupération des données depuis le contrôleur
$listing = $data['listing'] ?? [];
$letters = $data['letters'] ?? [];
$evaluatedCount = $data['evaluatedCount'] ?? 0;
$totalLetters = $data['totalLetters'] ?? 0;
$evaluationCriteria = $data['evaluationCriteria'] ?? [];
$currentUser = $data['currentUser'] ?? [];
?>

<div class="evaluate-letters-page">
    <div class="evaluate-letters-container">
        <!-- En-tête d'évaluation -->
        <div class="evaluation-header">
            <div class="header-content">
                <h1 class="evaluation-title">
                    <i class="fas fa-star"></i>
                    Évaluation des Lettres de Motivation
                </h1>
                <p class="evaluation-subtitle">
                    Annonce : <strong><?= htmlspecialchars($listing['titre']) ?></strong>
                </p>
            </div>
            
            <div class="header-progress">
                <div class="progress-info">
                    <span class="progress-text">
                        <?= $evaluatedCount ?> / <?= $totalLetters ?> lettres évaluées
                    </span>
                    <div class="progress-bar">
                        <div class="progress-fill" data-progress="<?= $totalLetters > 0 ? ($evaluatedCount / $totalLetters) * 100 : 0 ?>"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations sur l'annonce -->
        <div class="listing-info">
            <div class="listing-card">
                <h3 class="section-title">
                    <i class="fas fa-home"></i>
                    Détails de l'annonce
                </h3>
                
                <div class="listing-content">
                    <div class="listing-image">
                        <?php if (!empty($listing['image'])): ?>
                            <img src="/uploads/<?= htmlspecialchars($listing['image']) ?>" 
                                 alt="<?= htmlspecialchars($listing['titre']) ?>"
                                 class="info-img">
                        <?php else: ?>
                            <div class="no-image">
                                <i class="fas fa-home"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="listing-details">
                        <h4 class="listing-title"><?= htmlspecialchars($listing['titre']) ?></h4>
                        <p class="listing-description">
                            <?= htmlspecialchars($listing['description']) ?>
                        </p>
                        
                        <div class="listing-meta">
                            <span class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($listing['adresse'] ?? 'Adresse non spécifiée') ?>
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-euro-sign"></i>
                                <?= number_format($listing['prix_total'] ?? 0, 0, ',', ' ') ?> €
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-ticket-alt"></i>
                                <?= number_format($listing['ticket_price'] ?? 0, 0, ',', ' ') ?> € le ticket
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                Date limite : <?= date('d/m/Y', strtotime($listing['end_date'] ?? 'now')) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Critères d'évaluation -->
        <div class="evaluation-criteria">
            <div class="criteria-card">
                <h3 class="section-title">
                    <i class="fas fa-list-check"></i>
                    Critères d'évaluation
                </h3>
                
                <div class="criteria-grid">
                    <?php foreach ($evaluationCriteria as $criterion): ?>
                        <div class="criteria-item">
                            <div class="criteria-header">
                                <h4 class="criteria-name"><?= htmlspecialchars($criterion['name']) ?></h4>
                                <span class="criteria-weight"><?= $criterion['weight'] ?> points</span>
                            </div>
                            <p class="criteria-description"><?= htmlspecialchars($criterion['description']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="criteria-total">
                    <span class="total-label">Total des points :</span>
                    <span class="total-value">100 points</span>
                </div>
            </div>
        </div>
        
        <!-- Liste des lettres à évaluer -->
        <div class="letters-section">
            <div class="letters-header">
                <h3 class="section-title">
                    <i class="fas fa-file-alt"></i>
                    Lettres à évaluer
                </h3>
                
                <div class="letters-count">
                    <span class="count-text">
                        <?= $totalLetters - $evaluatedCount ?> lettre<?= ($totalLetters - $evaluatedCount) > 1 ? 's' : '' ?> restante<?= ($totalLetters - $evaluatedCount) > 1 ? 's' : '' ?>
                    </span>
                </div>
            </div>
            
            <?php if (empty($letters)): ?>
                <!-- État vide -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h4 class="empty-title">Toutes les lettres ont été évaluées !</h4>
                    <p class="empty-description">
                        Félicitations ! Vous avez terminé l'évaluation de toutes les lettres pour cette annonce.
                        Vous pouvez maintenant procéder à la sélection du gagnant.
                    </p>
                    
                    <div class="empty-actions">
                        <a href="/jury/select-winner?listing_id=<?= $listing['id'] ?>" class="btn btn-primary">
                            <i class="fas fa-trophy"></i>
                            Sélectionner le gagnant
                        </a>
                        
                        <a href="/jury/results?listing_id=<?= $listing['id'] ?>" class="btn btn-outline">
                            <i class="fas fa-chart-bar"></i>
                            Voir les résultats
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Liste des lettres -->
                <div class="letters-list">
                    <?php foreach ($letters as $letter): ?>
                        <div class="letter-card" data-letter-id="<?= $letter['id'] ?>">
                            <div class="letter-header">
                                <div class="letter-info">
                                    <h4 class="letter-title">Lettre #<?= $letter['id'] ?></h4>
                                    <div class="letter-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-calendar"></i>
                                            Soumise le <?= date('d/m/Y', strtotime($letter['date_submission'])) ?>
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-clock"></i>
                                            <?= date('H:i', strtotime($letter['date_submission'])) ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="letter-status">
                                    <span class="status-badge pending">
                                        <i class="fas fa-clock"></i>
                                        En attente
                                    </span>
                                </div>
                            </div>
                            
                            <div class="letter-content">
                                <div class="content-section">
                                    <h5 class="section-subtitle">Contenu de la lettre :</h5>
                                    <div class="letter-text">
                                        <?= nl2br(htmlspecialchars($letter['contenu'])) ?>
                                    </div>
                                </div>
                                
                                <?php if (!empty($letter['motivations'])): ?>
                                    <div class="content-section">
                                        <h5 class="section-subtitle">Motivations exprimées :</h5>
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
                            
                            <!-- Formulaire d'évaluation -->
                            <div class="evaluation-form">
                                <h5 class="form-title">Votre évaluation :</h5>
                                
                                <form class="evaluation-form-content" data-letter-id="<?= $letter['id'] ?>">
                                    <div class="criteria-evaluation">
                                        <?php foreach ($evaluationCriteria as $criterion): ?>
                                            <div class="criterion-evaluation">
                                                <label class="criterion-label">
                                                    <?= htmlspecialchars($criterion['name']) ?>
                                                    <span class="criterion-weight">(<?= $criterion['weight'] ?> points)</span>
                                                </label>
                                                
                                                <div class="score-input-group">
                                                    <input type="number" 
                                                           name="criterion_<?= $criterion['id'] ?>" 
                                                           class="score-input" 
                                                           min="0" 
                                                           max="<?= $criterion['weight'] ?>" 
                                                           step="1"
                                                           placeholder="0-<?= $criterion['weight'] ?>"
                                                           required>
                                                    <span class="score-max">/ <?= $criterion['weight'] ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <div class="total-score">
                                        <span class="total-label">Score total :</span>
                                        <span class="total-value" id="total-score-<?= $letter['id'] ?>">0</span>
                                        <span class="total-max">/ 100</span>
                                    </div>
                                    
                                    <div class="evaluation-comments">
                                        <label for="comments-<?= $letter['id'] ?>" class="comments-label">
                                            Commentaires d'évaluation (optionnel) :
                                        </label>
                                        <textarea name="comments" 
                                                  id="comments-<?= $letter['id'] ?>" 
                                                  class="comments-textarea" 
                                                  placeholder="Vos observations et commentaires sur cette lettre..."
                                                  rows="4"></textarea>
                                    </div>
                                    
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary submit-evaluation-btn">
                                            <i class="fas fa-save"></i>
                                            Sauvegarder l'évaluation
                                        </button>
                                        
                                        <button type="button" class="btn btn-outline reset-evaluation-btn">
                                            <i class="fas fa-undo"></i>
                                            Réinitialiser
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Actions globales -->
                <div class="global-actions">
                    <div class="actions-card">
                        <h4 class="actions-title">
                            <i class="fas fa-cogs"></i>
                            Actions globales
                        </h4>
                        
                        <div class="actions-buttons">
                            <button type="button" class="btn btn-success" id="save-all-evaluations-btn">
                                <i class="fas fa-save"></i>
                                Sauvegarder toutes les évaluations
                            </button>
                            
                            <a href="/jury/results?listing_id=<?= $listing['id'] ?>" class="btn btn-outline">
                                <i class="fas fa-chart-bar"></i>
                                Voir les résultats
                            </a>
                            
                            <a href="/jury/select-winner?listing_id=<?= $listing['id'] ?>" class="btn btn-primary">
                                <i class="fas fa-trophy"></i>
                                Sélectionner le gagnant
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ⚠️ Sécurité PROD : JavaScript inline supprimé — utiliser fichier .js externe -->
<!-- Le JavaScript est maintenant géré par le fichier jury-evaluate.js -->
