<?php

require_once 'dbConnection.php';
require_once 'product.php';
require_once 'routes.php';

$router = new HandleRouter($conn);
$route = $_SERVER['REQUEST_URI'];
$router->getRoute($route);

?>