<?php

/**
 * VUE DU TABLEAU DE BORD - HOW I WIN MY HOME V2 - MAS CABANIDS DESIGN
 * 
 * Tableau de bord principal des utilisateurs connectés
 * avec design Mas Cabanids : glassmorphism, organic shapes, animations
 */

// Récupération des données depuis le contrôleur
$user = $data['user'] ?? [];
$stats = $data['stats'] ?? [];
$recentListings = $data['recentListings'] ?? [];
$recentTickets = $data['recentTickets'] ?? [];
$recentLetters = $data['recentLetters'] ?? [];
$isLoggedIn = $data['isLoggedIn'] ?? false;
$userRole = $data['userRole'] ?? null;
$userBalance = $data['userBalance'] ?? 0;
?>

<div class="dashboard-page-mascabanids">
    <div class="dashboard-container-mascabanids">

        <!-- HERO SECTION MAS CABANIDS -->
        <div class="dashboard-hero-mascabanids">
            <div class="hero-background-organic"></div>
            <div class="hero-content-mascabanids">
                <div class="hero-text-mascabanids">
                    <h1 class="hero-title-mascabanids">
                        <span class="hero-greeting">Bonjour</span>
                        <span class="hero-name-highlight"><?= htmlspecialchars($user['first_name'] ?? 'Utilisateur') ?></span>
                    </h1>
                    <p class="hero-subtitle-mascabanids">
                        Votre espace personnel pour gérer vos concours immobiliers
                    </p>
                    <div class="hero-meta-mascabanids">
                        <div class="meta-item-organic">
                            <i class="fas fa-clock"></i>
                            <span>Dernière connexion : <?= date('d/m/Y à H:i') ?></span>
                        </div>
                    </div>
                </div>
                <div class="hero-actions-mascabanids">
                    <a href="/listings/create" class="btn-hero-mascabanids btn-primary-mascabanids">
                        <i class="fas fa-plus-circle"></i>
                        <span>Créer une annonce</span>
                    </a>
                    <a href="/listings" class="btn-hero-mascabanids btn-secondary-mascabanids">
                        <i class="fas fa-search"></i>
                        <span>Explorer les annonces</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- BALANCE SECTION MAS CABANIDS -->
        <div class="balance-section-mascabanids">
            <div class="balance-card-mascabanids">
                <div class="balance-header-mascabanids">
                    <div class="balance-icon-mascabanids">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="balance-info-mascabanids">
                        <h3 class="balance-title-mascabanids">Mon solde</h3>
                        <div class="balance-amount-mascabanids">
                            <?= number_format($userBalance, 2, ',', ' ') ?>€
                        </div>
                    </div>
                </div>
                <div class="balance-actions-mascabanids">
                    <a href="/account/deposit" class="btn-action-mascabanids btn-primary-mascabanids">
                        <i class="fas fa-plus"></i>
                        <span>Recharger</span>
                    </a>
                    <a href="/account/history" class="btn-action-mascabanids btn-outline-mascabanids">
                        <i class="fas fa-history"></i>
                        <span>Historique</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- STATISTIQUES MAS CABANIDS -->
        <div class="stats-showcase-mascabanids">
            <div class="showcase-header-mascabanids">
                <h2 class="showcase-title-mascabanids">Vos performances</h2>
                <p class="showcase-subtitle-mascabanids">Un aperçu de votre activité sur la plateforme</p>
            </div>

            <div class="stats-grid-mascabanids">
                <div class="stat-card-mascabanids">
                    <div class="stat-icon-mascabanids">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="stat-content-mascabanids">
                        <div class="stat-number-mascabanids"><?= number_format($stats['total_tickets'] ?? 0) ?></div>
                        <div class="stat-label-mascabanids">Mes tickets</div>
                        <div class="stat-description-mascabanids">Tickets achetés</div>
                    </div>
                    <div class="stat-decoration-mascabanids"></div>
                </div>

                <div class="stat-card-mascabanids">
                    <div class="stat-icon-mascabanids">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="stat-content-mascabanids">
                        <div class="stat-number-mascabanids"><?= number_format($stats['total_listings'] ?? 0) ?></div>
                        <div class="stat-label-mascabanids">Mes annonces</div>
                        <div class="stat-description-mascabanids">Biens en concours</div>
                    </div>
                    <div class="stat-decoration-mascabanids"></div>
                </div>

                <a href="/game/my-letters" class="stat-card-mascabanids stat-card-clickable-mascabanids">
                    <div class="stat-icon-mascabanids">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-content-mascabanids">
                        <div class="stat-number-mascabanids"><?= number_format($stats['total_letters'] ?? 0) ?></div>
                        <div class="stat-label-mascabanids">Mes lettres</div>
                        <div class="stat-description-mascabanids">Lettres de motivation</div>
                    </div>
                    <div class="stat-decoration-mascabanids"></div>
                </a>

                <div class="stat-card-mascabanids">
                    <div class="stat-icon-mascabanids">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div class="stat-content-mascabanids">
                        <div class="stat-number-mascabanids"><?= number_format($stats['total_participations'] ?? 0) ?></div>
                        <div class="stat-label-mascabanids">Participations QCM</div>
                        <div class="stat-description-mascabanids">Tests complétés</div>
                    </div>
                    <div class="stat-decoration-mascabanids"></div>
                </div>
            </div>
        </div>

        <!-- CONTENU PRINCIPAL MAS CABANIDS -->
        <div class="dashboard-content-mascabanids">
            <!-- MES ANNONCES -->
            <?php if (!empty($data['listings'])): ?>
                <div class="content-card-mascabanids">
                    <div class="card-header-mascabanids">
                        <div class="card-title-mascabanids">
                            <div class="card-icon-mascabanids">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="card-text-mascabanids">
                                <h3>Mes annonces récentes</h3>
                                <p>Gérez vos biens en concours</p>
                            </div>
                        </div>
                        <a href="/listings/my-listings" class="card-action-mascabanids">
                            <span>Voir toutes</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="card-content-mascabanids">
                        <div class="listings-grid-mascabanids">
                            <?php foreach (array_slice($data['listings'], 0, 3) as $listing): ?>
                                <div class="listing-item-mascabanids">
                                    <div class="listing-image-mascabanids">
                                        <?php if (!empty($listing['image'])): ?>
                                            <img src="<?= htmlspecialchars($listing['image']) ?>" alt="<?= htmlspecialchars($listing['title']) ?>">
                                        <?php else: ?>
                                            <div class="listing-placeholder-mascabanids">
                                                <i class="fas fa-home"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="listing-status-badge-mascabanids status-<?= $listing['status'] ?>">
                                            <?= ucfirst($listing['status']) ?>
                                        </div>
                                    </div>

                                    <div class="listing-info-mascabanids">
                                        <h4 class="listing-title-mascabanids"><?= htmlspecialchars($listing['title']) ?></h4>
                                        <p class="listing-location-mascabanids">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?= htmlspecialchars($listing['city'] ?? 'Ville non précisée') ?>
                                        </p>
                                        <div class="listing-meta-mascabanids">
                                            <div class="meta-item-mascabanids">
                                                <i class="fas fa-ticket-alt"></i>
                                                <span><?= number_format($listing['tickets_needed']) ?> tickets</span>
                                            </div>
                                            <div class="meta-item-mascabanids">
                                                <i class="fas fa-euro-sign"></i>
                                                <span><?= number_format($listing['ticket_price']) ?>€</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="listing-actions-mascabanids">
                                        <a href="/listings/edit?id=<?= $listing['id'] ?>" class="btn-action-small-mascabanids btn-outline-mascabanids">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/listings/view?id=<?= $listing['id'] ?>" class="btn-action-small-mascabanids btn-primary-mascabanids">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- MES TICKETS -->
            <?php if (!empty($data['tickets'])): ?>
                <div class="content-card-mascabanids">
                    <div class="card-header-mascabanids">
                        <div class="card-title-mascabanids">
                            <div class="card-icon-mascabanids">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div class="card-text-mascabanids">
                                <h3>Mes tickets récents</h3>
                                <p>Suivez vos participations</p>
                            </div>
                        </div>
                        <a href="/ticket/my-tickets" class="card-action-mascabanids">
                            <span>Voir tous</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="card-content-mascabanids">
                        <div class="tickets-grid-mascabanids">
                            <?php foreach (array_slice($data['tickets'], 0, 4) as $ticket): ?>
                                <div class="ticket-item-mascabanids">
                                    <div class="ticket-icon-mascabanids">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                    <div class="ticket-info-mascabanids">
                                        <h4 class="ticket-number-mascabanids"><?= htmlspecialchars($ticket['numero_ticket']) ?></h4>
                                        <p class="ticket-listing-mascabanids"><?= htmlspecialchars($ticket['listing_titre'] ?? 'Annonce') ?></p>
                                        <div class="ticket-meta-mascabanids">
                                            <div class="meta-item-mascabanids">
                                                <i class="fas fa-calendar"></i>
                                                <span><?= date('d/m/Y', strtotime($ticket['date_achat'])) ?></span>
                                            </div>
                                            <div class="meta-item-mascabanids">
                                                <i class="fas fa-euro-sign"></i>
                                                <span><?= number_format($ticket['ticket_price']) ?>€</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ticket-status-mascabanids">
                                        <span class="status-badge-mascabanids status-<?= $ticket['status'] ?>">
                                            <?= ucfirst($ticket['status']) ?>
                                        </span>
                                        <a href="/tickets/view?id=<?= $ticket['id'] ?>" class="btn-action-small-mascabanids btn-primary-mascabanids">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- MES LETTRES -->
            <?php if (!empty($data['letters'])): ?>
                <div class="content-card-mascabanids">
                    <div class="card-header-mascabanids">
                        <div class="card-title-mascabanids">
                            <div class="card-icon-mascabanids">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="card-text-mascabanids">
                                <h3>Mes lettres récentes</h3>
                                <p>Lettres de motivation</p>
                            </div>
                        </div>
                        <a href="/game/my-letters" class="card-action-mascabanids">
                            <span>Voir toutes</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="card-content-mascabanids">
                        <div class="letters-grid-mascabanids">
                            <?php foreach (array_slice($data['letters'], 0, 4) as $letter): ?>
                                <div class="letter-item-mascabanids">
                                    <div class="letter-icon-mascabanids">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="letter-info-mascabanids">
                                        <h4 class="letter-title-mascabanids"><?= htmlspecialchars($letter['title'] ?? 'Lettre de motivation') ?></h4>
                                        <p class="letter-listing-mascabanids"><?= htmlspecialchars($letter['listing_titre'] ?? 'Annonce') ?></p>
                                        <div class="letter-meta-mascabanids">
                                            <div class="meta-item-mascabanids">
                                                <i class="fas fa-calendar"></i>
                                                <span><?= date('d/m/Y', strtotime($letter['created_at'])) ?></span>
                                            </div>
                                            <div class="meta-item-mascabanids">
                                                <i class="fas fa-check-circle"></i>
                                                <span><?= ucfirst($letter['status'] ?? 'En attente') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="letter-actions-mascabanids">
                                        <a href="/letter/view?id=<?= $letter['id'] ?>" class="btn-action-small-mascabanids btn-primary-mascabanids">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>
</div>