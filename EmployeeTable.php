<?php

/* --------------------------------------------------------------------------------------
 * CONSTANTS
 *
 * These constants should be used when refering to the table and the fields within its
 * records.
 * ------------------------------------------------------------------------------------- */
define("EMPLOYEE_TABLE", "employeeTable");
define("EMP_ID", "employeeID");
define("EMP_NAME", "employeeName");
define("EMP_EMAIL", "emailAddress");
define("EMP_PASSWORD", "password");
define("EMP_DATEJOINED", "dateJoinedTheCompany");
define("EMP_LEAVE_ENTITLEMENT", "annualLeaveEntitlement");
define("EMP_MAIN_VACATION_REQ_ID", "mainVacationRequestID");
define("EMP_COMPANY_ROLE", "companyRole_companyRoleID");

/* --------------------------------------------------------------------------------------
 * Function CreateEmployeeTable
 *
 * This function creates the SQL statement needed to construct the table
 * in the database.
 *
 * @return (bool)  True if table is created successfully, false otherwise.
 * ------------------------------------------------------------------------------------- */

function CreateEmployeeTable() {
    $sql = "CREATE TABLE IF NOT EXISTS `mydb`.`EmployeeTable` (
         `employeeID` INT NOT NULL AUTO_INCREMENT,
         `employeeName` VARCHAR(50) NOT NULL,
         `emailAddress` VARCHAR(50) NOT NULL,
         `password` VARCHAR(20) NOT NULL,
         `dateJoinedTheCompany` DATE NOT NULL,
         `annualLeaveEntitlement` INT(1) NOT NULL,
         `mainVacationRequestID` INT NULL,
         `companyRole_companyRoleID` INT NOT NULL,
         PRIMARY KEY (`employeeID`),
         INDEX `fk_Employee_companyRole1_idx` (`companyRole_companyRoleID` ASC),
         CONSTRAINT `fk_Employee_companyRole1`
         FOREIGN KEY (`companyRole_companyRoleID`)
         REFERENCES `mydb`.`companyRoleTable` (`companyRoleID`)
         ON DELETE NO ACTION
         ON UPDATE NO ACTION);";

    performSQL($sql);
}

/* --------------------------------------------------------------------------------------
 * Function CreateEmployee
 *
 * This function creates a new Employee record in the table.
 *
 * $employeeName (string)  Name of the employee
 * $emailAddress (string)  Email address of the employee
 * $password (string)      Password for the employee
 * $dateJoinedTheCompany (string) Date joined the company. Must be in the form YYYY-MM-DD
 * $annualLeaveEntitlement (int) Number of days annual leave that the employee is entitled to.
 * $mainVacationRequestID (int) ID of the main Vacation Request record associated with 
 *                         this employee. Note this parameter may be set to NULL if 
 *                         the employee has no mainVacationRequest at time of creation.
 * $companyRoleID (int) ID of the company role record associated with this employee.
 *
 * @return (array) If successful, an array is returned where each key represents a field
 *                 in the record. If unsuccessful, the return will be NULL.
 * ------------------------------------------------------------------------------------- */

