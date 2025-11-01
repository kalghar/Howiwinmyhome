<?php
/**
 * Contrôleur des comptes utilisateur - HOW I WIN MY HOME V1
 * 
 * Gère les pages de dépôt et d'historique des comptes utilisateur
 */

// L'autoloader gère automatiquement les dépendances

class AccountController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        // Appeler le constructeur parent pour initialiser les services
        parent::__construct();
        
        // Initialiser le modèle utilisateur
        $this->userModel = new User();
    }

    /**
     * Page de dépôt
     */
    public function deposit()
    {
        // Vérifier la connexion
        if (!$this->isUserLoggedIn()) {
            $this->redirect('/auth/login');
            return;
        }

        $userId = $_SESSION['user_id'] ?? null;
        $user = $this->userModel->getById($userId);
        $userBalance = $user['balance'] ?? 0;

        $data = [
            'title' => 'Recharger mon compte - How I Win My Home',
            'page' => 'account-deposit',
            'userBalance' => $userBalance,
            'isLoggedIn' => $this->isUserLoggedIn()
        ];

        return $this->renderLayout('account/deposit', $data);
    }


    /**
     * Traitement du dépôt
     */
    public function processDeposit()
    {
        // Vérifier la connexion
        if (!$this->isUserLoggedIn()) {
            $this->redirect('/auth/login');
            return;
        }

        $userId = $_SESSION['user_id'] ?? null;
        $amount = floatval($_POST['amount'] ?? 0);
        $errors = [];

        // Validation
        if ($amount <= 0) {
            $errors[] = 'Le montant doit être supérieur à 0€';
        }

        if ($amount > 1000) {
            $errors[] = 'Le montant maximum par dépôt est de 1000€';
        }

        if (empty($errors)) {
            // Mettre à jour le solde
            $success = $this->userModel->addBalance($userId, $amount);

            if ($success) {
                // Log de l'opération
                error_log("Dépôt effectué - User ID: $userId, Montant: $amount");

                $_SESSION['success_message'] = "Dépôt de " . number_format($amount, 0, ',', ' ') . "€ effectué avec succès !";
                $this->redirect('/listings');
                return;
            } else {
                $errors[] = 'Erreur lors du dépôt. Veuillez réessayer.';
            }
        }

        // En cas d'erreur, stocker les erreurs et rediriger vers la page de dépôt
        $_SESSION['error_message'] = implode('<br>', $errors);
        $_SESSION['form_data'] = ['amount' => $amount];
        $this->redirect('/account/deposit');
    }

    /**
     * Historique des transactions
     */
    public function history()
    {
        // Vérifier la connexion
        if (!$this->isUserLoggedIn()) {
            $this->redirect('/auth/login');
            return;
        }

        $data = [
            'title' => 'Historique des transactions - How I Win My Home',
            'page' => 'account-history',
            'isLoggedIn' => $this->isUserLoggedIn()
        ];

        return $this->renderLayout('account/history', $data);
    }
}