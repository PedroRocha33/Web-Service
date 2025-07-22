<?php

namespace Source\WebService;

use Source\Models\Product;
use Source\Models\Category;

class Products extends Api
{
    /**
     * Lista todos os produtos
     */
    public function listProducts(): void
    {
        // Pegar parâmetros de filtro e paginação
        $filters = [
            'category_id' => $_GET['category_id'] ?? null,
            'active' => $_GET['active'] ?? null,
            'search' => $_GET['search'] ?? null,
            'baixo_estoque' => $_GET['baixo_estoque'] ?? null
        ];
        
        $page = (int)($_GET['page'] ?? 1);
        $limit = (int)($_GET['limit'] ?? 20);

        $product = new Product();
        $products = $product->findAll($filters, $page, $limit);
        
        if (!$products) {
            $this->call(200, "success", "Nenhum produto encontrado", "info")->back([]);
            return;
        }

        $this->call(200, "success", "Lista de produtos", "success")->back($products);
    }

    /**
     * Cria um novo produto
     */
    public function createProduct(array $data): void
    {
         $this->auth(); // Verificar autenticação

        if (empty($data['name'])) {
            $this->call(400, "bad_request", "Nome da categoria é obrigatório", "error")->back();
            return;
        }

        $product = new Product(
            $data['id'],
            $data['code'],
            $data['name'],
            $data['description'] ?? null,
            $data['category_id'],
            $data['unit'],
            $data['cost_price'],
            $data['sale_price'],
            $data['active'] ?? true
        );

        if (!$product->insert()) {
            $this->call(500, "internal_server_error", $product->getErrorMessage(), "error")->back();
            return;
        }

        $this->call(201, "created", "Produto criada com sucesso", "success")->back([
            "id" => $product->getId(),
            "name" => $product->getName(),
            "active" => $product->getActive()
        ]);
    }

    /**
     * Busca produto por ID
     */
    public function getProductById(array $data): void
    {
        if (!isset($data["id"])) {
            $this->call(400, "bad_request", "ID é obrigatório", "error")->back();
            return;
        }

        if (!filter_var($data["id"], FILTER_VALIDATE_INT)) {
            $this->call(400, "bad_request", "ID deve ser um número válido", "error")->back();
            return;
        }

        $product = new Product();
        if (!$product->findById($data["id"])) {
            $this->call(404, "not_found", "Produto não encontrado", "error")->back();
            return;
        }

        $response = [
            "id" => $product->getId(),
            "code" => $product->getCode(),
            "name" => $product->getName(),
            "description" => $product->getDescription(),
            "category_id" => $product->getCategoryId(),
            "unit" => $product->getUnit(),
            "cost_price" => $product->getCostPrice(),
            "sale_price" => $product->getSalePrice(),
            "min_stock" => $product->getMinStock(),
            "max_stock" => $product->getMaxStock(),
            "active" => $product->getActive(),
            "created_at" => $product->getCreatedAt(),
            "updated_at" => $product->getUpdatedAt()
        ];

        $this->call(200, "success", "Produto encontrado", "success")->back($response);
    }

    /**
     * Atualiza um produto
     */
   public function updateProduct(array $data): void 
{
    $this->auth(); // Verificar autenticação
    
    // Validar se o ID foi fornecido
    if (empty($data["id"])) {
        $this->call(400, "bad_request", "ID é obrigatório", "error")->back();
        return;
    }
    
    // Buscar o produto existente
    $product = new Product();
    if (!$product->findById($data["id"])) {
        $this->call(404, "not_found", "Produto não encontrado", "error")->back();
        return;
    }
    
    // Lista dos campos que podem ser atualizados
    $allowedFields = [
        'code' => 'setCode',
        'name' => 'setName', 
        'description' => 'setDescription',
        'category_id' => 'setCategoryId',
        'unit' => 'setUnit',
        'cost_price' => 'setCostPrice',
        'sale_price' => 'setSalePrice',
        'min_stock' => 'setMinStock',
        'max_stock' => 'setMaxStock',
        'active' => 'setActive'
    ];
    
    $updated = false;
    
    // DEBUG: Vamos ver o que está chegando
    error_log("=== DEBUG UPDATE PRODUCT ===");
    error_log("Dados recebidos: " . json_encode($data));
    error_log("Campos permitidos: " . implode(', ', array_keys($allowedFields)));
    
    // Atualizar apenas os campos fornecidos
    foreach ($allowedFields as $field => $setter) {
        error_log("Verificando campo '$field':");
        error_log("  - Existe? " . (array_key_exists($field, $data) ? 'SIM' : 'NÃO'));
        
        if (array_key_exists($field, $data)) {
            error_log("  - Valor: " . var_export($data[$field], true));
            error_log("  - É null? " . ($data[$field] === null ? 'SIM' : 'NÃO'));
            error_log("  - É string vazia? " . ($data[$field] === '' ? 'SIM' : 'NÃO'));
        }
        
        if (array_key_exists($field, $data) && $data[$field] !== null) {
            // Permitir string vazia apenas para description
            if ($data[$field] === '' && $field !== 'description') {
                error_log("  - PULANDO: campo vazio e não é description");
                continue;
            }
            
            error_log("  - PROCESSANDO campo '$field'");
            
            // Validações específicas
            switch ($field) {
                case 'category_id':
                    if (!$this->validateCategory($data[$field])) {
                        $this->call(400, "bad_request", "Categoria inválida", "error")->back();
                        return;
                    }
                    break;
                    
                case 'cost_price':
                case 'sale_price':
                    if (!is_numeric($data[$field]) || $data[$field] < 0) {
                        $this->call(400, "bad_request", "Preço deve ser um número positivo", "error")->back();
                        return;
                    }
                    $data[$field] = (float) $data[$field];
                    break;
                    
                case 'min_stock':
                case 'max_stock':
                    if (!is_numeric($data[$field]) || $data[$field] < 0) {
                        $this->call(400, "bad_request", "Estoque deve ser um número positivo", "error")->back();
                        return;
                    }
                    $data[$field] = (int) $data[$field];
                    break;
                    
                case 'active':
                    $data[$field] = (int) $data[$field];
                    break;
            }
            
            // Aplicar a atualização
            $product->$setter($data[$field]);
            $updated = true;
            error_log("  - APLICADO: $setter com valor " . var_export($data[$field], true));
        } else {
            error_log("  - IGNORADO: campo não atende critérios");
        }
    }
    
    error_log("Updated = " . ($updated ? 'true' : 'false'));
    error_log("=== FIM DEBUG ===");
    
    // Verificar se houve alguma atualização
    if (!$updated) {
        $this->call(400, "bad_request", "Nenhum campo válido fornecido para atualização", "warning")->back();
        return;
    }
    
    // Salvar no banco de dados
    if (!$product->update()) {
        $errorMessage = method_exists($product, 'getErrorMessage') 
            ? $product->getErrorMessage() 
            : "Erro ao atualizar produto";
            
        $this->call(500, "internal_server_error", $errorMessage, "error")->back();
        return;
    }
    
    // Retornar sucesso com dados atualizados
    $this->call(200, "success", "Produto atualizado com sucesso", "success")->back([
        "id" => $product->getId(),
        "code" => $product->getCode(),
        "name" => $product->getName(),
        "description" => $product->getDescription(),
        "category_id" => $product->getCategoryId(),
        "unit" => $product->getUnit(),
        "cost_price" => $product->getCostPrice(),
        "sale_price" => $product->getSalePrice(),
        "min_stock" => $product->getMinStock(),
        "max_stock" => $product->getMaxStock(),
        "active" => $product->getActive()
    ]);
}

/**
 * Validar se a categoria existe
 */
private function validateCategory($categoryId): bool 
{
    if (empty($categoryId)) {
        return false;
    }
    
    $category = new Category();
    return $category->findById($categoryId);
}

