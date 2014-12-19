<?php

function testDateTable()
{
	//CREATE
	 $date = CreateDate("2014-12-25",
                    	NULL);
                    	
 	//RETRIEVE
	$dates 		= RetrieveDates();
	
	$filter[DATE_TABLE_DATE] = "2014-12-25";
	$dates	 	= RetrieveDates($filter);

	//UPDATE
	$date[DATE_TABLE_DATE] = "2014-12-26";
	$success = UpdateDate($dates[0]);
	
	//DELETE
	$success = DeleteDate($dates[0][DATE_TABLE_DATE_ID]);
}


?>