<?php

require_once 'dbConnection.php';
require_once 'product.php';
require_once 'routes.php';

$router = new HandleRouter($conn);
$route = $_SERVER['REQUEST_URI'];
$router->getRoute($route);

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     if ($_SERVER['REQUEST_URI'] === '/deleteProducts') {
//         $ids = $_POST['data'];
//         $dataR = (object) ['message' => 'Invalid product IDs', 'sentData' => $ids, 'products' => []];
//         $dataR = json_encode($dataR);
//         header('Content-Type: application/json');
//         echo json_encode($dataR);
//     }
// }

?>