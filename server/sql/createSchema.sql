CREATE TABLE `ispraviMe`.`user` (
    `userID` varchar(75) NOT NULL,
    `timeAppeared` datetime NOT NULL,
    `lastIP` varchar(45) NOT NULL,
    PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `ispraviMe`.`user_ip` (
    `userID` varchar(75) NOT NULL,
    `IP` varchar(45) NOT NULL,
    `time` datetime NOT NULL,
    PRIMARY KEY (`userID`,`IP`),
    CONSTRAINT `userIP`
        FOREIGN KEY (`userID`)
        REFERENCES `ispraviMe`.`user` (`userID`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `ispraviMe`.`request` (
    `reqID` int NOT NULL AUTO_INCREMENT,
    `requestText` text,
    `reqTextLength` int DEFAULT NULL,
    `userID` varchar(75) NOT NULL,
    `timeRequested` datetime DEFAULT NULL,
    `timeProcessed` float DEFAULT NULL,
    PRIMARY KEY (`reqID`),
    CONSTRAINT `reqUserID`
        FOREIGN KEY (`userID`)
        REFERENCES `ispraviMe`.`user` (`userID`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `ispraviMe`.`error_type` (
    `errorTypeID` varchar(10) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
    `errorTypeDesc` varchar(45) NOT NULL,
    PRIMARY KEY (`errorTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `ispraviMe`.`error_type` VALUES
    ('GG','grammar'),
    ('PP','pleonasm'),
    ('cc','unclasifiable'),
    ('gg','case-mix'),
    ('kk','alphanum-mix'),
    ('ll','major'),
    ('mm','moderate'),
    ('ss','minor'),
    ('xx','extreme');

CREATE TABLE `ispraviMe`.`error` (
    `errorID` int NOT NULL AUTO_INCREMENT,
    `errorTypeID` varchar(10) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
    `errorPhrase` varchar(30) DEFAULT NULL,
    `numOccur` int DEFAULT NULL,
    `correctedTo` varchar(30) DEFAULT NULL,
    PRIMARY KEY (`errorID`),
    CONSTRAINT `errorErrorType`
        FOREIGN KEY (`errorTypeID`)
        REFERENCES `ispraviMe`.`error_type` (`errorTypeID`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=337 DEFAULT CHARSET=latin1;

CREATE TABLE `request_error` (
    `errorID` int NOT NULL,
    `reqID` int DEFAULT NULL,
    PRIMARY KEY (`errorID`, `reqID`),
    CONSTRAINT `reqErrorReq`
        FOREIGN KEY (`reqID`)
        REFERENCES `ispraviMe`.`request` (`reqID`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION,
    CONSTRAINT `reqErrorError`
        FOREIGN KEY (`errorID`)
        REFERENCES `ispraviMe`.`error` (`errorID`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE VIEW usr_req_counter AS SELECT u.userID, COUNT(r.reqID) AS reqCount FROM user u JOIN request r ON r.userID = u.userID GROUP BY u.userID;

CREATE TABLE `networks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `mask` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8;

INSERT INTO `ispraviMe`.`networks`
(`name`, `mask`)
VALUES
('Grad Zagreb', '213.147.104.128/27'),
('Index.hr', '74.63.112.0/24'),
('Net.hr', '107.170.27.97'),
('Net.hr', '81.4.120.35'),
('Net.hr', '46.101.4.62'),
('Net.hr', '176.31.232.167'),
('Net.hr', '188.165.199.94'),
('Net.hr', '67.159.5.242'),
('Net.hr', '67.159.36.19'),
('Net.hr', '67.159.36.20'),
('Net.hr', '213.191.146.0/24'),
('NK Dinamo', '212.92.197.8/29'),
('Dnevno.hr', '85.114.38.246'),
('Dnevno.hr', '213.186.17.158'),
('Grad Rijeka', '212.91.124.136/30'),
('Narodni list - Zadar', '85.114.54.24/29'),
('Obiteljski radio', '195.29.227.192/28'),
('RTL', '195.29.146.160/28'),
('RTL', '83.139.112.64/28'),
('Index.hr', '93.174.93.0/24'),
('Index.hr', '31.45.242.0/24'),
('Index.hr', '78.129.148.0/24'),
('Index.hr', '50.7.78.234/32'),
('Index.hr', '167.114.118.4/32'),
('Index.hr', '66.90.121.130/32'),
('Index.hr', '66.90.121.144/32'),
('Index.hr', '95.154.230.252/32'),
('Index.hr', '80.80.34.204/32'),
('Coca Cola', '195.29.117.72/29'),
('Coca Cola', '213.202.121.16/29'),
('Coca Cola', '77.237.121.252'),
('Coca Cola', '89.164.73.28'),
('SbbNet', '82.117.194.66'),
('Metronet', '213.147.120.54'),
('Hina', '193.105.193.102'),
('Dobbin', '85.10.49.250'),
('Novi list', '85.114.62.128/26'),
('Avalon', '89.201.167.26'),
('Adris', '195.29.95.225'),
('Adris', '195.29.94.17'),
('narod.hr', '188.129.4.78'),
('preporuceno.com', '81.93.70.246'),
('Degorian', '85.114.40.50'),
('Degorian', '85.114.43.112/28'),
('Fashion.hr', '213.147.97.173'),
('JGL', '195.29.103.34'),
('RBA', '193.23.182.0/24'),
('H1', '80.80.50.48'),
('Brod Portal', '85.114.47.230'),
('Croatia Records', '89.201.162.78'),
('Zagrebačka banka', '195.29.221.170'),
('Zagrebačka banka', '195.29.221.169'),
('Adria media', '89.164.92.0/24'),
('Nova TV', '91.209.32.0/24'),
('HEP', '217.68.80.0/20'),
('CARNet', '193.198.0.0/16'),
('CARNet', '161.53.0.0/16'),
('CARNet', '82.132.0.0/17'),
('CATNet', '31.147.0.0/16'),
('Styria', '193.25.220.0/24'),
('EPH', '213.202.78.0/24'),
('MGIPU', '89.249.104.32/28'),
('HGK', '212.15.185.0/27'),
('Nacional', '185.46.33.13'),
('SDP', '85.114.43.40/29'),
('China Radio International', '122.10.133.0/24'),
('Monitor Online', '94.102.234.200/29'),
('Al Jazeera Balkans', '146.255.146.96/32'),
('.ba', '77.238.210.171/32'),
('.ba', '5.9.141.106'),
('Banka', '213.191.143.192/29'),
('Emporion', '213.147.99.0/24'),
('Fightsite', '212.92.205.3'),
('24sata', '193.25.220.10'),
('24sata', '193.25.220.11'),
('24sata', '91.207.22.0/24'),
('BNet', '94.253.188.0/24'),
('T-HT', '31.217.6.34'),
('HRT', '195.29.137.64/27'),
('Alternativa za Vas', '78.134.245.84'),
('Alkemist Translation Agency', '109.227.63.5'),
('Nepoznati oglašivač', '212.92.200.229'),
('Glas Slavonije', '213.202.94.32/29'),
('Hrvatske autoceste', '193.105.23.0/24'),
('HRT', '213.5.56.0/21'),
('Navigatio Europea', '212.91.115.224/29'),
('Oglasnik', '195.245.255.0/24'),
('Koprivnica', '212.92.200.254'),
('Radio 057', '83.139.111.107'),
('Sportske novosti', '195.29.197.112/29'),
('Iskon', '213.191.142.0/24'),
('Root Media', '85.10.51.128/28'),
('Kantonalno tužiteljstvo BiH', '195.222.55.94'),
('Hrvatska lutrija', '195.29.226.0/28');
