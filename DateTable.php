<?php

define ("DATE_TABLE",                       "dateTable");

define ("DATE_TABLE_DATE_ID",               "dateID");
define ("DATE_TABLE_DATE",                  "date");
define ("DATE_TABLE_PUBLIC_HOL_ID",         "publicHolidayID");

function createDateTable($connection)
{
  $sql = "CREATE TABLE IF NOT EXISTS `mydb`.`DateTable` (
         `dateID` INT NULL AUTO_INCREMENT,
         `date` DATE NOT NULL,
         `publicHolidayID` INT NULL,
         PRIMARY KEY (`dateID`));";
    performSQL($connection,$sql);
}


function CreateDate($connection,
					$dateParam,
                    $publicHolidayID)
        
{   $date[DATE_TABLE_DATE_ID]               = NULL;
    $date[DATE_TABLE_DATE]                  = $dateParam;
    $date[DATE_TABLE_PUBLIC_HOL_ID]         = $publicHolidayID; 
    
    $success = sqlInsertDate($connection,$date);
	if (! $success )
	{
		error_log ("Failed to create Date. ".print_r($date));
		$date = NULL;
	}
	
    return $date;
}

function sqlInsertDate($connection,&$date)
{
    $sql="INSERT INTO DateTable (date,publicHolidayID) ".
         "VALUES ('".$date[DATE_TABLE_DATE]."',";
    
    if ($date[DATE_TABLE_PUBLIC_HOL_ID] <> NULL)
    {
          $sql = $sql."'".$date[DATE_TABLE_PUBLIC_HOL_ID]."');";
    }
    else 
    {
        $sql = $sql."NULL);";
    }
    
    $date[DATE_TABLE_DATE_ID] = performSQLInsert($connection,$sql);
    return $date[DATE_TABLE_DATE_ID]<>0;
}

function RetrieveDates($connection,$filter=NULL)     
{
	return performSQLSelect($connection,DATE_TABLE,$filter);
}

function UpdateDate($connection,$fields)
{
    return performSQLUpdate($connection,DATE_TABLE,
                            DATE_TABLE_DATE_ID,$fields); 	
}


function DeleteDate($connection,$ID)
{
    $sql ="DELETE FROM dateTable WHERE dateID=".$ID.";";
    
    return performSQL($connection,$sql);
}

?>
