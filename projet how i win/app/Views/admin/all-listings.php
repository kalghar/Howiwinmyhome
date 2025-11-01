<?php
/**
 * VUE TOUTES LES ANNONCES - HOW I WIN MY HOME V1
 * 
 * Interface d'administration pour gérer toutes les annonces
 */

// Récupération des données depuis le contrôleur
$listings = $data['listings'] ?? [];
$listingStats = $data['listingStats'] ?? [];
$isLoggedIn = $data['isLoggedIn'] ?? false;
$userRole = $data['userRole'] ?? null;
?>

<div class="admin-page">
    <div class="admin-container">
        <!-- En-tête d'administration -->
        <div class="page-header">
            <!-- Titre et description -->
            <div class="header-content">
                <div class="title-section">
                    <h1 class="page-title">
                        <i class="fas fa-home"></i>
                        Toutes les annonces
                    </h1>
                    <p class="page-description">
                        Gérez toutes les annonces immobilières de la plateforme
                    </p>
                </div>
                
                <!-- Statut de session -->
                <div class="header-status">
                    <div class="status-indicator">
                        <i class="fas fa-shield-alt"></i>
                        <span>Mode administrateur actif</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistiques des annonces -->
        <div class="statistics-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-chart-bar"></i>
                    Statistiques des annonces
                </h2>
            </div>
            
            <div class="stats-grid-enhanced">
                <div class="stat-card-enhanced stat-card-total">
                    <div class="stat-card-header">
                        <div class="stat-icon-enhanced">
                            <i class="fas fa-home"></i>
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number-enhanced"><?= number_format($listingStats['total'] ?? 0) ?></div>
                        <div class="stat-label-enhanced">Total annonces</div>
                        <div class="stat-subtitle">Toutes les annonces</div>
                    </div>
                </div>
                
                <div class="stat-card-enhanced stat-card-active">
                    <div class="stat-card-header">
                        <div class="stat-icon-enhanced">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number-enhanced"><?= number_format($listingStats['active'] ?? 0) ?></div>
                        <div class="stat-label-enhanced">Annonces actives</div>
                        <div class="stat-subtitle">En ligne</div>
                    </div>
                </div>
                
                <div class="stat-card-enhanced stat-card-pending">
                    <div class="stat-card-header">
                        <div class="stat-icon-enhanced">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number-enhanced"><?= number_format($listingStats['pending'] ?? 0) ?></div>
                        <div class="stat-label-enhanced">En attente</div>
                        <div class="stat-subtitle">À modérer</div>
                    </div>
                </div>
                
                <div class="stat-card-enhanced stat-card-rejected">
                    <div class="stat-card-header">
                        <div class="stat-icon-enhanced">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number-enhanced"><?= number_format($listingStats['rejected'] ?? 0) ?></div>
                        <div class="stat-label-enhanced">Rejetées</div>
                        <div class="stat-subtitle">Non approuvées</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Liste des annonces -->
        <div class="content-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-list"></i>
                    Liste des annonces
                </h2>
                <div class="section-actions">
                    <a href="/admin/pending-listings" class="btn btn-primary">
                        <i class="fas fa-clock"></i>
                        Voir les annonces en attente
                    </a>
                </div>
            </div>
            
            <div class="listings-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Prix</th>
                            <th>Vendeur</th>
                            <th>Statut</th>
                            <th>Date création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($listings)): ?>
                            <?php foreach ($listings as $listing): ?>
                            <tr>
                                <td><?= htmlspecialchars($listing['id'] ?? 'N/A') ?></td>
                                <td>
                                    <div class="listing-info">
                                        <div class="listing-title">
                                            <?= htmlspecialchars($listing['title'] ?? 'Sans titre') ?>
                                        </div>
                                        <div class="listing-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?= htmlspecialchars($listing['city'] ?? 'Non spécifié') ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="price-info">
                                        <span class="price"><?= number_format($listing['prix_total'] ?? 0, 0, ',', ' ') ?> €</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="seller-info">
                                        <span class="seller-name"><?= htmlspecialchars($listing['seller_name'] ?? 'Vendeur') ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= htmlspecialchars($listing['status'] ?? 'pending') ?>">
                                        <?= ucfirst(htmlspecialchars($listing['status'] ?? 'pending')) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($listing['created_at'] ?? 'now')) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="/admin/listing/<?= $listing['id'] ?>" class="btn btn-small btn-outline">
                                            <i class="fas fa-eye"></i>
                                            Voir
                                        </a>
                                        <button class="btn btn-small btn-warning" data-action="edit" data-listing-id="<?= $listing['id'] ?>">
                                            <i class="fas fa-edit"></i>
                                            Modifier
                                        </button>
                                        <?php if ($listing['status'] === 'pending'): ?>
                                            <button class="btn btn-small btn-success" data-action="approve" data-listing-id="<?= $listing['id'] ?>">
                                                <i class="fas fa-check"></i>
                                                Approuver
                                            </button>
                                            <button class="btn btn-small btn-danger" data-action="reject" data-listing-id="<?= $listing['id'] ?>">
                                                <i class="fas fa-times"></i>
                                                Rejeter
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-home"></i>
                                        <p>Aucune annonce trouvée</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- ⚠️ Sécurité PROD : JavaScript inline supprimé — utiliser fichier .js externe -->
<!-- Le JavaScript est maintenant géré par le fichier admin-listings.js -->
