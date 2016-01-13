<?php
header('Content-Type: application/json');

$data = array();

$data['recordsTotal'] = 200;
$data['recordsFiltered'] = 200;
$data['userID'] = $_GET['id'];
/*
"draw": 1,
  "recordsTotal": 57,
  "recordsFiltered": 57
*/
$data['data'] = array();

$user = array();

$user['id'] = '68da1748-259a-4a57-92d4-5f9b185e3cd7';
$user['first_appear'] = '2015-12-24';
$user['num_requests'] = 4;
$user['last_ip'] = '61.53.18.66';
array_push($data['data'], $user);

$user['id'] = 'a5431b68-a8af-459d-b4b9-9bc7acb9065c';
$user['first_appear'] = '2016-01-01';
$user['num_requests'] = 14;
$user['last_ip'] = '161.53.18.66';
array_push($data['data'], $user);
sleep(1);
echo json_encode($data);
?>
