<?php
/**
 * Vue pour la réinitialisation du mot de passe
 * HOW I WIN MY HOME V1
 */

// Vérifier que les données sont bien passées
if (!isset($data)) {
    $data = [];
}

$title = $data['title'] ?? 'Mot de passe oublié - How I Win My Home';
$page = $data['page'] ?? 'forgot-password';
$isLoggedIn = $data['isLoggedIn'] ?? false;
$userRole = $data['userRole'] ?? 'guest';
?>

<div class="forgot-password-container">
    <div class="forgot-password-form">
        <h1>Mot de passe oublié</h1>
        
        <div class="forgot-password-info">
            <p>Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
        </div>
        
        <!-- Affichage des messages -->
        <?php if (isset($data['message']) && $data['message']): ?>
            <div class="notice notice-success">
                <p><?= htmlspecialchars($data['message']) ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($data['error']) && $data['error']): ?>
            <div class="notice notice-error">
                <p><?= htmlspecialchars($data['error']) ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($data['errors']) && !empty($data['errors'])): ?>
            <div class="notice notice-error">
                <ul>
                    <?php foreach ($data['errors'] as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form id="forgot-password-form" method="POST" action="/forgot-password" class="auth-form">
            <!-- Protection CSRF -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            
            <div class="form-group">
                <label for="email" class="form-label">Adresse email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input" 
                    required
                    placeholder="Votre adresse email"
                    autocomplete="email"
                >
                <div class="form-error" id="email-error"></div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" tabindex="0">
                    Envoyer le lien de réinitialisation
                </button>
            </div>
        </form>
        
        <div class="forgot-password-links">
            <p>
                <a href="/auth/login" class="link">Retour à la connexion</a>
            </p>
            <p>
                <a href="/auth/register" class="link">Créer un compte</a>
            </p>
        </div>
        
        <!-- Message d'information pour la V1 -->
        <div class="forgot-password-notice">
            <div class="notice notice-info">
                <h3>Version 1.0 - Fonctionnalité en développement</h3>
                <p>La réinitialisation de mot de passe par email sera disponible dans une prochaine version.</p>
                <p>Pour le moment, contactez l'administrateur si vous avez oublié votre mot de passe.</p>
            </div>
        </div>
    </div>
</div>

<style>
.forgot-password-container {
    max-width: 500px;
    margin: 2rem auto;
    padding: 2rem;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.forgot-password-form h1 {
    text-align: center;
    color: #333;
    margin-bottom: 1.5rem;
}

.forgot-password-info {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1.5rem;
    border-left: 4px solid #007bff;
}

.forgot-password-info p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #333;
}

.form-input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.form-input:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

.form-error {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: none;
}

.form-actions {
    margin-bottom: 1.5rem;
}

.btn {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    font-weight: 500;
    text-decoration: none;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-primary {
    background-color: #007bff;
    color: white;
    width: 100%;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.forgot-password-links {
    text-align: center;
    margin-bottom: 2rem;
}

.forgot-password-links p {
    margin: 0.5rem 0;
}

.link {
    color: #007bff;
    text-decoration: none;
}

.link:hover {
    text-decoration: underline;
}

.forgot-password-notice {
    margin-top: 2rem;
}

.notice {
    padding: 1rem;
    border-radius: 4px;
    border-left: 4px solid;
}

.notice-info {
    background-color: #e7f3ff;
    border-left-color: #007bff;
    color: #004085;
}

.notice-success {
    background-color: #d4edda;
    border-left-color: #28a745;
    color: #155724;
}

.notice-error {
    background-color: #f8d7da;
    border-left-color: #dc3545;
    color: #721c24;
}

.notice ul {
    margin: 0.5rem 0 0 0;
    padding-left: 1.5rem;
}

.notice li {
    margin: 0.25rem 0;
}

.notice h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
    color: #004085;
}

.notice p {
    margin: 0.25rem 0;
    font-size: 0.9rem;
}
</style>
