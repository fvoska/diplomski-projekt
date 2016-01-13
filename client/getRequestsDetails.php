<?php
header('Content-Type: application/json');

$data = array();

$data['recordsTotal'] = 1;
$data['recordsFiltered'] = 1;
$data['requestID'] = $_GET['id'];
/*
"draw": 1,
  "recordsTotal": 57,
  "recordsFiltered": 57
*/
$data['data'] = array();

$error = array();

$error['id'] = '94513';
$error['suspicious'] = 'čovijek';
$error['type'] = 'Pravopisna';
$error['numOccur'] = '1';
array_push($data['data'], $error);
sleep(1);
echo json_encode($data);
?>
