<?php

function testEmployeeTable()
{
	//CREATE
	$role = CreateCompanyRole("Cashier",8);
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
                           		
                           		
    $mainVacationRequest =CreateMainVactionRequest($employee[EMP_ID],
                                  				   "2014-10-12",
                                  				   "2014-10-17",
                                  				   "2014-08-12",
                                  				   "2014-08-17");
                                  				   
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
   
 
   DeleteEmployee($employee[EMP_ID]); 

   $employees = RetrieveEmployees();
   $absenceTypes = RetrieveAbsenceTypes();
   $AdHocAbsenceRequests = RetrieveAdHocAbsenceRequests();
   $ApprovedAbsenceBookings = RetrieveApprovedAbsenceBookings();
   $MainVacationRequests = RetrieveMainVacationRequests();
   $companyRoles = RetrieveCompanyRoles();
   
}

?>