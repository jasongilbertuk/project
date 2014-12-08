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

function performSQL($connection,$sql,$errorMessage)
{
    $result = mysqli_query($connection,$sql);
    if (! $result)
    {
        echo mysqli_error($connection);
        die($errorMessage);
    }   
    return mysqli_insert_id($connection);
}      

function performSQLWithResults($connection,$sql,$errorMessage)
{	
	$result = mysqli_query($connection,$sql);
    if (! $result)
    {
        echo mysqli_error($connection);
        die($errorMessage);
    }
    
    while ($row = mysqli_fetch_array($result))
    {
    	$results[] = $row;
    }   
    return $results; 
}

function performSQLSelectWithFilter($connection,$tableName,$filter,$error)     
{
	$sql ="SELECT * FROM ".$tableName." WHERE ";
	foreach($filter as $key=>$value)
	{
		$whereClause[] = $key."='".$value."'";
	}
	
	$sql = $sql.implode(" AND ",$whereClause);
	echo $sql;	
	return performSQLWithResults($connection,$sql,$error);
}


function useDB($connection)
{
 $sql ="USE mydb;";
 performSQL($connection,$sql,"Failure in useDB");
}


function dropDB($connection)
{
    $sql ="DROP DATABASE IF EXISTS `mydb`;";
    performSQL($connection,$sql,"Failure in dropDB");
}


function createDB($connection)
{
    $sql ="CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;";
    performSQL($connection,$sql,"Failure in createDB");
}

function createDateTable($connection)
{
  $sql = "CREATE TABLE IF NOT EXISTS `mydb`.`DateTable` (
         `dateID` INT NULL AUTO_INCREMENT,
         `date` DATE NOT NULL,
         `publicHolidayID` INT NULL,
         PRIMARY KEY (`dateID`));";
    performSQL($connection,$sql,"Failure in createDateTable");
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
    performSQL($connection,$sql,"Failure in createPublicHolidayTable");
}

function createAbsenceTypeTable($connection)
{
   $sql= "CREATE TABLE IF NOT EXISTS `mydb`.`absenceTypeTable` (
         `absenceTypeID` INT NOT NULL AUTO_INCREMENT,
         `absenceTypeName` VARCHAR(20) NOT NULL,
         `usesAnnualLeave` TINYINT(1) NOT NULL,
         `canBeDenied` TINYINT(1) NOT NULL,
         PRIMARY KEY (`absenceTypeID`));";
    performSQL($connection,$sql,"Failure in createAbsenceTypeTable");
}

function createCompanyRoleTable($connection)
{
    $sql="CREATE TABLE IF NOT EXISTS `mydb`.`companyRoleTable` (
         `companyRoleID` INT NOT NULL AUTO_INCREMENT,
         `roleName` VARCHAR(30) NOT NULL,
         `minimumStaffingLevel` INT(1) NOT NULL,
          PRIMARY KEY (`companyRoleID`));";
    performSQL($connection,$sql,"Failure in createCompanyRoleTable");
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
    
     performSQL($connection,$sql,"Failure in createEmployeeTable");
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
    
    performSQL($connection,$sql,"Failure in createApprovedAbsenceBookingTable");
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
   
    performSQL($connection,$sql,"Failure in createApprovedAbsenceDateTable");
}

function createAdHocAbsenceRequestTable($connection)
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
    
    performSQL($connection,$sql,"Failure in createAdHocAbsenceRequest");
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
    
    performSQL($connection,$sql,"Failure in createMainVacationRequestTable");
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
    createAdHocAbsenceRequestTable($connection);
    createMainVacationRequestTable($connection);
}


function addCompanyRole($connection,$role,$minStaffLevel)
{

    $sql ="INSERT INTO companyroletable (roleName,minimumStaffingLevel) VALUES ('".$role."',".$minStaffLevel.");";
    return performSQL($connection,$sql,"Failure in addCompanyRole");
}

function addEmployee ($connection,$name,$email,$password,$dateJoined,$annualLeave,$companyrole)
{
    $sql="INSERT INTO EmployeeTable (employeeName,emailAddress,password,annualLeaveEntitlement,dateJoinedTheCompany,companyRole_companyRoleID) "
            . "VALUES ('".$name."','".$email."','".$password."','".$dateJoined."','.$annualLeave.','.$companyrole.');";
    return performSQL($connection,$sql,"Failure in addEmployee");
}

function addAbsenceType ($connection,$absenceTypeName,$usesAnnualLeave,$canBeDenied)
{
    $sql="INSERT INTO absenceTypeTable (absenceTypeName,usesAnnualLeave,canBeDenied) "
            . "VALUES ('".$absenceTypeName."','".$usesAnnualLeave."','".$canBeDenied."');";
    return performSQL($connection,$sql,"Failure in addAbsenceType");
    
}

