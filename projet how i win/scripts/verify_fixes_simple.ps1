# Script de verification post-fix pour How I Win My Home (Windows PowerShell)
# Verifie que toutes les corrections ont ete appliquees correctement

Write-Host "VERIFICATION POST-FIX - HOW I WIN MY HOME" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

$ERRORS = 0
$WARNINGS = 0

function Count-Error {
    param($message)
    $script:ERRORS++
    Write-Host "   ERREUR: $message" -ForegroundColor Red
}

function Count-Warning {
    param($message)
    $script:WARNINGS++
    Write-Host "   AVERTISSEMENT: $message" -ForegroundColor Yellow
}

function Show-Success {
    param($message)
    Write-Host "   SUCCESS: $message" -ForegroundColor Green
}

Write-Host "VERIFICATION DES CORRECTIONS BLOQUANTES" -ForegroundColor Magenta
Write-Host "=======================================" -ForegroundColor Magenta

# 1. Verifier que les fichiers sensibles ont ete supprimes
Write-Host "Fichiers sensibles supprimes..." -ForegroundColor White
$sensitiveFiles = @(
    "public/reset_admin_password.php",
    "public/index.php.backup",
    "cookies.txt"
)

foreach ($file in $sensitiveFiles) {
    if (Test-Path $file) {
        Count-Error "Fichier sensible encore present: $file"
    } else {
        Show-Success "Fichier sensible supprime: $file"
    }
}

# 2. Verifier que le .gitignore des uploads existe
Write-Host "Protection des uploads..." -ForegroundColor White
if (Test-Path "public/uploads/.gitignore") {
    Show-Success "Fichier .gitignore present dans uploads"
} else {
    Count-Error "Fichier .gitignore manquant dans uploads"
}

# 3. Verifier que les methodes manquantes ont ete ajoutees
Write-Host "Methodes manquantes ajoutees..." -ForegroundColor White

# AdminController
if (Select-String -Path "app/Controllers/AdminController.php" -Pattern "function getAdminStats" -Quiet) {
    Show-Success "AdminController getAdminStats ajoutee"
} else {
    Count-Error "AdminController getAdminStats manquante"
}

if (Select-String -Path "app/Controllers/AdminController.php" -Pattern "function getPendingListings" -Quiet) {
    Show-Success "AdminController getPendingListings ajoutee"
} else {
    Count-Error "AdminController getPendingListings manquante"
}

# User Model
if (Select-String -Path "app/Models/User.php" -Pattern "function getTotalCount" -Quiet) {
    Show-Success "User getTotalCount ajoutee"
} else {
    Count-Error "User getTotalCount manquante"
}

# Listing Model
if (Select-String -Path "app/Models/Listing.php" -Pattern "function getTotalCount" -Quiet) {
    Show-Success "Listing getTotalCount ajoutee"
} else {
    Count-Error "Listing getTotalCount manquante"
}

Write-Host ""

Write-Host "VERIFICATION DES CORRECTIONS CRITIQUES" -ForegroundColor Magenta
Write-Host "======================================" -ForegroundColor Magenta

# 4. Verifier que les erreurs SQL ont ete corrigees
Write-Host "Erreurs SQL corrigees..." -ForegroundColor White

# Ticket Model
if (Select-String -Path "app/Models/Ticket.php" -Pattern "db->execute" -Quiet) {
    Count-Error "Ticket.php contient encore db->execute"
} else {
    Show-Success "Ticket.php corrige (plus de db->execute)"
}

# Letter Model
if (Select-String -Path "app/Models/Letter.php" -Pattern "db->execute" -Quiet) {
    Count-Error "Letter.php contient encore db->execute"
} else {
    Show-Success "Letter.php corrige (plus de db->execute)"
}

# 5. Verifier que les validations sont harmonisees
Write-Host "Validations harmonisees..." -ForegroundColor White
if (Test-Path "public/assets/js/validation-rules.js") {
    Show-Success "Fichier validation-rules.js cree"
} else {
    Count-Error "Fichier validation-rules.js manquant"
}

if (Test-Path "public/assets/js/real-time-validation.js") {
    Show-Success "Fichier real-time-validation.js cree"
} else {
    Count-Error "Fichier real-time-validation.js manquant"
}

Write-Host ""

Write-Host "VERIFICATION DES CORRECTIONS MAJEURES" -ForegroundColor Magenta
Write-Host "=====================================" -ForegroundColor Magenta

# 6. Verifier que les messages flash ont ete supprimes
Write-Host "Messages flash supprimes..." -ForegroundColor White

# BaseController
if (Select-String -Path "app/Controllers/BaseController.php" -Pattern "setFlashMessage" -Quiet) {
    Count-Warning "BaseController contient encore setFlashMessage"
} else {
    Show-Success "BaseController setFlashMessage supprime"
}

# AdminController
if (Select-String -Path "app/Controllers/AdminController.php" -Pattern "setFlashMessage" -Quiet) {
    Count-Warning "AdminController contient encore setFlashMessage"
} else {
    Show-Success "AdminController setFlashMessage supprime"
}

