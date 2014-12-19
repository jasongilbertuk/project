<?php
/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableTest_Create_001
 *
 * Test to ensure CreateAbsenceTable handles invalid name parameter (NULL) being passed.
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function AbsenceTypeTableTest_Create_001()
{
	$testTitle 	= "Create with invalid name parameter";
	$testResult = "Fail";
	$testError 	= "";
	
	$absenceType = CreateAbsenceType( NULL,FALSE,FALSE);
    if ($absenceType == NULL)
    {
    	$testResult = "Pass";
    }
    rowTestTableOutput(__FUNCTION__,$testTitle,$testResult,$testError);
}

/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableTest_Create_002
 *
 * Test to ensure CreateAbsenceTable handles invalid usesLeave parameter being passed.
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function AbsenceTypeTableTest_Create_002()
{
	$testTitle 	= "Create with invalid usesLeave parameter";
	$testResult = "Fail";
	$testError 	= "";
	
	//CREATE
	$absenceType = CreateAbsenceType( "Sick Leave","7",FALSE);
    if ($absenceType == NULL)
    {
    	$testResult = "Pass";
    }
    rowTestTableOutput(__FUNCTION__,$testTitle,$testResult,$testError);
}

/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableTest_Create_003
 *
 * Test to ensure CreateAbsenceTable handles invalid canBeDenied parameter being passed.
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function AbsenceTypeTableTest_Create_003()
{
	$testTitle 	= "Create with invalid canBeDenied parameter";
	$testResult = "Fail";
	$testError 	= "";
	
	//CREATE
	$absenceType = CreateAbsenceType( "Sick Leave",FALSE,"7");
    if ($absenceType == NULL)
    {
    	$testResult = "Pass";
    }
    rowTestTableOutput(__FUNCTION__,$testTitle,$testResult,$testError);
}

/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableTest_Create_004
 *
 * Test to ensure CreateAbsenceTable creates entry in database if valid parameters are
 * passed.
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function AbsenceTypeTableTest_Create_004()
{
	$testTitle 	= "Create with valid parameters";
	$testResult = "Fail";
	$testError 	= "";
	
	//CREATE
	$absenceType = CreateAbsenceType( "Sick Leave",FALSE,TRUE);
    
    if ($absenceType != NULL AND $absenceType[ABS_TYPE_ID] <> NULL)
    {
    	//-------------------------------------------------------------------------- 
    	//Successful result. Now let's attempt to retrieve record from database
    	//and ensure that the fields of that record match the values we gave in the 
    	//create call.
    	//-------------------------------------------------------------------------- 
    	$absenceTypeFromDB = RetrieveAbsenceTypeByID($absenceType[ABS_TYPE_ID]);
    	
	 	if ( ($absenceTypeFromDB[ABS_TYPE_ID] == $absenceType[ABS_TYPE_ID]) AND
	 		 (strcmp($absenceTypeFromDB[ABS_TYPE_NAME],"Sick Leave")==0) AND
	 		 ($absenceTypeFromDB[ABS_TYPE_USES_LEAVE] == FALSE) AND
	 		 ($absenceTypeFromDB[ABS_TYPE_CAN_BE_DENIED] == TRUE))
	 	{
	 		$testResult = "Pass";
	 	}
    }
    rowTestTableOutput(__FUNCTION__,$testTitle,$testResult,$testError);
}

