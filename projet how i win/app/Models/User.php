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

class User extends AbstractModel implements ModelInterface, AuthInterface {

    use AuthTrait;

    protected string $table = 'users';

    private string $firstName = '';
    private string $lastname = '';
    private string $email = '';
    private string $password = '';
    private string $role = 'user';

    public function create(): void {
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("
            INSERT INTO users (first_name, last_name, email, password, role, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([$this->firstName, $this->lastname, $this->email, $hashedPassword, $this->role]);
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstname(string $firstname): string
    {
        return $this->firstName = $firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getByEmail(string $email): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        return $stmt->fetch();
    }

    public function emailExists(string $email): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        return $stmt->fetchColumn() > 0;
    }

    public function updateProfile(int $id, array $data): bool {
        $allowedFields = ['email', 'role'];
        
        $updates = [];
        $values = [];

        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $updates[] = "$field = ?";
                $values[] = $value;
            }
        }

        if (empty($updates)) {
            return false;
        }
        
        $values[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }

    public function changePassword(int $id, string $newPassword): bool {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        
        return $stmt->execute([$hashedPassword, $id]);
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