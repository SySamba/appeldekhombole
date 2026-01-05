<?php
require_once 'config/database.php';

echo "<h1>Vérification de la structure de la base de données</h1>";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Vérifier la structure de la table membres
    $query = "DESCRIBE membres";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Structure de la table 'membres':</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Récupérer un exemple de membre
    $query = "SELECT * FROM membres LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $membre = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($membre) {
        echo "<h2>Exemple de membre (premier enregistrement):</h2>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Champ</th><th>Valeur</th></tr>";
        foreach ($membre as $key => $value) {
            echo "<tr>";
            echo "<td><strong>" . htmlspecialchars($key) . "</strong></td>";
            echo "<td>" . htmlspecialchars($value) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<h3>Vérification spécifique du champ 'role':</h3>";
        if (isset($membre['role'])) {
            echo "<p style='color: green;'>✓ Le champ 'role' existe</p>";
            echo "<p>Valeur: <strong>" . htmlspecialchars($membre['role']) . "</strong></p>";
            echo "<p>Type: " . gettype($membre['role']) . "</p>";
            echo "<p>Longueur: " . strlen($membre['role']) . " caractères</p>";
        } else {
            echo "<p style='color: red;'>✗ Le champ 'role' n'existe pas dans les résultats</p>";
        }
    } else {
        echo "<p>Aucun membre dans la base de données</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur: " . $e->getMessage() . "</p>";
}
?>
