<?php

include_once $_SERVER['DOCUMENT_ROOT'] ."/api/config/Database.php";
$database = new Database();

$db = $database->getMYSQLI();
if($db)
echo 'connected';
else
echo 'not connected';

?>