function CreateEmployee($employeeName, $emailAddress, $password, $dateJoinedTheCompany, $annualLeaveEntitlement, $mainVacationRequestID, $companyRoleID) {
    $employee = NULL;
    //--------------------------------------------------------------------------------
    // Validate Input parameters
    //--------------------------------------------------------------------------------
    $inputIsValid = TRUE;

    if (isNullOrEmptyString($employeeName)) {
        error_log("Invalid employeeName passed to CreateEmployee.");
        $inputIsValid = FALSE;
    }

    if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address passed to CreateEmployee.");
        $inputIsValid = FALSE;
    }

    //Todo add password length and format check.
    //Todo MD5 encode password.
    if (isNullOrEmptyString($password)) {
        error_log("Invalid password passed to CreateEmployee.");
        $inputIsValid = FALSE;
    }

    if (!isValidDate($dateJoinedTheCompany)) {
        error_log("Invalid dateJoinedTheCompany passed to CreateEmployee.");
        $inputIsValid = FALSE;
    }

    if (!is_numeric($annualLeaveEntitlement)) {
        error_log("Invalid annualLeaveEntitlement passed to CreateEmployee.");
        $inputIsValid = FALSE;
    }

    if ($mainVacationRequestID <> NULL) {
        $record = RetrieveMainVacationRequestByID($mainVacationRequestID);

        if ($record == NULL) {
            error_log("Invalid mainVacationRequestID passed to CreateEmployee.");
            $inputIsValid = FALSE;
        }
    }

    $record = RetrieveCompanyRoleByID($companyRoleID);

    if ($record == NULL) {
        error_log("Invalid companyRoleID passed to CreateEmployee.");
        $inputIsValid = FALSE;
    }

    //--------------------------------------------------------------------------------
    // Only attempt to insert a record in the database if the input parameters are ok.
    //--------------------------------------------------------------------------------
    if ($inputIsValid) {
        // Create an array with each field required in the record. 
        $employee[EMP_ID] = NULL;
        $employee[EMP_NAME] = $employeeName;
        $employee[EMP_EMAIL] = $emailAddress;
        $employee[EMP_PASSWORD] = $password;
        $employee[EMP_DATEJOINED] = $dateJoinedTheCompany;
        $employee[EMP_LEAVE_ENTITLEMENT] = $annualLeaveEntitlement;
        $employee[EMP_MAIN_VACATION_REQ_ID] = $mainVacationRequestID;
        $employee[EMP_COMPANY_ROLE] = $companyRoleID;

        $success = sqlInsertEmployee($employee);
        if (!$success) {
            error_log("Failed to create Employee. " . print_r($employee));
            $employee = NULL;
        }
    }
    return $employee;
}

/* --------------------------------------------------------------------------------------
 * Function sqlInsertEmployee 
 *
 * This function constructs the SQL statement required to insert a new record
 * into the employee table.
 *
 * &$employee(array) Array containing all of the fields required for the record.
 *
 * @return (bool) TRUE if insert into database was successful, false otherwise.
 * 		   
 * Note: If successful then the EMP_ID entry in the 
 * array passed by the caller will be set to the ID of the record in the database. 
 * ------------------------------------------------------------------------------------- */

function sqlInsertEmployee(&$employee) {
    $sql = "INSERT INTO EmployeeTable (employeeName,emailAddress,password," .
            "annualLeaveEntitlement,dateJoinedTheCompany,companyRole_companyRoleID) " .
            "VALUES ('" . $employee[EMP_NAME] . "','" . $employee[EMP_EMAIL] . "','"
            . $employee[EMP_PASSWORD] . "','" . $employee[EMP_LEAVE_ENTITLEMENT] .
            "','" . $employee[EMP_DATEJOINED] . "','" . $employee[EMP_COMPANY_ROLE] . "');";

    $employee[EMP_ID] = performSQLInsert($sql);
    return $employee[EMP_ID] <> 0;
}

/* --------------------------------------------------------------------------------------
 * Function RetrieveEmployeeByID
 *
 * This function uses the ID supplied as a parameter to construct an SQL select statement
 * and then performs this query, returning an array containing the key value pairs of the
 * record (or NULL if no record is found matching the id).
 *
 * $id (int) id of the record to retrieve from the database..
 *
 * @return (array) array of key value pairs representing the fields in the record, or 
 *                 NULL if no record exists with the id supplied.
 * ------------------------------------------------------------------------------------- */

function RetrieveEmployeeByID($id) {
    $filter[EMP_ID] = $id;
    $resultArray = performSQLSelect(EMPLOYEE_TABLE, $filter);

    $result = NULL;

    if (count($resultArray) == 1) {      //Check to see if record was found.
        $result = $resultArray[0];
    }

    return $result;
}

/* --------------------------------------------------------------------------------------
 * Function RetrieveEmployees
 *
 * This function constructs the SQL statement required to query the employees table.
 *
 * $filter (array) Optional parameter. If supplied, then the array should contain a set
 *                 of key value pairs, where the keys correspond to one (or more) fields
 *                 in the record (see constants at top of file) and the values correspond
 *                 to the values to filter against (IE: The WHERE clause).
 *
 * @return (array) If successful, an array of arrays, where each element corresponds to 
 *                 a row from the query. If a failure occurs, return will be NULL. 
 * ------------------------------------------------------------------------------------- */

