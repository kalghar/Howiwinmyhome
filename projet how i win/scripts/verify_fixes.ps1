# Script de vérification post-fix pour How I Win My Home (Windows PowerShell)
# Vérifie que toutes les corrections ont été appliquées correctement

Write-Host "🔍 VÉRIFICATION POST-FIX - HOW I WIN MY HOME" -ForegroundColor Cyan
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host ""

# Configuration
$ERRORS = 0
$WARNINGS = 0

# Fonction pour compter les erreurs
function Count-Error {
    param($message)
    $script:ERRORS++
    Write-Host "   ❌ ERREUR: $message" -ForegroundColor Red
}

# Fonction pour compter les avertissements
function Count-Warning {
    param($message)
    $script:WARNINGS++
    Write-Host "   ⚠️  AVERTISSEMENT: $message" -ForegroundColor Yellow
}

# Fonction pour afficher le succès
function Show-Success {
    param($message)
    Write-Host "   ✅ SUCCESS: $message" -ForegroundColor Green
}

Write-Host "📋 VÉRIFICATION DES CORRECTIONS BLOQUANTES" -ForegroundColor Magenta
Write-Host "==========================================" -ForegroundColor Magenta

# 1. Vérifier que les fichiers sensibles ont été supprimés
Write-Host "🔍 Fichiers sensibles supprimés..." -ForegroundColor White
$sensitiveFiles = @(
    "public/reset_admin_password.php",
    "public/index.php.backup",
    "cookies.txt"
)

foreach ($file in $sensitiveFiles) {
    if (Test-Path $file) {
        Count-Error "Fichier sensible encore présent: $file"
    } else {
        Show-Success "Fichier sensible supprimé: $file"
    }
}

# 2. Vérifier que le .gitignore des uploads existe
Write-Host "🔍 Protection des uploads..." -ForegroundColor White
if (Test-Path "public/uploads/.gitignore") {
    Show-Success "Fichier .gitignore présent dans uploads"
} else {
    Count-Error "Fichier .gitignore manquant dans uploads"
}

# 3. Vérifier que les méthodes manquantes ont été ajoutées
Write-Host "🔍 Méthodes manquantes ajoutées..." -ForegroundColor White

# AdminController
if (Select-String -Path "app/Controllers/AdminController.php" -Pattern "function getAdminStats" -Quiet) {
    Show-Success "AdminController::getAdminStats() ajoutée"
} else {
    Count-Error "AdminController::getAdminStats() manquante"
}

if (Select-String -Path "app/Controllers/AdminController.php" -Pattern "function getPendingListings" -Quiet) {
    Show-Success "AdminController::getPendingListings() ajoutée"
} else {
    Count-Error "AdminController::getPendingListings() manquante"
}

# User Model
if (Select-String -Path "app/Models/User.php" -Pattern "function getTotalCount" -Quiet) {
    Show-Success "User::getTotalCount() ajoutée"
} else {
    Count-Error "User::getTotalCount() manquante"
}

# Listing Model
if (Select-String -Path "app/Models/Listing.php" -Pattern "function getTotalCount" -Quiet) {
    Show-Success "Listing::getTotalCount() ajoutée"
} else {
    Count-Error "Listing::getTotalCount() manquante"
}

Write-Host ""

Write-Host "📋 VÉRIFICATION DES CORRECTIONS CRITIQUES" -ForegroundColor Magenta
Write-Host "=========================================" -ForegroundColor Magenta

# 4. Vérifier que les erreurs SQL ont été corrigées
Write-Host "🔍 Erreurs SQL corrigées..." -ForegroundColor White

# Ticket Model
if (Select-String -Path "app/Models/Ticket.php" -Pattern "\$db->execute" -Quiet) {
    Count-Error "Ticket.php contient encore `$db->execute"
} else {
    Show-Success "Ticket.php corrigé (plus de `$db->execute)"
}

# Letter Model
if (Select-String -Path "app/Models/Letter.php" -Pattern "\$db->execute" -Quiet) {
    Count-Error "Letter.php contient encore `$db->execute"
} else {
    Show-Success "Letter.php corrigé (plus de `$db->execute)"
}

# 5. Vérifier que les validations sont harmonisées
Write-Host "🔍 Validations harmonisées..." -ForegroundColor White
if (Test-Path "public/assets/js/validation-rules.js") {
    Show-Success "Fichier validation-rules.js créé"
} else {
    Count-Error "Fichier validation-rules.js manquant"
}

if (Test-Path "public/assets/js/real-time-validation.js") {
    Show-Success "Fichier real-time-validation.js créé"
} else {
    Count-Error "Fichier real-time-validation.js manquant"
}

Write-Host ""

Write-Host "📋 VÉRIFICATION DES CORRECTIONS MAJEURES" -ForegroundColor Magenta
Write-Host "========================================" -ForegroundColor Magenta

# 6. Vérifier que les messages flash ont été supprimés
Write-Host "🔍 Messages flash supprimés..." -ForegroundColor White

# BaseController
if (Select-String -Path "app/Controllers/BaseController.php" -Pattern "setFlashMessage" -Quiet) {
    Count-Warning "BaseController contient encore setFlashMessage"
} else {
    Show-Success "BaseController: setFlashMessage supprimé"
}

# AdminController
if (Select-String -Path "app/Controllers/AdminController.php" -Pattern "setFlashMessage" -Quiet) {
    Count-Warning "AdminController contient encore setFlashMessage"
} else {
    Show-Success "AdminController: setFlashMessage supprimé"
}

