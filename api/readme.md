#####

Base:  
http://localhost/Web-Service

---

## Rotas Públicas

### GET `/`  
**Descrição:** Página inicial do site.  
**Parâmetros:** Nenhum  
**Resposta:** Conteúdo HTML da página inicial ou JSON com informações.  
**Códigos HTTP:**  
- `200 OK` – Sucesso  
- `500 Internal Server Error` – Erro interno

---

### GET `/sobre`  
**Descrição:** Página "Sobre nós".  
**Parâmetros:** Nenhum  
**Exemplo de requisição:**  
GET http://localhost/Web-Service/sobre

**Resposta:** Conteúdo HTML ou JSON da página "Sobre".  
**Códigos HTTP:**  
- `200 OK`  
- `500 Internal Server Error`

---

### GET `/contato`  
**Descrição:** Página de contato.  
**Parâmetros:** Nenhum  
**Resposta:** Formulário ou informações para contato.  
**Códigos HTTP:**  
- `200 OK`  
- `500 Internal Server Error`

---

### GET `/login`  
**Descrição:** Página de login do usuário.  
**Parâmetros:** Nenhum  
**Resposta:** Formulário de login.  
**Códigos HTTP:**  
- `200 OK`  
- `401 Unauthorized` – Se tentar acessar área restrita sem autenticação.

---

### GET `/registro`  
**Descrição:** Página para registro de novos usuários.  
**Parâmetros:** Nenhum  
**Resposta:** Formulário de registro.  
**Códigos HTTP:**  
- `200 OK`  
- `400 Bad Request` – Em caso de dados inválidos no POST (não nesta rota GET).

---

### GET `/faqs`  
**Descrição:** Página de Perguntas Frequentes.  
**Parâmetros:** Nenhum  
**Resposta:** Conteúdo das FAQs.  
**Códigos HTTP:**  
- `200 OK`

---

## Rotas Restritas (Área do Usuário)

> **Prefixo:** `/app`

### GET `/app/`  
**Descrição:** Página inicial da área restrita (dashboard do usuário).  
**Requer autenticação.**  
**Parâmetros:** Nenhum  
**Resposta:** Conteúdo personalizado para o usuário logado.  
**Códigos HTTP:**  
- `200 OK`  
- `401 Unauthorized` – Caso não autenticado.

---

## Rotas Administrativas

> **Prefixo:** `/admin`

### GET `/admin/`  
**Descrição:** Página inicial da área administrativa.  
**Requer autenticação de administrador.**  
**Resposta:** Dashboard administrativo.  
**Códigos HTTP:**  
- `200 OK`  
- `401 Unauthorized`

### GET `/admin/clientes`  
**Descrição:** Página/listagem de clientes.  
**Requer autenticação de administrador.**  
**Resposta:** Lista de clientes cadastrados.  
**Códigos HTTP:**  
- `200 OK`  
- `401 Unauthorized`

---

## Tratamento de Erros

### GET `/ops/{errcode}`  
**Descrição:** Página de erro que exibe o código do erro passado na URL.  
**Parâmetros:**  
- `errcode` (integer) – Código do erro HTTP.  
**Exemplo:**  
GET http://localhost/Web-Service/ops/404
