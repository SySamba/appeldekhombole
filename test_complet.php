<?php
require_once 'classes/Membre.php';

echo "<h1>🧪 Test Complet du Système</h1>";

// 1. Test de la base de données
echo "<h2>1. Test Base de Données</h2>";
try {
    $membre_obj = new Membre();
    echo "✅ Connexion à la base de données réussie<br>";
    
    $stats = $membre_obj->obtenirStatistiques();
    echo "✅ Statistiques récupérées : " . $stats['total'] . " membres<br>";
} catch (Exception $e) {
    echo "❌ Erreur base de données : " . $e->getMessage() . "<br>";
}

// 2. Test du dossier images
echo "<h2>2. Test Dossier Images</h2>";
$upload_dir = 'uploads/photos/';
if (is_dir($upload_dir)) {
    echo "✅ Dossier uploads/photos/ existe<br>";
    if (is_writable($upload_dir)) {
        echo "✅ Dossier accessible en écriture<br>";
    } else {
        echo "❌ Dossier non accessible en écriture<br>";
    }
} else {
    echo "❌ Dossier uploads/photos/ n'existe pas<br>";
    if (mkdir($upload_dir, 0777, true)) {
        echo "✅ Dossier créé avec succès<br>";
    }
}

// 3. Créer une image de test si elle n'existe pas
echo "<h2>3. Création Image de Test</h2>";
$test_image = $upload_dir . 'membre_test.jpg';
if (!file_exists($test_image)) {
    $width = 160;
    $height = 180;
    $image = imagecreate($width, $height);
    
    $bg = imagecolorallocate($image, 240, 240, 240);
    $text_color = imagecolorallocate($image, 0, 0, 0);
    $border = imagecolorallocate($image, 0, 0, 0);
    
    imagefill($image, 0, 0, $bg);
    imagerectangle($image, 0, 0, $width-1, $height-1, $border);
    
    $text = "SAMBA SY";
    imagestring($image, 5, 40, 80, $text, $text_color);
    imagestring($image, 3, 50, 100, "MEMBRE", $text_color);
    
    if (imagejpeg($image, $test_image, 90)) {
        echo "✅ Image de test créée : $test_image<br>";
    } else {
        echo "❌ Impossible de créer l'image<br>";
    }
    imagedestroy($image);
} else {
    echo "✅ Image de test existe : $test_image<br>";
}

// 4. Ajouter un membre de test avec photo
echo "<h2>4. Test Ajout Membre avec Photo</h2>";
try {
    // Vérifier si le membre test existe déjà
    $membres_test = $membre_obj->rechercher('SAMBA SY');
    
    if (empty($membres_test)) {
        $data_test = [
            'nom' => 'SY',
            'prenom' => 'SAMBA',
            'date_naissance' => '1990-01-01',
            'telephone' => '77 123 45 67',
            'email' => 'samba.sy@email.com',
            'adresse' => 'Khombole, Quartier Centre, Rue de la République',
            'photo' => 'membre_test.jpg', // Utiliser l'image créée
            'role' => 'Président',
            'statut' => 'Actif',
            'date_adhesion' => date('Y-m-d')
        ];
        
        $id_test = $membre_obj->ajouter($data_test);
        if ($id_test) {
            echo "✅ Membre de test ajouté avec ID : $id_test<br>";
            echo "<a href='generer_carte.php?id=$id_test' target='_blank' class='btn btn-success'>Voir la Carte</a><br>";
        } else {
            echo "❌ Erreur lors de l'ajout du membre<br>";
        }
    } else {
        $id_test = $membres_test[0]['id'];
        echo "✅ Membre de test existe déjà avec ID : $id_test<br>";
        echo "<a href='generer_carte.php?id=$id_test' target='_blank' class='btn btn-success'>Voir la Carte</a><br>";
    }
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "<br>";
}

// 5. Test d'affichage de l'image
echo "<h2>5. Test Affichage Image</h2>";
if (file_exists($test_image)) {
    echo "Image de test :<br>";
    echo "<img src='$test_image' style='border: 3px solid #000; width: 160px; height: 180px; object-fit: cover;' alt='Test'><br>";
} else {
    echo "❌ Image non trouvée<br>";
}

echo "<hr>";
echo "<h2>🚀 Actions de Test</h2>";
echo "<div style='margin: 10px 0;'>";
echo "<a href='debug_images.php' class='btn btn-info'>Debug Images</a> ";
echo "<a href='ajouter_membre_test.php' class='btn btn-primary'>Ajouter Membre</a> ";
echo "<a href='gestion_membres.php' class='btn btn-success'>Gestion Membres</a> ";
echo "<a href='scanner_qr.php' class='btn btn-warning'>Scanner QR</a>";
echo "</div>";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Complet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .btn { 
            display: inline-block;
            margin: 5px; 
            padding: 8px 15px; 
            text-decoration: none; 
            border-radius: 4px; 
            color: white; 
            font-weight: bold;
        }
        .btn-primary { background: #007bff; }
        .btn-success { background: #28a745; }
        .btn-info { background: #17a2b8; }
        .btn-warning { background: #ffc107; color: #000; }
        h1, h2 { color: #333; }
    </style>
</head>
<body>
    <div class="alert alert-success">
        <strong>✅ Test Complet Terminé !</strong><br>
        Si tous les éléments sont ✅, le système fonctionne correctement.
        <br>Cliquez sur "Voir la Carte" pour tester l'affichage.
    </div>
</body>
</html>
