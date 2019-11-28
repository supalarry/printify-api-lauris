<?php

/* create url for ipstack API call to find country code of visitor */
$ipStackKey = "2ebc12290f47bb5f0bc58b9a9bb7d9cb";
//$ipUser =  $_SERVER['REMOTE_ADDR'];
$ipUser = "134.201.250.155";
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

/*
I could already setup a table beforehand with all the country codes, so there
is no need to insert etc., just increase or decrease counter.

Now we need to setup mysql for country code and increase counter field by 1.
If the counter is already X, then do not allow the request.
Finally, we need to have a mechanism to check when was first request made,
and if it is X amount of seconds, then clear the counter.


check mysql events to essentially set requests for all countries to 0 every x seconds
set up table with all country codes with requests of 0 by default
when app starts send a query to specify refresh time for database/ THIS SET MANUALLY IF YOU WANT
if a request is made and limit is hit, do not allow service/maybe timeout is possible until refresh happens

so if we allow 50 requests per 10 seconds, every 10 seconds requests go to 0 and if 51st one
arrives then 50 is already there and we do not serve it.
*/

/* close curl resource */
curl_close($curl);
