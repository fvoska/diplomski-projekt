<?php
header('Content-Type: application/json');

$data = array();

$data['recordsTotal'] = 200; // Ukupan broj zahtjeva (broj redaka tablice).
$data['recordsFiltered'] = 200; // Broj zahtjeva koji se vraćaju nakon filtriranja.

// data je lista zahtjeva, od kojih svaki ima:
$data['data'] = array();

$request = array();

$request['id'] = '3452'; // ID
$request['time'] = '2015-12-26 07:59 GMT'; // Vrijeme kada je zaprimljen.
$request['processing'] = '2.01s'; // Vrijeme obrade
$request['numErrors'] = '4'; // Broj grešaka u njemu (JOINati ERROR i REQUEST_ERROR, grupirati po reqID i napraviti SUM po numOccur)
array_push($data['data'], $request);

$request['id'] = '3455';
$request['time'] = '2016-01-01 16:20 GMT';
$request['processing'] = '1.45s';
$request['numErrors'] = '1';
array_push($data['data'], $request);

usleep(500000);
echo json_encode($data);
?>