function RetrieveEmployees($filter = NULL) {
    $inputIsValid = TRUE;

    //--------------------------------------------------------------------------------
    // Validate Input parameters
    //--------------------------------------------------------------------------------
    if ($filter <> NULL) {
        foreach ($filter as $key => $value) {
            if (strcmp($key, EMP_ID) == 0) {
                if (!is_numeric($value)) {
                    error_log("Invalid EMP_ID of " . $value .
                            " passed to RetrieveEmployees.");
                    $inputIsValid = FALSE;
                }
            } else if (strcmp($key, EMP_NAME) == 0) {
                if (isNullOrEmptyString($value)) {
                    error_log("Invalid EMP_NAME passed to RetrieveEmployees.");
                    $inputIsValid = FALSE;
                }
            } else if (strcmp($key, EMP_EMAIL) == 0) {
                if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
                    error_log("Invalid EMP_EMAIL of " . $value .
                            " passed to RetrieveEmployees.");
                    $inputIsValid = FALSE;
                }
            } else if (strcmp($key, EMP_PASSWORD) == 0) {
                if (isNullOrEmptyString($value)) {
                    error_log("Invalid EMP_PASSWORD passed to RetrieveEmployees.");
                    $inputIsValid = FALSE;
                }
            } else if (strcmp($key, EMP_DATEJOINED) == 0) {
                if (!isValidDate($value)) {
                    error_log("Invalid EMP_DATEJOINED of " . $value .
                            " passed to RetrieveEmployees.");
                    $inputIsValid = FALSE;
                }
            } else if (strcmp($key, EMP_LEAVE_ENTITLEMENT) == 0) {
                if (!is_numeric($value)) {
                    error_log("Invalid EMP_LEAVE_ENTITLEMENT of " . $value .
                            " passed to RetrieveEmployees.");
                    $inputIsValid = FALSE;
                }
            } else if (strcmp($key, EMP_MAIN_VACATION_REQ_ID) == 0) {
                if (!is_numeric($value)) {
                    error_log("Invalid EMP_MAIN_VACATION_REQ_ID of " . $value .
                            " passed to RetrieveEmployees.");
                    $inputIsValid = FALSE;
                }
            } else if (strcmp($key, EMP_COMPANY_ROLE) == 0) {
                if (!is_numeric($value)) {
                    error_log("Invalid EMP_COMPANY_ROLE of " . $value .
                            " passed to RetrieveEmployees.");
                    $inputIsValid = FALSE;
                }
            } else {
                error_log("Unknown Filter " . $key . " passed to RetrieveEmployees.");
                $inputIsValid = FALSE;
            }
        }
    }

    //--------------------------------------------------------------------------------
    // Only attempt to perform query in the database if the input parameters are ok.
    //--------------------------------------------------------------------------------
    $result = NULL;
    if ($inputIsValid) {
        $result = performSQLSelect(EMPLOYEE_TABLE, $filter);
    }
    return $result;
}

/* --------------------------------------------------------------------------------------
 * Function UpdateEmployee
 *
 * This function constructs the SQL statement required to update a row in 
 * the Employee table.
 *
 * $fields (array) array of key value pairs, where keys correspond to fields in the
 *                 record (see constants at start of this file). Note, this array
 *                 MUST provide the id of the record (EMP_ID) and one or more other
 *                 fields to be updated. 
 *
 * @return (bool) TRUE if update succeeds. FALSE otherwise. 
 * ------------------------------------------------------------------------------------- */

