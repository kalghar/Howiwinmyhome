<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($data['description'] ?? 'How I Win My Home - Plateforme de concours immobiliers') ?>">

    <title><?= htmlspecialchars($data['title'] ?? 'How I Win My Home') ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">

    <!-- CSS de base -->
    <link rel="stylesheet" href="/assets/css/styles.css?v=<?= time() ?>">
    <link rel="stylesheet" href="/assets/css/components.css?v=<?= time() ?>">
    <link rel="stylesheet" href="/assets/css/header.css?v=<?= time() ?>">
    <link rel="stylesheet" href="/assets/css/footer.css?v=<?= time() ?>">
    <link rel="stylesheet" href="/assets/css/auth-modals.css?v=<?= time() ?>">

    <!-- CSS spécifique à la page -->
    <?php if (isset($data['page_css']) && !empty($data['page_css'])): ?>
        <?php foreach ($data['page_css'] as $cssFile): ?>
            <link rel="stylesheet" href="/assets/css/<?= htmlspecialchars($cssFile) ?>?v=<?= time() ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body class="page-<?= htmlspecialchars($data['page'] ?? 'default') ?>">
    <!-- Header -->
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <!-- Main Content -->
    <main id="main-content" class="main-content">
        <?php if (isset($viewContent)): ?>
            <?= $viewContent ?>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <?php include __DIR__ . '/../partials/footer.php'; ?>

    <!-- Modals d'authentification - uniquement si utilisateur non connecté -->
    <?php if (!isset($data['isLoggedIn']) || !$data['isLoggedIn']): ?>
        <?php include __DIR__ . '/../partials/auth-modals.php'; ?>
    <?php endif; ?>

    <!-- JavaScript de base -->
    <script src="/assets/js/app.js"></script>
    <script src="/assets/js/flash-messages.js"></script>

    <!-- JavaScript spécifique à la page -->
    <?php if (isset($data['page_js']) && !empty($data['page_js'])): ?>
        <?php foreach ($data['page_js'] as $jsFile): ?>
            <script src="/assets/js/<?= htmlspecialchars($jsFile) ?>?v=<?= time() ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- modal-simple.js chargé APRÈS les scripts de page -->
    <script src="/assets/js/modal-simple.js"></script>

    <!-- Messages flash -->
    <?php
    $flashMessages = $_SESSION['flash_messages'] ?? [];
    if (!empty($flashMessages)):
        foreach ($flashMessages as $message):
    ?>
            <div data-flash-message data-flash-type="<?= htmlspecialchars($message['type']) ?>" class="flash-message-hidden">
                <?= htmlspecialchars($message['message']) ?>
            </div>
    <?php
        endforeach;
        unset($_SESSION['flash_messages']);
    endif;
    ?>

    <!-- Scripts spécifiques aux pages -->
    <?php if (strpos($_SERVER['REQUEST_URI'], '/listings/create') !== false): ?>
        <script src="/assets/js/listing-create.js"></script>
    <?php endif; ?>
</body>

</html>