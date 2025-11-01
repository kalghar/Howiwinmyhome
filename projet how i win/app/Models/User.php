<?php
/**
 * Modèle User - Gestion des utilisateurs pour l'application How I Win My Home
 * 
 * Ce modèle gère toutes les opérations de base de données liées aux utilisateurs :
 * - Création et inscription de nouveaux utilisateurs
 * - Authentification et vérification des mots de passe
 * - Récupération et modification des informations utilisateur
 * - Gestion des profils et des mots de passe
 * - Opérations administratives (liste, comptage, suppression)
 * 
 * Le modèle implémente des mécanismes de sécurité comme le hashage des mots de passe
 * avec bcrypt et la validation des données avant insertion en base.
 * 
 * @author How I Win My Home Team
 * @version 2.0.0
 * @since 2025-08-12
 */

require_once __DIR__ . '/../Config/Database.php';

class User {
    
    // ========================================
    // PROPRIÉTÉS DE LA CLASSE
    // ========================================
    
    /**
     * Instance PDO pour la connexion à la base de données
     * 
     * Cette propriété stocke la connexion PDO obtenue via le singleton Database
     * pour effectuer toutes les opérations de base de données sur la table users.
     * 
     * @var PDO
     */
    private $pdo;
    
    // ========================================
    // CONSTRUCTEUR
    // ========================================
    
    /**
     * Constructeur du modèle User
     * 
     * Initialise la connexion à la base de données en récupérant
     * l'instance PDO depuis le singleton Database.
     */
    public function __construct() {
        // Récupérer la connexion PDO depuis le singleton Database
        // Cette approche garantit une seule connexion partagée
        $this->pdo = Database::getInstance()->getConnection();
    }
    
    // ========================================
    // MÉTHODES DE CRÉATION ET AUTHENTIFICATION
    // ========================================
    
