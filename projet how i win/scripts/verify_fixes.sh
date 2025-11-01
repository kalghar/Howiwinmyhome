#!/bin/bash
# Script de vérification post-fix pour How I Win My Home
# Vérifie que toutes les corrections ont été appliquées correctement

echo "🔍 VÉRIFICATION POST-FIX - HOW I WIN MY HOME"
echo "============================================="
echo ""

# Configuration
ERRORS=0
WARNINGS=0

# Fonction pour compter les erreurs
count_error() {
    ERRORS=$((ERRORS + 1))
    echo "   ❌ ERREUR: $1"
}

# Fonction pour compter les avertissements
count_warning() {
    WARNINGS=$((WARNINGS + 1))
    echo "   ⚠️  AVERTISSEMENT: $1"
}

# Fonction pour afficher le succès
show_success() {
    echo "   ✅ SUCCESS: $1"
}

echo "📋 VÉRIFICATION DES CORRECTIONS BLOQUANTES"
echo "=========================================="

# 1. Vérifier que les fichiers sensibles ont été supprimés
echo "🔍 Fichiers sensibles supprimés..."
sensitive_files=(
    "public/reset_admin_password.php"
    "public/index.php.backup"
    "cookies.txt"
)

for file in "${sensitive_files[@]}"; do
    if [ -f "$file" ]; then
        count_error "Fichier sensible encore présent: $file"
    else
        show_success "Fichier sensible supprimé: $file"
    fi
done

# 2. Vérifier que le .gitignore des uploads existe
echo "🔍 Protection des uploads..."
if [ -f "public/uploads/.gitignore" ]; then
    show_success "Fichier .gitignore présent dans uploads"
else
    count_error "Fichier .gitignore manquant dans uploads"
fi

# 3. Vérifier que les méthodes manquantes ont été ajoutées
echo "🔍 Méthodes manquantes ajoutées..."

# AdminController
if grep -q "function getAdminStats" app/Controllers/AdminController.php; then
    show_success "AdminController::getAdminStats() ajoutée"
else
    count_error "AdminController::getAdminStats() manquante"
fi

if grep -q "function getPendingListings" app/Controllers/AdminController.php; then
    show_success "AdminController::getPendingListings() ajoutée"
else
    count_error "AdminController::getPendingListings() manquante"
fi

# User Model
if grep -q "function getTotalCount" app/Models/User.php; then
    show_success "User::getTotalCount() ajoutée"
else
    count_error "User::getTotalCount() manquante"
fi

# Listing Model
if grep -q "function getTotalCount" app/Models/Listing.php; then
    show_success "Listing::getTotalCount() ajoutée"
else
    count_error "Listing::getTotalCount() manquante"
fi

echo ""

echo "📋 VÉRIFICATION DES CORRECTIONS CRITIQUES"
echo "========================================="

# 4. Vérifier que les erreurs SQL ont été corrigées
echo "🔍 Erreurs SQL corrigées..."

# Ticket Model
if grep -q "\$db->execute" app/Models/Ticket.php; then
    count_error "Ticket.php contient encore \$db->execute"
else
    show_success "Ticket.php corrigé (plus de \$db->execute)"
fi

# Letter Model
if grep -q "\$db->execute" app/Models/Letter.php; then
    count_error "Letter.php contient encore \$db->execute"
else
    show_success "Letter.php corrigé (plus de \$db->execute)"
fi

# 5. Vérifier que les validations sont harmonisées
echo "🔍 Validations harmonisées..."
if [ -f "public/assets/js/validation-rules.js" ]; then
    show_success "Fichier validation-rules.js créé"
else
    count_error "Fichier validation-rules.js manquant"
fi

if [ -f "public/assets/js/real-time-validation.js" ]; then
    show_success "Fichier real-time-validation.js créé"
else
    count_error "Fichier real-time-validation.js manquant"
fi

echo ""

echo "📋 VÉRIFICATION DES CORRECTIONS MAJEURES"
echo "========================================"

# 6. Vérifier que les messages flash ont été supprimés
echo "🔍 Messages flash supprimés..."

# BaseController
if grep -q "setFlashMessage" app/Controllers/BaseController.php; then
    count_warning "BaseController contient encore setFlashMessage"
else
    show_success "BaseController: setFlashMessage supprimé"
fi

# AdminController
if grep -q "setFlashMessage" app/Controllers/AdminController.php; then
    count_warning "AdminController contient encore setFlashMessage"
