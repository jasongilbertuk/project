<?php


include 'AdHocRequestTable.php';
include 'CompanyRoleTable.php';
include 'EmployeeTable.php';
include 'MainVacationRequestTable.php';
include 'AbsenceTypeTable.php';
include 'ApprovedAbsenceBookingTable.php';
include 'ApprovedAbsenceBookingDateTable.php';
include 'DateTable.php';
include 'PublicHolidayTable.php';
include 'KeyAlgorithms.php';

$connection = connectToSql("localhost", "root", "root");
CreateNewDatabase(false);
    



function isValidDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') == $date;
}

// Function for basic field validation (present and neither empty nor only white space
function IsNullOrEmptyString($question) {
    return (!isset($question) || trim($question) === '');
}

function printCallstackAndDie() {
    echo "Fatal Error. Please contact your system administrator.<br/>";

    $callers = debug_backtrace();

    echo "Dump Trace<br/>";
    foreach ($callers as $caller) {
        echo "Function:   " . $caller['function'] . "    Line:   " . $caller['line'] . "<br/>";
    }
    die();
}

function connectToSql($server, $username, $password) {
    $connection = mysqli_connect($server, $username, $password);
    if (!$connection) {
        printCallstackAndDie();
    }
    return $connection;
}

function performSQL($sql) {
    $result = FALSE;
    $conn = $GLOBALS["connection"];
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        error_log("PerformSQL failed. Sql = $sql");
    }
    return $result;
}

function performSQLDelete($sql) {
    $deletedRows = 0;

    $conn = $GLOBALS["connection"];
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $deletedRows = mysqli_affected_rows($conn);
    }

    return $deletedRows;
}

function performSQLInsert($sql) {
    $conn = $GLOBALS["connection"];
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        printCallstackAndDie();
    }
    return mysqli_insert_id($conn);
}

function performSQLSelect($tableName, $filter) {
    $conn = $GLOBALS["connection"];

    $sql = "SELECT * FROM " . $tableName;

    if ($filter <> NULL) {
        $sql = $sql . " WHERE ";

        foreach ($filter as $key => $value) {
            $whereClause[] = $key . "='" . $value . "'";
        }

        $sql = $sql . implode(" AND ", $whereClause);
    }
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        printCallstackAndDie();
    }
    $results = NULL;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $results[] = $row;
    }


    return $results;
}

function performSQLUpdate($tableName, $idFieldName, $fields) {
    $conn = $GLOBALS["connection"];
    $sql = "UPDATE " . $tableName . " SET ";

    if ($fields <> NULL) {
        foreach ($fields as $key => $value) {
            if (!is_numeric($key) AND $key <> $idFieldName) {
                if ($value <> NULL) {
                    $updateClause[] = $key . "='" . $value . "'";
                } else {
                    $updateClause[] = $key . "=NULL";
                }
            }
        }

        $sql = $sql . implode(",", $updateClause);
    }
    $sql = $sql . " WHERE " . $idFieldName . "='" . $fields[$idFieldName] . "';";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo mysqli_error($conn);
        printCallstackAndDie();
    }

    return TRUE;
}

function UseDB() {
    $sql = "USE mydb;";
    performSQL($sql);
}

function DropDB() {
    $sql = "DROP DATABASE IF EXISTS `mydb`;";
    performSQL($sql);
}

function CreateDB() {
    $sql = "CREATE SCHEMA IF NOT EXISTS `mydb`" .
            "DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;";
    performSQL($sql);
}

function CreateAdministratorAccount()
{
	$employees = RetrieveEmployees();
	if (count($employees) == 0)
	{
		CreateCompanyRole("Cashier",2);
		CreateEmployee("Sam Gilbert","samgilbertuk@hotmail.com","zaq12wsx","2014-11-01",20, NULL, 1,1,1);	
	}
}

function CreateNewDatabase($destroyExistingDB=false) {
    if ($destroyExistingDB)
    {
        DropDB();
    }
    CreateDB();
    UseDB();
    CreateDateTable();
    CreatePublicHolidayTable();
    CreateAbsenceTypeTable();
    CreateCompanyRoleTable();
    CreateEmployeeTable();
    CreateApprovedAbsenceBookingTable();
    CreateApprovedAbsenceDateTable();
    CreateAdHocAbsenceRequestTable();
    CreateMainVacationRequestTable();
    CreateAdministratorAccount();
    
}



?>