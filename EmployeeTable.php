<?php

define ("EMPLOYEE_TABLE",                   "employeeTable");
define ("EMP_ID",                           "employeeID");
define ("EMP_NAME",                         "employeeName");
define ("EMP_EMAIL",                        "emailAddress");
define ("EMP_PASSWORD",                     "password");
define ("EMP_DATEJOINED",                   "dateJoinedTheCompany");
define ("EMP_LEAVE_ENTITLEMENT",            "annualLeaveEntitlement");
define ("EMP_MAIN_VACATION_REQ_ID",         "mainVacationRequestID");
define ("EMP_COMPANY_ROLE",                 "companyRole_companyRoleID");

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
    
     performSQL($connection,$sql);
}

function CreateEmployee($connection,
						$employeeName,
                        $emailAddress,
                        $password,
                        $dateJoinedTheCompany,
                        $annualLeaveEntitlement,
                        $mainVacationRequestID,
                        $companyRoleID)
{
    $employee[EMP_ID]                       = NULL;
    $employee[EMP_NAME]                     = $employeeName;
    $employee[EMP_EMAIL]                    = $emailAddress;
    $employee[EMP_PASSWORD]                 = $password;
    $employee[EMP_DATEJOINED]               = $dateJoinedTheCompany;
    $employee[EMP_LEAVE_ENTITLEMENT]        = $annualLeaveEntitlement;
    $employee[EMP_MAIN_VACATION_REQ_ID]     = $mainVacationRequestID;
    $employee[EMP_COMPANY_ROLE]             = $companyRoleID;
    
    $success = sqlInsertEmployee($connection,$employee);
	if (! $success )
	{
		error_log ("Failed to create Employee. ".print_r($employee));
		$employee = NULL;
	}
    return $employee;
}


function sqlInsertEmployee ($connection,&$employee)
{
    //TODO Add check to ensure that the Company Role ID exists in the Database.
    
    $sql="INSERT INTO EmployeeTable (employeeName,emailAddress,password,".
    	 "annualLeaveEntitlement,dateJoinedTheCompany,companyRole_companyRoleID) ".
    	 "VALUES ('".$employee[EMP_NAME]."','".$employee[EMP_EMAIL]."','"
    	 .$employee[EMP_PASSWORD]."','".$employee[EMP_LEAVE_ENTITLEMENT].
    	 "','".$employee[EMP_DATEJOINED]."','".$employee[EMP_COMPANY_ROLE]."');";
    
    $employee[EMP_ID] = performSQLInsert($connection,$sql);
    return $employee[EMP_ID] <> 0;
}

function RetrieveEmployees($connection,$filter=NULL)     
{
	return performSQLSelect($connection,EMPLOYEE_TABLE,$filter);
}

function UpdateEmployee ($connection,$fields)
{
    return performSQLUpdate($connection,EMPLOYEE_TABLE,EMP_ID,$fields); 	
}

function DeleteEmployee($connection,$ID)
{
    //TODO Delete all main vaction requests with matching employee id
    //TODO delete all AdHoc absence requests with matching employee id
    //TODO delete all approved absence bookings with matching employee id
    $sql ="DELETE FROM employeeTable WHERE employeeID=".$ID.";";
    
    return performSQL($connection,$sql);
}

?>
