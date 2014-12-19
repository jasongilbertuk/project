<?php

function testApprovedAbsenceBookingTable()
{
	$role = CreateCompanyRole("Cashier",8);
	
	$employee = CreateEmployee( "Jason Gilbert",
        	                    "jasongilbertuk@hotmail.com",
            	                "zaq12wsx",
                	            "1990-11-28",
                    	        25,
                        	    NULL,
                           		$role[COMP_ROLE_ID]);
                           		
	$absenceType = CreateAbsenceType( "Sick Leave",
    	                              "0",
        	                          "0");
	
	//CREATE
	$approvedAbsenceBooking = CreateApprovedAbsenceBooking(
										  $employee[EMP_ID],
    	                                  "2014-11-21",
        	                              "2014-11-23",
            	                          $absenceType[ABS_TYPE_ID]);
 	
 	//RETRIEVE
	$approvedAbsenceBookings = RetrieveApprovedAbsenceBookings();	
	$filter[APPR_ABS_START_DATE] = "2014-11-20";
	$approvedAbsenceBookings = RetrieveApprovedAbsenceBookings($filter);
	
	//UPDATE
	$approvedAbsenceBooking[APPR_ABS_START_DATE] = "2014-11-20";
	$success = UpdateApprovedAbsenceBooking($approvedAbsenceBooking);
	
	//DELETE
	$success = DeleteApprovedAbsenceBooking($approvedAbsenceBooking[APPR_ABS_BOOKING_ID]);
}

?>