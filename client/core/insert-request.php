<?php
include("/var/www/hacheck.tel.fer.hr/grupa84/app/core/config.php");
include(APP_PATH."/core/controller.php");

$app=new Controller();
$app->insertHascheckRequest($argv);
?>
