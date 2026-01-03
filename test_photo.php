<?php
// Test pour vérifier l'affichage des photos
echo "<h2>Test d'affichage des photos</h2>";

// Vérifier si le dossier uploads/photos existe
$photo_dir = 'uploads/photos/';
if (is_dir($photo_dir)) {
    echo "<p style='color: green;'>✓ Dossier uploads/photos/ existe</p>";
    
    // Lister les fichiers dans le dossier
    $files = scandir($photo_dir);
    $images = array_filter($files, function($file) {
        return in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
    });
    
    if (empty($images)) {
        echo "<p style='color: orange;'>⚠ Aucune image trouvée dans uploads/photos/</p>";
        echo "<p>Pour tester l'affichage des photos :</p>";
        echo "<ol>";
        echo "<li>Ajoutez une image dans le dossier uploads/photos/</li>";
        echo "<li>Ou utilisez la gestion des membres pour ajouter un membre avec photo</li>";
        echo "</ol>";
    } else {
        echo "<p style='color: green;'>✓ Images trouvées :</p>";
        echo "<ul>";
        foreach ($images as $image) {
            echo "<li>$image</li>";
        }
        echo "</ul>";
    }
} else {
    echo "<p style='color: red;'>✗ Dossier uploads/photos/ n'existe pas</p>";
}

// Vérifier les permissions
if (is_writable($photo_dir)) {
    echo "<p style='color: green;'>✓ Dossier uploads/photos/ est accessible en écriture</p>";
} else {
    echo "<p style='color: red;'>✗ Dossier uploads/photos/ n'est pas accessible en écriture</p>";
}

echo "<hr>";
echo "<h3>Test de la carte</h3>";
echo "<p><a href='test_carte.php'>Tester la génération de carte</a></p>";
echo "<p><a href='gestion_membres.php'>Aller à la gestion des membres</a></p>";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Photo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <div class="alert alert-info">
        <h4>Instructions :</h4>
        <p>Ce fichier teste l'affichage des photos dans le système de cartes de membres.</p>
        <p>Si vous voyez des erreurs, vérifiez que :</p>
        <ul>
            <li>Le dossier uploads/photos/ existe</li>
            <li>Les permissions sont correctes</li>
            <li>Les images sont dans les formats supportés (JPG, PNG, GIF)</li>
        </ul>
    </div>
</body>
</html>
