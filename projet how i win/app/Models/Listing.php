<?php

/**
 * Modèle Listing - Gestion des annonces immobilières pour How I Win My Home
 * 
 * Ce modèle gère toutes les opérations de base de données liées aux annonces immobilières :
 * - Création et gestion des annonces de biens immobiliers
 * - Récupération et filtrage des annonces (actives, en attente, par vendeur)
 * - Gestion des statuts des annonces (pending, active, rejected, completed)
 * - Calcul des tickets vendus et vérification de la capacité d'acceptation
 * - Recherche et pagination des annonces
 * - Opérations administratives (modération, statistiques)
 * 
 * Le modèle implémente des jointures avec la table users pour récupérer
 * les informations des vendeurs et des mécanismes de sécurité pour
 * la validation des données.
 * 
 * @author How I Win My Home Team
 * @version 2.0.0
 * @since 2025-08-12
 */

// Inclure la classe Database
require_once __DIR__ . '/../Config/Database.php';

class Listing
{

    // ========================================
    // PROPRIÉTÉS DE LA CLASSE
    // ========================================

    /**
     * Instance PDO pour la connexion à la base de données
     * 
     * Cette propriété stocke la connexion PDO obtenue via le singleton Database
     * pour effectuer toutes les opérations de base de données sur la table listings.
     * 
     * @var PDO
     */
    private $pdo;

    // ========================================
    // CONSTRUCTEUR
    // ========================================

    /**
     * Constructeur du modèle Listing
     * 
     * Initialise la connexion à la base de données en récupérant
     * l'instance PDO depuis le singleton Database.
     */
    public function __construct()
    {
        // Récupérer la connexion PDO depuis le singleton Database
        // Cette approche garantit une seule connexion partagée
        $this->pdo = Database::getInstance()->getConnection();
    }

    // ========================================
    // MÉTHODES DE CRÉATION ET GESTION
    // ========================================

