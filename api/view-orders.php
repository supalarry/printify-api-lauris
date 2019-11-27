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
/* File containg class for connecting to mysql */
include_once("../config/Database.php");
/* File containing classes to manipulate orders */
include_once("../utils/OrderUtils.php");
/* File containing classes to manipulate products */
include_once("../utils/ProductUtils.php");

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
  while ($row = $response->fetch(PDO::FETCH_ASSOC)){
    extract($row);
    /* here we have orderID from orders table. Use it to get that order's products. */
    $response = $orderUtils->queryOrderProducts($id);
    $products = $response->fetchAll(PDO::FETCH_ASSOC);
    /* If type has been specified and given order does not contain product
    of the type, continue to next order */
    if (isset($_GET['type']) && $orderUtils->productOfTypeIncluded($_GET['type'], $products)){
      continue;
    }
    //addInfoOrderProducts($products);
    foreach ($products as &$product){
      unset($product["id"]);
      $price = $productUtils->getProductPrice($item['productID']);
      array_push($product,
        array("productPrice" => $price),
        array("productType" => $productUtils->getProductType($item['productID'])),
        array("totalPrice" => $product['quantity'] * $price)
      );
    }
    // put all products in array
    $orderId = $products[0]['orderID'];
    $orderPrice = $product->getOrderPrice($products);
    array_push($products, array("total order $orderId price" => "$orderPrice"));
    array_push($orders['data'], $products);
  }
  echo json_encode($orders);
} else {
  echo json_encode(array("message" => "No orders found"));
}

//function addInfoOrderProducts($products)
