<?php
header('Content-Type: application/json');

$user = array();

// Za usera s ID == $_GET['id'] dohvaćamo:

$user['id'] = $_GET['id'];
$user['first_appear'] = '2015-12-24';

$user['ip_history'] = array(); // povijest svih IP adresa.
$user['ip_history'][] = '61.53.18.66';
$user['ip_history'][] = '61.53.18.67';
$user['ip_history'][] = '61.53.18.68';
$user['ip_history'][] = '61.53.18.69';

// Koje tipove pogrešaka ima u svojim zahtjevima. JOINANJE par tablica, grupiranje po tipu pogreške i onda sumiranje numOccur.
$user['error_stats'] = array();
$error = array();
$error['label'] = 'mm';
$error['value'] = 12;
$user['error_stats'][] = $error;
$error['label'] = 'GG';
$error['value'] = 3;
$user['error_stats'][] = $error;
$error['label'] = 'xx';
$error['value'] = 4;
$user['error_stats'][] = $error;

// Statistika korišenja unazad 6 mjeseci - koliko svaki mjesec ima poslano zahtjeva.
$user['usage_stats'] = array();
$user['usage_stats']['monthly'] = array();
$year = array();
$month['month'] = '2016-01';
$month['requests'] = 125;
$user['usage_stats']['monthly'][] = $month;
$month['month'] = '2015-12';
$month['requests'] = 123;
$user['usage_stats']['monthly'][] = $month;
$month['month'] = '2015-11';
$month['requests'] = 42;
$user['usage_stats']['monthly'][] = $month;
$month['month'] = '2015-10';
$month['requests'] = 54;
$user['usage_stats']['monthly'][] = $month;

$user['request_stats'] = array();
$user['request_stats']['num_requests'] = 4;// Ukupan broj zahtjeva
$error_p = array();
$error_p['avg_word_count'] = 123; // Prosječni broj riječi u njegovim zahtjevima
$error_p['avg_error_count'] = 12; // Prosječni broj pogrešaka u njegovim zahtjevima.
$user['request_stats']['error_percentage'] = $error_p;

usleep(500000);
echo json_encode($user);
?>
