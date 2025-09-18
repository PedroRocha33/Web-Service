<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header("Location: /Web-service/login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .profile-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .profile-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            opacity: 0.8;
        }

        .profile-container:hover {
            transform: translateY(-5px);
        }

        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            object-fit: cover;
            position: relative;
            z-index: 2;
            margin: 20px auto 30px;
            transition: transform 0.3s ease;
            background: linear-gradient(45deg, #f0f0f0, #e0e0e0);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #999;
        }

        .profile-photo:hover {
            transform: scale(1.05);
        }

        .profile-info {
            position: relative;
            z-index: 2;
        }

        .profile-name {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .profile-email {
            font-size: 16px;
            color: #666;
            margin-bottom: 25px;
            background: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            border-radius: 25px;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .edit-button {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .edit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .edit-button:active {
            transform: translateY(0);
        }

        .social-links {
            margin-top: 25px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-link {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(102, 126, 234, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #667eea;
            transition: all 0.3s ease;
            font-size: 18px;
        }

        .social-link:hover {
            background: #667eea;
            color: white;
            transform: translateY(-3px);
        }

        @media (max-width: 480px) {
            .profile-container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .profile-name {
                font-size: 24px;
            }
            
            .profile-photo {
                width: 100px;
                height: 100px;
                font-size: 40px;
            }
        }

        /* Animação de entrada */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-container {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-photo">
            <img src="<?= ($_SESSION['user_photo'] ?? 'Visitante'); ?>" alt="">
        </div>
        
        <div class="profile-info">
            <h1 class="profile-name">
                <?= ($_SESSION['user_name'] ?? 'Visitante'); ?>
            </h1>
            <div class="profile-email">
                <?= ($_SESSION['user_email'] ?? 'Visitante'); ?>
            </div>
            
            <a href="<?= url("/app/edit-profile") ?>">
                <button class="edit-button">
                 Editar Perfil
                </button>
            </a>
            
            <div class="social-links">
                <a href="#" class="social-link" title="LinkedIn">
                    💼
                </a>
                <a href="#" class="social-link" title="GitHub">
                    💻
                </a>
                <a href="#" class="social-link" title="Twitter">
                    🐦
                </a>
            </div>
        </div>
    </div>

    <script>
        function editProfile() {
            const name = prompt("Digite o novo nome:", );
            const email = prompt("Digite o novo email:", "joao.silva@email.com");
            
            if (name !== null && name.trim() !== "") {
                document.querySelector('.profile-name').textContent = name;
            }
            
            if (email !== null && email.trim() !== "") {
                document.querySelector('.profile-email').textContent = email;
            }
        }

        // Adicionar efeito de clique na foto para upload
        // document.querySelector('.profile-photo').addEventListener('click', function() {
        //     const input = document.createElement('input');
        //     input.type = 'file';
        //     input.accept = 'image/*';
        //     input.onchange = function(e) {
        //         const file = e.target.files[0];
        //         if (file) {
        //             const reader = new FileReader();
        //             reader.onload = function(e) {
        //                 const photo = document.querySelector('.profile-photo');
        //                 photo.innerHTML = '';
        //                 photo.style.backgroundImage = `url(${e.target.result})`;
        //                 photo.style.backgroundSize = 'cover';
        //                 photo.style.backgroundPosition = 'center';
        //             };
        //             reader.readAsDataURL(file);
        //         }
        //     };
        //     input.click();
        // });

        // Adicionar tooltip na foto
        document.querySelector('.profile-photo').title = "Clique para alterar a foto";
    </script>
</body>
</html>