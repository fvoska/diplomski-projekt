#!/usr/bin/env python
# -*- coding: utf-8 -*-

import cgi
import cgitb; cgitb.enable()
import os, sys
import commands
import time
from datetime import datetime
import codecs
import re
import string
import json
import subprocess



vrijeme_pocetka = datetime.now()
arguments = cgi.FieldStorage()
datum = time.strftime("%Y-%m-%d")

vrsta = "input"
direktorij = "/var/www/hacheck.tel.fer.hr/grupa84/temp"
hasek_dir = "/users/hacheck/checker"
izvjesce_dir = "/var/www/hacheck.tel.fer.hr/grupa84/stat"
izvjesce_datoteka = izvjesce_dir + "/report-json"
statistika_datoteka = izvjesce_dir + "/statistika-json"


if "textarea" in arguments.keys():
    tekst = arguments["textarea"].value
else:
    tekst = ""

#provjeravanje konteksta
import kontekstualno
(kontekst, skripta) = kontekstualno.provjeri(arguments, hasek_dir)

#podesavanje kolacica
import kolacic
(c, korisnikID, novi_korisnik) = kolacic.podesi_kolacice()

ip = os.getenv("REMOTE_ADDR")
procesID = str(os.getpid())       #dobije se broj python procesa koji izvodi skriptu


privremena_datoteka = direktorij + "/hacheck_" + datum + "_" + ip + "_" + procesID

unos = tekst.decode("utf-8")
duljina_unosa = len(unos)


#prilagodi se za haseka
import prilagodba
unos_modificirani = prilagodba.modificiraj(unos)
count = len(re.findall(r'\w+', unos_modificirani))

#resetira se statistika
broj_gresaka = 0
brojac = {'-xx-': 0, '-ll-': 0, '-mm-': 0, '-ss-': 0, '-cc-': 0, '-gg-': 0, '-kk-': 0, '-GG-': 0, '-PP-': 0}

#napravi se privremena datoteka
with codecs.open(privremena_datoteka, "w", "utf-8") as datoteka:
        datoteka.write(unos_modificirani + "\n")
datoteka.close()

hasek_izlaz = commands.getoutput("cat " + privremena_datoteka + "|" + skripta).split('\n')
izvjesce = hasek_izlaz

ispis=[]
if (novi_korisnik == "yes"):
    ispis.append(str(c["HascheckUserID"]))          # print "Set-Cookie: ..."
ispis.append("Content-Type: application/json; charset=\"UTF-8\"\n")
ispis.append("{")
ispis.append("\t\"request\": {")
ispis.append("\t\t\"fixpunctuation\": \"no\",")
ispis.append("\t\t\"commonerrors\": \"no\",")
ispis.append("\t\t\"text length\": " + str(duljina_unosa) + ",")
ispis.append("\t\t\"content type\": \"" + vrsta + "\"")
ispis.append("\t},")
ispis.append("\t\"results\": {")

#pokrece se obrada hasekovog izlaza
if (hasek_izlaz[0]):
    import obrada_json
    (ispis, brojac, broj_gresaka, izvjestaj) = obrada_json.pripremi_ispis(ispis, hasek_izlaz, brojac, broj_gresaka, unos)
else:       #nema gresaka
    ispis.append("\t\t\"errors\": 0")

vrijeme_kraja = datetime.now()
vremenski_interval = vrijeme_kraja-vrijeme_pocetka
sekunde = str(vremenski_interval.seconds)
mikrosekunde = str(vremenski_interval.microseconds).zfill(6)

ispis.append("\t},")
ispis.append("\t\"time\": " + sekunde + "." + mikrosekunde)
ispis.append("}")

for i in ispis:
    print (i.encode('utf-8'))

podaci_za_bazu ={}
podaci_za_bazu['ip'] = ip
podaci_za_bazu['userID'] = korisnikID
podaci_za_bazu['requestTime'] = vrijeme_pocetka.strftime("%Y-%m-%d %H:%M:%S.%f")
podaci_za_bazu['context'] = kontekst
podaci_za_bazu['wordCounter'] = count
try:
    podaci_za_bazu['report'] = izvjestaj
except NameError:
    podaci_za_bazu['report'] = {}
    podaci_za_bazu['report']['errors'] = []
    podaci_za_bazu['report']['numberOfErrors'] = 0
podaci_za_bazu['timeProcessed']=sekunde+"."+mikrosekunde
json_podaci = json.dumps(podaci_za_bazu)
#print ("JSON koji će se predavati php skripti:	" + json_podaci.encode('utf-8'))
#php_output = commands.getoutput("php php_skripta.php " + "\"" + json_podaci + "\"")
#print ("izlaz iz php skripte:	" + php_output)
subprocess.call(["php","-f","/var/www/hacheck.tel.fer.hr/grupa84/app/core/insert-request.php", json_podaci])

try:
    #izrada statistike
    if (ip != "161.53.19.185"):
        trenutno_vrijeme = time.time()
        datum = time.strftime('%a %b %d %H:%M:%S %Z %Y', time.localtime(trenutno_vrijeme + 19 * 60 + 38))       #kad se pokrene naredba date iz ljuske dobije se pomaknuto vrijeme...(19 min i 38 sec)
        zaglavlje_izvjesca = "\nFrom: " + ip + "  Version: 3.0 beta\n" + datum + "\n\n"

        pomocna=(izvjesce, zaglavlje_izvjesca, izvjesce_datoteka, statistika_datoteka, brojac, broj_gresaka, privremena_datoteka)

        import statistika
        statistika.generiraj(pomocna)
except:
    pass

os.remove(privremena_datoteka)
