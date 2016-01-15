var translations = {
  hr: {
    /* Common */
    //'application_title': 'Diplomski Projekt',
    'application_title': 'Ispravi.me Statistika',
    'toggle_nav': 'Navigacija',
    'back': 'Vrati se',

    /* Homepage */
    'home': 'Naslovna',
    'errors_found': 'Pronađenih pogrešaka',
    'errors_found_distinct': 'Jedinstvenih pogrešaka',
    'requests_processed': 'Obrađenih zahtjeva',
    'users_count': 'Korisnika',

    /* Users */
    'users': 'Korisnici',
    'usersDetails': 'Detalji o korisniku',
    'usersRequests': 'Zahtjevi korisnika',
    'user': 'Korisnik',
    'user_id': 'ID',
    'first_appear': 'Prvi pristup',
    'num_requests': 'Broj zahtjeva',
    'last_ip': 'Zadnja IP adresa',
    'ip_history': 'Korištene IP adrese',
    'user_details_sessions': 'Sjednice',
    'user_details_requests': 'Zahtjevi za ispravljanjem',
    'chart_error_types': 'Udio tipova pogrešaka',
    'error_percentage': 'Učestalost pogrešaka u zahtjevima',
    'request_word_count_avg': 'Prosječan broj riječi po zahtjevu',
    'request_error_count_avg': 'Prosječan broj pogrešaka po zahtjevu',
    'request_error_percent': 'Postotak pogrešaka',
    'chart_activity_monthly': 'Broj zahtjeva po mjesecima',
    'no_user_errors': 'Nema grešaka',

    /* Requests */
    'req_word_count': 'Broj riječi',
    'requests': 'Zahtjevi',
    'requestsDetails': 'Detalji zahtjeva',
    'request_id': 'ID',
    'req_time': 'Vrijeme zahtjeva',
    'req_processing': 'Vrijeme obrade',
    'num_errors': 'Broj pogrešaka',

    /* Errors */
    'errors': 'Pogreške',
    'errorsGroup': 'Riječ/fraza',
    'errors_req': 'Pogreške zahtjeva',
    'error_id': 'ID',
    'suspicious_word': 'Pogrešna riječ/fraza',
    'error_type': 'Tip pogreške',
    'num_total_occur': 'Ukupan broj pojavljivanja pogreške',
    'num_total_occur_req': 'Broj zahtjeva u kojima se pojavljuje',
    'all_requests': 'svih zahtjeva',
    'num_occur': 'Broj pojavljivanja pogreške u zahtjevu',
    'error_frequent': 'Najčešće pogreške',

    /* Error types */
    'xx': 'Ekstremna',
    'll': 'Značajna',
    'mm': 'Umjerena',
    'ss': 'Manja',
    'cc': 'Neklasificirana',
    'gg': 'Malo/veliko slovo',
    'kk': 'Umiješani brojevi',
    'GG': 'Gramatička',
    'PP': 'Pleonazam',

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
    'processing': '<div class="loader">Obrađujem</div>'
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
