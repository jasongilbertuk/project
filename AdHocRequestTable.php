<?php

define ("ADHOC_ABSENCE_REQUEST_TABLE",      "adHocAbsenceRequestTable");

define ("AD_HOC_REQ_ID",                    "adHocAbsenceRequestID");
define ("AD_HOC_EMP_ID",                    "employeeID");
define ("AD_HOC_START",                     "startDate");
define ("AD_HOC_END",                       "endDate");
define ("AD_HOC_ABSENCE_TYPE_ID",           "absenceTypeID");



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
    
    performSQL($connection,$sql);
}


function CreateAdHocAbsenceRequest($connection,
								   $employeeID,
                                   $startDate,
                                   $endDate,
                                   $absenceTypeID)
{
	$request[AD_HOC_REQ_ID]             = NULL;
	$request[AD_HOC_EMP_ID]             = $employeeID;
	$request[AD_HOC_START]              = $startDate;
	$request[AD_HOC_END]                = $endDate;
	$request[AD_HOC_ABSENCE_TYPE_ID]    = $absenceTypeID;
	
	$success = sqlInsertAdHocAbsenceRequest($connection,$request);
	
	if (! $success )
	{
		error_log ("Failed to create Ad Hoc Absence Request. ".print_r($request));
		$request = NULL;
	}
	return $request;   
}

function sqlInsertAdHocAbsenceRequest ($connection,&$adHocAbsenceRequest)
{
    $sql="INSERT INTO adHocAbsenceRequestTable (employeeID,startDate,endDate,absenceTypeID) "
            . "VALUES ('".$adHocAbsenceRequest[AD_HOC_EMP_ID].
            "','".$adHocAbsenceRequest[AD_HOC_START]."','".
            $adHocAbsenceRequest[AD_HOC_END].
            "','".$adHocAbsenceRequest[AD_HOC_ABSENCE_TYPE_ID]."');";
    $adHocAbsenceRequest[AD_HOC_REQ_ID] = performSQLInsert($connection,$sql);
    return $adHocAbsenceRequest[AD_HOC_REQ_ID] <> 0;
}

function RetrieveAdHocAbsenceRequests($connection,$filter=NULL)     
{
	return performSQLSelect($connection,ADHOC_ABSENCE_REQUEST_TABLE,$filter);
}

function UpdateAdHocAbsenceRequest ($connection,$fields)
{
    return performSQLUpdate($connection,ADHOC_ABSENCE_REQUEST_TABLE,
                            AD_HOC_REQ_ID,$fields); 	
}

function DeleteAdHocAbsenceRequest($connection,$ID)
{
    $sql ="DELETE FROM adHocAbsenceRequestTable WHERE adHocAbsenceRequestID=".$ID.";";
    
    return performSQL($connection,$sql);
}

?>

