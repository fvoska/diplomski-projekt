#!/usr/bin/env python
# -*- coding: utf-8 -*-

import codecs
import re
import string

def pripremi_ispis(ispis, hasek_izlaz, brojac, broj_gresaka, unos):

    pogreske = len(hasek_izlaz)
    ispis.append("\t<results errors=\"" + str(pogreske) + "\">")
    for redak in hasek_izlaz:
        #zamijeni cudne znakove hrvatskim slovima
        import hrv_slova
        redak = hrv_slova.popravi(redak)

        m = re.search(r"^-([xlmskgcGP][xlmskgcGP])- ([^\s]*) (\d*)", redak)
        if (m):
            if ((m.group(1) != "GG") and (m.group(1) != "PP")):
                brojac["-" + m.group(1) + "-"] += int(m.group(3))
                broj_gresaka += int(m.group(3))

            ispis.append("\t\t<error occurrences=\"" + m.group(3) + "\" severity=\"" + m.group(1)+ "\">")

            sumnjivo = m.group(2)
            if ((m.group(1) == "GG") or (m.group(1) == "PP")):
                sumnjivo = re.sub("#"," ",sumnjivo)
            lista_pozicija = [p.start() for p in re.finditer(re.compile(r"\b%s\b" % sumnjivo, re.UNICODE) , unos)]
            for pozicija in lista_pozicija:
                ispis.append("\t\t\t<position>" + str(pozicija) + "</position>")

            duljina_sumnjivo = len(sumnjivo)
            ispis.append("\t\t\t<length>" + str(duljina_sumnjivo) + "</length>")
            ispis.append("\t\t\t<suspicious>" + sumnjivo + "</suspicious>")

            m = re.search(r"=> (.*)", redak)
            if (m):
                ponuda = m.group(1)
                prijedlozi = []
                prijedlozi = re.split(r"\?\s?", ponuda)
                if ((re.search("^\!(.*?)\!?\?", ponuda)) or (re.search("(.*obliku.*)\?", ponuda))):
                    tip = m.group(1).lower()
                    ispis.append("\t\t\t\t<possible type=\"" + tip + "\"/>")
                else:
                    ispis.append("\t\t\t<suggestions>")
                    for prijedlog in prijedlozi:
                        if(prijedlog):          #to zato jer je zadnji element liste prazan zbog splitanja
                            ispis.append("\t\t\t\t<word>" + prijedlog + "</word>")
                    ispis.append("\t\t\t</suggestions>")
            ispis.append("\t\t</error>")
    ispis.append("\t</results>")

    return(ispis, brojac, broj_gresaka)

if __name__ == '__main__':
    main()
