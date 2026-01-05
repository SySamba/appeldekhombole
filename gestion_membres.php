<?php
require_once 'auth_check.php';
require_once 'classes/Membre.php';

$membre_obj = new Membre();
$message = '';
$type_message = '';

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'ajouter':
                $photo = null;
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                    $photo = $membre_obj->uploadPhoto($_FILES['photo']);
                }
                
                $data = [
                    'nom' => $_POST['nom'],
                    'prenom' => $_POST['prenom'],
                    'date_naissance' => $_POST['date_naissance'] ?: null,
                    'telephone' => $_POST['telephone'],
                    'email' => $_POST['email'] ?: null,
                    'adresse' => $_POST['adresse'],
                    'photo' => $photo,
                    'role' => $_POST['role'],
                    'statut' => $_POST['statut'],
                    'date_adhesion' => $_POST['date_adhesion']
                ];
                
                if ($membre_obj->ajouter($data)) {
                    $message = "Membre ajouté avec succès !";
                    $type_message = "success";
                } else {
                    $message = "Erreur lors de l'ajout du membre.";
                    $type_message = "danger";
                }
                break;
                
            case 'modifier':
                $photo = $_POST['photo_actuelle'];
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                    $new_photo = $membre_obj->uploadPhoto($_FILES['photo']);
                    if ($new_photo) {
                        $photo = $new_photo;
                    }
                }
                
                $data = [
                    'nom' => $_POST['nom'],
                    'prenom' => $_POST['prenom'],
                    'date_naissance' => $_POST['date_naissance'] ?: null,
                    'telephone' => $_POST['telephone'],
                    'email' => $_POST['email'] ?: null,
                    'adresse' => $_POST['adresse'],
                    'photo' => $photo,
                    'role' => $_POST['role'],
                    'statut' => $_POST['statut']
                ];
                
                if ($membre_obj->modifier($_POST['id'], $data)) {
                    $message = "Membre modifié avec succès !";
                    $type_message = "success";
                } else {
                    $message = "Erreur lors de la modification.";
                    $type_message = "danger";
                }
                break;
                
            case 'supprimer':
                if ($membre_obj->supprimer($_POST['id'])) {
                    $message = "Membre supprimé avec succès !";
                    $type_message = "success";
                } else {
                    $message = "Erreur lors de la suppression.";
                    $type_message = "danger";
                }
                break;
        }
    }
}

// Recherche
$terme_recherche = isset($_GET['recherche']) ? $_GET['recherche'] : '';
if ($terme_recherche) {
    $membres = $membre_obj->rechercher($terme_recherche);
} else {
    $membres = $membre_obj->obtenirTous();
}

