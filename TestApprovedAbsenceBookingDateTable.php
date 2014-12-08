<?php

function testApprovedAbsenceBookingDateTable($connection)
{
	$date = CreateDate($connection,
	 					"2014-12-25",
                    	NULL);
                    	
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
	
	$approvedAbsenceBooking = CreateApprovedAbsenceBooking($connection,
										  $employee[EMP_ID],
    	                                  "2014-11-21",
        	                              "2014-11-23",
            	                          $absenceType[ABS_TYPE_ID]);
 	

	//CREATE
	$approvedAbsenceBookingDate = CreateApprovedAbsenceBookingDate($connection,
																   $date[DATE_TABLE_DATE_ID],
                                                                   $approvedAbsenceBooking[APPR_ABS_BOOKING_ID]);
 	//RETRIEVE
	$approvedAbsenceBookingDates = RetrieveApprovedAbsenceBookingDates($connection);
	$filter[APPR_ABS_BOOK_DATE_ABS_BOOK_ID] = $approvedAbsenceBooking[APPR_ABS_BOOKING_ID];
	$approvedAbsenceBookings = RetrieveApprovedAbsenceBookingDates($connection,$filter);

	//UPDATE
	$approvedAbsenceBookingDate[APPR_ABS_BOOK_DATE_ABS_BOOK_ID] = $approvedAbsenceBooking[APPR_ABS_BOOKING_ID];
	$success = UpdateApprovedAbsenceBookingDate($connection,$approvedAbsenceBookingDate);

	//DELETE
	$success = DeleteApprovedAbsenceBookingDate($connection, 
                         $approvedAbsenceBookingDate[APPR_ABS_BOOK_DATE_ID]);
}



?>
