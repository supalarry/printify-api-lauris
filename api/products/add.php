<?php
// This is public API with no token keys
header('Access-Control-Allow-Origin: *');
// Accept json for communication
header('Content-Type: application/json');
// Allow POST for communication
header('Access-Control-Allow-Methods: POST');
// Allowed header values
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods');

include_once("../../config/Database.php");
include_once("../../models/Product.php");

$database = new Database();
$db = $database->connect();

// Create product object
$product = new Product($db);

// Get posted data
$data = json_decode(file_get_contents("php://input"));

$product->setValues($data->price, $data->productType, $data->color, $data->size);
/*$product->price = $data->price;
$product->productType = $data->productType;
$product->color = $data->color;
$product->size = $data->size;*/

if ($product->createProduct()){
  echo json_encode(array("message" => "Product created"));
} else {
  echo json_encode(array("message" => "Product was not created"));
}
