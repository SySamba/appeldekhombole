<?php
session_start();

// Identifiants de connexion
define('ADMIN_EMAIL', 'jeunesalappel@gmail.com');
define('ADMIN_PASSWORD', 'Khombole2025@#');

$error = '';

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: index.php');
    exit;
}

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($email === ADMIN_EMAIL && $password === ADMIN_PASSWORD) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_email'] = $email;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Email ou mot de passe incorrect';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Les Jeunes du mouvement à L'APPEL DE KHOMBOLE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
            background: #0a0e27;
        }

        body::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 25%, #0d4d2b 50%, #f4e93d 100%);
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% {
                transform: scale(1) rotate(0deg);
                opacity: 0.9;
            }
            50% {
                transform: scale(1.1) rotate(5deg);
                opacity: 1;
            }
        }

        .background-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: float 20s infinite ease-in-out;
        }

        .shape1 {
            width: 400px;
            height: 400px;
            background: #0b843e;
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }

        .shape2 {
            width: 350px;
            height: 350px;
            background: #f4e93d;
            bottom: -100px;
            right: -100px;
            animation-delay: 5s;
        }

        .shape3 {
            width: 300px;
            height: 300px;
            background: #dc3545;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 10s;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(50px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-50px, 50px) scale(0.9);
            }
        }

        .login-container {
            position: relative;
            z-index: 10;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4), 
                        0 0 0 1px rgba(255, 255, 255, 0.2);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            animation: slideUp 0.8s ease-out;
            margin: 15px auto;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 100%);
            padding: 25px 30px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }
            100% {
                left: 100%;
            }
        }

        .logo-container {
            position: relative;
            display: inline-block;
            margin-bottom: 12px;
        }

        .logo-login {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            background: white;
            padding: 8px;
            position: relative;
            z-index: 2;
            animation: logoFloat 3s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .logo-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90px;
            height: 90px;
            background: radial-gradient(circle, rgba(244, 233, 61, 0.4), transparent);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.5;
            }
            50% {
                transform: translate(-50%, -50%) scale(1.2);
                opacity: 0.8;
            }
        }

        .login-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: white;
            margin-bottom: 5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            letter-spacing: 0.5px;
        }

        .login-subtitle {
            font-size: 0.85rem;
            color: #f4e93d;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .login-body {
            padding: 25px 30px;
            background: white;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 20px;
        }

        .welcome-text h4 {
            color: #0b843e;
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 5px;
        }

        .welcome-text p {
            color: #666;
            font-size: 0.85rem;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label i {
            color: #0b843e;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            border: 2px solid #e8ecef;
            border-radius: 12px;
            padding: 12px 18px 12px 50px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            background: #f8f9fa;
        }

        .form-control:focus {
            border-color: #0b843e;
            box-shadow: 0 0 0 4px rgba(11, 132, 62, 0.1);
            background: white;
            outline: none;
        }

        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #0b843e;
            font-size: 1.1rem;
            z-index: 2;
        }

        .password-toggle {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            z-index: 10;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .password-toggle:hover {
            color: #0b843e;
            transform: translateY(-50%) scale(1.1);
        }

        .btn-login {
            background: linear-gradient(135deg, #0b843e 0%, #1a5f2e 100%);
            border: none;
            color: white !important;
            padding: 13px 25px;
            border-radius: 12px;
            font-weight: 700;
            width: 100%;
            transition: all 0.4s ease;
            margin-top: 5px;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            z-index: 1;
            cursor: pointer;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
            z-index: -1;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(11, 132, 62, 0.4);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .alert-custom {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe5e5 100%);
            border: 2px solid #ff9999;
            color: #c33;
            border-radius: 12px;
            padding: 12px 15px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shake 0.5s ease;
            font-size: 0.9rem;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .alert-custom i {
            font-size: 1.3rem;
        }

        .login-footer {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 18px 25px;
            text-align: center;
        }

        .footer-text {
            color: #666;
            font-size: 0.8rem;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .social-links {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 10px;
        }

        .social-link {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
        }

        .social-link::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: scale(0);
            transition: transform 0.3s ease;
        }

        .social-link:hover::before {
            transform: scale(1);
        }

        .social-link:hover {
            transform: translateY(-5px) rotate(5deg);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .social-instagram {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
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

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 18px 0 0 0;
            color: #999;
            font-size: 0.8rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }

        .divider span {
            padding: 0 15px;
        }

        @media (max-width: 576px) {
            .login-container {
                border-radius: 20px;
            }

            .login-body {
                padding: 30px 25px;
            }

            .login-title {
                font-size: 1.3rem;
            }

            .welcome-text h4 {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <div class="background-shapes">
        <div class="shape shape1"></div>
        <div class="shape shape2"></div>
        <div class="shape shape3"></div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <div class="logo-container">
                <div class="logo-glow"></div>
                <img src="logo.png" alt="Logo Association" class="logo-login">
            </div>
            <div class="login-title">Les Jeunes du mouvement</div>
            <div class="login-subtitle">À L'APPEL DE KHOMBOLE</div>
        </div>

        <div class="login-body">
            <div class="welcome-text">
                <h4><i class="fas fa-shield-alt"></i> Espace Sécurisé</h4>
                <p>Connectez-vous pour accéder à votre espace</p>
            </div>

            <?php if ($error): ?>
                <div class="alert-custom">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        <span>Adresse Email</span>
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="votre@email.com" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>
                        <span>Mot de passe</span>
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-key input-icon"></i>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="••••••••••" required style="padding-right: 55px;">
                        <i class="fas fa-eye password-toggle" id="togglePassword" onclick="togglePasswordVisibility()"></i>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                </button>
            </form>

            <div class="divider">
                <span>Rejoignez-nous</span>
            </div>
        </div>

        <div class="login-footer">
            <p class="footer-text">Les Jeunes du mouvement à L'APPEL DE KHOMBOLE</p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePassword');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            const btn = document.querySelector('.btn-login');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Connexion en cours...';
        });

        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>
