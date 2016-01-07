# 1. Generalno

## 1.1. Skripta za ispravljanje

U skripti za ispravljanje pokupiti sve što trebamo pratiti:
* Informacije o korisniku:
    * IP (eksterni)
    * User ID iz cookieja - HascheckUserID
    * Session ID iz cookieja - HasekSID
* Informacije o zahtjevu:
    * Datum i vrijeme zahtjeva
    * Tekst poslan na ispravljanje
    * Context on|off
    * Izvještaj o svakoj pogrešci:
        * Sumnjiva riječ (ona koja je kriva)
        * Vrsta pogreške (razne oznake)
        * Broj pojavljivanja pogreške
        * Predložene ispravke
        * Prihvaćena ispravka - zahtjeva hookanje u frontend ispravljača

## 1.2. Obrada zahtjeva

Kada primimo zahtjev za obradu teksta:
1. Ako je ovo prvi puta da se javio userID:
    * Stvori novog usera
2. Ako je ovo prvi puta da se javio sessionID:
    * Stvori novi session (s novim ili već postojećim userID)
3. Ako već postoji session s primljenim ID-om, ali je IP različit:
    * Dodaj novi zapis u SESSION_IP s sessionID i novim IP-om i zapiši novi IP u lastIP (za brzi dohvat zadnjeg IP-a da ne trebamo stalno JOIN-ati SESSION i SESSION_IP ako nas samo zanima zadnji IP).
4. Stvori novi zapis u REQUEST:
    * Zapisati sessionID
    * Spremiti tekst koji je poslan na ispravljanje u requestText
        * Dodati mogućnost korisniku da se NE sprema (radi privatnosti), npr. preko parametra skripti za ispravljanje slično kao i kontekst.
    * Spremiti vremena timeRequested i timeProcessed
    * Za svaku grešku stvoriti novi zapis u ERROR:
        * errorID je AUTONUMBER
        * errorTypeID zapišemo odmah (-gg- i ostali)
        * errorPhrase je riječ (ili više njih ako je npr. neka kontekstualna, ali to nam niti nije bitno, spremamo ko string)
            * errorPhrase može npr. biti "rjeka" (ije/je pogreška) ili "auto auto" (kontekstulna pogreška - ponavljanje)
        * numOccur je koliko puta se errorPhrase pojavljuje u requestedText - ne treba računati, to već piše kad se vrati rezultat ispravljanja iz skripte
        * correctedTo ako od frontenda možemo dobiti feedback na što je korisnik errorPhrase ispravio, onda spremimo tu ispravku
        * Povezati novostvoreni ERROR zapis sa zapisom u REQUEST preko REQUEST_ERROR

# 2. Baza podataka
Prikupljene stvari spremiti u bazu podataka.

## 2.1. Korisnik
```
USER = (userID, timeAppeared)
K = (userID)

Tipovi podataka:
userID - (HEX) TEXT - vrijednost HascheckUserID (primjer userID-a: 75b14409-c90d-475e-b865-dd689738a683).
timeAppeared - DATETIME - kada se pojavi novi ID kojeg još nemamo u bazi, dodaje se novi korisnik s tim ID-om. I onda naravno to vrijeme zapišemo.
```

## 2.2. Session
```
SESSION = (sessionID, userID, lastIP)
K = (sessionID)

Tipovi podataka:
sessionID - (HEX) TEXT- vrijednost HasekSID (primer sessionID-a: vjerojatno isto kao i za userID).
userID - strani ključ referencira USER.userID
lastIP - TEXT

Kada se pojavi novi sessionID kojeg još nemamo u bazu, dodaje se novi session s tim ID-om. Pridružuje mu se korisnik i njegov IP.
```

## 2.3. Session IP
```
SESSION_IP = (sessionID, IP)
K = (sessionID, IP)

Tipovi podataka:
IP - TEXT
sessionID - strani ključ refenrencira SESSION.sessionID
```

## 2.4. Zahtjev
```
REQUEST = (reqID, sessionID, requestText, timeRequested, timeProcessed)
K = (reqID)

Tipovi podataka:
reqID - INT AUTONUMBER
requestText - TEXT
sessionID - strani ključ refenrencira SESSION.sessionID
timeRequested - DATETIME
timeProcessed - DATETIME
```

