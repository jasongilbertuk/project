<?php

function testCompanyRoleTable()
{
	//CREATE
	$role = CreateCompanyRole("Cashier",8);

	//RETRIEVE
	$companyRoles = RetrieveCompanyRoles();

	$filter[COMP_ROLE_MIN_STAFF] = 8;
	$companyRoles = RetrieveCompanyRoles($filter);

	//UPDATE
	$role[COMP_ROLE_MIN_STAFF] = 2;
	$success = UpdateCompanyRole($role);

	//DELETE
	$success = DeleteCompanyRole($role[COMP_ROLE_ID]);
}

?>