/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableTest_Retrieve_001
 *
 * Test to ensure that retrieve from database with no filter successfully returns
 * all records and that the values of all the records returned are correct.  
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function AbsenceTypeTableTest_Retrieve_001()
{
	$testTitle 	= "Retrieve with no filter";
	$testResult = "Pass";
	$testError  = "";
	
 	$absenceTypes = RetrieveAbsenceTypes();

	if ($absenceTypes != NULL)
	{
		if (count($absenceTypes) <> 4)
		{
			$testError = "Count of records is incorrect. count = ".count($absenceTypes);
			$testResult = "Fail";
		}
		
		foreach($absenceTypes as $absenceType)
		{
			if ($absenceType <> NULL)
			{	
				if ($absenceType[ABS_TYPE_NAME] == "Sick Leave" )
				{
					if ( ($absenceType[ABS_TYPE_USES_LEAVE] <> FALSE ) OR
					     ($absenceType[ABS_TYPE_CAN_BE_DENIED] <> FALSE) )
					{
						$testError = "Sick Leave fields incorrect";
						$testResult = "Fail";
					}
				}
				else if ($absenceType[ABS_TYPE_NAME] == "Annual Leave")
				{
					if ( ($absenceType[ABS_TYPE_USES_LEAVE] <> TRUE) OR
				    	 ($absenceType[ABS_TYPE_CAN_BE_DENIED] <> TRUE ) )
					{
						$testError = "Annual Leave fields incorrect";
						$testResult = "Fail";
					}
				}
				else if ($absenceType[ABS_TYPE_NAME] == "Training")
				{
					if ( ($absenceType[ABS_TYPE_USES_LEAVE] <> FALSE ) AND
				   	  ($absenceType[ABS_TYPE_CAN_BE_DENIED] <> TRUE ) )
					{
						$testError = "Training fields incorrect";
						$testResult = "Fail";
					}
				}
				else if ($absenceType[ABS_TYPE_NAME] == "Emergency Leave")
				{
					if ( ($absenceType[ABS_TYPE_USES_LEAVE] <> TRUE ) OR
				   	  ($absenceType[ABS_TYPE_CAN_BE_DENIED] <> FALSE) )
					{
						$testError = "Emergency Leave fields incorrect";
						$testResult = "Fail";
					}
				}
				else
				{	
						$testError = "Unrecognised absenceType record in DB. "
									.$absenceType[ABS_TYPE_NAME];
						$testResult = "Fail";
				}
			}
		}
	}
	
    rowTestTableOutput(__FUNCTION__,$testTitle,$testResult,$testError);
}


/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableTest_Retrieve_002
 *
 * Test to ensure that retrieve from database with a valid ID filter succesfully returns
 * the right data.  
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function AbsenceTypeTableTest_Retrieve_002()
{
	$testTitle 	= "Retrieve with valid parameters - filter on ID field";
	$testResult = "Pass";
	$testError  = "";
	
  	//RETRIEVE
  	$filter[ABS_TYPE_ID] = "1";
  	$absenceTypes = RetrieveAbsenceTypes($filter);

	if ($absenceTypes != NULL)
	{
		if (count($absenceTypes) <> 1)
		{
			$testError = "Count of records is incorrect. count = ".count($absenceTypes);
			$testResult = "Fail";
		}
		
		foreach($absenceTypes as $absenceType)
		{
			if ($absenceType <> NULL)
			{	
				if ($absenceType[ABS_TYPE_NAME] == "Sick Leave" )
				{
					if ( ($absenceType[ABS_TYPE_USES_LEAVE] <> FALSE) OR
					     ($absenceType[ABS_TYPE_CAN_BE_DENIED] <> FALSE ) )
					{
						$testError = "Sick Leave fields incorrect";
						$testResult = "Fail";
					}
				}
				else
				{	
						$testError = "Unrecognised absenceType record in DB. "
								     .$absenceType[ABS_TYPE_NAME];
						$testResult = "Fail";
				}
			}
		}
	}
	
    rowTestTableOutput(__FUNCTION__,$testTitle,$testResult,$testError);
}