else
    show_success "AdminController: setFlashMessage supprimé"
fi

# 7. Vérifier que la gestion d'erreurs a été améliorée
echo "🔍 Gestion d'erreurs améliorée..."
if grep -q "function logError" app/Controllers/BaseController.php; then
    show_success "BaseController: logError() ajoutée"
else
    count_error "BaseController: logError() manquante"
fi

if grep -q "function handleException" app/Controllers/BaseController.php; then
    show_success "BaseController: handleException() ajoutée"
else
    count_error "BaseController: handleException() manquante"
fi

echo ""

echo "📋 VÉRIFICATION DES CORRECTIONS MINEURES"
echo "========================================"

# 8. Vérifier que la documentation a été créée
echo "🔍 Documentation créée..."
docs=(
    "docs/API_CONTROLLERS.md"
    "docs/API_MODELS.md"
    "docs/VIEWS_STRUCTURE.md"
    "analysis/fix_progress.md"
    "ai_acknowledgements.md"
)

for doc in "${docs[@]}"; do
    if [ -f "$doc" ]; then
        show_success "Documentation créée: $doc"
    else
        count_error "Documentation manquante: $doc"
    fi
done

# 9. Vérifier que les méthodes communes ont été ajoutées
echo "🔍 Méthodes communes ajoutées..."
if grep -q "function requireAdminRole" app/Controllers/BaseController.php; then
    show_success "BaseController: requireAdminRole() ajoutée"
else
    count_error "BaseController: requireAdminRole() manquante"
fi

if grep -q "function requireLogin" app/Controllers/BaseController.php; then
    show_success "BaseController: requireLogin() ajoutée"
else
    count_error "BaseController: requireLogin() manquante"
fi

echo ""

echo "📋 VÉRIFICATION DES SÉCURITÉS"
echo "============================="

# 10. Vérifier que les commentaires de sécurité PROD ont été ajoutés
echo "🔍 Commentaires sécurité PROD..."
if grep -q "Sécurité PROD" app/Config/Config.php; then
    show_success "Commentaires sécurité PROD ajoutés dans Config.php"
else
    count_warning "Commentaires sécurité PROD manquants dans Config.php"
fi

# 11. Vérifier que les tokens CSRF sont présents
echo "🔍 Tokens CSRF..."
csrf_count=$(find app/Views -name "*.php" -exec grep -l "csrf_token" {} \; | wc -l)
if [ $csrf_count -gt 0 ]; then
    show_success "$csrf_count fichier(s) avec tokens CSRF"
else
    count_warning "Aucun token CSRF trouvé dans les vues"
fi

echo ""

echo "📋 VÉRIFICATION DES SCRIPTS DE TEST"
echo "==================================="

# 12. Vérifier que les scripts de test ont été créés
echo "🔍 Scripts de test créés..."
scripts=(
    "scripts/smoke_test.sh"
    "scripts/validate_php.sh"
    "scripts/verify_fixes.sh"
    "scripts/PR_TEMPLATE.md"
)

for script in "${scripts[@]}"; do
    if [ -f "$script" ]; then
        show_success "Script créé: $script"
    else
        count_error "Script manquant: $script"
    fi
done

echo ""

echo "🎉 VÉRIFICATION POST-FIX TERMINÉE"
echo "================================="
echo ""

echo "📊 RÉSUMÉ:"
echo "- Erreurs détectées: $ERRORS"
echo "- Avertissements: $WARNINGS"
echo ""

if [ $ERRORS -eq 0 ]; then
    echo "✅ SUCCESS: Toutes les corrections ont été appliquées correctement"
    echo "🚀 Le projet est prêt pour les tests"
    
    if [ $WARNINGS -gt 0 ]; then
        echo "⚠️  $WARNINGS avertissement(s) à vérifier"
    fi
else
    echo "❌ ERREURS DÉTECTÉES: $ERRORS erreur(s)"
    echo "🔧 Veuillez corriger les erreurs avant de continuer"
fi

echo ""
echo "🔧 PROCHAINES ÉTAPES:"
echo "1. Si des erreurs sont détectées, les corriger"
echo "2. Lancer les tests Docker: docker compose up -d"
echo "3. Exécuter les tests: ./scripts/smoke_test.sh"
echo "4. Valider manuellement les fonctionnalités critiques"
echo ""

exit $ERRORS
