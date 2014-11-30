<?php

function connectToSql($server,$username,$password)
{
    $connection= mysqli_connect($server,$username,$password);
    if (! $connection)
    { 
        die("Unable to connect to server.");
    }
    return $connection;
}       


function dropDB($connection)
{
    $sql ="DROP DATABASE IF EXISTS `mydb`;";
    $result = mysqli_query($connection,$sql);
    
    if (! $result)
    {
        die("Unable to drop database.");
    }   
}


function createDB($connection)
{
    $sql ="CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;";
    $result = mysqli_query($connection,$sql);
    
    if (! $result)
    {
        die("Unable to create database.");
    }   
}

function createDateTable($connection)
{
  $sql = "CREATE TABLE IF NOT EXISTS `mydb`.`DateTable` (
         `dateID` INT NULL AUTO_INCREMENT,
         `date` DATE NOT NULL,
         `publicHolidayID` INT NULL,
         PRIMARY KEY (`dateID`));";
  
  $result = mysqli_query($connection,$sql);
  
    if (! $result)
    {
        die("Unable to create Date table.");
    }   
          
         
}


function createPublicHolidayTable($connection)
{
   $sql="CREATE TABLE IF NOT EXISTS `mydb`.`publicHolidayTable` (
        `publicHolidayID` INT NOT NULL AUTO_INCREMENT,
        `nameOfPublicHoliday` VARCHAR(40) NOT NULL,
        `dateID` INT NOT NULL,
        PRIMARY KEY (`publicHolidayID`),
        INDEX `fk_publicHoliday_Date_idx` (`dateID` ASC),
        CONSTRAINT `fk_publicHoliday_Date`
        FOREIGN KEY (`dateID`)
        REFERENCES `mydb`.`DateTable` (`dateID`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION);"; 
   
  
   $result = mysqli_query($connection,$sql);
   
   if (! $result)
    {
        die("Unable to create Public Holiday table.");
    }   
}



function createAbsenceTypeTable($connection)
{
   $sql= "CREATE TABLE IF NOT EXISTS `mydb`.`absenceTypeTable` (
         `absenceTypeID` INT NOT NULL AUTO_INCREMENT,
         `absenceTypeName` VARCHAR(20) NOT NULL,
         `usesAnnualLeave` TINYINT(1) NOT NULL,
         `canBeDenied` TINYINT(1) NOT NULL,
         PRIMARY KEY (`absenceTypeID`));";
   
   $result = mysqli_query($connection,$sql);
    
if (! $result)
    {
        die("Unable to create Absence Type table.");
    }   
}



function createCompanyRoleTable($connection)
{
    $sql="CREATE TABLE IF NOT EXISTS `mydb`.`companyRoleTable` (
         `companyRoleID` INT NOT NULL AUTO_INCREMENT,
         `roleName` VARCHAR(30) NOT NULL,
         `minimumStaffingLevel` INT(1) NOT NULL,
          PRIMARY KEY (`companyRoleID`));";
    
    $result = mysqli_query($connection,$sql);
    
    if (! $result)
    {
        die("Unable create Company Role table.");
    }   
}



function createEmployeeTable($connection)
{
    $sql="CREATE TABLE IF NOT EXISTS `mydb`.`EmployeeTable` (
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
         ON UPDATE NO ACTION);";
    
    $result = mysqli_query($connection,$sql);
    
    if (! $result)
    {
        die("Unable to create Employee table.");
    }   
}



function createApprovedAbsenceBookingTable($connection)
{
    $sql="CREATE TABLE IF NOT EXISTS `mydb`.`approvedAbsenceBookingTable` (
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
    ON UPDATE NO ACTION);";
    
    $result = mysqli_query($connection,$sql);
    
    if (! $result)
    {
        die("Unable to create Approved Absence Booking table.");
    }   
}



function createApprovedAbsenceDateTable($connection)
{
   $sql="CREATE TABLE IF NOT EXISTS `mydb`.`approvedAbsenceBookingDate` (
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
    ON UPDATE NO ACTION);";
   
   $result = mysqli_query($connection,$sql);
    
    if (! $result)
    {
        die("Unable to create Approved Absence Date table.");
    }   
}


function createAdHocAbsenceRequest($connection)
{
    $sql="CREATE TABLE IF NOT EXISTS `mydb`.`adHocAbsenceRequestTable` (
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
    ON UPDATE NO ACTION);";
    
    $result = mysqli_query($connection,$sql);
    
    if (! $result)
    {
        die("Unable to create Ad Hoc Absence Request table.");
    }   
}



function createMainVacationRequestTable($connection)
{
    $sql="CREATE TABLE IF NOT EXISTS `mydb`.`mainVacationRequestTable` (
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
         ON UPDATE NO ACTION);";
    
     $result = mysqli_query($connection,$sql);
    
    if (! $result)
    {
        die("Unable to create Main Vacation Request table.");
    }   
}

function useDB($connection)
{
 $sql ="USE mydb;";
    $result = mysqli_query($connection,$sql);
   if (! $result)
    {
        die("Unable to use mydb");
    }   
}


function createNewDatabase($connection)
{
    dropDB($connection);
    createDB($connection);
    useDB($connection);
    createDateTable($connection);
    createPublicHolidayTable($connection);
    createAbsenceTypeTable($connection);
    createCompanyRoleTable($connection);
    createEmployeeTable($connection);
    createApprovedAbsenceBookingTable($connection);
    createApprovedAbsenceDateTable($connection);
    createAdHocAbsenceRequest($connection);

  
}

function addCompanyRole($connection,$role,$minStaffLevel)
{

    $sql ="INSERT INTO companyroletable (roleName,minimumStaffingLevel) VALUES ('".$role."',".$minStaffLevel.");";
    $result = mysqli_query($connection,$sql);
    if (! $result)
    {
        echo mysqli_error($connection);
        die("Unable to add company role.");
    }   
}

function addEmployee ($connection,$name,$email,$password,$dateJoined,$annualLeave,$companyrole)
{
    $sql="INSERT INTO EmployeeTable (employeeName,emailAddress,password,annualLeaveEntitlement,dateJoinedTheCompany,companyRole_companyRoleID) "
            . "VALUES ('".$name."','".$email."','".$password."','".$dateJoined."','.$annualLeave.','.$companyrole.');";
    $result = mysqli_query($connection,$sql);
    echo $sql;
    if (! $result)
    {
        echo mysqli_error($connection);
        die("Unable to add employee.");
    }   
    
}

$connection = connectToSql("localhost","root","root");
createNewDatabase($connection);
addCompanyRole($connection,"Cashier",2);
addCompanyRole($connection,"Manager",3);
addEmployee ($connection,"Sam","samgilbertuk@hotmail.com","zaq12wsx","28/11/1995",22,1);

mysqli_close($connection);  


?>