/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableTest_Retrieve_003
 *
 * Test to ensure that retrieve from database with a valid name filter succesfully returns
 * the right data.  
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function AbsenceTypeTableTest_Retrieve_003()
{
	$testTitle 	= "Retrieve with valid parameters - filter on Name field";
	$testResult = "Pass";
	$testError  = "";
	
  	//RETRIEVE
  	$filter[ABS_TYPE_NAME] = "Training";
  	$absenceTypes = RetrieveAbsenceTypes($filter);

	if ($absenceTypes != NULL)
	{
		if (count($absenceTypes) <> 1)
		{
			$testError = "Count of records is incorrect. count = ".count($absenceTypes);
			$testResult = "Fail";
		}
		
		foreach($absenceTypes as $absenceType)
		{
			if ($absenceType <> NULL)
			{	
				if ($absenceType[ABS_TYPE_NAME] 	== "Training")
				{
					if ( ($absenceType[ABS_TYPE_USES_LEAVE] <> FALSE ) AND
				   	  ($absenceType[ABS_TYPE_CAN_BE_DENIED] <> TRUE ) )
					{
						$testError = "Training fields incorrect";
						$testResult = "Fail";
					}
				}
				else
				{	
						$testError = "Unrecognised absenceType record in DB. "
									 .$absenceType[ABS_TYPE_NAME];
						$testResult = "Fail";
				}
			}
		}
	}
	
    rowTestTableOutput(__FUNCTION__,$testTitle,$testResult,$testError);
}

/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableTest_Retrieve_004
 *
 * Test to ensure that retrieve from database with a valid usesLeave filter succesfully
 * returns the right data.  
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function AbsenceTypeTableTest_Retrieve_004()
{
	$testTitle 	= "Retrieve with valid parameters - filter on UsesLeave field";
	$testResult = "Pass";
	$testError  = "";
	
  	//RETRIEVE
  	$filter[ABS_TYPE_USES_LEAVE] = TRUE;
  	$absenceTypes = RetrieveAbsenceTypes($filter);

	if ($absenceTypes != NULL)
	{
		if (count($absenceTypes) <> 2)
		{
			$testError = "Count of records is incorrect. count = ".count($absenceTypes);
			$testResult = "Fail";
		}
		
		foreach($absenceTypes as $absenceType)
		{
			if ($absenceType <> NULL)
			{	
			    if ($absenceType[ABS_TYPE_NAME] 	== "Annual Leave")
				{
					if ( ($absenceType[ABS_TYPE_USES_LEAVE] <> TRUE ) OR
				    	 ($absenceType[ABS_TYPE_CAN_BE_DENIED] <> TRUE ) )
					{
						$testError = "Annual Leave fields incorrect";
						$testResult = "Fail";
					}
				}
				else if ($absenceType[ABS_TYPE_NAME] 	== "Emergency Leave")
				{
					if ( ($absenceType[ABS_TYPE_USES_LEAVE] <> TRUE ) OR
				   	  ($absenceType[ABS_TYPE_CAN_BE_DENIED] <> FALSE ) )
					{
						$testError = "Emergency Leave fields incorrect";
						$testResult = "Fail";
					}
				}
					else
				{	
						$testError = "Unrecognised absenceType record in DB. "
									 .$absenceType[ABS_TYPE_NAME];
						$testResult = "Fail";
				}
			}
		}
	}
	
    rowTestTableOutput(__FUNCTION__,$testTitle,$testResult,$testError);
}

