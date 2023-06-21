<?php
require_once 'product.php';
require_once 'dbConnection.php';

abstract class Router
{
    abstract public function getRoute($route);

}

class HandleRouter extends Router
{

    private $dbConnection;
    public function __construct($conn)
    {
        $this->dbConnection = $conn;
    }
    public function getRoute($route)
    {

        if ($route === '/products') {
            $this->displayProducts();
        } elseif ($route === '/newProduct') {
            $newProduct = $_POST['data'];
            $newProduct = json_decode($newProduct);
            $this->addProduct($newProduct);
        } elseif ($route === '/deleteProducts') {
            $data = $_POST['data'];
            $this->deleteProducts($data);
        } else {
            echo "405 Unknown method";
        }

    }

    private function addProduct($data)
    {
        $productItem = new ProductItem($this->dbConnection);
        $response = $productItem->create($data);
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    private function deleteProducts($data)
    {
        $productItem = new ProductItem($this->dbConnection);
        $response = $productItem->massDelete($data);
        $response = (object) [
            'products' => $response
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    private function displayProducts()
    {
        $productItem = new ProductItem($this->dbConnection);
        $response = $productItem->display();
        $response = (object) [
            'products' => $response
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
    }

}

?>