<?php

class ProductUtils{
  private $connection;
  /* Create object with connection to mysql */
  public function __construct($connection){
    $this->connection = $connection;
  }
  /* query for a single product by id */
  public function queryProductById($id){
    $id = $_GET['id'];
    $query = "SELECT * FROM products WHERE id=?";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(1, $_GET['id']);
    $stmt->execute();
    return $stmt;
  }
  /* query for all products */
  public function queryAllProducts(){
    $query = "SELECT * FROM products";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }
  /* create product */
  public function createProduct($price, $type, $color, $size){
      if ($this->is_new($type, $color, $size)){
        $query = "INSERT INTO products (id, price, productType, color, size)
        values (DEFAULT, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $price = htmlspecialchars(strip_tags($price));
        $productType = htmlspecialchars(strip_tags($type));
        $color = htmlspecialchars(strip_tags($color));
        $size = htmlspecialchars(strip_tags($size));
        $stmt->bindParam(1, $price);
        $stmt->bindParam(2, $type);
        $stmt->bindParam(3, $color);
        $stmt->bindParam(4, $size);
        $stmt->execute();
      } else {
        echo json_encode(array(
          "message"=> "product of type $type, color $color, size $size exists already."
        )); die;
      }
  }
  /* check if product of type, color and size does not exist */
  public function is_new($type, $color, $size){
    $query = "SELECT * FROM products WHERE productType=? AND color=? AND size=?";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(1, $type);
    $stmt->bindParam(2, $color);
    $stmt->bindParam(3, $size);
    $stmt->execute();
    if ($stmt->rowCount() == 0)
      return (true);
    return (false);
  }
  /* Given an id of a product, get it's price */
  public function getProductPrice($id){
    $query = "SELECT * FROM products WHERE id=?";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    if ($stmt->rowCount() == 0){
      echo json_encode(array("message" => "Incorrect product ID"));die;
    }
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
    return ($price);
  }
  /* Given an id of a product, get it's type */
  public function getProductType($id){
    $query = "SELECT * FROM products WHERE id=?";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    if ($stmt->rowCount() == 0){
      echo json_encode(array("message" => "Incorrect product type"));die;
    }
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
    return ($productType);
  }
}
