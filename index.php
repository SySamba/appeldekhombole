<?php
require_once 'auth_check.php';
require_once 'classes/Membre.php';

$membre_obj = new Membre();
$stats = $membre_obj->obtenirStatistiques();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Jeunes du mouvement à L'APPEL DE KHOMBOLE - Gestion des Membres</title>
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

        .hero-section {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin: 40px auto;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .hero-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            color: #0b843e;
            padding: 60px 40px;
            text-align: center;
            position: relative;
            border-bottom: 5px solid #0b843e;
        }

        .hero-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #0b843e 0%, #f4e93d 50%, #dc3545 100%);
        }

        .logo-hero {
            width: 120px;
            height: 120px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            background: white;
            padding: 10px;
        }

        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border-top: 4px solid #0b843e;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 32px;
            box-shadow: 0 5px 15px rgba(11, 132, 62, 0.3);
        }

        .btn-khombole {
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 100%);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-khombole:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            color: white;
        }

        .btn-secondary-khombole {
            background: white;
            border: 2px solid #0b843e;
            color: #0b843e;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .btn-secondary-khombole:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            background: #0b843e;
            color: white;
        }

        .stats-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 40px;
            border-radius: 0;
            margin: 0;
            border-bottom: 3px solid #f4e93d;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
        }

        .stat-number {
            font-size: 48px;
            font-weight: 700;
            color: #0b843e;
            display: block;
        }

        .stat-label {
            color: #666;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 14px;
        }

        .footer-section {
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 100%);
            color: white;
            padding: 40px;
            text-align: center;
            margin-top: 0;
            border-radius: 0 0 20px 20px;
        }

        .association-name {
            font-size: 2.5rem;
            font-weight: 700;
            color: #0b843e;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 10px;
        }

        .association-subtitle {
            font-size: 1.1rem;
            color: #666;
            font-weight: 500;
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
            .hero-header {
                padding: 40px 20px;
            }

            .logo-hero {
                width: 80px;
                height: 80px;
            }

            .association-name {
                font-size: 1.8rem;
            }

            .hero-header h2 {
                font-size: 1.5rem;
            }

            .stats-section {
                padding: 30px 20px;
            }

            .stat-number {
                font-size: 36px;
            }

            .stat-label {
                font-size: 12px;
            }

            .feature-card {
                padding: 25px;
                margin-bottom: 20px;
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }

            .btn-khombole,
            .btn-secondary-khombole {
                padding: 12px 24px;
                font-size: 14px;
            }

            .footer-section {
                padding: 30px 20px;
            }

            .footer-section .row > div {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 0;
            }

            .container {
                padding: 0;
            }

            .hero-section {
                margin: 10px;
                border-radius: 15px;
            }

            .hero-header {
                padding: 30px 15px;
            }

            .logo-hero {
                width: 70px;
                height: 70px;
            }

            .association-name {
                font-size: 1.5rem;
            }

            .hero-header h2 {
                font-size: 1.2rem;
            }

            .association-subtitle {
                font-size: 0.9rem;
            }

            .stats-section {
                padding: 20px 10px;
            }

            .stat-item {
                padding: 15px 10px;
            }

            .stat-number {
                font-size: 28px;
            }

            .stat-label {
                font-size: 11px;
            }

            .container.py-5 {
                padding-top: 2rem !important;
                padding-bottom: 2rem !important;
            }

            .feature-card {
                padding: 20px;
            }

            .feature-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }

            .feature-card h4 {
                font-size: 1.1rem;
            }

            .feature-card p {
                font-size: 0.9rem;
            }

            .btn-khombole,
            .btn-secondary-khombole {
                padding: 10px 20px;
                font-size: 13px;
            }

            .footer-section {
                padding: 25px 15px;
            }

            .footer-section h5,
            .footer-section h6 {
                font-size: 1rem;
            }

            .footer-section p,
            .footer-section small {
                font-size: 0.85rem;
            }

            .social-link {
                width: 35px;
                height: 35px;
                font-size: 16px;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .hero-header {
                padding: 50px 30px;
            }

            .feature-card {
                padding: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="hero-section">
            <!-- En-tête Hero -->
            <div class="hero-header">
                <div class="text-end mb-3">
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                    </a>
                </div>
                <img src="logo.png" alt="Logo Association" class="logo-hero">
                <h1 class="association-name mb-2">Les Jeunes du mouvement</h1>
                <h2 class="display-5 fw-bold mb-3" style="color: #dc3545;">À L'APPEL DE KHOMBOLE</h2>
                <p class="association-subtitle mb-0">Système de Gestion des Membres avec Cartes QR</p>
            </div>

            <!-- Statistiques -->
            <div class="stats-section stats-section">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $stats['total']; ?></span>
                            <div class="stat-label">Membres Total</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $stats['actifs']; ?></span>
                            <div class="stat-label">Membres Actifs</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo count($stats['roles']); ?></span>
                            <div class="stat-label">Rôles Différents</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fonctionnalités -->
            <div class="container py-5 features-section">
                <h2 class="text-center mb-5 fw-bold" style="color: #0b843e;">Fonctionnalités Principales</h2>
                
                <div class="row g-4 justify-content-center">
                    <div class="col-md-5">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4 class="mb-3" style="color: #0b843e;">Gestion des Membres</h4>
                            <p class="text-muted mb-4">Ajoutez, modifiez et gérez tous vos membres. Générez des cartes professionnelles avec QR codes intégrés.</p>
                            <a href="gestion_membres.php" class="btn-khombole">
                                <i class="fas fa-arrow-right me-2"></i>Accéder
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-5">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <h4 class="mb-3" style="color: #0b843e;">Scanner QR Codes</h4>
                            <p class="text-muted mb-4">Scannez les QR codes pour afficher instantanément les informations complètes des membres.</p>
                            <a href="scanner_qr.php" class="btn-khombole">
                                <i class="fas fa-camera me-2"></i>Scanner
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Pied de page -->
        <div class="footer-section">
            <div class="row">
                <div class="col-md-4 text-center mb-3">
                    <h5><i class="fas fa-heart me-2"></i>À propos</h5>
                    <p class="mb-0">Les Jeunes du mouvement à L'APPEL DE KHOMBOLE</p>
                    <small class="opacity-75">Système de gestion des membres</small>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <h5><i class="fas fa-envelope me-2"></i>Contact</h5>
                    <p class="mb-1">
                        <a href="mailto:jeunesalappel@gmail.com" class="text-white text-decoration-none">
                            <i class="fas fa-envelope me-2"></i>jeunesalappel@gmail.com
                        </a>
                    </p>
                    <p class="mb-0">
                        <a href="tel:+221772862728" class="text-white text-decoration-none">
                            <i class="fas fa-phone me-2"></i>+221 77 286 27 28
                        </a>
                    </p>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <h5><i class="fas fa-share-alt me-2"></i>Suivez-nous</h5>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
