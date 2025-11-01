<?php
/**
 * Contrôleur Document simplifié - How I Win My Home
 * 
 * Gère l'upload et la gestion des documents de manière simple
 */

class DocumentController extends BaseController {
    
    private $documentModel;
    
    public function __construct() {
        parent::__construct();
        $this->documentModel = new Document();
    }
    
    /**
     * Affiche la liste des documents de l'utilisateur
     */
    public function index() {
        if (!$this->isUserLoggedIn()) {
            return $this->redirect('/auth/login');
        }
        
        $userId = $this->getCurrentUserId();
        $documents = $this->documentModel->getByUserId($userId);
        
        $data = [
            'title' => 'Mes documents - How I Win My Home',
            'page' => 'documents',
            'documents' => $documents
        ];
        
        return $this->renderLayout('documents/index', $data);
    }
    
    /**
     * Affiche le formulaire d'upload
     */
    public function upload() {
        if (!$this->isUserLoggedIn()) {
            return $this->redirect('/auth/login');
        }
        
        $listingId = $_GET['listing_id'] ?? null;
        
        $data = [
            'title' => 'Uploader un document - How I Win My Home',
            'page' => 'document-upload',
            'listing_id' => $listingId
        ];
        
        return $this->renderLayout('documents/upload', $data);
    }
    
    /**
     * Traite l'upload d'un document
     */
    public function processUpload() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/documents');
        }
        
        if (!$this->isUserLoggedIn()) {
            return $this->redirect('/auth/login');
        }
        
        $userId = $this->getCurrentUserId();
        
        // Vérifier qu'un fichier a été uploadé
        if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
            return $this->redirect('/documents/upload?error=upload_failed');
        }
        
        $file = $_FILES['document'];
        
        // Validation du fichier
        $validation = $this->validateFile($file);
        if (!$validation['valid']) {
            return $this->redirect('/documents/upload?error=' . $validation['error']);
        }
        
        // Créer le dossier de destination
        $uploadDir = UPLOAD_PATH . '/documents/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Générer un nom de fichier unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $filePath = $uploadDir . $filename;
        
        // Déplacer le fichier
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            return $this->redirect('/documents/upload?error=move_failed');
        }
        
        // Sauvegarder en base de données
        $documentData = [
            'user_id' => $userId,
            'listing_id' => $_POST['listing_id'] ?? null,
            'filename' => $filename,
            'original_filename' => $file['name'],
            'file_path' => $filePath,
            'file_size' => $file['size'],
            'mime_type' => $file['type'],
            'document_type' => $_POST['document_type'] ?? 'other',
            'status' => 'uploaded'
        ];
        
        $documentId = $this->documentModel->create($documentData);
        
        if ($documentId) {
            return $this->redirect('/documents?success=uploaded');
        } else {
            // Supprimer le fichier si l'insertion en DB a échoué
            unlink($filePath);
            return $this->redirect('/documents/upload?error=db_failed');
        }
    }
    
    /**
     * Télécharge un document
     */
    public function download($documentId) {
        if (!$this->isUserLoggedIn()) {
            return $this->redirect('/auth/login');
        }
        
        $userId = $this->getCurrentUserId();
        $userRole = $this->getCurrentUserRole();
        
        // Vérifier les permissions
        if (!$this->documentModel->canAccess($documentId, $userId, $userRole)) {
            return $this->redirect('/documents');
        }
        
        $document = $this->documentModel->getById($documentId);
        if (!$document || !file_exists($document['file_path'])) {
            return $this->redirect('/documents');
        }
        
        // Envoyer le fichier
        header('Content-Type: ' . $document['mime_type']);
        header('Content-Disposition: attachment; filename="' . $document['original_filename'] . '"');
        header('Content-Length: ' . filesize($document['file_path']));
        readfile($document['file_path']);
        exit;
    }
    
    /**
     * Affiche un document
     */
    public function view($documentId) {
        if (!$this->isUserLoggedIn()) {
            return $this->redirect('/auth/login');
        }
        
        $userId = $this->getCurrentUserId();
        $userRole = $this->getCurrentUserRole();
        
        // Vérifier les permissions
        if (!$this->documentModel->canAccess($documentId, $userId, $userRole)) {
            return $this->redirect('/documents');
        }
        
        $document = $this->documentModel->getById($documentId);
        if (!$document || !file_exists($document['file_path'])) {
            return $this->redirect('/documents');
        }
        
        // Afficher le document selon son type
        if (strpos($document['mime_type'], 'image/') === 0) {
            header('Content-Type: ' . $document['mime_type']);
            readfile($document['file_path']);
            exit;
        } else {
            // Pour les PDF et autres, forcer le téléchargement
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $document['original_filename'] . '"');
            readfile($document['file_path']);
            exit;
        }
    }
    
    /**
     * Supprime un document
     */
    public function delete($documentId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/documents');
        }
        
        if (!$this->isUserLoggedIn()) {
            return $this->redirect('/auth/login');
        }
        
        $userId = $this->getCurrentUserId();
        
        $document = $this->documentModel->getById($documentId, $userId);
        if (!$document) {
            return $this->redirect('/documents');
        }
        
        // Supprimer le fichier physique
        if (file_exists($document['file_path'])) {
            unlink($document['file_path']);
        }
        
        // Supprimer de la base de données
        $this->documentModel->delete($documentId, $userId);
        
        return $this->redirect('/documents?success=deleted');
    }
    
    /**
     * Valide un fichier uploadé
     */
    private function validateFile($file) {
        // Vérifier la taille
        if ($file['size'] > MAX_FILE_SIZE) {
            return ['valid' => false, 'error' => 'file_too_large'];
        }
        
        // Vérifier le type MIME
        $allowedTypes = [
            'image/jpeg', 'image/png', 'image/gif',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        
        if (!in_array($file['type'], $allowedTypes)) {
            return ['valid' => false, 'error' => 'invalid_type'];
        }
        
        // Vérifier l'extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
        
        if (!in_array($extension, $allowedExtensions)) {
            return ['valid' => false, 'error' => 'invalid_extension'];
        }
        
        return ['valid' => true];
    }
}
