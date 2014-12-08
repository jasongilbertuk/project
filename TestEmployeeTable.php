<?php

function testEmployeeTable($connection)
{
	//CREATE
	$role = CreateCompanyRole($connection,"Cashier",8);
	
	$employee = CreateEmployee( $connection,
							    "Jason Gilbert",
        	                   "jasongilbertuk@hotmail.com",
            	               "zaq12wsx",
                	           "1990-11-28",
                    	       25,
                        	   NULL,
                           		$role[COMP_ROLE_ID]);
                           		
	//RETRIEVE
	$employees = RetrieveEmployees($connection);

	$filter[EMP_LEAVE_ENTITLEMENT] = 25;
	$employees = RetrieveEmployees($connection,$filter);

	//UPDATE
	$employee[EMP_PASSWORD] = "xsw21qaz";
	$success = UpdateEmployee($connection,$employee);

	//DELETE
	$success = DeleteEmployee($connection,$employee[EMP_ID]);
}

?>