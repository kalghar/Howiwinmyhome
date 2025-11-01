<?php

/**
 * Contrôleur de la page d'accueil - HOW I WIN MY HOME V1
 * 
 * Gère l'affichage de la page d'accueil et des informations générales
 * Hérite de BaseController pour utiliser les méthodes communes
 */

// Includes supprimés - gérés par l'autoloader

class HomeController extends BaseController
{

    // Propriétés pour optimiser les instanciations répétitives
    private $listingModel;
    private $gameModel;

    /**
     * Vérifie si une redirection admin est nécessaire
     * @return bool True si redirection nécessaire
     */
    public static function needsAdminRedirect()
    {
        if (
            session_status() === PHP_SESSION_ACTIVE &&
            isset($_SESSION['user_logged_in']) &&
            $_SESSION['user_logged_in'] === true &&
            isset($_SESSION['user_role']) &&
            $_SESSION['user_role'] === 'admin'
        ) {
            return true;
        }
        return false;
    }

    /**
     * Constructeur - initialise les modèles une seule fois
     */
    public function __construct()
    {
        parent::__construct();
        $this->listingModel = new Listing();
        $this->gameModel = new Game();
    }

    /**
     * Affiche la page d'accueil
     * @return string HTML de la page d'accueil
     */
    public function index()
    {
        // Vérification de redirection admin
        if ($this->isUserLoggedIn() && $this->hasRole('admin')) {
            $this->redirect('/admin');
            return '';
        }

        try {
            // Récupérer les annonces actives récentes
            $recentListings = $this->listingModel->getRecent(6) ?: [];

            // Récupérer les statistiques générales
            $stats = $this->getHomeStats();

            // Récupérer les témoignages ou succès récents
            $successStories = $this->getSuccessStories();

            $data = [
                'title' => 'How I Win My Home - Gagnez votre bien immobilier !',
                'page' => 'home',
                'page_css' => ['home.css'],
                'page_js' => ['image-carousel.js'],
                'recentListings' => $recentListings,
                'stats' => $stats,
                'successStories' => $successStories,
                'isLoggedIn' => $this->isUserLoggedIn(),
                'userRole' => $this->getCurrentUserRole()
            ];

            return $this->renderLayout('home/index', $data);
        } catch (Exception $e) {
            error_log("Erreur lors de l'affichage de la page d'accueil: " . $e->getMessage());
            return $this->renderLayout('errors/error', [
                'title' => 'Erreur - How I Win My Home',
                'message' => 'Impossible de charger la page d\'accueil'
            ]);
        }
    }

    /**
     * Affiche la page "À propos"
     * @return string HTML de la page à propos
     */
    public function about()
    {
        $data = [
            'title' => 'À propos - How I Win My Home',
            'page' => 'about',
            'isLoggedIn' => $this->isUserLoggedIn()
        ];

        return $this->renderLayout('home/about', $data);
    }

    /**
     * Affiche la page "Comment ça marche"
     * @return string HTML de la page explicative
     */
    public function howItWorks()
    {
        $data = [
            'title' => 'Comment ça marche - How I Win My Home',
            'page' => 'how-it-works',
            'isLoggedIn' => $this->isUserLoggedIn(),
            'steps' => $this->getHowItWorksSteps()
        ];

        return $this->renderLayout('home/how-it-works', $data);
    }

    /**
     * Affiche la page FAQ
     * @return string HTML de la page FAQ
     */
    public function faq()
    {
        $data = [
            'title' => 'FAQ - Questions fréquemment posées - How I Win My Home',
            'page' => 'faq',
            'isLoggedIn' => $this->isUserLoggedIn(),
            'faqs' => $this->getFaqData()
        ];

        return $this->renderLayout('home/faq', $data);
    }

    /**
     * Affiche la page des conditions générales
     * @return string HTML des conditions générales
     */
    public function terms()
    {
        $data = [
            'title' => 'Conditions générales - How I Win My Home',
            'page' => 'terms',
            'isLoggedIn' => $this->isUserLoggedIn()
        ];

        return $this->renderLayout('home/terms', $data);
    }

    /**
     * Affiche la page de politique de confidentialité
     * @return string HTML de la politique de confidentialité
     */
    public function privacy()
    {
        $data = [
            'title' => 'Politique de confidentialité - How I Win My Home',
            'page' => 'privacy',
            'isLoggedIn' => $this->isUserLoggedIn()
        ];

        return $this->renderLayout('home/privacy', $data);
    }

    /**
     * Affiche la page de contact
     * @return string HTML de la page de contact
     */
    public function contact()
    {
        // Traiter la soumission du formulaire de contact
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->processContactForm();
        }

        $data = [
            'title' => 'Contact - How I Win My Home',
            'page' => 'contact',
            'isLoggedIn' => $this->isUserLoggedIn(),
            'errors' => [],
            'old' => []
        ];

