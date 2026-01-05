<?php
require_once 'config/database.php';
require_once 'classes/Membre.php';

$membre_obj = new Membre();
$membres = $membre_obj->obtenirTous();

if (count($membres) > 0) {
    echo "Premier membre trouvé:\n";
    echo "Nom: " . $membres[0]['nom'] . "\n";
    echo "Prénom: " . $membres[0]['prenom'] . "\n";
    echo "Rôle: " . $membres[0]['role'] . "\n";
    echo "QR Code: " . $membres[0]['qr_code'] . "\n";
    echo "\nToutes les clés disponibles:\n";
    print_r(array_keys($membres[0]));
} else {
    echo "Aucun membre dans la base de données\n";
}
?>
