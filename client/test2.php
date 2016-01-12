<?php
header('Content-Type: application/json');

$data = array();

$data['recordsTotal'] = 2;
$data['recordsFiltered'] = 2;
/*
"draw": 1,
  "recordsTotal": 57,
  "recordsFiltered": 57
*/
$data['data'] = array();

$request = array();

$request['id'] = '3452';
$request['time'] = '2015-12-26 07:59 GMT';
$request['processing'] = '2.01s';
$request['numErrors'] = '4';
array_push($data['data'], $request);

$request['id'] = '3455';
$request['time'] = '2016-01-01 16:20 GMT';
$request['processing'] = '1.45s';
$request['numErrors'] = '1';
array_push($data['data'], $request);

echo json_encode($data);
?>
