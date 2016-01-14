<?php
header('Content-Type: application/json');

$data = array();

$data['id'] = $_GET['id'];
$data['user'] = '68da1748-259a-4a57-92d4-5f9b185e3cd7';
$data['time'] = '2015-12-25 17:00 GMT';
$data['processing_time'] = 1.24;
$data['num_errors'] = 2;

usleep(500000);
echo json_encode($data);
?>
