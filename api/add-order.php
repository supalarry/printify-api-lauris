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
/* Limits incoming traffic from a country */
require_once("request-filter.php");
/* File containg class for connecting to mysql */
require_once("../config/Database.php");
/* File containing classes to manipulate products */
require_once("../utils/ProductUtils.php");
/* File containing classes to manipulate orders */
require_once("../utils/OrderUtils.php");
/* Class to create order draft */
require_once("../fpdf-library/fpdf.php");

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
  echo json_encode(array("message" => "Order $id has been created and draft saved."));
  generateOrderDraft($id, $data);
} else {
  echo json_encode(array("message" => "Order needs to have value of at least 10 euros"));
  die;
}

function generateOrderDraft($id, $data){
  global $orderUtils;
  $dateCreated = date('Y-m-d');
  $pdf = new FPDF();
  $orderPrice = 0;

  $pdf->AddPage();
  $pdf->SetFont('helvetica','B',16);
  $pdf->Image('../fpdf-library/printifyLogo.png',5,10,-350);
  $pdf->Cell(50,60, "Order ID : $id");
  $pdf->Cell(100,60, "Date created : $dateCreated", 0, 1);
  $queryOrderedProducts = $orderUtils->queryOrderProducts($id);
  $products = $queryOrderedProducts->fetchAll(PDO::FETCH_ASSOC);
  addInfoForOrderProducts($products);
  addTotalPrice($products);
  $pdf->SetFont('helvetica','B',12);
  $pdf->Cell(28,-40, "Product ID");
  $pdf->Cell(28,-40, "Type");
  $pdf->Cell(28,-40, "Color");
  $pdf->Cell(28,-40, "Size");
  $pdf->Cell(28,-40, "Price");
  $pdf->Cell(28,-40, "Quantity");
  $pdf->Cell(28,-40, "Total price", 0, 1);
  $pdf->Cell(28, 25, "", 0, 1);
  $pdf->SetFont('helvetica','B', 10);
  $pdf->SetFont('');
  foreach ($products as $product){
    $productId = $product['productID'];
    $productType = $product['productType'];
    $productColor = $product['productColor'];
    $productSize = $product['productSize'];
    $productPrice = $product['productPrice'];
    $productQuantity = $product['quantity'];
    $totalPrice = $product['totalPrice'];
    $orderPrice += $totalPrice;
    $pdf->Cell(28, 5, "$productId");
    $pdf->Cell(28, 5, "$productType");
    $pdf->Cell(28, 5, "$productColor");
    $pdf->Cell(28, 5, "$productSize");
    $pdf->Cell(28, 5, "$productPrice");
    $pdf->Cell(28, 5, "$productQuantity");
    $pdf->Cell(28, 5, "$totalPrice", 0, 1);
  }
  $pdf->SetFont('helvetica','B',12);
  $pdf->Cell(40, 5, "Total order price $orderPrice $", 0, 1);
  $pdf->SetFont('helvetica','B', 10);
  $pdf->SetFont('');
  $pdf->MultiCell(80, 5, "https://printify.com/\nmerchantsupport@printify.com");
  $filename = $dateCreated . "-ID-" . $id . ".pdf";
  $pdf->Output("../order-drafts/$filename", 'F');
}

/* For each order's products add their price, type, and total price within an
order for display purposes */
function addInfoForOrderProducts(&$products){
  global $productUtils;

  foreach ($products as &$product){
    unset($product["id"]);
    $price = $productUtils->getProductPrice($product['productID']);
    $product['productPrice'] = $price;
    $product['productType'] = $productUtils->getProductType($product['productID']);
    $product['productColor'] = $productUtils->getProductColor($product['productID']);
    $product['productSize'] = $productUtils->getProductSize($product['productID']);
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
