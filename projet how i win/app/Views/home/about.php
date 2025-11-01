<?php
/**
 * Vue de la page "À propos" - HOW I WIN MY HOME V1
 * 
 * Présente l'entreprise, sa mission et ses valeurs
 */
?>

<div class="about-page">
    <!-- En-tête de la page -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">À propos de How I Win My Home</h1>
            <p class="page-subtitle">Découvrez notre mission et notre vision</p>
        </div>
    </div>

    <!-- Section principale -->
    <div class="main-content">
        <div class="container">
            
            <!-- Notre histoire -->
            <section class="about-section">
                <div class="row">
                    <div class="col-md-6">
                        <h2>Notre Histoire</h2>
                        <p>Fondée en 2025, <strong>How I Win My Home</strong> est née d'une vision simple : démocratiser l'accès à la propriété immobilière en France.</p>
                        <p>Face aux défis du marché immobilier traditionnel, nous avons créé une plateforme innovante qui transforme le processus d'achat immobilier en une expérience accessible, transparente et équitable.</p>
                    </div>
                    <div class="col-md-6">
                        <div class="about-image">
                            <img src="/assets/images/about-hero.jpg" alt="Équipe How I Win My Home" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Notre mission -->
            <section class="mission-section">
                <div class="text-center">
                    <h2>Notre Mission</h2>
                    <div class="mission-content">
                        <p class="mission-text">
                            <strong>Rendre la propriété immobilière accessible à tous</strong> en créant des opportunités équitables 
                            et en utilisant la technologie pour simplifier le processus d'achat.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Nos valeurs -->
            <section class="values-section">
                <h2 class="text-center">Nos Valeurs</h2>
                <div class="row">
                    <div class="col-md-4">
                        <div class="value-card">
                            <div class="value-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h3>Transparence</h3>
                            <p>Nous croyons en la transparence totale dans tous nos processus, de la création des annonces à la sélection des gagnants.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="value-card">
                            <div class="value-icon">
                                <i class="fas fa-balance-scale"></i>
                            </div>
                            <h3>Équité</h3>
                            <p>Chaque participant a une chance égale de gagner, peu importe son profil ou ses ressources.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="value-card">
                            <div class="value-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3>Communauté</h3>
                            <p>Nous construisons une communauté de futurs propriétaires qui se soutiennent mutuellement.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Notre équipe -->
            <section class="team-section">
                <h2 class="text-center">Notre Équipe</h2>
                <div class="row">
                    <div class="col-md-4">
                        <div class="team-member">
                            <div class="member-photo">
                                <img src="/assets/images/team-ceo.jpg" alt="CEO" class="img-fluid rounded-circle">
                            </div>
                            <h3>Marie Dubois</h3>
                            <p class="member-role">CEO & Fondatrice</p>
                            <p class="member-description">15 ans d'expérience dans l'immobilier et la technologie</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="team-member">
                            <div class="member-photo">
                                <img src="/assets/images/team-cto.jpg" alt="CTO" class="img-fluid rounded-circle">
                            </div>
                            <h3>Thomas Martin</h3>
                            <p class="member-role">CTO</p>
                            <p class="member-description">Expert en développement web et sécurité informatique</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="team-member">
                            <div class="member-photo">
                                <img src="/assets/images/team-legal.jpg" alt="Legal" class="img-fluid rounded-circle">
                            </div>
                            <h3>Sophie Bernard</h3>
                            <p class="member-role">Directrice Juridique</p>
                            <p class="member-description">Spécialiste en droit immobilier et réglementation</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Statistiques -->
            <section class="stats-section">
                <h2 class="text-center">Nos Chiffres</h2>
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-number">500+</div>
                            <div class="stat-label">Propriétaires Satisfaits</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-number">50+</div>
                            <div class="stat-label">Biens Immobiliers</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-number">10,000+</div>
                            <div class="stat-label">Participants</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-number">98%</div>
                            <div class="stat-label">Taux de Satisfaction</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA -->
            <section class="cta-section text-center">
                <h2>Prêt à réaliser votre rêve immobilier ?</h2>
                <p>Rejoignez notre communauté et participez à nos concours immobiliers !</p>
                <div class="cta-buttons">
                    <a href="/listings" class="btn btn-primary btn-lg">Voir les annonces</a>
                    <a href="/register" class="btn btn-outline-primary btn-lg">S'inscrire</a>
                </div>
            </section>

        </div>
    </div>
</div>
