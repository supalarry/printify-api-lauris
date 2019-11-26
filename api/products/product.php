<?php
// This is public API with no token keys
header('Access-Control-Allow-Origin: *');
// Accept json for communication
header('Content-Type: application/json');

include_once("../../config/Database.php");
include_once("../../models/Product.php");

$database = new Database();
$db = $database->connect();

// Create product object
$product = new Product($db);

// product query
$result = $product->getProduct();
$num = $result->rowCount();

if ($num > 0){
  // product array
  $product = array();
  $product['data'] = array();
  $row = $result->fetch(PDO::FETCH_ASSOC);
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
  // put product in array
  array_push($product['data'], $post_item);
  // turn associative array into json
  echo json_encode($product);
} else {
  // no products
  echo json_encode(
    array("message" => "No product found")
  );
}
