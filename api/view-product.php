<?php

/*
** GET request
** View one product by it's id
** http://localhost/api/view-product.php?id=1
*/

/* This is public API with no token keys */
header('Access-Control-Allow-Origin: *');
/* Accept json for communication */
header('Content-Type: application/json');
/* Limits incoming traffic from a country */
require_once("../config/request-filter.php");
/* File containg class for connecting to mysql */
require_once("../config/Database.php");
/* File containing classes to manipulate products */
require_once("../utils/ProductUtils.php");

$database = new Database();
$connection = $database->connect();

$productUtils = new ProductUtils($connection);

/* If id is passed in url,
query this product and count if one row was returned */
if (isset($_GET['id']) && strlen($_GET['id'])){
  $response = $productUtils->queryProductById($_GET['id']);
  $count = $response->rowCount();
} else {
  echo json_encode(array("message" => "Please, set id of the product"));die;
}

/* fetch row containing information about the product if given ID is valid */
if ($count != 0){
  $row = $response->fetch(PDO::FETCH_ASSOC);
  extract($row);
  $product = array(
    "id" => $id,
    "price" => $price,
    "productType" => $productType,
    "color" => $color,
    "size" => $size,
    "created_at" => $created_at
  );
  echo json_encode($product);
} else {
  echo json_encode( array("message" => "No product found with given ID of {$_GET['id']}"));
}
