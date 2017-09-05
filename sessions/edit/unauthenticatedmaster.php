<?php

include('databaseHelper.php');
include('session.php');

$db = new dataBaseContext();

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>

<link rel="stylesheet" type="text/css" href="style.css" media="screen" />