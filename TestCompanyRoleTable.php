<?php

function testCompanyRoleTable($connection)
{
	//CREATE
	$role = CreateCompanyRole($connection,"Cashier",8);

	//RETRIEVE
	$companyRoles = RetrieveCompanyRoles($connection);

	$filter[COMP_ROLE_MIN_STAFF] = 8;
	$companyRoles = RetrieveCompanyRoles($connection,$filter);

	//UPDATE
	$role[COMP_ROLE_MIN_STAFF] = 2;
	$success = UpdateCompanyRole($connection,$role);

	//DELETE
	$success = DeleteCompanyRole($connection,$role[COMP_ROLE_ID]);
}

?>