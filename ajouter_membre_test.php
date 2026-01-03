<?php
require_once 'classes/Membre.php';

$message = '';
$type_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $membre_obj = new Membre();
    
    // Gérer l'upload de photo
    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $photo = $membre_obj->uploadPhoto($_FILES['photo']);
        if (!$photo) {
            $message = "Erreur lors de l'upload de la photo.";
            $type_message = "danger";
        }
    }
    
    if (!$message) {
        $data = [
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'date_naissance' => $_POST['date_naissance'] ?: null,
            'telephone' => $_POST['telephone'],
            'email' => $_POST['email'] ?: null,
            'adresse' => $_POST['adresse'],
            'photo' => $photo,
            'role' => $_POST['role'],
            'statut' => 'Actif',
            'date_adhesion' => date('Y-m-d')
        ];
        
        $id = $membre_obj->ajouter($data);
        if ($id) {
            $message = "Membre ajouté avec succès ! ID: $id";
            $type_message = "success";
            echo "<script>setTimeout(function(){ window.location.href='generer_carte.php?id=$id'; }, 2000);</script>";
        } else {
            $message = "Erreur lors de l'ajout du membre.";
            $type_message = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Membre Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4><i class="fas fa-user-plus me-2"></i>Ajouter un Membre de Test</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($message): ?>
                            <div class="alert alert-<?php echo $type_message; ?> alert-dismissible fade show" role="alert">
                                <?php echo $message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Prénom *</label>
                                        <input type="text" class="form-control" name="prenom" value="Samba" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nom *</label>
                                        <input type="text" class="form-control" name="nom" value="SY" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Téléphone *</label>
                                        <input type="tel" class="form-control" name="telephone" value="77 123 45 67" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="samba.sy@email.com">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Date de naissance</label>
                                <input type="date" class="form-control" name="date_naissance" value="1990-01-01">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Adresse *</label>
                                <textarea class="form-control" name="adresse" rows="2" required>Khombole, Quartier Centre, Rue de la République</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Rôle</label>
                                <select class="form-select" name="role">
                                    <option value="Membre">Membre</option>
                                    <option value="Responsable">Responsable</option>
                                    <option value="Président" selected>Président</option>
                                    <option value="Vice-Président">Vice-Président</option>
                                    <option value="Secrétaire">Secrétaire</option>
                                    <option value="Trésorier">Trésorier</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Photo (JPG, PNG, GIF - Max 5MB)</label>
                                <input type="file" class="form-control" name="photo" accept="image/*">
                                <div class="form-text">Sélectionnez une photo pour tester l'affichage sur la carte</div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-plus me-2"></i>Ajouter et Générer la Carte
                                </button>
                            </div>
                        </form>
                        
                        <hr>
                        <div class="text-center">
                            <a href="gestion_membres.php" class="btn btn-outline-primary me-2">
                                <i class="fas fa-users me-2"></i>Gestion des Membres
                            </a>
                            <a href="test_photo.php" class="btn btn-outline-info">
                                <i class="fas fa-camera me-2"></i>Test Photos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>
