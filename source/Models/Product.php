<?php

namespace Source\Models;

use Source\Core\Connect;
use Source\Core\Model;
use PDOException;

class Product extends Model
{

        private $userAuth; // ou public/protected, conforme seu uso


    protected $id;
    protected $code;
    protected $name;
    protected $description;
    protected $category_id;
    protected $unit;
    protected $cost_price;
    protected $sale_price;
    protected $min_stock;
    protected $max_stock;
    protected $active;
    protected $created_at;
    protected $updated_at;

    public function __construct(
        string $id = null,
        string $code = null,
        string $name = null,
        string $description = null,
        string $category_id = null,
        string $unit = null,
        float $cost_price = null,
        float $sale_price = null,
        int $min_stock = null,
        int $max_stock = null,
        int $active = 1,
        string $created_at = null,
        string $updated_at = null
    ) {
        $this->table = "products";

        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
        $this->category_id = $category_id;
        $this->unit = $unit;
        $this->cost_price = $cost_price;
        $this->sale_price = $sale_price;
        $this->min_stock = $min_stock;
        $this->max_stock = $max_stock;
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

    public function getCode(): ?string 
    { 
        return $this->code; 
    }
    public function setCode(?string $code): void 
    { 
        $this->code = $code; 
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
    public function setDescription(?string $description): void { $this->description = $description; }

    public function getCategoryId(): ?string { return $this->category_id; }
    public function setCategoryId(?string $category_id): void { $this->category_id = $category_id; }

    public function getUnit(): ?string { return $this->unit; }
    public function setUnit(?string $unit): void { $this->unit = $unit; }

    public function getCostPrice(): ?float { return $this->cost_price; }
    public function setCostPrice(?float $cost_price): void { $this->cost_price = $cost_price; }

    public function getSalePrice(): ?float { return $this->sale_price; }
    public function setSalePrice(?float $sale_price): void { $this->sale_price = $sale_price; }

    public function getMinStock(): ?int { return $this->min_stock; }
    public function setMinStock(?int $min_stock): void { $this->min_stock = $min_stock; }

    public function getMaxStock(): ?int { return $this->max_stock; }
    public function setMaxStock(?int $max_stock): void { $this->max_stock = $max_stock; }

    public function getActive(): ?int { return $this->active; }
    public function setActive(?int $active): void { $this->active = $active; }

    public function getCreatedAt(): ?string { return $this->created_at; }
    public function setCreatedAt(?string $created_at): void { $this->created_at = $created_at; }

    public function getUpdatedAt(): ?string { return $this->updated_at; }
    public function setUpdatedAt(?string $updated_at): void { $this->updated_at = $updated_at; }

    // Insere o produto no banco
    public function insert(): bool
    {
        $this->id = uniqid(); // ou use UUID se o banco aceitar
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');

        if (!parent::insert()) {
            $this->errorMessage = "Erro ao inserir o produto: {$this->getErrorMessage()}";
            return false;
        }

        return true;
    }

    // Buscar por código
    public function findByCode(string $code): bool
    {
        $sql = "SELECT * FROM products WHERE code = :code";
        $stmt = Connect::getInstance()->prepare($sql);
        $stmt->bindValue(":code", $code);

        try {
            $stmt->execute();
            $result = $stmt->fetch();
            if (!$result) {
                return false;
            }

            foreach ($result as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }

            return true;
        } catch (PDOException $e) {
            $this->errorMessage = "Erro ao buscar o produto: {$e->getMessage()}";
            return false;
        }
    }

public function update(): bool
{
    
    if (!$this->id) {
        $this->errorMessage = "ID do produto não definido para atualização.";
        return false;
    }

    $this->updated_at = date('Y-m-d H:i:s');

    $sql = "UPDATE products SET
                id = :id,
                code = :code,
                name = :name,
                description = :description,
                category_id = :category_id,
                unit = :unit,
                cost_price = :cost_price,
                sale_price = :sale_price,
                min_stock = :min_stock,
                max_stock = :max_stock,
                active = :active,
                updated_at = :updated_at
            WHERE id = :id";

    $stmt = Connect::getInstance()->prepare($sql);
    
    $stmt->bindValue(":id", $this->id);
    $stmt->bindValue(":code", $this->code);
    $stmt->bindValue(":name", $this->name);
    $stmt->bindValue(":description", $this->description);
    $stmt->bindValue(":category_id", $this->category_id);
    $stmt->bindValue(":unit", $this->unit);
    $stmt->bindValue(":cost_price", $this->cost_price);
    $stmt->bindValue(":sale_price", $this->sale_price);
    $stmt->bindValue(":min_stock", $this->min_stock, \PDO::PARAM_INT);
    $stmt->bindValue(":max_stock", $this->max_stock, \PDO::PARAM_INT);
    $stmt->bindValue(":active", $this->active, \PDO::PARAM_INT);
    $stmt->bindValue(":updated_at", $this->updated_at);

    try {
        return $stmt->execute();
    } catch (PDOException $e) {
        $this->errorMessage = "Erro ao atualizar o produto: " . $e->getMessage();
        return false;
    }
}

public function delete(): bool
{
    $stmt = Connect::getInstance()->prepare("DELETE FROM products WHERE id = :id");
    $stmt->bindValue(":id", $this->id);
    return $stmt->execute();
}


}

