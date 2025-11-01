<?php
/**
 * INTERFACE ADMINISTRATION DOCUMENTS - HOW I WIN MY HOME V1
 * 
 * Interface pour la gestion et validation des documents sensibles
 */

// Récupération des données depuis le contrôleur
$documents = $data['documents'] ?? [];
$stats = $data['stats'] ?? [];
$filters = $data['filters'] ?? [];
$pagination = $data['pagination'] ?? [];
?>

<div class="admin-page">
    <div class="admin-container">
        <!-- En-tête -->
        <div class="page-header">
            <div class="header-content">
                <div class="title-section">
                    <h1 class="page-title">
                        <i class="fas fa-file-shield"></i>
                        Gestion des documents
                    </h1>
                    <p class="page-description">
                        Inspection, consultation et validation des documents sensibles
                    </p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-outline" id="refresh-documents-btn">
                        <i class="fas fa-sync-alt"></i>
                        Actualiser
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?= $stats['pending'] ?? 0 ?></div>
                    <div class="stat-label">En attente</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon verified">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?= $stats['verified'] ?? 0 ?></div>
                    <div class="stat-label">Vérifiés</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon rejected">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?= $stats['rejected'] ?? 0 ?></div>
                    <div class="stat-label">Rejetés</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?= $stats['total'] ?? 0 ?></div>
                    <div class="stat-label">Total</div>
                </div>
            </div>
        </div>

        <!-- Recherche simple -->
        <div class="search-section">
            <form class="search-form" method="GET" action="/admin/documents">
                <div class="search-row">
                    <div class="search-group">
                        <input type="text" name="search" id="search" placeholder="Rechercher par nom d'utilisateur..." 
                               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" class="form-input">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Liste des documents -->
        <div class="documents-section">
            <div class="documents-header">
                <h3>Queue de traitement des documents</h3>
                <div class="documents-count">
                    <?= count($documents) ?> document(s) en attente
                </div>
            </div>

            <?php if (empty($documents)): ?>
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    <h4>Aucun document en attente</h4>
                    <p>Tous les documents ont été traités !</p>
                </div>
            <?php else: ?>
                <div class="documents-queue">
                    <?php foreach ($documents as $document): ?>
                        <div class="document-item" data-document-id="<?= $document['id'] ?>">
                            <div class="document-info">
                                <div class="document-header">
                                    <div class="document-type">
                                        <i class="fas fa-file-alt"></i>
                                        <span><?= ucfirst(str_replace('_', ' ', $document['type'] ?? 'Document')) ?></span>
                                    </div>
                                    <div class="document-status status-<?= $document['status'] ?? 'pending' ?>">
                                        <?= ucfirst($document['status'] ?? 'En attente') ?>
                                    </div>
                                </div>
                                <div class="document-details">
                                    <div class="detail-item">
                                        <span class="label">Utilisateur:</span>
                                        <span class="value"><?= htmlspecialchars($document['user_name'] ?? 'Utilisateur #' . $document['user_id']) ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Date d'upload:</span>
                                        <span class="value"><?= date('d/m/Y H:i', strtotime($document['created_at'] ?? 'now')) ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Taille:</span>
                                        <span class="value"><?= number_format(($document['file_size'] ?? 0) / 1024, 1) ?> KB</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Temps en attente:</span>
                                        <span class="value time-waiting"><?= $document['time_waiting'] ?? 'N/A' ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="document-actions">
                                <button class="btn btn-primary btn-sm" data-action="view" data-document-id="<?= $document['id'] ?>">
                                    <i class="fas fa-eye"></i>
                                    Consulter
                                </button>
                                <button class="btn btn-outline btn-sm" data-action="download" data-document-id="<?= $document['id'] ?>">
                                    <i class="fas fa-download"></i>
                                    Télécharger
                                </button>
                                <?php if (($document['status'] ?? 'pending') === 'uploaded'): ?>
                                    <button class="btn btn-success btn-sm" data-action="verify" data-document-id="<?= $document['id'] ?>">
                                        <i class="fas fa-check"></i>
                                        Valider
                                    </button>
                                    <button class="btn btn-danger btn-sm" data-action="reject" data-document-id="<?= $document['id'] ?>">
                                        <i class="fas fa-times"></i>
                                        Rejeter
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination supprimée pour test -->
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de consultation de document -->
<div id="documentModal" class="modal modal-hidden">
    <div class="modal-overlay"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-file-alt"></i>
                Consultation du document
            </h3>
            <button class="modal-close" data-action="close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="document-info">
                <div class="document-header">
                    <div class="document-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="document-details">
                        <h4 class="document-name">Document d'identité</h4>
                        <span class="document-id">#<span id="documentId">1</span></span>
                        <span class="document-status pending">
                            <i class="fas fa-clock"></i>
                            En attente
                        </span>
                    </div>
                </div>
                
                <div class="document-meta">
                    <div class="meta-item">
                        <label>Utilisateur</label>
                        <span id="documentUser">Jean Dupont</span>
                    </div>
                    <div class="meta-item">
                        <label>Date d'upload</label>
                        <span id="documentDate">09/09/2025</span>
                    </div>
                    <div class="meta-item">
                        <label>Taille du fichier</label>
                        <span id="documentSize">2.3 MB</span>
                    </div>
                    <div class="meta-item">
                        <label>Type de document</label>
                        <span id="documentType">Pièce d'identité</span>
                    </div>
                </div>
                
                <div class="document-preview">
                    <div class="preview-placeholder">
                        <i class="fas fa-file-pdf"></i>
                        <p>Aperçu du document</p>
                        <small>Cliquez sur télécharger pour voir le fichier complet</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <button class="btn btn-outline" data-action="download">
                <i class="fas fa-download"></i>
                Télécharger
            </button>
            <button class="btn btn-success" data-action="verify">
                <i class="fas fa-check"></i>
                Valider
            </button>
            <button class="btn btn-danger" data-action="reject">
                <i class="fas fa-times"></i>
                Rejeter
            </button>
            <button class="btn btn-secondary" data-action="close">
                Fermer
            </button>
        </div>
    </div>
</div>

<!-- Modal de rejet de document -->
<div id="rejectModal" class="modal modal-hidden">
    <div class="modal-overlay"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-exclamation-triangle"></i>
                Rejeter le document
            </h3>
            <button class="modal-close" data-action="close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <form id="rejectForm">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <div class="form-group">
                    <label for="rejectReason">Raison du rejet *</label>
                    <select id="rejectReason" name="reason" class="form-select" required>
                        <option value="">Choisir une raison...</option>
                        <option value="illegible">Document illisible</option>
                        <option value="incomplete">Document incomplet</option>
                        <option value="wrong_format">Format non conforme</option>
                        <option value="expired">Document expiré</option>
                        <option value="suspicious">Document suspect</option>
                        <option value="duplicate">Document en doublon</option>
                        <option value="wrong_type">Mauvais type de document</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="rejectMessage">Message personnalisé (optionnel)</label>
                    <textarea id="rejectMessage" name="message" class="form-textarea" rows="3" 
                              placeholder="Ajoutez des détails supplémentaires..."></textarea>
                </div>
            </form>
        </div>
        
        <div class="modal-footer">
            <button class="btn btn-outline" data-action="close">
                Annuler
            </button>
            <button class="btn btn-danger" data-action="confirm-reject">
                <i class="fas fa-times"></i>
                Confirmer le rejet
            </button>
        </div>
    </div>
</div>