## 2.5. Tip pogreške
```
ERROR_TYPE = (errorTypeID, errorTypeDesc)
K = (errorTypeID)

Ovo popunimo ručno i imamo za stalno.

Tipovi podataka:
errorTypeID - TEXT - primjeri mogućih vrijednosti: -kk-, -gg-, itd. Označavaju gramatičke, pravopisne, itd. - treba vidjeti točno koji kod je koji tip, da možemo opise ispravno popuniti.
errorTypeDecs - TEXT
```

## 2.6. Pogreške
```
ERROR = (errorID, errorTypeID, errorPhrase, numOccur, correctedTo)
K = (errorID)

Tipovi podataka:
errorID - INT AUTONUMBER
errorTypeID - strani ključ refenrencira ERROR_TYPE.errorTypeID
errorPhrase - TEXT
numOccur - INT
correctedTo - TEXT
```

## 2.7. Pogreške zahtjeva
```
REQUEST_ERROR = (errorID, reqID)
K = (errorID, reqID)

errorID - strani ključ referencira ERROR.errorID
reqID - strani ključ referencira REQUEST.reqID
```

# 3. Povlačenje iz baze

Ovo bi mogli napraviti jednostavne PHP skripte koje izvršavaju SQL nad bazom i vraćaju rezultate u JSON-u (encode_json nad asocijativnim poljima).

Te skripte bi trebale nuditi i neke izračune statistike. Sortiranje najbolje u SQL-u napraviti.

Ako imamo stvarno puno podataka, bilo bi zgodno uvesti paginaciju u PHP skripte tako da sa frontenda ne moramo dohvaćati sve, već samo npr. zahtjeve 51-100, 101-150 i slično (ajax upit na zahtjevi_za_ispravljanje.php?from=51&length=50).

Još eventualno dodati cachiranje na tipa 5-10 minuta. Pogledati što tu ima za PHP (APC, memcached, redis, in-memory/RAM disk itd.)

Kratki primjer kako vući stvari iz MySQL-a preko PHP-a:

Ovdje je samo kod za rad s bazom - model.php:
```
class Dohvati {
    public $db = null;

    public function __construct(){
        $this->get_db();
    }

    private function get_db(){
        if(!is_object($this->db)){
            $this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
            $this->db->exec('SET NAMES utf8');
        }
        return $this->db;
    }

    public function get_error($id) {
        $data = array();
        $stmt = null;
        $query = '
            SELECT *
            FROM ERROR
            WHERE errorID = :id
        ';
        $parameters[':id'] = $id;
        $stmt = $this->db->prepare($query);
        $stmt->execute($parameters);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        if(count($rows)>0){
            // Naravno ako je samo jedan s errorID == id (a tko bi i trebalo biti), count($rows) == 1 i donji foreach ide samo jednom.
            foreach($rows as $row){
                $tmp = array();
                $tmp['id'] = $row['errorID'];
                $tmp['type'] = $this->get_error_type_name($row['errorTypeID']); // trebalo bi implementirati još naravno i ovu funkciju koja za errorTypeID vraća opis (TEXT, string, štagod).
                $tmp['phrase'] = $row['errorPhrase'];
                $tmp['occurrences'] = $row['numOccur'];
                $tmp['corrected'] = $row['correctedTo'];
                $data[] = $tmp;
            }
        }
        return $data;
        // return $data[0] // možda i ovako za ono što smo sigurni da vraća samo jednu stvar.
    }
}
```

Ovdje je skripta kojoj pristupamo preko ajax-a - error.php?error_id=xxx:
```
$model = new Dohvati();
$error = $model->get_error($_GET['error_id']);
echo json_encode($error);
```

# 4. Frontend

Na fronendu imamo Angular koji možemo bindati na AJAX zahtjeve. Nije nužno da se svi proračuni statistike izvravaju u PHP skriptama, neke manje proračune možemo i ovdje raditi.

Trebamo se dogovoriti točno u kojem formatu se rezultati statističkih proračuna vraćaju iz PHP skripta. Onda sve to prikazujemo u lijepim tablicama i grafovima.
