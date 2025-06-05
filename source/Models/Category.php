<?php

namespace Source\Models;

use Source\Core\Connect;
use Source\Core\Model;
use PDOException;

class Category extends Model
{
    protected $id;
    protected $name;
    protected $description;
    protected $active;
    protected $created_at;
    protected $updated_at;

    public function __construct(
        string $id = null,
        string $name = null,
        string $description = null,
        bool $active = true,
        string $created_at = null,
        string $updated_at = null
    )
    {
        $this->table = "categories";
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->active = $active;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getters e Setters

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    // Inserir categoria
    public function insert(): bool
    {
        if (empty($this->name)) {
            $this->errorMessage = "Nome da categoria é obrigatório";
            return false;
        }

        $sql = "SELECT * FROM categories WHERE name = :name";
        $stmt = Connect::getInstance()->prepare($sql);
        $stmt->bindValue(":name", $this->name);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $this->errorMessage = "Categoria já cadastrada";
            return false;
        }

        // Gera UUID se não for passado
        if (!$this->id) {
            $this->id = uniqid('', true);
        }

        if (!parent::insert()) {
            $this->errorMessage = "Erro ao inserir a categoria: {$this->getErrorMessage()}";
            return false;
        }

        return true;
    }

    // Buscar categoria por nome
    public function findByName(string $name): bool
    {
        $sql = "SELECT * FROM categories WHERE name = :name";
        $stmt = Connect::getInstance()->prepare($sql);
        $stmt->bindValue(":name", $name);

        try {
            $stmt->execute();
            $result = $stmt->fetch();
            if (!$result) {
                return false;
            }

            $this->id = $result->id;
            $this->name = $result->name;
            $this->description = $result->description;
            $this->active = $result->active;
            $this->created_at = $result->created_at;
            $this->updated_at = $result->updated_at;

            return true;
        } catch (PDOException $e) {
            $this->errorMessage = "Erro ao buscar a categoria: {$e->getMessage()}";
            return false;
        }
    }

public function delete(): bool
{
    $stmt = Connect::getInstance()->prepare("DELETE FROM categories WHERE id = :id");
    $stmt->bindValue(":id", $this->id);
    return $stmt->execute();
}

}