/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableTest_Retrieve_005
 *
 * Test to ensure that retrieve from database with a valid canBeDenied filter succesfully
 * returns the right data.  
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function AbsenceTypeTableTest_Retrieve_005()
{
	$testTitle 	= "Retrieve with valid parameters - filter on CanBeDenied field";
	$testResult = "Pass";
	$testError  = "";
	
  	//RETRIEVE
  	$filter[ABS_TYPE_CAN_BE_DENIED] = TRUE;
  	$absenceTypes = RetrieveAbsenceTypes($filter);

	if ($absenceTypes != NULL)
	{
		if (count($absenceTypes) <> 2)
		{
			$testError = "Count of records is incorrect. count = ".count($absenceTypes);
			$testResult = "Fail";
		}
		
		foreach($absenceTypes as $absenceType)
		{
			if ($absenceType <> NULL)
			{	

				if ($absenceType[ABS_TYPE_NAME] == "Annual Leave")
				{
					if ( ($absenceType[ABS_TYPE_USES_LEAVE] <> TRUE ) OR
				    	 ($absenceType[ABS_TYPE_CAN_BE_DENIED] <> TRUE) )
					{
						$testError = "Annual Leave fields incorrect";
						$testResult = "Fail";
					}
				}
				else if ($absenceType[ABS_TYPE_NAME] 	== "Training")
				{
					if ( ($absenceType[ABS_TYPE_USES_LEAVE] <> FALSE ) AND
				   	  ($absenceType[ABS_TYPE_CAN_BE_DENIED] <> TRUE ) )
					{
						$testError = "Training fields incorrect";
						$testResult = "Fail";
					}
				}
				else
				{	
						$testError = "Unrecognised absenceType record in DB. "
									  .$absenceType[ABS_TYPE_NAME];
						$testResult = "Fail";
				}
			}
		}
	}
	
    rowTestTableOutput(__FUNCTION__,$testTitle,$testResult,$testError);
}

/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableTest_Retrieve_006
 *
 * Test to ensure that retrieve from database with a multiple filters succesfully
 * returns the right data.  
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function AbsenceTypeTableTest_Retrieve_006()
{
	$testTitle 	= "Retrieve with valid parameters - multiple filters";
	$testResult = "Pass";
	$testError  = "";
	
  	//RETRIEVE
  	$filter[ABS_TYPE_USES_LEAVE] = TRUE;
	$filter[ABS_TYPE_CAN_BE_DENIED] = TRUE;
  	$absenceTypes = RetrieveAbsenceTypes($filter);

	if ($absenceTypes != NULL)
	{
		if (count($absenceTypes) <> 1)
		{
			$testError = "Count of records is incorrect. count = ".count($absenceTypes);
			$testResult = "Fail";
		}
		
		foreach($absenceTypes as $absenceType)
		{
			if ($absenceType <> NULL)
			{	

				if ($absenceType[ABS_TYPE_NAME] 	== "Annual Leave")
				{
					if ( ($absenceType[ABS_TYPE_USES_LEAVE] <> TRUE ) OR
				    	 ($absenceType[ABS_TYPE_CAN_BE_DENIED] <> TRUE ) )
					{
						$testError = "Annual Leave fields incorrect";
						$testResult = "Fail";
					}
				}
				else
				{	
						$testError = "Unrecognised absenceType record in DB. "
									 .$absenceType[ABS_TYPE_NAME];
						$testResult = "Fail";
				}
			}
		}
	}
	
    rowTestTableOutput(__FUNCTION__,$testTitle,$testResult,$testError);
}

/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableTest_Retrieve_007
 * 
 * Test to ensure that retrieve from database with a invalid filter returns a failure
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function AbsenceTypeTableTest_Retrieve_007()
{
	$testTitle 	= "Retrieve with invalid parameter";
	$testResult = "Pass";
	$testError  = "";
	
  	//RETRIEVE
  	$filter["An Invalid Value"] = TRUE;
	$absenceTypes = RetrieveAbsenceTypes($filter);

	if ($absenceTypes != NULL)
	{
		if (count($absenceTypes) <> 1)
		{
			$testError = "Count of records is incorrect. count = ".count($absenceTypes);
			$testResult = "Fail";
		}
		
		foreach($absenceTypes as $absenceType)
		{
			if ($absenceType <> NULL)
			{	
				$testError = "Unrecognised absenceType record in DB. "
							 .$absenceType[ABS_TYPE_NAME];
				$testResult = "Fail";
			}
		}
	}
	
    rowTestTableOutput(__FUNCTION__,$testTitle,$testResult,$testError);
}


