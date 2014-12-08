<?php

function testAbsenceTypeTable($connection)
{
	//CREATE
	$absenceType = CreateAbsenceType( $connection,
									  "Sick Leave",
    	                              "0",
        	                          "0");
	
	//RETRIEVE
	$absenceTypes = RetrieveAbsenceTypes($connection);

	$filter[ABS_TYPE_USES_LEAVE] = "1";
	$absenceTypes = RetrieveAbsenceTypes($connection,$filter);

	//UPDATE
	$absenceType[ABS_TYPE_USES_LEAVE] = "1";
	$success = UpdateAbsenceType($connection,$absenceType);

	//DELETE
	$success = DeleteAbsenceType($connection, $absenceType[ABS_TYPE_ID]);
}


?>