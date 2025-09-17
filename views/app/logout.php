<?php
session_start();

// Limpa todas as variáveis de sessão
session_unset();

// Destroi a sessão
session_destroy();

// Redireciona para o login
header("Location: /Web-Service/login");
exit;
