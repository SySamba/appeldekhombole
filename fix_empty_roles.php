<?php
require_once 'auth_check.php';
require_once 'config/database.php';

echo "<h1>Correction des rôles vides</h1>";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Trouver tous les membres sans rôle
    $query = "SELECT id, nom, prenom, role FROM membres WHERE role IS NULL OR role = ''";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $membres_sans_role = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>Nombre de membres sans rôle : " . count($membres_sans_role) . "</p>";
    
    if (count($membres_sans_role) > 0) {
        echo "<h2>Membres à corriger :</h2>";
        echo "<ul>";
        foreach ($membres_sans_role as $membre) {
            echo "<li>" . htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']) . " (ID: " . $membre['id'] . ")</li>";
        }
        echo "</ul>";
        
        // Mettre à jour tous les membres sans rôle avec "Membre" par défaut
        if (isset($_POST['corriger'])) {
            $query_update = "UPDATE membres SET role = 'Membre' WHERE role IS NULL OR role = ''";
            $stmt_update = $conn->prepare($query_update);
            
            if ($stmt_update->execute()) {
                $count = $stmt_update->rowCount();
                echo "<p style='color: green; font-weight: bold;'>✓ " . $count . " membre(s) mis à jour avec le rôle 'Membre'</p>";
                echo "<p><a href='gestion_membres.php'>Retour à la gestion des membres</a></p>";
            } else {
                echo "<p style='color: red;'>✗ Erreur lors de la mise à jour</p>";
            }
        } else {
            echo "<form method='POST'>";
            echo "<p><strong>Voulez-vous mettre à jour tous ces membres avec le rôle 'Membre' par défaut ?</strong></p>";
            echo "<button type='submit' name='corriger' style='background: #0b843e; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Oui, corriger maintenant</button>";
            echo " <a href='gestion_membres.php' style='margin-left: 10px;'>Annuler</a>";
            echo "</form>";
        }
    } else {
        echo "<p style='color: green;'>✓ Tous les membres ont un rôle défini</p>";
        echo "<p><a href='gestion_membres.php'>Retour à la gestion des membres</a></p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
}
?>
