<?php
/*--------------------------------------------------------------------------------------
 * CONSTANTS
 *
 * These constants should be used when refering to the table and the fields within its
 * records.
 *-------------------------------------------------------------------------------------*/
define ("ABSENCE_TYPE_TABLE",               "absenceTypeTable");
define ("ABS_TYPE_ID",                      "absenceTypeID");
define ("ABS_TYPE_NAME",                    "absenceTypeName");
define ("ABS_TYPE_USES_LEAVE",              "usesAnnualLeave");
define ("ABS_TYPE_CAN_BE_DENIED",           "canBeDenied");

/*--------------------------------------------------------------------------------------
 * Function CreateAbsenceTypeTable
 *
 * This function creates the SQL statement needed to construct the AbsenceType table
 * in the database.
 *
 * @return (bool)  True if table is created successfully, false otherwise.
 *-------------------------------------------------------------------------------------*/
function CreateAbsenceTypeTable()
{
   $sql= "CREATE TABLE IF NOT EXISTS `mydb`.`absenceTypeTable` (
         `absenceTypeID` INT NOT NULL AUTO_INCREMENT,
         `absenceTypeName` VARCHAR(20) NOT NULL,
         `usesAnnualLeave` TINYINT(1) NOT NULL,
         `canBeDenied` TINYINT(1) NOT NULL,
         PRIMARY KEY (`absenceTypeID`));";
    
    return performSQL($sql);
}

/*--------------------------------------------------------------------------------------
 * Function CreateAbsenceType
 *
 * This function creates a new Absence Type row in the AbsenceTypeTable.
 *
 * $absenceTypeName (string) Textual name of the type of absence.
 * $usesAnnual Leave (boolean) Whether or not this type of absence uses annual leave.
 * $canBeDenied (boolean) Whether or not this type of absence can be denied.
 *
 * @return (array) If successful, an array is returned where each key represents a field
 *                 in the record. If unsuccessful, the return will be NULL.
 *-------------------------------------------------------------------------------------*/
function CreateAbsenceType($absenceTypeName,
                           $usesAnnualLeave,
                           $canBeDenied)
{
	$absenceType = NULL;
	//--------------------------------------------------------------------------------
	// Validate Input parameters
	//--------------------------------------------------------------------------------
	$inputIsValid = TRUE;

	if ( $absenceTypeName == NULL )
	{
		error_log ("Invalid NULL absenceTypeName passed to CreateAbsenceType.");
		$inputIsValid = FALSE;
	}
	
 	if ( $absenceTypeName == "" )
	{
		error_log ("Invalid empty absenceTypeName passed to CreateAbsenceType.");
		$inputIsValid = FALSE;
	}
 	
 	if ( ($usesAnnualLeave <> TRUE AND $usesAnnualLeave <> FALSE) )
	{
		error_log ("Invalid usesAnnualLeave parameter passed to CreateAbsenceType.");
		$inputIsValid = FALSE;
	}
	
	if ( ($canBeDenied <> TRUE AND $canBeDenied <> FALSE) )
	{
		error_log ("Invalid canBeDenied parameter passed to CreateAbsenceType.");
		$inputIsValid = FALSE;
	}
	
	//--------------------------------------------------------------------------------
	// Only attempt to insert a record in the database if the input parameters are ok.
	//--------------------------------------------------------------------------------
	if ($inputIsValid)
	{
		// Create an array with each field required in the record. 
		$absenceType[ABS_TYPE_ID]               = NULL;
    	$absenceType[ABS_TYPE_NAME]             = $absenceTypeName;
    	$absenceType[ABS_TYPE_USES_LEAVE]       = $usesAnnualLeave;
    	$absenceType[ABS_TYPE_CAN_BE_DENIED]    = $canBeDenied;
    
   		$success = sqlInsertAbsenceType($absenceType);
   		if (! $success )
		{
			error_log ("Failed to create absence type.");
			$absenceType = NULL;
		}
	}

	return $absenceType;
}

/*--------------------------------------------------------------------------------------
 * Function sqlInsertAbsenceType
 *
 * This function constructs the SQL statement required to insert a new record
 * into the absenceTypeTable
 *
 * &$absenceType (array) Array containing all of the fields required for the record.
 *
 * @return (bool) TRUE if insert into database was successful, false otherwise.
 *		   
 * Note: If successful then the ABS_TYPE_ID entry in the array
 *	     passed by the caller will be set to the ID of the record in the database. 
 *-------------------------------------------------------------------------------------*/
 function sqlInsertAbsenceType (&$absenceType)
{
	$absenceType[ABS_TYPE_ID] = NULL;
	
	$sql="INSERT INTO absenceTypeTable (absenceTypeName,usesAnnualLeave,canBeDenied) "
            . "VALUES ('"
            .$absenceType[ABS_TYPE_NAME]."','"
            .$absenceType[ABS_TYPE_USES_LEAVE]."','"
            .$absenceType[ABS_TYPE_CAN_BE_DENIED]."');";
    
   	$absenceType[ABS_TYPE_ID] = performSQLInsert($sql);

    return $absenceType[ABS_TYPE_ID] <> 0;
}

