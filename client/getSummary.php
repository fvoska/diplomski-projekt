<?php
header('Content-Type: application/json');

$data = array();

$data['count'] = array();
$data['count']['users'] = 1215;
$data['count']['requests'] = 3216874;
$data['count']['errors'] = 6546841351;

usleep(500000);
echo json_encode($data);
?>
