<?php

include('session.php');
include('databaseHelper.php');
include('entities.php');

//session_start();

if (!isset($_SESSION['userName']))
{  
  header("Location: login.php");
  die;

	//echo 'not defined';
}
else
{
  //echo 'hoi';
}

// function test_input($data) {
//   $data = trim($data);
//   $data = stripslashes($data);
//   $data = htmlspecialchars($data);
//   return $data;
// }

$db = new dataBaseContext();







//$db->deleteEntry($entry);

//$entry->Pad = 'update4';
//$db->updateEntry($entry);

//$db->listEntries();

//$db->Close();

//$db->setLogin('testR', 'mijntest');



?>

<link rel="stylesheet" type="text/css" href="style.css" media="screen" />