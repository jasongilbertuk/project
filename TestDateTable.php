<?php

function testDateTable($connection)
{
	//CREATE
	 $date = CreateDate($connection,
	 					"2014-12-25",
                    	NULL);
 
 	//RETRIEVE
	$dates 		= RetrieveDates($connection);
	$filter[DATE_TABLE_DATE] = "2014-12-26";
	$dates	 	= RetrieveDates($connection,$filter);

	//UPDATE
	$date[DATE_TABLE_DATE] = "2014-12-26";
	$success = UpdateDate($connection,$date);
	
	//DELETE
	$success = DeleteDate($connection, 
 	                         $date[DATE_TABLE_DATE_ID]);
}


?>