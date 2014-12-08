<?php

function testMainVacationRequestTable($connection)
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

	//CREATE
	$mainVacationRequest = CreateMainVactionRequest($connection,
													$employee[EMP_ID],
                                                    "2014-08-12",
                                                    "2014-08-19",
												    "2014-09-12",
						    						"2014-09-19");

	//RETRIEVE
	$mainVacationRequests 		= RetrieveMainVacationRequests($connection);
	$filter[MAIN_VACATION_1ST_START] = "2014-08-12";
	$mainVacationRequests 		= RetrieveMainVacationRequests($connection,$filter);

	//UPDATE
	$mainVacationRequest[MAIN_VACATION_2ND_START] = "2014-02-11";
	$success = UpdateMainVacactionRequest($connection,$mainVacationRequest);

	//DELETE
	$success = DeleteMainVacationRequest($connection,
                                    $mainVacationRequest[MAIN_VACATION_REQ_ID]);
}


?>