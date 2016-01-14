<?php
header('Content-Type: application/json');

$data = array();

// Ovdje treba za svaki zapis iz tablice ERROR_TYPE ispisati oznaku greške (mm, GG i ostalo) i broj koliko puta se taj tip pogreške pojavio (Grupirati tablicu ERRORS po tipu i onda napraviti SUM po stupcu numOccur).
$error = array();
$error['label'] = 'mm';
$error['value'] = 122;
$data['error_types'][] = $error;
$error['label'] = 'GG';
$error['value'] = 30;
$data['error_types'][] = $error;
$error['label'] = 'xx';
$error['value'] = 45;
$data['error_types'][] = $error;

$data['error_count'] = array();
$data['error_count']['num_errors'] = 6546841351; // Ukupan broj pogrešaka (broj redova tablice ERRORS)
$error_p = array();
$error_p['avg_word_count'] = 95; // Prosječna duljina zahtjeva (AVG stupca textLength u tablici REQUEST)
$error_p['avg_error_count'] = 5; // Prosječan broj grešaka po zahtjevu. Čini mi se da bi to bilo: JOINATI tablice REQUEST, REQUEST_ERROR i ERROR, grupirati po reqID i napraviti AVG za stupac numOccur.
$data['error_count']['error_percentage'] = $error_p;

// Ovdje jednostavno ispišemo 5 errora koji se najviše pojavljuju. Grupirati tablicu ERRORS po errorPhrase i onda napraviti SUM stupca numOccur. Sortirati po toj sumi i uzeti 5 najvećih.
$data['most_frequent'] = array();
$data['most_frequent'][] = 'čobjek';
$data['most_frequent'][] = 'ivan ivan';
$data['most_frequent'][] = 'programranje';
$data['most_frequent'][] = 'likvitnost';
$data['most_frequent'][] = 'gramica';

usleep(500000);
echo json_encode($data);
?>
