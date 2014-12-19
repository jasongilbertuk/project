<?php

function testEmployeeTable()
{
	//CREATE
	$role = CreateCompanyRole("Cashier",8);
  	
  	if ($role == NULL)
    {
    	echo "ROLE IS NULL";
    }                       		
  	
	$employee = CreateEmployee( "Jason Gilbert",
        	                   "jasongilbertuk@hotmail.com",
            	               "zaq12wsx",
                	           "1990-11-28",
                    	       25,
                        	   NULL,
                           		$role[COMP_ROLE_ID]);
  
  	$employee2 = CreateEmployee( "Sam Gilbert",
        	                   "samgilbertuk@hotmail.com",
            	               "zaq12wsx",
                	           "1990-11-28",
                    	       25,
                        	   NULL,
                           		$role[COMP_ROLE_ID]);
                           		
                           		
    if ($employee == NULL)
    {
    	echo "EMPLOYEE IS NULL";
    }                       		
                           		
    $mainVacationRequest =CreateMainVactionRequest($employee[EMP_ID],
                                  				   "2014-10-12",
                                  				   "2014-10-17",
                                  				   "2014-08-12",
                                  				   "2014-08-17");
                                  				   
    if ($mainVacationRequest == NULL)
    {
    	echo "MAIN VACATION REQUEST IS NULL";
    }                       		
    
    $absenceType = CreateAbsenceType("Annual Leave",TRUE,TRUE);
    
    $employee = RetrieveEmployeeByID($employee[EMP_ID]);  
    $adHocAbsenceRequest = CreateAdHocAbsenceRequest($employee[EMP_ID],
                                   					 "2014-12-03",
                                   					 "2014-12-06",
                                   					 $absenceType[ABS_TYPE_ID]);  
                                   					 

	$approvedAbsenceBooking = CreateApprovedAbsenceBooking($employee[EMP_ID],
                                       				 "2014-12-03",
                                   					 "2014-12-06",
                                       				 $absenceType[ABS_TYPE_ID]);

   
   $employees = RetrieveEmployees();
   $absenceTypes = RetrieveAbsenceTypes();
   $AdHocAbsenceRequests = RetrieveAdHocAbsenceRequests();
   $ApprovedAbsenceBookings = RetrieveApprovedAbsenceBookings();
   $MainVacationRequests = RetrieveMainVacationRequests();
   $companyRoles = RetrieveCompanyRoles();
   
   echo "<br/>EMPLOYEES<br/>";
   print_r($employees);
   echo "<br/>ABSENCE TYPES<br/>";
   print_r($absenceTypes);
   echo "<br/>AD HOC ABSENCE REQUESTS<br/>";
   print_r($AdHocAbsenceRequests);
   echo "<br/>APPROVED ABSENCE BOOKINGS<br/>";
   print_r($ApprovedAbsenceBookings);
   echo "<br/>MAIN VACATION REQUESTS<br/>";
   print_r($MainVacationRequests);
   echo "<br/>COMPANY ROLES<br/>";
   print_r($companyRoles);

   DeleteEmployee($employee[EMP_ID]); 
	echo "<br/><br/>Employee Deleted<br/><br/>";

   $employees = RetrieveEmployees();
   $absenceTypes = RetrieveAbsenceTypes();
   $AdHocAbsenceRequests = RetrieveAdHocAbsenceRequests();
   $ApprovedAbsenceBookings = RetrieveApprovedAbsenceBookings();
   $MainVacationRequests = RetrieveMainVacationRequests();
   $companyRoles = RetrieveCompanyRoles();
   
   echo "<br/>EMPLOYEES<br/>";
   print_r($employees);
   echo "<br/>ABSENCE TYPES<br/>";
   print_r($absenceTypes);
   echo "<br/>AD HOC ABSENCE REQUESTS<br/>";
   print_r($AdHocAbsenceRequests);
   echo "<br/>APPROVED ABSENCE BOOKINGS<br/>";
   print_r($ApprovedAbsenceBookings);
   echo "<br/>MAIN VACATION REQUESTS<br/>";
   print_r($MainVacationRequests);
   echo "<br/>COMPANY ROLES<br/>";
   print_r($companyRoles);

   
}

?>