/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableTest_Update_001
 *
 * Test to ensure that valid update of record is successful.
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function AbsenceTypeTableTest_Update_001()
{
	$testTitle 	= "Valid Update attempt";
	$testResult = "Pass";
	$testError  = "";
	
  	//RETRIEVE
  	$filter[ABS_TYPE_NAME] = "Sick Leave";
	$absenceTypes = RetrieveAbsenceTypes($filter);

	if ($absenceTypes != NULL)
	{
		if (count($absenceTypes) <> 1)
		{
			$testError = "Count of records is incorrect. count = ".count($absenceTypes);
			$testResult = "Fail";
		}
		else
		{
			$absenceType = $absenceTypes[0];
			if ($absenceType <> NULL)
			{	
				if ($absenceType[ABS_TYPE_NAME] == "Sick Leave")
				{
					$absenceType[ABS_TYPE_NAME] = "Sickness Leave";
					$success = UpdateAbsenceType($absenceType);
					
					if (!$success)
					{
						$testError = "Failure in call to UpdateAbsenceType. ";
						$testResult = "Fail";
					}
					else
					{	
					
						$filter[ABS_TYPE_NAME] = "Sick Leave";
						$retrieved = RetrieveAbsenceTypes($filter);
						if (count($retrieved)<>0)
						{
							$testError = "Unexpected entries in database. ";
							$testResult = "Fail";
						}
					
				  		$filter[ABS_TYPE_NAME] = "Sickness Leave";
				  		unset($retrieved);
						$retrieved = RetrieveAbsenceTypes($filter);
						if (count($retrieved)<>1)
						{
							$testError = "Expected entry not in database. count=".count($retrieved);
							$testResult = "Fail";
						}
					}
					
				}
				else
				{
					$testError = "Unrecognised absenceType record in DB. "
							     .$absenceType[ABS_TYPE_NAME];
					$testResult = "Fail";
				}
			}
		}
	}
	
    rowTestTableOutput(__FUNCTION__,$testTitle,$testResult,$testError);
}

/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableTest_Update_002
 *
 * Test to ensure that attempt to update a record using an invalid name fails.
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function AbsenceTypeTableTest_Update_002()
{
	$testTitle 	= "Invalid Update attempt - name field";
	$testResult = "Pass";
	$testError  = "";
	
  	//RETRIEVE
  	$filter[ABS_TYPE_NAME] = "Sickness Leave";
	$absenceTypes = RetrieveAbsenceTypes($filter);

	if ($absenceTypes != NULL)
	{
		if (count($absenceTypes) <> 1)
		{
			$testError = "Count of records is incorrect. count = ".count($absenceTypes);
			$testResult = "Fail";
		}
		
		foreach($absenceTypes as $absenceType)
		{
			if ($absenceType <> NULL)
			{	
				if ($absenceType[ABS_TYPE_NAME] 	== "Sickness Leave")
				{
					$absenceType[ABS_TYPE_NAME] = NULL;
					$success = UpdateAbsenceType($absenceType);
					if ($success)
					{
						$testError = "Failure to detect invalid name field";
						$testResult = "Fail";
					}	
				}
				else
				{
					$testError = "Unrecognised absenceType record in DB. "
								 .$absenceType[ABS_TYPE_NAME];
					$testResult = "Fail";
				}
			}
		}
	}
	
    rowTestTableOutput(__FUNCTION__,$testTitle,$testResult,$testError);
}