function UpdateEmployee($fields) {
    //--------------------------------------------------------------------------------
    // Validate Input parameters
    //--------------------------------------------------------------------------------
    $inputIsValid = TRUE;
    $validID = false;
    $countOfFields = 0;

    foreach ($fields as $key => $value) {
        if ($key == EMP_ID) {
            $record = RetrieveEmployeeByID($value);
            if ($record <> NULL) {
                $validID = true;
                $countOfFields++;
            }
        } else if ($key == EMP_NAME) {
            $countOfFields++;

            if (isNullOrEmptyString($value)) {
                error_log("Invalid EMP_NAME passed to UpdateEmployee.");
                $inputIsValid = FALSE;
            }
        } else if ($key == EMP_EMAIL) {
            $countOfFields++;

            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                error_log("Invalid email address passed to UpdateEmployee.");
                $inputIsValid = FALSE;
            }
        } else if ($key == EMP_PASSWORD) {
            $countOfFields++;

            if ($value == "" OR $value == NULL) {
                error_log("Invalid EMP_PASSWORD passed to UpdateEmployee.");
                $inputIsValid = FALSE;
            }
        } else if ($key == EMP_DATEJOINED) {
            $countOfFields++;

            if (!isValidDate($value)) {
                error_log("Invalid EMP_DATEJOINED passed to UpdateEmployee.");
                $inputIsValid = FALSE;
            }
        } else if ($key == EMP_LEAVE_ENTITLEMENT) {
            $countOfFields++;

            if (!is_numeric($value)) {
                error_log("Invalid EMP_LEAVE_ENTITLEMENT passed to UpdateEmployee.");
                $inputIsValid = FALSE;
            }
        } else if ($key == EMP_MAIN_VACATION_REQ_ID) {
            $record = RetrieveMainVacationRequestByID($value);

            if ($record == NULL) {
                error_log("Invalid EMP_MAIN_VACATION_REQ_ID passed to UpdateEmployee.");
                $inputIsValid = FALSE;
            }
        } else if ($key == EMP_COMPANY_ROLE) {
            $countOfFields++;

            $record = RetrieveCompanyRoleByID($value);

            if ($record == NULL) {
                error_log("Invalid EMP_COMPANY_ROLE passed to UpdateEmployee.");
                $inputIsValid = FALSE;
            }
        } else {
            error_log("Invalid field passed to UpdateEmployee. $key=" . $key);
            $inputIsValid = FALSE;
        }
    }

    if (!$validID) {
        error_log("No valid ID supplied in call to UpdateEmployee.");
        $inputIsValid = FALSE;
    }

    if ($countOfFields < 2) {
        error_log("Insufficent fields supplied in call to UpdateEmployee.");
        $inputIsValid = FALSE;
    }

    //--------------------------------------------------------------------------------
    // Only attempt to update a record in the database if the input parameters are ok.
    //--------------------------------------------------------------------------------
    $success = false;

    if ($inputIsValid) {
        $success = performSQLUpdate(EMPLOYEE_TABLE, EMP_ID, $fields);
    }
    return $success;
}

/* --------------------------------------------------------------------------------------
 * Function DeleteEmployee
 *
 * This function constructs the SQL statement required to delete a row in 
 * the employee table.
 *
 * $ID(integer) ID of the record to be removed from the table. This should be set to 
 *              the EMP_ID value of the record you wish to delete.
 *
 * @return (int) count of rows deleted. 0 means delete was unsuccessful. 
 * ------------------------------------------------------------------------------------- */

function DeleteEmployee($ID) {
    $result = 0;

    $employee = RetrieveEmployeeByID($ID);

    if ($employee != NULL) {
        if ($employee[EMP_MAIN_VACATION_REQ_ID] <> NULL) {
            DeleteMainVacatioNRequest($employee[EMP_MAIN_VACATION_REQ_ID]);
        }

        $filter[AD_HOC_EMP_ID] = $ID;
        $adHocAbsenceRequests = RetrieveAdHocAbsenceRequests($filter);

        foreach ((array) $adHocAbsenceRequests as $value) {
            DeleteAdHocAbsenceRequest($value[AD_HOC_REQ_ID]);
        }

        unset($filter);
        $filter[APPR_ABS_EMPLOYEE_ID] = $ID;
        $approvedAbsenceBookings = RetrieveApprovedAbsenceBookings($filter);

        if ($approvedAbsenceBookings <> NULL) {
            foreach ($approvedAbsenceBookings as $value) {
                DeleteApprovedAbsenceBooking($value[APPR_ABS_BOOKING_ID]);
            }
        }

        $sql = "DELETE FROM employeeTable WHERE employeeID=" . $ID . ";";
        $result = performSQL($sql);
    }

    return $result;
}


/* --------------------------------------------------------------------------------------
 * Function GetEmployeeCount
 *
 * This function gets a count of employee records which match a given filter.
 *
 * $filter(array) array of key value pairs representing the fields of the record
 *                that should be filtered into the count.
 *
 * @return (int) count of rows that match this filter. 
 * ------------------------------------------------------------------------------------- */

function GetEmployeeCount(&$totalEmployees,&$employeesWithNoMainVacation) 
{
    $conn = $GLOBALS["connection"];

    $sql = "SELECT COUNT(*) FROM ".EMPLOYEE_TABLE;
   
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        printCallstackAndDie();
    }
    $data = mysqli_fetch_array($result);
    $totalEmployees = $data[0];  
     
     
    $sql = "SELECT COUNT(*) FROM ".EMPLOYEE_TABLE." WHERE mainVacationRequestID IS NULL";
   
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        printCallstackAndDie();
    }
    $data = mysqli_fetch_array($result);
    $employeesWithNoMainVacation = $data[0];  
}
?>
