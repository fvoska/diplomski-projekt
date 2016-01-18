<?php
header('Access-Control-Allow-Origin: *');

include("config.php");
include("controller.php");

$app=new Controller();

$app->start();

?>
