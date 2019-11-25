<?php
$dbContainer = 'mysqlDB';
$user = 'lauris-printify';
$password = '123qweasdzxc';
$db = 'products';
$port = 3306;

$conn = new PDO("mysql:host=$dbContainer;port=$port;dbname=$db", $user, $password);

?>
<!DOCTYPE html>
<html>
<body>

<h1>My First Heading</h1>
<p>My first paragraph.</p>

</body>
</html>
