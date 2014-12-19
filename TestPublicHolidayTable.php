<?php

function testPublicHolidayTable()
{
	 $date = CreateDate("2014-12-25",
                    	NULL);
                    	
    if ($date)
    {
		//CREATE
		$publicHoliday = CreatePublicHoliday("Boxing Day",
											 $date[DATE_TABLE_DATE_ID]);
				
		if ($publicHoliday)
		{									 
			//RETRIEVE
			$publicHolidays = RetrievePublicHolidays();

			$filter[PUB_HOL_DATE_ID] = $date[DATE_TABLE_DATE_ID];
			$publicHolidays	= RetrievePublicHolidays($filter);
		
			//UPDATE
			$publicHoliday[PUB_HOL_NAME] = "Box Day";
			$success = UpdatePublicHoliday($publicHoliday);
		
			//DELETE
			$success = DeletePublicHoliday($publicHoliday[PUB_HOL_ID]);
		}
	}
}

?>