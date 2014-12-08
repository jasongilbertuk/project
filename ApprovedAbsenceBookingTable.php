<?php
define ("APPROVED_ABSENCE_BOOKING_TABLE",   "approvedAbsenceBookingTable");

define ("APPR_ABS_BOOKING_ID",              "approvedAbsenceBookingID");
define ("APPR_ABS_EMPLOYEE_ID",             "employeeID");
define ("APPR_ABS_START_DATE",              "absenceStartDate");
define ("APPR_ABS_END_DATE",                "approvedEndDate");
define ("APPR_ABS_ABS_TYPE_ID",             "absenceTypeID");


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
    
    performSQL($connection,$sql);
}


function CreateApprovedAbsenceBooking($connection,
									  $employeeID,
                                      $absenceStartDate,
                                      $absenceEndDate,
                                      $absenceTypeID)
{
    $booking[APPR_ABS_BOOKING_ID]           = NULL;
    $booking[APPR_ABS_EMPLOYEE_ID]          = $employeeID;
    $booking[APPR_ABS_START_DATE]           = $absenceStartDate;
    $booking[APPR_ABS_END_DATE]             = $absenceEndDate;
    $booking[APPR_ABS_ABS_TYPE_ID]          = $absenceTypeID;
 
 	$success = sqlInsertApprovedAbsenceBooking($connection,$booking);
	if (! $success )
	{
		error_log ("Failed to create Approved Absence Booking. ".print_r($booking));
		$booking = NULL;
	}
    return $booking;
}


function sqlInsertApprovedAbsenceBooking($connection,&$absenceBooking)
{
    $sql="INSERT INTO approvedAbsenceBookingTable ".
         "(employeeID,absenceStartDate,approvedEndDate,absenceTypeID) ".
         "VALUES ('".
            $absenceBooking[APPR_ABS_EMPLOYEE_ID]."','".
            $absenceBooking[APPR_ABS_START_DATE]."','".
            $absenceBooking[APPR_ABS_END_DATE]."','".
            $absenceBooking[APPR_ABS_ABS_TYPE_ID]."');";
    
    $absenceBooking[APPR_ABS_BOOKING_ID] = performSQLInsert($connection,$sql);
    return $absenceBooking[APPR_ABS_BOOKING_ID] <> 0;

}

function RetrieveApprovedAbsenceBookings($connection,$filter=NULL)     
{
	return performSQLSelect($connection,APPROVED_ABSENCE_BOOKING_TABLE,$filter);
}


function UpdateApprovedAbsenceBooking($connection,$fields)
{
    return performSQLUpdate($connection,APPROVED_ABSENCE_BOOKING_TABLE,
                            APPR_ABS_BOOKING_ID,$fields); 	
}

function DeleteApprovedAbsenceBooking($connection,$ID)
{
    $sql ="DELETE FROM approvedAbsenceBookingTable WHERE approvedAbsenceBookingID=".$ID.";";
    
    return performSQL($connection,$sql);
}

?>
