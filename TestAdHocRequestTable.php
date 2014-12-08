<?php

function testAdHocAbsenceRequestTable($connection)
{
	$role = CreateCompanyRole($connection,"Cashier",8);
	
	$employee = CreateEmployee( $connection,
							    "Sam Gilbert",
        	                   "samgilbertuk@hotmail.com",
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
	$adHocAbsenceRequest = CreateAdHocAbsenceRequest($connection,
													 $employee[EMP_ID],
    	                                             "2014-03-12",
        	                                         "2014-04-12",
            	                                     $absenceType[ABS_TYPE_ID]);

	//RETRIEVE
	$adHocAbsenceRequests 		= RetrieveAdHocAbsenceRequests($connection);
	$filter[AD_HOC_START] = "2014-03-11";
	$adHocAbsenceRequests 	= RetrieveAdHocAbsenceRequests($connection,$filter);

	//UPDATE
	$adHocAbsenceRequest[AD_HOC_START] = "2014-03-11";
	$success = UpdateAdHocAbsenceRequest($connection,$adHocAbsenceRequest);
	
	//DELETE
	$success = DeleteAdHocAbsenceRequest($connection, 
    	                                 $adHocAbsenceRequest[ABS_TYPE_ID]);
}

?>