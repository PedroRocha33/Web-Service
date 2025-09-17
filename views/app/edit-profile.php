<?php
session_start();
require_once __DIR__ . "/../../source/Core/Connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: /Web-Service/login");
    exit;
}

$pdo = \Source\Core\Connect::getInstance();

// Busca os dados atuais do usuário
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
$stmt->bindParam(":id", $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$photo = (!empty($_FILES["photo"]["name"]) ? $_FILES["photo"] : null);


$id = $_SESSION['user_id'];
$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? null;

// Upload da foto
$photoPath = null;
if (!empty($_FILES['photo']['name'])) {
    $uploadDir = __DIR__ . "/storage/images/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = uniqid() . "_" . basename($_FILES['photo']['name']);
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
        $photoPath = "/storage/images/" . $fileName;
    }
}

// Monta a query dinamicamente
$sql = "UPDATE users SET email = :email";
$params = [":email" => $email, ":id" => $id];

if (!empty($password)) {
    $sql .= ", password = :password";
    $params[":password"] = password_hash($password, PASSWORD_DEFAULT);
}

if ($photoPath) {
    $sql .= ", photo = :photo";
    $params[":photo"] = $photoPath;
}

$sql .= " WHERE id = :id";

$stmt = $pdo->prepare($sql);
if ($stmt->execute($params)) {
    $_SESSION['success'] = "Perfil atualizado com sucesso!";
    if ($photoPath) {
        $_SESSION['user_photo'] = $photoPath; // atualiza na sessão também
    }
} else {
    $_SESSION['error'] = "Erro ao atualizar o perfil.";
}


?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 500px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="password"], input[type="file"] {
            width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;
        }
        button { padding: 10px 15px; border: none; background: #007BFF; color: #fff; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .profile-photo img { border-radius: 50%; width: 80px; height: 80px; object-fit: cover; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Perfil</h2>

        <!-- Foto atual -->
        <div class="profile-photo">
            <?php if (!empty($user['photo'])): ?>
            <img src="<?= ($_SESSION['user_photo'] ?? 'Visitante'); ?>" alt="">
            <?php else: ?>
                👤
            <?php endif; ?>
        </div>
        <br>

        <!-- Form para atualizar perfil -->
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="photo">Alterar Foto:</label>
                <input type="file" name="photo" id="photo" accept="image/*">
            </div>

            <div class="form-group">
                <label for="email">Alterar E-mail:</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>">
            </div>

            <div class="form-group">
                <label for="password">Nova Senha:</label>
                <input type="password" name="password" id="password" placeholder="Deixe em branco para não alterar">
            </div>

            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>

