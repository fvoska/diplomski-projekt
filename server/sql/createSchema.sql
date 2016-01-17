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
