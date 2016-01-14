<?php
header('Content-Type: application/json');

$data = array();

$data['recordsTotal'] = 2;
$data['recordsFiltered'] = 2;

$data['data'] = array();

$error = array();

$error['suspicious'] = 'čovijek';
$error['type'] = 'mm';
$error['totalNumOccur'] = '25';
array_push($data['data'], $error);

$error['suspicious'] = 'ivan ivan';
$error['type'] = 'GG';
$error['totalNumOccur'] = '153';
array_push($data['data'], $error);

$error['suspicious'] = 'ljepo';
$error['type'] = 'mm';
$error['totalNumOccur'] = '2';
array_push($data['data'], $error);

usleep(500000);
echo json_encode($data);
?>
