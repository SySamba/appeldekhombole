<?php
require_once 'auth_check.php';
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

// URL pour le QR code pointant vers le profil du membre sur le site
$qr_data = "https://appeldekhombole.com/profil_membre.php?id=" . $membre['id'];

$qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qr_data);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte de Membre - <?php echo htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Arial:wght@400;700;900&display=swap');
        
        body {
            font-family: 'Arial', sans-serif;
            background: #f0f0f0;
            min-height: 100vh;
            padding: 20px;
        }

        .carte-membre {
            width: 650px;
            height: 420px;
            background: white;
            border-radius: 0;
            position: relative;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            margin: 0 auto 30px;
            color: black;
            border: 2px solid #000;
        }

        .bande-verte {
            width: 100%;
            height: 40px;
            background: #0b843e !important;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .logo-carte {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 160px;
            height: auto;
            z-index: 10;
        }

        .carte-header {
            position: absolute;
            top: 30px;
            left: 190px;
            right: 30px;
            text-align: center;
        }

        .carte-title {
            font-size: 36px;
            font-weight: 900;
            color: black;
            margin: 0 0 10px 0;
            letter-spacing: 3px;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .carte-subtitle {
            font-size: 16px;
            font-weight: 700;
            color: #d32f2f;
            margin: 0;
            line-height: 1.3;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .carte-body {
            position: absolute;
            top: 200px;
            left: 30px;
            right: 150px;
            bottom: 80px;
            display: flex;
            gap: 40px;
        }

        .photo-section {
            flex-shrink: 0;
        }

        .photo-membre {
            width: 160px;
            height: 180px;
            border: 3px solid black;
            object-fit: cover;
            background: #f5f5f5;
            display: block;
        }

        .photo-placeholder {
            width: 160px;
            height: 180px;
            border: 3px solid black;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #666;
            font-weight: bold;
            font-family: Arial, sans-serif;
        }

        .info-section {
            flex-grow: 1;
            padding-top: 10px;
            background-image: url('fond.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 8px;
            padding: 15px;
            position: relative;
        }
        
        .info-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.75);
            border-radius: 8px;
            z-index: 1;
        }
        
        .info-section > * {
            position: relative;
            z-index: 2;
        }

        .info-item {
            margin-bottom: 18px;
            font-size: 18px;
            font-weight: 700;
            color: black;
            font-family: Arial, sans-serif;
            line-height: 1.4;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.8);
        }

        .qr-section {
            position: absolute;
            bottom: 30px;
            right: 30px;
            text-align: center;
        }

        .qr-code {
            width: 100px;
            height: 100px;
            border: 3px solid black;
        }




        .print-btn {
            background: #0b843e;
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            margin: 20px auto;
            display: block;
        }

        .print-btn:hover {
            background: #1a5f2e;
            color: white;
        }

        @media print {
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .print-btn, .no-print {
                display: none !important;
            }
            .carte-membre {
                page-break-inside: avoid;
                margin: 0 auto !important;
                box-shadow: none !important;
                border: 2px solid #000 !important;
            }
            .container {
                max-width: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .bande-verte {
                background: #0b843e !important;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            /* Masquer les éléments indésirables à l'impression */
            @page {
                margin: 0;
                size: auto;
            }
            /* Masquer les en-têtes et pieds de page du navigateur */
            html {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mb-4 no-print">
            <h1 class="text-dark mb-3">
                <i class="fas fa-id-card me-2"></i>
                Carte de Membre
            </h1>
        </div>

        <!-- Carte Recto -->
        <div class="carte-membre">
            <!-- Bande verte en haut -->
            <div class="bande-verte"></div>
            
            <!-- Logo -->
            <img src="logo.png" alt="Logo A l'Appel de Khombole" class="logo-carte">
            
            <!-- Titres -->
            <div class="carte-header">
                <div class="carte-title">CARTE MEMBRE</div>
                <div class="carte-subtitle">ACTION POUR LE PROGRÈS D'UNE EXIGENCE LOCALE</div>
            </div>
            
            <!-- Corps de la carte -->
            <div class="carte-body">
                <!-- Photo -->
                <div class="photo-section">
                    <?php if ($membre['photo'] && file_exists('uploads/photos/' . $membre['photo'])): ?>
                        <img src="uploads/photos/<?php echo htmlspecialchars($membre['photo']); ?>" 
                             alt="Photo du membre" class="photo-membre">
                    <?php else: ?>
                        <div class="photo-placeholder">
                            Photo
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Informations -->
                <div class="info-section">
                    <div class="info-item">
                        Prénom: <?php echo htmlspecialchars($membre['prenom']); ?>
                    </div>
                    <div class="info-item">
                        Nom: <?php echo htmlspecialchars($membre['nom']); ?>
                    </div>
                    <div class="info-item">
                        Numéro: <?php echo htmlspecialchars($membre['telephone']); ?>
                    </div>
                    <div class="info-item">
                        Adresse: <?php echo htmlspecialchars($membre['adresse']); ?>
                    </div>
                </div>
            </div>
            
            <!-- QR Code -->
            <div class="qr-section">
                <img src="<?php echo $qr_url; ?>" alt="QR Code" class="qr-code">
            </div>
            
        </div>


        <button onclick="imprimerCarte()" class="print-btn">
            <i class="fas fa-print me-2"></i>
            Imprimer la Carte
        </button>

        <div class="text-center no-print">
            <a href="gestion_membres.php" class="btn btn-light">
                <i class="fas fa-arrow-left me-2"></i>
                Retour à la gestion
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function imprimerCarte() {
            // Créer une nouvelle fenêtre pour l'impression
            const printWindow = window.open('', '_blank');
            
            // Copier le contenu HTML dans la nouvelle fenêtre
            const carteContent = document.querySelector('.carte-membre').outerHTML;
            
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Carte de Membre</title>
                    <style>
                        @import url('https://fonts.googleapis.com/css2?family=Arial:wght@400;700;900&display=swap');
                        
                        * {
                            margin: 0;
                            padding: 0;
                            box-sizing: border-box;
                        }
                        
                        body {
                            font-family: 'Arial', sans-serif;
                            background: white;
                            padding: 0;
                            margin: 0;
                        }

                        .carte-membre {
                            width: 650px;
                            height: 420px;
                            background: white;
                            border-radius: 0;
                            position: relative;
                            color: black;
                            border: 2px solid #000;
                            margin: 20px auto;
                        }

                        .bande-verte {
                            width: 100%;
                            height: 40px;
                            background: #0b843e !important;
                            position: absolute;
                            top: 0;
                            left: 0;
                            z-index: 1;
                            -webkit-print-color-adjust: exact !important;
                            color-adjust: exact !important;
                            print-color-adjust: exact !important;
                        }

                        .logo-carte {
                            position: absolute;
                            top: 20px;
                            left: 20px;
                            width: 160px;
                            height: auto;
                            z-index: 10;
                        }

                        .carte-header {
                            position: absolute;
                            top: 30px;
                            left: 190px;
                            right: 30px;
                            text-align: center;
                        }

                        .carte-title {
                            font-size: 36px;
                            font-weight: 900;
                            color: black;
                            margin: 0 0 10px 0;
                            letter-spacing: 3px;
                            font-family: Arial, sans-serif;
                            text-align: center;
                        }

                        .carte-subtitle {
                            font-size: 16px;
                            font-weight: 700;
                            color: #d32f2f;
                            margin: 0;
                            line-height: 1.3;
                            font-family: Arial, sans-serif;
                            text-align: center;
                        }

                        .carte-body {
                            position: absolute;
                            top: 200px;
                            left: 30px;
                            right: 150px;
                            bottom: 80px;
                            display: flex;
                            gap: 40px;
                        }

                        .photo-section {
                            flex-shrink: 0;
                        }

                        .photo-membre {
                            width: 160px;
                            height: 180px;
                            border: 3px solid black;
                            object-fit: cover;
                            background: #f5f5f5;
                            display: block;
                        }

                        .photo-placeholder {
                            width: 160px;
                            height: 180px;
                            border: 3px solid black;
                            background: #f5f5f5;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 28px;
                            color: #666;
                            font-weight: bold;
                            font-family: Arial, sans-serif;
                        }

                        .info-section {
                            flex-grow: 1;
                            padding-top: 10px;
                            background-image: url('fond.jpg');
                            background-size: cover;
                            background-position: center;
                            background-repeat: no-repeat;
                            border-radius: 8px;
                            padding: 15px;
                            position: relative;
                        }
                        
                        .info-section::before {
                            content: '';
                            position: absolute;
                            top: 0;
                            left: 0;
                            right: 0;
                            bottom: 0;
                            background: rgba(255, 255, 255, 0.75);
                            border-radius: 8px;
                            z-index: 1;
                        }
                        
                        .info-section > * {
                            position: relative;
                            z-index: 2;
                        }

                        .info-item {
                            margin-bottom: 18px;
                            font-size: 18px;
                            font-weight: 700;
                            color: black;
                            font-family: Arial, sans-serif;
                            line-height: 1.4;
                            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.8);
                        }

                        .qr-section {
                            position: absolute;
                            bottom: 30px;
                            right: 30px;
                            text-align: center;
                        }

                        .qr-code {
                            width: 100px;
                            height: 100px;
                            border: 3px solid black;
                        }

                        @page {
                            margin: 0;
                            size: auto;
                        }
                        
                        @media print {
                            body {
                                -webkit-print-color-adjust: exact !important;
                                color-adjust: exact !important;
                                print-color-adjust: exact !important;
                            }
                            .bande-verte {
                                background: #0b843e !important;
                                -webkit-print-color-adjust: exact !important;
                                color-adjust: exact !important;
                                print-color-adjust: exact !important;
                            }
                        }
                    </style>
                </head>
                <body>
                    ${carteContent}
                </body>
                </html>
            `);
            
            printWindow.document.close();
            
            // Attendre que le contenu soit chargé puis imprimer
            printWindow.onload = function() {
                setTimeout(() => {
                    printWindow.print();
                    printWindow.close();
                }, 500);
            };
        }
    </script>
</body>
</html>
