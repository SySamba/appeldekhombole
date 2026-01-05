<?php
require_once 'auth_check.php';
require_once 'config/database.php';
require_once 'classes/Membre.php';

echo "<h1>Test de mise à jour du rôle</h1>";

// Récupérer le membre ID 13
$membre_obj = new Membre();
$membre = $membre_obj->obtenirParId(13);

if ($membre) {
    echo "<h2>Membre actuel :</h2>";
    echo "<p><strong>Nom :</strong> " . htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']) . "</p>";
    echo "<p><strong>Rôle actuel :</strong> '" . htmlspecialchars($membre['role']) . "'</p>";
    echo "<p><strong>Longueur du rôle :</strong> " . strlen($membre['role']) . " caractères</p>";
    
    if (isset($_POST['nouveau_role'])) {
        $nouveau_role = $_POST['nouveau_role'];
        
        echo "<hr>";
        echo "<h2>Tentative de mise à jour...</h2>";
        echo "<p><strong>Nouveau rôle à enregistrer :</strong> " . htmlspecialchars($nouveau_role) . "</p>";
        
        $data = [
            'nom' => $membre['nom'],
            'prenom' => $membre['prenom'],
            'date_naissance' => $membre['date_naissance'],
            'telephone' => $membre['telephone'],
            'email' => $membre['email'],
            'adresse' => $membre['adresse'],
            'photo' => $membre['photo'],
            'role' => $nouveau_role,
            'statut' => $membre['statut']
        ];
        
        if ($membre_obj->modifier(13, $data)) {
            echo "<p style='color: green; font-weight: bold;'>✓ Mise à jour réussie !</p>";
            
            // Vérifier la mise à jour
            $membre_updated = $membre_obj->obtenirParId(13);
            echo "<p><strong>Rôle après mise à jour :</strong> '" . htmlspecialchars($membre_updated['role']) . "'</p>";
            echo "<p><strong>Longueur :</strong> " . strlen($membre_updated['role']) . " caractères</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>✗ Erreur lors de la mise à jour</p>";
        }
    }
    
    echo "<hr>";
    echo "<h2>Formulaire de test</h2>";
    echo "<form method='POST'>";
    echo "<label><strong>Sélectionnez un nouveau rôle :</strong></label><br>";
    echo "<select name='nouveau_role' class='form-control' style='width: 500px; padding: 10px; margin: 10px 0;'>";
    echo "<option value=''>-- Sélectionner --</option>";
    echo "<option value='Coordonnateur'>Coordonnateur</option>";
    echo "<option value='Secrétaire Général'>Secrétaire Général</option>";
    echo "<option value='Trésorier'>Trésorier</option>";
    echo "<option value='Commission Technique et de Contrôle (Chartes & Règlement intérieur)'>Commission Technique et de Contrôle (Chartes & Règlement intérieur)</option>";
    echo "<option value='Commission Marketing, Communication & Médias Sociaux'>Commission Marketing, Communication & Médias Sociaux</option>";
    echo "<option value='Membre'>Membre</option>";
    echo "</select><br>";
    echo "<button type='submit' style='background: #0b843e; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Mettre à jour le rôle</button>";
    echo "</form>";
    
} else {
    echo "<p style='color: red;'>Membre non trouvé</p>";
}

echo "<hr>";
echo "<p><a href='gestion_membres.php'>Retour à la gestion</a></p>";
?>
