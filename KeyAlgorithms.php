<?php
/* -----------------------------------------------------------------------------
 * Function IsPublicHoliday
 *
 * This function creates checks the supplied date to determine whether or not
 * the date is a public holiday.
 *
 * $date (string) string date in the form YYYY-MM-DD.
 * @return (bool)  TRUE if date supplied is a public holiday, otherwise FALSE.
 * -------------------------------------------------------------------------- */
function isPublicHoliday($date)
{
    //Assume false. Will set to true if it is a public holiday
    $result = FALSE; 

    // Obtain the date record that matches this date from the Date table 
    $filter[DATE_TABLE_DATE] = $date;
    $dates = RetrieveDates($filter);
    
    //Q.Was the date found?
    if ($dates <> NULL)
    {
        //Yes. Q.Does the date record have a public holiday ID set?
        if ($dates[0][DATE_TABLE_PUBLIC_HOL_ID]<> NULL)
        {
            //Yes. Date is therefore a public holiday.
            $result = TRUE;
        }
    }
    return $result;
}

/* -----------------------------------------------------------------------------
 * Function IsWeekend
 *
 * This function creates checks the supplied date to determine whether or not
 * the date is on a Saturday or Sunday..
 *
 * $date (string) string date in the form YYYY-MM-DD.
 * @return (bool)  TRUE if date supplied lies on a weekend. FALSE otherwise.
 * -------------------------------------------------------------------------- */
function isWeekend($date)
{
    //Assume false. Will set to true if it is a weekend
    $result = FALSE;
    
    //Convert the date supplied to a lower case textual day of the week.
    $date = strtotime($date);
    $date = date("l", $date);
    $date = strtolower($date);
    
    if (($date == "saturday" )|| ($date == "sunday"))
    {
        $result = TRUE;
    }
    return $result;
}



/* -----------------------------------------------------------------------------
 * Function CalculateAnnualLeaveRequired
 *
 * This function calculates how many days of annual leave will be needed to 
 * book a period of time between two dates.
 *
 * $startDate (string) string date in the form YYYY-MM-DD.
 * $endDate   (string) string date in the form YYYY-MM-DD.
 * $absenceType (array) array of key value pairs from the absence type record.
 * @return (int)  Number of days annual leave required for this period. Will be
 *                zero if no leave is required.
 * -------------------------------------------------------------------------- */
function CalculateAnnualLeaveRequired($startDate,$endDate,$absenceType)
{
    //Assume no leave is required. Will increment this in the function.
    $annualLeaveRequired = 0;
           
    
    //Q.Does the absence type supplied use annual leave?
    if ($absenceType[ABS_USES_LEAVE] == TRUE)
    {
        //Y.We need to calulate the leave required. First convert dates supplied
        //  into times.
        $startTime = strtotime($startDate);
        $endTime = strtotime($endDate);

        // Loop between timestamps, 24 hours at a time.
        // Note that 86400 = 24 hours in second.
        for ($i = $startTime; $i <= $endTime; $i = $i + 86400) 
        {
            //Format the time into a date string
            $thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
            
            if (!isWeekend($thisDate))
            {
                if (!isPublicHoliday($thisDate) )
                {
                    //Date is not a weekend or public holiday, so increment
                    $annualLeaveRequired = $annualLeaveRequired + 1;
                }
            }
        }
    }
    return $annualLeaveRequired;
}


/* ----------------------------------------------------------------------------
 * Function CalculateEmployeeLeaveTaken
 *
 * This function calculates the number of days annual leave that has been 
 * approved for the employee.
 *
 * $employeeID(int) ID of the employee to calculate this for.
 *
 * @return (int) Number of days taken by the employee. 
 * -------------------------------------------------------------------------- */
