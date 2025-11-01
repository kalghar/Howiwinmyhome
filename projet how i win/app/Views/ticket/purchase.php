<?php
/**
 * Vue d'achat de tickets - How I Win My Home
 * 
 * Interface pour l'achat de tickets de participation aux concours
 * 
 * @author How I Win My Home Team
 * @version 2.0.0
 * @since 2025-01-27
 */

// Récupérer les données
$listing = $data['listing'] ?? [];
$user = $data['user'] ?? [];
$ticketPrice = $data['ticket_price'] ?? 0;
$ticketsAvailable = $data['tickets_available'] ?? 0;
$maxTickets = $data['max_tickets'] ?? 1;
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- En-tête -->
            <div class="text-center mb-4">
                <h1 class="h2 mb-3">🎫 Achat de Tickets</h1>
                <p class="text-muted">Participez au concours pour gagner ce bien immobilier</p>
            </div>

            <!-- Informations de l'annonce -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📋 Informations du Bien</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <?php if (!empty($listing['image'])): ?>
                                <img src="<?= htmlspecialchars($listing['image']) ?>" 
                                     alt="<?= htmlspecialchars($listing['title']) ?>" 
                                     class="img-fluid rounded">
                            <?php else: ?>
                                <div class="image-placeholder-large">
                                    <i class="fas fa-home fa-3x text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <h4 class="card-title"><?= htmlspecialchars($listing['title']) ?></h4>
                            <p class="text-muted"><?= htmlspecialchars($listing['short_description'] ?? $listing['description']) ?></p>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <strong>💰 Prix du bien :</strong><br>
                                    <span class="h5 text-primary"><?= number_format($listing['price'], 0, ',', ' ') ?> €</span>
                                </div>
                                <div class="col-sm-6">
                                    <strong>🏠 Type :</strong><br>
                                    <span class="text-capitalize"><?= htmlspecialchars($listing['property_type']) ?></span>
                                </div>
                            </div>
                            
                            <div class="row mt-2">
                                <div class="col-sm-6">
                                    <strong>📏 Surface :</strong><br>
                                    <?= htmlspecialchars($listing['property_size']) ?> m²
                                </div>
                                <div class="col-sm-6">
                                    <strong>🏠 Pièces :</strong><br>
                                    <?= htmlspecialchars($listing['rooms']) ?> pièces
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire d'achat -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">🛒 Achat de Tickets</h5>
                </div>
                <div class="card-body">
                    <?php if ($ticketsAvailable <= 0): ?>
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                            <h5>Plus de tickets disponibles</h5>
                            <p class="mb-0">Tous les tickets pour ce concours ont été vendus.</p>
                        </div>
                    <?php else: ?>
                        <form id="purchaseForm" method="POST" action="/ticket/process-purchase">
                            <input type="hidden" name="csrf_token" value="<?= App::generateCSRFToken() ?>">
                            <input type="hidden" name="listing_id" value="<?= $listing['id'] ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity" class="form-label">
                                            <strong>Nombre de tickets</strong>
                                        </label>
                                        <select class="form-select" id="quantity" name="quantity" required>
                                            <?php for ($i = 1; $i <= $maxTickets; $i++): ?>
                                                <option value="<?= $i ?>"><?= $i ?> ticket<?= $i > 1 ? 's' : '' ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <div class="form-text">
                                            Tickets disponibles : <strong><?= $ticketsAvailable ?></strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <strong>Prix par ticket</strong>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">€</span>
                                            <input type="text" class="form-control" 
                                                   value="<?= number_format($ticketPrice, 2, ',', ' ') ?>" 
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Calcul du total -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h6 class="mb-1">Total à payer :</h6>
                                                <p class="mb-0 text-muted">Sélectionnez le nombre de tickets pour voir le total</p>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <span id="totalPrice" class="h4 text-primary">0,00 €</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informations de l'utilisateur -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6>👤 Informations de facturation</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Nom :</strong> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <a href="/listings/<?= $listing['id'] ?>" class="btn btn-outline-secondary me-3">
                                        <i class="fas fa-arrow-left"></i> Retour à l'annonce
                                    </a>
                                    <button type="submit" class="btn btn-success btn-lg" id="purchaseBtn">
                                        <i class="fas fa-credit-card"></i> Procéder au paiement
                                    </button>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Informations importantes -->
            <div class="card mt-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">⚠️ Informations importantes</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Une fois le paiement confirmé, vous recevrez un email de confirmation</li>
                        <li>Vous devrez ensuite remplir le QCM dans les 24h</li>
                        <li>Après le QCM, vous devrez écrire une lettre de motivation</li>
                        <li>Les tickets ne sont pas remboursables</li>
                        <li>Le concours se termine le <?= date('d/m/Y', strtotime($listing['end_date'])) ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantitySelect = document.getElementById('quantity');
    const totalPriceSpan = document.getElementById('totalPrice');
    const ticketPrice = <?= $ticketPrice ?>;
    
    function updateTotal() {
        const quantity = parseInt(quantitySelect.value);
        const total = quantity * ticketPrice;
        totalPriceSpan.textContent = total.toLocaleString('fr-FR', {
            style: 'currency',
            currency: 'EUR'
        });
    }
    
    quantitySelect.addEventListener('change', updateTotal);
    updateTotal(); // Calcul initial
    
    // Gestion du formulaire
    document.getElementById('purchaseForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const purchaseBtn = document.getElementById('purchaseBtn');
        purchaseBtn.disabled = true;
        purchaseBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
        
        const formData = new FormData(this);
        
        fetch('/ticket/process-purchase', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert('Erreur : ' + data.error);
                purchaseBtn.disabled = false;
                purchaseBtn.innerHTML = '<i class="fas fa-credit-card"></i> Procéder au paiement';
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
            purchaseBtn.disabled = false;
            purchaseBtn.innerHTML = '<i class="fas fa-credit-card"></i> Procéder au paiement';
        });
    });
});
</script>