$stats = $membre_obj->obtenirStatistiques();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Membres - Les Jeunes du mouvement à L'APPEL DE KHOMBOLE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a5f2e 0%, #0b843e 50%, #f4e93d 100%);
            min-height: 100vh;
        }

        .main-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin: 20px auto;
            overflow: hidden;
        }

        .header-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            color: #0b843e;
            padding: 30px;
            border-bottom: 5px solid #0b843e;
            position: relative;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #0b843e 0%, #f4e93d 50%, #dc3545 100%);
        }

        .header-section h1 {
            color: #0b843e;
        }

        .header-section p {
            color: #dc3545;
            font-weight: 600;
        }

        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border: 2px solid #0b843e;
        }

        .stats-card h3 {
            color: #0b843e;
            font-weight: 700;
        }

        .stats-card small {
            color: #666;
            font-weight: 600;
        }

        .membre-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-left: 4px solid #0b843e;
            transition: all 0.3s ease;
        }

        .membre-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .photo-membre-small {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid #0b843e;
            object-fit: cover;
        }

        .btn-khombole {
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 100%);
            border: none;
            color: white;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-khombole:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }

        .search-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 20px;
            border-radius: 0;
            margin: 0;
            border-bottom: 3px solid #f4e93d;
        }

        .modal-header {
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 100%);
            color: white;
            border-bottom: 3px solid #f4e93d;
        }

        .footer-section {
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 100%);
            color: white;
            padding: 30px;
            text-align: center;
            margin-top: 0;
        }

        .social-links {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 15px;
        }

        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 18px;
        }

        .social-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .social-instagram {
            background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);
        }

        .social-facebook {
            background: #1877f2;
        }

        .social-email {
            background: #0b843e;
        }

        .social-phone {
            background: #25d366;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .header-section .row {
                flex-direction: column;
            }

            .header-section .col-md-6 {
                width: 100%;
                margin-bottom: 20px;
            }

            .stats-card {
                margin-bottom: 10px;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                margin: 10px;
                border-radius: 15px;
            }

            .header-section {
                padding: 25px 20px;
            }

            .header-section h1 {
                font-size: 1.5rem;
            }

            .stats-card {
                padding: 15px;
            }

            .stats-card h3 {
                font-size: 1.5rem;
            }

            .search-section {
                padding: 15px;
            }

            .search-section .row {
                flex-direction: column;
                gap: 15px;
            }

            .search-section .col-md-6 {
                width: 100%;
            }

            .search-section .text-end {
                text-align: center !important;
            }

            .btn-khombole,
            .btn-outline-success,
            .btn-outline-dark {
                margin-bottom: 10px;
                width: 100%;
            }

            .membre-card {
                padding: 15px;
            }

            .photo-membre-small {
                width: 45px;
                height: 45px;
            }

            .footer-section {
                padding: 25px 15px;
            }

            .modal-body {
                padding: 20px 15px;
            }
        }

        @media (max-width: 576px) {
            .main-container {
                margin: 5px;
                border-radius: 12px;
            }

            .header-section {
                padding: 20px 15px;
            }

            .header-section h1 {
                font-size: 1.3rem;
            }

            .header-section p {
                font-size: 0.85rem;
            }

            .stats-card {
                padding: 12px;
            }

            .stats-card h3 {
                font-size: 1.3rem;
            }

            .stats-card small {
                font-size: 0.75rem;
            }

            .search-section {
                padding: 12px;
            }

            #searchInput {
                font-size: 14px;
            }

            .btn-khombole,
            .btn-outline-success,
            .btn-outline-dark {
                padding: 8px 16px;
                font-size: 13px;
            }

            .membre-card {
                padding: 12px;
                margin-bottom: 15px;
            }

            .membre-card h6 {
                font-size: 0.95rem;
            }

            .membre-card small {
                font-size: 0.75rem;
            }

            .photo-membre-small {
                width: 40px;
                height: 40px;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn-group .btn {
                border-radius: 5px !important;
                margin-bottom: 5px;
            }

            .footer-section {
                padding: 20px 12px;
            }

            .footer-section h6 {
                font-size: 0.9rem;
            }

            .footer-section small {
                font-size: 0.75rem;
            }

            .social-link {
                width: 35px;
                height: 35px;
                font-size: 16px;
            }

            .modal-dialog {
                margin: 10px;
            }

            .modal-body {
                padding: 15px;
            }

            .modal-header h5 {
                font-size: 1.1rem;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .header-section {
                padding: 28px;
            }

            .membre-card {
                padding: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-container">
            <!-- En-tête -->
            <div class="header-section">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <a href="index.php" class="btn btn-outline-success mb-2">
                            <i class="fas fa-home me-2"></i>Accueil
                        </a>
                        <h1><i class="fas fa-users me-3"></i>Gestion des Membres</h1>
                        <p class="mb-0">Les Jeunes du mouvement à L'APPEL DE KHOMBOLE</p>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <div class="stats-card">
                                    <h3><?php echo $stats['total']; ?></h3>
                                    <small>Total Membres</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stats-card">
                                    <h3><?php echo $stats['actifs']; ?></h3>
                                    <small>Membres Actifs</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stats-card">
                                    <h3><?php echo count($stats['roles']); ?></h3>
                                    <small>Rôles Différents</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($message): ?>
            <div class="alert alert-<?php echo $type_message; ?> alert-dismissible fade show m-3" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Section de recherche et actions -->
            <div class="search-section">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="position-relative">
                            <input type="text" class="form-control" id="searchInput" 
                                   placeholder="Rechercher un membre (nom, prénom, téléphone, rôle)..." 
                                   value="<?php echo htmlspecialchars($terme_recherche); ?>">
                            <i class="fas fa-search position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); color: #0b843e;"></i>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-khombole me-2" data-bs-toggle="modal" data-bs-target="#ajouterModal">
                            <i class="fas fa-plus me-2"></i>Ajouter Membre
                        </button>
                        <a href="scanner_qr.php" class="btn btn-outline-success">
                            <i class="fas fa-qrcode me-2"></i>Scanner QR
                        </a>
                        <a href="index.php" class="btn btn-outline-dark">
                            <i class="fas fa-home me-2"></i>Accueil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Liste des membres -->
            <div class="p-3">
                <?php if (empty($membres)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h4>Aucun membre trouvé</h4>
                        <p class="text-muted">
                            <?php echo $terme_recherche ? 'Aucun résultat pour votre recherche.' : 'Commencez par ajouter des membres.'; ?>
                        </p>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($membres as $membre): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="membre-card">
                                    <div class="d-flex align-items-center mb-3">
                                        <?php if ($membre['photo'] && file_exists('uploads/photos/' . $membre['photo'])): ?>
                                            <img src="uploads/photos/<?php echo htmlspecialchars($membre['photo']); ?>" 
                                                 alt="Photo" class="photo-membre-small me-3">
                                        <?php else: ?>
                                            <div class="photo-membre-small me-3 d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']); ?></h6>
                                            <small class="text-muted"><?php echo htmlspecialchars($membre['role']); ?></small>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small><i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($membre['telephone']); ?></small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <span class="badge bg-<?php echo $membre['statut'] === 'Actif' ? 'success' : ($membre['statut'] === 'Inactif' ? 'secondary' : 'warning'); ?>">
                                            <?php echo htmlspecialchars($membre['statut']); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="btn-group w-100 mb-2" role="group">
                                        <a href="generer_carte.php?id=<?php echo $membre['id']; ?>" 
                                           class="btn btn-sm btn-success" title="Voir carte">
                                            <i class="fas fa-id-card"></i>
                                        </a>
                                        <button class="btn btn-sm btn-primary" 
                                                onclick="modifierMembre(<?php echo htmlspecialchars(json_encode($membre)); ?>)" 
                                                title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" 
                                                onclick="confirmerSuppression(<?php echo $membre['id']; ?>, '<?php echo htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']); ?>')" 
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <a href="envoyer_invitation_whatsapp.php?id=<?php echo $membre['id']; ?>" 
                                       class="btn btn-sm w-100" 
                                       style="background: #25d366; color: white;"
                                       title="Inviter sur WhatsApp">
                                        <i class="fab fa-whatsapp me-1"></i>Inviter sur WhatsApp
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Footer -->
            <div class="footer-section">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <h6><i class="fas fa-heart me-2"></i>À propos</h6>
                        <small>Les Jeunes du mouvement à L'APPEL DE KHOMBOLE</small>
                    </div>
                    <div class="col-md-4 text-center mb-3">
                        <h6><i class="fas fa-envelope me-2"></i>Contact</h6>
                        <small>
                            <a href="mailto:jeunesalappel@gmail.com" class="text-white text-decoration-none d-block">
                                jeunesalappel@gmail.com
                            </a>
                            <a href="tel:+221772862728" class="text-white text-decoration-none d-block">
                                +221 77 286 27 28
                            </a>
                        </small>
                    </div>
                    <div class="col-md-4 text-center mb-3">
                        <h6><i class="fas fa-share-alt me-2"></i>Suivez-nous</h6>
                        <div class="social-links">
                            <a href="https://www.instagram.com/lesjeunesdumvtalappel20/" target="_blank" class="social-link social-instagram" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://www.facebook.com/profile.php?id=61585996523452" target="_blank" class="social-link social-facebook" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="mailto:jeunesalappel@gmail.com" class="social-link social-email" title="Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <a href="tel:+221772862728" class="social-link social-phone" title="Téléphone">
                                <i class="fas fa-phone"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajouter Membre -->
    <div class="modal fade" id="ajouterModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Ajouter un Membre</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="ajouter">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nom *</label>
                                    <input type="text" class="form-control" name="nom" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Prénom *</label>
                                    <input type="text" class="form-control" name="prenom" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Téléphone *</label>
                                    <input type="tel" class="form-control" name="telephone" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date de naissance</label>
                                    <input type="date" class="form-control" name="date_naissance">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date d'adhésion *</label>
                                    <input type="date" class="form-control" name="date_adhesion" 
                                           value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Adresse *</label>
                            <textarea class="form-control" name="adresse" rows="2" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Rôle</label>
                                    <select class="form-select" name="role">
                                        <option value="Membre">Membre</option>
                                        <option value="Responsable">Responsable</option>
                                        <option value="Président">Président</option>
                                        <option value="Vice-Président">Vice-Président</option>
                                        <option value="Secrétaire">Secrétaire</option>
                                        <option value="Trésorier">Trésorier</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Statut</label>
                                    <select class="form-select" name="statut">
                                        <option value="Actif">Actif</option>
                                        <option value="Inactif">Inactif</option>
                                        <option value="Suspendu">Suspendu</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Photo</label>
                            <input type="file" class="form-control" name="photo" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-khombole">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Modifier Membre -->
    <div class="modal fade" id="modifierModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Modifier le Membre</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data" id="formModifier">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="modifier">
                        <input type="hidden" name="id" id="modifier_id">
                        <input type="hidden" name="photo_actuelle" id="modifier_photo_actuelle">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nom *</label>
                                    <input type="text" class="form-control" name="nom" id="modifier_nom" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Prénom *</label>
                                    <input type="text" class="form-control" name="prenom" id="modifier_prenom" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Téléphone *</label>
                                    <input type="tel" class="form-control" name="telephone" id="modifier_telephone" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="modifier_email">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date de naissance</label>
                                    <input type="date" class="form-control" name="date_naissance" id="modifier_date_naissance">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Rôle</label>
                                    <select class="form-select" name="role" id="modifier_role">
                                        <option value="Membre">Membre</option>
                                        <option value="Responsable">Responsable</option>
                                        <option value="Président">Président</option>
                                        <option value="Vice-Président">Vice-Président</option>
                                        <option value="Secrétaire">Secrétaire</option>
                                        <option value="Trésorier">Trésorier</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Adresse *</label>
                            <textarea class="form-control" name="adresse" rows="2" id="modifier_adresse" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Statut</label>
                                    <select class="form-select" name="statut" id="modifier_statut">
                                        <option value="Actif">Actif</option>
                                        <option value="Inactif">Inactif</option>
                                        <option value="Suspendu">Suspendu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nouvelle photo</label>
                                    <input type="file" class="form-control" name="photo" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-khombole">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Confirmation Suppression -->
    <div class="modal fade" id="suppressionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmer la suppression</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer le membre <strong id="nom_suppression"></strong> ?</p>
                    <p class="text-danger"><small>Cette action est irréversible.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="supprimer">
                        <input type="hidden" name="id" id="id_suppression">
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Recherche automatique en temps réel
        const searchInput = document.getElementById('searchInput');
        const membreCards = document.querySelectorAll('.membre-card');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;
            
            membreCards.forEach(card => {
                const cardParent = card.closest('.col-md-6');
                const nom = card.querySelector('h6') ? card.querySelector('h6').textContent.toLowerCase() : '';
                const role = card.querySelector('.text-muted') ? card.querySelector('.text-muted').textContent.toLowerCase() : '';
                const phone = card.querySelector('.fa-phone') ? card.querySelector('.fa-phone').parentElement.textContent.toLowerCase() : '';
                
                if (nom.includes(searchTerm) || role.includes(searchTerm) || phone.includes(searchTerm)) {
                    cardParent.style.display = '';
                    card.style.animation = 'fadeIn 0.3s ease';
                    visibleCount++;
                } else {
                    cardParent.style.display = 'none';
                }
            });
            
            // Afficher un message si aucun résultat
            const noResultMsg = document.getElementById('noResultMessage');
            if (visibleCount === 0 && searchTerm !== '') {
                if (!noResultMsg) {
                    const msg = document.createElement('div');
                    msg.id = 'noResultMessage';
                    msg.className = 'col-12 text-center py-5';
                    msg.innerHTML = '<i class="fas fa-search fa-3x text-muted mb-3"></i><h4>Aucun membre trouvé</h4><p class="text-muted">Essayez avec un autre terme de recherche</p>';
                    document.querySelector('.row').appendChild(msg);
                }
            } else if (noResultMsg) {
                noResultMsg.remove();
            }
        });
        
        function modifierMembre(membre) {
            document.getElementById('modifier_id').value = membre.id;
            document.getElementById('modifier_nom').value = membre.nom;
            document.getElementById('modifier_prenom').value = membre.prenom;
            document.getElementById('modifier_telephone').value = membre.telephone;
            document.getElementById('modifier_email').value = membre.email || '';
            document.getElementById('modifier_date_naissance').value = membre.date_naissance || '';
            document.getElementById('modifier_adresse').value = membre.adresse;
            document.getElementById('modifier_role').value = membre.role;
            document.getElementById('modifier_statut').value = membre.statut;
            document.getElementById('modifier_photo_actuelle').value = membre.photo || '';
            
            new bootstrap.Modal(document.getElementById('modifierModal')).show();
        }

        function confirmerSuppression(id, nom) {
            document.getElementById('id_suppression').value = id;
            document.getElementById('nom_suppression').textContent = nom;
            new bootstrap.Modal(document.getElementById('suppressionModal')).show();
        }
    </script>
</body>
</html>
