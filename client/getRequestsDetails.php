<?php
header('Content-Type: application/json');

$data = array();

$data['id'] = $_GET['id']; // ID
$data['user'] = '68da1748-259a-4a57-92d4-5f9b185e3cd7'; // KOji korisnik je poslao taj zahtjev
$data['time'] = '2015-12-25 17:00 GMT'; // Vrijeme zahtjeva
$data['processing_time'] = 1.24; // Vrijeme obrade
$data['word_count'] = 8; // Broj riječi u zahtjevu (textLength)
$data['num_errors'] = 2; // Isto ko i u getRequests.

usleep(500000);
echo json_encode($data);
?>
