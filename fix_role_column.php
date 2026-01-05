<?php
require_once 'auth_check.php';
require_once 'config/database.php';

echo "<h1>Correction de la colonne 'role'</h1>";

echo "<div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;'>";
echo "<h3>Problème identifié :</h3>";
echo "<p>La colonne 'role' est de type <strong>ENUM</strong> avec seulement ces valeurs autorisées :</p>";
echo "<ul>";
echo "<li>Membre</li>";
echo "<li>Responsable</li>";
echo "<li>Président</li>";
echo "<li>Vice-Président</li>";
echo "<li>Secrétaire</li>";
echo "<li>Trésorier</li>";
echo "<li>Autre</li>";
echo "</ul>";
echo "<p><strong>Solution :</strong> Convertir la colonne en VARCHAR pour accepter tous les nouveaux rôles.</p>";
echo "</div>";

if (isset($_POST['fix_now'])) {
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        echo "<h2>Étape 1 : Modification de la structure de la table</h2>";
        
        // Modifier la colonne role de ENUM à VARCHAR
        $query = "ALTER TABLE membres MODIFY COLUMN `role` VARCHAR(255) DEFAULT 'Membre'";
        $stmt = $conn->prepare($query);
        
        if ($stmt->execute()) {
            echo "<p style='color: green; font-weight: bold;'>✓ La colonne 'role' a été convertie en VARCHAR(255)</p>";
            
            echo "<h2>Étape 2 : Vérification</h2>";
            $query_check = "DESCRIBE membres";
            $stmt_check = $conn->prepare($query_check);
            $stmt_check->execute();
            $columns = $stmt_check->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($columns as $column) {
                if ($column['Field'] === 'role') {
                    echo "<p><strong>Type de la colonne 'role' :</strong> " . htmlspecialchars($column['Type']) . "</p>";
                    echo "<p><strong>Valeur par défaut :</strong> " . htmlspecialchars($column['Default']) . "</p>";
                }
            }
            
            echo "<h2>Étape 3 : Test de mise à jour</h2>";
            $test_role = "Commission Marketing, Communication & Médias Sociaux";
            $query_test = "UPDATE membres SET `role` = :role WHERE id = 13";
            $stmt_test = $conn->prepare($query_test);
            $stmt_test->bindParam(':role', $test_role, PDO::PARAM_STR);
            
            if ($stmt_test->execute()) {
                echo "<p style='color: green;'>✓ Test de mise à jour réussi</p>";
                
                // Vérifier le résultat
                $query_verify = "SELECT id, nom, prenom, `role` FROM membres WHERE id = 13";
                $stmt_verify = $conn->prepare($query_verify);
                $stmt_verify->execute();
                $membre = $stmt_verify->fetch(PDO::FETCH_ASSOC);
                
                echo "<p><strong>Rôle enregistré :</strong> '" . htmlspecialchars($membre['role']) . "'</p>";
                
                if ($membre['role'] === $test_role) {
                    echo "<div style='background: #d4edda; padding: 20px; border-left: 4px solid #28a745; margin: 20px 0;'>";
                    echo "<h3 style='color: #155724;'>🎉 SUCCÈS !</h3>";
                    echo "<p style='color: #155724;'>La colonne 'role' fonctionne maintenant correctement. Vous pouvez utiliser tous les nouveaux rôles.</p>";
                    echo "</div>";
                } else {
                    echo "<p style='color: red;'>Le test a échoué. Rôle attendu : " . htmlspecialchars($test_role) . "</p>";
                }
            }
            
            echo "<hr>";
            echo "<p><a href='gestion_membres.php' class='btn' style='background: #0b843e; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Retour à la gestion des membres</a></p>";
            
        } else {
            echo "<p style='color: red; font-weight: bold;'>✗ Erreur lors de la modification de la colonne</p>";
        }
        
    } catch (PDOException $e) {
        echo "<p style='color: red;'><strong>Erreur SQL :</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<div style='background: #f8d7da; padding: 20px; border-left: 4px solid #dc3545; margin: 20px 0;'>";
    echo "<h3 style='color: #721c24;'>⚠️ ATTENTION</h3>";
    echo "<p style='color: #721c24;'>Cette opération va modifier la structure de votre base de données.</p>";
    echo "<p style='color: #721c24;'><strong>Action :</strong> Convertir la colonne 'role' de ENUM en VARCHAR(255)</p>";
    echo "<p style='color: #721c24;'><strong>Impact :</strong> Les données existantes seront préservées, mais vous pourrez désormais utiliser tous les nouveaux rôles.</p>";
    echo "</div>";
    
    echo "<form method='POST'>";
    echo "<button type='submit' name='fix_now' style='background: #dc3545; color: white; padding: 15px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold;'>Corriger maintenant</button>";
    echo " <a href='gestion_membres.php' style='margin-left: 20px;'>Annuler</a>";
    echo "</form>";
}
?>
