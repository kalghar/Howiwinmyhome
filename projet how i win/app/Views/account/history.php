<?php
/**
 * Vue de l'historique des transactions
 */
?>

<div class="page-header">
    <div class="container">
        <div class="page-title">
            <h1>
                <i class="fas fa-history"></i>
                Historique des transactions
            </h1>
            <p class="page-description">
                Consultez l'historique de vos dépôts et achats de tickets
            </p>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="container">
        <div class="history-section">
            <div class="history-card">
                <div class="history-header">
                    <h2 class="history-title">
                        <i class="fas fa-list"></i>
                        Mes transactions
                    </h2>
                    <div class="history-actions">
                        <a href="/account/deposit" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Nouveau dépôt
                        </a>
                        <a href="/dashboard" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i>
                            Retour au tableau de bord
                        </a>
                    </div>
                </div>
                
                <div class="transactions-list">
                    <div class="transaction-item">
                        <div class="transaction-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div class="transaction-details">
                            <h3 class="transaction-title">Dépôt de fonds</h3>
                            <p class="transaction-description">Ajout de 50€ à votre compte</p>
                            <div class="transaction-meta">
                                <span class="transaction-date">
                                    <i class="fas fa-calendar"></i>
                                    15/01/2024 à 14:30
                                </span>
                                <span class="transaction-status success">
                                    <i class="fas fa-check-circle"></i>
                                    Confirmé
                                </span>
                            </div>
                        </div>
                        <div class="transaction-amount positive">
                            +50,00€
                        </div>
                    </div>
                    
                    <div class="transaction-item">
                        <div class="transaction-icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="transaction-details">
                            <h3 class="transaction-title">Achat de ticket</h3>
                            <p class="transaction-description">Ticket pour l'annonce "Appartement T3 Paris"</p>
                            <div class="transaction-meta">
                                <span class="transaction-date">
                                    <i class="fas fa-calendar"></i>
                                    14/01/2024 à 16:45
                                </span>
                                <span class="transaction-status success">
                                    <i class="fas fa-check-circle"></i>
                                    Confirmé
                                </span>
                            </div>
                        </div>
                        <div class="transaction-amount negative">
                            -5,00€
                        </div>
                    </div>
                    
                    <div class="transaction-item">
                        <div class="transaction-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div class="transaction-details">
                            <h3 class="transaction-title">Dépôt de fonds</h3>
                            <p class="transaction-description">Ajout de 25€ à votre compte</p>
                            <div class="transaction-meta">
                                <span class="transaction-date">
                                    <i class="fas fa-calendar"></i>
                                    10/01/2024 à 09:15
                                </span>
                                <span class="transaction-status success">
                                    <i class="fas fa-check-circle"></i>
                                    Confirmé
                                </span>
                            </div>
                        </div>
                        <div class="transaction-amount positive">
                            +25,00€
                        </div>
                    </div>
                </div>
                
                <div class="history-footer">
                    <p class="history-info">
                        <i class="fas fa-info-circle"></i>
                        Les transactions sont mises à jour en temps réel
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