/*--------------------------------------------------------------------------------------
 * Function RetrieveAbsenceTypeByID
 *
 * This function uses the ID supplied as a parameter to construct an SQL select statement
 * and then performs this query, returning an array containing the key value pairs of the
 * record (or NULL if no record is found matching the id).
 *
 * $id (int) id of the record to retrieve from the database..
 *
 * @return (array) array of key value pairs representing the fields in the record, or 
 *                 NULL if no record exists with the id supplied.
 *-------------------------------------------------------------------------------------*/
function RetrieveAbsenceTypeByID($id)
{
	$filter[ABS_TYPE_ID] = $id;
	$resultArray = performSQLSelect(ABSENCE_TYPE_TABLE,$filter);
	
	$absenceType = NULL;
	
	if (count($resultArray) == 1)
	{
		$absenceType = $resultArray[0];
	}

	return $absenceType;
}

/*--------------------------------------------------------------------------------------
 * Function RetrieveAbsenceTypes
 *
 * This function constructs the SQL statement required to query the AbsenceTypeTable.
 *
 * $filter (array) Optional parameter. If supplied, then the array should contain a set
 *                 of key value pairs, where the keys correspond to one (or more) fields
 *                 in the record (see constants at top of file) and the values correspond
 *                 to the values to filter against (IE: The WHERE clause).
 *
 * @return (array) If successful, an array of arrays, where each element corresponds to 
 *                 a row from the query. If a failure occurs, return will be NULL. 
 *-------------------------------------------------------------------------------------*/
function RetrieveAbsenceTypes($filter=NULL)     
{
	$inputIsValid = TRUE;
	
	//--------------------------------------------------------------------------------
	// Validate Input parameters
	//--------------------------------------------------------------------------------
	if ( $filter <> NULL )
	{
		foreach ($filter as $key=>$value)
		{
			if (strcmp($key,ABS_TYPE_ID) == 0)
			{
				if (! is_numeric($value))
				{
					error_log ("Invalid AbsenceTypeID of ".$value.
								" passed to RetrieveAbsenceTypes.");
					$inputIsValid = FALSE;
				}

			}
			else if (strcmp($key,ABS_TYPE_NAME) == 0)
			{
				if ($value == NULL or $value == "")
				{
					error_log ("Invalid ABS_TYPE_NAME of ".$value.
								" passed to RetrieveAbsenceTypes.");
					$inputIsValid = FALSE;
				}
			}
			else if (strcmp($key,ABS_TYPE_USES_LEAVE) == 0)
			{
				if ($value <> TRUE AND $value <> FALSE)
				{
					error_log ("Invalid ABS_TYPE_USES_LEAVE of ".$value.
								" passed to RetrieveAbsenceTypes.");
					$inputIsValid = FALSE;
				}
			}
			else if (strcmp($key,ABS_TYPE_CAN_BE_DENIED) == 0)
			{
				if ($value <> TRUE AND $value <> FALSE)
				{
					error_log ("Invalid ABS_TYPE_CAN BE DENIED of ".$value.
								" passed to RetrieveAbsenceTypes.");
					$inputIsValid = FALSE;
				}
			}
			else
			{
				error_log ("Unknown Filter ".$key." passed to RetrieveAbsenceTypes.");
				$inputIsValid = FALSE;
			}
		}
	}
	
	//--------------------------------------------------------------------------------
	// Only attempt to perform query in the database if the input parameters are ok.
	//--------------------------------------------------------------------------------
	$result = NULL;
	if ($inputIsValid)
	{
		$result = performSQLSelect(ABSENCE_TYPE_TABLE,$filter);
	}
	
	return $result;
}

/*--------------------------------------------------------------------------------------
 * Function UpdateAbsenceType
 *
 * This function constructs the SQL statement required to update a row in 
 * the AbsenceTypeTable.
 *
 * $fields (array) array of key value pairs, where keys correspond to fields in the
 *                 record (see constants at start of this file). Note, this array
 *                 MUST provide the id of the record (ABS_TYPE_ID) and one or more other
 *                 fields to be updated. 
 *
 * @return (bool) TRUE if update succeeds. FALSE otherwise. 
 *-------------------------------------------------------------------------------------*/
