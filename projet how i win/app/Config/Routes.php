<?php
/**
 * Configuration des routes de l'application
 * HOW I WIN MY HOME V1 - SIMPLIFIÉ
 * 
 * Ce fichier définit toutes les routes de l'application
 * selon l'architecture MVC simplifiée
 */

return [
    // Routes publiques
    '' => ['controller' => 'HomeController', 'action' => 'index'],
    'home' => ['controller' => 'HomeController', 'action' => 'index'],
    
    // Routes d'authentification
    'auth/login' => ['controller' => 'AuthController', 'action' => 'login'],
    'auth/register' => ['controller' => 'AuthController', 'action' => 'register'],
    'auth/process-login' => ['controller' => 'AuthController', 'action' => 'processLogin'],
    'auth/process-register' => ['controller' => 'AuthController', 'action' => 'processRegister'],
    'logout' => ['controller' => 'AuthController', 'action' => 'logout'],
    'forgot-password' => ['controller' => 'AuthController', 'action' => 'forgotPassword'],
    
    // Routes des annonces
    'listings' => ['controller' => 'ListingController', 'action' => 'index'],
    'listings/view' => ['controller' => 'ListingController', 'action' => 'view'],
    'listings/view/{id}' => ['controller' => 'ListingController', 'action' => 'view'],
    'listings/create' => ['controller' => 'ListingController', 'action' => 'create'],
    'listings/store' => ['controller' => 'ListingController', 'action' => 'store'],
    'listings/my-listings' => ['controller' => 'ListingController', 'action' => 'myListings'],
    'listings/delete' => ['controller' => 'ListingController', 'action' => 'delete'],
    'listings/delete/{id}' => ['controller' => 'ListingController', 'action' => 'delete'],
    'listings/search' => ['controller' => 'ListingController', 'action' => 'search'],
    
    // Routes du jeu (tickets, QCM, lettres)
    'game/buy-ticket' => ['controller' => 'GameController', 'action' => 'buyTicket'],
    'game/process-ticket-purchase' => ['controller' => 'GameController', 'action' => 'processTicketPurchase'],
    'ticket/process-purchase' => ['controller' => 'GameController', 'action' => 'processTicketPurchase'],
    'game/my-tickets' => ['controller' => 'GameController', 'action' => 'myTickets'],
    'game/qcm' => ['controller' => 'GameController', 'action' => 'qcm'],
    'game/process-qcm-answers' => ['controller' => 'GameController', 'action' => 'processQcmAnswers'],
    'game/qcm-results' => ['controller' => 'GameController', 'action' => 'qcmResults'],
    'game/create-letter' => ['controller' => 'GameController', 'action' => 'createLetter'],
    'game/process-letter' => ['controller' => 'GameController', 'action' => 'processLetter'],
    'game/my-letters' => ['controller' => 'GameController', 'action' => 'myLetters'],
    'game/view-letter' => ['controller' => 'GameController', 'action' => 'viewLetter'],
    
    // Routes d'administration (regroupées)
    'admin' => ['controller' => 'AdminController', 'action' => 'index'],
    'admin/users' => ['controller' => 'AdminController', 'action' => 'users'],
    'admin/update-user-status' => ['controller' => 'AdminController', 'action' => 'updateUserStatus'],
    'admin/listings' => ['controller' => 'AdminController', 'action' => 'allListings'],
    'admin/listing/{id}' => ['controller' => 'AdminController', 'action' => 'viewListing'],
    'admin/pending-listings' => ['controller' => 'AdminController', 'action' => 'pendingListings'],
    'admin/approve-listing' => ['controller' => 'AdminController', 'action' => 'approveListing'],
    'admin/approve-listing/{id}' => ['controller' => 'AdminController', 'action' => 'approveListing'],
    'admin/reject-listing' => ['controller' => 'AdminController', 'action' => 'rejectListing'],
    'admin/reject-listing/{id}' => ['controller' => 'AdminController', 'action' => 'rejectListing'],
    'admin/update-listing-status' => ['controller' => 'AdminController', 'action' => 'updateListingStatus'],
    'admin/settings' => ['controller' => 'AdminController', 'action' => 'settings'],
    'admin/update-settings' => ['controller' => 'AdminController', 'action' => 'updateSettings'],
    'admin/reports' => ['controller' => 'AdminController', 'action' => 'reports'],
    'admin/documents' => ['controller' => 'AdminController', 'action' => 'documents'],
    'admin/documents/view/{id}' => ['controller' => 'AdminController', 'action' => 'viewDocument'],
    'admin/documents/download/{id}' => ['controller' => 'AdminController', 'action' => 'downloadDocument'],
    'admin/documents/verify/{id}' => ['controller' => 'AdminController', 'action' => 'verifyDocument'],
    'admin/documents/reject/{id}' => ['controller' => 'AdminController', 'action' => 'rejectDocument'],
    
    // Routes des documents (simplifiées)
    'documents' => ['controller' => 'DocumentController', 'action' => 'index'],
    'documents/upload' => ['controller' => 'DocumentController', 'action' => 'upload'],
    'documents/process-upload' => ['controller' => 'DocumentController', 'action' => 'processUpload'],
    'documents/view/{id}' => ['controller' => 'DocumentController', 'action' => 'view'],
    'documents/download/{id}' => ['controller' => 'DocumentController', 'action' => 'download'],
    'documents/delete/{id}' => ['controller' => 'DocumentController', 'action' => 'delete'],
    'admin/jury' => ['controller' => 'AdminController', 'action' => 'jury'],
    'admin/jury/evaluate-letters' => ['controller' => 'AdminController', 'action' => 'evaluateLetters'],
    'admin/jury/select-winner' => ['controller' => 'AdminController', 'action' => 'selectWinner'],
    'admin/jury/results' => ['controller' => 'AdminController', 'action' => 'juryResults'],
    'admin/jury/evaluate-letter/{id}' => ['controller' => 'AdminController', 'action' => 'evaluateLetter'],
    'admin/jury/process-winner-selection' => ['controller' => 'AdminController', 'action' => 'processWinnerSelection'],
    
    // Routes du tableau de bord utilisateur
    'dashboard' => ['controller' => 'DashboardController', 'action' => 'index'],
    'profile' => ['controller' => 'DashboardController', 'action' => 'profile'], // Alias pour /dashboard/profile
    'dashboard/profile' => ['controller' => 'DashboardController', 'action' => 'profile'],
    'dashboard/change-password' => ['controller' => 'DashboardController', 'action' => 'changePassword'],
    'dashboard/process-password-change' => ['controller' => 'DashboardController', 'action' => 'processPasswordChange'],
    'dashboard/update-profile' => ['controller' => 'DashboardController', 'action' => 'updateProfile'],
    'dashboard/delete-account' => ['controller' => 'DashboardController', 'action' => 'deleteAccount'],
    
        // Routes du compte utilisateur
        'account/deposit' => ['controller' => 'AccountController', 'action' => 'deposit'],
        'account/process-deposit' => ['controller' => 'AccountController', 'action' => 'processDeposit'],
        'account/history' => ['controller' => 'AccountController', 'action' => 'history'],
    
    // Routes des pages statiques
    'about' => ['controller' => 'HomeController', 'action' => 'about'],
    'how-it-works' => ['controller' => 'HomeController', 'action' => 'howItWorks'],
    'faq' => ['controller' => 'HomeController', 'action' => 'faq'],
    'terms' => ['controller' => 'HomeController', 'action' => 'terms'],
    'privacy' => ['controller' => 'HomeController', 'action' => 'privacy'],
    'contact' => ['controller' => 'HomeController', 'action' => 'contact'],
];