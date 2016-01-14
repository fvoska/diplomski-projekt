<?php
header('Content-Type: application/json');

$data = array();

$data['recordsTotal'] = 2; // Broj grešaka unutar zahtjeva s ID == $_GET['id']
$data['recordsFiltered'] = 2; // Broj nakon filtriranja.
$data['requestID'] = $_GET['id']; // ID primljen preko parametra.

// Polje grešaka, sve stvari iz tablice
$data['data'] = array();

$error = array();

$error['id'] = '94513';
$error['suspicious'] = 'čovijek';
$error['type'] = 'mm';
$error['numOccur'] = '2'; // ukupan broj pojavljivanja (SUM stupca numOccur u tablici ERRORS)
array_push($data['data'], $error);

$error['id'] = '94514';
$error['suspicious'] = 'ivan ivan';
$error['type'] = 'GG';
$error['numOccur'] = '1';
array_push($data['data'], $error);

usleep(500000);
echo json_encode($data);
?>
