<?php
header('Content-Type: application/json');

$data = array();

$data['suspicious'] = $_GET['group']; // Sumnjava riječ / greška
$data['num_occur'] = 354; // Broj pojavljivanja greške, isto kao u getErrors (SUM stupca numOccur u tablici ERRORS)
$data['num_occur_req'] = 255; // Broj zahtjeva u kojima se pojavljuje - nije isto kao i num_occur jer se u jednom zahtjevu greška može pojaviti više puta. Ovdje treba pobrojati zahtjeve u kojima se nalazi.
$data['req_count'] = 13548; // Ukupan broj zahtjeva (COUNT broj redataka REQUEST tablice), treba za ispis nekog postotka.
$data['type'] = 'mm'; // Tip pogreške.

usleep(500000);
echo json_encode($data);
?>
