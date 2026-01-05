<?php
require_once 'auth_check.php';
require_once 'classes/Membre.php';

echo "<h1>Debug Scanner QR</h1>";

// Test 1: Récupérer tous les membres
$membre_obj = new Membre();
$membres = $membre_obj->obtenirTous();

echo "<h2>Test 1: Liste des membres</h2>";
if (count($membres) > 0) {
    echo "<p>Nombre de membres: " . count($membres) . "</p>";
    echo "<h3>Premier membre:</h3>";
    echo "<pre>";
    print_r($membres[0]);
    echo "</pre>";
} else {
    echo "<p>Aucun membre trouvé</p>";
}

// Test 2: Si un QR code est fourni
if (isset($_GET['qr'])) {
    echo "<h2>Test 2: Recherche par QR Code</h2>";
    echo "<p>QR Code recherché: " . htmlspecialchars($_GET['qr']) . "</p>";
    
    $membre = $membre_obj->obtenirParQRCode($_GET['qr']);
    
    if ($membre) {
        echo "<h3>Membre trouvé par QR Code:</h3>";
        echo "<pre>";
        print_r($membre);
        echo "</pre>";
        
        echo "<h3>Affichage du rôle:</h3>";
        echo "<p>Rôle: " . htmlspecialchars($membre['role']) . "</p>";
        echo "<p>Type de la variable role: " . gettype($membre['role']) . "</p>";
        echo "<p>Valeur brute: '" . $membre['role'] . "'</p>";
    } else {
        echo "<p>Aucun membre trouvé avec ce QR code</p>";
        
        // Essayer avec la méthode alternative
        $membre = $membre_obj->obtenirParDonneesQR($_GET['qr']);
        if ($membre) {
            echo "<h3>Membre trouvé par données QR:</h3>";
            echo "<pre>";
            print_r($membre);
            echo "</pre>";
        }
    }
}

echo "<hr>";
echo "<h2>Formulaire de test</h2>";
echo "<form method='GET'>";
echo "<label>QR Code à tester:</label><br>";
echo "<input type='text' name='qr' value='" . (isset($_GET['qr']) ? htmlspecialchars($_GET['qr']) : '') . "' style='width: 400px;'><br><br>";
echo "<button type='submit'>Tester</button>";
echo "</form>";
?>
