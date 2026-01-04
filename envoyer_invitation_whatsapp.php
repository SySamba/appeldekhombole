<?php
require_once 'auth_check.php';
require_once 'classes/Membre.php';
require_once 'classes/WhatsAppIntegration.php';

if (!isset($_GET['id'])) {
    header('Location: gestion_membres.php');
    exit;
}

$membre_obj = new Membre();
$membre = $membre_obj->obtenirParId($_GET['id']);

if (!$membre) {
    header('Location: gestion_membres.php');
    exit;
}

$whatsapp = new WhatsAppIntegration();
$invitation = $whatsapp->envoyerInvitationGroupe(
    $membre['telephone'], 
    $membre['nom'], 
    $membre['prenom']
);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation WhatsApp - <?php echo htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a5f2e 0%, #0b843e 50%, #f4e93d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .invitation-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            padding: 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        .whatsapp-icon {
            width: 100px;
            height: 100px;
            background: #25d366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            color: white;
            font-size: 50px;
            box-shadow: 0 10px 30px rgba(37, 211, 102, 0.3);
        }

        .btn-whatsapp {
            background: #25d366;
            border: none;
            color: white;
            padding: 15px 40px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 18px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3);
        }

        .btn-whatsapp:hover {
            background: #128c7e;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(37, 211, 102, 0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: white;
            border: 2px solid #0b843e;
            color: #0b843e;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        .btn-secondary-custom:hover {
            background: #0b843e;
            color: white;
        }

        .membre-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
        }

        .membre-info h4 {
            color: #0b843e;
            margin-bottom: 15px;
        }

        .info-item {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px 0;
            color: #666;
        }

        .info-item i {
            margin-right: 10px;
            color: #0b843e;
        }
    </style>
</head>
<body>
    <div class="invitation-card">
        <div class="whatsapp-icon">
            <i class="fab fa-whatsapp"></i>
        </div>
        
        <h2 class="mb-3" style="color: #0b843e;">Invitation WhatsApp</h2>
        <p class="text-muted mb-4">Envoyez l'invitation au groupe WhatsApp</p>
        
        <div class="membre-info">
            <h4><?php echo htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']); ?></h4>
            <div class="info-item">
                <i class="fas fa-phone"></i>
                <span><?php echo htmlspecialchars($membre['telephone']); ?></span>
            </div>
            <div class="info-item">
                <i class="fas fa-user-tag"></i>
                <span><?php echo htmlspecialchars($membre['role']); ?></span>
            </div>
        </div>

        <?php if ($invitation && $invitation['success']): ?>
            <div class="alert alert-success mb-4">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo htmlspecialchars($invitation['message']); ?>
            </div>
            
            <a href="<?php echo htmlspecialchars($invitation['url']); ?>" 
               target="_blank" 
               class="btn-whatsapp">
                <i class="fab fa-whatsapp me-2"></i>Ouvrir WhatsApp et Envoyer
            </a>
            
            <div class="mt-4">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Le message avec le lien du groupe sera automatiquement préparé
                </small>
            </div>
        <?php else: ?>
            <div class="alert alert-danger mb-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Erreur lors de la préparation de l'invitation
            </div>
        <?php endif; ?>
        
        <a href="gestion_membres.php" class="btn-secondary-custom">
            <i class="fas fa-arrow-left me-2"></i>Retour à la gestion
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
