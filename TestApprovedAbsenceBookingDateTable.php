<?php

function testApprovedAbsenceBookingDateTable()
{
	$date = CreateDate("2014-12-25",
                    	NULL);
    if ($date)
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
	
		$approvedAbsenceBooking = CreateApprovedAbsenceBooking(
											  $employee[EMP_ID],
    	    	                              "2014-11-21",
        	    	                          "2014-11-23",
            	    	                      $absenceType[ABS_TYPE_ID]);
 	

		//CREATE
		$approvedAbsenceBookingDate = CreateApprovedAbsenceBookingDate($date[DATE_TABLE_DATE_ID],
                                                                   $approvedAbsenceBooking[APPR_ABS_BOOKING_ID]);
 		//RETRIEVE
		$approvedAbsenceBookingDates = RetrieveApprovedAbsenceBookingDates();
		$filter[APPR_ABS_BOOK_DATE_ABS_BOOK_ID] = $approvedAbsenceBooking[APPR_ABS_BOOKING_ID];
		$approvedAbsenceBookings = RetrieveApprovedAbsenceBookingDates($filter);

		//UPDATE
		$approvedAbsenceBookingDate[APPR_ABS_BOOK_DATE_ABS_BOOK_ID] = $approvedAbsenceBooking[APPR_ABS_BOOKING_ID];
		$success = UpdateApprovedAbsenceBookingDate($approvedAbsenceBookingDate);

		//DELETE
		$success = DeleteApprovedAbsenceBookingDate($approvedAbsenceBookingDate[APPR_ABS_BOOK_DATE_ID]);
	}
}



?>
