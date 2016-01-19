<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8'); 

include("config.php");
include("controller.php");

$app=new Controller();

$app->start();

?>
