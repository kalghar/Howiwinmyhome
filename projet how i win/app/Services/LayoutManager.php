<?php

/**
 * GESTIONNAIRE DE LAYOUT SIMPLIFIÉ
 * HOW I WIN MY HOME - ARCHITECTURE MVC
 * ========================================
 *
 * Ce service gère le rendu des vues et layouts de manière simple
 * Parfait pour un examen : complet mais facile à expliquer
 *
 * @author How I Win My Home Team
 * @version 2.0.0 (Simplifié)
 * @since 2025-08-12
 */

class LayoutManager
{

    /**
     * Chemin de base des vues
     */
    private $viewsBasePath;

    /**
     * Chemin de base des partials
     */
    private $partialsBasePath;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->viewsBasePath = __DIR__ . '/../Views/';
        $this->partialsBasePath = __DIR__ . '/../Views/partials/';
    }

    /**
     * Rendu d'une vue simple avec les données fournies
     */
    public function renderView($viewPath, $data = []): string
    {
        // Extraire les variables pour les rendre accessibles dans la vue
        extract($data);

        // Vérifier l'existence de la vue
        $viewFile = $this->viewsBasePath . "{$viewPath}.php";

        if (!file_exists($viewFile)) {
            throw new Exception("Vue introuvable : {$viewPath}");
        }

        // Capture de sortie pour le rendu
        ob_start();

        try {
            include $viewFile;
            $content = ob_get_clean();

            if (empty(trim($content))) {
                throw new Exception("Le rendu de la vue a produit un contenu vide pour : {$viewPath}");
            }

            return $content;
        } catch (Exception $e) {
            ob_end_clean();
            throw $e;
        }
    }

    /**
     * Rendu d'un layout complet avec header, vue et footer
     */
    public function renderLayout($viewPath, $data = []): string
    {
        // Déterminer le nom de la page
        $pageName = $data['page'] ?? $this->getPageNameFromViewPath($viewPath);
        $data['page'] = $pageName;

        // Charger les assets appropriés
        $this->loadPageAssets($pageName, $data);

        // Rendu de la vue
        $viewContent = $this->renderView($viewPath, $data);

        // Rendu du layout complet
        ob_start();

        try {
            include $this->viewsBasePath . 'layouts/main.php';
            $content = ob_get_clean();

            if (empty(trim($content))) {
                throw new Exception("Le rendu du layout a produit un contenu vide pour : {$viewPath}");
            }

            return $content;
        } catch (Exception $e) {
            ob_end_clean();
            throw new Exception("Erreur lors du rendu du layout : " . $e->getMessage());
        }
    }

    /**
     * Rendu d'un partial (composant réutilisable)
     */
    public function renderPartial($partialName, $data = []): string
    {
        $partialFile = $this->partialsBasePath . "{$partialName}.php";

        if (!file_exists($partialFile)) {
            throw new Exception("Partial introuvable : {$partialName}");
        }

        ob_start();

        try {
            extract($data);
            include $partialFile;
            $content = ob_get_clean();

            if (empty(trim($content))) {
                throw new Exception("Le rendu du partial a produit un contenu vide pour : {$partialName}");
            }

            return $content;
        } catch (Exception $e) {
            ob_end_clean();
            throw $e;
        }
    }

    /**
     * Vérifie si une vue existe
     */
    public function viewExists($viewPath): bool
    {
        $viewFile = $this->viewsBasePath . "{$viewPath}.php";
        return file_exists($viewFile);
    }

    /**
     * Vérifie si un partial existe
     */
    public function partialExists($partialName): bool
    {
        $partialFile = $this->partialsBasePath . "{$partialName}.php";
        return file_exists($partialFile);
    }

    /**
     * Détermine le nom de la page à partir du chemin de la vue
     */
    private function getPageNameFromViewPath($viewPath): string
    {
        $parts = explode('/', $viewPath);

        // Détection spéciale pour les pages admin
        if ($parts[0] === 'admin' && isset($parts[1])) {
            return 'admin-' . $parts[1];
        }

        // Retourner le premier segment par défaut
        return $parts[0] ?? 'home';
    }

    /**
     * Charge les assets appropriés pour une page donnée
     */
    private function loadPageAssets(string $pageName, array &$data): void
    {
        // Assets par page (complet et cohérent)
        $pageAssets = [
            'listings' => ['css' => ['listings.css'], 'js' => ['listings.js', 'image-carousel.js']],
            'listing-view' => ['css' => ['listings.css'], 'js' => ['listings.js', 'image-carousel.js']],
            'listing-create' => ['css' => ['listing-create.css'], 'js' => ['listing-create.js']],
            'my-listings' => ['css' => ['my-listings.css'], 'js' => ['my-listings.js']],
            'dashboard' => ['css' => ['dashboard.css'], 'js' => ['dashboard.js']],
            'account-deposit' => ['css' => ['account.css'], 'js' => ['account.js']],
            'account-history' => ['css' => ['account.css'], 'js' => ['account.js']],
            'admin' => ['css' => ['admin.css', 'admin-documents.css'], 'js' => ['admin.js']],
            'admin-listings' => ['css' => ['admin.css', 'admin-documents.css'], 'js' => ['admin.js']],
            'admin-listing-detail' => ['css' => ['admin.css', 'admin-documents.css', 'admin-listing-detail.css'], 'js' => ['admin.js', 'admin-listing-detail.js']],
            'admin-pending-listings' => ['css' => ['admin.css', 'admin-documents.css', 'admin-pending-listings.css'], 'js' => ['admin.js', 'admin-pending-listings.js']],
            'admin-users' => ['css' => ['admin.css', 'admin-documents.css'], 'js' => ['admin.js']],
            'admin-settings' => ['css' => ['admin.css', 'admin-documents.css'], 'js' => ['admin.js']],
            'admin-documents' => ['css' => ['admin-documents.css'], 'js' => ['admin-documents.js']],
            'admin-reports' => ['css' => ['admin-reports.css'], 'js' => ['admin-reports.js']],
            'auth' => ['css' => ['auth-modals.css'], 'js' => []],
            'home' => ['css' => ['home.css', 'auth-modals.css'], 'js' => ['home.js', 'image-carousel.js']],
            'profile' => ['css' => ['profile.css'], 'js' => ['profile.js']],
            'my-tickets' => ['css' => ['my-tickets.css'], 'js' => ['my-tickets.js']],
            'buy-ticket' => ['css' => ['ticket-buy.css'], 'js' => ['ticket-buy.js']],
            'qcm' => ['css' => ['qcm.css'], 'js' => ['qcm.js']],
            'qcm-results' => ['css' => ['qcm.css'], 'js' => ['qcm-results.js']],
            'letter-create' => ['css' => ['letter.css'], 'js' => ['letter.js']],
            'letter-my-letters' => ['css' => ['letter.css', 'my-letters.css'], 'js' => ['my-letters.js']],
            'view-letter' => ['css' => ['letter.css'], 'js' => ['letter.js']],
            'contact' => ['css' => ['contact.css'], 'js' => ['contact.js']],
            'faq' => ['css' => ['faq.css']],
            'how-it-works' => ['css' => ['how-it-works.css']]
        ];

        // Ajouter les assets de la page aux données
        if (isset($pageAssets[$pageName])) {
            $data['page_css'] = $pageAssets[$pageName]['css'] ?? [];
            $data['page_js'] = $pageAssets[$pageName]['js'] ?? [];
            error_log("LayoutManager: Assets chargés pour page '$pageName': CSS=" . implode(',', $data['page_css']) . " JS=" . implode(',', $data['page_js']));
        } else {
            $data['page_css'] = [];
            $data['page_js'] = [];
            error_log("LayoutManager: Aucun asset trouvé pour page '$pageName'");
        }

        // Assets principaux (toujours chargés via le layout)
        $data['main_css'] = ['styles.css', 'components.css', 'header.css', 'footer.css'];
        $data['main_js'] = ['app.js', 'flash-messages.js'];
    }
}
