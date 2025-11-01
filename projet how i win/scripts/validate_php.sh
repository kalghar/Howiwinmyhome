#!/bin/bash
# Script de validation PHP pour How I Win My Home
# Vérifie la syntaxe, les erreurs et les standards

echo "🔍 VALIDATION PHP - HOW I WIN MY HOME"
echo "====================================="
echo ""

# Configuration
PHP_FILES="app/Controllers/*.php app/Models/*.php app/Config/*.php"
ERRORS=0

# Fonction pour compter les erreurs
count_errors() {
    local output=$1
    local error_count=$(echo "$output" | grep -c "ERROR\|FATAL\|Parse error" || true)
    ERRORS=$((ERRORS + error_count))
    return $error_count
}

echo "📋 VÉRIFICATION DE LA SYNTAXE PHP"
echo "================================="

# Vérifier la syntaxe de tous les fichiers PHP
for file in $PHP_FILES; do
    if [ -f "$file" ]; then
        echo "🔍 Vérification: $file"
        
        # Vérifier la syntaxe
        php -l "$file" 2>&1 | while read line; do
            if echo "$line" | grep -q "No syntax errors"; then
                echo "   ✅ Syntaxe OK"
            elif echo "$line" | grep -q "ERROR\|FATAL\|Parse error"; then
                echo "   ❌ ERREUR: $line"
                ERRORS=$((ERRORS + 1))
            fi
        done
    fi
done

echo ""

echo "📋 VÉRIFICATION DES ERREURS PHP"
echo "==============================="

# Vérifier les erreurs dans les contrôleurs
echo "🔍 Contrôleurs..."
for file in app/Controllers/*.php; do
    if [ -f "$file" ]; then
        # Vérifier les erreurs communes
        if grep -q "setFlashMessage" "$file"; then
            echo "   ⚠️  $file: setFlashMessage trouvé (peut être un résidu)"
        fi
        
        if grep -q "require_once.*\.php" "$file"; then
            echo "   ⚠️  $file: require_once trouvé (vérifier l'autoloader)"
        fi
        
        if grep -q "mysql_" "$file"; then
            echo "   ❌ $file: Fonctions mysql_* obsolètes trouvées"
            ERRORS=$((ERRORS + 1))
        fi
    fi
done

echo ""

# Vérifier les erreurs dans les modèles
echo "🔍 Modèles..."
for file in app/Models/*.php; do
    if [ -f "$file" ]; then
        # Vérifier les erreurs communes
        if grep -q "\$db->execute" "$file"; then
            echo "   ❌ $file: \$db->execute trouvé (méthode inexistante)"
            ERRORS=$((ERRORS + 1))
        fi
        
        if grep -q "\$db->selectOne" "$file"; then
            echo "   ❌ $file: \$db->selectOne trouvé (méthode inexistante)"
            ERRORS=$((ERRORS + 1))
        fi
        
        if grep -q "\$db->select" "$file"; then
            echo "   ❌ $file: \$db->select trouvé (méthode inexistante)"
            ERRORS=$((ERRORS + 1))
        fi
    fi
done

echo ""

echo "📋 VÉRIFICATION DES SÉCURITÉS"
echo "============================="

# Vérifier les problèmes de sécurité
echo "🔍 Vérification des sécurités..."

# Vérifier les sessions non sécurisées
if grep -q "secure.*false" app/Config/Config.php; then
    echo "   ⚠️  Sessions non sécurisées en DEV (acceptable)"
fi

# Vérifier les tokens CSRF
csrf_count=$(find app/Views -name "*.php" -exec grep -l "csrf_token" {} \; | wc -l)
echo "   📊 $csrf_count fichiers avec tokens CSRF"

# Vérifier les échappements HTML
echo "🔍 Vérification des échappements..."
for file in app/Views/*.php app/Views/*/*.php; do
    if [ -f "$file" ]; then
        if grep -q "<?=.*\$" "$file" && ! grep -q "htmlspecialchars" "$file"; then
            echo "   ⚠️  $file: Échappement HTML potentiellement manquant"
        fi
    fi
done

echo ""

echo "📋 VÉRIFICATION DES MÉTHODES MANQUANTES"
echo "======================================="

# Vérifier que les méthodes critiques existent
echo "🔍 Vérification des méthodes critiques..."

# AdminController
if grep -q "function getAdminStats" app/Controllers/AdminController.php; then
    echo "   ✅ AdminController::getAdminStats() existe"
else
    echo "   ❌ AdminController::getAdminStats() manquante"
    ERRORS=$((ERRORS + 1))
fi

if grep -q "function getPendingListings" app/Controllers/AdminController.php; then
    echo "   ✅ AdminController::getPendingListings() existe"
else
    echo "   ❌ AdminController::getPendingListings() manquante"
    ERRORS=$((ERRORS + 1))
fi

# User Model
if grep -q "function getTotalCount" app/Models/User.php; then
    echo "   ✅ User::getTotalCount() existe"
else
    echo "   ❌ User::getTotalCount() manquante"
    ERRORS=$((ERRORS + 1))
fi

# Listing Model
if grep -q "function getTotalCount" app/Models/Listing.php; then
    echo "   ✅ Listing::getTotalCount() existe"
else
    echo "   ❌ Listing::getTotalCount() manquante"
    ERRORS=$((ERRORS + 1))
fi

echo ""

echo "📋 VÉRIFICATION DES FICHIERS DE DOCUMENTATION"
echo "============================================="

# Vérifier que la documentation existe
docs=(
    "docs/API_CONTROLLERS.md"
    "docs/API_MODELS.md"
    "docs/VIEWS_STRUCTURE.md"
    "analysis/fix_progress.md"
    "ai_acknowledgements.md"
)

for doc in "${docs[@]}"; do
    if [ -f "$doc" ]; then
        echo "   ✅ $doc existe"
    else
        echo "   ❌ $doc manquant"
        ERRORS=$((ERRORS + 1))
    fi
done

echo ""

echo "🎉 VALIDATION PHP TERMINÉE"
echo "=========================="
echo ""

if [ $ERRORS -eq 0 ]; then
    echo "✅ SUCCESS: Aucune erreur critique détectée"
    echo "🚀 Le code est prêt pour les tests"
else
    echo "❌ ERREURS DÉTECTÉES: $ERRORS erreur(s)"
    echo "🔧 Veuillez corriger les erreurs avant de continuer"
fi

echo ""
echo "📝 RÉSUMÉ:"
echo "- Syntaxe PHP: Vérifiée"
echo "- Erreurs communes: Vérifiées"
echo "- Sécurités: Vérifiées"
echo "- Méthodes critiques: Vérifiées"
echo "- Documentation: Vérifiée"
echo ""
echo "🔧 PROCHAINES ÉTAPES:"
echo "1. Si des erreurs sont détectées, les corriger"
echo "2. Lancer les tests Docker"
echo "3. Exécuter les tests manuels"
echo ""

exit $ERRORS
