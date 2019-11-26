<?php
// Class used to connect to mysql database
class Database{
  // Parameters for connection
  private $dbContainer = 'mysqlDB';
  private $user = 'root';
  private $password = 'root';
  private $db = 'printify-products';
  private $port = 3306;
  private $connection;
  // Connect to mysql
  public function connect(){
    $this->connection = null;
    try {
      $this->connection = new PDO("mysql:host=$this->dbContainer;
      port=$this->port;dbname=$this->db", $this->user, $this->password);
    } catch(PDOException $exception){
      echo "Error while connecting to database: $exception->getMessage()";
    }
    return $this->connection;
  }
}
