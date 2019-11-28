<?php
/* set how many requests per minute from incoming country is allowed */
$requestsPerMinuteLimit = 5;
/* File containg class for connecting to mysql */
require_once('../config/Database.php');
/* create url for ipstack API call to find country code of visitor */
$ipStackKey = "2ebc12290f47bb5f0bc58b9a9bb7d9cb";
$ipUser =  $_SERVER['REMOTE_ADDR'];
//$ipUser = "134.201.250.155"; // this is test IP from US.
$ipStackUrl = "http://api.ipstack.com/" . $ipUser . "?access_key=" . $ipStackKey . "&fields=country_code";

/* create curl resource to make HTTP request */
$curl = curl_init();
/* set curl option */
curl_setopt($curl, CURLOPT_URL, $ipStackUrl);
/* instead of displaying html, save it in a variable */
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
/* run curl aka execute http request and save response in $countryCode */
$response = curl_exec($curl);
$responseDecoded = json_decode($response);
$countryCode = $responseDecoded->country_code;
/* if an error occured, default $countryCode is US */
if (strlen($countryCode) < 1)
  $countryCode = "US";

/* Find out how many $requests from $countryCode have been made */
$database = new Database();
$connection = $database->connect();
$query = "SELECT * FROM country_codes WHERE country = ?";
$stmt = $connection->prepare($query);
$stmt->bindParam(1, $countryCode);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
extract($row);

/* If $requests is less than $requestsPerMinuteLimit, allow traffic. Otherwise exit. */
if ($requests < $requestsPerMinuteLimit){
  $query = "UPDATE country_codes SET requests = requests + 1 WHERE country = ?";
  $stmt = $connection->prepare($query);
  $stmt->bindParam(1, $countryCode);
  $stmt->execute();
} else {
  echo json_encode(array("message" => "$requestsPerMinuteLimit requests allowed per minute from $countryCode"));
  die;
}
/* close curl resource */
curl_close($curl);