function UpdateAbsenceType ($fields)
{
	//--------------------------------------------------------------------------------
	// Validate Input parameters
	//--------------------------------------------------------------------------------
	$inputIsValid = TRUE;
	$validID = false;
	$countOfFields = 0;
	
	foreach ($fields as $key=>$value)
	{
		if ($key == ABS_TYPE_ID)
		{
			$record = RetrieveAbsenceTypeByID ($value);
			if ($record <> NULL)
			{
				$validID = true;
				$countOfFields++;
			}
		}
		else if ($key == ABS_TYPE_NAME)
		{
			$countOfFields++;

			if ( $value == NULL OR $value="" )
			{
				error_log ("Invalid ABS_TYPE_NAME passed to UpdateAbsenceType.");
				$inputIsValid = FALSE;
			}

		}	
		else if ($key == ABS_TYPE_USES_LEAVE)
		{
			$countOfFields++;

			if ( $value <> TRUE AND $value <> FALSE )
			{
				error_log ("Invalid ABS_TYPE_USES_LEAVE passed to UpdateAbsenceType.");
				$inputIsValid = FALSE;
			}
		}
		else if ($key == ABS_TYPE_CAN_BE_DENIED)
		{
			$countOfFields++;

			if ( $value <> TRUE AND $value <> FALSE )
			{
				error_log ("Invalid ABS_TYPE_CAN_BE_DENIED passed to UpdateAbsenceType.");
				$inputIsValid = FALSE;
			}
		}
		else
		{
			error_log ("Invalid field passed to UpdateAbsenceType. $key=".$key);
			$inputIsValid = FALSE;
		}
	}
	
	if (!$validID)
	{
		error_log ("No valid ID supplied in call to UpdateAbsenceType.");
		$inputIsValid = FALSE;
	}
	
	if ($countOfFields < 2)
	{
		error_log ("Insufficent fields supplied in call to UpdateAbsenceType.");
		$inputIsValid = FALSE;
	}
	
	//--------------------------------------------------------------------------------
	// Only attempt to update a record in the database if the input parameters are ok.
	//--------------------------------------------------------------------------------
	$success = false;
	
	if ($inputIsValid)
	{    
		$success = performSQLUpdate(ABSENCE_TYPE_TABLE, ABS_TYPE_ID,$fields); 	
    }
    
    return $success;
}

/*--------------------------------------------------------------------------------------
 * Function DeleteAbsenceType
 *
 * This function constructs the SQL statement required to delete a row in 
 * the AbsenceTypeTable.
 *
 * $ID(integer) ID of the record to be removed from the table. This should be set to 
 *              the ABS_TYPE_ID value of the record you wish to delete.
 *
 * @return (int) count of rows deleted. 0 means delete was unsuccessful, usually because
 *               one or more records in the database refers to this absenceType.
 *-------------------------------------------------------------------------------------*/
function DeleteAbsenceType($ID)
{
	$isValidRequest = TRUE;
	
	//-----------------------------------------------------------------------------------
	// Ensure there are no AdHocAbsenceRequest records which reference this absence type.
	//-----------------------------------------------------------------------------------
	$filter[AD_HOC_ABSENCE_TYPE_ID] = $ID;
	$adHocAbsenceRequests = RetrieveAdHocAbsenceRequests($filter);
	if ($adHocAbsenceRequests <> NULL)
	{
		error_log ("Attempt to DeleteAbsenceType failed. ".
				   "One or more adHocAbsenceRequest records exist with an ".
				   "absence type ID of ".$ID);
		$isValidRequest = FALSE;
	}
	
	//-----------------------------------------------------------------------------------
	// Ensure there are no ApprovedAbsenceBooking records which reference this absence type.
	//-----------------------------------------------------------------------------------
	unset($filter);
	$filter[APPR_ABS_ABS_TYPE_ID] = $ID;
	$approvedBookings = RetrieveApprovedAbsenceBookings($filter);
	if ($approvedBookings <> NULL)
	{
		error_log ("Attempt to DeleteAbsenceType failed. ".
				   "One or more approvedAbsenceBooking records exist with an ".
				   "absence type ID of ".$ID);
		$isValidRequest = FALSE;
	}

	
	$result = 0;
	if ($isValidRequest)
	{
		$sql ="DELETE FROM absenceTypeTable WHERE absenceTypeID=".$ID.";";
    	$result = performSQLDelete($sql);
    }
    return $result;
}

?>
