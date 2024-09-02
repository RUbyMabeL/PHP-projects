<?php
require "functions.php";

if (isset($_POST['id'])) {
  deleteContact($_POST['id']);
}

header("Location: index.php");
