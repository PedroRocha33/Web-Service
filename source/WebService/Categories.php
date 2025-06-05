<?php

namespace Source\WebService;

use Source\Models\Category;

class Categories extends Api
{
    /**
     * Lista todas as categorias
     */
    public function listCategories(): void
    {
        $filters = [
            'ativo' => $_GET['ativo'] ?? null,
            'search' => $_GET['search'] ?? null
        ];

        $page = (int)($_GET['page'] ?? 1);
        $limit = (int)($_GET['limit'] ?? 20);

        $category = new Category();
        $categories = $category->findAll($filters, $page, $limit);

        if (!$categories) {
            $this->call(200, "success", "Nenhuma categoria encontrada", "info")->back([]);
            return;
        }

        $this->call(200, "success", "Lista de categorias", "success")->back($categories);
    }

    /**
     * Cria uma nova categoria
     */
    public function createCategory(array $data): void
    {
        $this->auth(); // Verificar autenticação

        if (empty($data['name'])) {
            $this->call(400, "bad_request", "Nome da categoria é obrigatório", "error")->back();
            return;
        }

        $category = new Category(
            $data['id'],
            $data['name'],
            $data['description'] ?? null,
            $data['active'] ?? true
        );

        if (!$category->insert()) {
            $this->call(500, "internal_server_error", $category->getErrorMessage(), "error")->back();
            return;
        }

        $this->call(201, "created", "Categoria criada com sucesso", "success")->back([
            "id" => $category->getId(),
            "name" => $category->getNome(),
            "active" => $category->getAtivo()
        ]);
    }

    /**
     * Busca categoria por ID
     */
    public function getCategoryById(array $data): void
    {
        if (empty($data["id"])) {
            $this->call(400, "bad_request", "ID da categoria é obrigatório", "error")->back();
            return;
        }

        $category = new Category();
        if (!$category->findById($data["id"])) {
            $this->call(404, "not_found", "Categoria não encontrada", "error")->back();
            return;
        }

        $this->call(200, "success", "Categoria encontrada", "success")->back([
            "id" => $category->getId(),
            "nome" => $category->getNome(),
            "descricao" => $category->getDescricao(),
            "ativo" => $category->getAtivo(),
            "created_at" => $category->getCreatedAt(),
            "updated_at" => $category->getUpdatedAt()
        ]);
    }

    /**
     * Atualiza uma categoria
     */
    public function updateCategory(array $data): void
    {
        $this->auth();

        if (empty($data["id"])) {
            $this->call(400, "bad_request", "ID da categoria é obrigatório", "error")->back();
            return;
        }

        $category = new Category();
        if (!$category->findById($data["id"])) {
            $this->call(404, "not_found", "Categoria não encontrada", "error")->back();
            return;
        }

        if (isset($data["name"])) $category->setNome($data["name"]);
        if (isset($data["description"])) $category->setDescricao($data["description"]);
        if (isset($data["active"])) $category->setAtivo((bool)$data["active"]);

        if (!$category->update()) {
            $this->call(500, "internal_server_error", $category->getErrorMessage(), "error")->back();
            return;
        }

        $this->call(200, "success", "Categoria atualizada com sucesso", "success")->back([
            "id" => $category->getId(),
            "name" => $category->getNome(),
            "active" => $category->getAtivo()
        ]);
    }

    /**
     * Exclui uma categoria
     */
    public function deleteCategory(array $data): void
    {
        $this->auth();

        if (empty($data["id"])) {
            $this->call(400, "bad_request", "ID da categoria é obrigatório", "error")->back();
            return;
        }

        $category = new Category();
        if (!$category->findById($data["id"])) {
            $this->call(404, "not_found", "Categoria não encontrada", "error")->back();
            return;
        }

        // Verificar se categoria está associada a produtos (exemplo fictício)
        // if ($category->hasProducts()) {
        //     $this->call(409, "conflict", "Não é possível excluir categoria com produtos vinculados", "error")->back();
        //     return;
        // }

        if (!$category->delete()) {
            $this->call(500, "internal_server_error", $category->getErrorMessage(), "error")->back();
            return;
        }

        $this->call(200, "success", "Categoria excluída com sucesso", "success")->back();
    }

    /**
     * Ativar/Desativar categoria
     */
    public function toggleCategoryStatus(array $data): void
    {
        $this->auth();

        if (empty($data["id"])) {
            $this->call(400, "bad_request", "ID da categoria é obrigatório", "error")->back();
            return;
        }

        $category = new Category();
        if (!$category->findById($data["id"])) {
            $this->call(404, "not_found", "Categoria não encontrada", "error")->back();
            return;
        }

        $newStatus = !$category->getAtivo();
        $category->setAtivo($newStatus);

        if (!$category->update()) {
            $this->call(500, "internal_server_error", $category->getErrorMessage(), "error")->back();
            return;
        }

        $statusText = $newStatus ? "ativada" : "desativada";
        $this->call(200, "success", "Categoria {$statusText} com sucesso", "success")->back([
            "id" => $category->getId(),
            "ativo" => $category->getAtivo()
        ]);
    }
}
