<?php
header('Content-Type: application/json');

$data = array();

$data['recordsTotal'] = 2; // Broj zahtjeva korisnika s ID == $_GET['id']
$data['recordsFiltered'] = 2; // Broj njegovih zahtjeva nakon filtriranja.
$data['userID'] = $_GET['id']; // Vraćamo ID

// Polje njegovih zahtjeva, svaki ima:
$data['data'] = array();

$request = array();

$request['id'] = '3452'; // ID zahtjeva
$request['time'] = '2015-12-26 07:59 GMT'; // Vrijeme zahtjeva
$request['processing'] = '2.01s'; // Vrijeme obrade
$request['numErrors'] = '4'; // Broj grešaka
array_push($data['data'], $request);

$request['id'] = '3455';
$request['time'] = '2016-01-01 16:20 GMT';
$request['processing'] = '1.45s';
$request['numErrors'] = '1';
array_push($data['data'], $request);
usleep(500000);
echo json_encode($data);
?>
