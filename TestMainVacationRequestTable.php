<?php

function testMainVacationRequestTable()
{
	$role = CreateCompanyRole("Cashier",8);
	
	$employee = CreateEmployee( "Jason Gilbert",
        	                   "jasongilbertuk@hotmail.com",
            	               "zaq12wsx",
                	           "1990-11-28",
                    	       25,
                        	   NULL,
                           		$role[COMP_ROLE_ID]);

	//CREATE
	$mainVacationRequest = CreateMainVactionRequest($employee[EMP_ID],
                                                    "2014-08-12",
                                                    "2014-08-19",
												    "2014-09-12",
						    						"2014-09-19");

	//RETRIEVE
	$mainVacationRequests 		= RetrieveMainVacationRequests();
	$filter[MAIN_VACATION_1ST_START] = "2014-08-12";
	$mainVacationRequests 		= RetrieveMainVacationRequests($filter);

	//UPDATE
	$mainVacationRequest[MAIN_VACATION_2ND_START] = "2014-02-11";
	$success = UpdateMainVacactionRequest($mainVacationRequest);

	//DELETE
	$success = DeleteMainVacationRequest($mainVacationRequest[MAIN_VACATION_REQ_ID]);
}


?>