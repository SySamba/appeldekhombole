<?php
echo "<h2>🔍 Debug des Images</h2>";

// 1. Vérifier si le dossier existe
$upload_dir = 'uploads/photos/';
echo "<h3>1. Vérification du dossier</h3>";
if (is_dir($upload_dir)) {
    echo "✅ Dossier '$upload_dir' existe<br>";
    
    // Vérifier les permissions
    if (is_writable($upload_dir)) {
        echo "✅ Dossier accessible en écriture<br>";
    } else {
        echo "❌ Dossier non accessible en écriture<br>";
    }
} else {
    echo "❌ Dossier '$upload_dir' n'existe pas<br>";
    echo "Création du dossier...<br>";
    if (mkdir($upload_dir, 0777, true)) {
        echo "✅ Dossier créé avec succès<br>";
    } else {
        echo "❌ Impossible de créer le dossier<br>";
    }
}

// 2. Lister les fichiers
echo "<h3>2. Fichiers dans le dossier</h3>";
if (is_dir($upload_dir)) {
    $files = scandir($upload_dir);
    $image_files = [];
    
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $file_path = $upload_dir . $file;
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                $image_files[] = $file;
                echo "📸 $file (" . filesize($file_path) . " bytes)<br>";
            } else {
                echo "📄 $file (non-image)<br>";
            }
        }
    }
    
    if (empty($image_files)) {
        echo "⚠️ Aucune image trouvée<br>";
    }
} else {
    echo "❌ Impossible de lire le dossier<br>";
}

// 3. Créer une image de test si nécessaire
echo "<h3>3. Création d'image de test</h3>";
$test_image = $upload_dir . 'test_membre.jpg';

if (!file_exists($test_image)) {
    // Créer une image de test
    $width = 160;
    $height = 180;
    $image = imagecreate($width, $height);
    
    // Couleurs
    $bg = imagecolorallocate($image, 220, 220, 220);
    $text_color = imagecolorallocate($image, 50, 50, 50);
    $border = imagecolorallocate($image, 0, 0, 0);
    
    // Fond et bordure
    imagefill($image, 0, 0, $bg);
    imagerectangle($image, 0, 0, $width-1, $height-1, $border);
    
    // Texte
    $text = "PHOTO TEST";
    $font = 5;
    $text_width = imagefontwidth($font) * strlen($text);
    $x = ($width - $text_width) / 2;
    $y = $height / 2 - 20;
    imagestring($image, $font, $x, $y, $text, $text_color);
    
    $info = "160x180";
    $info_width = imagefontwidth(3) * strlen($info);
    $info_x = ($width - $info_width) / 2;
    imagestring($image, 3, $info_x, $y + 30, $info, $text_color);
    
    // Sauvegarder
    if (imagejpeg($image, $test_image, 90)) {
        echo "✅ Image de test créée : $test_image<br>";
    } else {
        echo "❌ Impossible de créer l'image de test<br>";
    }
    
    imagedestroy($image);
} else {
    echo "✅ Image de test existe déjà : $test_image<br>";
}

// 4. Tester l'affichage
echo "<h3>4. Test d'affichage</h3>";
if (file_exists($test_image)) {
    echo "Image de test :<br>";
    echo "<img src='$test_image' style='border: 2px solid #000; max-width: 160px;' alt='Test'><br>";
    echo "Chemin : $test_image<br>";
    echo "Taille : " . filesize($test_image) . " bytes<br>";
} else {
    echo "❌ Aucune image à afficher<br>";
}

// 5. Vérifier la classe Membre
echo "<h3>5. Test de la classe Membre</h3>";
try {
    require_once 'classes/Membre.php';
    $membre_obj = new Membre();
    echo "✅ Classe Membre chargée avec succès<br>";
    
    // Tester la méthode uploadPhoto
    echo "✅ Méthode uploadPhoto disponible<br>";
} catch (Exception $e) {
    echo "❌ Erreur avec la classe Membre : " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h3>🚀 Actions</h3>";
echo "<a href='ajouter_membre_test.php' class='btn btn-primary'>Ajouter Membre Test</a> ";
echo "<a href='gestion_membres.php' class='btn btn-success'>Gestion Membres</a> ";
echo "<a href='test_carte.php' class='btn btn-info'>Test Carte</a>";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Images</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .btn { margin: 5px; padding: 8px 15px; text-decoration: none; border-radius: 4px; color: white; }
        .btn-primary { background: #007bff; }
        .btn-success { background: #28a745; }
        .btn-info { background: #17a2b8; }
    </style>
</head>
<body>
    <div class="alert alert-info">
        <strong>Instructions :</strong>
        <ol>
            <li>Vérifiez que tous les éléments sont ✅</li>
            <li>Si des erreurs ❌, corrigez les permissions du dossier</li>
            <li>Utilisez "Ajouter Membre Test" pour tester avec une vraie photo</li>
        </ol>
    </div>
</body>
</html>
