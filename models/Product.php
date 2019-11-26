<?php
// maybe instead rename this to printify-store
// furthermoe, maybe dont have printify-store class but just a file with connection
// and functions you can include idk
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
  public function getProducts(){
    $query = "SELECT * FROM products";
    // replace from to orders table later on
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  // get every order
  public function listOrders(){
    $query = "SELECT * FROM orders";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }
  // select all items from a specfic order by ID in orders_products table
  public function listOrderProducts($id){
    $query = "SELECT * FROM orders_products WHERE orderID=?";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(1, $id);
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

  // check if given type, color and size does not exist
  public function is_new(){
    $query = "SELECT * FROM products WHERE productType=? AND color=? AND size=?";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(1, $this->productType);
    $stmt->bindParam(2, $this->color);
    $stmt->bindParam(3, $this->size);
    $stmt->execute();
    if ($stmt->rowCount() == 0){
      return (true);
    }
    return (false);
  }
  // create product
  public function createProduct(){
      if ($this->is_new()){
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
      } else {
        echo json_encode(array(
          "message"=> "product of type $this->type, color $this->color, size $this->size exists already."
        ));
      }
  }

  public function insertOrderID(){
    $queryInsert = "INSERT INTO orders (id) values (DEFAULT)";
    $queryLastID = "SELECT LAST_INSERT_ID()";
    $this->connection->exec($queryInsert);
    # $s = $this->connection->exec($queryLastID);
    $lastId = $this->connection->lastInsertId();
    return $lastId;
  }

  public function insertOrderProducts($orderId, $data){
    foreach ($data as $id => $quantity){
      $query = "INSERT INTO orders_products (id, orderId, productId, quantity, price, productType, totalPrice)
      values (DEFAULT, ?, ?, ?, ?, ?, ?)";
      $stmt = $this->connection->prepare($query);
      $orderId = intval(htmlspecialchars(strip_tags($orderId)));
      $id = intval(htmlspecialchars(strip_tags($id)));
      $quantity = intval(htmlspecialchars(strip_tags($quantity)));
      $price = $this->getProductPrice($id);
      $type =  $this->getProductType($id);
      $totalPrice = $price * $quantity;
      $stmt->bindParam(1, $orderId);
      $stmt->bindParam(2, $id);
      $stmt->bindParam(3, $quantity);
      $stmt->bindParam(4, $price);
      $stmt->bindParam(5, $type);
      $stmt->bindParam(6, $totalPrice);
      $stmt->execute();
    }
  }

  public function getProductPrice($id){
    // add error handling if not existing id is put in
    $query = "SELECT * FROM products WHERE id=?";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
    return ($price);
  }
  public function getProductType($id){
    // add error handling if not existing id is put in
    $query = "SELECT * FROM products WHERE id=?";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
    return ($productType);
  }
  // RANDOM note lol maybe in orders_products add also type of ordered item
  public function getOrderPrice($order){
    $total = 0;
    foreach ($order as $item){
      $total += $item['totalPrice'];
    }
    return ($total);
  }
  // this is for using when displaying certain orders containing some type product
  function typeExists($type, $products){
    foreach ($products as $product){
      $productID = $product['id'];
      $query = "SELECT * FROM products WHERE productType=? AND id=?";
      $stmt = $this->connection->prepare($query);
      $stmt->bindParam(1, $type);
      $stmt->bindParam(2, $productID);
      $stmt->execute();
      if ($stmt->rowCount() > 0){
        return (true);
      }
    }
    return (false);
  }
}
