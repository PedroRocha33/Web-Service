<?php

namespace Source\WebService;

use Source\Core\Connect;
use Source\Models\User;
use Source\Core\JWTToken;
use SorFabioSantos\Uploader\Uploader;




class Users extends Api
{
    public function listUsers (): void
    {
        $users = new User();
        //var_dump($users->findAll());
        $this->call(200, "success", "Lista de usuários", "success")
            ->back($users->findAll());
    }

    public function createUser(array $data)
    {
                 $pdo = Connect::getInstance(); // pega a instância única do PDO


      try {


            // 1. Receber e sanear os dados do formulário
            $name = filter_var($data['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
            $password = $data['password'];

            // 2. Criptografar a senha para segurança
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            if ($hashed_password === false) {
                // Falha ao criar o hash da senha
                return false;
            }

            // 4. Inserir os dados no banco de dados de forma segura (PDO)
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashed_password]);

            return true; // Sucesso na inserção
            
        } catch (PDOException $e) {
            // Logar o erro do banco de dados para depuração, mas não exibi-lo ao usuário
            error_log("Erro no banco de dados: " . $e->getMessage());
            return false;
        };

        $this->call(201, "created", "Usuário criado com sucesso", "success")
            ->back($response);

    }

    public function listUserById (array $data): void
    {

        if(!isset($data["id"])) {
            $this->call(400, "bad_request", "ID inválido", "error")->back();
            return;
        }

        if(!filter_var($data["id"], FILTER_VALIDATE_INT)) {
            $this->call(400, "bad_request", "ID inválido", "error")->back();
            return;
        }

        $user = new User();
        if(!$user->findById($data["id"])){
            $this->call(200, "success", "Usuário não encontrado", "error")->back();
            return;
        }
        $response = [
            "name" => $user->getName(),
            "email" => $user->getEmail()
        ];
        $this->call(200, "success", "Encontrado com sucesso", "success")->back($response);
    }

    public function deleteUser (array $data): void
    {
        $this->auth();
        $this->call(200, "success", "Usuário excluído com sucesso", "success")
            ->back($data);
    }

    public function updateUser (array $data): void
    {
        $this->auth();
        var_dump($data);
        var_dump( $this->userAuth);
        var_dump($this->userAuth->name, $this->userAuth->email);
    }

    public function login(array $data): void
    {
        // Verificar se os dados de login foram fornecidos
        if (empty($data["email"]) || empty($data["password"])) {
            $this->call(400, "bad_request", "Credenciais inválidas", "error")->back();
            return;
        }

        $user = new User();

        if(!$user->findByEmail($data["email"])){
            $this->call(401, "unauthorized", "Usuário não encontrado", "error")->back();
            return;
        }

        if(!password_verify($data["password"], $user->getPassword())){
            $this->call(401, "unauthorized", "Senha inválida", "error")->back();
            return;
        }

        // Gerar o token JWT
        $jwt = new JWTToken();
        $token = $jwt->create([
            "email" => $user->getEmail(),
            "name" => $user->getName(),
            "photo" => $user->getPhoto(),
            "rule" => $user->getIdType()
        ]);

        // Retornar o token JWT na resposta
        $this->call(200, "success", "Login realizado com sucesso", "success")
            ->back([
                "token" => $token,
                "user" => [
                    "id" => $user->getId(),
                    "name" => $user->getName(),
                    "email" => $user->getEmail(),
                    "photo" => $user->getPhoto()
                ]
            ]);

    }

 public function updatePhoto (): void
    {
        $this->auth();
        var_dump($this->userAuth);

        $photo = (!empty($_FILES["photo"]["name"]) ? $_FILES["photo"] : null);

        $upload = new Uploader();
        $path = $upload->Image($photo);
        if(!$path) {
            $this->call(400, "bad_request", $upload->getMessage(), "error")->back();
            return;
        }

        $user = new User();
        $user->findByEmail($this->userAuth->email);
        $user->setPhoto($path);
        if(!$user->updateById()){
            $this->call(500, "internal_server_error", $user->getErrorMessage(), "error")->back();
            return;
        }

        $this->call(200, "success", "Foto atualizada com sucesso", "success")->back();

    }

    public function updateFile():void
    {
        $this->auth();

        $file = (!empty($_FILES["file"]["name"]) ? $_FILES["file"] : null);

        $upload = new Uploader();
        $path = $upload->File($file);
        if(!$path) {
            $this->call(400, "bad_request", $upload->getMessage(), "error")->back();
            return;
        }

        $user = new User();
        $user->findByEmail($this->userAuth->email);
        $user->setPhoto($path);
        if(!$user->updateById()){
            $this->call(500, "internal_server_error", $user->getErrorMessage(), "error")->back();
            return;
        }

        $this->call(200, "success", "Arquivo atualizada com sucesso", "success")->back();

    }


}