function addAdHocAbsenceRequest ($connection,$employeeID,$startDate,$endDate,$absenceTypeID)
{
    $sql="INSERT INTO adHocAbsenceRequestTable (employeeID,startDate,endDate,absenceTypeID) "
            . "VALUES ('".$employeeID."','".$startDate."','".$endDate."','".$absenceTypeID."');";
    return performSQL($connection,$sql,"Failure in addAdHocAbsenceRequest");
}

function addMainVacactionRequest($connection,$employeeID,$firstChoiceStartDate,$firstChoiceEndDate,$secondChoiceStartDate,$secondChoiceEndDate)
{
    $sql="INSERT INTO mainVacationRequestTable (employeeID,firstChoiceStartDate,firstChoiceEndDate,secondChoiceStartDate,secondChoiceEndDate) "."VALUES ('".$employeeID."','".$firstChoiceStartDate."','".$firstChoiceEndDate."','".$secondChoiceStartDate."','".$secondChoiceEndDate."');";
    return performSQL($connection,$sql,"Failure in addMainVacationRequest");
}

function addApprovedAbsenceBooking($connection,$employeeID,$absenceStartDate,$absenceEndDate,$absenceTypeID)
{
    $sql="INSERT INTO approvedAbsenceBookingTable (employeeID,absenceStartDate,approvedEndDate,absenceTypeID) "."VALUES ('".$employeeID."','".$absenceStartDate."','".$absenceEndDate."','".$absenceTypeID."');";
    return performSQL($connection,$sql,"Failure in addApprovedAbsenceBooking");
}

function addDate($connection,$date,$publicHolidayID)
{
    $sql="INSERT INTO DateTable (date,publicHolidayID) "."VALUES ('".$date."','".$publicHolidayID."');";
    return performSQL($connection,$sql,"Failure in addDate");
}

function addPublicHoliday($connection,$holidayName,$dateID)
{
    $sql="INSERT INTO publicHolidayTable (nameOfPublicHoliday,dateID) "."VALUES ('".$holidayName."','".$dateID."');";
    //todo update date to point to new public holiday.
    return performSQL($connection,$sql,"Failure in addPublicHoliday");
}

function addApprovedAbsenceBookingDate($connection,$dateID,$approvedAbsenceBookingID)
{
    $sql="INSERT INTO approvedAbsenceBookingDate (dateID,approvedAbsenceBookingID) "."VALUES ('".$dateID."','".$approvedAbsenceBookingID."');";
    return performSQL($connection,$sql,"Failure in addPublicHoliday");
}

function getCompanyRoles($connection)     
{
	$sql ="SELECT * FROM companyroletable";
	return performSQLWithResults($connection,$sql,"Failure in getCompanyRoles");
}

function getEmployees($connection)     
{
	$sql ="SELECT * FROM employeeTable";
	return performSQLWithResults($connection,$sql,"Failure in getEmployees");
}

function getAbsenceTypes($connection)     
{
	$sql ="SELECT * FROM absenceTypeTable";
	return performSQLWithResults($connection,$sql,"Failure in getAbsenceTypes");
}

function getAdHocAbsenceRequests($connection)     
{
	$sql ="SELECT * FROM adHocAbsenceRequestTable";
	return performSQLWithResults($connection,$sql,"Failure in getAdHocAbsenceRequests");
}

function getMainVacationRequests($connection)     
{
	$sql ="SELECT * FROM mainVacationRequestTable";
	return performSQLWithResults($connection,$sql,"Failure in getMainVacationRequests");
}

function getApprovedAbsenceBookings($connection)     
{
	$sql ="SELECT * FROM approvedAbsenceBookingTable";
	return performSQLWithResults($connection,$sql,"Failure in getApproveAbsenceBookings");
}

function getDates($connection)     
{
	$sql ="SELECT * FROM dateTable";
	return performSQLWithResults($connection,$sql,"Failure in getDates");
}

function getPublicHolidays($connection)     
{
	$sql ="SELECT * FROM publicHolidayTable";
	return performSQLWithResults($connection,$sql,"Failure in getPublicHolidays");
}

function getApprovedAbsenceBookingDates($connection)     
{
	$sql ="SELECT * FROM approvedAbsenceBookingDate";
	return performSQLWithResults($connection,$sql,"Failure in getApproveAbsenceBookingDates");
}


