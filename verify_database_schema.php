<?php
require_once 'auth_check.php';
require_once 'config/database.php';

echo "<h1>Vérification de la structure de la base de données</h1>";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Vérifier la structure de la table membres
    echo "<h2>1. Structure de la table 'membres':</h2>";
    $query = "DESCRIBE membres";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' cellpadding='8' style='border-collapse: collapse;'>";
    echo "<tr style='background: #0b843e; color: white;'><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th><th>Extra</th></tr>";
    
    $role_exists = false;
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td><strong>" . htmlspecialchars($column['Field']) . "</strong></td>";
        echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Default']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
        echo "</tr>";
        
        if ($column['Field'] === 'role') {
            $role_exists = true;
        }
    }
    echo "</table>";
    
    if ($role_exists) {
        echo "<p style='color: green; font-weight: bold;'>✓ La colonne 'role' existe dans la table</p>";
    } else {
        echo "<p style='color: red; font-weight: bold;'>✗ ATTENTION : La colonne 'role' n'existe PAS dans la table !</p>";
        echo "<p>Colonnes disponibles : " . implode(', ', array_column($columns, 'Field')) . "</p>";
    }
    
    // Test de mise à jour directe avec SQL
    echo "<hr>";
    echo "<h2>2. Test de mise à jour SQL directe</h2>";
    
    if (isset($_POST['test_update'])) {
        $test_role = $_POST['test_role'];
        echo "<p>Tentative de mise à jour avec le rôle : <strong>" . htmlspecialchars($test_role) . "</strong></p>";
        
        try {
            // Test 1: UPDATE avec backticks
            $query1 = "UPDATE membres SET `role` = :role WHERE id = 13";
            $stmt1 = $conn->prepare($query1);
            $stmt1->bindParam(':role', $test_role, PDO::PARAM_STR);
            $result1 = $stmt1->execute();
            $affected1 = $stmt1->rowCount();
            
            echo "<p><strong>Test 1 - UPDATE avec backticks:</strong></p>";
            echo "<p>Requête : <code>" . htmlspecialchars($query1) . "</code></p>";
            echo "<p>Résultat : " . ($result1 ? "✓ Succès" : "✗ Échec") . "</p>";
            echo "<p>Lignes affectées : " . $affected1 . "</p>";
            
            // Vérifier le résultat
            $query_check = "SELECT id, nom, prenom, `role` FROM membres WHERE id = 13";
            $stmt_check = $conn->prepare($query_check);
            $stmt_check->execute();
            $membre_check = $stmt_check->fetch(PDO::FETCH_ASSOC);
            
            echo "<p><strong>Vérification après UPDATE:</strong></p>";
            echo "<p>Rôle dans la base : '" . htmlspecialchars($membre_check['role']) . "'</p>";
            echo "<p>Longueur : " . strlen($membre_check['role']) . " caractères</p>";
            
            if ($membre_check['role'] === $test_role) {
                echo "<p style='color: green; font-weight: bold;'>✓ Le rôle a été correctement enregistré !</p>";
            } else {
                echo "<p style='color: red; font-weight: bold;'>✗ Le rôle n'a PAS été enregistré correctement</p>";
                
                // Test 2: UPDATE sans backticks
                echo "<hr>";
                echo "<p><strong>Test 2 - UPDATE sans backticks:</strong></p>";
                $query2 = "UPDATE membres SET role = :role WHERE id = 13";
                $stmt2 = $conn->prepare($query2);
                $stmt2->bindParam(':role', $test_role, PDO::PARAM_STR);
                $result2 = $stmt2->execute();
                $affected2 = $stmt2->rowCount();
                
                echo "<p>Requête : <code>" . htmlspecialchars($query2) . "</code></p>";
                echo "<p>Résultat : " . ($result2 ? "✓ Succès" : "✗ Échec") . "</p>";
                echo "<p>Lignes affectées : " . $affected2 . "</p>";
                
                // Vérifier à nouveau
                $stmt_check->execute();
                $membre_check2 = $stmt_check->fetch(PDO::FETCH_ASSOC);
                echo "<p>Rôle après test 2 : '" . htmlspecialchars($membre_check2['role']) . "'</p>";
            }
            
        } catch (PDOException $e) {
            echo "<p style='color: red;'><strong>Erreur SQL:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    
    echo "<hr>";
    echo "<h2>Formulaire de test</h2>";
    echo "<form method='POST'>";
    echo "<label><strong>Rôle à tester :</strong></label><br>";
    echo "<input type='text' name='test_role' value='Coordonnateur Test' style='width: 300px; padding: 8px; margin: 10px 0;'><br>";
    echo "<button type='submit' name='test_update' style='background: #0b843e; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Tester la mise à jour</button>";
    echo "</form>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>Erreur:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<hr>";
echo "<p><a href='gestion_membres.php'>Retour à la gestion</a></p>";
?>
