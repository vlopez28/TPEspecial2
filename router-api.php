<?php

require_once 'libs/Router.php';  
require_once './api/properties.api.controller.php';
require_once './api/auth.api.controller.php';

// crea el router
$router = new Router();

// define la tabla de ruteo
$router->addRoute('properties', 'GET', 'PropertiesApiController', 'getAll');
$router->addRoute('properties/:ID', 'GET', 'PropertiesApiController', 'getOne');
$router->addRoute('properties/:ID', 'DELETE', 'PropertiesApiController', 'delete');
$router->addRoute('properties', 'POST', 'PropertiesApiController', 'insert');
$router->addRoute('properties/:ID', 'PUT', 'PropertiesApiController', 'update');
$router->addRoute('users/token', 'GET', 'AuthApiController', 'getToken');
$router->addRoute('users/:ID', 'GET', 'AuthApiController', 'getUser');

// rutea
$resource = $_GET["resource"];
$method = $_SERVER['REQUEST_METHOD'];
$router->route($resource, $method);

