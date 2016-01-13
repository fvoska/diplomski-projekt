var translations = {
  hr: {
    /* Common */
    'application_title': 'Diplomski Projekt',
    'toggle_nav': 'Navigacija',
    'back': 'Vrati se',

    /* Homepage */
    'home': 'Naslovna',

    /* Users */
    'users': 'Korisnici',
    'usersDetails': 'Detalji o korisniku',
    'usersRequests': 'Zahtjevi korisnika',
    'user_id': 'ID',
    'first_appear': 'Prvi pristup',
    'num_requests': 'Broj zahtjeva',
    'last_ip': 'Zadnja IP adresa',
    'ip_history': 'Korištene IP adrese',
    'user_details_sessions': 'Sjednice',
    'user_details_requests': 'Zahtjevi za ispravljanjem',
    'chart_error_types': 'Udio tipova pogrešaka',
    'error_percentage': 'Učestalost grešaka u zahtjevima',
    'request_word_count_avg': 'Prosječan broj riječi po zahtjevu',
    'request_error_count_avg': 'Prosječan broj grešaka po zahtjevu',
    'request_error_percent': 'Postotak pogrešaka',
    'chart_activity_monthly': 'Broj zahtjeva po mjesecima',

    /* Requests */
    'requests': 'Zahtjevi',
    'requestsDetails': 'Detalji zahtjeva',
    'request_id': 'ID',
    'req_time': 'Vrijeme zahtjeva',
    'req_processing': 'Vrijeme obrade',
    'num_errors': 'Broj grešaka',

    /* Errors */
    'errors': 'Greške',
    'error_id': 'ID',
    'suspicious_word': 'Pogrešna riječ/fraza',
    'error_type': 'Tip pogreške',
    'num_occur': 'Broj pojavljivanja pogreške u zahtjevu',

    /* Tables */
    'lengthMenu': 'Prikaži _MENU_ zapisa po stranici',
    'search': 'Traži:  ',
    'zeroRecords': 'Nema rezultata',
    'info': 'Stranica _PAGE_ od _PAGES_',
    'infoEmpty': 'Nema zapisa',
    'infoFiltered': '(filtrirano iz ukupno _MAX_ zapisa)',
    'actions': 'Akcije',
    'details': 'Detalji',
    'filter_by': 'Filtriraj po',
    'first': 'Prva',
    'last': 'Zadnja',
    'next': 'Sljedeća',
    'previous': 'Prethodna',
    'processing': '<div class="loader">Obrađujem</div>',

    /* Page 2 & 3 navigation parent */
    'pages23': 'More pages',

    /* Page 2 */
    'page2': 'Page 2',

    /* Page 3 */
    'page3': 'Page 3'
  }
}

function trans(code) {
  var t = translations[config.language][code];
  if (t) {
    return t;
  }
  else {
    return 'NO TRANSLATION FOR ' + code + '';
  }
}
