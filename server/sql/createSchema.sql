CREATE SCHEMA `ispraviMe`;

CREATE TABLE `ispraviMe`.`user` (
  `userID` VARCHAR(75) NOT NULL,
  `timeAppeard` DATETIME NOT NULL,
  `lastIP` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`userID`));

CREATE TABLE `ispraviMe`.`user_ip` (
  `userID` VARCHAR(75) NOT NULL,
  `IP` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`userID`, `IP`),
  CONSTRAINT `uIP`
    FOREIGN KEY (`userID`)
    REFERENCES `ispraviMe`.`user` (`userID`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION);

CREATE TABLE `ispraviMe`.`request` (
  `reqID` INT NOT NULL AUTO_INCREMENT,
  `requestText` TEXT NULL,
  `reqTextLength` INT NULL,
  `userID` VARCHAR(75) NOT NULL,
  `timeRequested` DATETIME NULL,
  `timeProcessed` DATETIME NULL,
  PRIMARY KEY (`reqID`),
    FOREIGN KEY (`userID`)
    REFERENCES `ispraviMe`.`user` (`userID`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION);

CREATE TABLE `ispraviMe`.`error_type` (
  `errorTypeID` VARCHAR(10) NOT NULL,
  `errorTypeDesc` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`errorTypeID`));

CREATE TABLE `ispraviMe`.`error` (
  `errorID` INT NOT NULL AUTO_INCREMENT,
  `errorTypeID` VARCHAR(10) NULL,
  `errorPhrase` VARCHAR(30) NULL,
  `numOccur` INT NULL,
  `correctedTo` VARCHAR(30) NULL,
  PRIMARY KEY (`errorID`),
    FOREIGN KEY (`errorTypeID`)
    REFERENCES `ispraviMe`.`error_type` (`errorTypeID`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION);

CREATE TABLE `ispraviMe`.`request_error` (
  `errorID` INT NOT NULL,
  `reqID` INT NULL,
  PRIMARY KEY (`errorID`),
  CONSTRAINT `reqKey`
    FOREIGN KEY (`reqID`)
    REFERENCES `ispraviMe`.`request` (`reqID`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `errorKey`
    FOREIGN KEY (`errorID`)
    REFERENCES `ispraviMe`.`error` (`errorID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