/*TODO DELETE THIS function CalculateEmployeeLeaveTaken($employeeID)
{
    $conn = $GLOBALS["connection"];
    $return = NULL;
    $sql = "SELECT COUNT(DateTable.date) FROM DateTable ".
            "WHERE DateTable.publicHolidayID IS NULL ".
            "AND DateTable.dateID IN (SELECT ".
            "ApprovedAbsenceBookingDate.dateID FROM approvedAbsenceBookingDate ".
            "WHERE approvedAbsenceBookingDate.approvedAbsenceBookingID IN ".
            "(Select ApprovedAbsenceBookingID FROM approvedAbsenceBookingTable ".
            "JOIN absenceTypeTable WHERE ".
            "approvedAbsenceBookingTable.absenceTypeID = absenceTypeTable.absenceTypeID".
            " AND approvedAbsenceBookingTable.employeeID = ".$employeeID.
            " AND absenceTypeTable.usesAnnualLeave = 1)) AND DAYOFWEEK(DateTable.date)<>1".
            " AND DAYOFWEEK(DateTable.date)<>7 ;";
                       
    $result = mysqli_query($conn, $sql);
    if (!$result) 
    {
        error_log("PerformSQL failed. Sql = $sql");
    }
    else
    {
        $data= mysqli_fetch_array($result);
        $return = $data[0];
    }
    return $return;
}*/

/* ----------------------------------------------------------------------------
 * Function CalculateRemainingAnnualLeave
 *
 * This function calculates the number of days annual leave that has been 
 * approved for the employee.
 *
 * $employeeID(int) ID of the employee to calculate this for.
 *
 * @return (int) Number of days taken by the employee. 
 * -------------------------------------------------------------------------- */
function CalculateRemaininAnnualLeave($employeeID)
{
    //Assume no leave is remaining. Will increment this in the function.
    $annualLeaveRemaining = 0;
    
    $employee = RetrieveEmployeeByID($employeeID);
    if ($employee)
    {
        $annualLeaveRemaining = $employee[EMP_LEAVE_ENTITLEMENT];
        
        $filter[APPR_ABS_EMPLOYEE_ID] = $employeeID;
        $bookings = RetrieveApprovedAbsenceBookings($filter);
        
        if ($bookings)
        {
            foreach ($bookings as $booking)
            {
                $startDate   = $booking[APPR_ABS_START_DATE]; 
                $endDate     = $booking[APPR_ABS_END_DATE];
                $absenceType = $booking[APPR_ABS_ABS_TYPE_ID];
                
                $leaveRequired = CalculateAnnualLeaveRequired($startDate,
                                                              $endDate,
                                                              $absenceType);
                
                $annualLeaveRemaining = $annualLeaveRemaining - $leaveRequired;
            }
        }
        
    }
    
    return $annualLeaveRemaining;
}

/* ----------------------------------------------------------------------------
 * Function HasSufficientAnnualLeave
 *
 * This function will determine whether an employee has sufficent annual leave
 * available to cover the period of absence between start date and end date, 
 * taking into account the absence type of the request.
 *
 * $employeeID(int) ID of the employee to calculate this for.
 * $startDate(string) start date of the request in the form YYYY-MM-DD.
 * $endDate(string) end date of the request in the form YYYY-MM-DD.
 * $absenceTypeID(int) ID of the absence type of this request.
 *
 * @return (bool) TRUE means sufficent days to cover the requested period.
 *                FALSE means insufficent days to cover the requested period. 
 * -------------------------------------------------------------------------- */
function HasSufficentAnnualLeave($employeeID,$startDate,$endDate,$absenceTypeID)
{
	$hasSufficentLeave = FALSE;
	$employeesAvailableLeave = CalculateRemainingAnnualLeave($employeeID);
	$absenceType = RetieveAbsenceTypeByID($absenceTypeID);
	
	$amountOfLeaveNeeded = CalculateAnnualLeaveRequired($startDate,$endDate,$absenceType);
	
	if ($amountOfLeaveNeeded <= $employeesAvailableLeave)
	{
		$hasSufficentLeave = TRUE;
	}
	
	return $hasSufficentLeave;
}

/* ----------------------------------------------------------------------------
 * Function SufficentStaffInRoleToGrantRequest
 *
 * This function will determine whether there are sufficent staff within a role
 * to allow the employee to book the period of startDate to endDate as absence.
 *
 * $employeeID(int) ID of the employee to calculate this for.
 * $startDate(string) start date of the request in the form YYYY-MM-DD.
 * $endDate(string) end date of the request in the form YYYY-MM-DD.
 *
 * @return (bool) TRUE means there are sufficent staff to grant the request.
 *                FALSE means there are insufficent staff to grant the request. 
 * -------------------------------------------------------------------------- */
