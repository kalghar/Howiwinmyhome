<?php
/**
 * VUE DU JURY - HOW I WIN MY HOME V1
 * 
 * Tableau de bord du jury pour évaluer
 * les lettres de motivation
 */

// Récupération des données depuis le contrôleur
$stats = $data['stats'] ?? [];
$recentListings = $data['recentListings'] ?? [];
$recentEvaluations = $data['recentEvaluations'] ?? [];
$pendingActions = $data['pendingActions'] ?? [];
$isLoggedIn = $data['isLoggedIn'] ?? false;
$userRole = $data['userRole'] ?? null;
?>

<div class="jury-page">
    <div class="jury-container">
        <!-- En-tête du jury -->
        <div class="page-header">
            <div class="header-content">
                <h1 class="page-title">
                    <i class="fas fa-gavel"></i>
                    Tableau de Bord du Jury
                </h1>
                <p class="page-description">
                    Évaluez les lettres de motivation et sélectionnez le gagnant final
                </p>
            </div>
            
            <!-- Statut de session -->
            <div class="header-status">
                <div class="status-indicator">
                    <i class="fas fa-gavel"></i>
                    <span>Session active</span>
                </div>
            </div>
        </div>
        
        <!-- Statistiques globales -->
        <div class="statistics-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-chart-bar"></i>
                    Statistiques des évaluations
                </h2>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number"><?= number_format($stats['total_listings'] ?? 0) ?></span>
                        <span class="stat-label">Annonces à évaluer</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number"><?= number_format($stats['total_letters'] ?? 0) ?></span>
                        <span class="stat-label">Lettres reçues</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number"><?= number_format($stats['evaluated_letters'] ?? 0) ?></span>
                        <span class="stat-label">Lettres évaluées</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number"><?= number_format($stats['pending_letters'] ?? 0) ?></span>
                        <span class="stat-label">En attente</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number"><?= number_format($stats['completed_listings'] ?? 0) ?></span>
                        <span class="stat-label">Annonces terminées</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="quick-actions-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-bolt"></i>
                    Actions rapides
                </h2>
            </div>
            
            <div class="actions-grid">
                <a href="/jury/evaluate-letters" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <h3 class="action-title">Évaluer des lettres</h3>
                    <p class="action-description">
                        Commencez ou continuez l'évaluation des lettres de motivation
                    </p>
                    <div class="action-meta">
                        <span class="meta-item">
                            <i class="fas fa-clock"></i>
                            <?= number_format($stats['pending_letters'] ?? 0) ?> en attente
                        </span>
                    </div>
                </a>
                
                <a href="/jury/select-winner" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3 class="action-title">Sélectionner un gagnant</h3>
                    <p class="action-description">
                        Choisissez le gagnant final pour les annonces terminées
                    </p>
                    <div class="action-meta">
                        <span class="meta-item">
                            <i class="fas fa-check"></i>
                            <?= number_format($stats['ready_for_winner'] ?? 0) ?> prêtes
                        </span>
                    </div>
                </a>
                
                <a href="/jury/results" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="action-title">Voir les résultats</h3>
                    <p class="action-description">
                        Consultez les statistiques et résultats des évaluations
                    </p>
                    <div class="action-meta">
                        <span class="meta-item">
                            <i class="fas fa-star"></i>
                            <?= number_format($stats['total_evaluations'] ?? 0) ?> évaluations
                        </span>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Contenu principal -->
        <div class="jury-content">
            <div class="content-grid">
                <!-- Colonne principale -->
                <div class="main-content">
                    <!-- Annonces récentes -->
                    <?php if (!empty($recentListings)): ?>
                    <div class="content-card">
                        <div class="card-header">
                            <h2 class="card-title">
                                <i class="fas fa-home"></i>
                                Annonces récentes à évaluer
                            </h2>
                            <a href="/jury/listings" class="card-action">
                                Voir toutes
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        
                        <div class="listings-list">
                            <?php foreach (array_slice($recentListings, 0, 5) as $listing): ?>
                            <div class="listing-item">
                                <div class="listing-image">
                                    <?php if (!empty($listing['image'])): ?>
                                        <img src="<?= htmlspecialchars($listing['image']) ?>" 
                                             alt="<?= htmlspecialchars($listing['titre']) ?>">
                                    <?php else: ?>
                                        <div class="listing-placeholder">
                                            <i class="fas fa-home"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="listing-info">
                                    <h3 class="listing-title"><?= htmlspecialchars($listing['titre']) ?></h3>
                                    <p class="listing-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?= htmlspecialchars($listing['ville'] ?? 'Ville non précisée') ?>
                                    </p>
                                    <div class="listing-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-ticket-alt"></i>
                                            <?= $listing['tickets_vendus'] ?? 0 ?> / <?= $listing['tickets_needed'] ?? 0 ?> tickets
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-file-alt"></i>
                                            <?= $listing['letters_count'] ?? 0 ?> lettres
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="listing-status">
                                    <span class="status-badge status-<?= $listing['status'] ?>">
                                        <?= ucfirst($listing['status']) ?>
                                    </span>
                                    <div class="listing-actions">
                                        <?php if ($listing['status'] === 'active'): ?>
                                            <a href="/jury/evaluate-letters?listing_id=<?= $listing['id'] ?>" class="btn btn-small btn-primary">
                                                <i class="fas fa-edit"></i>
                                                Évaluer
                                            </a>
                                        <?php elseif ($listing['status'] === 'evaluated'): ?>
                                            <a href="/jury/select-winner?listing_id=<?= $listing['id'] ?>" class="btn btn-small btn-success">
                                                <i class="fas fa-trophy"></i>
                                                Choisir
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Évaluations récentes -->
                    <?php if (!empty($recentEvaluations)): ?>
                    <div class="content-card">
                        <div class="card-header">
                            <h2 class="card-title">
                                <i class="fas fa-star"></i>
                                Évaluations récentes
                            </h2>
                            <a href="/jury/results" class="card-action">
                                Voir toutes
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        
                        <div class="evaluations-list">
                            <?php foreach (array_slice($recentEvaluations, 0, 5) as $evaluation): ?>
                            <div class="evaluation-item">
                                <div class="evaluation-info">
                                    <h3 class="evaluation-title">
                                        <?= htmlspecialchars($evaluation['listing_title'] ?? 'Annonce') ?>
                                    </h3>
                                    <p class="evaluation-letter">
                                        <?= htmlspecialchars(substr($evaluation['letter_title'] ?? '', 0, 100)) ?>
                                        <?= strlen($evaluation['letter_title'] ?? '') > 100 ? '...' : '' ?>
                                    </p>
                                    <div class="evaluation-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-calendar"></i>
                                            <?= date('d/m/Y', strtotime($evaluation['evaluation_date'] ?? 'now')) ?>
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-user"></i>
                                            <?= htmlspecialchars($evaluation['jury_member'] ?? 'Jury') ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="evaluation-score">
                                    <div class="score-display">
                                        <span class="score-value"><?= $evaluation['score'] ?? 0 ?>/100</span>
                                        <div class="score-bar">
                                            <div class="score-fill evaluation-progress-fill" 
                                                 data-width="<?= ($evaluation['score'] ?? 0) ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <a href="/jury/evaluation-details?id=<?= $evaluation['id'] ?>" class="btn btn-small btn-outline">
                                        <i class="fas fa-eye"></i>
                                        Détails
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Colonne latérale -->
                <div class="sidebar-content">
                    <!-- Actions en attente -->
                    <div class="content-card">
                        <div class="card-header">
                            <h2 class="card-title">
                                <i class="fas fa-clock"></i>
                                Actions en attente
                            </h2>
                        </div>
                        
                        <div class="pending-actions">
                            <?php if (!empty($pendingActions)): ?>
                                <?php foreach ($pendingActions as $action): ?>
                                <div class="pending-item">
                                    <div class="pending-icon">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                    <div class="pending-content">
                                        <p><?= htmlspecialchars($action['description']) ?></p>
                                        <a href="<?= htmlspecialchars($action['url']) ?>" class="pending-link">
                                            <?= htmlspecialchars($action['action_text']) ?>
                                        </a>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="no-pending">
                                    <i class="fas fa-check-circle"></i>
                                    <p>Aucune action en attente</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Liens rapides -->
                    <div class="content-card">
                        <div class="card-header">
                            <h2 class="card-title">
                                <i class="fas fa-link"></i>
                                Accès rapides
                            </h2>
                        </div>
                        
                        <div class="quick-links">
                            <a href="/jury/evaluate-letters" class="quick-link">
                                <i class="fas fa-edit"></i>
                                Évaluer des lettres
                            </a>
                            <a href="/jury/select-winner" class="quick-link">
                                <i class="fas fa-trophy"></i>
                                Sélectionner un gagnant
                            </a>
                            <a href="/jury/results" class="quick-link">
                                <i class="fas fa-chart-line"></i>
                                Voir les résultats
                            </a>
                            <a href="/jury/settings" class="quick-link">
                                <i class="fas fa-cog"></i>
                                Paramètres du jury
                            </a>
                        </div>
                    </div>
                    
                    <!-- Informations du jury -->
                    <div class="content-card">
                        <div class="card-header">
                            <h2 class="card-title">
                                <i class="fas fa-info-circle"></i>
                                Informations
                            </h2>
                        </div>
                        
                        <div class="jury-info">
                            <div class="info-item">
                                <span class="info-label">Membre du jury :</span>
                                <span class="info-value"><?= htmlspecialchars($data['user']['nom'] ?? 'Utilisateur') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Session active depuis :</span>
                                <span class="info-value"><?= date('d/m/Y à H:i') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Dernière activité :</span>
                                <span class="info-value">À l'instant</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ⚠️ Sécurité PROD : JavaScript inline supprimé — utiliser fichier .js externe -->
<!-- Le JavaScript est maintenant géré par le fichier jury-dashboard.js -->