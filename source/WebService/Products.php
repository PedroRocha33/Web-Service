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
            'categoria_id' => $_GET['categoria_id'] ?? null,
            'ativo' => $_GET['ativo'] ?? null,
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

        // Validar campos obrigatórios
        $required = ['codigo', 'name', 'categoria_id', 'unidade_medida', 'preco_custo', 'preco_venda'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->call(400, "bad_request", "Campo '{$field}' é obrigatório", "error")->back();
                return;
            }
        }

        // Validar se categoria existe
        $category = new Category();
        if (!$category->findById($data['categoria_id'])) {
            $this->call(400, "bad_request", "Categoria não encontrada", "error")->back();
            return;
        }

        // Validar valores numéricos
        if (!is_numeric($data['preco_custo']) || !is_numeric($data['preco_venda'])) {
            $this->call(400, "bad_request", "Preços devem ser valores numéricos", "error")->back();
            return;
        }

        $product = new Product(
            null, // id será gerado automaticamente
            $data['codigo'],
            $data['name'],
            $data['descricao'] ?? null,
            $data['categoria_id'],
            $data['unidade_medida'],
            (float)$data['preco_custo'],
            (float)$data['preco_venda'],
            (int)($data['estoque_minimo'] ?? 0),
            (int)($data['estoque_maximo'] ?? 0),
            $data['ativo'] ?? true
        );

        if (!$product->insert()) {
            $this->call(500, "internal_server_error", $product->getErrorMessage(), "error")->back();
            return;
        }

        // Resposta com dados do produto criado
        $response = [
            "id" => $product->getId(),
            "codigo" => $product->getCode(),
            "name" => $product->getName(),
            "categoria_id" => $product->getCategoriaId(),
            "preco_venda" => $product->getPrecoVenda(),
            "ativo" => $product->getAtivo()
        ];

        $this->call(201, "created", "Produto criado com sucesso", "success")->back($response);
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
            "codigo" => $product->getCode(),
            "name" => $product->getName(),
            "descricao" => $product->getDescricao(),
            "categoria_id" => $product->getCategoriaId(),
            "categoria_nome" => $product->getCategoriaNome(), // Se tiver join
            "unidade_medida" => $product->getUnidadeMedida(),
            "preco_custo" => $product->getPrecoCusto(),
            "preco_venda" => $product->getPrecoVenda(),
            "estoque_minimo" => $product->getEstoqueMinimo(),
            "estoque_maximo" => $product->getEstoqueMaximo(),
            "estoque_atual" => $product->getEstoqueAtual(),
            "ativo" => $product->getAtivo(),
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

        if (!isset($data["id"])) {
            $this->call(400, "bad_request", "ID é obrigatório", "error")->back();
            return;
        }

          // ✅ CORREÇÃO: Buscar o produto existente
    $product = new Product();
    if (!$product->findById($data["id"])) {
    $this->call(404, "not_found", "Produto não encontrado", "error")->back();
    return;
}

        // Validar categoria se fornecida
        if (isset($data['categoria_id'])) {
            $category = new Category();
            if (!$category->findById($data['categoria_id'])) {
                $this->call(400, "bad_request", "Categoria não encontrada", "error")->back();
                return;
            }
        }

        // Validar preços se fornecidos
        if (isset($data['preco_custo']) && !is_numeric($data['preco_custo'])) {
            $this->call(400, "bad_request", "Preço de custo deve ser numérico", "error")->back();
            return;
        }

        if (isset($data['preco_venda']) && !is_numeric($data['preco_venda'])) {
            $this->call(400, "bad_request", "Preço de venda deve ser numérico", "error")->back();
            return;
        }

        // Atualizar apenas os campos fornecidos
        if (isset($data['codigo'])) $product->setCode($data['codigo']);
        if (isset($data['name'])) $product->setName($data['name']);
        if (isset($data['description'])) $product->setDescription($data['description']);
        if (isset($data['categoria_id'])) $product->setCategoryId($data['categoria_id']);
        if (isset($data['unidade_medida'])) $product->setUnit($data['unidade_medida']);
        if (isset($data['preco_custo'])) $product->setCostPrice((float)$data['preco_custo']);
        if (isset($data['preco_venda'])) $product->setSalePrice((float)$data['preco_venda']);
        if (isset($data['estoque_minimo'])) $product->setMinStock((int)$data['estoque_minimo']);
        if (isset($data['estoque_maximo'])) $product->setMaxStock((int)$data['estoque_maximo']);
        if (isset($data['ativo'])) $product->setActive((bool)$data['ativo']);

        if (!$product->update()) {
            $this->call(500, "internal_server_error", $product->getErrorMessage(), "error")->back();
            return;
        }

        $this->call(200, "success", "Produto atualizado com sucesso", "success")->back([
            "id" => $product->getId(),
            "codigo" => $product->getCode(),
            "name" => $product->getName(),
            "preco_venda" => $product->getSalePrice(),
            "ativo" => $product->getActive()
        ]);
    }

    /**
     * Exclui um produto
     */
    public function deleteProduct(array $data): void
    {
        $this->auth(); // Verificar autenticação

        if (!isset($data["id"])) {
            $this->call(400, "bad_request", "ID é obrigatório", "error")->back();
            return;
        }

        $product = new Product();
        if (!$product->findById($data["id"])) {
            $this->call(404, "not_found", "Produto não encontrado", "error")->back();
            return;
        }

        // Verificar se produto tem movimentações de estoque
        if ($product->hasStockMovements()) {
            $this->call(409, "conflict", "Não é possível excluir produto com movimentações de estoque", "error")->back();
            return;
        }

        if (!$product->delete()) {
            $this->call(500, "internal_server_error", $product->getErrorMessage(), "error")->back();
            return;
        }

        $this->call(200, "success", "Produto excluído com sucesso", "success")->back();
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

        $newStatus = !$product->getAtivo();
        $product->setAtivo($newStatus);

        if (!$product->update()) {
            $this->call(500, "internal_server_error", $product->getErrorMessage(), "error")->back();
            return;
        }

        $statusText = $newStatus ? "ativado" : "desativado";
        $this->call(200, "success", "Produto {$statusText} com sucesso", "success")->back([
            "id" => $product->getId(),
            "nome" => $product->getNome(),
            "ativo" => $product->getAtivo()
        ]);
    }
}