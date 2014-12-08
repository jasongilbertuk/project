<?php

function testPublicHolidayTable($connection)
{
	 $date = CreateDate($connection,
	 					"2014-12-25",
                    	NULL);
	//CREATE
	$publicHoliday = CreatePublicHoliday($connection,
										 "Boxing Day",
										 $date[DATE_TABLE_DATE_ID]);
 	//RETRIEVE
	$publicHolidays = RetrievePublicHolidays($connection);

	$filter[PUB_HOL_DATE_ID] = $date[DATE_TABLE_DATE_ID];
	$publicHolidays	= RetrievePublicHolidays($connection,$filter);
	
	//UPDATE
	$publicHoliday[PUB_HOL_NAME] = "Box Day";
	$success = UpdatePublicHoliday($connection,$publicHoliday);
	
	//DELETE
	$success = DeletePublicHoliday($connection, 
                         $publicHoliday[PUB_HOL_ID]);
	
}

?>