/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableTest_Update_003
 *
 * Test to ensure that attempt to update a record using an invalid usesLeave field fails.
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function AbsenceTypeTableTest_Update_003()
{
	$testTitle 	= "Invalid Update attempt - usesLeave field";
	$testResult = "Pass";
	$testError  = "";
	
  	//RETRIEVE
  	$filter[ABS_TYPE_NAME] = "Sickness Leave";
	$absenceTypes = RetrieveAbsenceTypes($filter);

	if ($absenceTypes != NULL)
	{
		if (count($absenceTypes) <> 1)
		{
			$testError = "Count of records is incorrect. count = ".count($absenceTypes);
			$testResult = "Fail";
		}
		
		foreach($absenceTypes as $absenceType)
		{
			if ($absenceType <> NULL)
			{	
				if ($absenceType[ABS_TYPE_NAME] 	== "Sickness Leave")
				{
					$absenceType[ABS_TYPE_USES_LEAVE] = 2;
					$success = UpdateAbsenceType($absenceType);
					if ($success)
					{
						$testError = "Failure to detect invalid uses leave field";
						$testResult = "Fail";
					}	
				}
				else
				{
					$testError = "Unrecognised absenceType record in DB. "
								 .$absenceType[ABS_TYPE_NAME];
					$testResult = "Fail";
				}
			}
		}
	}
	
    rowTestTableOutput(__FUNCTION__,$testTitle,$testResult,$testError);
}

/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableTest_Update_004
 *
 * Test to ensure that attempt to update a record using an invalid canBeDenied field fails.
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function AbsenceTypeTableTest_Update_004()
{
	$testTitle 	= "Invalid Update attempt - canBeDenied field";
	$testResult = "Pass";
	$testError  = "";
	
  	//RETRIEVE
  	$filter[ABS_TYPE_NAME] = "Sickness Leave";
	$absenceTypes = RetrieveAbsenceTypes($filter);

	if ($absenceTypes != NULL)
	{
		if (count($absenceTypes) <> 1)
		{
			$testError = "Count of records is incorrect. count = ".count($absenceTypes);
			$testResult = "Fail";
		}
		
		foreach($absenceTypes as $absenceType)
		{
			if ($absenceType <> NULL)
			{	
				if ($absenceType[ABS_TYPE_NAME] 	== "Sickness Leave")
				{
					$absenceType[ABS_TYPE_CAN_BE_DENIED] = 2;
					$success = UpdateAbsenceType($absenceType);
					if ($success)
					{
						$testError = "Failure to detect invalid can be denied field";
						$testResult = "Fail";
					}	
				}
				else
				{
					$testError = "Unrecognised absenceType record in DB. "
							     .$absenceType[ABS_TYPE_NAME];
					$testResult = "Fail";
				}
			}
		}
	}
	
    rowTestTableOutput(__FUNCTION__,$testTitle,$testResult,$testError);
}

/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableTest_Delete_001
 *
 * Ensure that attempting to delete an invalid key from the database fails. 
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function 	AbsenceTypeTableTest_Delete_001()
{
	$testTitle 	= "Invalid Delete attempt - deleting a key that doesn't exist in DB.";
	$testResult = "Pass";
	$testError  = "";
	
	$invalidID = 100;
	$deletedRows = DeleteAbsenceType($invalidID);

	if ($deletedRows <> 0)
	{
		$testError  = "Unexpected success result when attempting to delete "
					 ."a key that doesn't exist. Deleted Rows = ".$deletedRows;
		$testResult = "Fail";
	}
	
    rowTestTableOutput(__FUNCTION__,$testTitle,$testResult,$testError);
}

/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableTest_Delete_002
 *
 * Ensure that attempting to delete a valid key from the database succeeds.
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function 	AbsenceTypeTableTest_Delete_002($validID)
{
	$testTitle 	= "Valid Delete attempt - deleting a key that exists in DB.";
	$testResult = "Pass";
	$testError  = "";
	
	$deletedRows = DeleteAbsenceType($validID);
	if ($deletedRows <> 1)
	{
		$testError  = "Failed to delete a valid key.";
		$testResult = "Fail";
	}
	
    rowTestTableOutput(__FUNCTION__,$testTitle,$testResult,$testError);
}

