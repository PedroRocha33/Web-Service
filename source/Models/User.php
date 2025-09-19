<?php

namespace Source\Models;

use Source\Core\Connect;
use Source\Core\Model;

class User extends Model
{
    protected $id;
    protected $idType;
    protected $name;
    protected $email;
    protected $password;
    protected $photo;
    protected $link;


    public function __construct(
        int $id = null,
        int $idType = null,
        string $name = null,
        string $email = null,
        string $password = null,
        string $photo = null,
        string $link = null
    )
    {
        $this->table = "users";
        $this->id = $id;
        $this->idType = $idType;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->photo = $photo;
        $this->link = $link;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getIdType(): ?int
    {
        return $this->idType;
    }

    public function setIdType(?int $idType): void
    {
        $this->idType = $idType;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): void
    {
        $this->photo = $photo;
    }

    public function login () {
        echo "Olá, {$this->name}! Você está logado!";
    }

    public function insert (): bool
    {

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errorMessage = "E-mail inválido";
            return false;
        }

        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = Connect::getInstance()->prepare($sql);
        $stmt->bindValue(":email", $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $this->errorMessage = "E-mail já cadastrado";
            return false;
        }

        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        if(!parent::insert()){
            $this->errorMessage = "Erro ao inserir o registro: {$this->getErrorMessage()}";
            return false;
        }

        return true;
    }

     public function createUser(array $data): bool
    {
        // Certifique-se de ter a conexão PDO disponível (por exemplo, via um método estático ou global)
        // Exemplo: $pdo = getPdoConnection();
        // Ou, se a conexão estiver em Config.php, inclua-a no início do script.
        global $pdo; // Exemplo de uso de variável global, se for o caso

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
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, photo) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $hashed_password, $photo_name]);

            return true; // Sucesso na inserção
            
        } catch (PDOException $e) {
            // Logar o erro do banco de dados para depuração, mas não exibi-lo ao usuário
            error_log("Erro no banco de dados: " . $e->getMessage());
            return false;
        };
    }


    public function findByEmail (string $email): bool
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = Connect::getInstance()->prepare($sql);
        $stmt->bindValue(":email", $email);

        try {
            $stmt->execute();
            $result = $stmt->fetch();
            if (!$result) {
                return false;
            }
            $this->id = $result->id;
            $this->idType = $result->idType;
            $this->name = $result->name;
            $this->email = $result->email;
            $this->password = $result->password;
            $this->photo = $result->photo;

            return true;
        } catch (PDOException $e) {
            $this->errorMessage = "Erro ao buscar o registro: {$e->getMessage()}";
            return false;
        }

    }

    public function findLink (string $link): bool
    {
        $sql = "SELECT * FROM users WHERE link = :link";
        $stmt = Connect::getInstance()->prepare($sql);
        $stmt->bindValue(":link", $link);
        $stmt->execute();
        $result = $stmt->fetch();
        if (!$result) {
            return false;
        }
        return true;
    }

}