function SufficentStaffInRoleToGrantRequest($employeeID,$startDate,$endDate)
{
	$sufficentStaffInRole = TRUE;
	
	$employee = RetrieveEmployeeByID($employeeID);
	$employeeRole = RetrieveCompanyRoleByID($employee[EMP_COMPANY_ROLE]);

	$minimumStaffingLevel = $employeeRole[COMP_ROLE_MIN_STAFF];
	
	$filter[EMP_COMPANY_ROLE] = $employee[EMP_COMPANY_ROLE];
    $employeesInRole = RetrieveEmployees($filter);
     
    $numEmployeesInRole = count($employeesInRole);

	$tempDate = strtotime($startDate);
    $endTime = strtotime($endDate);

	$underMinimumStaffing = FALSE;
	
	while ($tempDate <= $endTime AND underMinimumStaffing == FALSE)
	{
		$tempStaffingLevel = $numEmployeesInRole;
		$strDate = date('Y-m-d', $tempDate); // 2010-05-01, 2010-05-02, etc
	    
	    unset($filter);
    	$filter[DATE_TABLE_DATE] = $strDate;
    	$dateRecords = RetrieveDates($filter);
	
		$dateID = $dateRecords[0][DATE_TABLE_DATE_ID];
        
        unset($filter);
    	$filter[APPR_ABS_BOOK_DATE_DATE_ID] = $dateID;
        $bookingsForDate = RetrieveApprovedAbsenceBookingDates($filter);
        
        foreach ($bookingsForDate as $bookingDate)
        {
        	$absenceBooking = RetrieveApprovedAbsenceBookingByID($bookingDate[APPR_ABS_BOOK_DATE_ABS_BOOK_ID]);
        	$staffMember = RetrieveEmployeeByID($absenceBooking[[APPR_ABS_EMPLOYEE_ID]);
            
            if ($employee[EMP_COMPANY_ROLE] == $staffMember[EMP_COMPANY_ROLE])
            {
            	$tempStaffingLevel = $tempStaffingLevel - 1;
            }
        }
        
        if ($tempStaffingLevel <= $minimumStaffingLevel)
        {
        	$underMinimumStaffing = TRUE;
        	$sufficentStaffInRole = FALSE;
        }
        
		//move temp date onto the next day. Note tempdate is in seconds.
        $tempDate = $tempDate + + 86400; //86400 = 60 seconds * 60 minutes * 24 hours.
	}
	return $sufficentStaffInRole;
}	


/* ----------------------------------------------------------------------------
 * Function ProcessAbsenceRequest
 *
 * This function will todo.
 *
 * $employeeID(int) ID of the employee that this absence request is for.
 * $startDate(string) start date of the request in the form YYYY-MM-DD.
 * $endDate(string) end date of the request in the form YYYY-MM-DD.
 * $absenceTypeID(int) ID of the absence type of this request.
 *
 * @return (bool) TRUE means the booking was approved.
 *                FALSE means the booking was denied. 
 * -------------------------------------------------------------------------- */
 function ProcessAbsenceRequest($employeeID,$startDate,$endDate,$absenceTypeID)
{
	$bookingApproved = TRUE;
	if (HasSufficentAnnualLeave($employeeID,$startDate,$endDate,$absenceTypeID) == FALSE)
	{
		//todo sendEmailToEmployee( “Request Denied. Insufficient annual leave remaining.”)
        //todo Remove entry from AdHoc Request Table
        $bookingApproved = FALSE; 
	}
	else
	{
		if (SufficentStaffInRoleToGrantRequest($employeeID,$startDate,$endDate)
		{
         	//todo Create new entry in Approved Absence Booking Date Table
            //todo Remove entry from AdHoc Request Table
		    //todo sendEmailToEmployee( “Request Approved.”)
            $bookingApproved = TRUE;
		}
		else
		{
			$absenceType = RetrieveAbsenceTypeByID($absenceTypeID);
			if ($absenceType[ABS_TYPE_CAN_BE_DENIED])
			{
				$bookingApproved = FALSE;
				//todo sendEmailToEmployee( “Request Denied. Request would leave role below minimum staffing level.”)
                //todo Remove entry from AdHoc Request Table
            }
            else
            {                   
            	//todo sendEmailToManager(“Warning: below minimum staffing levels.”)
                //todo sendEmailToEmployee( “Request Approved.”)
                //todo Create new entry in Approved Absence Booking Date Table
                //todo Create new entry in Approved Absence Booking Table
                $bookingApproved = TRUE;
            }
        }
    }
           
    return $bookingApproved;
}










?>
