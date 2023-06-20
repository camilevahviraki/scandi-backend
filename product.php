<?php

abstract class Product
{
    protected $id;
    protected $name;
    protected $sku;
    protected $price;
    protected $attributeName;
    protected $attributeValue;
    protected $sizeMB;
    protected $weightKG;
    protected $dimentions;
    protected $product;
    public function getProduct()
    {
        return $this->product;
    }
    public function setProduct($product)
    {
        $this->product = $product;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function setAttributeName($attributeName)
    {
        $this->attributeName = $attributeName;
    }

    public function setAttributeValue($attributeValue)
    {
        $this->attributeValue = $attributeValue;
    }

    public function setSizeMB($sizeMB)
    {
        $this->sizeMB = $sizeMB;
    }

    public function setDimentions($dimentions)
    {
        $this->dimentions = $dimentions;
    }

    public function setWeightKG($weightKG)
    {
        $this->weightKG = $weightKG;
    }

    abstract public function generateUniqueSKU($sku);

    abstract public function create($data);

    abstract public function display();

    abstract public function massDelete($ids);
}


class ProductItem extends Product
{

    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function generateUniqueSKU($providedSKU)
    {
        $length = 4;
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $sku = '';

        do {
            $randomString = $providedSKU . '_';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }

            $query = "SELECT COUNT(*) FROM products WHERE sku = '$randomString'";
            $result = $this->conn->query($query);
            $count = $result->fetch_row()[0];

            if ($count == 0) {
                $sku = $randomString;
            }
        } while ($sku == '');

        $this->sku = $sku;
        return true;
    }
    public function create($productData)
    {

        $this->setName($productData->name);
        $this->setSku($productData->sku);
        $this->setPrice($productData->price);
        $this->setAttributeName($productData->attributeName);
        $this->setAttributeValue($productData->attributeValue);
        $this->setSizeMB($productData->sizeMB);
        $this->setDimentions($productData->dimentions);
        $this->setWeightKG($productData->weightKG);

        $this->setProduct($productData);

        $create_product = null;

        if ($this->generateUniqueSKU($productData->sku)) {
            $create_product = "INSERT INTO products (name, sku, price, attributeName, attributeValue, sizeMB, weightKG, dimentions)
         VALUES (
            '$this->name',
            '$this->sku',
            $this->price,
            '$this->attributeName',
            '$this->attributeValue',
            '$this->sizeMB',
            '$this->weightKG',
            '$this->dimentions'
        )";

            if ($this->conn->query($create_product)) {
                return (object) ['message' => 'Product Created successfully'];
            } else {
                return (object) ['message' => 'Error creating product'];
            }

        } else {
            return (object) ['message' => 'Error generating product_sku'];
        }


    }

    public function display()
    {
        $query = "SELECT * FROM products";
        $result = $this->conn->query($query);

        if ($result) {
            if ($result->num_rows > 0) {
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                return $rows;
            } else {
                return [];
            }
        } else {
            return ['error' => "Error executing query: " . $this->conn->error];
        }
    }

    public function massDelete($productsSelected)
    {
        $idsString = implode(",", $productsSelected);
        $query = "DELETE FROM products WHERE id IN ($idsString)";
        if ($this->conn->query($query)) {
            return (object) ['message' => 'Products deleted succesfully'];
        } else {
            return (object) ['message' => 'Error deleting Products'];
        }
    }
}

?>