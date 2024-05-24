<?php
session_start();
include_once ("Configuration.php");
$controllerName = isset($_GET["controller"]) ? $_GET["controller"] : "" ;
$actionName = isset($_GET["action"]) ? $_GET["action"] : "" ;

if (!isset($_SESSION['user']) && $controllerName != "Users") {
    // Si el usuario no está logueado, redirige al controlador de inicio de sesión
    $controllerName = "Users";
    $actionName = "getLogin";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $controllerName == "Users" && $actionName == "getRegister") {
    $controllerName = "Users";
    $actionName = "postRegister";
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && $controllerName == "Users" && $actionName == "getLogin") {
    $controllerName = "Users";
    $actionName = "postLogin";
}

$router = Configuration::getRouter();
$router->route($controllerName, $actionName);

// index.php?controller=tours&action=get
// tours/get

