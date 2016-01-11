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

ip = os.getenv("REMOTE_ADDR")
procesID = str(os.getpid())       #dobije se broj python procesa koji izvodi skriptu


privremena_datoteka = direktorij + "/hacheck_" + datum + "_" + ip + "_" + procesID

unos = tekst.decode("utf-8")
duljina_unosa = len(unos)


#prilagodi se za haseka
import prilagodba
unos_modificirani = prilagodba.modificiraj(unos)

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
    (ispis, brojac, broj_gresaka) = obrada_json.pripremi_ispis(ispis, hasek_izlaz, brojac, broj_gresaka, unos)
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

#izrada statistike
if (ip != "161.53.19.185"):
    trenutno_vrijeme = time.time()
    datum = time.strftime('%a %b %d %H:%M:%S %Z %Y', time.localtime(trenutno_vrijeme + 19 * 60 + 38))       #kad se pokrene naredba date iz ljuske dobije se pomaknuto vrijeme...(19 min i 38 sec)
    zaglavlje_izvjesca = "\nFrom: " + ip + "  Version: 3.0 beta\n" + datum + "\n\n"

    pomocna=(izvjesce, zaglavlje_izvjesca, izvjesce_datoteka, statistika_datoteka, brojac, broj_gresaka, privremena_datoteka)

    import statistika
    statistika.generiraj(pomocna)

os.remove(privremena_datoteka)



