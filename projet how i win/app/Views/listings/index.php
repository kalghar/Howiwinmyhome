<?php

/**
 * VUE DES ANNONCES - HOW I WIN MY HOME V1
 * 
 * Liste des annonces immobilières disponibles
 * avec filtres, recherche et affichage en grille
 * REFONTE COMPLÈTE MAS CABANIDS
 */

// Récupération des données depuis le contrôleur
$listings = $data['listings'] ?? [];
$categories = $data['categories'] ?? [];
$filters = $data['filters'] ?? [];
$totalListings = $data['totalListings'] ?? 0;
$isLoggedIn = $data['isLoggedIn'] ?? false;
$userRole = $data['userRole'] ?? null;
?>

<div class="listings-page-mascabanids">
    <div class="listings-container-mascabanids">
        <!-- HERO SECTION MAS CABANIDS -->
        <div class="listings-hero-mascabanids">
            <div class="hero-background-organic-mascabanids"></div>
            <div class="hero-content-mascabanids">
                <div class="hero-text-mascabanids">
                    <h1 class="hero-title-mascabanids">
                        Nos <span class="highlight-mascabanids">annonces</span> immobilières
                    </h1>
                    <p class="hero-subtitle-mascabanids">
                        Découvrez des biens exceptionnels et participez à nos concours équitables
                    </p>
                    <div class="hero-stats-mascabanids">
                        <div class="stat-badge-mascabanids">
                            <span class="stat-number-mascabanids"><?= number_format($totalListings) ?></span>
                            <span class="stat-label-mascabanids">Annonces</span>
                        </div>
                        <div class="stat-badge-mascabanids">
                            <span class="stat-number-mascabanids"><?= number_format($data['totalParticipants'] ?? 0) ?></span>
                            <span class="stat-label-mascabanids">Participants</span>
                        </div>
                    </div>
                </div>
                <div class="hero-actions-mascabanids">
                    <?php if ($isLoggedIn && $userRole === 'user'): ?>
                        <a href="/listings/create" class="btn-hero-mascabanids btn-primary-mascabanids">
                            <i class="fas fa-plus-circle"></i>
                            <span>Créer une annonce</span>
                        </a>
                    <?php endif; ?>
                    <button class="btn-hero-mascabanids btn-secondary-mascabanids filter-toggle-mascabanids">
                        <i class="fas fa-filter"></i>
                        <span>Filtres</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- FILTRES MAS CABANIDS -->
        <div class="filters-showcase-mascabanids" id="filtersSection">
            <div class="filters-container-mascabanids">
                <form method="GET" action="/listings" class="filters-form-mascabanids">
                    <!-- Recherche par texte -->
                    <div class="filter-group-mascabanids">
                        <label for="search" class="filter-label-mascabanids">
                            <i class="fas fa-search"></i>
                            Recherche
                        </label>
                        <input type="text"
                            id="search"
                            name="search"
                            class="filter-input-mascabanids"
                            placeholder="Rechercher un bien..."
                            value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                    </div>

                    <!-- Filtre par ville -->
                    <div class="filter-group-mascabanids">
                        <label for="ville" class="filter-label-mascabanids">
                            <i class="fas fa-map-marker-alt"></i>
                            Ville
                        </label>
                        <select id="ville" name="ville" class="filter-select-mascabanids">
                            <option value="">Toutes les villes</option>
                            <?php foreach ($data['villes'] ?? [] as $ville): ?>
                                <option value="<?= htmlspecialchars($ville) ?>"
                                    <?= ($filters['ville'] ?? '') === $ville ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ville) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filtre par type de bien -->
                    <div class="filter-group-mascabanids">
                        <label for="type" class="filter-label-mascabanids">
                            <i class="fas fa-home"></i>
                            Type de bien
                        </label>
                        <select id="type" name="type" class="filter-select-mascabanids">
                            <option value="">Tous les types</option>
                            <option value="apartment" <?= ($filters['type'] ?? '') === 'apartment' ? 'selected' : '' ?>>
                                Appartement
                            </option>
                            <option value="house" <?= ($filters['type'] ?? '') === 'house' ? 'selected' : '' ?>>
                                Maison
                            </option>
                            <option value="villa" <?= ($filters['type'] ?? '') === 'villa' ? 'selected' : '' ?>>
                                Villa
                            </option>
                            <option value="terrain" <?= ($filters['type'] ?? '') === 'terrain' ? 'selected' : '' ?>>
                                Terrain
                            </option>
                        </select>
                    </div>

                    <!-- Filtre par prix de ticket -->
                    <div class="filter-group-mascabanids">
                        <label for="prix_max" class="filter-label-mascabanids">
                            <i class="fas fa-euro-sign"></i>
                            Prix max. par ticket
                        </label>
                        <input type="number"
                            id="prix_max"
                            name="prix_max"
                            class="filter-input-mascabanids"
                            placeholder="Prix maximum"
                            min="5"
                            max="20"
                            value="<?= htmlspecialchars($filters['prix_max'] ?? '20') ?>">
                    </div>

                    <!-- Filtre par surface -->
                    <div class="filter-group-mascabanids">
                        <label for="surface_min" class="filter-label-mascabanids">
                            <i class="fas fa-ruler-combined"></i>
                            Surface min. (m²)
                        </label>
                        <input type="number"
                            id="surface_min"
                            name="surface_min"
                            class="filter-input-mascabanids"
                            placeholder="Surface minimum"
                            min="1"
                            value="<?= htmlspecialchars($filters['surface_min'] ?? '') ?>">
                    </div>

                    <!-- Boutons des filtres -->
                    <div class="filter-actions-mascabanids">
                        <button type="submit" class="btn-filter-mascabanids btn-filter-primary-mascabanids">
                            <i class="fas fa-check"></i>
                            <span>Appliquer les filtres</span>
                        </button>
                        <a href="/listings" class="btn-filter-mascabanids btn-filter-outline-mascabanids">
                            <i class="fas fa-refresh"></i>
                            <span>Réinitialiser</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- CONTENU PRINCIPAL MAS CABANIDS -->
        <div class="listings-content-mascabanids">
            <?php if (empty($listings)): ?>
                <!-- ÉTAT VIDE MAS CABANIDS -->
                <div class="empty-state-mascabanids">
                    <div class="empty-icon-mascabanids">
                        <i class="fas fa-home"></i>
                    </div>
                    <h2 class="empty-title-mascabanids">Aucune annonce disponible</h2>
                    <p class="empty-description-mascabanids">
                        Il n'y a actuellement aucune annonce correspondant à vos critères.
                        Revenez plus tard ou modifiez vos filtres de recherche.
                    </p>
                    <div class="empty-actions-mascabanids">
                        <a href="/listings" class="btn-action-mascabanids btn-primary-mascabanids">
                            <i class="fas fa-refresh"></i>
                            <span>Voir toutes les annonces</span>
                        </a>
                        <?php if ($isLoggedIn && $userRole === 'user'): ?>
                            <a href="/listings/create" class="btn-action-mascabanids btn-outline-mascabanids">
                                <i class="fas fa-plus"></i>
                                <span>Créer une annonce</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- GRILLE DES ANNONCES MAS CABANIDS -->
                <div class="listings-grid-mascabanids">
                    <?php foreach ($listings as $listing): ?>
                        <div class="listing-card-mascabanids" data-listing-id="<?= $listing['id'] ?>">
                            <!-- Image de l'annonce -->
                            <div class="listing-image-mascabanids">
                                <?php if (!empty($listing['image'])): ?>
                                    <img src="<?= htmlspecialchars($listing['image']) ?>"
                                        alt="<?= htmlspecialchars($listing['title'] ?? 'Image de l\'annonce') ?>"
                                        loading="lazy">
                                <?php else: ?>
                                    <div class="listing-placeholder-mascabanids">
                                        <i class="fas fa-home"></i>
                                    </div>
                                <?php endif; ?>

                                <!-- Badges -->
                                <div class="listing-badges-mascabanids">
                                    <?php if ($listing['status'] === 'featured'): ?>
                                        <span class="badge-mascabanids badge-featured-mascabanids">
                                            <i class="fas fa-star"></i>
                                            <span>Vedette</span>
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($listing['status'] === 'new'): ?>
                                        <span class="badge-mascabanids badge-new-mascabanids">
                                            <i class="fas fa-fire"></i>
                                            <span>Nouveau</span>
                                        </span>
                                    <?php endif; ?>

                                    <span class="badge-mascabanids badge-status-mascabanids status-<?= $listing['status'] ?>">
                                        <?= ucfirst($listing['status']) ?>
                                    </span>
                                </div>

                                <!-- Prix du ticket -->
                                <div class="ticket-price-mascabanids">
                                    <span class="price-amount-mascabanids"><?= number_format($listing['ticket_price'], 0) ?>€</span>
                                    <span class="price-label-mascabanids">par ticket</span>
                                </div>
                            </div>

                            <!-- Contenu de l'annonce -->
                            <div class="listing-content-mascabanids">
                                <h3 class="listing-title-mascabanids">
                                    <a href="/listings/view?id=<?= $listing['id'] ?>">
                                        <?= htmlspecialchars($listing['title'] ?? 'Titre non disponible') ?>
                                    </a>
                                </h3>

                                <p class="listing-location-mascabanids">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?= htmlspecialchars($listing['city'] ?? 'Ville non précisée') ?></span>
                                </p>

                                <!-- Caractéristiques du bien -->
                                <div class="listing-features-mascabanids">
                                    <div class="feature-item-mascabanids">
                                        <i class="fas fa-ruler-combined"></i>
                                        <span><?= number_format($listing['property_size'] ?? 0) ?> m²</span>
                                    </div>
                                    <div class="feature-item-mascabanids">
                                        <i class="fas fa-door-open"></i>
                                        <span><?= $listing['rooms'] ?? 0 ?> pièces</span>
                                    </div>
                                    <div class="feature-item-mascabanids">
                                        <i class="fas fa-bed"></i>
                                        <span><?= $listing['bedrooms'] ?? 0 ?> chambres</span>
                                    </div>
                                </div>

                                <!-- Progression des tickets -->
                                <div class="tickets-progress-mascabanids">
                                    <div class="progress-header-mascabanids">
                                        <span class="progress-label-mascabanids">Tickets vendus</span>
                                        <span class="progress-count-mascabanids">
                                            <?= $listing['tickets_vendus'] ?? 0 ?> / <?= $listing['tickets_needed'] ?>
                                        </span>
                                    </div>
                                    <div class="progress-bar-mascabanids">
                                        <div class="progress-fill-mascabanids"
                                            data-width="<?= min(100, (($listing['tickets_vendus'] ?? 0) / $listing['tickets_needed']) * 100) ?>">
                                        </div>
                                    </div>
                                    <div class="progress-info-mascabanids">
                                        <span class="remaining-tickets-mascabanids">
                                            <?= max(0, $listing['tickets_needed'] - ($listing['tickets_vendus'] ?? 0)) ?> tickets restants
                                        </span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="listing-actions-mascabanids">
                                    <a href="/listings/view?id=<?= $listing['id'] ?>" class="btn-action-small-mascabanids btn-outline-mascabanids">
                                        <i class="fas fa-eye"></i>
                                        <span>Voir détails</span>
                                    </a>

                                    <?php if ($isLoggedIn): ?>
                                        <a href="/game/buy-ticket?listing_id=<?= $listing['id'] ?>" class="btn-action-small-mascabanids btn-primary-mascabanids">
                                            <i class="fas fa-ticket-alt"></i>
                                            <span>Acheter un ticket</span>
                                        </a>
                                    <?php else: ?>
                                        <a href="/auth/login" class="btn-action-small-mascabanids btn-primary-mascabanids">
                                            <i class="fas fa-sign-in-alt"></i>
                                            <span>Se connecter</span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- PAGINATION MAS CABANIDS -->
                <?php if (isset($data['pagination']) && $data['pagination']['totalPages'] > 1): ?>
                    <div class="pagination-section-mascabanids">
                        <div class="pagination-info-mascabanids">
                            Affichage de <?= $data['pagination']['start'] + 1 ?> à
                            <?= min($data['pagination']['end'], $totalListings) ?>
                            sur <?= $totalListings ?> annonces
                        </div>

                        <div class="pagination-controls-mascabanids">
                            <?php if ($data['pagination']['currentPage'] > 1): ?>
                                <a href="?page=<?= $data['pagination']['currentPage'] - 1 ?>" class="pagination-link-mascabanids">
                                    <i class="fas fa-chevron-left"></i>
                                    <span>Précédent</span>
                                </a>
                            <?php endif; ?>

                            <?php for (
                                $i = max(1, $data['pagination']['currentPage'] - 2);
                                $i <= min($data['pagination']['totalPages'], $data['pagination']['currentPage'] + 2);
                                $i++
                            ): ?>
                                <a href="?page=<?= $i ?>"
                                    class="pagination-link-mascabanids <?= $i === $data['pagination']['currentPage'] ? 'active' : '' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($data['pagination']['currentPage'] < $data['pagination']['totalPages']): ?>
                                <a href="?page=<?= $data['pagination']['currentPage'] + 1 ?>" class="pagination-link-mascabanids">
                                    <span>Suivant</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- JavaScript pour les filtres -->
<script src="/assets/js/listings.js"></script>
<!-- Le JavaScript est maintenant géré par le fichier listings.js -->