<?php

namespace Source\WebService;

use Source\Core\JWTToken;
use Source\Models\User; // ✅ ADICIONE ESTA LINHA


class Api
{
    protected $userAuth = null;

    protected $headers;
    protected $response;

    public function __construct()
    {
        header('Content-Type: application/json; charset=UTF-8');
        $this->headers = getallheaders();
    }

    protected function call (int $code, string $status = null, string $message = null, $type = null): Api
    {
        http_response_code($code);
        if(!empty($status)){
            $this->response = [
                "code" => $code,
                "type" => $type,
                "status" => $status,
                "message" => (!empty($message) ? $message : null)
            ];
        }
        return $this;
    }

    protected function back(array $data = null): Api
    {
        if ($data) {
            $this->response["data"] = $data;
        }
        echo json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $this;
    }

    protected function auth(): void
    {
        $token = $this->headers['token'] ?? null;

        if (!$token) {
            $this->call(401, "unauthorized", "Token não fornecido", "error")->back();
            exit();
        }

        $jwt = new JWTToken();
        $decoded = $jwt->decode($token);

        if (!$decoded) {
            $this->call(401, "unauthorized", "Token inválido ou expirado", "error")->back();
            exit();
        }

        // $this->userAuth = $decoded->data;

          // ✅ CORREÇÃO: Extrair apenas o ID do usuário
        $userData = (array) $decoded->data;
        $userId = $userData['id'] ?? $userData['user_id'] ?? null;
        
        if ($userId) {
            $this->userAuth = new User($userId);
        }
    
    }

}

