<?php
/*
** POST request
** Add an order
** http://localhost/api/add-order.php
**
** {
** 	"1" (product ID) : "2" (quantity),
** 	"2" (product ID) : "2" (quantity)
** }
*/

/* This is public API with no token keys */
header('Access-Control-Allow-Origin: *');
/* Accept json for communication */
header('Content-Type: application/json');
/* Allow POST request */
header('Access-Control-Allow-Methods: POST');
/* Allow specific headers */
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods');
/* File containg class for connecting to mysql */
require_once("../config/Database.php");
/* File containing classes to manipulate products */
require_once("../utils/ProductUtils.php");
/* File containing classes to manipulate orders */
require_once("../utils/OrderUtils.php");

$database = new Database();
$connection = $database->connect();

$orderUtils = new OrderUtils($connection);
$productUtils = new ProductUtils($connection);

/* Get posted data */
$data = json_decode(file_get_contents("php://input"));

/* validate that the order has total price of at least 10 */
$totalPrice = 0;

foreach ($data as $productId => $quantity){
  $productPrice = $productUtils->getProductPrice($productId);
  $totalPrice += $productPrice * $quantity;
}
if ($totalPrice > 9){
  $orderUtils->openOrder();
  $id = $orderUtils->lastOrderId();
  $orderUtils->insertProducts($id, $data);
  echo json_encode(array("message" => "Order $id has been created"));
} else {
  echo json_encode(array("message" => "Order needs to have value of at least 10 euros"));
}
