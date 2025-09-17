
<?php
use Source\Core\Connect;

// Incluir a configuração do banco (com $pdo definido)
require_once __DIR__ . "/../../source/Core/Config.php";
require_once __DIR__ . "/../../source/Core/Connect.php";

if (isset($_POST['submit'])) {
                     $pdo = Connect::getInstance(); // pega a instância única do PDO


    // 1. Receber os dados do formulário com segurança
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // 2. Criptografar a senha antes de armazená-la
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 3. Usar uma instrução preparada para inserção segura
    try {
        // garante que a variável $pdo existe aqui
        if (!isset($pdo)) {
            throw new Exception("Conexão com o banco de dados não inicializada.");
        }

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashed_password]);

        //echo "Usuário inserido com sucesso!";
    } catch (PDOException $e) {
        die("Erro no banco de dados: " . $e->getMessage());
    } catch (Exception $e) {
        die("Erro: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Armazém Conectado</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Web-Service/assets/cadastro.css">
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
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="logo">
                    <i class="fas fa-box-open"></i>
                    <h1>Armazém Conectado</h1>
                </div>
                <p>Crie sua conta e comece a gerenciar seu estoque</p>
            </div>
            
            <form class="register-form" action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Nome completo</label>
                    <div class="input-group">
                        <i class="far fa-user"></i>
                        <input type="text" id="name" name="name" class="input-field" placeholder="Seu nome completo" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <div class="input-group">
                        <i class="far fa-envelope"></i>
                        <input type="email" name="email" id="email" class="input-field" placeholder="seu@email.com" required>
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
                
                <div class="form-group">
                    <label for="confirm-password">Confirmar senha</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirm-password" class="input-field" placeholder="••••••••" required>
                        <button type="button" class="toggle-password" aria-label="Mostrar senha">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">
                        Li e concordo com os <a href="#">Termos de Serviço</a> e <a href="#">Política de Privacidade</a>
                    </label>
                </div>
                
                <button type="submit" name="submit" class="btn-primary">Criar Conta</button>

                <div class="register-footer">
                    Já tem uma conta? <a href="<?= url("/app") ?>">Faça login</a>
                </div>
            </form>
        </div>
        
        <a href="<?= url("/"); ?>" class="back-to-home">
            <i class="fas fa-arrow-left"></i> Voltar para página inicial
        </a>
    </div>
</body>
</html>