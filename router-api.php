<?php

require_once 'libs/Router.php';  
require_once './api/properties.api.controller.php';
require_once './api/auth.api.controller.php';

$router = new Router();

$router->addRoute('properties', 'GET', 'PropertiesApiController', 'getAll');
$router->addRoute('properties/:ID', 'GET', 'PropertiesApiController', 'getOne');
$router->addRoute('properties/:ID', 'DELETE', 'PropertiesApiController', 'delete');
$router->addRoute('properties', 'POST', 'PropertiesApiController', 'insert');
$router->addRoute('properties/:ID', 'PUT', 'PropertiesApiController', 'update');
$router->addRoute('users/token', 'GET', 'AuthApiController', 'getToken');

$resource = $_GET["resource"];
$method = $_SERVER['REQUEST_METHOD'];
$router->route($resource, $method);