        return $this->renderLayout('home/contact', $data);
    }

    /**
     * Traite le formulaire de contact
     * @return string Redirection ou message d'erreur
     */
    private function processContactForm()
    {
        try {
            // Validation des données avec InputValidator
            $rules = [
                'nom' => ['required', 'min:2', 'max:50'],
                'email' => ['required', 'email'],
                'sujet' => ['required', 'min:5', 'max:100'],
                'message' => ['required', 'min:10', 'max:1000']
            ];

            [$validatedData, $validationErrors] = \App\Validators\InputValidator::validatePost($rules);

            if (!empty($validationErrors)) {
                return $this->renderLayout('home/contact', [
                    'title' => 'Contact - How I Win My Home',
                    'page' => 'contact',
                    'isLoggedIn' => $this->isUserLoggedIn(),
                    'errors' => $validationErrors,
                    'old' => $validatedData
                ]);
            }

            // Traitement du message de contact
            $contactData = [
                'nom' => \App\Validators\InputValidator::sanitizeString($validatedData['nom']),
                'email' => \App\Validators\InputValidator::sanitizeEmail($validatedData['email']),
                'sujet' => \App\Validators\InputValidator::sanitizeString($validatedData['sujet']),
                'message' => \App\Validators\InputValidator::sanitizeString($validatedData['message']),
                'date_contact' => date('Y-m-d H:i:s')
            ];

            // Envoyer un email de notification (simulation)
            $this->sendContactNotification($contactData);

            // CORRECTION : Message flash supprimé
            return $this->redirect('/contact');
        } catch (Exception $e) {
            error_log("Erreur lors du traitement du formulaire de contact: " . $e->getMessage());
            // CORRECTION : Message flash supprimé
            return $this->renderLayout('home/contact', [
                'title' => 'Contact - How I Win My Home',
                'page' => 'contact',
                'isLoggedIn' => $this->isUserLoggedIn(),
                'errors' => ['general' => 'Une erreur s\'est produite'],
                'old' => $_POST
            ]);
        }
    }

    // ========================================
    // MÉTHODES PRIVÉES ET UTILITAIRES
    // ========================================

    /**
     * Obtient les statistiques de la page d'accueil
     * @return array Statistiques générales
     */
    private function getHomeStats()
    {
        try {
            $stats = [
                'total_listings' => 0,
                'total_winners' => 0,
                'active_contests' => 0
            ];

            // Compter les annonces
            $listings = $this->listingModel->getAll() ?: [];
            $stats['total_listings'] = count($listings);

            // Compter les concours actifs
            $activeListings = array_filter($listings, function ($listing) {
                return $listing['status'] === 'active' &&
                    (!isset($listing['end_date']) || strtotime($listing['end_date']) > time());
            });
            $stats['active_contests'] = count($activeListings);

            // Compter les gagnants
            $winnerLetters = array_filter($listings, function ($listing) {
                return $listing['status'] === 'completed' && isset($listing['winner_user_id']);
            });
            $stats['total_winners'] = count($winnerLetters);

            return $stats;
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des stats d'accueil: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtient les histoires de succès
     * @return array Histoires de succès
     */
    private function getSuccessStories()
    {
        try {
            // Pour la V1, retourner des histoires simulées
            // Dans une version future, ceci pourrait être stocké en base
            return [
                [
                    'id' => 1,
                    'nom' => 'Marie D.',
                    'ville' => 'Lyon',
                    'bien' => 'Appartement 3 pièces',
                    'message' => 'Grâce à How I Win My Home, j\'ai pu réaliser mon rêve d\'accession à la propriété !',
                    'date' => '2024-12-15',
                    'note' => 5
                ],
                [
                    'id' => 2,
                    'nom' => 'Pierre L.',
                    'ville' => 'Marseille',
                    'bien' => 'Maison avec jardin',
                    'message' => 'Un concept innovant qui m\'a permis d\'acheter ma maison à un prix accessible.',
                    'date' => '2024-11-20',
                    'note' => 5
                ],
                [
                    'id' => 3,
                    'nom' => 'Sophie M.',
                    'ville' => 'Toulouse',
                    'bien' => 'Appartement 2 pièces',
                    'message' => 'Je recommande vivement cette plateforme ! Processus simple et transparent.',
                    'date' => '2024-10-10',
                    'note' => 5
                ]
            ];
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des histoires de succès: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtient les étapes du processus
     * @return array Étapes du processus
     */
    private function getHowItWorksSteps()
    {
        return [
            [
                'step' => 1,
                'title' => 'Inscription gratuite',
                'description' => 'Créez votre compte en quelques clics et choisissez votre rôle : acheteur ou vendeur.',
                'icon' => 'user-plus',
                'duration' => '2 minutes'
            ],
            [
                'step' => 2,
                'title' => 'Parcourez les annonces',
                'description' => 'Découvrez notre sélection de biens immobiliers disponibles pour le concours.',
                'icon' => 'search',
                'duration' => 'Quelques minutes'
            ],
            [
                'step' => 3,
                'title' => 'Achetez votre ticket',
                'description' => 'Participez au concours en achetant un ticket pour le bien de votre choix.',
                'icon' => 'ticket',
                'duration' => '5 minutes'
            ],
            [
                'step' => 4,
                'title' => 'Passez le QCM',
                'description' => 'Répondez à un questionnaire de qualification pour continuer votre participation.',
                'icon' => 'question-circle',
                'duration' => '5 minutes'
            ],
            [
                'step' => 5,
                'title' => 'Rédigez votre lettre',
                'description' => 'Écrivez une lettre de motivation pour convaincre notre jury.',
                'icon' => 'edit',
                'duration' => '15-30 minutes'
            ],
            [
                'step' => 6,
                'title' => 'Attendez le résultat',
                'description' => 'Notre jury évalue toutes les lettres et sélectionne le gagnant final.',
                'icon' => 'clock',
                'duration' => '7-14 jours'
            ]
        ];
    }

    /**
     * Valide les données du formulaire de contact
     * @param array $data Données du formulaire
     * @return array Résultat de la validation
     */
    private function validateContactData($data)
    {
        $errors = [];

        // Validation du nom
        if (empty($data['nom'])) {
            $errors['nom'] = 'Le nom est obligatoire';
        } elseif (strlen($data['nom']) < 2) {
            $errors['nom'] = 'Le nom doit contenir au moins 2 caractères';
        } elseif (strlen($data['nom']) > 50) {
            $errors['nom'] = 'Le nom ne peut pas dépasser 50 caractères';
        }

        // Validation de l'email
        if (empty($data['email'])) {
            $errors['email'] = 'L\'email est obligatoire';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format d\'email invalide';
        }

        // Validation du sujet
        if (empty($data['sujet'])) {
            $errors['sujet'] = 'Le sujet est obligatoire';
        } elseif (strlen($data['sujet']) < 5) {
            $errors['sujet'] = 'Le sujet doit contenir au moins 5 caractères';
        } elseif (strlen($data['sujet']) > 100) {
            $errors['sujet'] = 'Le sujet ne peut pas dépasser 100 caractères';
        }

        // Validation du message
        if (empty($data['message'])) {
            $errors['message'] = 'Le message est obligatoire';
        } elseif (strlen($data['message']) < 10) {
            $errors['message'] = 'Le message doit contenir au moins 10 caractères';
        } elseif (strlen($data['message']) > 1000) {
            $errors['message'] = 'Le message ne peut pas dépasser 1000 caractères';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'message' => empty($errors) ? '' : 'Veuillez corriger les erreurs'
        ];
    }

    /**
     * Envoie une notification de contact
     * @param array $contactData Données du contact
     */
    private function sendContactNotification($contactData)
    {
        try {
            // Pour la V1, simulation de l'envoi d'email
            // Dans une version future, ceci enverrait un vrai email
            $emailHelper = new EmailHelper();
            $emailHelper->sendContactNotificationEmail(
                $contactData['email'],
                $contactData['nom'],
                $contactData['sujet'],
                $contactData['message']
            );

            // Log du contact
            error_log("Nouveau message de contact de {$contactData['nom']} ({$contactData['email']}) : {$contactData['sujet']}");
        } catch (Exception $e) {
            error_log("Erreur lors de l'envoi de la notification de contact: " . $e->getMessage());
        }
    }

    /**
     * Obtient les questions fréquemment posées
     * @return array FAQ
     */
    private function getFaqData()
    {
        return [
            [
                'question' => 'Comment fonctionne le concours ?',
                'reponse' => 'Le concours se déroule en plusieurs étapes : achat de ticket, passage d\'un QCM, rédaction d\'une lettre de motivation, puis évaluation par notre jury qui sélectionne le gagnant final.'
            ],
            [
                'question' => 'Combien coûte un ticket ?',
                'reponse' => 'Le prix du ticket varie selon le bien immobilier. Il est fixé par le vendeur et généralement compris entre 5€ et 20€.'
            ],
            [
                'question' => 'Quelles sont mes chances de gagner ?',
                'reponse' => 'Vos chances dépendent du nombre de participants et de la qualité de votre lettre de motivation. Plus vous vous démarquez, plus vos chances augmentent !'
            ],
            [
                'question' => 'Puis-je participer à plusieurs concours ?',
                'reponse' => 'Oui, vous pouvez acheter des tickets pour plusieurs biens immobiliers différents. Chaque concours est indépendant.'
            ],
            [
                'question' => 'Que se passe-t-il si je gagne ?',
                'reponse' => 'Si vous gagnez, vous devenez propriétaire du bien immobilier au prix du ticket ! Notre équipe vous accompagne dans toutes les démarches administratives.'
            ],
            [
                'question' => 'Le concours est-il légal ?',
                'reponse' => 'Absolument ! Notre plateforme respecte strictement la législation française sur les jeux de hasard et les concours. Tous nos processus sont validés par des experts juridiques.'
            ]
        ];
    }
}
