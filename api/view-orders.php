<?php
/*
** GET request
** Viewing orders
** http://localhost/api/view-orders.php
**
** Viewing orders that contain item of a specific type of product
** http://localhost/api/view-orders.php?type=socks
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
/* File containing classes to manipulate orders */
require_once("../utils/OrderUtils.php");

$database = new Database();
$connection = $database->connect();

$orderUtils = new OrderUtils($connection);
$productUtils = new ProductUtils($connection);

$response = $orderUtils->queryAllOrders();
$count = $response->rowCount();

/* check if any orders exist */
if ($count > 0){
  $orders = array();
  $orders['data'] = array();
  /* fetch order IDs */
  while ($row = $response->fetch(PDO::FETCH_ASSOC)){
    extract($row);
    /* fetch products and their quantities for each order */
    $responseProducts = $orderUtils->queryOrderProducts($id);
    $products = $responseProducts->fetchAll(PDO::FETCH_ASSOC);
    /* If type has been specified and given order does not contain product
    of the type, continue to next order */
    if (isset($_GET['type']) && !$orderUtils->productOfTypeIncluded($_GET['type'], $products)){
      continue;
    }
    // put these two functions in order utils and remove from add order
    addInfoForOrderProducts($products);
    addTotalPrice($products);
    array_push($orders['data'], $products);
  }
  echo json_encode($orders);
} else {
  echo json_encode(array("message" => "No orders found"));
}

/* For each order's products add their price, type, and total price within an
order for display purposes */
function addInfoForOrderProducts(&$products){
  global $productUtils;

  foreach ($products as &$product){
    unset($product["id"]);
    $price = $productUtils->getProductPrice($product['productID']);
    $product['productPrice'] = $price;
    $product['prodctType'] = $productUtils->getProductType($product['productID']);
    $product['totalPrice'] = $product['quantity'] * $price;
  }
}

/* Calculate total price of an order and save it in order's own array */
function addTotalPrice(&$products){
  global $orderUtils;

  $orderId = $products[0]['orderID'];
  $orderPrice = $orderUtils->getOrderPrice($products);
  array_push($products, array("total order $orderId price" => "$orderPrice"));
}
