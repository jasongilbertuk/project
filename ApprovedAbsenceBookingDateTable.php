<?php

define ("APPROVED_ABSENCE_BOOKING_DATE",    "approvedAbsenceBookingDate");

define ("APPR_ABS_BOOK_DATE_ID",            "approvedAbsenceBookingDateID");
define ("APPR_ABS_BOOK_DATE_DATE_ID",       "dateID");
define ("APPR_ABS_BOOK_DATE_ABS_BOOK_ID",   "approvedAbsenceBookingID");

function createApprovedAbsenceDateTable($connection)
{
   $sql="CREATE TABLE IF NOT EXISTS `mydb`.`approvedAbsenceBookingDate` (
  `approvedAbsenceBookingDateID` INT NOT NULL AUTO_INCREMENT,
  `dateID` INT NOT NULL,
  `approvedAbsenceBookingID` INT NOT NULL,
  PRIMARY KEY (`approvedAbsenceBookingDateID`),
  INDEX `fk_approvedAbsenceBookingDate_Date1_idx` (`dateID` ASC),
  INDEX `fk_approvedAbsenceBookingDate_approvedAbsenceBooking1_idx` 
  (`approvedAbsenceBookingID` ASC),
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
   
    performSQL($connection,$sql);
}



function CreateApprovedAbsenceBookingDate($connection,
										  $dateID,
                                          $approvedAbsenceBookingID)
{
    $bookingDate[APPR_ABS_BOOK_DATE_ID]          = NULL;
    $bookingDate[APPR_ABS_BOOK_DATE_DATE_ID]     = $dateID;
    $bookingDate[APPR_ABS_BOOK_DATE_ABS_BOOK_ID] = $approvedAbsenceBookingID;
    
    $success = sqlInsertApprovedAbsenceBookingDate($connection,$bookingDate);
	if (! $success )
	{
		error_log ("Failed to create Approved Absence Booking Date ".print_r($bookingDate));
		$bookingDate = NULL;
	}
    return $bookingDate;
}

function sqlInsertApprovedAbsenceBookingDate($connection,&$absenceBookingDate)
{
    $sql="INSERT INTO approvedAbsenceBookingDate ".
         "(dateID,approvedAbsenceBookingID) ".
         "VALUES ('".
            $absenceBookingDate[APPR_ABS_BOOK_DATE_DATE_ID]."','".
            $absenceBookingDate[APPR_ABS_BOOK_DATE_ABS_BOOK_ID]."');";
    
    $absenceBookingDate[APPR_ABS_BOOK_DATE_ID] = performSQLInsert($connection,$sql);
   return $absenceBookingDate[APPR_ABS_BOOK_DATE_ID] <> 0;
}


function RetrieveApprovedAbsenceBookingDates($connection,$filter=NULL)     
{
	return performSQLSelect($connection,APPROVED_ABSENCE_BOOKING_DATE,$filter);
}

function UpdateApprovedAbsenceBookingDate($connection,$fields)
{
    return performSQLUpdate($connection,APPROVED_ABSENCE_BOOKING_DATE,
                            APPR_ABS_BOOK_DATE_ID,$fields); 	
}

function DeleteApprovedAbsenceBookingDate($connection,$ID)
{
    $sql ="DELETE FROM approvedAbsenceBookingDate WHERE approvedAbsenceBookingDateID=".$ID.";";
    
    return performSQL($connection,$sql);
}

?>
