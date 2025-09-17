
<?php
use Source\Core\Connect;

require_once __DIR__ . "/../../source/Core/Config.php";
require_once __DIR__ . "/../../source/Core/Connect.php";

session_start();

if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['password'])) {
    $pdo = Connect::getInstance();

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    // Consulta segura
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // login ok
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        
        // regenerar id da sessão (boa prática)
        session_regenerate_id(true);

        header("Location: /Web-service/app"); // ajuste a rota
        exit;
    } else {
        $_SESSION['error'] = "E-mail ou senha inválidos.";
        header("Location: /Web-Service/login");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Armazém Conectado</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('assets/login.css') ?>">
    <style>
        .btn-link {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007BFF;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-family: Arial, sans-serif;
        transition: background 0.3s ease;
    }
        
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --light: #f8fafc;
            --dark: #0f172a;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f1f5f9;
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        
        .login-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .login-header {
            background-color: var(--primary);
            padding: 25px 30px;
            color: white;
            text-align: center;
        }
        
        .login-header .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 15px;
        }
        
        .login-header .logo i {
            font-size: 28px;
        }
        
        .login-header .logo h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .login-header p {
            opacity: 0.9;
        }
        
        .login-form {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
            font-size: 0.9rem;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary);
        }
        
        .input-field {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }
        
        .input-field:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--secondary);
            cursor: pointer;
        }
        
        .login-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
        }
        
        .forgot-password {
            color: var(--primary);
            text-decoration: none;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
            text-align: center;
            text-decoration: none;
        }
        
        .btn:hover {
            background-color: var(--primary-dark);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 25px;
            color: var(--secondary);
            font-size: 0.9rem;
        }
        
        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .back-to-home {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: var(--secondary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        
        .back-to-home i {
            margin-right: 5px;
        }
        
        .back-to-home:hover {
            color: var(--dark);
        }

    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-box-open"></i>
                    <h1>Armazém Conectado</h1>
                </div>
                <p>Sistema de Gestão de Estoque</p>
            </div>
            
            <form class="login-form" action="" method="post">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <div class="input-group">
                        <i class="far fa-envelope"></i>
                        <input type="email" id="email" name="email" class="input-field" placeholder="seu@email.com" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Senha</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" class="input-field" placeholder="••••••••" required>
                        <button type="button" class="toggle-password" aria-label="Mostrar senha">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="login-options">
                    <label class="remember-me">
                        <input type="checkbox" id="remember">
                        Lembrar-me
                    </label>
                    <a href="#" class="forgot-password">Esqueceu a senha?</a>
                </div>
                
                <button type="submit" name="submit" class="btn-primary">Entrar</button>
                
                <div class="login-footer">
                    Não tem uma conta? <a href="<?= url("/registro") ?>">Cadastre-se</a>
                </div>
            </form>
        </div>
        
        <a href="<?= url("/"); ?>" class="back-to-home">
            <i class="fas fa-arrow-left"></i> Voltar para página inicial
        </a>
    </div>
    
</body>
</html>