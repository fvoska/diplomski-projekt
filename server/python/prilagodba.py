#!/usr/bin/env python
# -*- coding: utf-8 -*-

import re
import string

def modificiraj(unos):

    unos_modificirani = unos
    unos_modificirani = unos_modificirani.replace(u"\u00e6", u"\u0107" )        #pretvara ae u ć
    #unos_modificirani = re.sub(u"\p{Cf}", '', unos_modificirani)
    unos_modificirani = re.sub(re.compile("[^\w\d\s]", re.UNICODE), u" ", unos_modificirani)
    #unos_modificirani = re.sub(u"\p{Pi}", u'"', unos_modificirani)
    unos_modificirani = unos_modificirani.replace(u"\u201e", u'"')
    unos_modificirani = unos_modificirani.replace(u"\u2022", u" ")
    unos_modificirani = unos_modificirani.replace(u"\u2026", u".")
    #unos_modificirani = re.sub("\p{Pf}", '"',unos_modificirani)
    #unos_modificirani = re.sub("\p{Pd}", "-",unos_modificirani)
    #unos_modificirani = re.sub("\p{No}", '"',unos_modificirani)
    unos_modificirani = re.sub("@", " ",unos_modificirani)
    unos_modificirani = re.sub("[\[\]\{\}]", " ",unos_modificirani)
    unos_modificirani = re.sub("'\s", " ",unos_modificirani)
    unos_modificirani = unos_modificirani.replace("\\", " ")

    #zamijeni hrvatska slova cudnim znakovima
    import hrv_slova
    unos_modificirani = hrv_slova.zamijeni(unos_modificirani)

    return(unos_modificirani)

if __name__ == '__main__':
    main()
