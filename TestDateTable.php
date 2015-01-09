<?php

function testDateTable()
{
	//CREATE
	$role = CreateCompanyRole("Cashier",8);
  	$employee = CreateEmployee( "Jason Gilbert",
        	                   "jasongilbertuk@hotmail.com",
              	                   "Zaq12wsx",
                	           "1990-11-28",
                    	           25,
                        	   NULL,
                           	   $role[COMP_ROLE_ID]);
  
                           		
    $absenceType = CreateAbsenceType("Annual Leave",true,true);
    
    $employee = RetrieveEmployeeByID($employee[EMP_ID]);  
   $approvedAbsenceBooking = CreateApprovedAbsenceBooking($employee[EMP_ID],
                                       		           "2014-12-03",
                                   			   "2014-12-06",
                                       			   $absenceType[ABS_TYPE_ID]);
    
    $date1 = CreateDate("2014-12-03",NULL);
    $date2 = CreateDate("2014-12-04",NULL);
    $date3 = CreateDate("2014-12-05",NULL);
    $date4 = CreateDate("2014-12-06",NULL);
    $date5 = CreateDate("2014-12-07",NULL);
    
    $publicHoliday = CreatePublicHoliday("St.Jasons Day",$date2[DATE_TABLE_DATE_ID]);
    
    $approvedAbsenceBookingDate = CreateApprovedAbsenceBookingDate($date2[DATE_TABLE_DATE_ID],
                                                              $approvedAbsenceBooking[APPR_ABS_BOOKING_ID]);
    
    
   $employees = RetrieveEmployees();
   $absenceTypes = RetrieveAbsenceTypes();
   $AdHocAbsenceRequests = RetrieveAdHocAbsenceRequests();
   $ApprovedAbsenceBookings = RetrieveApprovedAbsenceBookings();
   $MainVacationRequests = RetrieveMainVacationRequests();
   $companyRoles = RetrieveCompanyRoles();
   $dates = RetrieveDates();
   $publicHolidays = RetrievePublicHolidays();
   $approveAbsenceBookingDates = RetrieveApprovedAbsenceBookingDates();
   
   DeleteEmployee($employee[EMP_ID]); 

   $employees = RetrieveEmployees();
   $absenceTypes = RetrieveAbsenceTypes();
   $AdHocAbsenceRequests = RetrieveAdHocAbsenceRequests();
   $ApprovedAbsenceBookings = RetrieveApprovedAbsenceBookings();
   $MainVacationRequests = RetrieveMainVacationRequests();
   $companyRoles = RetrieveCompanyRoles();
   $dates = RetrieveDates();
   $publicHolidays = RetrievePublicHolidays();
   $approveAbsenceBookingDates = RetrieveApprovedAbsenceBookingDates();
   
    
}


?>