<?php
/* Class to create order draft */
require_once("fpdf.php");
/* Function generating Printify invoice of the order */
function generateOrderDraft($id, $data){
  global $productUtils;
  global $orderUtils;
  $dateCreated = date('Y-m-d');
  $pdf = new FPDF();
  $orderPrice = 0;

  $pdf->AddPage();
  $pdf->SetFont('helvetica','B',16);
  $pdf->Image('/var/www/html/invoice-generator/printifyLogo.png',5,10,-350);
  $pdf->Cell(50,60, "Order ID : $id");
  $pdf->Cell(100,60, "Date created : $dateCreated", 0, 1);
  $queryOrderedProducts = $orderUtils->queryOrderProducts($id);
  $products = $queryOrderedProducts->fetchAll(PDO::FETCH_ASSOC);
  $productUtils->addInfoForOrderProducts($products);
  $orderUtils->addTotalPrice($products);
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
