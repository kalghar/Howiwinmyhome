<?php

/**
 * VUE DE DÉTAIL D'ANNONCE - HOW I WIN MY HOME V1
 * 
 * Affichage détaillé d'une annonce immobilière
 */

// Récupération des données depuis le contrôleur
$listing = $data['listing'] ?? [];
$seller = $data['seller'] ?? [];
$ticketStats = $data['ticketStats'] ?? [];
$canBuyTickets = $data['canBuyTickets'] ?? false;
$userTickets = $data['userTickets'] ?? [];
$relatedListings = $data['relatedListings'] ?? [];
$isLoggedIn = $data['isLoggedIn'] ?? false;

// Fonction de traduction des types de biens
function translatePropertyType($type)
{
    $translations = [
        'apartment' => 'Appartement',
        'house' => 'Maison',
        'villa' => 'Villa',
        'studio' => 'Studio',
        'loft' => 'Loft',
        'other' => 'Autre'
    ];

    return $translations[$type] ?? ucfirst($type);
}
?>

<div class="listing-detail-page">
    <div class="container">
        <!-- Layout unifié avec Flexbox -->
        <div class="listing-unified-layout">
            <!-- Section d'annonce (pleine largeur) -->
            <div class="announcement-section">
                <div class="breadcrumb">
                    <a href="/listings">Annonces</a>
                    <span class="separator">></span>
                    <span class="current"><?= htmlspecialchars($listing['title'] ?? 'Annonce') ?></span>
                </div>

                <h1 class="listing-title">
                    <?= htmlspecialchars($listing['title'] ?? 'Annonce') ?>
                </h1>

                <div class="listing-meta">
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?= htmlspecialchars($listing['city'] ?? 'Ville non précisée') ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <span>Publié le <?= date('d/m/Y', strtotime($listing['created_at'] ?? 'now')) ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-user"></i>
                        <span>Vendeur : <?= htmlspecialchars($seller['email'] ?? 'Non disponible') ?></span>
                    </div>
                </div>
            </div>

            <!-- Carrousel central (pleine largeur) -->
            <div class="main-carousel-section">
                <?php if (!empty($listing['images']) && is_array($listing['images'])): ?>
                    <div class="image-carousel" data-listing-id="<?= $listing['id'] ?>">
                        <div class="carousel-container">
                            <?php foreach ($listing['images'] as $index => $image): ?>
                                <div class="carousel-slide <?= $index === 0 ? 'active' : '' ?>">
                                    <img src="/uploads/listings/<?= htmlspecialchars($image['filename']) ?>"
                                        alt="<?= htmlspecialchars($listing['title'] ?? 'Image') ?>"
                                        loading="lazy">
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if (count($listing['images']) > 1): ?>
                            <div class="carousel-controls">
                                <button class="carousel-btn prev" data-listing-id="<?= $listing['id'] ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="carousel-btn next" data-listing-id="<?= $listing['id'] ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>

                            <div class="carousel-indicators">
                                <?php foreach ($listing['images'] as $index => $image): ?>
                                    <button class="indicator <?= $index === 0 ? 'active' : '' ?>"
                                        data-listing-id="<?= $listing['id'] ?>"
                                        data-slide="<?= $index ?>"></button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="no-image">
                        <i class="fas fa-home"></i>
                        <p>Aucune image disponible</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Section informations (sans cards sur le côté) -->
            <div class="info-section">
                <!-- Détails du bien -->
                <div class="property-details">
                    <h2>Détails du bien</h2>
                    <div class="details-grid">
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="detail-content">
                                <label>Type de bien :</label>
                                <span><?= translatePropertyType($listing['property_type'] ?? 'Non précisé') ?></span>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-ruler-combined"></i>
                            </div>
                            <div class="detail-content">
                                <label>Surface :</label>
                                <span><?= htmlspecialchars($listing['property_size'] ?? '0') ?> m²</span>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-door-open"></i>
                            </div>
                            <div class="detail-content">
                                <label>Pièces :</label>
                                <span><?= htmlspecialchars($listing['rooms'] ?? '0') ?></span>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-bed"></i>
                            </div>
                            <div class="detail-content">
                                <label>Chambres :</label>
                                <span><?= htmlspecialchars($listing['bedrooms'] ?? '0') ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations du concours -->
                <div class="contest-details">
                    <h2>Informations du concours</h2>
                    <div class="contest-info-simple">
                        <div class="info-item">
                            <i class="fas fa-bullseye"></i>
                            <div>
                                <strong>Objectif minimum :</strong>
                                <span><?= number_format($listing['tickets_needed'] ?? 0, 0, ',', ' ') ?> tickets</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div>
                                <strong>Date de fin :</strong>
                                <span><?= date('d/m/Y', strtotime($listing['end_date'] ?? '')) ?></span>
                            </div>
                        </div>

                        <div class="info-item">
                            <i class="fas fa-euro-sign"></i>
                            <div>
                                <strong>Prix du ticket :</strong>
                                <span class="ticket-price"><?= number_format($listing['ticket_price'] ?? 0, 0, ',', ' ') ?> €</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <?php if (!empty($listing['description'])): ?>
                    <div class="description">
                        <h2>Description</h2>
                        <p><?= nl2br(htmlspecialchars($listing['description'])) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Actions -->
                <?php if ($canBuyTickets): ?>
                    <div class="actions">
                        <a href="/game/buy-ticket?listing_id=<?= $listing['id'] ?>" class="btn-large">
                            <i class="fas fa-ticket-alt"></i>
                            Acheter un ticket
                        </a>
                    </div>
                <?php else: ?>
                    <div class="info-message">
                        <i class="fas fa-info-circle"></i>
                        <span>Concours terminé ou tickets épuisés</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>



<!-- Annonces similaires -->
<?php if (!empty($relatedListings)): ?>
    <div class="related-listings">
        <h3>Annonces similaires</h3>
        <div class="listings-grid">
            <?php foreach ($relatedListings as $relatedListing): ?>
                <div class="listing-card">
                    <div class="listing-image">
                        <?php if (!empty($relatedListing['image'])): ?>
                            <img src="<?= htmlspecialchars($relatedListing['image']) ?>"
                                alt="<?= htmlspecialchars($relatedListing['title'] ?? 'Annonce') ?>"
                                loading="lazy">
                        <?php else: ?>
                            <div class="no-image">
                                <i class="fas fa-home"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="listing-content">
                        <h4><?= htmlspecialchars($relatedListing['title'] ?? 'Annonce') ?></h4>
                        <p class="listing-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?= htmlspecialchars($relatedListing['city'] ?? 'Ville non précisée') ?>
                        </p>
                        <div class="listing-price">
                            <?= number_format($relatedListing['ticket_price'] ?? 0, 0, ',', ' ') ?> € par ticket
                        </div>

                        <a href="/listings/view?id=<?= $relatedListing['id'] ?>" class="btn btn-outline btn-small">
                            Voir détails
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
</div>
</div>