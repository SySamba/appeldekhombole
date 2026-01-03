<?php
// Créer une image de test simple
$width = 200;
$height = 240;

// Créer une image
$image = imagecreate($width, $height);

// Définir les couleurs
$bg_color = imagecolorallocate($image, 240, 240, 240); // Gris clair
$text_color = imagecolorallocate($image, 0, 0, 0); // Noir
$border_color = imagecolorallocate($image, 100, 100, 100); // Gris foncé

// Remplir le fond
imagefill($image, 0, 0, $bg_color);

// Ajouter une bordure
imagerectangle($image, 0, 0, $width-1, $height-1, $border_color);

// Ajouter du texte
$text = "PHOTO TEST";
$font_size = 5;
$text_width = imagefontwidth($font_size) * strlen($text);
$text_height = imagefontheight($font_size);
$x = ($width - $text_width) / 2;
$y = ($height - $text_height) / 2;

imagestring($image, $font_size, $x, $y, $text, $text_color);

// Ajouter des informations supplémentaires
$info = "160x180px";
$info_width = imagefontwidth(3) * strlen($info);
$info_x = ($width - $info_width) / 2;
$info_y = $y + 30;
imagestring($image, 3, $info_x, $info_y, $info, $text_color);

// Sauvegarder l'image
$filename = 'uploads/photos/test_photo.jpg';
imagejpeg($image, $filename, 90);
imagedestroy($image);

echo "<h2>Image de test créée !</h2>";
echo "<p>L'image de test a été créée dans : <code>$filename</code></p>";
echo "<img src='$filename' alt='Image de test' style='border: 2px solid #000; max-width: 200px;'>";
echo "<p><a href='test_photo.php'>Vérifier les photos</a></p>";
echo "<p><a href='ajouter_membre_test.php'>Ajouter un membre avec cette photo</a></p>";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer Image Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <div class="alert alert-success">
        <h4>Image de test créée avec succès !</h4>
        <p>Vous pouvez maintenant tester l'affichage des photos dans les cartes de membres.</p>
    </div>
</body>
</html>
