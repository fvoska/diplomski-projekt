<?php
header('Content-Type: application/json');

$user = array();

$user['id'] = $_GET['id'];
$user['first_appear'] = '2015-12-24';
$user['num_requests'] = 4;
$user['ip_history'] = array();
$user['ip_history'][] = '61.53.18.66';
$user['ip_history'][] = '61.53.18.67';
$user['ip_history'][] = '61.53.18.68';
$user['ip_history'][] = '61.53.18.69';

sleep(1);
echo json_encode($user);
?>
