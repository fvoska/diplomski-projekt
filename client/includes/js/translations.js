var translations = {
  en: {
    /* Common */
    'application_title': 'Diplomski Projekt',
    'toggle_nav': 'Toggle navigation',

    /* Homepage */
    'home': 'Dashboard',

    /* Page 1 */
    'page1': 'Page 1',

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
