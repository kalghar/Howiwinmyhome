<?php
/**
 * VUE ANNONCES EN ATTENTE - HOW I WIN MY HOME V1
 * 
 * Interface d'administration pour valider les annonces en attente
 */

// Récupération des données depuis le contrôleur
$pendingListings = $data['pendingListings'] ?? [];
$isLoggedIn = $data['isLoggedIn'] ?? false;
$userRole = $data['userRole'] ?? null;
?>

<div class="admin-page">
    <div class="admin-container">
        <!-- En-tête d'administration -->
        <div class="page-header">
            <div class="header-content">
                <div class="title-section">
                    <h1 class="page-title">
                        <i class="fas fa-clock"></i>
                        Annonces en attente de validation
                    </h1>
                    <p class="page-description">
                        Validez ou rejetez les annonces immobilières soumises par les utilisateurs
                    </p>
                </div>
                
                <div class="header-actions">
                    <a href="/admin/listings" class="btn btn-outline">
                        <i class="fas fa-list"></i>
                        Toutes les annonces
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Liste des annonces en attente -->
        <div class="content-section">
            <?php if (!empty($pendingListings)): ?>
                <div class="listings-grid">
                    <?php foreach ($pendingListings as $listing): ?>
                    <div class="listing-card" data-listing-id="<?= htmlspecialchars($listing['id'] ?? '0', ENT_QUOTES, 'UTF-8') ?>">
                        <!-- En-tête de l'annonce -->
                        <div class="listing-header">
                            <div class="listing-title">
                                <h3><?= htmlspecialchars($listing['title'] ?? 'Sans titre') ?></h3>
                                <div class="listing-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= htmlspecialchars($listing['city'] ?? 'Non spécifié') ?>
                                </div>
                            </div>
                            <div class="listing-price">
                                <span class="price"><?= number_format($listing['price'] ?? 0, 0, ',', ' ') ?> €</span>
                            </div>
                        </div>
                        
                        <!-- Images de l'annonce -->
                        <div class="listing-images">
                            <h4><i class="fas fa-images"></i> Photos du bien</h4>
                            <?php if (!empty($listing['images'])): ?>
                                <div class="images-grid">
                                    <?php foreach ($listing['images'] as $image): ?>
                                    <div class="image-item">
                                        <img src="/<?= htmlspecialchars($image['file_path'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                                             alt="Photo du bien" 
                                             class="listing-image js-open-image-modal"
                                             data-image-src="/<?= htmlspecialchars($image['file_path'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                        <?php if ($image['is_primary']): ?>
                                            <span class="primary-badge">Principale</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="no-images">
                                    <i class="fas fa-image"></i>
                                    <p>Aucune photo disponible</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Documents de vérification -->
                        <div class="listing-documents">
                            <h4><i class="fas fa-file-shield"></i> Documents de vérification</h4>
                            <?php if (!empty($listing['documents'])): ?>
                                <div class="documents-list">
                                    <?php foreach ($listing['documents'] as $document): ?>
                                    <div class="document-item">
                                        <div class="document-info">
                                            <i class="fas fa-file-alt"></i>
                                            <span class="document-type">
                                                <?= ucfirst(str_replace('_', ' ', $document['document_type'] ?? 'Document')) ?>
                                            </span>
                                            <span class="document-status status-<?= $document['status'] ?? 'pending' ?>">
                                                <?= ucfirst($document['status'] ?? 'En attente') ?>
                                            </span>
                                        </div>
                                        <div class="document-actions">
                                                    <button class="btn btn-small btn-outline js-view-document" 
                                                            data-file-path="<?= htmlspecialchars($document['file_path'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        <i class="fas fa-eye"></i>
                                                        Voir
                                                    </button>
                                                    <button class="btn btn-small btn-primary js-download-document" 
                                                            data-file-path="<?= htmlspecialchars($document['file_path'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        <i class="fas fa-download"></i>
                                                        Télécharger
                                                    </button>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="no-documents">
                                    <i class="fas fa-file-alt"></i>
                                    <p>Aucun document de vérification</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Informations de l'annonce -->
                        <div class="listing-details">
                            <div class="detail-row">
                                <span class="label">Type de bien:</span>
                                <span class="value"><?= ucfirst($listing['property_type'] ?? 'Non spécifié') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Surface:</span>
                                <span class="value"><?= $listing['property_size'] ?? 'N/A' ?> m²</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Pièces:</span>
                                <span class="value"><?= $listing['rooms'] ?? 'N/A' ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Chambres:</span>
                                <span class="value"><?= $listing['bedrooms'] ?? 'N/A' ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Prix du ticket:</span>
                                <span class="value"><?= $listing['ticket_price'] ?? 'N/A' ?> €</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Tickets nécessaires:</span>
                                <span class="value"><?= number_format($listing['tickets_needed'] ?? 0) ?></span>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="listing-description">
                            <h4>Description</h4>
                            <p><?= htmlspecialchars($listing['description'] ?? 'Aucune description') ?></p>
                        </div>
                        
                        <!-- Actions d'administration -->
                        <div class="listing-actions">
                            <button class="btn btn-success js-approve-listing" data-listing-id="<?= htmlspecialchars($listing['id'] ?? '0', ENT_QUOTES, 'UTF-8') ?>">
                                <i class="fas fa-check"></i>
                                Approuver l'annonce
                            </button>
                            <button class="btn btn-danger js-reject-listing" data-listing-id="<?= htmlspecialchars($listing['id'] ?? '0', ENT_QUOTES, 'UTF-8') ?>">
                                <i class="fas fa-times"></i>
                                Rejeter l'annonce
                            </button>
                            <a href="/admin/listing/<?= $listing['id'] ?>" class="btn btn-outline">
                                <i class="fas fa-eye"></i>
                                Voir en détail
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    <h3>Aucune annonce en attente</h3>
                    <p>Toutes les annonces ont été traitées !</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal pour afficher les images -->
<div id="imageModal" class="modal modal-hidden">
    <div class="modal-overlay js-close-image-modal"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Photo du bien</h3>
            <button class="modal-close js-close-image-modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <img id="modalImage" src="" alt="Photo du bien" class="modal-image">
        </div>
    </div>
</div>

<!-- Scripts de gestion des annonces en attente -->
<script src="/assets/js/admin-pending-listings.js"></script>
