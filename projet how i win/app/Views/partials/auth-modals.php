<?php

/**
 * PARTIAL MODALS D'AUTHENTIFICATION - HOW I WIN MY HOME V1
 * 
 * Ce partial contient les modals de connexion et d'inscription
 * utilisés sur toutes les pages de l'application
 */
?>

<!-- ========================================
     MODALES D'AUTHENTIFICATION
     ======================================== -->

<!-- Modal de connexion Mas Cabanids -->
<div id="login-modal" class="modal-overlay modal-overlay-mascabanids" data-modal-type="login">
    <div class="modal-backdrop-mascabanids"></div>
    <div class="modal-container modal-container-mascabanids">
        <div class="modal-content-mascabanids">
            <!-- Header avec design organique -->
            <div class="modal-header modal-header-mascabanids">
                <div class="header-decoration-mascabanids">
                    <div class="decoration-circle"></div>
                    <div class="decoration-circle"></div>
                    <div class="decoration-circle"></div>
                </div>
                <h3 class="modal-title modal-title-mascabanids">
                    <i class="fas fa-lock title-icon"></i>
                    Connexion
                </h3>
                <p class="modal-subtitle-mascabanids">
                    Accédez à votre espace personnel
                </p>
                <button type="button" class="modal-close modal-close-mascabanids" data-modal-close aria-label="Fermer la modale">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Body avec formulaire redesigné -->
            <div class="modal-body modal-body-mascabanids">
                <form id="login-form" class="auth-form-mascabanids" method="POST" action="/auth/process-login">
                    <!-- Protection CSRF -->
                    <input type="hidden" name="csrf_token" value="test_csrf_token">
                    <input type="hidden" name="csrf_identifier" value="login_form">

                    <div class="form-group-mascabanids">
                        <label for="login-email" class="form-label-mascabanids">
                            <i class="fas fa-envelope label-icon"></i>
                            Email
                        </label>
                        <div class="input-wrapper-mascabanids">
                            <input type="email" id="login-email" name="email" class="form-input-mascabanids" required>
                            <div class="input-decoration-mascabanids"></div>
                        </div>
                    </div>

                    <div class="form-group-mascabanids">
                        <label for="login-password" class="form-label-mascabanids">
                            <i class="fas fa-lock label-icon"></i>
                            Mot de passe
                        </label>
                        <div class="input-wrapper-mascabanids">
                            <input type="password" id="login-password" name="password" class="form-input-mascabanids" required>
                            <div class="input-decoration-mascabanids"></div>
                        </div>
                    </div>

                    <div class="form-group-mascabanids">
                        <label class="checkbox-mascabanids">
                            <input type="checkbox" name="remember" id="login-remember">
                            <span class="checkbox-custom-mascabanids">
                                <span class="checkmark-mascabanids">✓</span>
                            </span>
                            <span class="checkbox-label-mascabanids">Se souvenir de moi</span>
                        </label>
                    </div>

                    <div class="form-actions-mascabanids">
                        <button type="submit" class="btn-auth-mascabanids btn-login-mascabanids" tabindex="0">
                            <i class="fas fa-sign-in-alt btn-icon-mascabanids"></i>
                            <span class="btn-text-mascabanids">Se connecter</span>
                            <div class="btn-shine-mascabanids"></div>
                        </button>
                    </div>

                    <div class="form-footer-mascabanids">
                        <a href="/forgot-password" class="forgot-password-mascabanids">
                            <i class="fas fa-key link-icon"></i>
                            Mot de passe oublié ?
                        </a>
                        <div class="switch-form-mascabanids">
                            <span class="switch-text">Pas encore de compte ?</span>
                            <button type="button" class="switch-button-mascabanids" data-auth-action="register">
                                <i class="fas fa-user-plus switch-icon"></i>
                                S'inscrire
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'inscription Mas Cabanids -->
<div id="register-modal" class="modal-overlay modal-overlay-mascabanids" data-modal-type="register">
    <div class="modal-backdrop-mascabanids"></div>
    <div class="modal-container modal-container-mascabanids">
        <div class="modal-content-mascabanids">
            <!-- Header avec design organique -->
            <div class="modal-header modal-header-mascabanids">
                <div class="header-decoration-mascabanids">
                    <div class="decoration-circle"></div>
                    <div class="decoration-circle"></div>
                    <div class="decoration-circle"></div>
                </div>
                <h3 class="modal-title modal-title-mascabanids">
                    <i class="fas fa-user-plus title-icon"></i>
                    Inscription
                </h3>
                <p class="modal-subtitle-mascabanids">
                    Rejoignez notre communauté
                </p>
                <button type="button" class="modal-close modal-close-mascabanids" data-modal-close aria-label="Fermer la modale">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Body avec formulaire redesigné -->
            <div class="modal-body modal-body-mascabanids">
                <form id="register-form" class="auth-form-mascabanids" method="POST" action="/auth/process-register">
                    <!-- Protection CSRF -->
                    <input type="hidden" name="csrf_token" value="test_csrf_token">
                    <input type="hidden" name="csrf_identifier" value="register_form">

                    <!-- Grille 2 colonnes pour prénom/nom -->
                    <div class="form-row-mascabanids">
                        <div class="form-group-mascabanids">
                            <label for="register-firstname" class="form-label-mascabanids">
                                <i class="fas fa-user label-icon"></i>
                                Prénom
                            </label>
                            <div class="input-wrapper-mascabanids">
                                <input type="text" id="register-firstname" name="firstname" class="form-input-mascabanids" required>
                                <div class="input-decoration-mascabanids"></div>
                            </div>
                        </div>

                        <div class="form-group-mascabanids">
                            <label for="register-lastname" class="form-label-mascabanids">
                                <i class="fas fa-users label-icon"></i>
                                Nom
                            </label>
                            <div class="input-wrapper-mascabanids">
                                <input type="text" id="register-lastname" name="lastname" class="form-input-mascabanids" required>
                                <div class="input-decoration-mascabanids"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-mascabanids">
                        <label for="register-email" class="form-label-mascabanids">
                            <i class="fas fa-envelope label-icon"></i>
                            Email
                        </label>
                        <div class="input-wrapper-mascabanids">
                            <input type="email" id="register-email" name="email" class="form-input-mascabanids" required>
                            <div class="input-decoration-mascabanids"></div>
                        </div>
                    </div>

                    <div class="form-group-mascabanids">
                        <label for="register-password" class="form-label-mascabanids">
                            <i class="fas fa-lock label-icon"></i>
                            Mot de passe
                        </label>
                        <div class="input-wrapper-mascabanids">
                            <input type="password" id="register-password" name="password" class="form-input-mascabanids" required
                                minlength="8" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$"
                                title="Le mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre et un caractère spécial">
                            <div class="input-decoration-mascabanids"></div>
                        </div>
                        <div class="form-hint-mascabanids">
                            <i class="fas fa-lightbulb hint-icon"></i>
                            Au moins 8 caractères, une majuscule, un chiffre et un caractère spécial
                        </div>
                    </div>

                    <div class="form-group-mascabanids">
                        <label for="register-confirm-password" class="form-label-mascabanids">
                            <i class="fas fa-lock label-icon"></i>
                            Confirmer le mot de passe
                        </label>
                        <div class="input-wrapper-mascabanids">
                            <input type="password" id="register-confirm-password" name="password_confirm" class="form-input-mascabanids" required>
                            <div class="input-decoration-mascabanids"></div>
                        </div>
                    </div>

                    <div class="form-group-mascabanids">
                        <label class="checkbox-mascabanids">
                            <input type="checkbox" name="terms" id="register-terms" required>
                            <span class="checkbox-custom-mascabanids">
                                <span class="checkmark-mascabanids">✓</span>
                            </span>
                            <span class="checkbox-label-mascabanids">
                                J'accepte les <a href="/terms" target="_blank" class="terms-link-mascabanids">conditions d'utilisation</a>
                            </span>
                        </label>
                    </div>

                    <div class="form-actions-mascabanids">
                        <button type="submit" class="btn-auth-mascabanids btn-register-mascabanids" tabindex="0">
                            <i class="fas fa-user-plus btn-icon-mascabanids"></i>
                            <span class="btn-text-mascabanids">Créer mon compte</span>
                            <div class="btn-shine-mascabanids"></div>
                        </button>
                    </div>

                    <div class="form-footer-mascabanids">
                        <div class="switch-form-mascabanids">
                            <span class="switch-text">Déjà un compte ?</span>
                            <button type="button" class="switch-button-mascabanids" data-auth-action="login">
                                <i class="fas fa-sign-in-alt switch-icon"></i>
                                Se connecter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>