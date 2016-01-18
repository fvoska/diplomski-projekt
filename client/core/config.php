<?php
define("APP_PATH","/var/www/hacheck.tel.fer.hr/grupa84/app/");

class Config{

    protected $dbh;
    protected $config;

    /*
     * parsing ini file
     * @return config array
     */
    public function __construct(){

        header('Content-type: text/html; charset=UTF-8');
        ini_set('short_open_tag', 'On');
        ini_set('date.timezone', 'Europe/Zagreb');

        $this->config = parse_ini_file(APP_PATH."/config.ini", true);
        $this->dbh = new PDO('mysql:host='.$this->config['db']['dbHost'].';dbname='.$this->config['db']['dbName'].';port=3306', $this->config['db']['dbUser'], $this->config['db']['dbPass']);
    }


}
