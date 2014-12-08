<?php
define ("MAIN_VACATION_REQUEST_TABLE",      "mainVacationRequestTable");

define ("MAIN_VACATION_REQ_ID",             "mainVacationRequestID");
define ("MAIN_VACATION_EMP_ID",             "employeeID");
define ("MAIN_VACATION_1ST_START",          "firstChoiceStartDate");
define ("MAIN_VACATION_1ST_END",            "firstChoiceEndDate");
define ("MAIN_VACATION_2ND_START",          "secondChoiceStartDate");
define ("MAIN_VACATION_2ND_END",            "secondChoiceEndDate");

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
    
    performSQL($connection,$sql);
}

function CreateMainVactionRequest($connection,
								  $employeeID,
                                  $firstChoiceStartDate,
                                  $firstChoiceEndDate,
                                  $secondChoiceStartDate,
                                  $secondChoiceEndDate)
{
	$request[MAIN_VACATION_REQ_ID]      = NULL;
	$request[MAIN_VACATION_EMP_ID]      = $employeeID;
	$request[MAIN_VACATION_1ST_START]   = $firstChoiceStartDate;
	$request[MAIN_VACATION_1ST_END]     = $firstChoiceEndDate;
	$request[MAIN_VACATION_2ND_START]   = $secondChoiceStartDate;
	$request[MAIN_VACATION_2ND_END]     = $secondChoiceEndDate;
	
	$success = sqlInsertMainVacationRequest($connection,$request);
	if (! $success )
	{
		error_log ("Failed to create main vacation request. ".print_r($request));
		$role = NULL;
	}
	
	return $request;
    
}

function sqlInsertMainVacationRequest($connection,&$request)
{
    //TODO Add check to ensure that the Employee ID exists in the Database.
    $sql="INSERT INTO mainVacationRequestTable (employeeID,firstChoiceStartDate,".
    	"firstChoiceEndDate,secondChoiceStartDate,secondChoiceEndDate) ".
    	"VALUES ('".$request[MAIN_VACATION_EMP_ID].
    	"','".$request[MAIN_VACATION_1ST_START]."','".$request[MAIN_VACATION_1ST_END].
    	"','".$request[MAIN_VACATION_2ND_START]."','".$request[MAIN_VACATION_2ND_END]."');";
    $request[MAIN_VACATION_REQ_ID] = performSQLInsert($connection,$sql);
    return $request[MAIN_VACATION_REQ_ID] <> 0;
    
}

function RetrieveMainVacationRequests($connection,$filter=NULL)     
{
	return performSQLSelect($connection,MAIN_VACATION_REQUEST_TABLE,$filter);
}

function UpdateMainVacactionRequest($connection,$fields)
{
    return performSQLUpdate($connection,MAIN_VACATION_REQUEST_TABLE,
                            MAIN_VACATION_REQ_ID,$fields); 	
}


function DeleteMainVacationRequest($connection,$ID)
{
    $sql ="DELETE FROM mainVacationRequestTable WHERE mainVacationRequestID=".$ID.";";
    
    return performSQL($connection,$sql);
}

?>

