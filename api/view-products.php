<?php

/*
** GET request
** View all products
** http://localhost/api/view-products.php
*/

/* This is public API with no token keys */
header('Access-Control-Allow-Origin: *');
/* Accept json for communication */
header('Content-Type: application/json');
/* Limits incoming traffic from a country */
require_once("request-filter.php");
/* File containg class for connecting to mysql */
require_once("../config/Database.php");
/* File containing classes to manipulate products */
require_once("../utils/ProductUtils.php");

$database = new Database();
$connection = $database->connect();

$productUtils = new ProductUtils($connection);

$response = $productUtils->queryAllProducts();
$count = $response->rowCount();

/* If at least 1 product exists, fetch each row from
products table, turn it into an array and save in big array storing
arrays of all products */
if ($count > 0){
  $products = array();
  $products['data'] = array();
  while ($row = $response->fetch(PDO::FETCH_ASSOC)){
    extract($row);
    $product = array(
      "id" => $id,
      "price" => $price,
      "productType" => $productType,
      "color" => $color,
      "size" => $size,
      "created_at" => $created_at
    );
    array_push($products['data'], $product);
  }
  echo json_encode($products);
} else {
  echo json_encode(array("message" => "No products found"));
}
