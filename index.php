<?php

require __DIR__ . "/vendor/autoload.php";
//require __DIR__ . "/source/Core/Config.php";
require_once "source/Core/Config.php";
// require __DIR__ . "/source/core/Helpers.php";
require_once "source/Core/Helpers.php";

require_once "source/Models/User.php";




use CoffeeCode\Router\Router;

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

$route->dispatch();

if ($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}

ob_end_flush();