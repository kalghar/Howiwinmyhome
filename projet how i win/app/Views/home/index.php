<?php

/**
 * VUE D'ACCUEIL SIMPLIFIÉE - HOW I WIN MY HOME
 * 
 * Page d'accueil principale avec présentation du concept
 * Parfait pour un examen : complet mais facile à expliquer
 */

// Récupération des données depuis le contrôleur
$recentListings = $data['recentListings'] ?? [];
$stats = $data['stats'] ?? [];
$isLoggedIn = $data['isLoggedIn'] ?? false;
?>

<!-- Section Hero -->
<section class="hero-section">
    <div class="hero-container">
        <div class="hero-content">
            <h1 class="hero-title">
                <span class="highlight">Gagnez</span> votre futur chez-vous
            </h1>
            <p class="hero-subtitle">
                Participez à des concours immobiliers uniques et remportez votre maison ou appartement de rêve !
            </p>

            <div class="hero-actions">
                <?php if (!$isLoggedIn): ?>
                    <button type="button" class="btn btn-primary btn-large" data-modal="register">
                        Commencer maintenant
                    </button>
                    <a href="/how-it-works" class="btn btn-outline btn-large">
                        Comment ça marche ?
                    </a>
                <?php else: ?>
                    <a href="/listings" class="btn btn-primary btn-large">
                        Voir les annonces
                    </a>
                    <a href="/dashboard" class="btn btn-outline btn-large">
                        Mon tableau de bord
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-image">
                <!-- Image de fond ou élément visuel sans icône -->
            </div>
        </div>
    </div>
</section>

<!-- Section Statistiques -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-content">
                    <div class="stat-number"><?= number_format($stats['total_listings'] ?? 0) ?></div>
                    <div class="stat-label">Annonces disponibles</div>
                </div>
            </div>

            <div class="stat-item">
                <div class="stat-content">
                    <div class="stat-number"><?= number_format($stats['total_winners'] ?? 0) ?></div>
                    <div class="stat-label">Gagnants heureux</div>
                </div>
            </div>

            <div class="stat-item">
                <div class="stat-content">
                    <div class="stat-number">4.9/5</div>
                    <div class="stat-label">Note utilisateurs</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Comment ça marche -->
<section class="how-it-works-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Comment ça marche ?</h2>
            <p class="section-subtitle">Un processus simple en 4 étapes pour gagner votre bien immobilier</p>
        </div>

        <div class="steps-grid">
            <div class="step-item">
                <div class="step-number">1</div>
                <h3 class="step-title">Inscription gratuite</h3>
                <p class="step-description">Créez votre compte en quelques clics</p>
            </div>

            <div class="step-item">
                <div class="step-number">2</div>
                <h3 class="step-title">Achetez votre ticket</h3>
                <p class="step-description">Participez au concours en achetant un ticket</p>
            </div>

            <div class="step-item">
                <div class="step-number">3</div>
                <h3 class="step-title">Rédigez votre lettre</h3>
                <p class="step-description">Écrivez une lettre de motivation</p>
            </div>

            <div class="step-item">
                <div class="step-number">4</div>
                <h3 class="step-title">Gagnez !</h3>
                <p class="step-description">Notre jury sélectionne le gagnant</p>
            </div>
        </div>
    </div>
</section>

<!-- Section Annonces récentes -->
<?php if (!empty($recentListings)): ?>
    <section class="recent-listings-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Annonces récentes</h2>
                <p class="section-subtitle">Découvrez nos dernières offres immobilières</p>
            </div>

            <div class="listings-grid">
                <?php foreach (array_slice($recentListings, 0, 6) as $listing): ?>
                    <div class="listing-card">
                        <div class="listing-image">
                            <?php if (!empty($listing['images']) && count($listing['images']) > 0): ?>
                                <div class="home-image-carousel" data-listing-id="<?= $listing['id'] ?>">
                                    <div class="home-carousel-container">
                                        <?php foreach ($listing['images'] as $index => $image): ?>
                                            <div class="home-carousel-slide <?= $index === 0 ? 'active' : '' ?>">
                                                <img src="/uploads/listings/<?= htmlspecialchars($image['filename']) ?>"
                                                    alt="<?= htmlspecialchars($listing['title'] ?? 'Annonce') ?>">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <?php if (count($listing['images']) > 1): ?>
                                        <div class="home-carousel-controls">
                                            <button class="home-carousel-btn prev" data-listing-id="<?= $listing['id'] ?>">
                                                <i class="fas fa-chevron-left"></i>
                                            </button>
                                            <button class="home-carousel-btn next" data-listing-id="<?= $listing['id'] ?>">
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                        </div>

                                        <div class="home-carousel-indicators">
                                            <?php foreach ($listing['images'] as $index => $image): ?>
                                                <button class="indicator <?= $index === 0 ? 'active' : '' ?>"
                                                    data-listing-id="<?= $listing['id'] ?>"
                                                    data-slide="<?= $index ?>"></button>
                                            <?php endforeach; ?>
                                        </div>

                                        <div class="image-counter">
                                            <span class="current">1</span> / <span class="total"><?= count($listing['images']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="listing-placeholder">
                                    <!-- Image placeholder -->
                                </div>
                            <?php endif; ?>

                            <div class="listing-price">
                                <span class="ticket-price"><?= number_format($listing['ticket_price'], 0) ?>€</span>
                                <span class="price-label">par ticket</span>
                            </div>
                        </div>

                        <div class="listing-content">
                            <h3 class="listing-title"><?= htmlspecialchars($listing['title'] ?? 'Annonce') ?></h3>
                            <p class="listing-location">
                                <?= htmlspecialchars($listing['city'] ?? 'Ville non précisée') ?>
                            </p>
                            <div class="listing-details">
                                <span class="detail-item">
                                    <?= number_format($listing['property_size'] ?? 0) ?> m²
                                </span>
                                <span class="detail-item">
                                    <?= $listing['rooms'] ?? 0 ?> pièces
                                </span>
                            </div>
                            <div class="listing-actions">
                                <?php if (!$isLoggedIn): ?>
                                    <button type="button" class="btn btn-primary btn-small" data-modal="register">
                                        Participer
                                    </button>
                                <?php else: ?>
                                    <a href="/listings/view?id=<?= $listing['id'] ?>" class="btn btn-primary btn-small">
                                        Voir l'annonce
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="section-actions">
                <a href="/listings" class="btn btn-outline btn-large">
                    Voir toutes les annonces
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Section Call-to-Action -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">Prêt à tenter votre chance ?</h2>
            <p class="cta-subtitle">
                Rejoignez notre communauté et participez à nos concours exclusifs.
                Votre rêve immobilier n'est qu'à quelques clics !
            </p>

            <div class="cta-actions">
                <?php if (!$isLoggedIn): ?>
                    <button type="button" class="btn btn-primary btn-large" data-modal="register">
                        Commencer l'aventure
                    </button>
                    <button type="button" class="btn btn-outline btn-large" data-modal="login">
                        Se connecter
                    </button>
                <?php else: ?>
                    <a href="/listings" class="btn btn-primary btn-large">
                        Découvrir les annonces
                    </a>
                    <a href="/dashboard" class="btn btn-outline btn-large">
                        Mon espace personnel
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>