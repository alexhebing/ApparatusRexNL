<?php


include('databaseHelper.php');
include('entities.php');

$db = new dataBaseContext();

print_r($db->listBoomfactors());


?>