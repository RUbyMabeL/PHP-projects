<?php
$dsn = "mysql:host=localhost;dbname=contact_book";
$username = "root";
$password = "970426";

try {
  $db = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
