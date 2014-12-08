<?php

define ("PUBLIC_HOLIDAY_TABLE",             "publicHolidayTable");

define ("PUB_HOL_ID",                       "publicHolidayID");
define ("PUB_HOL_NAME",                     "nameOfPublicHoliday");
define ("PUB_HOL_DATE_ID",                  "dateID");

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
    performSQL($connection,$sql);
}

function CreatePublicHoliday($connection,
							 $nameOfPublicHoliday,
                             $dateID)
{
    $publicHoliday[PUB_HOL_ID]              = NULL;                 
    $publicHoliday[PUB_HOL_NAME]            = $nameOfPublicHoliday;
    $publicHoliday[PUB_HOL_DATE_ID]         = $dateID;

    $success = sqlInsertPublicHoliday($connection, $publicHoliday);

    if (! $success )
	{
		error_log ("Failed to create public holiday. ".print_r($publicHoliday));
		$publicHoliday = NULL;
	}
    return $publicHoliday;
}

function sqlInsertPublicHoliday($connection,&$publicHoliday)
{
    $sql="INSERT INTO publicHolidayTable (nameOfPublicHoliday,dateID) ".
         "VALUES ('".$publicHoliday[PUB_HOL_NAME].
         "','".$publicHoliday[PUB_HOL_DATE_ID]."');";
    //todo update date to point to new public holiday.
    $publicHoliday[PUB_HOL_ID] = performSQLInsert($connection,$sql);
    return $publicHoliday[PUB_HOL_ID] <> 0;
    
}

function RetrievePublicHolidays($connection,$filter=NULL)     
{
	return performSQLSelect($connection,PUBLIC_HOLIDAY_TABLE,$filter);
}

function UpdatePublicHoliday($connection,$fields)
{
    return performSQLUpdate($connection,PUBLIC_HOLIDAY_TABLE,
                            PUB_HOL_ID,$fields); 	
}

function DeletePublicHoliday($connection,$ID)
{
    $sql ="DELETE FROM publicHolidayTable WHERE publicHolidayID=".$ID.";";
    
    return performSQL($connection,$sql);
}

?>