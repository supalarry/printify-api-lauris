<?php

/*
** POST request
** Add a product
** http://localhost/api/add-product.php
**
** {
** 	"price": 10,
** 	"productType": "socks",
** 	"color": "yellow",
** 	"size": "L"
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
/* File containing classes to manipulate products*/
require_once("../utils/ProductUtils.php");

$database = new Database();
$connection = $database->connect();

$productUtils = new ProductUtils($connection);

/* Get posted data */
$data = json_decode(file_get_contents("php://input"));

/* Save posted values in productUtils object, to later create
a query to submit an entry to mysql */

$productUtils->createProduct($data->price, $data->productType, $data->color, $data->size);
echo json_encode(array("message" => "Product created"));
