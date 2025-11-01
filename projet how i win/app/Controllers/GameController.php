<?php
/**
 * CONTRÔLEUR JEU - HOW I WIN MY HOME V1
 * 
 * Gère toutes les fonctionnalités liées au jeu :
 * - Tickets d'achat
 * - QCM chronométré
 * - Lettres de motivation
 * - Résultats et sélection
 */

class GameController extends BaseController {
    
    private $userModel;
    private $listingModel;
    private $gameModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->listingModel = new Listing();
        $this->gameModel = new Game();
    }
    
    // ========================================
    // GESTION DES TICKETS
    // ========================================
    
    /**
     * Affiche la page d'achat de ticket
     */
    public function buyTicket() {
        // Temporairement désactivé pour les tests
        // if (!$this->isUserLoggedIn()) {
        //     return $this->redirect('/auth/login');
        // }
        
        $listingId = $_GET['listing_id'] ?? null;
        if (!$listingId) {
            return $this->redirect('/listings');
        }
        
        $listing = $this->listingModel->getById($listingId);
        if (!$listing || $listing['status'] !== 'active') {
            return $this->redirect('/listings');
        }
        
        // Calculer les statistiques des tickets
        $ticketsVendus = $this->gameModel->countTicketsByListing($listingId);
        $prixTotal = $listing['prix_total'] ?? 0;
        $prixTicket = $listing['prix_ticket'] ?? $listing['ticket_price'] ?? 0;
        
        // Si le prix du ticket n'est pas défini, utiliser une valeur par défaut
        if ($prixTicket <= 0) {
            $prixTicket = 5; // Prix par défaut de 5€
        }
        
        // Calcul avec la marge de 10% (commission)
        $commissionRate = 0.10; // 10%
        $netTicketPrice = $prixTicket * (1 - $commissionRate); // Prix net par ticket (90% du prix)
        $ticketsTotal = $netTicketPrice > 0 ? intval($prixTotal / $netTicketPrice) : 0;
        $ticketsRestants = max(0, $ticketsTotal - $ticketsVendus);
        
        // Récupérer le solde de l'utilisateur connecté
        $userBalance = 0;
        if ($this->isUserLoggedIn()) {
            $userBalance = $this->userModel->getBalance($this->getCurrentUserId());
        }
        
        $data = [
            'title' => 'Acheter un ticket - ' . ($listing['title'] ?? 'Annonce'),
            'page' => 'buy-ticket',
            'page_css' => ['ticket-buy.css'],
            'isLoggedIn' => $this->isUserLoggedIn(),
            'listing' => $listing,
            'ticketsVendus' => $ticketsVendus,
            'ticketsRestants' => $ticketsRestants,
            'ticketsTotal' => $ticketsTotal,
            'userBalance' => $userBalance
        ];
        
        return $this->renderLayout('ticket/buy', $data);
    }
    
    /**
     * Traite l'achat d'un ticket
     */
    public function processTicketPurchase() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/listings');
        }
        
        if (!$this->isUserLoggedIn()) {
            return $this->redirect('/auth/login');
        }
        
        $listingId = $_POST['listing_id'] ?? null;
        $userId = $this->getCurrentUserId();
        
        if (!$listingId) {
            return $this->redirect('/listings');
        }
        
        $listing = $this->listingModel->getById($listingId);
        if (!$listing || $listing['status'] !== 'active') {
            return $this->redirect('/listings');
        }
        
        // Vérifier si l'utilisateur a déjà un ticket pour cette annonce
        $existingTicket = $this->gameModel->getTicketByUserAndListing($userId, $listingId);
        if ($existingTicket) {
            return $this->redirect('/game/qcm?listing_id=' . $listingId);
        }
        
        // Récupérer le prix du ticket
        $ticketPrice = $listing['ticket_price'] ?? $listing['prix_ticket'] ?? 5;
        
        // Vérifier le solde de l'utilisateur
        $userBalance = $this->userModel->getBalance($userId);
        if ($userBalance < $ticketPrice) {
            $_SESSION['error_message'] = "Solde insuffisant. Vous avez " . number_format($userBalance, 0, ',', ' ') . "€ mais le ticket coûte " . number_format($ticketPrice, 0, ',', ' ') . "€.";
            return $this->redirect('/account/deposit');
        }
        
        // Déduire le montant du solde
        $deductionSuccess = $this->userModel->deductBalance($userId, $ticketPrice);
        if (!$deductionSuccess) {
            $_SESSION['error_message'] = "Erreur lors de la déduction du solde. Veuillez réessayer.";
            return $this->redirect('/game/buy-ticket?listing_id=' . $listingId);
        }
        
        // Créer le ticket
        $ticketData = [
            'user_id' => $userId,
            'listing_id' => $listingId,
            'ticket_price' => $ticketPrice,
            'status' => 'active',
            'date_achat' => date('Y-m-d H:i:s')
        ];
        
        $ticketId = $this->gameModel->createTicket($ticketData);
        
        if ($ticketId) {
            $_SESSION['success_message'] = "Ticket acheté avec succès pour " . number_format($ticketPrice, 0, ',', ' ') . "€ !";
            return $this->redirect('/game/qcm?listing_id=' . $listingId);
        } else {
            // En cas d'erreur, rembourser le solde
            $this->userModel->addBalance($userId, $ticketPrice);
            $_SESSION['error_message'] = "Erreur lors de la création du ticket. Le montant a été remboursé.";
            return $this->redirect('/game/buy-ticket?listing_id=' . $listingId);
        }
    }
    
    /**
     * Affiche les tickets de l'utilisateur
     */
    public function myTickets() {
        if (!$this->isUserLoggedIn()) {
            return $this->redirect('/auth/login');
        }
        
        $userId = $this->getCurrentUserId();
        $tickets = $this->gameModel->getTicketsByUserId($userId);
        
        // Enrichir avec les informations des annonces
        foreach ($tickets as &$ticket) {
            $listing = $this->listingModel->getById($ticket['listing_id']);
            if ($listing) {
                $ticket['listing'] = $listing;
            }
        }
        
        // Séparer les tickets par statut
        $activeTickets = array_filter($tickets, function($ticket) {
            return $ticket['status'] === 'active';
        });
        
        // Récupérer les QCM et lettres en attente
        $pending_qcm = $this->gameModel->getQcmResultsByUserId($userId);
        $pending_letter = $this->gameModel->getLettersByUserId($userId);
        
        $data = [
            'title' => 'Mes tickets - How I Win My Home',
            'page' => 'my-tickets',
            'tickets' => $tickets,
            'activeTickets' => $activeTickets,
            'pending_qcm' => $pending_qcm,
            'pending_letter' => $pending_letter
        ];
        
        return $this->renderLayout('ticket/my-tickets', $data);
    }
    
    // ========================================
    // GESTION DU QCM
    // ========================================
    
    /**
     * Affiche le QCM chronométré
     */
    public function qcm() {
        if (!$this->isUserLoggedIn()) {
            return $this->redirect('/auth/login');
        }
        
        $listingId = $_GET['listing_id'] ?? null;
        if (!$listingId) {
            return $this->redirect('/listings');
        }
        
        $userId = $this->getCurrentUserId();
        
        // Vérifier que l'utilisateur a un ticket valide
        $ticket = $this->gameModel->getTicketByUserAndListing($userId, $listingId);
        if (!$ticket || $ticket['status'] !== 'active') {
            return $this->redirect('/game/buy-ticket?listing_id=' . $listingId);
        }
        
        // Vérifier si l'utilisateur a déjà passé le QCM
        $existingResult = $this->gameModel->getQcmResultByUserAndListing($userId, $listingId);
        if ($existingResult) {
            return $this->redirect('/game/qcm-results?listing_id=' . $listingId);
        }
        
        $listing = $this->listingModel->getById($listingId);
        if (!$listing || $listing['status'] !== 'active') {
            return $this->redirect('/listings');
        }
        
        // Récupérer les questions aléatoires
        $questions = $this->gameModel->getRandomQuestions(10);
        
        $data = [
            'title' => 'QCM - ' . ($listing['title'] ?? 'Annonce'),
            'page' => 'qcm',
            'listing' => $listing,
            'listingId' => $listingId,
            'questions' => $questions,
            'timeLimit' => 300 // 5 minutes
        ];
        
        return $this->renderLayout('qcm/index', $data);
    }
    
    /**
     * Traite les réponses du QCM
     */
    public function processQcmAnswers() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/listings');
        }
        
        if (!$this->isUserLoggedIn()) {
            return $this->redirect('/auth/login');
        }
        
        $listingId = $_POST['listing_id'] ?? null;
        $userId = $this->getCurrentUserId();
        
        if (!$listingId) {
            return $this->redirect('/listings');
        }
        
        // Vérifier que l'utilisateur a un ticket valide
        $ticket = $this->gameModel->getTicketByUserAndListing($userId, $listingId);
        if (!$ticket || $ticket['status'] !== 'active') {
            return $this->redirect('/game/buy-ticket?listing_id=' . $listingId);
        }
        
        // Vérifier si l'utilisateur a déjà passé le QCM
        $existingResult = $this->gameModel->getQcmResultByUserAndListing($userId, $listingId);
        if ($existingResult) {
            return $this->redirect('/game/qcm-results?listing_id=' . $listingId);
        }
        
        // Traiter les réponses
        $answers = $_POST['answers'] ?? [];
        $score = 0;
        $totalQuestions = count($answers);
        
        foreach ($answers as $questionId => $answer) {
            $question = $this->gameModel->getQuestionById($questionId);
            if ($question) {
                $correctAnswers = json_decode($question['correct_answers'], true);
                // Convertir la réponse numérique en lettre
                $answerLetter = '';
                switch ($answer) {
                    case '1': $answerLetter = 'A'; break;
                    case '2': $answerLetter = 'B'; break;
                    case '3': $answerLetter = 'C'; break;
                }
                if (in_array($answerLetter, $correctAnswers)) {
                    $score++;
                }
            }
        }
        
        // Calculer le pourcentage
        $percentage = $totalQuestions > 0 ? ($score / $totalQuestions) * 100 : 0;
        
        // Déterminer le statut
        $status = $percentage >= 70 ? 'qualifie' : 'elimine';
        
        // Récupérer l'ID du ticket
        $ticket = $this->gameModel->getTicketByUserAndListing($userId, $listingId);
        $ticketId = $ticket['id'] ?? null;
        
        // Sauvegarder le résultat
        $resultData = [
            'user_id' => $userId,
            'listing_id' => $listingId,
            'ticket_id' => $ticketId,
            'score' => $score,
            'total_questions' => $totalQuestions,
            'percentage' => $percentage,
            'status' => $status,
            'answers' => json_encode($answers),
            'completed_at' => date('Y-m-d H:i:s')
        ];
        
        $resultId = $this->gameModel->createQcmResult($resultData);
        
        if ($resultId) {
            if ($status === 'qualifie') {
                return $this->redirect('/game/create-letter?listing_id=' . $listingId);
            } else {
                return $this->redirect('/game/qcm-results?listing_id=' . $listingId);
            }
        } else {
            return $this->redirect('/game/qcm?listing_id=' . $listingId);
        }
    }
    
    /**
     * Affiche les résultats du QCM
     */
    public function qcmResults() {
        if (!$this->isUserLoggedIn()) {
            return $this->redirect('/auth/login');
        }
        
        $listingId = $_GET['listing_id'] ?? null;
        if (!$listingId) {
            return $this->redirect('/listings');
        }
        
        $userId = $this->getCurrentUserId();
        
        $result = $this->gameModel->getQcmResultByUserAndListing($userId, $listingId);
        if (!$result) {
            return $this->redirect('/game/qcm?listing_id=' . $listingId);
        }
        
        $listing = $this->listingModel->getById($listingId);
        
        $data = [
            'title' => 'Résultats QCM - ' . ($listing['title'] ?? 'Annonce'),
            'page' => 'qcm-results',
            'listing' => $listing,
            'result' => $result
        ];
        
        return $this->renderLayout('qcm/results', $data);
    }
    
    // ========================================
    // GESTION DES LETTRES DE MOTIVATION
    // ========================================
    
    /**
     * Affiche le formulaire de création de lettre
     */
    public function createLetter() {
        if (!$this->isUserLoggedIn()) {
            return $this->redirect('/auth/login');
        }
        
        $listingId = $_GET['listing_id'] ?? null;
        if (!$listingId) {
            return $this->redirect('/listings');
        }
        
        $userId = $this->getCurrentUserId();
        
        // Vérifier que l'utilisateur a réussi le QCM
        $qcmResult = $this->gameModel->getQcmResultByUserAndListing($userId, $listingId);
        if (!$qcmResult || $qcmResult['status'] !== 'qualifie') {
            return $this->redirect('/game/qcm?listing_id=' . $listingId);
        }
        
        // Vérifier que l'utilisateur n'a pas déjà une lettre
        $existingLetter = $this->gameModel->getLetterByUserAndListing($userId, $listingId);
        if ($existingLetter) {
            return $this->redirect('/game/view-letter?id=' . $existingLetter['id']);
        }
        
        $listing = $this->listingModel->getById($listingId);
        if (!$listing || $listing['status'] !== 'active') {
            return $this->redirect('/listings');
        }
        
        // Traiter la soumission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->processLetterCreation($listingId, $listing);
        }
        
        $data = [
            'title' => 'Lettre de motivation - ' . ($listing['title'] ?? 'Annonce'),
            'page' => 'letter-create',
            'listing' => $listing,
            'qcmResult' => $qcmResult
        ];
        
        return $this->renderLayout('letter/create', $data);
    }
    
    /**
     * Traite la création d'une lettre
     */
    private function processLetterCreation($listingId, $listing) {
        $userId = $this->getCurrentUserId();
        
        $contenu = $_POST['contenu'] ?? '';
        $titre = $_POST['titre'] ?? '';
        
        if (empty($contenu) || strlen($contenu) < 50) {
            return $this->redirect('/game/create-letter?listing_id=' . $listingId);
        }
        
        $letterData = [
            'user_id' => $userId,
            'listing_id' => $listingId,
            'contenu' => htmlspecialchars($contenu),
            'titre' => htmlspecialchars($titre),
            'status' => 'soumis',
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $letterId = $this->gameModel->createLetter($letterData);
        
        if ($letterId) {
            return $this->redirect('/game/my-letters');
        } else {
            return $this->redirect('/game/create-letter?listing_id=' . $listingId);
        }
    }
    
    /**
     * Traite la soumission de lettre de motivation
     */
    public function processLetter() {
        if (!$this->isUserLoggedIn()) {
            return $this->redirect('/auth/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/listings');
        }
        
        $userId = $this->getCurrentUserId();
        $listingId = $_POST['listing_id'] ?? null;
        
        if (!$listingId) {
            $_SESSION['error_message'] = 'Annonce non spécifiée.';
            return $this->redirect('/listings');
        }
        
        // Vérifier que l'utilisateur a le droit de soumettre une lettre pour cette annonce
        $qcmResult = $this->gameModel->getQcmResultByUserAndListing($userId, $listingId);
        if (!$qcmResult || ($qcmResult['pourcentage'] ?? 0) < 50) {
            $_SESSION['error_message'] = 'Vous devez d\'abord réussir le QCM pour soumettre une lettre.';
            return $this->redirect('/game/qcm?listing_id=' . $listingId);
        }
        
        // Validation des données
        $errors = [];
        $contenu = trim($_POST['contenu'] ?? '');
        $terms = $_POST['terms'] ?? false;
        
        if (empty($contenu)) {
            $errors['contenu'] = 'Le contenu de la lettre est requis.';
        } elseif (strlen($contenu) > 1000) {
            $errors['contenu'] = 'La lettre ne peut pas dépasser 1000 caractères.';
        }
        
        if (!$terms) {
            $errors['terms'] = 'Vous devez accepter les conditions.';
        }
        
        if (!empty($errors)) {
            $_SESSION['error_message'] = 'Veuillez corriger les erreurs.';
            $_SESSION['form_data'] = $_POST;
            return $this->redirect('/game/create-letter?listing_id=' . $listingId);
        }
        
        // Récupérer le ticket de l'utilisateur pour cette annonce
        $ticket = $this->gameModel->getTicketByUserAndListing($userId, $listingId);
        if (!$ticket) {
            $_SESSION['error_message'] = 'Ticket non trouvé.';
            return $this->redirect('/game/create-letter?listing_id=' . $listingId);
        }
        
        // Créer la lettre
        $letterData = [
            'user_id' => $userId,
            'listing_id' => $listingId,
            'ticket_id' => $ticket['id'],
            'contenu' => $contenu,
            'date_creation' => date('Y-m-d H:i:s'),
            'status' => 'soumise'
        ];
        
        $letterId = $this->gameModel->createLetter($letterData);
        
        if ($letterId) {
            $_SESSION['success_message'] = 'Votre lettre de motivation a été soumise avec succès !';
            return $this->redirect('/game/my-letters');
        } else {
            $_SESSION['error_message'] = 'Erreur lors de la soumission de votre lettre.';
            return $this->redirect('/game/create-letter?listing_id=' . $listingId);
        }
    }
    
    /**
     * Affiche les lettres de l'utilisateur
     */
    public function myLetters() {
        if (!$this->isUserLoggedIn()) {
            return $this->redirect('/auth/login');
        }
        
        $userId = $this->getCurrentUserId();
        $letters = $this->gameModel->getLettersByUserId($userId);
        
        // Calculer les statistiques
        $totalLetters = count($letters);
        $draftLetters = 0;
        $submittedLetters = 0;
        $evaluatedLetters = 0;
        $winnerLetters = 0;
        
        foreach ($letters as $letter) {
            switch ($letter['status']) {
                case 'brouillon':
                    $draftLetters++;
                    break;
                case 'soumise':
                    $submittedLetters++;
                    break;
                case 'evaluee':
                    $evaluatedLetters++;
                    break;
                case 'gagnante':
                    $winnerLetters++;
                    break;
            }
        }
        
        $data = [
            'title' => 'Mes lettres de motivation',
            'page' => 'letter-my-letters',
            'letters' => $letters,
            'totalLetters' => $totalLetters,
            'draftLetters' => $draftLetters,
            'submittedLetters' => $submittedLetters,
            'evaluatedLetters' => $evaluatedLetters,
            'winnerLetters' => $winnerLetters
        ];
        
        return $this->renderLayout('letter/my-letters', $data);
    }
    
    /**
     * Affiche une lettre spécifique
     */
    public function viewLetter() {
        if (!$this->isUserLoggedIn()) {
            return $this->redirect('/auth/login');
        }
        
        $letterId = $_GET['id'] ?? null;
        if (!$letterId) {
            return $this->redirect('/game/my-letters');
        }
        
        $userId = $this->getCurrentUserId();
        $letter = $this->gameModel->getLetterById($letterId);
        
        if (!$letter || $letter['user_id'] !== $userId) {
            return $this->redirect('/game/my-letters');
        }
        
        $listing = $this->listingModel->getById($letter['listing_id']);
        
        $data = [
            'title' => 'Ma lettre de motivation',
            'page' => 'view-letter',
            'letter' => $letter,
            'listing' => $listing
        ];
        
        return $this->renderLayout('letter/view', $data);
    }
}
