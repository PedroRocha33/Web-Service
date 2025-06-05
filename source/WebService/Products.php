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
            "categoria_id" => $product->getCategoryId(),
            "preco_venda" => $product->getSalePrice(),
            "ativo" => $product->getActive()
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
            "descricao" => $product->getDescription(),
            "categoria_id" => $product->getCategoryId(),
            "unidade_medida" => $product->getUnit(),
            "preco_custo" => $product->getCostPrice(),
            "preco_venda" => $product->getSalePrice(),
            "estoque_minimo" => $product->getMinStock(),
            "estoque_maximo" => $product->getMaxStock(),
            "ativo" => $product->getActive(),
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
    
    // Debug: Log dos dados recebidos
    error_log("=== UPDATE PRODUCT DEBUG ===");
    error_log("Dados recebidos: " . json_encode($data));
    error_log("Chaves disponíveis: " . implode(', ', array_keys($data)));
    error_log("Total de campos recebidos: " . count($data));
    
    if (!isset($data["id"])) {
        error_log("ERRO: ID não fornecido");
        $this->call(400, "bad_request", "ID é obrigatório", "error")->back();
        return;
    }
    
    error_log("ID do produto: " . $data["id"]);
    
    // Buscar o produto existente
    $product = new Product();
    if (!$product->findById($data["id"])) {
        error_log("ERRO: Produto não encontrado com ID: " . $data["id"]);
        $this->call(404, "not_found", "Produto não encontrado", "error")->back();
        return;
    }
    
    error_log("Produto encontrado - ID: " . $product->getId());
    error_log("Dados atuais do produto: " . json_encode([
        'id' => $product->getId(),
        'codigo' => $product->getCode(),
        'name' => $product->getName(),
        'categoria_id' => $product->getCategoryId(),
        'preco_custo' => $product->getCostPrice(),
        'preco_venda' => $product->getSalePrice()
    ]));
    
    // Validar categoria se fornecida
    if (isset($data['categoria_id'])) {
        error_log("Validando categoria ID: " . $data['category_id']);
        $category = new Category();
        if (!$category->findById($data['category_id'])) {
            error_log("ERRO: Categoria não encontrada: " . $data['category_id']);
            $this->call(400, "bad_request", "Categoria não encontrada", "error")->back();
            return;
        }
        error_log("Categoria válida: " . $data['category_id']);
    }
    
    // Validar preços se fornecidos
    if (isset($data['preco_custo'])) {
        error_log("Validando preço de custo: " . $data['preco_custo']);
        if (!is_numeric($data['preco_custo'])) {
            error_log("ERRO: Preço de custo não é numérico: " . $data['preco_custo']);
            $this->call(400, "bad_request", "Preço de custo deve ser numérico", "error")->back();
            return;
        }
        error_log("Preço de custo válido: " . $data['preco_custo']);
    }
    
    if (isset($data['preco_venda'])) {
        error_log("Validando preço de venda: " . $data['preco_venda']);
        if (!is_numeric($data['preco_venda'])) {
            error_log("ERRO: Preço de venda não é numérico: " . $data['preco_venda']);
            $this->call(400, "bad_request", "Preço de venda deve ser numérico", "error")->back();
            return;
        }
        error_log("Preço de venda válido: " . $data['preco_venda']);
    }
    
    // Verificar se há campos para atualizar
    $camposParaAtualizar = [];
    
    // Atualizar apenas os campos fornecidos
    if (isset($data['codigo'])) {
        error_log("Atualizando código: " . $data['codigo']);
        $product->setCode($data['codigo']);
        $camposParaAtualizar[] = 'codigo';
    }
    
    if (isset($data['name'])) {
        error_log("Atualizando nome: " . $data['name']);
        $product->setName($data['name']);
        $camposParaAtualizar[] = 'name';
    }
    
    if (isset($data['descricao'])) {
        error_log("Atualizando descrição: " . $data['descricao']);
        $product->setDescription($data['descricao']);
        $camposParaAtualizar[] = 'descricao';
    }
    
    if (isset($data['categoria_id'])) {
        error_log("Atualizando categoria_id: " . $data['categoria_id']);
        $product->setCategoryId($data['categoria_id']);
        $camposParaAtualizar[] = 'categoria_id';
    }
    
    if (isset($data['unidade_medida'])) {
        error_log("Atualizando unidade_medida: " . $data['unidade_medida']);
        $product->setUnit($data['unidade_medida']);
        $camposParaAtualizar[] = 'unidade_medida';
    }
    
    if (isset($data['preco_custo'])) {
        $precoCusto = (float)$data['preco_custo'];
        error_log("Atualizando preco_custo: " . $precoCusto);
        $product->setCostPrice($precoCusto);
        $camposParaAtualizar[] = 'preco_custo';
    }
    
    if (isset($data['preco_venda'])) {
        $precoVenda = (float)$data['preco_venda'];
        error_log("Atualizando preco_venda: " . $precoVenda);
        $product->setSalePrice($precoVenda);
        $camposParaAtualizar[] = 'preco_venda';
    }
    
    if (isset($data['estoque_minimo'])) {
        $estoqueMin = (int)$data['estoque_minimo'];
        error_log("Atualizando estoque_minimo: " . $estoqueMin);
        $product->setMinStock($estoqueMin);
        $camposParaAtualizar[] = 'estoque_minimo';
    }
    
    if (isset($data['estoque_maximo'])) {
        $estoqueMax = (int)$data['estoque_maximo'];
        error_log("Atualizando estoque_maximo: " . $estoqueMax);
        $product->setMaxStock($estoqueMax);
        $camposParaAtualizar[] = 'estoque_maximo';
    }
    
    if (isset($data['ativo'])) {
        $ativo = (bool)$data['ativo'];
        error_log("Atualizando ativo: " . ($ativo ? 'true' : 'false'));
        $product->setActive($ativo);
        $camposParaAtualizar[] = 'ativo';
    }
    
    // Verificar se há campos para atualizar
    if (empty($camposParaAtualizar)) {
        error_log("AVISO: Nenhum campo fornecido para atualização");
        $this->call(400, "bad_request", "Nenhum campo fornecido para atualização", "warning")->back();
        return;
    }
    
    error_log("Campos que serão atualizados: " . implode(', ', $camposParaAtualizar));
    
    // Log dos dados do produto antes da atualização
    error_log("Dados do produto ANTES da atualização: " . json_encode([
        'id' => $product->getId(),
        'codigo' => $product->getCode(),
        'name' => $product->getName(),
        'categoria_id' => $product->getCategoryId(),
        'preco_custo' => $product->getCostPrice(),
        'preco_venda' => $product->getSalePrice(),
        'ativo' => $product->getActive()
    ]));
    
    // Verificar se o método update existe
    if (!method_exists($product, 'update')) {
        error_log("ERRO CRÍTICO: Método 'update' não existe na classe Product");
        $this->call(500, "internal_server_error", "Método de atualização não encontrado", "error")->back();
        return;
    }
    
    error_log("Executando $product->update()...");
    
    // Tentar atualizar
    $updateResult = $product->update();
    
    error_log("Resultado do update(): " . ($updateResult ? 'true' : 'false'));
    
    if (!$updateResult) {
        // Verificar se existe método para pegar mensagem de erro
        $errorMessage = "Erro interno do servidor";
        
        if (method_exists($product, 'getErrorMessage')) {
            $errorMessage = $product->getErrorMessage();
            error_log("Mensagem de erro do produto: " . $errorMessage);
        } else {
            error_log("AVISO: Método 'getErrorMessage' não existe na classe Product");
        }
        
        // Verificar se existe método para pegar último erro SQL
        if (method_exists($product, 'getLastError')) {
            $lastError = $product->getLastError();
            error_log("Último erro SQL: " . $lastError);
        }
        
        // Verificar se existe propriedade ou método para erro de PDO
        if (method_exists($product, 'getPdoError')) {
            $pdoError = $product->getPdoError();
            error_log("Erro PDO: " . $pdoError);
        }
        
        error_log("ERRO: Falha na atualização do produto");
        $this->call(500, "internal_server_error", $errorMessage, "error")->back();
        return;
    }
    
    error_log("Produto atualizado com sucesso!");
    
    // Log dos dados do produto APÓS a atualização
    error_log("Dados do produto APÓS a atualização: " . json_encode([
        'id' => $product->getId(),
        'codigo' => $product->getCode(),
        'name' => $product->getName(),
        'categoria_id' => $product->getCategoryId(),
        'preco_custo' => $product->getCostPrice(),
        'preco_venda' => $product->getSalePrice(),
        'ativo' => $product->getActive()
    ]));
    
    // Verificar se os dados realmente mudaram no banco
    $productVerify = new Product();
    if ($productVerify->findById($data["id"])) {
        error_log("Verificação - dados no banco após update: " . json_encode([
            'id' => $productVerify->getId(),
            'codigo' => $productVerify->getCode(),
            'name' => $productVerify->getName(),
            'categoria_id' => $productVerify->getCategoryId(),
            'preco_custo' => $productVerify->getCostPrice(),
            'preco_venda' => $productVerify->getSalePrice(),
            'ativo' => $productVerify->getActive()
        ]));
    } else {
        error_log("ERRO: Não foi possível verificar os dados após a atualização");
    }
    
    error_log("=== FIM UPDATE PRODUCT DEBUG ===");
    
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
            "ativo" => $product->getActive()
        ]);
    }
}