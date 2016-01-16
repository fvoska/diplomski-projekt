#!/usr/bin/env python
# -*- coding: utf-8 -*-

import os
from datetime import datetime
from datetime import timedelta
import Cookie
import uuid


def podesi_kolacice():

    c = Cookie.SimpleCookie()
    kolacic = Cookie.SimpleCookie()
    kolacic_string = os.environ.get("HTTP_COOKIE")
    if(kolacic_string):
        kolacic.load(kolacic_string)
    novi_korisnik = "yes"

    if ("HascheckUserID" in kolacic.keys()):
        korisnikID = kolacic["HascheckUserID"].value
        novi_korisnik = "no"
    else:
        korisnikID = str(uuid.uuid1())
        c["HascheckUserID"] = korisnikID
        istice = datetime.utcnow() + timedelta(days = 3650)
        c["HascheckUserID"]["expires"] = istice.strftime('%a, %d %b %Y %H:%M:%S')
        c["HascheckUserID"]["domain"] = ".tel.fer.hr"

    return (c, korisnikID, novi_korisnik)

if __name__ == '__main__':
    main()
