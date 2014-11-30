-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`DateTable`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`DateTable` ;

CREATE TABLE IF NOT EXISTS `mydb`.`DateTable` (
  `dateID` INT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `publicHolidayID` INT NULL,
  PRIMARY KEY (`dateID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`publicHolidayTable`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`publicHolidayTable` ;

CREATE TABLE IF NOT EXISTS `mydb`.`publicHolidayTable` (
  `publicHolidayID` INT NOT NULL AUTO_INCREMENT,
  `nameOfPublicHoliday` VARCHAR(40) NOT NULL,
  `dateID` INT NOT NULL,
  PRIMARY KEY (`publicHolidayID`),
  INDEX `fk_publicHoliday_Date_idx` (`dateID` ASC),
  CONSTRAINT `fk_publicHoliday_Date`
    FOREIGN KEY (`dateID`)
    REFERENCES `mydb`.`DateTable` (`dateID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`absenceTypeTable`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`absenceTypeTable` ;

CREATE TABLE IF NOT EXISTS `mydb`.`absenceTypeTable` (
  `absenceTypeID` INT NOT NULL AUTO_INCREMENT,
  `absenceTypeName` VARCHAR(20) NOT NULL,
  `usesAnnualLeave` TINYINT(1) NOT NULL,
  `canBeDenied` TINYINT(1) NOT NULL,
  PRIMARY KEY (`absenceTypeID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`companyRoleTable`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`companyRoleTable` ;

CREATE TABLE IF NOT EXISTS `mydb`.`companyRoleTable` (
  `companyRoleID` INT NOT NULL AUTO_INCREMENT,
  `roleName` VARCHAR(30) NOT NULL,
  `minimumStaffingLevel` INT(1) NOT NULL,
  PRIMARY KEY (`companyRoleID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`EmployeeTable`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`EmployeeTable` ;

CREATE TABLE IF NOT EXISTS `mydb`.`EmployeeTable` (
  `employeeID` INT NOT NULL AUTO_INCREMENT,
  `employeeName` VARCHAR(50) NOT NULL,
  `emailAddress` VARCHAR(50) NOT NULL,
  `password` VARCHAR(20) NOT NULL,
  `dateJoinedTheCompany` DATE NOT NULL,
  `annualLeaveEntitlement` INT(1) NOT NULL,
  `mainVacationRequestID` INT NULL,
  `companyRole_companyRoleID` INT NOT NULL,
  PRIMARY KEY (`employeeID`),
  INDEX `fk_Employee_companyRole1_idx` (`companyRole_companyRoleID` ASC),
  CONSTRAINT `fk_Employee_companyRole1`
    FOREIGN KEY (`companyRole_companyRoleID`)
    REFERENCES `mydb`.`companyRoleTable` (`companyRoleID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`approvedAbsenceBookingTable`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`approvedAbsenceBookingTable` ;

CREATE TABLE IF NOT EXISTS `mydb`.`approvedAbsenceBookingTable` (
  `approvedAbsenceBookingID` INT NOT NULL AUTO_INCREMENT,
  `employeeID` INT NOT NULL,
  `absenceStartDate` DATE NOT NULL,
  `approvedEndDate` DATE NOT NULL,
  `absenceTypeID` INT NOT NULL,
  PRIMARY KEY (`approvedAbsenceBookingID`),
  INDEX `fk_approvedAbsenceBooking_absenceType1_idx` (`absenceTypeID` ASC),
  INDEX `fk_approvedAbsenceBooking_Employee1_idx` (`employeeID` ASC),
  CONSTRAINT `fk_approvedAbsenceBooking_absenceType1`
    FOREIGN KEY (`absenceTypeID`)
    REFERENCES `mydb`.`absenceTypeTable` (`absenceTypeID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_approvedAbsenceBooking_Employee1`
    FOREIGN KEY (`employeeID`)
    REFERENCES `mydb`.`EmployeeTable` (`employeeID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`approvedAbsenceBookingDate`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`approvedAbsenceBookingDate` ;

CREATE TABLE IF NOT EXISTS `mydb`.`approvedAbsenceBookingDate` (
  `approvedAbsenceBookingDateID` INT NOT NULL AUTO_INCREMENT,
  `dateID` INT NOT NULL,
  `approvedAbsenceBookingID` INT NOT NULL,
  PRIMARY KEY (`approvedAbsenceBookingDateID`),
  INDEX `fk_approvedAbsenceBookingDate_Date1_idx` (`dateID` ASC),
  INDEX `fk_approvedAbsenceBookingDate_approvedAbsenceBooking1_idx` (`approvedAbsenceBookingID` ASC),
  CONSTRAINT `fk_approvedAbsenceBookingDate_Date1`
    FOREIGN KEY (`dateID`)
    REFERENCES `mydb`.`DateTable` (`dateID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_approvedAbsenceBookingDate_approvedAbsenceBooking1`
    FOREIGN KEY (`approvedAbsenceBookingID`)
    REFERENCES `mydb`.`approvedAbsenceBookingTable` (`approvedAbsenceBookingID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`adHocAbsenceRequestTable`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`adHocAbsenceRequestTable` ;

CREATE TABLE IF NOT EXISTS `mydb`.`adHocAbsenceRequestTable` (
  `adHocAbsenceRequestID` INT NOT NULL AUTO_INCREMENT,
  `employeeID` INT NOT NULL,
  `startDate` DATE NOT NULL,
  `endDate` DATE NOT NULL,
  `absenceTypeID` INT NOT NULL,
  PRIMARY KEY (`adHocAbsenceRequestID`),
  INDEX `fk_adHocAbsenceRequest_absenceType1_idx` (`absenceTypeID` ASC),
  INDEX `fk_adHocAbsenceRequest_Employee1_idx` (`employeeID` ASC),
  CONSTRAINT `fk_adHocAbsenceRequest_absenceType1`
    FOREIGN KEY (`absenceTypeID`)
    REFERENCES `mydb`.`absenceTypeTable` (`absenceTypeID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_adHocAbsenceRequest_Employee1`
    FOREIGN KEY (`employeeID`)
    REFERENCES `mydb`.`EmployeeTable` (`employeeID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`mainVacationRequestTable`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`mainVacationRequestTable` ;

CREATE TABLE IF NOT EXISTS `mydb`.`mainVacationRequestTable` (
  `mainVacationRequestID` INT NOT NULL AUTO_INCREMENT,
  `employeeID` INT NOT NULL,
  `firstChoiceStartDate` DATE NOT NULL,
  `firstChoiceEndDate` DATE NOT NULL,
  `secondChoiceStartDate` DATE NOT NULL,
  `secondChoiceEndDate` DATE NOT NULL,
  PRIMARY KEY (`mainVacationRequestID`),
  INDEX `fk_mainVacationRequest_Employee1_idx` (`employeeID` ASC),
  CONSTRAINT `fk_mainVacationRequest_Employee1`
    FOREIGN KEY (`employeeID`)
    REFERENCES `mydb`.`EmployeeTable` (`employeeID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
