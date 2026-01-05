<?php
require_once 'classes/Membre.php';

// Vérifier si l'ID du membre est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID du membre requis');
}

$membre_obj = new Membre();
$membre = $membre_obj->obtenirParId($_GET['id']);

if (!$membre) {
    die('Membre non trouvé');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?php echo htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']); ?> - Les Jeunes du mouvement à L'APPEL DE KHOMBOLE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a5f2e 0%, #0b843e 50%, #f4e93d 100%);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }
        
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
            margin: 50px auto;
            max-width: 600px;
        }
        
        .profile-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            color: #0b843e;
            padding: 30px;
            text-align: center;
            border-bottom: 5px solid #0b843e;
            position: relative;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #0b843e 0%, #f4e93d 50%, #dc3545 100%);
        }

        .profile-header h2 {
            color: #0b843e;
        }

        .profile-header p {
            color: #dc3545;
            font-weight: 600;
        }
        
        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #0b843e;
            object-fit: cover;
            margin: 0 auto 20px;
            display: block;
            box-shadow: 0 5px 15px rgba(11, 132, 62, 0.3);
        }
        
        .profile-photo-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #0b843e;
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
            color: white;
            box-shadow: 0 5px 15px rgba(11, 132, 62, 0.3);
        }
        
        .profile-body {
            padding: 30px;
        }
        
        .info-row {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #0b843e;
        }
        
        .info-icon {
            width: 40px;
            height: 40px;
            background: #0b843e;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .info-content {
            flex-grow: 1;
        }
        
        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-actif {
            background: #d4edda;
            color: #155724;
        }
        
        .status-inactif {
            background: #f8d7da;
            color: #721c24;
        }
        
        .logo-footer {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-top: 3px solid #f4e93d;
        }
        
        .logo-footer img {
            height: 60px;
            opacity: 0.7;
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
        @media (max-width: 768px) {
            .profile-card {
                margin: 30px 15px;
            }

            .profile-header {
                padding: 25px 20px;
            }

            .profile-photo,
            .profile-photo-placeholder {
                width: 100px;
                height: 100px;
            }

            .profile-photo-placeholder {
                font-size: 35px;
            }

            .profile-header h2 {
                font-size: 1.5rem;
            }

            .profile-header p {
                font-size: 0.9rem;
            }

            .profile-body {
                padding: 25px 20px;
            }

            .info-row {
                padding: 12px;
                margin-bottom: 15px;
            }

            .info-icon {
                width: 35px;
                height: 35px;
                font-size: 14px;
            }

            .info-label {
                font-size: 12px;
            }

            .info-value {
                font-size: 14px;
            }

            .logo-footer {
                padding: 15px;
            }

            .logo-footer img {
                max-height: 60px !important;
            }

            .social-link {
                width: 35px;
                height: 35px;
                font-size: 16px;
            }
        }

        @media (max-width: 576px) {
            .profile-card {
                margin: 20px 10px;
                border-radius: 12px;
            }

            .profile-header {
                padding: 20px 15px;
            }

            .profile-photo,
            .profile-photo-placeholder {
                width: 80px;
                height: 80px;
            }

            .profile-photo-placeholder {
                font-size: 30px;
            }

            .profile-header h2 {
                font-size: 1.3rem;
            }

            .profile-header p {
                font-size: 0.85rem;
            }

            .profile-body {
                padding: 20px 15px;
            }

            .info-row {
                padding: 10px;
                margin-bottom: 12px;
                flex-direction: column;
                text-align: center;
            }

            .info-icon {
                width: 30px;
                height: 30px;
                font-size: 12px;
                margin: 0 auto 10px;
            }

            .info-label {
                font-size: 11px;
            }

            .info-value {
                font-size: 13px;
            }

            .status-badge {
                padding: 6px 12px;
                font-size: 12px;
            }

            .logo-footer {
                padding: 12px;
            }

            .logo-footer img {
                max-height: 50px !important;
            }

            .logo-footer p {
                font-size: 0.8rem;
            }

            .social-link {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }

            .btn-lg {
                padding: 10px 20px;
                font-size: 14px;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .profile-card {
                max-width: 700px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-card">
            <!-- En-tête du profil -->
            <div class="profile-header">
                <?php if ($membre['photo'] && file_exists('uploads/photos/' . $membre['photo'])): ?>
                    <img src="uploads/photos/<?php echo htmlspecialchars($membre['photo']); ?>" 
                         alt="Photo du membre" class="profile-photo">
                <?php else: ?>
                    <div class="profile-photo-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                <?php endif; ?>
                
                <h2 class="mb-2"><?php echo htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']); ?></h2>
                <p class="mb-0">Les Jeunes du mouvement à L'APPEL DE KHOMBOLE</p>
            </div>
            
            <!-- Corps du profil -->
            <div class="profile-body">
                <!-- Téléphone -->
                <div class="info-row">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Téléphone</div>
                        <div class="info-value"><?php echo htmlspecialchars($membre['telephone']); ?></div>
                    </div>
                </div>
                
                <!-- Adresse -->
                <div class="info-row">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Adresse</div>
                        <div class="info-value"><?php echo htmlspecialchars($membre['adresse']); ?></div>
                    </div>
                </div>
                
                <!-- Email -->
                <?php if ($membre['email']): ?>
                <div class="info-row">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Email</div>
                        <div class="info-value"><?php echo htmlspecialchars($membre['email']); ?></div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Date de naissance -->
                <?php if ($membre['date_naissance']): ?>
                <div class="info-row">
                    <div class="info-icon">
                        <i class="fas fa-birthday-cake"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Date de naissance</div>
                        <div class="info-value"><?php echo date('d/m/Y', strtotime($membre['date_naissance'])); ?></div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Rôle -->
                <div class="info-row">
                    <div class="info-icon">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Rôle</div>
                        <div class="info-value"><?php echo htmlspecialchars($membre['role']); ?></div>
                    </div>
                </div>
                
                <!-- Statut -->
                <div class="info-row">
                    <div class="info-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Statut</div>
                        <div class="info-value">
                            <span class="status-badge <?php echo $membre['statut'] === 'actif' ? 'status-actif' : 'status-inactif'; ?>">
                                <?php echo htmlspecialchars($membre['statut']); ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Date d'adhésion -->
                <div class="info-row">
                    <div class="info-icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Date d'adhésion</div>
                        <div class="info-value"><?php echo date('d/m/Y', strtotime($membre['date_adhesion'])); ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Pied de page avec logo -->
            <div class="logo-footer">
                <img src="logo.png" alt="Logo Les Jeunes du mouvement à L'APPEL DE KHOMBOLE" style="max-height: 80px;">
                <p class="mt-2 mb-2 text-muted small">Les Jeunes du mouvement à L'APPEL DE KHOMBOLE</p>
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
                <p class="mt-2 mb-0 text-muted small">
                    <a href="mailto:jeunesalappel@gmail.com" class="text-muted text-decoration-none">jeunesalappel@gmail.com</a> | 
                    <a href="tel:+221772862728" class="text-muted text-decoration-none">+221 77 286 27 28</a>
                </p>
            </div>
        </div>
        
        <!-- Bouton retour -->
        <div class="text-center mb-4">
            <a href="index.php" class="btn btn-light btn-lg">
                <i class="fas fa-home me-2"></i>
                Retour à l'accueil
            </a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
