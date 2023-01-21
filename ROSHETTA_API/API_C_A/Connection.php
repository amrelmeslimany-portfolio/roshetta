<?php

$dsn      = 'mysql:host=localhost;dbname=roshetta'; //Data Source Name
$user     = 'root'; //The User To Connect
$password = ''; //Password Of This User
$options  = array(
  PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', //To Support Arabic Letters
);

try { //try && catch
  $database = new PDO($dsn, $user, $password, $options); //Start A New Connection With PDO Class
  $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo 'you are connected';     //Message Succses Connection

} catch (PDOException $e) {
  echo 'Failed' . $e->getMessage(); //Message Failed Connection
}
?>