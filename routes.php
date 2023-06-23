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
        if ($route === '/') {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = $_POST['data'];
                $delete = $_POST['delete'];
                if ($delete === 1) {
                    $this->deleteProducts($data);
                } else {
                    $newProduct = json_decode($data);
                    $this->addProduct($newProduct);
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->displayProducts();
            }

        }else {
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