<?php

class OrderUtils {
  private $connection;
  /* Create object with connection to mysql */
  public function __construct($connection){
    $this->connection = $connection;
  }
  /* query for all orders */
  public function queryAllOrders(){
    $query = "SELECT * FROM orders";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }
  /* select all items from a specfic order by it's ID */
  public function queryOrderProducts($id){
    $query = "SELECT * FROM orders_products WHERE orderID=?";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    return $stmt;
  }
  /* Open a new order by adding a new ID to orders table */
  public function openOrder(){
    $queryInsert = "INSERT INTO orders (id) values (DEFAULT)";
    $queryLastID = "SELECT LAST_INSERT_ID()";
    $this->connection->exec($queryInsert);
  }
  /* After opening a new order, get it's id */
  public function lastOrderId(){
    $lastId = $this->connection->lastInsertId();
    return $lastId;
  }
  /* Given order id and products, insert products into order */
  public function insertProducts($orderId, $data){
    foreach ($data as $productId => $quantity){
      $query = "INSERT INTO orders_products (id, orderId, productId, quantity)
      values (DEFAULT, ?, ?, ?)";
      $stmt = $this->connection->prepare($query);
      $orderId = intval(htmlspecialchars(strip_tags($orderId)));
      $id = intval(htmlspecialchars(strip_tags($productId)));
      $quantity = intval(htmlspecialchars(strip_tags($quantity)));
      $stmt->bindParam(1, $orderId);
      $stmt->bindParam(2, $productId);
      $stmt->bindParam(3, $quantity);
      $stmt->execute();
    }
  }
  /* Get price of an order based on products' prices */
  public function getOrderPrice($order){
    $total = 0;
    foreach ($order as $item){
      $total += $item['totalPrice'];
    }
    return ($total);
  }
  /* indicate if order includes a product of some type */
  function productOfTypeIncluded($type, $products){
    foreach ($products as $product){
      $productID = $product['productID'];
      $query = "SELECT * FROM products WHERE productType=? AND id=?";
      $stmt = $this->connection->prepare($query);
      $stmt->bindParam(1, $type);
      $stmt->bindParam(2, $productID);
      $stmt->execute();
      if ($stmt->rowCount() > 0)
        return (true);
    }
    return (false);
  }
  /* Calculate total price of an order and save it in order's own array */
  function addTotalPrice(&$products){
    $orderId = $products[0]['orderID'];
    $orderPrice = $this->getOrderPrice($products);
    array_push($products, array("total order $orderId price" => "$orderPrice"));
  }
}