    /**
     * Crée une nouvelle annonce immobilière dans la base de données
     * 
     * Cette méthode gère la création d'une annonce en :
     * - Insérant toutes les informations du bien immobilier
     * - Définissant automatiquement le statut 'pending' (en attente de validation)
     * - Enregistrant la date de création automatiquement
     * - Gérant l'image optionnelle du bien
     * 
     * @param array $data Tableau contenant toutes les données de l'annonce
     * @return bool true si la création réussit, false sinon
     */
    public function create(array $data): int|false
    {
        // ========================================
        // PRÉPARATION DE LA REQUÊTE D'INSERTION
        // ========================================

        // Préparer la requête SQL avec tous les champs nécessaires
        // Le statut est automatiquement défini à 'pending' pour la modération
        $stmt = $this->pdo->prepare("
            INSERT INTO listings (
                user_id, title, description, price, prix_total, ticket_price, prix_ticket,
                tickets_needed, property_type, property_size, rooms, bedrooms,
                address, city, postal_code, country, start_date, end_date, status, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
        ");

        // ========================================
        // EXÉCUTION AVEC LES PARAMÈTRES
        // ========================================

        // Conversion des valeurs françaises vers anglaises pour la base de données
        $propertyTypeMapping = [
            'appartement' => 'apartment',
            'maison' => 'house',
            'villa' => 'villa',
            'studio' => 'studio',
            'loft' => 'loft',
            'autre' => 'other'
        ];

        $propertyType = $propertyTypeMapping[$data['property_type']] ?? $data['property_type'];

        // Exécuter la requête avec les données de l'annonce (CORRIGÉ)
        $result = $stmt->execute([
            $data['user_id'],           // ID de l'utilisateur créateur
            $data['title'],             // Titre de l'annonce
            $data['description'],       // Description complète du bien
            $data['price'],             // Prix du bien immobilier
            $data['prix_total'],        // Prix total du bien (CORRIGÉ)
            $data['ticket_price'],      // Prix d'un ticket
            $data['prix_ticket'],       // Prix d'un ticket (alias) (CORRIGÉ)
            $data['tickets_needed'],    // Nombre de tickets nécessaires
            $propertyType,              // Type de bien (converti en anglais)
            $data['property_size'],     // Surface du bien
            $data['rooms'],             // Nombre de pièces
            $data['bedrooms'],          // Nombre de chambres
            $data['address'],           // Adresse du bien
            $data['city'],              // Ville
            $data['postal_code'],       // Code postal
            $data['country'] ?? 'France', // Pays (défaut: France)
            $data['start_date'],        // Date de début du concours
            $data['end_date']           // Date de fin du concours
        ]);

        // Si la création réussit, sauvegarder les images et documents
        if ($result) {
            $listingId = $this->pdo->lastInsertId();

            // Sauvegarder les images si elles existent
            if (isset($data['images']) && is_array($data['images'])) {
                $this->saveListingImages($listingId, $data['images']);
            }

            // Sauvegarder les documents confidentiels si ils existent
            if (isset($data['documents']) && is_array($data['documents'])) {
                $this->saveListingDocuments($listingId, $data['documents']);
            }

            return $listingId;
        }

        return false;
    }

    // ========================================
    // MÉTHODES DE RÉCUPÉRATION
    // ========================================

    /**
     * Récupère une annonce spécifique par son identifiant unique
     * 
     * Cette méthode récupère une annonce complète avec les informations
     * du vendeur via une jointure avec la table users.
     * 
     * @param int $id L'identifiant unique de l'annonce
     * @return array|false Les données de l'annonce avec les infos vendeur ou false si non trouvée
     */
    public function getById(int $id): array|false
    {
        // ========================================
        // REQUÊTE AVEC JOINTURE
        // ========================================

        // Préparer la requête avec jointure pour récupérer les infos du vendeur
        $stmt = $this->pdo->prepare("
            SELECT l.*, u.email as seller_email, u.role as seller_role
            FROM listings l
            JOIN users u ON l.user_id = u.id
            WHERE l.id = ?
        ");

        // Exécuter la requête et retourner le résultat
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Récupère toutes les annonces actives et disponibles
     * 
     * Cette méthode récupère les annonces qui :
     * - Ont le statut 'active' (validées par l'admin)
     * - N'ont pas encore atteint leur date de fin
     * - Sont triées par date de création (plus récentes en premier)
     * 
     * @param int|null $limit Nombre maximum d'annonces à retourner
     * @param int $offset Nombre d'annonces à ignorer (pour la pagination)
     * @return array Liste des annonces actives avec infos vendeur
     */
    public function getActive(?int $limit = null, int $offset = 0): array
    {
        // ========================================
        // CONSTRUCTION DE LA REQUÊTE DE BASE
        // ========================================

        // Requête SQL avec jointure et filtres pour les annonces actives
        $sql = "
            SELECT l.*, u.email as seller_email, u.role as seller_role
            FROM listings l
            JOIN users u ON l.user_id = u.id
            WHERE l.status = 'active' AND l.end_date >= CURDATE()
            ORDER BY l.created_at DESC
        ";

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

        // Retourner toutes les annonces actives trouvées
        return $stmt->fetchAll();
    }

    /**
     * Récupère toutes les annonces créées par un vendeur spécifique
     * 
     * Cette méthode permet à un vendeur de consulter toutes ses annonces,
     * quel que soit leur statut (pending, active, rejected, etc.).
     * 
     * @param int $userId L'identifiant du vendeur
     * @return array Liste des annonces du vendeur triées par date de création
     */
    public function getBySeller(int $userId): array
    {
        // Préparer et exécuter la requête pour récupérer les annonces du vendeur
        $stmt = $this->pdo->prepare("
            SELECT * FROM listings 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);

        // Retourner toutes les annonces du vendeur
        return $stmt->fetchAll();
    }

    // ========================================
    // MÉTHODES DE MODIFICATION
    // ========================================

    /**
     * Met à jour une annonce existante
     * 
     * Cette méthode permet de modifier les informations d'une annonce
     * en ne permettant que la modification de champs autorisés pour la sécurité.
     * 
     * @param int $id L'identifiant de l'annonce à modifier
     * @param array $data Les nouvelles données à appliquer
     * @return bool true si la mise à jour réussit, false sinon
     */
    public function update(int $id, array $data): bool
    {
        // ========================================
        // DÉFINITION DES CHAMPS AUTORISÉS
        // ========================================

        // Seuls certains champs peuvent être modifiés pour la sécurité
        $allowedFields = ['title', 'description', 'price', 'ticket_price', 'end_date', 'image', 'status'];

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
        $sql = "UPDATE listings SET " . implode(', ', $updates) . " WHERE id = ?";

        // Préparer et exécuter la requête
        $stmt = $this->pdo->prepare($sql);

        // Retourner le résultat de l'exécution
        return $stmt->execute($values);
    }

    /**
     * Change le statut d'une annonce
     * 
     * Cette méthode permet de modifier uniquement le statut d'une annonce,
     * utilisée principalement par les administrateurs pour la modération.
     * 
     * @param int $id L'identifiant de l'annonce
     * @param string $status Le nouveau statut (pending, active, rejected, completed, etc.)
     * @return bool true si la mise à jour réussit, false sinon
     */
    public function updateStatus(int $id, string $status): bool
    {
        // Préparer et exécuter la requête de mise à jour du statut
        $stmt = $this->pdo->prepare("UPDATE listings SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    // ========================================
    // MÉTHODES DE SUPPRESSION
    // ========================================

    /**
     * Supprime une annonce de la base de données
     * 
     * Cette méthode supprime définitivement une annonce
     * et toutes ses données associées (tickets, etc.).
     * 
     * @param int $id L'identifiant de l'annonce à supprimer
     * @return bool true si la suppression réussit, false sinon
     */
    public function delete(int $id): bool
    {
        // Préparer et exécuter la requête de suppression
        $stmt = $this->pdo->prepare("DELETE FROM listings WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // ========================================
    // MÉTHODES DE GESTION DES TICKETS
    // ========================================

    /**
     * Compte le nombre de tickets vendus pour une annonce
     * 
     * Cette méthode calcule combien de tickets ont été achetés
     * pour participer à un concours immobilier spécifique.
     * 
     * @param int $listingId L'identifiant de l'annonce
     * @return int Le nombre de tickets vendus
     */
    public function getTicketsSold(int $listingId): int
    {
        // Compter le nombre de tickets dans la table tickets
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM tickets WHERE listing_id = ?
        ");
        $stmt->execute([$listingId]);

        // Retourner le nombre de tickets vendus
        return $stmt->fetchColumn();
    }

    /**
     * Vérifie si une annonce peut encore accepter des tickets
     * 
     * Cette méthode détermine si un concours immobilier est encore ouvert
     * en vérifiant :
     * - Que le nombre de tickets vendus n'a pas atteint la limite
     * - Que la date de fin n'est pas dépassée
     * 
     * @param int $listingId L'identifiant de l'annonce
     * @return bool true si l'annonce peut accepter des tickets, false sinon
     */
    public function canAcceptTickets(int $listingId): bool
    {
        // ========================================
        // RÉCUPÉRATION DE L'ANNONCE
        // ========================================

        // Récupérer les informations de l'annonce
        $listing = $this->getById($listingId);

        // Si l'annonce n'existe pas, retourner false
        if (!$listing) return false;

        // ========================================
        // VÉRIFICATION DES CONDITIONS
        // ========================================

        // Compter le nombre de tickets déjà vendus
        $ticketsSold = $this->getTicketsSold($listingId);

        // Vérifier que :
        // 1. Il reste des tickets disponibles
        // 2. La date de fin n'est pas dépassée
        return $ticketsSold < $listing['tickets_needed'] && $listing['end_date'] >= date('Y-m-d');
    }

    // ========================================
    // MÉTHODES ADMINISTRATIVES
    // ========================================

    /**
     * Récupère les annonces en attente de validation (fonctionnalité administrative)
     * 
     * Cette méthode permet aux administrateurs de consulter toutes les annonces
     * nouvellement créées qui nécessitent une validation avant publication.
     * 
     * @param int|null $limit Nombre maximum d'annonces à retourner
     * @param int $offset Nombre d'annonces à ignorer (pour la pagination)
     * @return array Liste des annonces en attente avec infos vendeur
     */
    public function getPending(?int $limit = null, int $offset = 0): array
    {
        // ========================================
        // CONSTRUCTION DE LA REQUÊTE DE BASE
        // ========================================

        // Requête SQL avec jointure pour récupérer les infos du vendeur
        $sql = "
            SELECT l.*, u.email as seller_email
            FROM listings l
            JOIN users u ON l.user_id = u.id
            WHERE l.status = 'pending'
            ORDER BY l.created_at ASC
        ";

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

        // Retourner toutes les annonces en attente
        return $stmt->fetchAll();
    }

    /**
     * Compte le nombre total d'annonces dans le système
     * 
     * Cette méthode est utilisée pour les statistiques administratives
     * et peut filtrer par statut si nécessaire.
     * 
     * @param string|null $status Statut spécifique à compter (optionnel)
     * @return int Le nombre total d'annonces (ou par statut)
     */
    public function count(?string $status = null): int
    {
        // ========================================
        // CONSTRUCTION DE LA REQUÊTE
        // ========================================

        // Requête SQL de base pour compter toutes les annonces
        $sql = "SELECT COUNT(*) FROM listings";
        $params = [];

        // Si un statut spécifique est demandé, ajouter le filtre
        if ($status) {
            $sql .= " WHERE status = ?";
            $params[] = $status;
        }

        // ========================================
        // EXÉCUTION ET RETOUR
        // ========================================

        // Préparer et exécuter la requête
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        // Retourner le nombre d'annonces
        return $stmt->fetchColumn();
    }

    // ========================================
    // MÉTHODES DE RECHERCHE
    // ========================================

    /**
     * Recherche des annonces par mot-clé et filtres
     * 
     * Cette méthode permet aux utilisateurs de rechercher des biens immobiliers
     * en utilisant des mots-clés et des filtres avancés.
     * 
     * @param string $query Le terme de recherche
     * @param string $propertyType Type de propriété
     * @param float $minPrice Prix minimum
     * @param float $maxPrice Prix maximum
     * @param string $ville Ville
     * @param int $limit Nombre maximum de résultats (défaut: 20)
     * @return array Liste des annonces correspondant à la recherche
     */
    public function search(string $query, string $propertyType = '', float $maxPrice = 0, float $surfaceMin = 0, string $ville = '', int $limit = 20): array
    {
        // ========================================
        // PRÉPARATION DES CONDITIONS DE RECHERCHE
        // ========================================

        $conditions = ["l.status = 'active'"];
        $params = [];

        // Terme de recherche dans le titre ou description
        if (!empty($query)) {
            $searchTerm = "%$query%";
            $conditions[] = "(l.title LIKE ? OR l.description LIKE ?)";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Filtre par type de propriété
        if (!empty($propertyType)) {
            $conditions[] = "l.property_type = ?";
            $params[] = $propertyType;
        }

        // Filtre par prix maximum (prix du ticket)
        if ($maxPrice > 0) {
            $conditions[] = "l.ticket_price <= ?";
            $params[] = $maxPrice;
        }

        // Filtre par surface minimum
        if ($surfaceMin > 0) {
            $conditions[] = "l.property_size >= ?";
            $params[] = $surfaceMin;
        }

        // Filtre par ville
        if (!empty($ville)) {
            $conditions[] = "l.city LIKE ?";
            $params[] = "%$ville%";
        }

        // ========================================
        // EXÉCUTION DE LA RECHERCHE
        // ========================================

        // Construire la requête SQL
        $sql = "
            SELECT l.*, u.email as seller_email
            FROM listings l
            JOIN users u ON l.user_id = u.id
            WHERE " . implode(' AND ', $conditions) . "
            ORDER BY l.created_at DESC
            LIMIT ?
        ";

        // Ajouter la limite
        $params[] = $limit;

        // Exécuter la requête
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        // Retourner les résultats
        return $stmt->fetchAll();
    }

    /**
     * Récupère toutes les annonces
     * 
     * Cette méthode est utilisée pour les tableaux de bord administratifs
     * et peut filtrer par statut si nécessaire.
     * 
     * @param string|null $status Statut spécifique à filtrer (optionnel)
     * @param int|null $limit Nombre maximum d'annonces à retourner
     * @param int $offset Nombre d'annonces à ignorer (pour la pagination)
     * @return array Liste de toutes les annonces
     */
    public function getAll(?string $status = null, ?int $limit = null, int $offset = 0): array
    {
        // ========================================
        // CONSTRUCTION DE LA REQUÊTE
        // ========================================

        // Requête SQL de base avec jointure pour les informations du vendeur
        $sql = "
            SELECT l.*, u.email as seller_email, u.role as seller_role
            FROM listings l
            JOIN users u ON l.user_id = u.id
        ";

        $params = [];

        // Ajouter le filtre par statut si spécifié
        if ($status) {
            $sql .= " WHERE l.status = ?";
            $params[] = $status;
        }

        // Ajouter l'ordre de tri
        $sql .= " ORDER BY l.created_at DESC";

        // ========================================
        // GESTION DE LA PAGINATION
        // ========================================

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
        }

        // ========================================
        // EXÉCUTION ET RETOUR
        // ========================================

        // Préparer et exécuter la requête
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        // Retourner toutes les annonces
        return $stmt->fetchAll();
    }

    /**
     * Récupère les annonces récentes
     * 
     * Cette méthode est utilisée pour afficher les annonces récentes
     * sur la page d'accueil et dans les tableaux de bord.
     * 
     * @param int $limit Nombre maximum d'annonces à retourner
     * @return array Liste des annonces récentes
     */
    public function getRecent(int $limit = 6): array
    {
        // ========================================
        // REQUÊTE POUR LES ANNONCES RÉCENTES
        // ========================================

        // Préparer la requête pour récupérer les annonces récentes actives
        $stmt = $this->pdo->prepare("
            SELECT l.*, u.email as seller_email, u.role as seller_role
            FROM listings l
            JOIN users u ON l.user_id = u.id
            WHERE l.status = 'active' 
            AND l.end_date >= CURDATE()
            ORDER BY l.created_at DESC
            LIMIT ?
        ");

        // Exécuter la requête avec la limite
        $stmt->execute([$limit]);
        $listings = $stmt->fetchAll();

        // Enrichir chaque annonce avec toutes ses images
        foreach ($listings as &$listing) {
            $images = $this->getListingImages($listing['id']);
            $listing['images'] = $images;
            $listing['image'] = !empty($images) ? '/uploads/listings/' . $images[0]['filename'] : null;
        }

        // Retourner les annonces récentes enrichies
        return $listings;
    }

    /**
     * Récupère la liste des villes disponibles
     * 
     * @return array Liste des villes
     */
    public function getVilles(): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT DISTINCT city 
                FROM listings 
                WHERE status = 'active' 
                AND city IS NOT NULL 
                AND city != '' 
                ORDER BY city ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des villes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère les annonces similaires
     * 
     * @param int $listingId ID de l'annonce actuelle
     * @param string $propertyType Type de propriété de l'annonce
     * @param int $limit Nombre maximum d'annonces à retourner
     * @return array Liste des annonces similaires
     */
    public function getRelated(int $listingId, string $propertyType, int $limit = 3): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT l.*, u.email as seller_email, u.role as seller_role
                FROM listings l
                JOIN users u ON l.user_id = u.id
                WHERE l.status = 'active' 
                AND l.id != ? 
                AND l.property_type = ?
                AND l.end_date >= CURDATE()
                ORDER BY l.created_at DESC
                LIMIT ?
            ");
            $stmt->execute([$listingId, $propertyType, $limit]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des annonces similaires: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Sauvegarde les images d'une annonce dans la table listing_images
     * 
     * @param int $listingId ID de l'annonce
     * @param array $imagePaths Chemins des images à sauvegarder
     * @return bool True si la sauvegarde réussit
     */
    public function saveListingImages(int $listingId, array $imagePaths): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO listing_images (listing_id, filename, is_primary, sort_order) 
                VALUES (?, ?, ?, ?)
            ");

            foreach ($imagePaths as $index => $imagePath) {
                // Extraire le nom du fichier du chemin complet
                $filename = basename($imagePath);

                // La première image est marquée comme principale
                $isPrimary = ($index === 0) ? 1 : 0;

                // Ordre de tri basé sur l'index
                $sortOrder = $index;

                $stmt->execute([$listingId, $filename, $isPrimary, $sortOrder]);
            }

            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la sauvegarde des images: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Sauvegarde les documents confidentiels d'une annonce dans la table secure_documents
     * 
     * @param int $listingId ID de l'annonce
     * @param array $documentPaths Chemins des documents à sauvegarder
     * @return bool True si la sauvegarde réussit
     */
    public function saveListingDocuments(int $listingId, array $documentPaths): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO documents (user_id, listing_id, document_type, original_filename, file_path, file_size, mime_type, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'uploaded', NOW())
            ");

            foreach ($documentPaths as $documentType => $documentPath) {
                // Extraire le nom du fichier du chemin complet
                $filename = basename($documentPath);

                // Obtenir la taille du fichier
                $fileSize = file_exists($documentPath) ? filesize($documentPath) : 0;

                // Obtenir le type MIME
                $mimeType = mime_content_type($documentPath) ?: 'application/octet-stream';

                // Générer un hash du fichier
                $fileHash = hash_file('sha256', $documentPath);

                // Le nom sécurisé est le même que le nom original pour l'instant
                $secureFilename = $filename;

                $stmt->execute([
                    $_SESSION['user_id'] ?? null, // user_id
                    $listingId,
                    $documentType,
                    $filename, // original_filename
                    $documentPath,
                    $fileSize,
                    $mimeType
                ]);
            }

            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la sauvegarde des documents: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les images d'une annonce
     * 
     * @param int $listingId ID de l'annonce
     * @return array Liste des images de l'annonce
     */
    public function getListingImages(int $listingId): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM listing_images 
                WHERE listing_id = ? 
                ORDER BY sort_order ASC, created_at ASC
            ");
            $stmt->execute([$listingId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des images: " . $e->getMessage());
            return [];
        }
    }

    // ========================================
    // MÉTHODES ADMINISTRATIVES MANQUANTES
    // ========================================

    /**
     * Récupère le nombre total d'annonces
     * @return int Nombre total d'annonces
     */
    public function getTotalCount(): int
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM listings");
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Erreur lors du comptage total des annonces: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Récupère le nombre d'annonces actives
     * @return int Nombre d'annonces actives
     */
    public function getActiveCount(): int
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM listings WHERE status = 'active'");
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Erreur lors du comptage des annonces actives: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Récupère le nombre d'annonces en attente
     * @return int Nombre d'annonces en attente
     */
    public function getPendingCount(): int
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM listings WHERE status = 'pending'");
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Erreur lors du comptage des annonces en attente: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Récupère le nombre d'annonces rejetées
     * @return int Nombre d'annonces rejetées
     */
    public function getRejectedCount(): int
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM listings WHERE status = 'rejected'");
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Erreur lors du comptage des annonces rejetées: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Récupère le nombre d'annonces terminées
     * @return int Nombre d'annonces terminées
     */
    public function getCompletedCount(): int
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM listings WHERE status = 'completed'");
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Erreur lors du comptage des annonces terminées: " . $e->getMessage());
            return 0;
        }
    }
}
