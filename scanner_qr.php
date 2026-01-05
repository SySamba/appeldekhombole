<?php
require_once 'auth_check.php';
require_once 'classes/Membre.php';

$membre = null;
$error = null;

if (isset($_GET['qr']) && !empty($_GET['qr'])) {
    $membre_obj = new Membre();
    
    // Essayer d'abord avec l'ancien format (qr_code)
    $membre = $membre_obj->obtenirParQRCode($_GET['qr']);
    
    // Si pas trouvé, essayer avec le nouveau format (données complètes)
    if (!$membre) {
        $membre = $membre_obj->obtenirParDonneesQR($_GET['qr']);
    }
    
    if (!$membre) {
        $error = "Aucun membre trouvé avec ce code QR.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner QR Code - Les Jeunes du mouvement à L'APPEL DE KHOMBOLE</title>
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

        .scanner-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            margin: 20px auto;
            max-width: 600px;
        }

        .scanner-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            color: #0b843e;
            padding: 30px;
            text-align: center;
            border-bottom: 5px solid #0b843e;
            position: relative;
        }

        .scanner-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #0b843e 0%, #f4e93d 50%, #dc3545 100%);
        }

        .scanner-header h1 {
            color: #0b843e;
        }

        .scanner-header p {
            color: #666;
        }

        .membre-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 15px;
            padding: 30px;
            margin: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 2px solid #0b843e;
        }

        .photo-membre {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #0b843e;
            object-fit: cover;
            margin: 0 auto 20px;
            display: block;
        }

        .photo-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #0b843e;
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 48px;
            box-shadow: 0 5px 15px rgba(11, 132, 62, 0.3);
        }

        .membre-nom {
            font-size: 28px;
            font-weight: 700;
            color: #0b843e;
            text-align: center;
            margin-bottom: 10px;
        }

        .membre-role {
            background: white;
            border: 2px solid #0b843e;
            color: #0b843e;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            display: inline-block;
            margin: 0 auto 20px;
            text-align: center;
            width: fit-content;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .info-item {
            background: white;
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #0b843e;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }

        .statut-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .statut-actif {
            background: #d4edda;
            color: #155724;
        }

        .statut-inactif {
            background: #f8d7da;
            color: #721c24;
        }

        .statut-suspendu {
            background: #fff3cd;
            color: #856404;
        }

        .qr-input-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .qr-input {
            border: 2px solid #0b843e;
            border-radius: 10px;
            padding: 15px;
            font-size: 16px;
            width: 100%;
            margin-bottom: 15px;
        }

        .scan-btn {
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 100%);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .scan-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            color: white;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            margin: 20px;
            text-align: center;
            border: 1px solid #f5c6cb;
        }

        .camera-section {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-top: 20px;
        }

        #qr-reader {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .scanner-container {
                margin: 15px;
            }

            .scanner-header {
                padding: 25px 20px;
            }

            .scanner-header h1 {
                font-size: 1.5rem;
            }

            .scanner-header p {
                font-size: 0.9rem;
            }

            .membre-card {
                padding: 25px 15px;
                margin: 15px;
            }

            .photo-membre,
            .photo-placeholder {
                width: 100px;
                height: 100px;
                font-size: 40px;
            }

            .membre-nom {
                font-size: 24px;
            }

            .membre-role {
                padding: 6px 16px;
                font-size: 14px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .info-item {
                padding: 12px;
            }

            .info-label {
                font-size: 11px;
            }

            .info-value {
                font-size: 14px;
            }

            .qr-input-section {
                padding: 25px 15px;
                margin: 15px;
            }

            .qr-input {
                padding: 12px;
                font-size: 14px;
            }

            .scan-btn {
                padding: 12px 25px;
                font-size: 14px;
            }

            .camera-section {
                padding: 15px;
            }

            .camera-section h5 {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {
            .scanner-container {
                margin: 10px;
                border-radius: 15px;
            }

            .scanner-header {
                padding: 20px 15px;
            }

            .scanner-header h1 {
                font-size: 1.3rem;
            }

            .scanner-header p {
                font-size: 0.85rem;
            }

            .membre-card {
                padding: 20px 12px;
                margin: 10px;
                border-radius: 12px;
            }

            .photo-membre,
            .photo-placeholder {
                width: 80px;
                height: 80px;
                font-size: 32px;
            }

            .membre-nom {
                font-size: 20px;
            }

            .membre-role {
                padding: 5px 12px;
                font-size: 13px;
            }

            .info-grid {
                gap: 10px;
            }

            .info-item {
                padding: 10px;
            }

            .info-label {
                font-size: 10px;
            }

            .info-value {
                font-size: 13px;
            }

            .statut-badge {
                padding: 4px 12px;
                font-size: 11px;
            }

            .qr-input-section {
                padding: 20px 12px;
                margin: 10px;
            }

            .qr-input {
                padding: 10px;
                font-size: 13px;
            }

            .scan-btn {
                padding: 10px 20px;
                font-size: 13px;
            }

            .camera-section {
                padding: 12px;
            }

            .camera-section h5 {
                font-size: 1rem;
            }

            .camera-section p {
                font-size: 0.85rem;
            }

            .btn {
                padding: 8px 16px;
                font-size: 13px;
            }

            .error-message {
                padding: 12px;
                margin: 10px;
                font-size: 0.9rem;
            }

            #qr-reader {
                max-width: 100%;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .scanner-container {
                max-width: 700px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="scanner-container">
            <div class="scanner-header">
                <h1><i class="fas fa-qrcode me-3"></i>Scanner QR Code</h1>
                <p class="mb-0">Scannez ou saisissez le code QR d'un membre</p>
            </div>

            <?php if ($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($membre): ?>
                <div class="membre-card">
                    <?php if ($membre['photo'] && file_exists('uploads/photos/' . $membre['photo'])): ?>
                        <img src="uploads/photos/<?php echo htmlspecialchars($membre['photo']); ?>" 
                             alt="Photo" class="photo-membre">
                    <?php else: ?>
                        <div class="photo-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php endif; ?>

                    <div class="membre-nom">
                        <?php echo htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']); ?>
                    </div>

                    <div class="text-center">
                        <div class="membre-role">
                            <i class="fas fa-user-tag me-2"></i>
                            <?php echo htmlspecialchars($membre['role']); ?>
                        </div>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-phone me-1"></i> Téléphone
                            </div>
                            <div class="info-value"><?php echo htmlspecialchars($membre['telephone']); ?></div>
                        </div>

                        <?php if ($membre['email']): ?>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-envelope me-1"></i> Email
                            </div>
                            <div class="info-value"><?php echo htmlspecialchars($membre['email']); ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if ($membre['date_naissance']): ?>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-birthday-cake me-1"></i> Date de naissance
                            </div>
                            <div class="info-value"><?php echo date('d/m/Y', strtotime($membre['date_naissance'])); ?></div>
                        </div>
                        <?php endif; ?>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-map-marker-alt me-1"></i> Adresse
                            </div>
                            <div class="info-value"><?php echo htmlspecialchars($membre['adresse']); ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar me-1"></i> Date d'adhésion
                            </div>
                            <div class="info-value"><?php echo date('d/m/Y', strtotime($membre['date_adhesion'])); ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-info-circle me-1"></i> Statut
                            </div>
                            <div class="info-value">
                                <span class="statut-badge statut-<?php echo strtolower($membre['statut']); ?>">
                                    <?php echo htmlspecialchars($membre['statut']); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="generer_carte.php?id=<?php echo $membre['id']; ?>" class="btn btn-success me-2">
                            <i class="fas fa-id-card me-2"></i>Voir la carte
                        </a>
                        <a href="scanner_qr.php" class="btn btn-primary">
                            <i class="fas fa-qrcode me-2"></i>Scanner un autre QR
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="qr-input-section">
                    <form method="GET" action="">
                        <label for="qr" class="form-label">
                            <i class="fas fa-qrcode me-2"></i>Code QR du membre
                        </label>
                        <input type="text" 
                               class="qr-input" 
                               id="qr" 
                               name="qr" 
                               placeholder="Saisissez ou scannez le code QR..."
                               value="<?php echo isset($_GET['qr']) ? htmlspecialchars($_GET['qr']) : ''; ?>"
                               required>
                        <button type="submit" class="scan-btn">
                            <i class="fas fa-search me-2"></i>
                            Rechercher le membre
                        </button>
                    </form>

                    <div class="camera-section">
                        <h5><i class="fas fa-camera me-2"></i>Scanner avec la caméra</h5>
                        <p class="text-muted">Cliquez sur le bouton ci-dessous pour activer la caméra</p>
                        <button onclick="startQRScanner()" class="btn btn-outline-primary">
                            <i class="fas fa-camera me-2"></i>Activer la caméra
                        </button>
                        <div id="qr-reader" style="display: none;"></div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="text-center p-3">
                <a href="gestion_membres.php" class="btn btn-outline-dark me-2">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour à la gestion
                </a>
                <a href="index.php" class="btn btn-outline-dark">
                    <i class="fas fa-home me-2"></i>
                    Accueil
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer-section container">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    
    <script>
        function startQRScanner() {
            const qrReader = document.getElementById('qr-reader');
            qrReader.style.display = 'block';
            
            const html5QrCode = new Html5Qrcode("qr-reader");
            
            html5QrCode.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                (decodedText, decodedResult) => {
                    // QR code scanné avec succès
                    document.getElementById('qr').value = decodedText;
                    html5QrCode.stop().then(() => {
                        qrReader.style.display = 'none';
                        // Soumettre automatiquement le formulaire
                        document.querySelector('form').submit();
                    });
                },
                (errorMessage) => {
                    // Erreur de scan (normal, continue à scanner)
                }
            ).catch(err => {
                console.error('Erreur lors du démarrage du scanner:', err);
                alert('Impossible d\'accéder à la caméra. Veuillez saisir le code manuellement.');
                qrReader.style.display = 'none';
            });
        }
    </script>
</body>
</html>
