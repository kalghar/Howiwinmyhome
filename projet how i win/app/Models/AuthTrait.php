<?php

trait AuthTrait
{
    public function authenticate(string $email, string $password): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            error_log("Authentification réussie pour: " . $email);
            return $user;
        } else {
            error_log("Échec authentification pour: " . $email . " - Utilisateur trouvé: " . ($user ? 'OUI' : 'NON'));
        }

        return false;
    }
}