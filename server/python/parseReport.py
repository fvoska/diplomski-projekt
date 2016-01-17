#!/usr/bin/env python
# -*- coding: utf-8 -*-

import re
from dateutil.parser import parse
import string
import json
import subprocess
import random

def popravi(redak):
    #zamijene se cudni znakovi hrvatskim slovima
    redak=redak.replace("{",u"š").replace("}",u"ć").replace("~",u"č").replace("|",u"đ").replace("`",u"ž")
    redak=redak.replace("[",u"Š").replace("]",u"Ć").replace("^",u"Č").replace("\\",u"Đ").replace("@",u"Ž")
    redak=redak.replace("#",u" ")
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

    reports_split = reports[i].split('\n')

    izvjestaj = {}
    izvjestaj['errors'] = []
    izvjestaj['numberOfErrors'] = int(errors_count[-2])

    if len(reports_split) == 5:
        # no errors
        pass
    else:
        for j in range(4, len(reports_split) - 1):
            s = reports_split[j].split('=>')
            sl = s[0].split()
            type = sl[0]
            suspicious = popravi(sl[1])
            num_occur = sl[2]

            e = {}
            e['suspicious'] = suspicious
            e['severity'] = type.replace('-', '')
            e['occurrences'] = int(num_occur)

            suggestions = []
            if len(s) == 2:
                sr = s[1].split()
                for k in sr:
                    suggestions.append(popravi(str(k[:-1])))
                e['suggestions'] = suggestions

            izvjestaj['errors'].append(e)

    podaci_za_bazu = {}
    podaci_za_bazu['ip'] = ip
    podaci_za_bazu['userID'] = user_id
    podaci_za_bazu['requestTime'] = request_time.strftime("%Y-%m-%d %H:%M:%S.%f")
    podaci_za_bazu['context'] = "on"
    podaci_za_bazu['wordCounter'] = int(errors_count[len(errors_count) - 1])
    podaci_za_bazu['report'] = izvjestaj
    podaci_za_bazu['timeProcessed'] = random.uniform(2, 6)
    json_podaci = json.dumps(podaci_za_bazu)
    print '\nUbacujem u bazu: '
    print json_podaci
    subprocess.call(["php","-f","/var/www/hacheck.tel.fer.hr/grupa84/app/core/insert-request.php", json_podaci])
