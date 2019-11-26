<?php
// maybe instead rename this to printify-store
class Product{
  private $connection;
  private $table = "printify-products";

  // Properties of a product
  private $id;
  private $price;
  private $productType;
  private $color;
  private $size;
  private $created_at;

  // Constructor to create new object
  public function __construct($db){
    $this->connection = $db;
  }
  // set values from post
  public function setValues($price, $productType, $color, $size){
    $this->price = $price;
    $this->productType = $productType;
    $this->color = $color;
    $this->size = $size;
  }
  // Get orders
  public function getOrders(){
    $query = "SELECT * FROM products";
    // replace from to orders table later on
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  // Get orders by type
  /*public function getOrdersByType($type){
    $query = "SELECT * FROM orders WHERE type=$type";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }*/

  // get single product
  public function getProduct(){
      if (isset($_GET['id'])){
        $id = $_GET['id'];
        $query = "SELECT * FROM products WHERE id=?";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $_GET['id']);
        $stmt->execute();
        return $stmt;
    } else {
      echo "Please, set id of the product";
      die;
    }
  }

  // create product
  public function createProduct(){
      $query = "INSERT INTO products (id, price, productType, color, size)
      values (DEFAULT, ?, ?, ?, ?)";
      $stmt = $this->connection->prepare($query);
      $this->price = htmlspecialchars(strip_tags($this->price));
      $this->productType = htmlspecialchars(strip_tags($this->productType));
      $this->color = htmlspecialchars(strip_tags($this->color));
      $this->size = htmlspecialchars(strip_tags($this->size));
      $stmt->bindParam(1, $this->price);
      $stmt->bindParam(2, $this->productType);
      $stmt->bindParam(3, $this->color);
      $stmt->bindParam(4, $this->size);
      // maybe row count insert here
      if ($stmt->execute()){
        return (true);
      }
      return (false);
  }
  // check if given type, color and size does not exist
  private function is_new(){
    $query = "SELECT * FROM products WHERE productType=? AND color=? AND size=?";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(1, $_GET['type']);
    $stmt->bindParam(2, $_GET['color']);
    $stmt->bindParam(3, $_GET['size']);
    $stmt->execute();
    if ($stmt->rowCount() == 0){
      return (true);
    }
    return (false);
  }
}