    /**
     * Crée un nouvel utilisateur dans la base de données
     * 
     * Cette méthode gère l'inscription d'un nouvel utilisateur en :
     * - Hashant le mot de passe avec bcrypt pour la sécurité
     * - Insérant les données dans la table users
     * - Enregistrant la date de création automatiquement
     * 
     * @param string $firstname Le prénom de l'utilisateur
     * @param string $lastname Le nom de famille de l'utilisateur
     * @param string $email L'adresse email de l'utilisateur (doit être unique)
     * @param string $password Le mot de passe en clair (sera hashé)
     * @param string $role Le rôle de l'utilisateur (défaut: 'user')
     * @return bool true si la création réussit, false sinon
     */
    public function create(string $firstname, string $lastname, string $email, string $password, string $role = 'user'): int|false {
        // ========================================
        // HASHAGE SÉCURISÉ DU MOT DE PASSE
        // ========================================
        
        // Hasher le mot de passe avec bcrypt (algorithme sécurisé par défaut)
        // PASSWORD_DEFAULT utilise actuellement bcrypt avec un coût de 10
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // ========================================
        // PRÉPARATION ET EXÉCUTION DE LA REQUÊTE
        // ========================================
        
        // Préparer la requête SQL avec des paramètres pour éviter l'injection SQL
        $stmt = $this->pdo->prepare("
            INSERT INTO users (first_name, last_name, email, password, role, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        
        // Exécuter la requête avec les paramètres sécurisés
        if ($stmt->execute([$firstname, $lastname, $email, $hashedPassword, $role])) {
            // Retourner l'ID de l'utilisateur créé
            return $this->pdo->lastInsertId();
        }
        
        // Retourner false si l'insertion échoue
        return false;
    }
    
    /**
     * Authentifie un utilisateur avec son email et mot de passe
     * 
     * Cette méthode vérifie les identifiants de connexion en :
     * - Récupérant l'utilisateur par son email
     * - Vérifiant le mot de passe avec password_verify()
     * - Retournant les données utilisateur si l'authentification réussit
     * 
     * @param string $email L'adresse email de l'utilisateur
     * @param string $password Le mot de passe en clair à vérifier
     * @return array|false Les données utilisateur si authentification réussie, false sinon
     */
    public function authenticate(string $email, string $password): array|false {
        // ========================================
        // RÉCUPÉRATION DE L'UTILISATEUR PAR EMAIL
        // ========================================
        
        // Préparer et exécuter la requête pour récupérer l'utilisateur
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        // Récupérer l'utilisateur depuis le résultat
        $user = $stmt->fetch();
        
        // ========================================
        // VÉRIFICATION DU MOT DE PASSE
        // ========================================
        
        // Si l'utilisateur existe et que le mot de passe correspond
        if ($user && password_verify($password, $user['password'])) {
            error_log("Authentification réussie pour: " . $email);
            // Retourner toutes les données de l'utilisateur
            return $user;
        } else {
            error_log("Échec authentification pour: " . $email . " - Utilisateur trouvé: " . ($user ? 'OUI' : 'NON'));
        }
        
        // Retourner false si l'authentification échoue
        return false;
    }
    
    // ========================================
    // MÉTHODES DE RÉCUPÉRATION
    // ========================================
    
    /**
     * Récupère un utilisateur par son identifiant unique
     * 
     * Cette méthode permet de récupérer les informations complètes
     * d'un utilisateur en utilisant son ID primaire.
     * 
     * @param int $id L'identifiant unique de l'utilisateur
     * @return array|false Les données utilisateur ou false si non trouvé
     */
    public function getById(int $id): array|false {
        // Préparer et exécuter la requête avec l'ID
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        
        // Retourner l'utilisateur trouvé ou false
        return $stmt->fetch();
    }
    
    /**
     * Récupère un utilisateur par son adresse email
     * 
     * Cette méthode permet de récupérer les informations d'un utilisateur
     * en utilisant son email (utile pour la vérification d'unicité).
     * 
     * @param string $email L'adresse email de l'utilisateur
     * @return array|false Les données utilisateur ou false si non trouvé
     */
    public function getByEmail(string $email): array|false {
        // Préparer et exécuter la requête avec l'email
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        // Retourner l'utilisateur trouvé ou false
        return $stmt->fetch();
    }
    
    /**
     * Vérifie si une adresse email existe déjà dans la base
     * 
     * Cette méthode est utilisée lors de l'inscription pour s'assurer
     * qu'un email n'est pas déjà utilisé par un autre utilisateur.
     * 
     * @param string $email L'adresse email à vérifier
     * @return bool true si l'email existe déjà, false sinon
     */
    public function emailExists(string $email): bool {
        // Compter le nombre d'utilisateurs avec cet email
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        // Retourner true si au moins un utilisateur a cet email
        return $stmt->fetchColumn() > 0;
    }
    
    // ========================================
    // MÉTHODES DE MODIFICATION
    // ========================================
    
    /**
     * Met à jour le profil d'un utilisateur
     * 
     * Cette méthode permet de modifier les informations d'un utilisateur
     * en ne permettant que la modification de champs autorisés pour la sécurité.
     * 
     * @param int $id L'identifiant de l'utilisateur à modifier
     * @param array $data Les nouvelles données à appliquer
     * @return bool true si la mise à jour réussit, false sinon
     */
    public function updateProfile(int $id, array $data): bool {
        // ========================================
        // DÉFINITION DES CHAMPS AUTORISÉS
        // ========================================
        
        // Seuls certains champs peuvent être modifiés pour la sécurité
        $allowedFields = ['email', 'role'];
        
        // Tableaux pour construire la requête SQL dynamiquement
        $updates = [];
        $values = [];
        
        // ========================================
        // VALIDATION ET PRÉPARATION DES DONNÉES
        // ========================================
        
        // Parcourir les données soumises et ne garder que les champs autorisés
        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                // Construire la partie SET de la requête SQL
                $updates[] = "$field = ?";
                // Ajouter la valeur dans le tableau des paramètres
                $values[] = $value;
            }
        }
        
        // ========================================
        // VÉRIFICATION ET EXÉCUTION
        // ========================================
        
        // Si aucun champ valide n'est fourni, retourner false
        if (empty($updates)) {
            return false;
        }
        
        // Ajouter l'ID à la fin des valeurs pour la clause WHERE
        $values[] = $id;
        
        // Construire la requête SQL dynamiquement
        $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
        
        // Préparer et exécuter la requête
        $stmt = $this->pdo->prepare($sql);
        
        // Retourner le résultat de l'exécution
        return $stmt->execute($values);
    }
    
    /**
     * Change le mot de passe d'un utilisateur
     * 
     * Cette méthode met à jour le mot de passe d'un utilisateur en :
     * - Hashant le nouveau mot de passe avec bcrypt
     * - Mettant à jour la base de données
     * 
     * @param int $id L'identifiant de l'utilisateur
     * @param string $newPassword Le nouveau mot de passe en clair
     * @return bool true si le changement réussit, false sinon
     */
    public function changePassword(int $id, string $newPassword): bool {
        // ========================================
        // HASHAGE DU NOUVEAU MOT DE PASSE
        // ========================================
        
        // Hasher le nouveau mot de passe avec bcrypt
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // ========================================
        // MISE À JOUR EN BASE DE DONNÉES
        // ========================================
        
        // Préparer et exécuter la requête de mise à jour
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        
        // Retourner le résultat de l'exécution
        return $stmt->execute([$hashedPassword, $id]);
    }
    
    // ========================================
    // MÉTHODES DE SUPPRESSION
    // ========================================
    
    /**
     * Supprime un utilisateur de la base de données
     * 
     * Cette méthode supprime définitivement un utilisateur
     * et toutes ses données associées.
     * 
     * @param int $id L'identifiant de l'utilisateur à supprimer
     * @return bool true si la suppression réussit, false sinon
     */
    public function delete(int $id): bool {
        // Préparer et exécuter la requête de suppression
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        
        // Retourner le résultat de l'exécution
        return $stmt->execute([$id]);
    }
    
