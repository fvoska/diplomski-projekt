<?php
header('Content-Type: application/json');

$data = array();

// Ista greška se može primiti od različitih korisnika u različitim zahtjevima.
// Ovdje želimo sve to grupirati, dakle prije svega napraviti GROUP tavlice ERRORS po stupcu errorPhrase.

$data['recordsTotal'] = 2; // Ukupan broj različitih errorPhrase-a (broj redaka u tablici ERRORS NAKON GRUPIRANJA!!! to je onda valjda COUNT_UNIQUE(errorPhrase))
$data['recordsFiltered'] = 2; // Ukupan broj errora nakon filtriranja.

$data['data'] = array(); // Polje errora.

$error = array();

// Svaki error ima sljedeće:
// Uočimo da nema ID jer više različitih ID-ova može imati isti errorPhrase, a tu nas samo errorPhrase-i zanimaju.
$error['suspicious'] = 'čovijek'; // riječ koja je kriva
$error['type'] = 'mm'; // tip greške
$error['totalNumOccur'] = '25'; // ukupan broj pojavljivanja (SUM stupca numOccur u tablici ERRORS)
array_push($data['data'], $error);

$error['suspicious'] = 'ivan ivan';
$error['type'] = 'GG';
$error['totalNumOccur'] = '153';
array_push($data['data'], $error);

$error['suspicious'] = 'ljepo';
$error['type'] = 'mm';
$error['totalNumOccur'] = '2';
array_push($data['data'], $error);

usleep(500000);
echo json_encode($data);
?>
