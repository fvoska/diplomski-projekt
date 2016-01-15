#!/usr/bin/env python
# -*- coding: utf-8 -*-

import re
from dateutil.parser import parse
import string

def popravi(redak):
    #zamijene se cudni znakovi hrvatskim slovima
    redak=redak.replace("{",u"š").replace("}",u"ć").replace("~",u"č").replace("|",u"đ").replace("`",u"ž")
    redak=redak.replace("[",u"Š").replace("]",u"Ć").replace("^",u"Č").replace("\\",u"Đ").replace("@",u"Ž")
    return(redak)

stat_file = open("statistika.primjer", "r")
stats = stat_file.read()
stat_file.close()

report_file = open("report.primjer", "r")
reports = report_file.read()
report_file.close()

stats = stats.split("--------------------------------------------------------------------------------\nFrom: ")
reports = reports.split("\nFrom: ")
n = len(reports)
if (len(stats) < len(reports)):
    n = len(stats)

for i in range(1, n):
    lines = stats[i].split("\n")
    ip = lines[0].split()[0]
    user_id = lines[1].split("UserID: ")[1]
    request_time = parse(lines[2])
    errors_types = lines[6].split()
    errors_count = lines[7].split()

    reports_len = len(reports[i].split('\n'))
    print reports_len

    if reports_len == 5:
        # no errors

    izvjestaj = {}
    izvjestaj['errors'] = []
    

    podaci_za_bazu = {}
    podaci_za_bazu['ip'] = ip
    podaci_za_bazu['userID'] = user_id
    podaci_za_bazu['requestTime'] = request_time.strftime("%Y-%m-%d %H:%M:%S.%f")
    podaci_za_bazu['context'] = "on"
    podaci_za_bazu['wordCounter'] = errors_count[len(errors_count) - 1]
    podaci_za_bazu['report'] = izvjestaj
    podaci_za_bazu['timeProcessed']="4.20"
    json_podaci = json.dumps(podaci_za_bazu)
