<?php

function testApprovedAbsenceBookingTable($connection)
{
	$role = CreateCompanyRole($connection,"Cashier",8);
	
	$employee = CreateEmployee( $connection,
							    "Jason Gilbert",
        	                    "jasongilbertuk@hotmail.com",
            	                "zaq12wsx",
                	            "1990-11-28",
                    	        25,
                        	    NULL,
                           		$role[COMP_ROLE_ID]);
                           		
	$absenceType = CreateAbsenceType( $connection,
									  "Sick Leave",
    	                              "0",
        	                          "0");
	
	//CREATE
	$approvedAbsenceBooking = CreateApprovedAbsenceBooking(
										  $connection,
										  $employee[EMP_ID],
    	                                  "2014-11-21",
        	                              "2014-11-23",
            	                          $absenceType[ABS_TYPE_ID]);
 	
 	//RETRIEVE
	$approvedAbsenceBookings = RetrieveApprovedAbsenceBookings($connection);	
	$filter[APPR_ABS_START_DATE] = "2014-11-20";
	$approvedAbsenceBookings = RetrieveApprovedAbsenceBookings($connection,$filter);
	
	//UPDATE
	$approvedAbsenceBooking[APPR_ABS_START_DATE] = "2014-11-20";
	$success = UpdateApprovedAbsenceBooking($connection,$approvedAbsenceBooking);
	
	//DELETE
	$success = DeleteApprovedAbsenceBooking($connection, 
                         $approvedAbsenceBooking[APPR_ABS_BOOKING_ID]);
}

?>