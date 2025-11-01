<?php

/**
 * VUE DES RÉSULTATS DE RECHERCHE - HOW I WIN MY HOME V1
 * 
 * Affiche les résultats de recherche avec filtres appliqués
 */

// Récupération des données depuis le contrôleur
$results = $data['results'] ?? [];
$searchTerm = $data['searchTerm'] ?? '';
$filters = $data['filters'] ?? [];
$totalResults = count($results);
$isLoggedIn = $data['isLoggedIn'] ?? false;
$userRole = $data['userRole'] ?? null;
?>

<div class="listings-page-mascabanids">
    <div class="listings-container-mascabanids">
        <!-- HERO SECTION RECHERCHE -->
        <div class="listings-hero-mascabanids">
            <div class="hero-background-organic-mascabanids"></div>
            <div class="hero-content-mascabanids">
                <div class="hero-text-mascabanids">
                    <h1 class="hero-title-mascabanids">
                        Résultats de <span class="highlight-mascabanids">recherche</span>
                    </h1>
                    <?php if (!empty($searchTerm)): ?>
                        <p class="hero-subtitle-mascabanids">
                            Recherche pour : <strong>"<?= htmlspecialchars($searchTerm) ?>"</strong>
                        </p>
                    <?php else: ?>
                        <p class="hero-subtitle-mascabanids">
                            Recherche avec filtres appliqués
                        </p>
                    <?php endif; ?>

                    <div class="hero-stats-mascabanids">
                        <div class="stat-badge-mascabanids">
                            <span class="stat-number-mascabanids"><?= number_format($totalResults) ?></span>
                            <span class="stat-label-mascabanids">Résultats</span>
                        </div>
                    </div>
                </div>
                <div class="hero-actions-mascabanids">
                    <a href="/listings" class="btn-hero-mascabanids btn-secondary-mascabanids">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour aux annonces</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- FILTRES APPLIQUÉS -->
        <div class="filters-showcase-mascabanids">
            <div class="filters-container-mascabanids">
                <?php if (!empty($searchTerm) || !empty($filters['type']) || !empty($filters['prix_max']) || !empty($filters['surface_min']) || !empty($filters['ville'])): ?>
                    <div class="filters-applied-mascabanids">
                        <h3>Filtres appliqués :</h3>
                        <div class="applied-filters-mascabanids">
                            <?php if (!empty($searchTerm)): ?>
                                <span class="filter-tag-mascabanids">
                                    <i class="fas fa-search"></i>
                                    "<?= htmlspecialchars($searchTerm) ?>"
                                </span>
                            <?php endif; ?>

                            <?php if (!empty($filters['type'])): ?>
                                <span class="filter-tag-mascabanids">
                                    <i class="fas fa-home"></i>
                                    <?= htmlspecialchars($filters['type']) ?>
                                </span>
                            <?php endif; ?>

                            <?php if (!empty($filters['prix_max'])): ?>
                                <span class="filter-tag-mascabanids">
                                    <i class="fas fa-euro-sign"></i>
                                    Max <?= htmlspecialchars($filters['prix_max']) ?>€
                                </span>
                            <?php endif; ?>

                            <?php if (!empty($filters['surface_min'])): ?>
                                <span class="filter-tag-mascabanids">
                                    <i class="fas fa-ruler-combined"></i>
                                    Min <?= htmlspecialchars($filters['surface_min']) ?>m²
                                </span>
                            <?php endif; ?>

                            <?php if (!empty($filters['ville'])): ?>
                                <span class="filter-tag-mascabanids">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= htmlspecialchars($filters['ville']) ?>
                                </span>
                            <?php endif; ?>

                            <a href="/listings" class="btn-clear-all-mascabanids">
                                <i class="fas fa-times"></i>
                                Effacer tous les filtres
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Formulaire de recherche rapide -->
                <form method="GET" action="/listings/search" class="filters-form-mascabanids">
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
                            value="<?= htmlspecialchars($searchTerm) ?>">
                    </div>

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

                    <div class="filter-group-mascabanids">
                        <label for="type" class="filter-label-mascabanids">
                            <i class="fas fa-home"></i>
                            Type de bien
                        </label>
                        <select id="type" name="type" class="filter-select-mascabanids">
                            <option value="">Tous les types</option>
                            <option value="appartement" <?= ($filters['type'] ?? '') === 'appartement' ? 'selected' : '' ?>>
                                Appartement
                            </option>
                            <option value="maison" <?= ($filters['type'] ?? '') === 'maison' ? 'selected' : '' ?>>
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
                            value="<?= htmlspecialchars($filters['prix_max'] ?? '') ?>">
                    </div>

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

                    <div class="filter-group-mascabanids">
                        <label for="surface_max" class="filter-label-mascabanids">
                            <i class="fas fa-ruler-combined"></i>
                            Surface max. (m²)
                        </label>
                        <input type="number"
                            id="surface_max"
                            name="surface_max"
                            class="filter-input-mascabanids"
                            placeholder="Surface maximum"
                            min="1"
                            value="<?= htmlspecialchars($filters['surface_max'] ?? '') ?>">
                    </div>

                    <div class="filter-group-mascabanids">
                        <label for="prix_min" class="filter-label-mascabanids">
                            <i class="fas fa-euro-sign"></i>
                            Prix min. par ticket
                        </label>
                        <input type="number"
                            id="prix_min"
                            name="prix_min"
                            class="filter-input-mascabanids"
                            placeholder="Prix minimum"
                            min="5"
                            max="20"
                            value="<?= htmlspecialchars($filters['prix_min'] ?? '') ?>">
                    </div>

                    <div class="filter-actions-mascabanids">
                        <button type="submit" class="btn-filter-mascabanids btn-filter-primary-mascabanids">
                            <i class="fas fa-check"></i>
                            <span>Rechercher</span>
                        </button>
                        <a href="/listings" class="btn-filter-mascabanids btn-filter-outline-mascabanids">
                            <i class="fas fa-refresh"></i>
                            <span>Voir toutes les annonces</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- RÉSULTATS DE RECHERCHE -->
        <div class="listings-content-mascabanids">
            <?php if (empty($results)): ?>
                <!-- ÉTAT VIDE -->
                <div class="empty-state-mascabanids">
                    <div class="empty-icon-mascabanids">
                        <i class="fas fa-search"></i>
                    </div>
                    <h2 class="empty-title-mascabanids">Aucun résultat trouvé</h2>
                    <p class="empty-description-mascabanids">
                        Aucune annonce ne correspond à vos critères de recherche.
                        Essayez de modifier vos filtres ou de revenir à la liste complète.
                    </p>
                    <div class="empty-actions-mascabanids">
                        <a href="/listings" class="btn-action-mascabanids btn-primary-mascabanids">
                            <i class="fas fa-refresh"></i>
                            <span>Voir toutes les annonces</span>
                        </a>
                        <button onclick="document.querySelector('.filters-form-mascabanids').scrollIntoView()" class="btn-action-mascabanids btn-outline-mascabanids">
                            <i class="fas fa-filter"></i>
                            <span>Modifier les filtres</span>
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <!-- GRILLE DES RÉSULTATS -->
                <div class="listings-grid-mascabanids">
                    <?php foreach ($results as $listing): ?>
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
            <?php endif; ?>
        </div>
    </div>
</div>