function getCompanyRolesWithFilter($connection,$filter)     
{
	return performSQLSelectWithFilter($connection,"companyRoleTable",$filter,"Failure in getCompanyRolesWithFilter");
}

function getEmployeesWithFilter($connection,$filter)     
{
	return performSQLSelectWithFilter($connection,"employeeTable",$filter,"Failure in getEmployeessWithFilter");
}

function getAbsenceTypesWithFilter($connection,$filter)     
{
	return performSQLSelectWithFilter($connection,"absenceTypeTable",$filter,"Failure in getAbsencTypesWithFilter");
}

function getAdHocAbsenceRequestsWithFilter($connection,$filter)     
{
	return performSQLSelectWithFilter($connection,"adHocAbsenceRequestTable",$filter,"Failure in getAdHocAbsenceRequestsWithFilter");
}

function getMainVacationRequestsWithFilter($connection,$filter)     
{
	return performSQLSelectWithFilter($connection,"mainVacationRequestTable",$filter,"Failure in getMainVacationRequestsWithFilter");
}

function getApprovedAbsenceBookingsWithFilter($connection,$filter)     
{
	return performSQLSelectWithFilter($connection,"approvedAbsenceBookingTable",$filter,"Failure in getApprovedAbsenceBookingsWithFilter");
}

function getDatesWithFilter($connection,$filter)     
{
	return performSQLSelectWithFilter($connection,"dateTable",$filter,"Failure in getDatesWithFilter");
}

function getPublicHolidaysWithFilter($connection,$filter)     
{
	return performSQLSelectWithFilter($connection,"publicHolidayTable",$filter,"Failure in getPublicHolidaysWithFilter");
}

function getApprovedAbsenceBookingDatesWithFilter($connection,$filter)     
{
	return performSQLSelectWithFilter($connection,"approvedAbsenceBookingDate",$filter,"Failure in getApprovedAbsenceBookingDatesWithFilter");
}




$connection 				= connectToSql("localhost","root","root");
createNewDatabase($connection);

$roleCashierID 				= addCompanyRole($connection,"Cashier",2);
$roleManagerID 				= addCompanyRole($connection,"Manager",3);
$employeeSamID 				= addEmployee ($connection,"Sam","samgilbertuk@hotmail.com","zaq12wsx","28/11/1995",22,$roleCashierID);
$employeeJasonID 			= addEmployee ($connection,"Jason","jasongilbertuk@hotmail.com","zaq12wsx","28/11/1990",30,$roleManagerID);
$absenceTypeAnnualLeaveID 	= addAbsenceType($connection,"Annual Leave","TRUE","TRUE");
$absenceTypeSickLeaveID 	= addAbsenceType($connection,"Sick Leave","FALSE","FALSE");
$adHocAbsenceRequest1ID 	= addAdHocAbsenceRequest($connection,$employeeSamID,"21/01/2014","25/01/2014",$absenceTypeAnnualLeaveID);
$adHocAbsenceRequest2ID 	= addAdHocAbsenceRequest($connection,$employeeJasonID,"12/04/2014","19/04/2014",$absenceTypeSickLeaveID);
$mainVacationRequest1ID 	= addMainVacactionRequest($connection,$employeeJasonID,"12/01/2014","19/01/2014","12/02/2014","19/02/2014");
$approvedAbsenceBooking1ID	= addApprovedAbsenceBooking($connection,$employeeJasonID,"25/12/2014","25/01/2014",$absenceTypeAnnualLeaveID);
$date25122014ID 			= addDate($connection,"25/12/2014",NULL);
$christmasID 				= addPublicHoliday($connection,"Christmas",$date25122014ID);
$approvedAbsenceBookingDate = addApprovedAbsenceBookingDate($connection,$date25122014ID,$approvedAbsenceBooking1ID);

$companyRoles 				= getCompanyRoles($connection);
$employees 					= getEmployees($connection);
$absenceTypes 				= getAbsenceTypes($connection);
$adHocAbsenceRequests 		= getAdHocAbsenceRequests($connection);
$mainVacationRequests 		= getMainVacationRequests($connection);
$approvedAbsenceBookings	= getApprovedAbsenceBookings($connection);
$dates 						= getDates($connection);
$publicHolidays 			= getPublicHolidays($connection);
$approvedAbsenceBookingDates= getApprovedAbsenceBookingDates($connection);

$filter["roleName"] = "Cashier";
$filter["minimumStaffingLevel"] = 2;

$results = getCompanyRolesWithFilter($connection,$filter);

unset($filter);
$filter["employeeID"] = "2";
$results = getEmployeesWithFilter($connection,$filter);
print_r($results);

mysqli_close($connection);  

?>




