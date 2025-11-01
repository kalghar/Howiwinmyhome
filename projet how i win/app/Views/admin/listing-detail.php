<?php
/**
 * VUE DÉTAILS ANNONCE - HOW I WIN MY HOME V1
 * 
 * Interface d'administration pour voir les détails d'une annonce
 */

// Récupération des données depuis le contrôleur
$listing = $data['listing'] ?? [];
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
                        <i class="fas fa-eye"></i>
                        Détails de l'annonce
                    </h1>
                    <p class="page-description">
                        Informations complètes sur l'annonce immobilière
                    </p>
                </div>
                
                <div class="header-actions">
                    <a href="/admin/listings" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Retour aux annonces
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Contenu de l'annonce -->
        <div class="content-section">
            <?php if (!empty($listing)): ?>
            <div class="listing-detail-card">
                <!-- En-tête de l'annonce -->
                <div class="listing-header">
                    <div class="listing-title">
                        <h2><?= htmlspecialchars($listing['title'] ?? 'Sans titre') ?></h2>
                        <div class="listing-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?= htmlspecialchars($listing['city'] ?? 'Non spécifié') ?>
                        </div>
                    </div>
                    <div class="listing-price">
                        <span class="price"><?= number_format($listing['price'] ?? 0, 0, ',', ' ') ?> €</span>
                        <div class="listing-status">
                            <span class="status-badge status-<?= $listing['status'] ?? 'pending' ?>">
                                <?= ucfirst($listing['status'] ?? 'En attente') ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Images de l'annonce -->
                <div class="listing-images">
                    <h3><i class="fas fa-images"></i> Photos du bien</h3>
                    <?php if (!empty($listing['images'])): ?>
                        <div class="images-grid">
                            <?php foreach ($listing['images'] as $image): ?>
                            <div class="image-item">
                                <img src="/<?= htmlspecialchars($image['file_path'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                                     alt="Photo du bien" 
                                     class="listing-image">
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
                    <h3><i class="fas fa-file-shield"></i> Documents de vérification</h3>
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
                    <h3>Informations du bien</h3>
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="label">Type de bien:</span>
                            <span class="value"><?= ucfirst($listing['property_type'] ?? 'Non spécifié') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Surface:</span>
                            <span class="value"><?= $listing['property_size'] ?? 'N/A' ?> m²</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Pièces:</span>
                            <span class="value"><?= $listing['rooms'] ?? 'N/A' ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Chambres:</span>
                            <span class="value"><?= $listing['bedrooms'] ?? 'N/A' ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Prix du ticket:</span>
                            <span class="value"><?= $listing['ticket_price'] ?? 'N/A' ?> €</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Tickets nécessaires:</span>
                            <span class="value"><?= number_format($listing['tickets_needed'] ?? 0) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Date de création:</span>
                            <span class="value"><?= date('d/m/Y H:i', strtotime($listing['created_at'] ?? '')) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Statut:</span>
                            <span class="value status-<?= $listing['status'] ?? 'pending' ?>">
                                <?= ucfirst($listing['status'] ?? 'En attente') ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="listing-description">
                    <h3>Description</h3>
                    <p><?= htmlspecialchars($listing['description'] ?? 'Aucune description') ?></p>
                </div>
                
                <!-- Actions d'administration -->
                <div class="listing-actions">
                    <?php if ($listing['status'] === 'pending'): ?>
                    <button class="btn btn-success js-approve-listing" data-listing-id="<?= htmlspecialchars($listing['id'] ?? '0', ENT_QUOTES, 'UTF-8') ?>">
                        <i class="fas fa-check"></i>
                        Approuver l'annonce
                    </button>
                    <button class="btn btn-danger js-reject-listing" data-listing-id="<?= htmlspecialchars($listing['id'] ?? '0', ENT_QUOTES, 'UTF-8') ?>">
                        <i class="fas fa-times"></i>
                        Rejeter l'annonce
                    </button>
                    <?php endif; ?>
                    <a href="/admin/listings" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Retour à la liste
                    </a>
                </div>
            </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Annonce non trouvée</h3>
                    <p>Cette annonce n'existe pas ou a été supprimée.</p>
                    <a href="/admin/listings" class="btn btn-primary">Retour aux annonces</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Scripts de gestion des détails d'annonce -->
<script src="/assets/js/admin-listing-detail.js"></script>
