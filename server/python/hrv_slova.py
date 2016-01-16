#!/usr/bin/env python
# -*- coding: utf-8 -*-

import string


def popravi(redak):
    #zamijene se cudni znakovi hrvatskim slovima
    redak=redak.replace("{",u"š").replace("}",u"ć").replace("~",u"č").replace("|",u"đ").replace("`",u"ž")
    redak=redak.replace("[",u"Š").replace("]",u"Ć").replace("^",u"Č").replace("\\",u"Đ").replace("@",u"Ž")

    return(redak)

def zamijeni(redak):
    #zamijene se hrvatska slova cudnim znakovima
    redak = redak.replace(u"š", "{").replace(u"ć", "}").replace(u"č", "~").replace(u"đ", "|").replace(u"ž", "`")
    redak = redak.replace(u"Š", "[").replace(u"Ć", "]").replace(u"Č", "^").replace(u"Đ", "\\").replace(u"Ž", "@")

    return(redak)

if __name__ == '__main__':
    main()
