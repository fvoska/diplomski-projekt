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

def pripremi_ispis(ispis, hasek_izlaz, brojac, broj_gresaka, unos):

    pogreske = len(hasek_izlaz)
    ispis.append("\t\t\"errors\": " + str(pogreske) + ",")
    ispis.append("\t\t\"error\": [")
    for redak in hasek_izlaz:
        #zamijeni se cudne znakove hrvatskim slovima
        import hrv_slova
        redak = hrv_slova.popravi(redak)

        m = re.search(r"^-([xlmskgcGP][xlmskgcGP])- ([^\s]*) (\d*)", redak)        #ja bi tu del plus umjesto *
        if (m):
            if (m.group(1) != "GG"):
                brojac["-" + m.group(1) + "-"] += int(m.group(3))
                broj_gresaka += int(m.group(3))

            ispis.append("\t\t\t{")
            ispis.append("\t\t\t\t\"occurrences\": " + m.group(3) + ",")
            ispis.append("\t\t\t\t\"severity\": \"" + m.group(1) + "\",")

            sumnjivo = m.group(2)
            if (m.group(1) == "GG"):
                sumnjivo = re.sub("#"," ",sumnjivo)

            ispis.append("\t\t\t\t\"position\": [")
            lista_pozicija = [p.start() for p in re.finditer(re.compile(r"\b%s\b" % sumnjivo, re.UNICODE) , unos)]
            for pozicija in lista_pozicija:
                ispis.append("\t\t\t\t\t" + str(pozicija) + ",")

            #mora se izbrisati zarez
            pom = ispis.pop()
            pom = pom.rstrip(",")
            ispis.append(pom)
            ispis.append("\t\t\t\t],")

            duljina_sumnjivo = len(sumnjivo)
            ispis.append("\t\t\t\t\"length\": " + str(duljina_sumnjivo) + ",")
            ispis.append("\t\t\t\t\"suspicious\": \"" + sumnjivo + "\"")

            m = re.search(r"=> (.*)", redak)
            if (m):
                #mora se dodati zarez
                pom = ispis.pop()
                ispis.append(pom + ",")

                ponuda = m.group(1)
                prijedlozi = []
                prijedlozi = re.split(r"\?\s?", ponuda)

                ispis.append("\t\t\t\t\"suggestions\": [")
                for prijedlog in prijedlozi:
                    if(prijedlog):          #to zato jer je zadnji element liste prazan zbog splitanja
                        ispis.append("\t\t\t\t\t\"" + prijedlog + "\",")

                #mora se izbrisati zarez
                pom = ispis.pop()
                pom = pom.rstrip(",")
                ispis.append(pom)

                ispis.append("\t\t\t\t]")
            ispis.append("\t\t\t},")

    #mora se izbrisati zarez
    pom = ispis.pop()
    pom = pom.rstrip(",")
    ispis.append(pom)

    ispis.append("\t\t]")

    return(ispis, brojac, broj_gresaka)
if __name__ == '__main__':
    main()
