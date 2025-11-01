<?php
/**
 * VUE DE CRÉATION DE LETTRES - HOW I WIN MY HOME V1
 * 
 * Interface de création de lettre de motivation
 * après réussite du QCM
 */

// Récupération des données depuis le contrôleur
$listing = $data['listing'] ?? [];
$qcmResult = $data['qcmResult'] ?? [];
$errors = $data['errors'] ?? [];
$old = $data['old'] ?? [];
?>

<div class="letter-create-page">
    <div class="letter-create-container">
        <!-- En-tête de la page -->
        <div class="page-header">
            <div class="header-content">
                <h1 class="page-title">
                    <i class="fas fa-edit"></i>
                    Rédiger votre lettre de motivation
                </h1>
                <p class="page-description">
                    Cette lettre sera évaluée anonymement par le jury pour déterminer le gagnant
                </p>
            </div>
            
            <!-- Navigation retour -->
            <div class="header-actions">
                <a href="/listings/view?id=<?= $listing['id'] ?? '' ?>" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i>
                    Retour à l'annonce
                </a>
            </div>
        </div>
        
        <!-- Informations sur l'annonce -->
        <div class="listing-info">
            <div class="info-card">
                <h3 class="info-title">
                    <i class="fas fa-home"></i>
                    Annonce concernée
                </h3>
                <div class="listing-card">
                    <div class="listing-image">
                        <?php if (!empty($listing['images']) && is_array($listing['images']) && !empty($listing['images'][0])): ?>
                            <img src="/uploads/listings/<?= htmlspecialchars($listing['images'][0]['filename']) ?>" 
                                 alt="<?= htmlspecialchars($listing['title'] ?? 'Annonce') ?>"
                                 class="info-img">
                        <?php elseif (!empty($listing['image'])): ?>
                            <img src="<?= htmlspecialchars($listing['image']) ?>" 
                                 alt="<?= htmlspecialchars($listing['title'] ?? 'Annonce') ?>"
                                 class="info-img">
                        <?php else: ?>
                            <div class="no-image">
                                <i class="fas fa-home"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="listing-details">
                        <h4><?= htmlspecialchars($listing['title'] ?? 'Annonce') ?></h4>
                        <p class="listing-description">
                            <?= htmlspecialchars(substr($listing['description'] ?? '', 0, 150)) ?>
                            <?= strlen($listing['description'] ?? '') > 150 ? '...' : '' ?>
                        </p>
                        
                        <div class="listing-meta">
                            <span class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($listing['ville'] ?? 'Ville non précisée') ?>
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-euro-sign"></i>
                                <?= number_format($listing['prix_total'] ?? 0, 0, ',', ' ') ?> €
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Résultats du QCM - Intégration améliorée -->
        <div class="qcm-performance">
            <div class="performance-card">
                <div class="performance-header">
                    <div class="performance-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="performance-title">
                        <h3>Votre performance au QCM</h3>
                        <p>Résultats de votre qualification</p>
                    </div>
                </div>
                
                <div class="performance-stats">
                    <div class="stat-item score-stat">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Score obtenu</span>
                            <span class="stat-value score">
                                <?= number_format($qcmResult['pourcentage'] ?? 0, 0) ?>/100
                            </span>
                        </div>
                    </div>
                    
                    <div class="stat-item time-stat">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Temps passé</span>
                            <span class="stat-value time">
                                <?= $qcmResult['timeSpent'] ?? 0 ?> min
                            </span>
                        </div>
                    </div>
                    
                    <div class="stat-item status-stat">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Statut</span>
                            <span class="stat-value status <?= ($qcmResult['pourcentage'] ?? 0) >= 50 ? 'success' : 'warning' ?>">
                                <?= ($qcmResult['pourcentage'] ?? 0) >= 50 ? 'Qualifié' : 'À améliorer' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Guide de rédaction - Design moderne et cohérent -->
        <div class="writing-guide">
            <div class="guide-card">
                <div class="guide-header">
                    <div class="guide-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div class="guide-title">
                        <h3>Conseils pour une lettre réussie</h3>
                        <p>Guide pratique pour rédiger une lettre de motivation efficace</p>
                    </div>
                </div>
                
                <div class="guide-sections">
                    <div class="guide-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h4>Structure recommandée</h4>
                        </div>
                        <div class="section-content">
                            <div class="tip-item">
                                <div class="tip-number">1</div>
                                <div class="tip-content">
                                    <strong>Introduction :</strong> Présentez-vous et exprimez votre intérêt
                                </div>
                            </div>
                            <div class="tip-item">
                                <div class="tip-number">2</div>
                                <div class="tip-content">
                                    <strong>Développement :</strong> Expliquez pourquoi ce bien vous intéresse
                                </div>
                            </div>
                            <div class="tip-item">
                                <div class="tip-number">3</div>
                                <div class="tip-content">
                                    <strong>Conclusion :</strong> Résumez vos motivations et remerciez
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="guide-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <h4>Conseils pratiques</h4>
                        </div>
                        <div class="section-content">
                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="tip-content">
                                    Soyez authentique et personnel dans votre approche
                                </div>
                            </div>
                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="tip-content">
                                    Évitez les phrases trop longues et complexes
                                </div>
                            </div>
                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="tip-content">
                                    Relisez votre texte pour corriger les fautes
                                </div>
                            </div>
                            <div class="tip-item">
                                <div class="tip-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="tip-content">
                                    Respectez la limite de 1000 caractères
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="guide-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h4>Points d'attention</h4>
                        </div>
                        <div class="section-content">
                            <div class="tip-item warning">
                                <div class="tip-icon">
                                    <i class="fas fa-user-secret"></i>
                                </div>
                                <div class="tip-content">
                                    Votre lettre sera évaluée anonymement
                                </div>
                            </div>
                            <div class="tip-item warning">
                                <div class="tip-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="tip-content">
                                    Le jury privilégie l'originalité et la sincérité
                                </div>
                            </div>
                            <div class="tip-item warning">
                                <div class="tip-icon">
                                    <i class="fas fa-ban"></i>
                                </div>
                                <div class="tip-content">
                                    Évitez les clichés et les formulations génériques
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Formulaire de création -->
        <div class="letter-form-section">
            <div class="form-container">
                <h3 class="form-title">
                    <i class="fas fa-pen-fancy"></i>
                    Votre lettre de motivation
                </h3>
                
                <?php if (!empty($errors)): ?>
                <div class="form-errors">
                    <?php foreach ($errors as $error): ?>
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="/game/process-letter" class="letter-form" data-no-ajax="true" novalidate>
                    <input type="hidden" name="listing_id" value="<?= $listing['id'] ?? '' ?>">
                    
                    <!-- Contenu de la lettre -->
                    <div class="form-group">
                        <label for="contenu" class="form-label">
                            <i class="fas fa-align-left"></i>
                            Contenu de votre lettre
                        </label>
                        <textarea id="contenu" 
                                  name="contenu" 
                                  class="form-textarea <?= isset($errors['contenu']) ? 'error' : '' ?>"
                                  placeholder="Rédigez votre lettre de motivation ici..."
                                  rows="12"
                                  maxlength="1000"
                                  required><?= htmlspecialchars($old['contenu'] ?? '') ?></textarea>
                        
                        <div class="form-help">
                            <span class="char-count">
                                <span id="currentCount">0</span> / 1000 caractères
                            </span>
                            <span class="char-limit">
                                Limite : 1000 caractères maximum
                            </span>
                        </div>
                        
                        <?php if (isset($errors['contenu'])): ?>
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <?= htmlspecialchars($errors['contenu']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Conditions -->
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="terms" value="1" required>
                            <span class="checkmark"></span>
                            J'accepte que ma lettre soit évaluée anonymement par le jury
                        </label>
                        <?php if (isset($errors['terms'])): ?>
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <?= htmlspecialchars($errors['terms']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Actions du formulaire -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-large">
                            <i class="fas fa-paper-plane"></i>
                            Soumettre ma lettre
                        </button>
                        
                        <button type="button" class="btn btn-outline btn-large preview-letter-btn">
                            <i class="fas fa-eye"></i>
                            Aperçu
                        </button>
                        
                        <a href="/listings/view?id=<?= $listing['id'] ?? '' ?>" class="btn btn-outline btn-large">
                            <i class="fas fa-times"></i>
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Aperçu de la lettre (modal) -->
        <div id="letterPreviewModal" class="modal letter-preview-modal">
            <div class="modal-overlay"></div>
            <div class="modal-container">
                <div class="modal-header">
                    <h3>Aperçu de votre lettre</h3>
                    <button type="button" class="modal-close close-preview-btn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="letterPreviewContent">
                        <!-- Le contenu de l'aperçu sera inséré ici -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary close-preview-btn">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script de gestion de la lettre -->
<!-- Le JavaScript est maintenant géré par le fichier letter-create-enhanced.js -->