# 7. Verifier que la gestion d'erreurs a ete amelioree
Write-Host "Gestion d'erreurs amelioree..." -ForegroundColor White
if (Select-String -Path "app/Controllers/BaseController.php" -Pattern "function logError" -Quiet) {
    Show-Success "BaseController logError ajoutee"
} else {
    Count-Error "BaseController logError manquante"
}

if (Select-String -Path "app/Controllers/BaseController.php" -Pattern "function handleException" -Quiet) {
    Show-Success "BaseController handleException ajoutee"
} else {
    Count-Error "BaseController handleException manquante"
}

Write-Host ""

Write-Host "VERIFICATION DES CORRECTIONS MINEURES" -ForegroundColor Magenta
Write-Host "=====================================" -ForegroundColor Magenta

# 8. Verifier que la documentation a ete creee
Write-Host "Documentation creee..." -ForegroundColor White
$docs = @(
    "docs/API_CONTROLLERS.md",
    "docs/API_MODELS.md",
    "docs/VIEWS_STRUCTURE.md",
    "analysis/fix_progress.md",
    "ai_acknowledgements.md"
)

foreach ($doc in $docs) {
    if (Test-Path $doc) {
        Show-Success "Documentation creee: $doc"
    } else {
        Count-Error "Documentation manquante: $doc"
    }
}

# 9. Verifier que les methodes communes ont ete ajoutees
Write-Host "Methodes communes ajoutees..." -ForegroundColor White
if (Select-String -Path "app/Controllers/BaseController.php" -Pattern "function requireAdminRole" -Quiet) {
    Show-Success "BaseController requireAdminRole ajoutee"
} else {
    Count-Error "BaseController requireAdminRole manquante"
}

if (Select-String -Path "app/Controllers/BaseController.php" -Pattern "function requireLogin" -Quiet) {
    Show-Success "BaseController requireLogin ajoutee"
} else {
    Count-Error "BaseController requireLogin manquante"
}

Write-Host ""

Write-Host "VERIFICATION DES SECURITES" -ForegroundColor Magenta
Write-Host "==========================" -ForegroundColor Magenta

# 10. Verifier que les commentaires de securite PROD ont ete ajoutes
Write-Host "Commentaires securite PROD..." -ForegroundColor White
if (Select-String -Path "app/Config/Config.php" -Pattern "Securite PROD" -Quiet) {
    Show-Success "Commentaires securite PROD ajoutes dans Config.php"
} else {
    Count-Warning "Commentaires securite PROD manquants dans Config.php"
}

# 11. Verifier que les tokens CSRF sont presents
Write-Host "Tokens CSRF..." -ForegroundColor White
$csrfFiles = Get-ChildItem -Path "app/Views" -Recurse -Filter "*.php" | Where-Object { 
    (Select-String -Path $_.FullName -Pattern "csrf_token" -Quiet) 
}
$csrfCount = $csrfFiles.Count
if ($csrfCount -gt 0) {
    Show-Success "$csrfCount fichier(s) avec tokens CSRF"
} else {
    Count-Warning "Aucun token CSRF trouve dans les vues"
}

Write-Host ""

Write-Host "VERIFICATION DES SCRIPTS DE TEST" -ForegroundColor Magenta
Write-Host "================================" -ForegroundColor Magenta

# 12. Verifier que les scripts de test ont ete crees
Write-Host "Scripts de test crees..." -ForegroundColor White
$scripts = @(
    "scripts/smoke_test.sh",
    "scripts/validate_php.sh",
    "scripts/verify_fixes.sh",
    "scripts/verify_fixes_simple.ps1",
    "scripts/PR_TEMPLATE.md"
)

foreach ($script in $scripts) {
    if (Test-Path $script) {
        Show-Success "Script cree: $script"
    } else {
        Count-Error "Script manquant: $script"
    }
}

Write-Host ""

Write-Host "VERIFICATION POST-FIX TERMINEE" -ForegroundColor Cyan
Write-Host "==============================" -ForegroundColor Cyan
Write-Host ""

Write-Host "RESUME:" -ForegroundColor Yellow
Write-Host "- Erreurs detectees: $ERRORS" -ForegroundColor Red
Write-Host "- Avertissements: $WARNINGS" -ForegroundColor Yellow
Write-Host ""

if ($ERRORS -eq 0) {
    Write-Host "SUCCESS: Toutes les corrections ont ete appliquees correctement" -ForegroundColor Green
    Write-Host "Le projet est pret pour les tests" -ForegroundColor Green
    
    if ($WARNINGS -gt 0) {
        Write-Host "$WARNINGS avertissement(s) a verifier" -ForegroundColor Yellow
    }
} else {
    Write-Host "ERREURS DETECTEES: $ERRORS erreur(s)" -ForegroundColor Red
    Write-Host "Veuillez corriger les erreurs avant de continuer" -ForegroundColor Red
}

Write-Host ""
Write-Host "PROCHAINES ETAPES:" -ForegroundColor Cyan
Write-Host "1. Si des erreurs sont detectees, les corriger" -ForegroundColor White
Write-Host "2. Lancer les tests Docker: docker compose up -d" -ForegroundColor White
Write-Host "3. Executer les tests manuels" -ForegroundColor White
Write-Host "4. Valider manuellement les fonctionnalites critiques" -ForegroundColor White
Write-Host ""

exit $ERRORS
