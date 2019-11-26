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

// product query
$result = $product->getOrders();
$num = $result->rowCount();

// check if any products
if ($num > 0){
  // products array
  $products = array();
  $products['data'] = array();
  while ($row = $result->fetch(PDO::FETCH_ASSOC)){
    // fetch each row as associative array
    extract($row);
    $post_item = array(
      "id" => $id,
      "price" => $price,
      "productType" => $productType,
      "color" => $color,
      "size" => $size,
      "created_at" => $created_at
    );
    // put all products in array
    array_push($products['data'], $post_item);
  }
  // turn associative array into json
  echo json_encode($products);
} else {
  // no products
  echo json_encode(
    array("message" => "No products found")
  );
}
