<?php

/**
 * Contrôleur des annonces immobilières - HOW I WIN MY HOME V1
 * 
 * Gère l'affichage, la création et la gestion des annonces immobilières
 * Hérite de BaseController pour utiliser les méthodes communes
 */

// Includes supprimés - gérés par l'autoloader

class ListingController extends BaseController
{

    // ========================================
    // PROPRIÉTÉS POUR OPTIMISATION
    // ========================================

    /**
     * Instance du modèle Listing (réutilisable)
     * @var Listing
     */
    private $listingModel;

    /**
     * Instance du modèle User (réutilisable)
     * @var User
     */
    private $userModel;

    /**
     * Instance du modèle Game (réutilisable)
     * @var Game
     */
    private $gameModel;

    // ========================================
    // CONSTRUCTEUR
    // ========================================

    public function __construct()
    {
        parent::__construct();

        // Initialiser les modèles une seule fois
        $this->listingModel = new Listing();
        $this->userModel = new User();
        $this->gameModel = new Game();
    }

    // ========================================
    // MÉTHODES PUBLIQUES
    // ========================================

    /**
     * Affiche la liste des annonces immobilières
     * @return string HTML de la liste des annonces
     */
    public function index()
    {
        try {
            // Vérifier si des filtres sont appliqués
            $hasFilters = !empty($_GET['search']) ||
                !empty($_GET['type']) ||
                !empty($_GET['prix_max']) ||
                !empty($_GET['surface_min']) ||
                !empty($_GET['ville']);

            if ($hasFilters) {
                // Traiter les filtres directement sur cette page
                $searchTerm = trim($_GET['search'] ?? '');
                $propertyType = $_GET['type'] ?? '';
                $maxPrice = (float)($_GET['prix_max'] ?? 0);
                $surfaceMin = (float)($_GET['surface_min'] ?? 0);
                $ville = trim($_GET['ville'] ?? '');

                // Utiliser la méthode de recherche du modèle
                $listings = $this->listingModel->search($searchTerm, $propertyType, $maxPrice, $surfaceMin, $ville) ?: [];
            } else {
                // Récupérer les annonces actives (sans filtres)
                $listings = $this->listingModel->getActive() ?: [];
            }

            // Enrichir chaque annonce avec ses images
            foreach ($listings as &$listing) {
                $images = $this->listingModel->getListingImages($listing['id']);
                $listing['images'] = $images;
                // Utiliser la première image comme image principale
                $listing['image'] = !empty($images) ? '/uploads/listings/' . $images[0]['filename'] : null;
            }

            // Récupérer les statistiques des annonces
            $stats = $this->getListingStats();

            $data = [
                'title' => 'Annonces immobilières - How I Win My Home',
                'page' => 'listings',
                'listings' => $listings,
                'stats' => $stats,
                'categories' => $this->getPropertyCategories(),
                'additional_scripts' => ['/assets/js/listings-enhanced.js'],
                'isLoggedIn' => $this->isUserLoggedIn(),
                'userRole' => $this->getCurrentUserRole(),
                'currentUserId' => $_SESSION['user_id'] ?? null,
                'totalListings' => count($listings),
                'totalParticipants' => $this->getTotalParticipants(),
                'villes' => $this->getVilles(),
                'filters' => $this->getFilters()
            ];

            return $this->renderLayout('listings/index', $data);
        } catch (Exception $e) {
            error_log("Erreur lors de l'affichage des annonces: " . $e->getMessage());
            // Plus de messages flash - suppression complète
            return $this->renderLayout('errors/error', [
                'title' => 'Erreur - How I Win My Home',
                'message' => 'Impossible de charger les annonces'
            ]);
        }
    }

    /**
     * Affiche une annonce spécifique
     * @return string HTML de l'annonce
     */
    public function view()
    {
        try {
            // Récupérer l'ID de l'annonce depuis l'URL
            $listingId = (int)($_GET['id'] ?? 0);

            if (!$listingId) {
                // Plus de messages flash - suppression complète('error', 'Annonce non trouvée');
                return $this->redirect('/listings');
            }

            // Récupérer l'annonce
            $listing = $this->listingModel->getById($listingId);

            if (!$listing || $listing['status'] !== 'active') {
                // Plus de messages flash - suppression complète('error', 'Annonce non trouvée ou non disponible');
                return $this->redirect('/listings');
            }

            // Récupérer les informations du vendeur
            $seller = $this->userModel->getById($listing['user_id']);

            // Récupérer les images de l'annonce
            $images = $this->listingModel->getListingImages($listingId);
            $listing['images'] = $images;

            // Récupérer les statistiques de l'annonce
            $ticketStats = $this->gameModel->getStatsByListing($listingId) ?: [];

            // Vérifier si l'utilisateur connecté peut acheter des tickets
            $canBuyTickets = false;
            $userTickets = [];

            if ($this->isUserLoggedIn()) {
                $canBuyTickets = $this->canUserBuyTickets($listingId);
                $userTickets = $this->gameModel->getTicketsByUserAndListing($this->getCurrentUserId(), $listingId) ?: [];
            }

            $data = [
                'title' => ($listing['title'] ?? 'Annonce') . ' - How I Win My Home',
                'page' => 'listing-view',
                'page_css' => ['listings.css'],
                'page_js' => ['image-carousel.js'],
                'isLoggedIn' => $this->isUserLoggedIn(),
                'listing' => $listing,
                'seller' => $seller,
                'ticketStats' => $ticketStats,
                'canBuyTickets' => $canBuyTickets,
                'userTickets' => $userTickets,
                'relatedListings' => $this->getRelatedListings($listingId, $listing['property_type'] ?? '')
            ];

            return $this->renderLayout('listings/view', $data);
        } catch (Exception $e) {
            $this->handleException($e, '/listings', 'Erreur lors du chargement de l\'annonce');
            return '';
        }
    }

