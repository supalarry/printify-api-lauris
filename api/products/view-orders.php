<?php
// This is public API with no token keys
header('Access-Control-Allow-Origin: *');
// Accept json for communication
header('Content-Type: application/json');

include_once("../../config/Database.php");
include_once("../../models/Product.php");

// Create database model and connect to it
$database = new Database();
$db = $database->connect();

// Create product object
$product = new Product($db);

$result = $product->listOrders();
$num = $result->rowCount();


// check if any products
if ($num > 0){
  // here we get id of each order and then go through products_orders array
  if (!isset($_GET['type'])){
    $orders = array();
    $orders['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)){
      // fetch each row as associative array
      extract($row);
      // here we have orderID from orders table
      $products = $product->listOrderProducts($id);
      $products = $products->fetchAll(PDO::FETCH_ASSOC);
      foreach ($products as &$item){
        unset($item["id"]);
      }
      // put all products in array
      $orderIDD = $products[0]['orderID'];
      $orderPrice = $product->getOrderPrice($products);
      array_push($products, array("total order $orderIDD price" => "$orderPrice"));
      array_push($orders['data'], $products);
    }
  } else {
    // here we also need to check whether or not such type is valid maybe in the first place
    // this is if type is set to filter out orders containing specific type of item
    $orders = array();
    $orders['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)){
      // fetch each row as associative array
      extract($row);
      // here we have orderID from orders table
      $products = $product->listOrderProducts($id);
      $products = $products->fetchAll(PDO::FETCH_ASSOC);
      if (!$product->typeExists($_GET['type'], $products)){
        continue;
      }
      foreach ($products as &$item){
        unset($item["id"]);
      }
      // put all products in array
      $orderIDD = $products[0]['orderID'];
      $orderPrice = $product->getOrderPrice($products);
      array_push($products, array("total order $orderIDD price" => "$orderPrice"));
      array_push($orders['data'], $products);
    }
  }
  // turn associative array into json
  echo json_encode($orders);
} else {
  // no products
  echo json_encode(
    array("message" => "No orders found")
  );
}
