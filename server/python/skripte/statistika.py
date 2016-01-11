#!/usr/bin/env python
# -*- coding: utf-8 -*-

import commands
import re
import string
import codecs

def generiraj(pomocna):

    (izvjesce, zaglavlje_izvjesca, izvjesce_datoteka, statistika_datoteka, brojac, broj_gresaka, privremena_datoteka)=pomocna

    wc = commands.getoutput("wc " + privremena_datoteka)
    wc = re.sub(privremena_datoteka,"",wc)
    wc = wc.rstrip()

    broj_rijeci = commands.getoutput("cat " + privremena_datoteka + " | sed -f /users/hacheck/checker/break.sed | /usr/bin/sort | /usr/bin/uniq -c | /usr/bin/awk 'NF == 2 {print}' | /usr/bin/awk '{a=a+$1} END {print a}'")
    if not (broj_rijeci):
        broj_rijeci = 0


    #generira se datoteka izvjesce

    with codecs.open(izvjesce_datoteka, "a", "utf-8") as dat:
        dat.write(zaglavlje_izvjesca)

        #zapisi izvjesce u datoteku ali popravi da budu hrvatska slova
        import hrv_slova
        if (izvjesce[0]):
            for redak in izvjesce:
                redak = hrv_slova.popravi(redak)
                dat.write(redak + "\n")
        else:
            dat.write(u"Nije bilo pogrešaka\n")
        dat.close()


    #generira se datoteka statistika
    dat = open(statistika_datoteka, 'a')
    dat.write(zaglavlje_izvjesca)
    dat.write(wc + "\n")
    dat.write("-"*81)
    dat.write("\n%9s%9s%9s%9s%9s%9s%9s%9s%9s\n" % ("-kk-","-gg-","-cc-","-xx-","-ll-","-mm-","-ss-","total","words"))
    dat.write("%9s%9s%9s%9s%9s%9s%9s%9s%9s\n" % (brojac["-kk-"], brojac["-gg-"], brojac["-cc-"], brojac["-xx-"], brojac["-ll-"], brojac["-mm-"],brojac["-ss-"], broj_gresaka , broj_rijeci))
    dat.write("-"*81)
    dat.close()

if __name__ == '__main__':
    main()
