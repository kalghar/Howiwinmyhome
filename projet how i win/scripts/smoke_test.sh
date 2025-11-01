#!/bin/bash
# Script de smoke test pour How I Win My Home
# Vérifie les corrections critiques après docker compose up

echo "🚀 SMOKE TEST - HOW I WIN MY HOME"
echo "=================================="
echo ""

# Configuration
BASE_URL="http://localhost:8080"
TIMEOUT=10

# Fonction pour tester une URL
test_url() {
    local url=$1
    local description=$2
    local expected_status=${3:-200}
    
    echo "🔍 Test: $description"
    echo "   URL: $url"
    
    response=$(curl -s -o /dev/null -w "%{http_code}" --max-time $TIMEOUT "$url")
    
    if [ "$response" = "$expected_status" ]; then
        echo "   ✅ SUCCESS ($response)"
    else
        echo "   ❌ FAILED (got $response, expected $expected_status)"
    fi
    echo ""
}

# Fonction pour tester un endpoint POST
test_post() {
    local url=$1
    local description=$2
    local data=$3
    local expected_status=${4:-200}
    
    echo "🔍 Test POST: $description"
    echo "   URL: $url"
    
    response=$(curl -s -o /dev/null -w "%{http_code}" --max-time $TIMEOUT -X POST -d "$data" "$url")
    
    if [ "$response" = "$expected_status" ]; then
        echo "   ✅ SUCCESS ($response)"
    else
        echo "   ❌ FAILED (got $response, expected $expected_status)"
    fi
    echo ""
}

echo "📋 VÉRIFICATION DES SERVICES DOCKER"
echo "===================================="

# Vérifier que Docker est en cours d'exécution
if ! docker compose ps | grep -q "Up"; then
    echo "❌ Docker services not running. Please run: docker compose up -d"
    exit 1
fi

echo "✅ Docker services are running"
echo ""

echo "📋 TESTS DE CONNECTIVITÉ"
echo "========================"

# Test 1: Page d'accueil
test_url "$BASE_URL" "Page d'accueil" 200

# Test 2: Page de connexion
test_url "$BASE_URL/auth/login" "Page de connexion" 200

# Test 3: Page d'inscription
test_url "$BASE_URL/auth/register" "Page d'inscription" 200

# Test 4: Page des annonces
test_url "$BASE_URL/listings" "Page des annonces" 200

echo "📋 TESTS D'ADMINISTRATION"
echo "========================="

# Test 5: Page admin (peut rediriger vers login)
test_url "$BASE_URL/admin" "Page administration" "200,302"

# Test 6: Page admin utilisateurs (peut rediriger vers login)
test_url "$BASE_URL/admin/users" "Page admin utilisateurs" "200,302"

# Test 7: Page admin annonces (peut rediriger vers login)
test_url "$BASE_URL/admin/listings" "Page admin annonces" "200,302"

echo "📋 TESTS DE CRÉATION D'ANNONCE"
echo "=============================="

# Test 8: Page de création d'annonce (peut rediriger vers login)
test_url "$BASE_URL/listings/create" "Page création annonce" "200,302"

echo "📋 TESTS DE VALIDATION"
echo "======================"

# Test 9: Test de validation côté serveur (sans authentification)
test_post "$BASE_URL/listings/create" "Validation création annonce (sans auth)" "title=test" "302,401"

echo "📋 VÉRIFICATION DES FICHIERS SENSIBLES"
echo "======================================"

# Vérifier que les fichiers sensibles ont été supprimés
sensitive_files=(
    "public/reset_admin_password.php"
    "public/index.php.backup"
    "cookies.txt"
)

for file in "${sensitive_files[@]}"; do
    if [ -f "$file" ]; then
        echo "❌ FICHIER SENSIBLE TROUVÉ: $file"
    else
        echo "✅ Fichier sensible supprimé: $file"
    fi
done

echo ""

echo "📋 VÉRIFICATION DES UPLOADS"
echo "==========================="

# Vérifier que le dossier uploads existe et est protégé
if [ -d "public/uploads" ]; then
    echo "✅ Dossier uploads existe"
    
    if [ -f "public/uploads/.gitignore" ]; then
        echo "✅ Fichier .gitignore présent dans uploads"
    else
        echo "❌ Fichier .gitignore manquant dans uploads"
    fi
else
    echo "❌ Dossier uploads manquant"
fi

echo ""

echo "📋 VÉRIFICATION DES LOGS DOCKER"
echo "==============================="

echo "🔍 Vérification des logs d'erreur récents..."
docker compose logs --tail=20 web 2>/dev/null | grep -i error || echo "✅ Aucune erreur récente détectée"

echo ""

echo "🎉 SMOKE TEST TERMINÉ"
echo "===================="
echo ""
echo "📝 RÉSUMÉ:"
echo "- Tests de connectivité: Vérifiez les résultats ci-dessus"
echo "- Fichiers sensibles: Vérifiez qu'ils sont supprimés"
echo "- Uploads: Vérifiez que le dossier est protégé"
echo "- Logs: Vérifiez qu'il n'y a pas d'erreurs critiques"
echo ""
echo "🔧 PROCHAINES ÉTAPES:"
echo "1. Si des tests échouent, vérifiez les logs Docker"
echo "2. Testez manuellement les fonctionnalités critiques"
echo "3. Vérifiez que l'upload de documents fonctionne"
echo "4. Testez l'administration des annonces"
echo ""
