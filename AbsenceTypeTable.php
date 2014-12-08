<?php

define ("ABSENCE_TYPE_TABLE",               "absenceTypeTable");

define ("ABS_TYPE_ID",                      "absenceTypeID");
define ("ABS_TYPE_NAME",                    "absenceTypeName");
define ("ABS_TYPE_USES_LEAVE",              "usesAnnualLeave");
define ("ABS_TYPE_CAN_BE_DENIED",           "canBeDenied");


function createAbsenceTypeTable($connection)
{
   $sql= "CREATE TABLE IF NOT EXISTS `mydb`.`absenceTypeTable` (
         `absenceTypeID` INT NOT NULL AUTO_INCREMENT,
         `absenceTypeName` VARCHAR(20) NOT NULL,
         `usesAnnualLeave` TINYINT(1) NOT NULL,
         `canBeDenied` TINYINT(1) NOT NULL,
         PRIMARY KEY (`absenceTypeID`));";
    performSQL($connection,$sql);
}


function CreateAbsenceType($connection,
						   $absenceTypeName,
                           $usesAnnualLeave,
                           $canBeDenied)
{
    $absenceType[ABS_TYPE_ID]               = NULL;
    $absenceType[ABS_TYPE_NAME]             = $absenceTypeName;
    $absenceType[ABS_TYPE_USES_LEAVE]       = $usesAnnualLeave;
    $absenceType[ABS_TYPE_CAN_BE_DENIED]    = $canBeDenied;
    
   	$success = sqlInsertAbsenceType($connection,$absenceType);
   	
   	if (! $success )
	{
		error_log ("Failed to create absence type. ".print_r($absenceType));
		$absenceType = NULL;
	}
	return $absenceType;
}

function sqlInsertAbsenceType ($connection,&$absenceType)
{
    $sql="INSERT INTO absenceTypeTable (absenceTypeName,usesAnnualLeave,canBeDenied) "
            . "VALUES ('".$absenceType[ABS_TYPE_NAME]."','"
            .$absenceType[ABS_TYPE_USES_LEAVE]."','"
            .$absenceType[ABS_TYPE_CAN_BE_DENIED]."');";
    
    $absenceType[ABS_TYPE_ID] = performSQLInsert($connection,$sql);
    return $absenceType[ABS_TYPE_ID] <> 0;
}

function RetrieveAbsenceTypes($connection,$filter=NULL)     
{
	return performSQLSelect($connection,ABSENCE_TYPE_TABLE,$filter);
}

function UpdateAbsenceType ($connection,$fields)
{
    return performSQLUpdate($connection,ABSENCE_TYPE_TABLE,
                            ABS_TYPE_ID,$fields); 	
}

function DeleteAbsenceType($connection,$ID)
{
    $sql ="DELETE FROM absenceTypeTable WHERE absenceTypeID=".$ID.";";
    
    return performSQL($connection,$sql);
}

?>
