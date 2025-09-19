<?php

require __DIR__ . "/vendor/autoload.php";
//require __DIR__ . "/source/Core/Config.php";
require_once "source/Core/Config.php";
// require __DIR__ . "/source/core/Helpers.php";
require_once "source/Core/Helpers.php";

require_once "source/Models/User.php";




use CoffeeCode\Router\Router;
use Source\Models\User;

ob_start();

$route = new Router("http://localhost/Web-Service", ":");

$route->namespace("Source\Web");
// Rotas amigáveis da área pública
$route->get("/", "Site:home");
$route->get("/sobre", "Site:about");
$route->get("/contato", "Site:contact");

$route->get("/login", "Site:login");
$route->post("/login", "Site:login");

$route->get("/registro","Site:register"); 
// Formulário de registro
$route->get("/registro","Site:register");

// Processar o submit do registro
$route->post("/registro","Site:register");

$route->get("/faqs","Site:faqs");


// Rotas amigáveis da área restrita
$route->group("/app");
$route->get("/", "App:home");
$route->get("/profile", "App:profile");

$route->get("/edit-profile", "App:editprofile");
$route->post("/edit-profile", "App:editprofile");
//$route->post("/edit-profile", "App:editprofile");



$route->group(null);

$route->group("/admin");
$route->get("/", "Admin:home");
$route->get("/clientes", "Admin:clients");
$route->group(null);



$route->get("/ops/{errcode}", "Site:error");

$route->group(null);

// Rotas da área individual, uma para cada cliente do SaaS
// var_dump($_GET["route"]);
$linkRaw = $_GET['route'] ?? '';
if (preg_match('~^/?([^/]+)~', $linkRaw, $m)) {
    $link = $m[1]; // ex.: "loja-fabio", "loja-maria", "loja-joao"
} else {
    $link = '';
}
//var_dump($link);
$user = new User();
if($user->findLink($link)){
    $route->group("/{$link}");
    $route->get("/", "Customer:home");
    $route->get("/catalogo", "Customer:catalog");
    // Demais rotas
    // $route->get("/carrinho-compras", "Customer:cart");
    $route->group(null);
}

$route->dispatch();

if ($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}

ob_end_flush();