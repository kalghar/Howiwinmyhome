<?php

/**
 * VUE DES ANNONCES DE L'UTILISATEUR - HOW I WIN MY HOME V1
 * 
 * Interface de gestion des annonces créées
 * par l'utilisateur connecté
 * REFONTE COMPLÈTE MAS CABANIDS
 */

// Récupération des données depuis le contrôleur
$listings = $data['listings'] ?? [];
$stats = $data['stats'] ?? [];
?>

<div class="my-listings-page-mascabanids">
    <div class="my-listings-container-mascabanids">
        <!-- HERO SECTION MAS CABANIDS -->
        <div class="my-listings-hero-mascabanids">
            <div class="hero-background-organic-mascabanids"></div>
            <div class="hero-content-mascabanids">
                <div class="hero-text-mascabanids">
                    <h1 class="hero-title-mascabanids">
                        <i class="fas fa-home"></i>
                        Mes <span class="highlight-mascabanids">Annonces</span>
                    </h1>
                    <p class="hero-subtitle-mascabanids">
                        Gérez vos annonces et suivez leurs performances
                    </p>
                </div>

                <div class="hero-actions-mascabanids">
                    <a href="/listings/create" class="btn-hero-mascabanids btn-primary-mascabanids">
                        <i class="fas fa-plus-circle"></i>
                        <span>Créer une annonce</span>
                    </a>

                    <a href="/dashboard" class="btn-hero-mascabanids btn-secondary-mascabanids">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Tableau de bord</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- STATISTIQUES MAS CABANIDS -->
        <div class="statistics-section-mascabanids">
            <div class="stats-card-mascabanids">
                <h3 class="stats-title-mascabanids">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistiques de vos annonces</span>
                </h3>

                <div class="stats-grid-mascabanids">
                    <div class="stat-item-mascabanids stat-total-mascabanids">
                        <div class="stat-icon-mascabanids">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="stat-content-mascabanids">
                            <span class="stat-number-mascabanids"><?= $stats['total_listings'] ?? 0 ?></span>
                            <span class="stat-label-mascabanids">Total des annonces</span>
                        </div>
                    </div>

                    <div class="stat-item-mascabanids stat-active-mascabanids">
                        <div class="stat-icon-mascabanids">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content-mascabanids">
                            <span class="stat-number-mascabanids"><?= $stats['active_listings'] ?? 0 ?></span>
                            <span class="stat-label-mascabanids">Annonces actives</span>
                        </div>
                    </div>

                    <div class="stat-item-mascabanids stat-pending-mascabanids">
                        <div class="stat-icon-mascabanids">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content-mascabanids">
                            <span class="stat-number-mascabanids"><?= $stats['pending_listings'] ?? 0 ?></span>
                            <span class="stat-label-mascabanids">En attente</span>
                        </div>
                    </div>

                    <div class="stat-item-mascabanids stat-tickets-mascabanids">
                        <div class="stat-icon-mascabanids">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="stat-content-mascabanids">
                            <span class="stat-number-mascabanids"><?= $stats['total_tickets_sold'] ?? 0 ?></span>
                            <span class="stat-label-mascabanids">Tickets vendus</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- LISTE DES ANNONCES MAS CABANIDS -->
        <div class="listings-section-mascabanids">
            <div class="listings-header-mascabanids">
                <h3 class="section-title-mascabanids">
                    <i class="fas fa-list"></i>
                    <span>Vos annonces</span>
                </h3>

                <div class="listings-count-mascabanids">
                    <span class="count-text-mascabanids">
                        <?= count($listings) ?> annonce<?= count($listings) > 1 ? 's' : '' ?> trouvée<?= count($listings) > 1 ? 's' : '' ?>
                    </span>
                </div>
            </div>

            <?php if (empty($listings)): ?>
                <!-- ÉTAT VIDE MAS CABANIDS -->
                <div class="empty-state-mascabanids">
                    <div class="empty-icon-mascabanids">
                        <i class="fas fa-home"></i>
                    </div>
                    <h4 class="empty-title-mascabanids">Aucune annonce trouvée</h4>
                    <p class="empty-description-mascabanids">
                        Vous n'avez pas encore créé d'annonce.
                        Commencez par créer votre première annonce !
                    </p>

                    <div class="empty-actions-mascabanids">
                        <a href="/listings/create" class="btn-action-mascabanids btn-primary-mascabanids">
                            <i class="fas fa-plus"></i>
                            <span>Créer une annonce</span>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- GRILLE DES ANNONCES MAS CABANIDS -->
                <div class="listings-grid-mascabanids">
                    <?php foreach ($listings as $listing): ?>
                        <div class="listing-card-mascabanids" data-listing-id="<?= $listing['id'] ?>">
                            <!-- Header de l'annonce -->
                            <div class="listing-header-mascabanids">
                                <div class="listing-info-mascabanids">
                                    <h4 class="listing-title-mascabanids">
                                        <?= htmlspecialchars($listing['title']) ?>
                                    </h4>
                                    <div class="listing-meta-mascabanids">
                                        <span class="meta-item-mascabanids">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span><?= htmlspecialchars($listing['city']) ?></span>
                                        </span>
                                        <span class="meta-item-mascabanids">
                                            <i class="fas fa-calendar"></i>
                                            <span>Créée le <?= date('d/m/Y', strtotime($listing['created_at'])) ?></span>
                                        </span>
                                    </div>
                                </div>

                                <div class="listing-status-mascabanids">
                                    <?php
                                    $statusClass = '';
                                    $statusText = '';
                                    $statusIcon = '';

                                    switch ($listing['status']) {
                                        case 'active':
                                            $statusClass = 'active';
                                            $statusText = 'Active';
                                            $statusIcon = 'fas fa-check-circle';
                                            break;
                                        case 'pending':
                                            $statusClass = 'pending';
                                            $statusText = 'En attente';
                                            $statusIcon = 'fas fa-clock';
                                            break;
                                        case 'rejected':
                                            $statusClass = 'rejected';
                                            $statusText = 'Rejetée';
                                            $statusIcon = 'fas fa-times-circle';
                                            break;
                                        default:
                                            $statusClass = 'unknown';
                                            $statusText = 'Inconnu';
                                            $statusIcon = 'fas fa-question-circle';
                                    }
                                    ?>

                                    <span class="status-badge-mascabanids status-<?= $statusClass ?>-mascabanids">
                                        <i class="<?= $statusIcon ?>"></i>
                                        <span><?= $statusText ?></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Contenu de l'annonce -->
                            <div class="listing-content-mascabanids">
                                <div class="listing-preview-mascabanids">
                                    <div class="listing-image-mascabanids">
                                        <?php if (!empty($listing['image'])): ?>
                                            <img src="/uploads/<?= htmlspecialchars($listing['image']) ?>"
                                                alt="<?= htmlspecialchars($listing['titre']) ?>"
                                                class="preview-img-mascabanids">
                                        <?php else: ?>
                                            <div class="no-image-mascabanids">
                                                <i class="fas fa-home"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="listing-details-mascabanids">
                                        <p class="listing-description-mascabanids">
                                            <?= htmlspecialchars(substr($listing['description'] ?? '', 0, 150)) ?>
                                            <?= strlen($listing['description'] ?? '') > 150 ? '...' : '' ?>
                                        </p>

                                        <div class="listing-meta-mascabanids">
                                            <span class="meta-item-mascabanids">
                                                <i class="fas fa-euro-sign"></i>
                                                <span><?= number_format($listing['prix_total'] ?? 0, 0, ',', ' ') ?> €</span>
                                            </span>
                                            <span class="meta-item-mascabanids">
                                                <i class="fas fa-ticket-alt"></i>
                                                <span><?= number_format($listing['prix_ticket'] ?? 0, 0, ',', ' ') ?> € le ticket</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions et statistiques -->
                            <div class="listing-actions-mascabanids">
                                <div class="actions-buttons-mascabanids">
                                    <a href="/listings/view?id=<?= $listing['id'] ?>"
                                        class="btn-action-small-mascabanids btn-outline-mascabanids"
                                        title="Voir l'annonce">
                                        <i class="fas fa-eye"></i>
                                        <span>Voir</span>
                                    </a>

                                    <?php if ($listing['status'] === 'active'): ?>
                                        <a href="/listings/edit?id=<?= $listing['id'] ?>"
                                            class="btn-action-small-mascabanids btn-primary-mascabanids"
                                            title="Modifier l'annonce">
                                            <i class="fas fa-edit"></i>
                                            <span>Modifier</span>
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <div class="listing-stats-mascabanids">
                                    <span class="stat-label-mascabanids">Tickets vendus :</span>
                                    <span class="stat-value-mascabanids"><?= $listing['tickets_vendus'] ?? 0 ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ⚠️ Sécurité PROD : JavaScript inline supprimé — utiliser fichier .js externe -->
<!-- Le JavaScript est maintenant géré par le fichier my-listings.js -->