    // ========================================
    // MÉTHODES ADMINISTRATIVES
    // ========================================
    
    /**
     * Récupère tous les utilisateurs (fonctionnalité administrative)
     * 
     * Cette méthode permet aux administrateurs de consulter
     * la liste complète des utilisateurs avec pagination optionnelle.
     * 
     * @param int|null $limit Nombre maximum d'utilisateurs à retourner
     * @param int $offset Nombre d'utilisateurs à ignorer (pour la pagination)
     * @return array Liste des utilisateurs triés par date de création
     */
    public function getAll(?int $limit = null, int $offset = 0): array {
        // ========================================
        // CONSTRUCTION DE LA REQUÊTE DE BASE
        // ========================================
        
        // Requête SQL de base avec tri par date de création (plus récent en premier)
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        
        // ========================================
        // GESTION DE LA PAGINATION
        // ========================================
        
        if ($limit) {
            // Si une limite est spécifiée, ajouter LIMIT et OFFSET
            $sql .= " LIMIT ? OFFSET ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$limit, $offset]);
        } else {
            // Sinon, exécuter la requête sans limite
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        }
        
        // Retourner tous les utilisateurs trouvés
        return $stmt->fetchAll();
    }
    
    /**
     * Compte le nombre total d'utilisateurs dans le système
     * 
     * Cette méthode est utilisée pour les statistiques administratives
     * et le tableau de bord d'administration.
     * 
     * @return int Le nombre total d'utilisateurs
     */
    public function count(): int {
        // Exécuter une requête COUNT et retourner le résultat
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    /**
     * Obtient le nombre total de participants actifs
     * 
     * @return int Nombre total de participants
     */
    public function getTotalParticipants(): int {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE status = 'active' AND role = 'user'");
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Erreur lors du comptage des participants: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Met à jour le statut d'un utilisateur
     * 
     * @param int $id ID de l'utilisateur
     * @param string $status Nouveau statut
     * @return bool True si la mise à jour réussit
     */
    public function updateStatus(int $id, string $status): bool {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
            return $stmt->execute([$status, $id]);
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour du statut utilisateur: " . $e->getMessage());
            return false;
        }
    }
    
    // ========================================
    // MÉTHODES ADMINISTRATIVES MANQUANTES
    // ========================================
    
    /**
     * Récupère le nombre total d'utilisateurs
     * @return int Nombre total d'utilisateurs
     */
    public function getTotalCount(): int {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users");
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Erreur lors du comptage total des utilisateurs: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Récupère le nombre d'utilisateurs actifs
     * @return int Nombre d'utilisateurs actifs
     */
    public function getActiveCount(): int {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE status = 'active'");
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Erreur lors du comptage des utilisateurs actifs: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Récupère le nombre d'utilisateurs inactifs
     * @return int Nombre d'utilisateurs inactifs
     */
    public function getInactiveCount(): int {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE status = 'inactive'");
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Erreur lors du comptage des utilisateurs inactifs: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Récupère le nombre d'utilisateurs suspendus
     * @return int Nombre d'utilisateurs suspendus
     */
    public function getSuspendedCount(): int {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE status = 'suspended'");
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Erreur lors du comptage des utilisateurs suspendus: " . $e->getMessage());
            return 0;
        }
    }
    
    // ========================================
    // GESTION DU SOLDE UTILISATEUR
    // ========================================
    
    /**
     * Ajoute un montant au solde de l'utilisateur
     * @param int $userId ID de l'utilisateur
     * @param float $amount Montant à ajouter
     * @return bool True si l'opération a réussi
     */
    public function addBalance(int $userId, float $amount): bool {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            $result = $stmt->execute([$amount, $userId]);
            
            if ($result) {
                error_log("Solde mis à jour - User ID: $userId, Montant ajouté: $amount");
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Erreur lors de l'ajout au solde: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Déduit un montant du solde de l'utilisateur
     * @param int $userId ID de l'utilisateur
     * @param float $amount Montant à déduire
     * @return bool True si l'opération a réussi
     */
    public function deductBalance(int $userId, float $amount): bool {
        try {
            // Vérifier que l'utilisateur a suffisamment de solde
            $user = $this->getById($userId);
            if (!$user || $user['balance'] < $amount) {
                return false;
            }
            
            $stmt = $this->pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
            $result = $stmt->execute([$amount, $userId]);
            
            if ($result) {
                error_log("Solde déduit - User ID: $userId, Montant déduit: $amount");
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Erreur lors de la déduction du solde: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère le solde de l'utilisateur
     * @param int $userId ID de l'utilisateur
     * @return float Solde de l'utilisateur
     */
    public function getBalance(int $userId): float {
        try {
            $stmt = $this->pdo->prepare("SELECT balance FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $result = $stmt->fetchColumn();
            return $result ? (float) $result : 0.0;
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération du solde: " . $e->getMessage());
            return 0.0;
        }
    }
    
} 