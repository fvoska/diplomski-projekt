<?php
header('Content-Type: application/json');

$data = array();

$data['recordsTotal'] = 200; // Ukupan broj usera / broj redaka tablice USERS.
$data['recordsFiltered'] = 200; // Ukupan broj usera nakon filtriranja.

// Polje usera, svaki ima:
$data['data'] = array();

$user = array();

$user['id'] = '68da1748-259a-4a57-92d4-5f9b185e3cd7'; // ID
$user['first_appear'] = '2015-12-24'; // Datum "registracije"
$user['num_requests'] = 4; // Broj zahtjeva
$user['last_ip'] = '61.53.18.66'; // Zadnji korišteni IP
array_push($data['data'], $user);

$user['id'] = 'a5431b68-a8af-459d-b4b9-9bc7acb9065c';
$user['first_appear'] = '2016-01-01';
$user['num_requests'] = 14;
$user['last_ip'] = '161.53.18.66';
array_push($data['data'], $user);
usleep(500000);
echo json_encode($data);
?>
