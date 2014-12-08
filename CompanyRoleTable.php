<?php

define ("COMPANY_ROLE_TABLE",               "companyRoleTable");

define ("COMP_ROLE_ID",                     "companyRoleID");
define ("COMP_ROLE_NAME",                   "roleName");
define ("COMP_ROLE_MIN_STAFF",              "minimumStaffingLevel");

function createCompanyRoleTable($connection)
{
    $sql="CREATE TABLE IF NOT EXISTS `mydb`.`companyRoleTable` (
         `companyRoleID` INT NOT NULL AUTO_INCREMENT,
         `roleName` VARCHAR(30) NOT NULL,
         `minimumStaffingLevel` INT(1) NOT NULL,
          PRIMARY KEY (`companyRoleID`));";
    performSQL($connection,$sql);
}

function CreateCompanyRole($connection,
						   $roleName,
                           $minStaffLevel)
{
	$role[COMP_ROLE_ID]                 = NULL;
	$role[COMP_ROLE_NAME]               = $roleName;
	$role[COMP_ROLE_MIN_STAFF]          = $minStaffLevel;
	
	$success = sqlInsertCompanyRole($connection,$role);
	if (! $success )
	{
		error_log ("Failed to create company Role. ".print_r($role));
		$role = NULL;
	}
	return $role;
}

function sqlInsertCompanyRole($connection,&$role)
{
    $sql ="INSERT INTO companyroletable (roleName,minimumStaffingLevel) ".
     	  "VALUES ('".$role[COMP_ROLE_NAME]."',".$role[COMP_ROLE_MIN_STAFF].");";
    
    $role[COMP_ROLE_ID] = performSQLInsert($connection,$sql);
    return ($role[COMP_ROLE_ID] <> 0);
}

function RetrieveCompanyRoles($connection,$filter=NULL)     
{
	return performSQLSelect($connection,COMPANY_ROLE_TABLE,$filter);
}

function UpdateCompanyRole($connection,$fields)
{
    return performSQLUpdate($connection,COMPANY_ROLE_TABLE,COMP_ROLE_ID,$fields); 	
}

function DeleteCompanyRole($connection,$ID)
{
    $sql ="DELETE FROM companyroletable WHERE companyRoleID=".$ID.";";
    
    return performSQL($connection,$sql);
}

?>