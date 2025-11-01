<?php
/**
 * Vue d'information pour le QCM - HOW I WIN MY HOME V1
 * 
 * Affiche des informations sur le QCM pour les utilisateurs non connectés
 */
?>

<div class="qcm-info-page">
    <!-- En-tête de la page -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Questionnaire à Choix Multiples (QCM)</h1>
            <p class="page-subtitle">Testez vos connaissances pour maximiser vos chances de gagner</p>
        </div>
    </div>

    <!-- Section principale -->
    <div class="main-content">
        <div class="container">
            
            <!-- Message d'information -->
            <div class="info-section text-center">
                <div class="info-icon">
                    <i class="fas fa-question-circle fa-3x text-primary"></i>
                </div>
                <h2>Connexion requise</h2>
                <p class="lead">
                    Pour passer le QCM et participer à nos concours immobiliers, 
                    vous devez être connecté à votre compte et avoir acheté des tickets.
                </p>
            </div>

            <!-- Qu'est-ce que le QCM ? -->
            <section class="qcm-explanation">
                <h3 class="text-center">Qu'est-ce que le QCM ?</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="explanation-card">
                            <div class="card-icon">
                                <i class="fas fa-brain"></i>
                            </div>
                            <h4>Test de connaissances</h4>
                            <p>Le QCM évalue vos connaissances en immobilier, finance et culture générale pour déterminer votre profil de participant.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="explanation-card">
                            <div class="card-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h4>Chronométré</h4>
                            <p>Vous disposez d'un temps limité pour répondre aux questions, ajoutant un défi supplémentaire au concours.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Avantages du QCM -->
            <section class="qcm-benefits">
                <h3 class="text-center">Pourquoi passer le QCM ?</h3>
                <div class="row">
                    <div class="col-md-4">
                        <div class="benefit-item">
                            <div class="benefit-number">1</div>
                            <h4>Maximiser vos chances</h4>
                            <p>Un bon score au QCM augmente significativement vos chances de gagner le bien immobilier.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="benefit-item">
                            <div class="benefit-number">2</div>
                            <h4>Évaluation équitable</h4>
                            <p>Le QCM permet une évaluation objective et équitable de tous les participants.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="benefit-item">
                            <div class="benefit-number">3</div>
                            <h4>Apprentissage</h4>
                            <p>Découvrez de nouvelles informations sur l'immobilier et la finance tout en participant.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Processus complet -->
            <section class="complete-process">
                <h3 class="text-center">Le processus complet</h3>
                <div class="process-timeline">
                    <div class="timeline-step">
                        <div class="step-marker">1</div>
                        <div class="step-content">
                            <h4>Inscription</h4>
                            <p>Créez votre compte gratuitement</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="step-marker">2</div>
                        <div class="step-content">
                            <h4>Achat de tickets</h4>
                            <p>Choisissez le bien et achetez vos tickets</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="step-marker">3</div>
                        <div class="step-content">
                            <h4>Passage du QCM</h4>
                            <p>Répondez aux questions dans le temps imparti</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="step-marker">4</div>
                        <div class="step-content">
                            <h4>Évaluation</h4>
                            <p>Le jury évalue votre participation et votre score</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="step-marker">5</div>
                        <div class="step-content">
                            <h4>Résultat</h4>
                            <p>Découvrez si vous avez gagné le bien immobilier</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Types de questions -->
            <section class="question-types">
                <h3 class="text-center">Types de questions</h3>
                <div class="row">
                    <div class="col-md-4">
                        <div class="question-type-card">
                            <div class="type-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <h4>Immobilier</h4>
                            <p>Questions sur le marché immobilier, les types de biens, la réglementation, etc.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="question-type-card">
                            <div class="type-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4>Finance</h4>
                            <p>Questions sur les prêts, l'investissement, la fiscalité immobilière, etc.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="question-type-card">
                            <div class="type-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <h4>Culture générale</h4>
                            <p>Questions variées pour évaluer votre culture générale et votre réflexion.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA -->
            <section class="cta-section text-center">
                <h3>Prêt à tester vos connaissances ?</h3>
                <p>Rejoignez-nous et participez à votre premier QCM immobilier !</p>
                <div class="cta-buttons">
                    <a href="/register" class="btn btn-primary btn-lg">Créer mon compte</a>
                    <a href="/auth/login" class="btn btn-outline-primary btn-lg">Me connecter</a>
                </div>
            </section>

        </div>
    </div>
</div>
