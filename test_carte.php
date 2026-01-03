<?php
// Test simple pour vérifier la carte avec un membre de test
require_once 'classes/Membre.php';

$membre_obj = new Membre();

// Créer un membre de test
$data_test = [
    'nom' => 'DIOP',
    'prenom' => 'Amadou',
    'date_naissance' => '1990-05-15',
    'telephone' => '77 123 45 67',
    'email' => 'amadou.diop@email.com',
    'adresse' => 'Khombole, Quartier Nord, Rue de la Paix',
    'photo' => null,
    'role' => 'Président',
    'statut' => 'Actif',
    'date_adhesion' => '2024-01-15'
];

echo "<h2>Test de la carte de membre</h2>";
echo "<p>Données du membre de test :</p>";
echo "<pre>";
print_r($data_test);
echo "</pre>";

// Ajouter le membre (si pas déjà existant)
$id_test = $membre_obj->ajouter($data_test);
if ($id_test) {
    echo "<p>Membre de test créé avec l'ID : $id_test</p>";
    echo "<p><a href='generer_carte.php?id=$id_test' target='_blank'>Voir la carte générée</a></p>";
} else {
    // Essayer de trouver le membre existant
    $membres = $membre_obj->rechercher('Amadou DIOP');
    if (!empty($membres)) {
        $id_test = $membres[0]['id'];
        echo "<p>Membre existant trouvé avec l'ID : $id_test</p>";
        echo "<p><a href='generer_carte.php?id=$id_test' target='_blank'>Voir la carte générée</a></p>";
    }
}

echo "<p><a href='gestion_membres.php'>Retour à la gestion des membres</a></p>";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Carte Membre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <div class="alert alert-info">
        <h4>Instructions de test :</h4>
        <ol>
            <li>Cliquez sur le lien "Voir la carte générée" ci-dessus</li>
            <li>Vérifiez que la carte s'affiche correctement avec :
                <ul>
                    <li>Le logo en haut à gauche</li>
                    <li>Le titre "CARTE MEMBRE"</li>
                    <li>Le sous-titre "ACTION POUR LE PROGRÈS D'UNE EXIGENCE LOCALE"</li>
                    <li>La photo (ou placeholder)</li>
                    <li>Le prénom et nom en majuscules</li>
                    <li>L'adresse</li>
                    <li>Le numéro de téléphone</li>
                    <li>Le QR code</li>
                </ul>
            </li>
            <li>Testez le QR code en allant sur <a href="scanner_qr.php">scanner_qr.php</a></li>
        </ol>
    </div>
</body>
</html>