/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableCreate
 *
 * This function performs a suite of unit tests related to the Create processing for the
 * absence type table.
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function testAbsenceTypeTableCreate()
{
	CreateNewDatabase();		//Start our testing with an empty database.

	//--------------------------------------------------------------
	// Run the tests
	//--------------------------------------------------------------
	AbsenceTypeTableTest_Create_001();
	AbsenceTypeTableTest_Create_002();
	AbsenceTypeTableTest_Create_003();
	AbsenceTypeTableTest_Create_004();
}


/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableRetrieve
 *
 * This function performs a suite of unit tests related to the Retrieve processing for the
 * absence type table.
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function testAbsenceTypeTableRetrieve()
{
	CreateNewDatabase();		//Start our testing with an empty database.

	//--------------------------------------------------------------
	// Create some sample data which will be used in retrieve tests.
	//--------------------------------------------------------------
	$absenceType = CreateAbsenceType( "Sick Leave"		,FALSE,FALSE);
	$absenceType = CreateAbsenceType( "Annual Leave"	,TRUE,TRUE);
	$absenceType = CreateAbsenceType( "Training" 		,FALSE,TRUE);
	$absenceType = CreateAbsenceType( "Emergency Leave"	,TRUE,FALSE);
	
	//--------------------------------------------------------------
	// Run the tests
	//--------------------------------------------------------------
	AbsenceTypeTableTest_Retrieve_001();
	AbsenceTypeTableTest_Retrieve_002();
	AbsenceTypeTableTest_Retrieve_003();
	AbsenceTypeTableTest_Retrieve_004();
	AbsenceTypeTableTest_Retrieve_005();
	AbsenceTypeTableTest_Retrieve_006();
	AbsenceTypeTableTest_Retrieve_007();
}

/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableUpdate
 *
 * This function performs a suite of unit tests related to the Update processing for the
 * absence type table.
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function testAbsenceTypeTableUpdate()
{
	CreateNewDatabase();		//Start our testing with an empty database.

	//--------------------------------------------------------------
	// Create some sample data which will be used in the update tests.
	//--------------------------------------------------------------
	$absenceType = CreateAbsenceType( "Sick Leave"		,FALSE,FALSE);

	//--------------------------------------------------------------
	// Run the tests
	//--------------------------------------------------------------
	AbsenceTypeTableTest_Update_001();
	AbsenceTypeTableTest_Update_002();
	AbsenceTypeTableTest_Update_003();
	AbsenceTypeTableTest_Update_004();
}


/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTableDelete
 *
 * This function performs a suite of unit tests related to the Delete processing for the
 * absence type table.
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function testAbsenceTypeTableDelete()
{
	CreateNewDatabase();		//Start our testing with an empty database.

	//--------------------------------------------------------------
	// Create some sample data which will be used in the delete tests.
	//--------------------------------------------------------------
	$absenceType = CreateAbsenceType( "Sick Leave",FALSE,FALSE);

	//--------------------------------------------------------------
	// Run the tests
	//--------------------------------------------------------------
	AbsenceTypeTableTest_Delete_001();
	AbsenceTypeTableTest_Delete_002($absenceType[ABS_TYPE_ID]);
}

/*--------------------------------------------------------------------------------------
 * Function AbsenceTypeTable
 *
 * This function performs a suite of unit tests for the absence type table functions.
 *
 * @return None
 *-------------------------------------------------------------------------------------*/
function testAbsenceTypeTable()
{
	//------------------------------------------------------------------------------------
	// Turn off Warnings, as we are intentionally going to be passing invalid data as part
	// of the unit tests.
	//------------------------------------------------------------------------------------
	error_reporting(E_ERROR | E_PARSE);

	beginTestTableOutput();
	testAbsenceTypeTableCreate();
	testAbsenceTypeTableRetrieve();
	testAbsenceTypeTableUpdate();
	testAbsenceTypeTableDelete();
	endTestTableOutput();
	
	// Re-enable all error reporting. 
	error_reporting(E_ALL);
};


?>