# 7. Vérifier que la gestion d'erreurs a été améliorée
Write-Host "🔍 Gestion d'erreurs améliorée..." -ForegroundColor White
if (Select-String -Path "app/Controllers/BaseController.php" -Pattern "function logError" -Quiet) {
    Show-Success "BaseController: logError() ajoutée"
} else {
    Count-Error "BaseController: logError() manquante"
}

if (Select-String -Path "app/Controllers/BaseController.php" -Pattern "function handleException" -Quiet) {
    Show-Success "BaseController: handleException() ajoutée"
} else {
    Count-Error "BaseController: handleException() manquante"
}

Write-Host ""

Write-Host "📋 VÉRIFICATION DES CORRECTIONS MINEURES" -ForegroundColor Magenta
Write-Host "========================================" -ForegroundColor Magenta

# 8. Vérifier que la documentation a été créée
Write-Host "🔍 Documentation créée..." -ForegroundColor White
$docs = @(
    "docs/API_CONTROLLERS.md",
    "docs/API_MODELS.md",
    "docs/VIEWS_STRUCTURE.md",
    "analysis/fix_progress.md",
    "ai_acknowledgements.md"
)

foreach ($doc in $docs) {
    if (Test-Path $doc) {
        Show-Success "Documentation créée: $doc"
    } else {
        Count-Error "Documentation manquante: $doc"
    }
}

# 9. Vérifier que les méthodes communes ont été ajoutées
Write-Host "🔍 Méthodes communes ajoutées..." -ForegroundColor White
if (Select-String -Path "app/Controllers/BaseController.php" -Pattern "function requireAdminRole" -Quiet) {
    Show-Success "BaseController: requireAdminRole() ajoutée"
} else {
    Count-Error "BaseController: requireAdminRole() manquante"
}

if (Select-String -Path "app/Controllers/BaseController.php" -Pattern "function requireLogin" -Quiet) {
    Show-Success "BaseController: requireLogin() ajoutée"
} else {
    Count-Error "BaseController: requireLogin() manquante"
}

Write-Host ""

Write-Host "📋 VÉRIFICATION DES SÉCURITÉS" -ForegroundColor Magenta
Write-Host "=============================" -ForegroundColor Magenta

# 10. Vérifier que les commentaires de sécurité PROD ont été ajoutés
Write-Host "🔍 Commentaires sécurité PROD..." -ForegroundColor White
if (Select-String -Path "app/Config/Config.php" -Pattern "Sécurité PROD" -Quiet) {
    Show-Success "Commentaires sécurité PROD ajoutés dans Config.php"
} else {
    Count-Warning "Commentaires sécurité PROD manquants dans Config.php"
}

# 11. Vérifier que les tokens CSRF sont présents
Write-Host "🔍 Tokens CSRF..." -ForegroundColor White
$csrfFiles = Get-ChildItem -Path "app/Views" -Recurse -Filter "*.php" | Where-Object { 
    (Select-String -Path $_.FullName -Pattern "csrf_token" -Quiet) 
}
$csrfCount = $csrfFiles.Count
if ($csrfCount -gt 0) {
    Show-Success "$csrfCount fichier(s) avec tokens CSRF"
} else {
    Count-Warning "Aucun token CSRF trouvé dans les vues"
}

Write-Host ""

Write-Host "📋 VÉRIFICATION DES SCRIPTS DE TEST" -ForegroundColor Magenta
Write-Host "===================================" -ForegroundColor Magenta

# 12. Vérifier que les scripts de test ont été créés
Write-Host "🔍 Scripts de test créés..." -ForegroundColor White
$scripts = @(
    "scripts/smoke_test.sh",
    "scripts/validate_php.sh",
    "scripts/verify_fixes.sh",
    "scripts/verify_fixes.ps1",
    "scripts/PR_TEMPLATE.md"
)

foreach ($script in $scripts) {
    if (Test-Path $script) {
        Show-Success "Script créé: $script"
    } else {
        Count-Error "Script manquant: $script"
    }
}

Write-Host ""

Write-Host "🎉 VÉRIFICATION POST-FIX TERMINÉE" -ForegroundColor Cyan
Write-Host "=================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "📊 RÉSUMÉ:" -ForegroundColor Yellow
Write-Host "- Erreurs détectées: $ERRORS" -ForegroundColor Red
Write-Host "- Avertissements: $WARNINGS" -ForegroundColor Yellow
Write-Host ""

if ($ERRORS -eq 0) {
    Write-Host "✅ SUCCESS: Toutes les corrections ont été appliquées correctement" -ForegroundColor Green
    Write-Host "🚀 Le projet est prêt pour les tests" -ForegroundColor Green
    
    if ($WARNINGS -gt 0) {
        Write-Host "⚠️  $WARNINGS avertissement(s) à vérifier" -ForegroundColor Yellow
    }
} else {
    Write-Host "❌ ERREURS DÉTECTÉES: $ERRORS erreur(s)" -ForegroundColor Red
    Write-Host "🔧 Veuillez corriger les erreurs avant de continuer" -ForegroundColor Red
}

Write-Host ""
Write-Host "🔧 PROCHAINES ÉTAPES:" -ForegroundColor Cyan
Write-Host "1. Si des erreurs sont détectées, les corriger" -ForegroundColor White
Write-Host "2. Lancer les tests Docker: docker compose up -d" -ForegroundColor White
Write-Host "3. Exécuter les tests: ./scripts/smoke_test.sh" -ForegroundColor White
Write-Host "4. Valider manuellement les fonctionnalités critiques" -ForegroundColor White
Write-Host ""

exit $ERRORS
