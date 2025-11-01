<?php

/**
 * VUE D'ADMINISTRATION - HOW I WIN MY HOME V1
 * 
 * Interface d'administration principale
 * pour gérer l'application
 */

// Récupération des données depuis le contrôleur
$stats = $data['stats'] ?? [];
$recentActivities = $data['recentActivities'] ?? [];
$pendingListings = $data['pendingListings'] ?? [];
$userStats = $data['userStats'] ?? [];
$isLoggedIn = $data['isLoggedIn'] ?? false;
$userRole = $data['userRole'] ?? null;
?>

<div class="admin-page">
    <div class="admin-container">
        <!-- En-tête héroïque administration -->
        <div class="admin-hero">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        <span class="highlight">Administration</span>
                    </h1>
                    <p class="hero-subtitle">
                        Gérez votre application How I Win My Home
                    </p>
                    <div class="hero-meta">
                        <span class="admin-status">
                            Mode administrateur actif
                        </span>
                    </div>
                </div>
                <div class="hero-actions">
                    <a href="/dashboard" class="btn btn-outline btn-large">
                        Retour au tableau de bord
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistiques globales -->
        <div class="admin-stats-showcase">
            <div class="showcase-header">
                <h2 class="showcase-title">
                    Statistiques globales
                </h2>
                <p class="showcase-subtitle">
                    Vue d'ensemble des performances de votre application
                </p>
            </div>

            <div class="stats-grid-enhanced">
                <div class="stat-card-enhanced stat-card-users">
                    <div class="stat-card-header">
                        <div class="stat-icon-enhanced">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-trend <?= ($stats['users_change'] ?? 0) >= 0 ? 'trend-up' : 'trend-down' ?>">
                            <i class="fas fa-<?= ($stats['users_change'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' ?>"></i>
                            <?= abs($stats['users_change'] ?? 0) ?>%
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number-enhanced"><?= number_format($stats['total_users'] ?? 0) ?></div>
                        <div class="stat-label-enhanced">Utilisateurs totaux</div>
                        <div class="stat-subtitle">Inscrits sur la plateforme</div>
                    </div>
                    <div class="stat-card-footer">
                        <div class="stat-indicator <?= ($stats['users_change'] ?? 0) >= 0 ? 'indicator-positive' : 'indicator-negative' ?>">
                            <span class="indicator-dot"></span>
                            <?= ($stats['users_change'] ?? 0) >= 0 ? 'En hausse' : 'En baisse' ?>
                        </div>
                    </div>
                </div>

                <div class="stat-card-enhanced stat-card-listings">
                    <div class="stat-card-header">
                        <div class="stat-icon-enhanced">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="stat-trend <?= ($stats['listings_change'] ?? 0) >= 0 ? 'trend-up' : 'trend-down' ?>">
                            <i class="fas fa-<?= ($stats['listings_change'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' ?>"></i>
                            <?= abs($stats['listings_change'] ?? 0) ?>%
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number-enhanced"><?= number_format($stats['total_listings'] ?? 0) ?></div>
                        <div class="stat-label-enhanced">Annonces totales</div>
                        <div class="stat-subtitle">Propriétés disponibles</div>
                    </div>
                    <div class="stat-card-footer">
                        <div class="stat-indicator <?= ($stats['listings_change'] ?? 0) >= 0 ? 'indicator-positive' : 'indicator-negative' ?>">
                            <span class="indicator-dot"></span>
                            <?= ($stats['listings_change'] ?? 0) >= 0 ? 'En hausse' : 'En baisse' ?>
                        </div>
                    </div>
                </div>

                <div class="stat-card-enhanced stat-card-tickets">
                    <div class="stat-card-header">
                        <div class="stat-icon-enhanced">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="stat-trend <?= ($stats['tickets_change'] ?? 0) >= 0 ? 'trend-up' : 'trend-down' ?>">
                            <i class="fas fa-<?= ($stats['tickets_change'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' ?>"></i>
                            <?= abs($stats['tickets_change'] ?? 0) ?>%
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number-enhanced"><?= number_format($stats['tickets_sold_today'] ?? 0) ?></div>
                        <div class="stat-label-enhanced">Tickets vendus</div>
                        <div class="stat-subtitle">Aujourd'hui</div>
                    </div>
                    <div class="stat-card-footer">
                        <div class="stat-indicator <?= ($stats['tickets_change'] ?? 0) >= 0 ? 'indicator-positive' : 'indicator-negative' ?>">
                            <span class="indicator-dot"></span>
                            <?= ($stats['tickets_change'] ?? 0) >= 0 ? 'En hausse' : 'En baisse' ?>
                        </div>
                    </div>
                </div>

                <div class="stat-card-enhanced stat-card-pending">
                    <div class="stat-card-header">
                        <div class="stat-icon-enhanced">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-trend trend-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            À traiter
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number-enhanced"><?= number_format($stats['pending_listings'] ?? 0) ?></div>
                        <div class="stat-label-enhanced">En attente</div>
                        <div class="stat-subtitle">Annonces à modérer</div>
                    </div>
                    <div class="stat-card-footer">
                        <div class="stat-indicator indicator-warning">
                            <span class="indicator-dot"></span>
                            Action requise
                        </div>
                    </div>
                </div>

                <div class="stat-card-enhanced stat-card-winners">
                    <div class="stat-card-header">
                        <div class="stat-icon-enhanced">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="stat-trend trend-success">
                            <i class="fas fa-star"></i>
                            Succès
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number-enhanced"><?= number_format($stats['total_winners'] ?? 0) ?></div>
                        <div class="stat-label-enhanced">Gagnants</div>
                        <div class="stat-subtitle">Joueurs récompensés</div>
                    </div>
                    <div class="stat-card-footer">
                        <div class="stat-indicator indicator-success">
                            <span class="indicator-dot"></span>
                            Excellent
                        </div>
                    </div>
                </div>

                <div class="stat-card-enhanced stat-card-revenue">
                    <div class="stat-card-header">
                        <div class="stat-icon-enhanced">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                        <div class="stat-trend <?= ($stats['revenue_change'] ?? 0) >= 0 ? 'trend-up' : 'trend-down' ?>">
                            <i class="fas fa-<?= ($stats['revenue_change'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' ?>"></i>
                            <?= abs($stats['revenue_change'] ?? 0) ?>%
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number-enhanced"><?= number_format($stats['total_revenue'] ?? 0, 0, ',', ' ') ?> €</div>
                        <div class="stat-label-enhanced">Chiffre d'affaires</div>
                        <div class="stat-subtitle">Revenus totaux</div>
                    </div>
                    <div class="stat-card-footer">
                        <div class="stat-indicator <?= ($stats['revenue_change'] ?? 0) >= 0 ? 'indicator-positive' : 'indicator-negative' ?>">
                            <span class="indicator-dot"></span>
                            <?= ($stats['revenue_change'] ?? 0) >= 0 ? 'En hausse' : 'En baisse' ?>
                        </div>
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

            <div class="quick-actions-grid-main">
                <a href="/admin/users" class="quick-action-card-main">
                    <div class="quick-action-icon-main">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="quick-action-content-main">
                        <h3 class="quick-action-title-main">Gérer les utilisateurs</h3>
                        <p class="quick-action-desc-main">
                            Consultez, modifiez et gérez les comptes utilisateurs
                        </p>
                        <div class="quick-action-meta-main">
                            <span class="meta-item-main">
                                <i class="fas fa-user-plus"></i>
                                <?= number_format($stats['new_users_today'] ?? 0) ?> nouveaux aujourd'hui
                            </span>
                        </div>
                    </div>
                </a>

                <a href="/admin/listings" class="quick-action-card-main">
                    <div class="quick-action-icon-main">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="quick-action-content-main">
                        <h3 class="quick-action-title-main">Modérer les annonces</h3>
                        <p class="quick-action-desc-main">
                            Validez ou rejetez les annonces en attente de modération
                        </p>
                        <div class="quick-action-meta-main">
                            <span class="meta-item-main">
                                <i class="fas fa-clock"></i>
                                <?= number_format($stats['pending_listings'] ?? 0) ?> en attente
                            </span>
                        </div>
                    </div>
                </a>


                <a href="/admin/settings" class="quick-action-card-main">
                    <div class="quick-action-icon-main">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div class="quick-action-content-main">
                        <h3 class="quick-action-title-main">Configuration</h3>
                        <p class="quick-action-desc-main">
                            Modifiez les paramètres de l'application et du système
                        </p>
                        <div class="quick-action-meta-main">
                            <span class="meta-item-main">
                                <i class="fas fa-cog"></i>
                                Paramètres système
                            </span>
                        </div>
                    </div>
                </a>

                <a href="/admin/reports" class="quick-action-card-main">
                    <div class="quick-action-icon-main">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="quick-action-content-main">
                        <h3 class="quick-action-title-main">Rapports et analyses</h3>
                        <p class="quick-action-desc-main">
                            Consultez les rapports détaillés et les analyses de performance
                        </p>
                        <div class="quick-action-meta-main">
                            <span class="meta-item-main">
                                <i class="fas fa-file-alt"></i>
                                Rapports disponibles
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="admin-content">
            <div class="content-grid">
                <!-- Colonne principale -->
                <div class="main-content">
                    <!-- Annonces en attente -->
                    <?php if (!empty($pendingListings)): ?>
                        <div class="content-card">
                            <div class="card-header">
                                <h2 class="card-title">
                                    <i class="fas fa-clock"></i>
                                    Annonces en attente de modération
                                </h2>
                                <div class="card-actions">
                                    <a href="/admin/documents" class="card-action">
                                        <i class="fas fa-file-shield"></i>
                                        Vérifier les documents
                                    </a>
                                    <a href="/admin/listings" class="card-action">
                                        Voir toutes
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="listings-list">
                                <?php foreach (array_slice($pendingListings, 0, 5) as $listing): ?>
                                    <div class="listing-item">
                                        <div class="listing-image">
                                            <?php if (!empty($listing['image'])): ?>
                                                <img src="<?= htmlspecialchars($listing['image']) ?>"
                                                    alt="<?= htmlspecialchars($listing['title']) ?>">
                                            <?php else: ?>
                                                <div class="listing-placeholder">
                                                    <i class="fas fa-home"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="listing-info">
                                            <h3 class="listing-title"><?= htmlspecialchars($listing['title']) ?></h3>
                                            <p class="listing-seller">
                                                <i class="fas fa-user"></i>
                                                <?= htmlspecialchars($listing['seller_name'] ?? 'Vendeur') ?>
                                            </p>
                                            <div class="listing-meta">
                                                <span class="meta-item">
                                                    <i class="fas fa-calendar"></i>
                                                    <?= date('d/m/Y', strtotime($listing['created_at'] ?? 'now')) ?>
                                                </span>
                                                <span class="meta-item">
                                                    <i class="fas fa-euro-sign"></i>
                                                    <?= number_format($listing['prix_total'] ?? 0, 0, ',', ' ') ?> €
                                                </span>
                                                <span class="meta-item">
                                                    <i class="fas fa-file-shield"></i>
                                                    Documents à vérifier
                                                </span>
                                            </div>
                                        </div>

                                        <div class="listing-actions">
                                            <a href="/admin/listing/<?= $listing['id'] ?>" class="btn btn-small btn-outline">
                                                <i class="fas fa-eye"></i>
                                                Voir
                                            </a>
                                            <a href="/admin/documents?listing_id=<?= $listing['id'] ?>" class="btn btn-small btn-warning">
                                                <i class="fas fa-file-shield"></i>
                                                Documents
                                            </a>
                                            <button type="button" class="btn btn-small btn-success approve-listing-btn" data-listing-id="<?= $listing['id'] ?>">
                                                <i class="fas fa-check"></i>
                                                Approuver
                                            </button>
                                            <button type="button" class="btn btn-small btn-danger reject-listing-btn" data-listing-id="<?= $listing['id'] ?>">
                                                <i class="fas fa-times"></i>
                                                Rejeter
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Activité récente -->
                    <?php if (!empty($recentActivities)): ?>
                        <div class="content-card">
                            <div class="card-header">
                                <h2 class="card-title">
                                    <i class="fas fa-history"></i>
                                    Activité récente du système
                                </h2>
                                <a href="/admin/activity" class="card-action">
                                    Voir tout
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>

                            <div class="activity-list">
                                <?php foreach (array_slice($recentActivities, 0, 10) as $activity): ?>
                                    <div class="activity-item">
                                        <div class="activity-icon">
                                            <i class="fas fa-<?= $activity['icon'] ?? 'info-circle' ?>"></i>
                                        </div>
                                        <div class="activity-content">
                                            <p class="activity-text"><?= htmlspecialchars($activity['message'] ?? $activity['description'] ?? 'Activité') ?></p>
                                            <div class="activity-meta">
                                                <span class="meta-item">
                                                    <i class="fas fa-user"></i>
                                                    <?= htmlspecialchars($activity['user_name'] ?? 'Système') ?>
                                                </span>
                                                <span class="meta-item">
                                                    <i class="fas fa-clock"></i>
                                                    <?= date('d/m/Y à H:i', strtotime($activity['created_at'] ?? 'now')) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Colonne latérale organisée -->
                <div class="sidebar-content">
                    <!-- Statistiques utilisateurs -->
                    <div class="content-card">
                        <div class="card-header">
                            <h2 class="card-title">
                                <i class="fas fa-users"></i>
                                Statistiques utilisateurs
                            </h2>
                        </div>
                        <div class="card-content">
                            <div class="user-stats-grid">
                                <div class="user-stat-card">
                                    <div class="user-stat-icon">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div class="user-stat-info">
                                        <span class="user-stat-number"><?= number_format($userStats['buyers'] ?? 0) ?></span>
                                        <span class="user-stat-label">Acheteurs</span>
                                    </div>
                                </div>

                                <div class="user-stat-card">
                                    <div class="user-stat-icon">
                                        <i class="fas fa-home"></i>
                                    </div>
                                    <div class="user-stat-info">
                                        <span class="user-stat-number"><?= number_format($userStats['sellers'] ?? 0) ?></span>
                                        <span class="user-stat-label">Vendeurs</span>
                                    </div>
                                </div>

                                <div class="user-stat-card">
                                    <div class="user-stat-icon">
                                        <i class="fas fa-gavel"></i>
                                    </div>
                                    <div class="user-stat-info">
                                        <span class="user-stat-number"><?= number_format($userStats['jury'] ?? 0) ?></span>
                                        <span class="user-stat-label">Jury</span>
                                    </div>
                                </div>

                                <div class="user-stat-card">
                                    <div class="user-stat-icon">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <div class="user-stat-info">
                                        <span class="user-stat-number"><?= number_format($userStats['admins'] ?? 0) ?></span>
                                        <span class="user-stat-label">Admins</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="content-card">
                        <div class="card-header">
                            <h2 class="card-title">
                                <i class="fas fa-bolt"></i>
                                Actions rapides
                            </h2>
                        </div>
                        <div class="card-content">
                            <div class="quick-actions-grid">
                                <a href="/admin/users" class="quick-action-card">
                                    <div class="quick-action-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="quick-action-content">
                                        <h3 class="quick-action-title">Gérer les utilisateurs</h3>
                                        <p class="quick-action-desc">Voir et modifier les comptes</p>
                                    </div>
                                </a>

                                <a href="/admin/listings" class="quick-action-card">
                                    <div class="quick-action-icon">
                                        <i class="fas fa-home"></i>
                                    </div>
                                    <div class="quick-action-content">
                                        <h3 class="quick-action-title">Modérer les annonces</h3>
                                        <p class="quick-action-desc">Valider les nouvelles annonces</p>
                                    </div>
                                </a>


                                <a href="/admin/tickets" class="quick-action-card">
                                    <div class="quick-action-icon">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                    <div class="quick-action-content">
                                        <h3 class="quick-action-title">Gérer les tickets</h3>
                                        <p class="quick-action-desc">Suivre les ventes</p>
                                    </div>
                                </a>

                                <a href="/admin/settings" class="quick-action-card">
                                    <div class="quick-action-icon">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                    <div class="quick-action-content">
                                        <h3 class="quick-action-title">Configuration</h3>
                                        <p class="quick-action-desc">Paramètres système</p>
                                    </div>
                                </a>

                                <a href="/admin/reports" class="quick-action-card">
                                    <div class="quick-action-icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <div class="quick-action-content">
                                        <h3 class="quick-action-title">Rapports</h3>
                                        <p class="quick-action-desc">Analyses et statistiques</p>
                                    </div>
                                </a>

                                <a href="/admin/activity" class="quick-action-card">
                                    <div class="quick-action-icon">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <div class="quick-action-content">
                                        <h3 class="quick-action-title">Activité</h3>
                                        <p class="quick-action-desc">Journal des actions</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Informations système -->
                    <div class="content-card">
                        <div class="card-header">
                            <h2 class="card-title">
                                <i class="fas fa-info-circle"></i>
                                Informations système
                            </h2>
                        </div>
                        <div class="card-content">
                            <div class="system-info-grid">
                                <div class="system-info-item">
                                    <div class="system-info-icon">
                                        <i class="fas fa-code-branch"></i>
                                    </div>
                                    <div class="system-info-content">
                                        <span class="system-info-label">Version</span>
                                        <span class="system-info-value"><?= htmlspecialchars($data['system']['version'] ?? '1.0.0') ?></span>
                                    </div>
                                </div>

                                <div class="system-info-item">
                                    <div class="system-info-icon">
                                        <i class="fas fa-server"></i>
                                    </div>
                                    <div class="system-info-content">
                                        <span class="system-info-label">Serveur</span>
                                        <span class="system-info-value status-ok">
                                            <i class="fas fa-check-circle"></i>
                                            Opérationnel
                                        </span>
                                    </div>
                                </div>

                                <div class="system-info-item">
                                    <div class="system-info-icon">
                                        <i class="fas fa-database"></i>
                                    </div>
                                    <div class="system-info-content">
                                        <span class="system-info-label">Base de données</span>
                                        <span class="system-info-value status-ok">
                                            <i class="fas fa-check-circle"></i>
                                            Connectée
                                        </span>
                                    </div>
                                </div>

                                <div class="system-info-item">
                                    <div class="system-info-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="system-info-content">
                                        <span class="system-info-label">Dernière MAJ</span>
                                        <span class="system-info-value"><?= date('d/m/Y') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de rejet d'annonce -->
    <div id="rejectModal" class="modal modal-hidden">
        <div class="modal-overlay"></div>
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Rejeter l'annonce
                </h3>
                <button class="modal-close" data-action="close-modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                <form id="rejectForm">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <input type="hidden" id="rejectListingId" name="listing_id">
                    <div class="form-group">
                        <label for="rejectReason">Raison du rejet *</label>
                        <select id="rejectReason" name="reason" class="form-select" required>
                            <option value="">Choisir une raison...</option>
                            <option value="incomplete">Informations incomplètes</option>
                            <option value="suspicious">Contenu suspect</option>
                            <option value="wrong_price">Prix non conforme</option>
                            <option value="fake_listing">Annonce fictive</option>
                            <option value="duplicate">Annonce en doublon</option>
                            <option value="inappropriate">Contenu inapproprié</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="rejectComment">Commentaire (optionnel)</label>
                        <textarea id="rejectComment" name="comment" class="form-textarea" rows="3"
                            placeholder="Ajoutez des détails sur le rejet..."></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-action="close-modal">
                    Annuler
                </button>
                <button type="button" class="btn btn-danger" data-action="submit-reject">
                    <i class="fas fa-times"></i>
                    Rejeter l'annonce
                </button>
            </div>
        </div>
    </div>

    <!-- ⚠️ Sécurité PROD : JavaScript inline supprimé — utiliser fichier .js externe -->
    <!-- Le JavaScript est maintenant géré par le fichier admin.js -->