<?php
/**
 * Vue de confirmation d'achat de tickets - How I Win My Home
 * 
 * Page de confirmation après achat réussi de tickets
 * 
 * @author How I Win My Home Team
 * @version 2.0.0
 * @since 2025-01-27
 */

// Récupérer les données
$ticket = $data['ticket'] ?? [];
$listing = $data['listing'] ?? [];
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Confirmation de succès -->
            <div class="text-center mb-4">
                <div class="success-icon mb-3">
                    <i class="fas fa-check-circle fa-5x text-success"></i>
            </div>
                <h1 class="h2 text-success mb-3">🎉 Achat Confirmé !</h1>
                <p class="text-muted">Votre ticket a été acheté avec succès</p>
        </div>
        
        <!-- Détails du ticket -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">🎫 Détails de votre ticket</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Numéro de ticket</h6>
                            <p class="h4 text-primary font-monospace"><?= htmlspecialchars($ticket['numero_ticket']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Prix payé</h6>
                            <p class="h4 text-success"><?= number_format($ticket['ticket_price'], 2, ',', ' ') ?> €</p>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6>Date d'achat</h6>
                            <p><?= date('d/m/Y à H:i', strtotime($ticket['date_achat'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Statut</h6>
                            <span class="badge bg-success">Actif</span>
                        </div>
                    </div>
                </div>
                        </div>
                        
            <!-- Informations du bien -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">🏠 Bien concerné</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <?php if (!empty($listing['image'])): ?>
                                <img src="<?= htmlspecialchars($listing['image']) ?>" 
                                     alt="<?= htmlspecialchars($listing['title']) ?>" 
                                     class="img-fluid rounded">
                            <?php else: ?>
                                <div class="image-placeholder">
                                    <i class="fas fa-home fa-2x text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <h5><?= htmlspecialchars($listing['title']) ?></h5>
                            <p class="text-muted"><?= htmlspecialchars($listing['short_description'] ?? $listing['description']) ?></p>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <strong>💰 Prix :</strong> <?= number_format($listing['price'], 0, ',', ' ') ?> €
                                </div>
                                <div class="col-sm-6">
                                    <strong>🏠 Type :</strong> <?= htmlspecialchars($listing['property_type']) ?>
                                </div>
                        </div>
                        
                            <div class="row mt-2">
                                <div class="col-sm-6">
                                    <strong>📏 Surface :</strong> <?= htmlspecialchars($listing['property_size']) ?> m²
                                </div>
                                <div class="col-sm-6">
                                    <strong>🏠 Pièces :</strong> <?= htmlspecialchars($listing['rooms']) ?> pièces
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Prochaines étapes -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">📋 Prochaines étapes</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item completed">
                            <div class="timeline-marker bg-success">
                                <i class="fas fa-check"></i>
                        </div>
                            <div class="timeline-content">
                                <h6>✅ Ticket acheté</h6>
                                <p class="text-muted mb-0">Votre ticket a été acheté avec succès</p>
                        </div>
                    </div>
                    
                        <div class="timeline-item active">
                            <div class="timeline-marker bg-primary">
                            <i class="fas fa-question-circle"></i>
                        </div>
                            <div class="timeline-content">
                                <h6>🎯 Passer le QCM</h6>
                                <p class="text-muted mb-2">Répondez aux questions sur l'immobilier</p>
                                <a href="/ticket/start-qcm/<?= $ticket['id'] ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-play"></i> Commencer le QCM
                                </a>
                        </div>
                    </div>
                    
                        <div class="timeline-item">
                            <div class="timeline-marker bg-secondary">
                                <i class="fas fa-pen"></i>
                        </div>
                            <div class="timeline-content">
                                <h6>📝 Écrire la lettre de motivation</h6>
                                <p class="text-muted mb-0">Rédigez votre lettre de motivation</p>
                        </div>
                    </div>
                    
                        <div class="timeline-item">
                            <div class="timeline-marker bg-secondary">
                            <i class="fas fa-trophy"></i>
                        </div>
                            <div class="timeline-content">
                                <h6>🏆 Résultats du concours</h6>
                                <p class="text-muted mb-0">Découvrez si vous avez gagné</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations importantes -->
            <div class="alert alert-info">
                <h6><i class="fas fa-info-circle"></i> Informations importantes</h6>
                <ul class="mb-0">
                    <li>Vous avez <strong>24 heures</strong> pour passer le QCM</li>
                    <li>Après le QCM, vous devrez écrire une lettre de motivation</li>
                    <li>Vous recevrez des rappels par email si vous n'avez pas terminé</li>
                    <li>Le concours se termine le <?= date('d/m/Y', strtotime($listing['end_date'])) ?></li>
                    </ul>
                </div>

            <!-- Actions -->
            <div class="text-center">
                <a href="/ticket/start-qcm/<?= $ticket['id'] ?>" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-play"></i> Commencer le QCM maintenant
                </a>
                <a href="/ticket/my-tickets" class="btn btn-outline-secondary">
                    <i class="fas fa-list"></i> Voir mes tickets
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
}

.timeline-item.completed .timeline-marker {
    background-color: #28a745 !important;
}

.timeline-item.active .timeline-marker {
    background-color: #007bff !important;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #dee2e6;
}

.timeline-item.completed .timeline-content {
    border-left-color: #28a745;
}

.timeline-item.active .timeline-content {
    border-left-color: #007bff;
    background: #e3f2fd;
}

.success-icon {
    animation: bounceIn 1s ease-out;
}

@keyframes bounceIn {
    0% { transform: scale(0.3); opacity: 0; }
    50% { transform: scale(1.05); }
    70% { transform: scale(0.9); }
    100% { transform: scale(1); opacity: 1; }
}
</style>