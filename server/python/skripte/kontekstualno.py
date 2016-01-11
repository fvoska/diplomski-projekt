#!/usr/bin/env python
# -*- coding: utf-8 -*-

import string

def provjeri(arguments, hasek_dir):
    if "context" in arguments.keys():
        kontekst = arguments['context'].value
        kontekst = kontekst.lower()
    else:
        kontekst = "on"

    if(kontekst != "on" and kontekst != "off"):
        kontekst = "off"

    if (kontekst == "on"):
        skripta = hasek_dir + "/haschek.mail1-p6"
    else:
        skripta = hasek_dir + "/haschek.mail1-p5"

    return (kontekst, skripta)

if __name__ == '__main__':
    main()