    /**
     * Exclui um produto
     */
    public function deleteProduct(array $data): void
    {
        $this->auth();

        if (empty($data["id"])) {
            $this->call(400, "bad_request", "ID da categoria é obrigatório", "error")->back();
            return;
        }

        $product = new Product();
        if (!$product->findById($data["id"])) {
            $this->call(404, "not_found", "Produto não encontrada", "error")->back();
            return;
        }

        // Verificar se Produto está associada a produtos (exemplo fictício)
        // if ($product->hasProducts()) {
        //     $this->call(409, "conflict", "Não é possível excluir Produto com produtos vinculados", "error")->back();
        //     return;
        // }

        if (!$product->delete()) {
            $this->call(500, "internal_server_error", $product->getErrorMessage(), "error")->back();
            return;
        }

        $this->call(200, "success", "Produto excluída com sucesso", "success")->back();
    
    }

    /**
     * Lista produtos com estoque baixo
     */
    public function getLowStockProducts(): void
    {
        $this->auth();

        $product = new Product();
        $products = $product->findLowStock();

        if (!$products) {
            $this->call(200, "success", "Nenhum produto com estoque baixo", "info")->back([]);
            return;
        }

        $this->call(200, "success", "Produtos com estoque baixo", "warning")->back($products);
    }

    /**
     * Busca produtos por código ou nome
     */
    public function searchProducts(array $data): void
    {
        if (empty($data['search'])) {
            $this->call(400, "bad_request", "Termo de busca é obrigatório", "error")->back();
            return;
        }

        $product = new Product();
        $products = $product->search($data['search']);

        if (!$products) {
            $this->call(200, "success", "Nenhum produto encontrado", "info")->back([]);
            return;
        }

        $this->call(200, "success", "Produtos encontrados", "success")->back($products);
    }

    /**
     * Obter estoque atual de um produto
     */
    public function getProductStock(array $data): void
    {
        if (!isset($data["id"])) {
            $this->call(400, "bad_request", "ID do produto é obrigatório", "error")->back();
            return;
        }

        $product = new Product();
        if (!$product->findById($data["id"])) {
            $this->call(404, "not_found", "Produto não encontrado", "error")->back();
            return;
        }

        $stockInfo = $product->getStockInfo();

        $this->call(200, "success", "Informações de estoque", "success")->back($stockInfo);
    }

    /**
     * Ativar/Desativar produto
     */
    public function toggleProductStatus(array $data): void
    {
        $this->auth();

        if (!isset($data["id"])) {
            $this->call(400, "bad_request", "ID do produto é obrigatório", "error")->back();
            return;
        }

        $product = new Product();
        if (!$product->findById($data["id"])) {
            $this->call(404, "not_found", "Produto não encontrado", "error")->back();
            return;
        }

        $newStatus = !$product->getActive();
        $product->setActive($newStatus);

        if (!$product->update()) {
            $this->call(500, "internal_server_error", $product->getErrorMessage(), "error")->back();
            return;
        }

        $statusText = $newStatus ? "ativado" : "desativado";
        $this->call(200, "success", "Produto {$statusText} com sucesso", "success")->back([
            "id" => $product->getId(),
            "nome" => $product->getName(),
            "active" => $product->getActive()
        ]);
    }
}