    /**
     * Affiche le formulaire de création d'annonce
     * @return string HTML du formulaire de création
     */
    public function create()
    {
        // Vérifier que l'utilisateur est connecté et a le rôle vendeur
        if (!$this->isUserLoggedIn()) {
            // Plus de messages flash - suppression complète
            return $this->redirect('/auth/login');
        }

        // Tous les utilisateurs connectés peuvent créer des annonces

        // Traiter la soumission du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->processListingCreation();
        }

        // CORRECTION : Générer le token CSRF
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Afficher le formulaire de création
        $data = [
            'title' => 'Créer une annonce - How I Win My Home',
            'page' => 'listing-create',
            'page_css' => ['listing-create.css'],
            'page_js' => ['listing-create.js'],
            'categories' => $this->getPropertyCategories(),
            'errors' => [],
            'old' => []
        ];

        return $this->renderLayout('listings/create', $data);
    }

    /**
     * Traite la création d'une nouvelle annonce (méthode publique pour la route store)
     * @return string Redirection ou message d'erreur
     */
    public function store()
    {
        // Vérifier que l'utilisateur est connecté
        if (!$this->isUserLoggedIn()) {
            return $this->redirect('/auth/login');
        }

        return $this->processListingCreation();
    }

    /**
     * Traite la création d'une nouvelle annonce
     * @return string Redirection ou message d'erreur
     */
    private function processListingCreation()
    {
        // Vérifier si c'est une requête AJAX dès le début
        $isAjax = $this->isAjaxRequest();

        try {
            // Vérifier que l'utilisateur est connecté
            if (!$this->isUserLoggedIn()) {
                return $this->redirect('/auth/login');
            }

            // Vérification CSRF (gérée par SecurityMiddleware)
            // Le SecurityMiddleware s'occupe déjà de la validation CSRF

            // Validation des données avec InputValidator
            $rules = [
                'title' => ['required', 'min:5', 'max:255'],
                'description' => ['required', 'min:20', 'max:2000'],
                'price' => ['required', 'numeric', 'min_value:50000', 'max_value:5000000'],
                'ticket_price' => ['required', 'in:5,10,15,20'],
                'tickets_needed' => ['required', 'numeric', 'min_value:10', 'max_value:200000'],
                'property_type' => ['required', 'in:apartment,house,villa,studio,loft,other'],
                'property_size' => ['required', 'numeric', 'min_value:10'],
                'rooms' => ['required', 'numeric', 'min_value:1'],
                'bedrooms' => ['required', 'numeric', 'min_value:0'],
                'address' => ['required'],
                'city' => ['required'],
                'postal_code' => ['required', 'regex:/^\d{5}$/'],
                'start_date' => ['required', 'date', 'after_or_equal:today'],
                'end_date' => ['required', 'date', 'after:start_date', 'before:+1 year']
            ];

            // Utiliser ValidationManager pour valider toutes les données
            $validationManager = new \App\Services\ValidationManager();
            $isValid = $validationManager->validate($_POST, $rules);

            if (!$isValid) {
                $validationErrors = $validationManager->getErrors();
                $this->returnJsonResponse(false, 'Erreurs de validation', $validationErrors, $_POST);
                return;
            }

            // Si validation réussie, utiliser les données POST
            $validatedData = $_POST;

            // Validation des fichiers
            $allowedImageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $allowedDocumentTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

            // Validation des images (CORRIGÉE)
            $validatedImages = [];
            $imageErrors = [];
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                foreach ($_FILES['images']['name'] as $key => $name) {
                    if (!empty($name)) {
                        // CORRECTION : Stocker le fichier complet, pas seulement tmp_name
                        $validatedImages[] = [
                            'name' => $_FILES['images']['name'][$key],
                            'type' => $_FILES['images']['type'][$key],
                            'tmp_name' => $_FILES['images']['tmp_name'][$key],
                            'error' => $_FILES['images']['error'][$key],
                            'size' => $_FILES['images']['size'][$key]
                        ];
                    }
                }
            }

            // Validation des documents de vérification individuels
            $documentErrors = [];
            $validatedDocuments = [];
            $requiredDocuments = ['identity_document', 'property_document', 'tax_document'];
            $optionalDocuments = ['energy_certificate'];

            // Vérifier les documents requis
            foreach ($requiredDocuments as $docType) {
                if (!isset($_FILES[$docType]) || $_FILES[$docType]['error'] !== UPLOAD_ERR_OK) {
                    $documentErrors[$docType] = "Document requis manquant: $docType";
                } else {
                    $validatedDocuments[$docType] = $_FILES[$docType];
                }
            }

            // Vérifier les documents optionnels
            foreach ($optionalDocuments as $docType) {
                if (isset($_FILES[$docType]) && $_FILES[$docType]['error'] === UPLOAD_ERR_OK) {
                    $validatedDocuments[$docType] = $_FILES[$docType];
                }
            }

            $allErrors = array_merge($imageErrors, $documentErrors);

            if (!empty($allErrors)) {
                // Si c'est une requête AJAX, retourner JSON
                if ($isAjax) {
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'message' => 'Erreurs de validation',
                        'errors' => $allErrors,
                        'old' => $validatedData
                    ]);
                    exit;
                }

                // CORRECTION : Message flash supprimé
                return $this->renderLayout('listings/create', [
                    'title' => 'Créer une annonce - How I Win My Home',
                    'page' => 'listing-create',
                    'categories' => $this->getPropertyCategories(),
                    'errors' => $allErrors,
                    'old' => $validatedData
                ]);
            }

            // Vérification du nombre d'images (3 minimum, 10 maximum)
            if (count($validatedImages) < 3) {
                $allErrors[] = 'Au moins 3 images sont requises';
                if ($isAjax) {
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'message' => 'Au moins 3 images sont requises',
                        'errors' => $allErrors,
                        'old' => $validatedData
                    ]);
                    exit;
                }
            } elseif (count($validatedImages) > 10) {
                $allErrors[] = 'Maximum 10 images autorisées';
                if ($isAjax) {
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'message' => 'Maximum 10 images autorisées',
                        'errors' => $allErrors,
                        'old' => $validatedData
                    ]);
                    exit;
                }

                return $this->renderLayout('listings/create', [
                    'title' => 'Créer une annonce - How I Win My Home',
                    'page' => 'listing-create',
                    'categories' => $this->getPropertyCategories(),
                    'errors' => $allErrors,
                    'old' => $validatedData
                ]);
            }

            // Traitement des images
            $imagePaths = $this->processListingImages($validatedImages);

            // Traitement des documents de vérification (REQUIS pour la validation)
            $documentPaths = $this->processVerificationDocuments($validatedDocuments);

            // Création de l'annonce avec les données validées (CORRIGÉ)
            $listingData = [
                'user_id' => $this->getCurrentUserId(),
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'price' => floatval($validatedData['price']),
                'prix_total' => floatval($validatedData['price']), // CORRIGÉ : Ajout du champ manquant
                'ticket_price' => floatval($validatedData['ticket_price']),
                'prix_ticket' => floatval($validatedData['ticket_price']), // CORRIGÉ : Ajout du champ manquant
                'tickets_needed' => intval($validatedData['tickets_needed']),
                'property_type' => $validatedData['property_type'],
                'property_size' => intval($validatedData['property_size']),
                'rooms' => intval($validatedData['rooms']),
                'bedrooms' => intval($validatedData['bedrooms']),
                'address' => $validatedData['address'],
                'city' => $validatedData['city'],
                'postal_code' => $validatedData['postal_code'],
                'country' => $validatedData['country'] ?? 'France', // CORRIGÉ : Ajout du champ manquant
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'status' => 'pending', // En attente de validation admin
                'images' => $imagePaths, // Ajouter les chemins des images
                'documents' => $documentPaths // Ajouter les chemins des documents
            ];

            $listingId = $this->listingModel->create($listingData);

            if ($listingId) {
                // Créer les enregistrements d'images dans la table listing_images
                if (!empty($imagePaths)) {
                    $this->createListingImages($listingId, $imagePaths);
                }

                // Créer les enregistrements de documents de vérification
                if (!empty($documentPaths)) {
                    $this->createVerificationDocuments($listingId, $documentPaths);
                }
                // Si c'est une requête AJAX, retourner JSON
                if ($isAjax) {
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'message' => 'Annonce envoyée pour validation ! Elle sera examinée par nos administrateurs avant publication.',
                        'redirect' => '/dashboard'
                    ]);
                    exit;
                }

                // CORRECTION : Redirection directe sans message flash (supprimé)
                return $this->redirect('/dashboard');
            } else {
                // Si c'est une requête AJAX, retourner JSON
                if ($isAjax) {
                    http_response_code(500);
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'message' => 'Erreur lors de la création de l\'annonce',
                        'errors' => ['general' => 'Erreur lors de la création de l\'annonce']
                    ]);
                    exit;
                }

                // CORRECTION : Message flash supprimé
                return $this->renderLayout('listings/create', [
                    'title' => 'Créer une annonce - How I Win My Home',
                    'page' => 'listing-create',
                    'categories' => $this->getPropertyCategories(),
                    'errors' => ['general' => 'Erreur lors de la création de l\'annonce'],
                    'old' => $_POST
                ]);
            }
        } catch (Exception $e) {
            $this->logError("Erreur lors de la création de l'annonce", [
                'user_id' => $this->getCurrentUserId(),
                'post_data' => $_POST,
                'files_count' => count($_FILES),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // Si c'est une requête AJAX, retourner JSON avec l'erreur détaillée (temporaire pour debug)
            if ($isAjax) {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Une erreur s\'est produite lors de la création de l\'annonce',
                    'errors' => ['general' => 'Une erreur s\'est produite']
                ]);
                exit;
            }

            // CORRECTION : Message flash supprimé
            return $this->renderLayout('listings/create', [
                'title' => 'Créer une annonce - How I Win My Home',
                'page' => 'listing-create',
                'categories' => $this->getPropertyCategories(),
                'errors' => ['general' => 'Une erreur s\'est produite'],
                'old' => $_POST
            ]);
        }
    }

    /**
     * Crée les enregistrements d'images pour une annonce
     * @param int $listingId ID de l'annonce
     * @param array $imagePaths Chemins des images
     * @return void
     */
    private function createListingImages($listingId, $imagePaths)
    {
        try {
            $db = Database::getInstance();
            $pdo = $db->getConnection();

            $stmt = $pdo->prepare("
                INSERT INTO listing_images (listing_id, filename, is_primary, sort_order) 
                VALUES (?, ?, ?, ?)
            ");

            foreach ($imagePaths as $index => $imagePath) {
                $filename = basename($imagePath);
                $isPrimary = ($index === 0) ? 1 : 0; // Première image = principale
                $sortOrder = $index + 1;

                $stmt->execute([$listingId, $filename, $isPrimary, $sortOrder]);
            }
        } catch (Exception $e) {
            error_log("Erreur lors de la création des images: " . $e->getMessage());
            // Ne pas faire échouer la création de l'annonce pour un problème d'images
        }
    }

    /**
     * Traite l'upload des documents de vérification
     * @param array $files Fichiers uploadés
     * @return array Chemins des documents ou tableau vide
     */
    private function processVerificationDocuments($files)
    {
        $documentPaths = [];
        // Documents requis pour la validation
        $requiredDocuments = ['identity_document', 'property_document', 'tax_document'];
        $optionalDocuments = ['energy_certificate'];

        try {
            // Vérifier les documents requis
            foreach ($requiredDocuments as $docType) {
                if (!isset($files[$docType]) || $files[$docType]['error'] !== UPLOAD_ERR_OK) {
                    // CORRECTION : Échouer si un document requis est manquant
                    $errorMessage = "Document requis manquant: $docType";
                    error_log($errorMessage);
                    throw new Exception($errorMessage);
                }

                $documentPath = $this->uploadVerificationDocument($files[$docType], $docType);
                if ($documentPath) {
                    $documentPaths[$docType] = $documentPath;
                } else {
                    // CORRECTION : Échouer si l'upload échoue
                    throw new Exception("Échec de l'upload du document: $docType");
                }
            }

            // Traiter les documents optionnels
            foreach ($optionalDocuments as $docType) {
                if (isset($files[$docType]) && $files[$docType]['error'] === UPLOAD_ERR_OK) {
                    $documentPath = $this->uploadVerificationDocument($files[$docType], $docType);
                    if ($documentPath) {
                        $documentPaths[$docType] = $documentPath;
                    }
                }
            }

            return $documentPaths;
        } catch (Exception $e) {
            error_log("Erreur lors du traitement des documents: " . $e->getMessage());
            throw new Exception('Erreur lors du traitement des documents de vérification: ' . $e->getMessage());
        }
    }

    /**
     * Upload un document de vérification
     * @param array $file Fichier uploadé
     * @param string $docType Type de document
     * @return string|null Chemin du document ou null
     */
    private function uploadVerificationDocument($file, $docType)
    {
        try {
            // Valider le fichier
            $validation = $this->validateDocument($file);
            if (!$validation['valid']) {
                throw new Exception($validation['error']);
            }

            // Générer un nom de fichier sécurisé
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $secureName = uniqid() . '_' . $docType . '.' . $extension;

            // Créer le chemin de destination
            $uploadDir = __DIR__ . '/../../public/uploads/documents/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $destination = $uploadDir . $secureName;

            // Déplacer le fichier
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                return 'uploads/documents/' . $secureName;
            }

            throw new Exception('Erreur lors de l\'upload du document');
        } catch (Exception $e) {
            error_log("Erreur lors de l'upload du document $docType: " . $e->getMessage());
            throw new Exception("Erreur lors de l'upload du document $docType");
        }
    }

    /**
     * Valide un document de vérification
     * @param array $file Fichier uploadé
     * @return array Résultat de validation
     */
    private function validateDocument($file)
    {
        // Vérifier les erreurs d'upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['valid' => false, 'error' => 'Erreur lors de l\'upload du fichier'];
        }

        // Vérifier la taille (max 10MB)
        if ($file['size'] > 10 * 1024 * 1024) {
            return ['valid' => false, 'error' => 'Le fichier est trop volumineux (max 10MB)'];
        }

        // Vérifier le type de fichier
        $allowedTypes = [
            'application/pdf',
            'image/jpeg',
            'image/jpg',
            'image/png',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        if (!in_array($file['type'], $allowedTypes)) {
            return ['valid' => false, 'error' => 'Format de fichier non autorisé (PDF, JPG, PNG, DOC, DOCX uniquement)'];
        }

        return ['valid' => true];
    }

    /**
     * Crée les enregistrements de documents de vérification
     * @param int $listingId ID de l'annonce
     * @param array $documentPaths Chemins des documents
     * @return void
     */
    private function createVerificationDocuments($listingId, $documentPaths)
    {
        try {
            $db = Database::getInstance();
            $pdo = $db->getConnection();

            $stmt = $pdo->prepare("
                INSERT INTO documents (
                    user_id, listing_id, document_type, original_filename, 
                    file_path, file_size, mime_type, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, 'uploaded', NOW())
            ");

            foreach ($documentPaths as $docType => $filePath) {
                $originalName = basename($filePath);
                $secureName = basename($filePath);
                $fullPath = __DIR__ . '/../../public/' . $filePath;

                if (file_exists($fullPath)) {
                    $fileSize = filesize($fullPath);
                    $mimeType = mime_content_type($fullPath);
                    $fileHash = hash_file('sha256', $fullPath);

                    $stmt->execute([
                        $this->getCurrentUserId(),
                        $listingId,
                        $docType,
                        $originalName,
                        $filePath,
                        $fileSize,
                        $mimeType
                    ]);
                }
            }
        } catch (Exception $e) {
            error_log("Erreur lors de la création des documents: " . $e->getMessage());
            // Ne pas faire échouer la création de l'annonce pour un problème de documents
        }
    }

    /**
     * Affiche le formulaire de modification d'annonce
     * @return string HTML du formulaire de modification
     */
    public function edit()
    {
        // Vérifier que l'utilisateur est connecté et a le rôle vendeur
        if (!$this->isUserLoggedIn()) {
            // Plus de messages flash - suppression complète('error', 'Vous devez être connecté pour modifier une annonce');
            return $this->redirect('/auth/login');
        }

        // Tous les utilisateurs connectés peuvent modifier leurs propres annonces

        $listingId = (int)($_GET['id'] ?? 0);
        if (!$listingId) {
            // Plus de messages flash - suppression complète('error', 'Annonce non trouvée');
            return $this->redirect('/dashboard');
        }

        // Vérifier que l'utilisateur est le propriétaire de l'annonce
        $listing = $this->listingModel->getById($listingId);

        if (!$listing || $listing['user_id'] !== $this->getCurrentUserId()) {
            // Plus de messages flash - suppression complète('error', 'Vous ne pouvez pas modifier cette annonce');
            return $this->redirect('/dashboard');
        }

        // Traiter la soumission du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->processListingUpdate($listingId);
        }

        // Afficher le formulaire de modification
        $data = [
            'title' => 'Modifier l\'annonce - How I Win My Home',
            'page' => 'listing-edit',
            'listing' => $listing,
            'categories' => $this->getPropertyCategories(),
            'errors' => [],
            'old' => []
        ];

        return $this->renderLayout('listings/edit', $data);
    }

    /**
     * Traite la modification d'une annonce
     * @param int $listingId ID de l'annonce
     * @return string Redirection ou message d'erreur
     */
    private function processListingUpdate($listingId)
    {
        try {
            // Validation des données
            $validation = $this->validateListingData($_POST);
            if (!$validation['valid']) {
                // Plus de messages flash - suppression complète('error', $validation['message']);
                return $this->redirect('/listings/edit?id=' . $listingId);
            }

            // Traitement de l'image si fournie
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->processListingImage($_FILES['image']);
            }

            // Mise à jour de l'annonce
            $updateData = [
                'titre' => trim($_POST['titre']),
                'description' => trim($_POST['description']),
                'prix_total' => (float)$_POST['prix_total'],
                'ticket_price' => (float)$_POST['ticket_price'],
                'tickets_needed' => (int)$_POST['tickets_needed'],
                'property_type' => $_POST['property_type'],
                'surface' => (int)($_POST['surface'] ?? 0),
                'nb_pieces' => (int)($_POST['nb_pieces'] ?? 0),
                'adresse' => trim($_POST['adresse'] ?? ''),
                'ville' => trim($_POST['ville'] ?? ''),
                'code_postal' => trim($_POST['code_postal'] ?? ''),
                'end_date' => $_POST['end_date']
            ];

            if ($imagePath) {
                $updateData['image'] = $imagePath;
            }

            if ($this->listingModel->update($listingId, $updateData)) {
                // Plus de messages flash - suppression complète('success', 'Annonce mise à jour avec succès !');
                return $this->redirect('/dashboard');
            } else {
                // Plus de messages flash - suppression complète('error', 'Erreur lors de la mise à jour de l\'annonce');
                return $this->redirect('/listings/edit?id=' . $listingId);
            }
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour de l'annonce: " . $e->getMessage());
            // Plus de messages flash - suppression complète('error', 'Une erreur s\'est produite lors de la mise à jour de l\'annonce');
            return $this->redirect('/listings/edit?id=' . $listingId);
        }
    }

    /**
     * Supprime une annonce
     * @return string Redirection
     */
    public function delete()
    {
        // Vérifier que l'utilisateur est connecté et a le rôle vendeur
        if (!$this->isUserLoggedIn()) {
            // Plus de messages flash - suppression complète('error', 'Vous devez être connecté pour supprimer une annonce');
            return $this->redirect('/auth/login');
        }

        // Tous les utilisateurs connectés peuvent supprimer leurs propres annonces

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/dashboard');
        }

        $listingId = (int)($_POST['listing_id'] ?? 0);
        if (!$listingId) {
            // Plus de messages flash - suppression complète('error', 'Annonce non trouvée');
            return $this->redirect('/dashboard');
        }

        // Vérifier que l'utilisateur est le propriétaire de l'annonce
        $listing = $this->listingModel->getById($listingId);

        if (!$listing || $listing['user_id'] !== $this->getCurrentUserId()) {
            // Plus de messages flash - suppression complète('error', 'Vous ne pouvez pas supprimer cette annonce');
            return $this->redirect('/dashboard');
        }

        try {
            if ($this->listingModel->delete($listingId)) {
                // Plus de messages flash - suppression complète('success', 'Annonce supprimée avec succès');
            } else {
                // Plus de messages flash - suppression complète('error', 'Erreur lors de la suppression de l\'annonce');
            }
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression de l'annonce: " . $e->getMessage());
            // Plus de messages flash - suppression complète('error', 'Une erreur s\'est produite lors de la suppression');
        }

        return $this->redirect('/dashboard');
    }

    /**
     * Recherche des annonces selon des critères
     * @return string HTML des résultats de recherche
     */
    public function search()
    {
        try {
            $searchTerm = trim($_GET['search'] ?? '');
            $propertyType = $_GET['type'] ?? '';
            $maxPrice = (float)($_GET['prix_max'] ?? 0);
            $surfaceMin = (float)($_GET['surface_min'] ?? 0);
            $ville = trim($_GET['ville'] ?? '');

            $results = $this->listingModel->search($searchTerm, $propertyType, $maxPrice, $surfaceMin, $ville) ?: [];

            $data = [
                'title' => 'Résultats de recherche - How I Win My Home',
                'page' => 'listings-search',
                'results' => $results,
                'searchTerm' => $searchTerm,
                'filters' => [
                    'type' => $propertyType,
                    'prix_max' => $maxPrice,
                    'surface_min' => $surfaceMin,
                    'ville' => $ville
                ],
                'categories' => $this->getPropertyCategories()
            ];

            return $this->renderLayout('listings/search', $data);
        } catch (Exception $e) {
            error_log("Erreur lors de la recherche: " . $e->getMessage());
            // Plus de messages flash - suppression complète('error', 'Erreur lors de la recherche');
            return $this->redirect('/listings');
        }
    }

    // ========================================
    // MÉTHODES PRIVÉES ET UTILITAIRES
    // ========================================

    /**
     * Valide les données d'une annonce en utilisant ValidationManager
     * @param array $data Données du formulaire
     * @param bool $isUpdate True si c'est une mise à jour
     * @return array Résultat de la validation
     */
    private function validateListingData($data, $isUpdate = false)
    {
        // Règles de validation pour les annonces
        $rules = [
            'title' => ['required', 'min:5', 'max:255'],
            'description' => ['required', 'min:20', 'max:2000'],
            'price' => ['required', 'numeric', 'min_value:50000', 'max_value:5000000'],
            'ticket_price' => ['required', 'in:5,10,15,20'],
            'tickets_needed' => ['required', 'numeric', 'min_value:10', 'max_value:200000'],
            'property_type' => ['required', 'in:appartement,maison,villa,studio,loft,autre'],
            'property_size' => ['required', 'numeric', 'min_value:10'],
            'rooms' => ['required', 'numeric', 'min_value:1'],
            'bedrooms' => ['required', 'numeric', 'min_value:0'],
            'address' => ['required'],
            'city' => ['required'],
            'postal_code' => ['required', 'regex:/^\d{5}$/'],
            'end_date' => ['required', 'date', 'after:tomorrow', 'before:+1 year']
        ];

        // Messages personnalisés
        $messages = [
            'title.required' => 'Le titre est obligatoire',
            'title.min' => 'Le titre doit contenir au moins 5 caractères',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères',
            'description.required' => 'La description est obligatoire',
            'description.min' => 'La description doit contenir au moins 20 caractères',
            'description.max' => 'La description ne peut pas dépasser 2000 caractères',
            'price.required' => 'Le prix est obligatoire',
            'price.numeric' => 'Le prix doit être un nombre',
            'price.min_value' => 'Le prix minimum est de 50 000€',
            'price.max_value' => 'Le prix maximum est de 5 000 000€',
            'ticket_price.required' => 'Le prix du ticket est obligatoire',
            'ticket_price.in' => 'Le prix du ticket doit être 5€, 10€, 15€ ou 20€',
            'tickets_needed.required' => 'Le nombre de tickets est obligatoire',
            'tickets_needed.numeric' => 'Le nombre de tickets doit être un nombre',
            'tickets_needed.min_value' => 'Le nombre minimum de tickets est 10',
            'tickets_needed.max_value' => 'Le nombre maximum de tickets est 10 000',
            'property_type.required' => 'Le type de bien est obligatoire',
            'property_type.in' => 'Type de bien invalide',
            'property_size.required' => 'La surface est obligatoire',
            'property_size.numeric' => 'La surface doit être un nombre',
            'property_size.min_value' => 'La surface minimum est de 10m²',
            'rooms.required' => 'Le nombre de pièces est obligatoire',
            'rooms.numeric' => 'Le nombre de pièces doit être un nombre',
            'rooms.min_value' => 'Le nombre de pièces doit être au moins 1',
            'bedrooms.required' => 'Le nombre de chambres est obligatoire',
            'bedrooms.numeric' => 'Le nombre de chambres doit être un nombre',
            'bedrooms.min_value' => 'Le nombre de chambres doit être positif ou zéro',
            'address.required' => 'L\'adresse est obligatoire',
            'city.required' => 'La ville est obligatoire',
            'postal_code.required' => 'Le code postal est obligatoire',
            'postal_code.regex' => 'Le code postal doit contenir 5 chiffres',
            'end_date.required' => 'La date de fin est obligatoire',
            'end_date.date' => 'La date de fin doit être une date valide',
            'end_date.after' => 'La date de fin doit être au moins demain',
            'end_date.before' => 'La date de fin ne peut pas dépasser 1 an',
            'images.required' => 'Vous devez fournir au moins 3 photos du bien',
            'images.array' => 'Les photos doivent être des fichiers valides',
            'images.min' => 'Vous devez fournir au moins 3 photos du bien',
            'images.max' => 'Vous ne pouvez pas fournir plus de 10 photos'
        ];

        // Utiliser ValidationManager
        $validationManager = new \App\Services\ValidationManager();
        $validationManager->setCustomMessages($messages);

        $isValid = $validationManager->validate($data, $rules);

        return [
            'valid' => $isValid,
            'errors' => $validationManager->getErrors(),
            'message' => $isValid ? '' : 'Veuillez corriger les erreurs'
        ];
    }

    /**
     * Traite l'upload d'une image d'annonce
     * @param array|null $file Fichier uploadé
     * @return string|null Chemin de l'image ou null
     */
    private function processListingImage($file)
    {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        try {
            // FileHelper est une classe statique
            return FileHelper::uploadImage($file, 'listings');
        } catch (Exception $e) {
            error_log("Erreur lors du traitement de l'image: " . $e->getMessage());
            throw new Exception('Erreur lors du traitement de l\'image');
        }
    }

    /**
     * Traite l'upload de plusieurs images d'annonce
     * @param array|null $files Fichiers uploadés
     * @return array|null Chemins des images ou null
     */
    private function processListingImages($validatedFiles)
    {
        if (!$validatedFiles || !is_array($validatedFiles)) {
            return [];
        }

        $imagePaths = [];
        $fileCount = count($validatedFiles);

        // Vérifier le nombre d'images (min 3, max 10)
        if ($fileCount < 3) {
            throw new Exception('Vous devez fournir au moins 3 photos du bien');
        }
        if ($fileCount > 10) {
            throw new Exception('Vous ne pouvez pas fournir plus de 10 photos');
        }

        try {
            // FileHelper est une classe statique

            foreach ($validatedFiles as $file) {
                // Vérifier que $file est un tableau et qu'il n'y a pas d'erreur
                if (!is_array($file) || $file['error'] !== UPLOAD_ERR_OK) {
                    continue;
                }

                // Vérifier la taille (max 5MB)
                if ($file['size'] > 5 * 1024 * 1024) {
                    throw new Exception('L\'image ' . $file['name'] . ' est trop volumineuse (max 5MB)');
                }

                // Vérifier le type de fichier
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!in_array($file['type'], $allowedTypes)) {
                    throw new Exception('Le fichier ' . $file['name'] . ' n\'est pas un format d\'image valide');
                }

                // Uploader l'image
                $imagePath = FileHelper::uploadImage($file, 'listings');
                if ($imagePath) {
                    $imagePaths[] = $imagePath;
                }
            }

            return $imagePaths;
        } catch (Exception $e) {
            error_log("Erreur lors du traitement des images: " . $e->getMessage());
            throw new Exception('Erreur lors du traitement des images: ' . $e->getMessage());
        }
    }

    /**
     * Vérifie si l'utilisateur peut acheter des tickets pour une annonce
     * @param int $listingId ID de l'annonce
     * @return bool True si l'utilisateur peut acheter
     */
    private function canUserBuyTickets($listingId)
    {
        try {
            $listing = $this->listingModel->getById($listingId);

            if (!$listing || $listing['status'] !== 'active') {
                return false;
            }

            // Vérifier si la date de fin n'est pas dépassée
            if (isset($listing['end_date']) && strtotime($listing['end_date']) <= time()) {
                return false;
            }

            // Vérifier si l'utilisateur n'est pas le vendeur
            if ($listing['user_id'] === $this->getCurrentUserId()) {
                return false;
            }

            // L'utilisateur peut toujours acheter des tickets (pas de limite)
            // Le système fonctionne avec un minimum à atteindre, pas une limite maximale
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la vérification des permissions d'achat: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtient les statistiques des annonces
     * @return array Statistiques des annonces
     */
    private function getListingStats()
    {
        try {
            $listings = $this->listingModel->getAll() ?: [];

            $stats = [
                'total' => count($listings),
                'active' => 0,
                'pending' => 0,
                'completed' => 0,
                'total_value' => 0,
                'avg_ticket_price' => 0
            ];

            $totalTicketPrice = 0;
            $totalTickets = 0;

            foreach ($listings as $listing) {
                $status = $listing['status'] ?? 'pending';

                switch ($status) {
                    case 'active':
                        $stats['active']++;
                        break;
                    case 'pending':
                        $stats['pending']++;
                        break;
                    case 'completed':
                        $stats['completed']++;
                        break;
                }

                if (isset($listing['prix_total'])) {
                    $stats['total_value'] += $listing['prix_total'];
                }

                if (isset($listing['ticket_price']) && isset($listing['tickets_needed'])) {
                    $totalTicketPrice += $listing['ticket_price'] * $listing['tickets_needed'];
                    $totalTickets += $listing['tickets_needed'];
                }
            }

            if ($totalTickets > 0) {
                $stats['avg_ticket_price'] = $totalTicketPrice / $totalTickets;
            }

            return $stats;
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des stats des annonces: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtient les catégories de propriétés
     * @return array Catégories de propriétés
     */
    private function getPropertyCategories()
    {
        return [
            'appartement' => 'Appartement',
            'maison' => 'Maison',
            'villa' => 'Villa',
            'terrain' => 'Terrain',
            'bureau' => 'Bureau/Commerce',
            'immeuble' => 'Immeuble',
            'autre' => 'Autre'
        ];
    }

    /**
     * Obtient les annonces similaires
     * @param int $listingId ID de l'annonce actuelle
     * @param string $propertyType Type de propriété de l'annonce
     * @return array Annonces similaires
     */
    private function getRelatedListings($listingId, $propertyType)
    {
        try {
            $related = $this->listingModel->getRelated($listingId, $propertyType, 3) ?: [];
            return $related;
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des annonces similaires: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtient le nombre total de participants
     * @return int Nombre total de participants
     */
    private function getTotalParticipants()
    {
        try {
            return $this->userModel->getTotalParticipants() ?: 0;
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération du nombre de participants: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtient la liste des villes disponibles
     * @return array Liste des villes
     */
    private function getVilles()
    {
        try {
            return $this->listingModel->getVilles() ?: [];
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des villes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtient les filtres appliqués
     * @return array Filtres appliqués
     */
    private function getFilters()
    {
        return [
            'search' => $_GET['search'] ?? '',
            'ville' => $_GET['ville'] ?? '',
            'type' => $_GET['type'] ?? '',
            'surface_min' => $_GET['surface_min'] ?? '',
            'prix_max' => $_GET['prix_max'] ?? ''
        ];
    }

    /**
     * Affiche les annonces de l'utilisateur connecté
     * @return string HTML de la liste des annonces de l'utilisateur
     */
    public function myListings()
    {
        // Vérifier que l'utilisateur est connecté
        if (!$this->isUserLoggedIn()) {
            return $this->redirect('/');
        }

        try {
            $userId = $this->getCurrentUserId();

            // Récupérer les annonces de l'utilisateur
            $listings = $this->listingModel->getBySeller($userId) ?: [];

            // Calculer les statistiques
            $stats = [
                'total_listings' => count($listings),
                'active_listings' => count(array_filter($listings, function ($listing) {
                    return $listing['status'] === 'active';
                })),
                'pending_listings' => count(array_filter($listings, function ($listing) {
                    return $listing['status'] === 'pending';
                })),
                'total_tickets_sold' => 0
            ];

            // Calculer le nombre total de tickets vendus
            foreach ($listings as $listing) {
                $stats['total_tickets_sold'] += $this->listingModel->getTicketsSold($listing['id']) ?: 0;
            }

            $data = [
                'title' => 'Mes annonces - How I Win My Home',
                'page' => 'my-listings',
                'listings' => $listings,
                'stats' => $stats,
                'currentUserId' => $userId
            ];

            return $this->renderLayout('listings/my-listings', $data);
        } catch (Exception $e) {
            error_log("Erreur lors de l'affichage des annonces de l'utilisateur: " . $e->getMessage());
            return $this->redirect('/listings');
        }
    }

    // ========================================
    // MÉTHODES UTILITAIRES
    // ========================================


    /**
     * Valide l'authentification et les permissions
     * @param bool $requireLogin Exiger une connexion
     * @param int|null $listingId ID de l'annonce pour vérifier la propriété
     * @return bool|string True si autorisé, sinon redirection
     */
    private function validatePermissions(bool $requireLogin = true, ?int $listingId = null)
    {
        if ($requireLogin && !$this->isUserLoggedIn()) {
            return $this->redirect('/auth/login');
        }

        if ($listingId) {
            $listing = $this->listingModel->getById($listingId);
            if (!$listing || $listing['user_id'] !== $this->getCurrentUserId()) {
                return $this->redirect('/dashboard');
            }
        }

        return true;
    }
}
