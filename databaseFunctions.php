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
CreateNewDatabase();
    



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

function CreateDefaultRecordsIfRequired() {
    
    //Q. Are there any employees in our database?
    $employees = RetrieveEmployees();
    if (count($employees) == 0) {
        
        //No employees. Let's set up the database with a default admin account.
        //Userguide should instruct system admin to delte this account as
        //soon as real employees accounts have been created.
        $role = CreateCompanyRole("Admin", 0);
        CreateEmployee("admin", "admin@admin.com", "admin", "2015-01-01", 20, NULL, $role[COMP_ROLE_ID], 1, 1);
    }
    
    $dates = RetrieveDates();
    if (count($dates) == 0) {
        //We also need to populate the database with date records.
        date_default_timezone_set('UTC');
        $date = '2015-01-01';
        $end_date = '2055-12-31';

        while (strtotime($date) <= strtotime($end_date)) {
            CreateDate($date, NULL);
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }
    }
}

function CreateNewDatabase($destroyExistingDB = false, $createWithTestData = false) {
    if ($destroyExistingDB) {
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
    CreateDefaultRecordsIfRequired();

    if ($createWithTestData) {

        $annualLeave = CreateAbsenceType("Annual Leave", 1, 1);
        $training = CreateAbsenceType("Training", 0, 1);
        $sickness = CreateAbsenceType("Sickness", 0, 0);
        $compasionate = CreateAbsenceType("Compasionate Leave", 0, 1);

        $cashier = CreateCompanyRole("Cashier", 3);
        $customerAdvisor = CreateCompanyRole("Customer Advisor", 2);
        $manager = CreateCompanyRole("Manager", 1);

        $steveBrookstein = CreateEmployee("Steve Brookstein", "stevebrookstein@test.com", "zaq12wsx", "2005-01-01", 20, NULL, $cashier[COMP_ROLE_ID], 0, 0);

        $shayneWard = CreateEmployee("Shane Ward", "shaneWard@test.com", "zaq12wsx", "2006-01-01", 20, NULL, $cashier[COMP_ROLE_ID], 0, 0);

        $leonaLewis = CreateEmployee("Leona Lewis", "leonalewis@test.com", "zaq12wsx", "2007-01-01", 20, NULL, $cashier[COMP_ROLE_ID], 0, 0);

        $leonJackson = CreateEmployee("Leon Jackson", "leonjackson@test.com", "zaq12wsx", "2008-01-01", 20, NULL, $cashier[COMP_ROLE_ID], 0, 0);

        $alexandraBurke = CreateEmployee("Alexandra Burke", "alexburke@test.com", "zaq12wsx", "2009-01-01", 20, NULL, $cashier[COMP_ROLE_ID], 0, 0);

        $joeMcElderry = CreateEmployee("Joe McElderry", "JoeMcElderry@test.com", "zaq12wsx", "2010-01-01", 20, NULL, $customerAdvisor[COMP_ROLE_ID], 0, 0);

        $mattCardle = CreateEmployee("Matt Cardle", "mattCardle@test.com", "zaq12wsx", "2011-01-01", 20, NULL, $customerAdvisor[COMP_ROLE_ID], 0, 0);
        $jamesArthur = CreateEmployee("James Arthur", "jamesarthur@test.com", "zaq12wsx", "2012-01-01", 20, NULL, $customerAdvisor[COMP_ROLE_ID], 0, 0);

        $samBailey = CreateEmployee("Sam Bailey", "sambailey@test.com", "zaq12wsx", "2013-01-01", 20, NULL, $customerAdvisor[COMP_ROLE_ID], 0, 0);

        $benHaenow = CreateEmployee("Ben Haenow", "benHaenow@test.com", "zaq12wsx", "2014-01-01", 20, NULL, $manager[COMP_ROLE_ID], 0, 1);


        $dates = RetrieveDates();

        if (count($dates) == 0) {
            date_default_timezone_set('UTC');

            // Start date
            $date = '2015-01-01';

            // End date
            $end_date = '2055-12-31';

            while (strtotime($date) <= strtotime($end_date)) {
                CreateDate($date, NULL);
                $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
            }
        }

        $dateID = RetrieveDateIDByDate("2015-04-03");
        $goodFriday = CreatePublicHoliday("Good Friday", $dateID);

        $dateID = RetrieveDateIDByDate("2015-04-06");
        $easterMonday = CreatePublicHoliday("Easter Monday", $dateID);

        $dateID = RetrieveDateIDByDate("2015-05-04");
        $earlyMay = CreatePublicHoliday("Early May Bank Holiday", $dateID);

        $dateID = RetrieveDateIDByDate("2015-05-25");
        $springHoliday = CreatePublicHoliday("Spring Bank Holiday", $dateID);

        $dateID = RetrieveDateIDByDate("2015-08-31");
        $summerHoliday = CreatePublicHoliday("Summer Bank Holiday", $dateID);

        $dateID = RetrieveDateIDByDate("2015-12-25");
        $christmasDay = CreatePublicHoliday("Christmas Day", $dateID);

        $dateID = RetrieveDateIDByDate("2015-12-28");
        $boxingDay = CreatePublicHoliday("Boxing Day (substitute day)", $dateID);

        $request = CreateMainVactionRequest($steveBrookstein[EMP_ID], "2015-01-10", "2015-01-15", "2015-02-10", "2015-02-15");

        $request = CreateMainVactionRequest($shayneWard[EMP_ID], "2015-01-10", "2015-01-15", "2015-02-10", "2015-02-15");
        $request = CreateMainVactionRequest($leonaLewis[EMP_ID], "2015-01-10", "2015-01-15", "2015-02-10", "2015-02-15");
        $request = CreateMainVactionRequest($leonJackson[EMP_ID], "2015-01-10", "2015-01-15", "2015-02-10", "2015-02-15");
        $request = CreateMainVactionRequest($alexandraBurke[EMP_ID], "2015-01-10", "2015-01-15", "2015-02-10", "2015-02-15");
        $request = CreateMainVactionRequest($joeMcElderry [EMP_ID], "2015-01-10", "2015-01-15", "2015-02-10", "2015-02-15");
        $request = CreateMainVactionRequest($jamesArthur [EMP_ID], "2015-01-10", "2015-01-15", "2015-02-10", "2015-02-15");
        $request = CreateMainVactionRequest($mattCardle[EMP_ID], "2015-01-10", "2015-01-15", "2015-02-10", "2015-02-15");
        $request = CreateMainVactionRequest($samBailey[EMP_ID], "2015-01-10", "2015-01-15", "2015-02-10", "2015-02-15");
        $request = CreateMainVactionRequest($benHaenow [EMP_ID], "2015-01-10", "2015-01-15", "2015-02-10", "2015-02-15");


        $request = CreateAdHocAbsenceRequest($steveBrookstein[EMP_ID], "2015-03-10", "2015-03-15", $annualLeave[ABS_TYPE_ID]);
        $request = CreateAdHocAbsenceRequest($shayneWard[EMP_ID], "2015-03-10", "2015-03-15", $annualLeave[ABS_TYPE_ID]);
        $request = CreateAdHocAbsenceRequest($leonaLewis[EMP_ID], "2015-03-10", "2015-03-15", $sickness[ABS_TYPE_ID]);
        $request = CreateAdHocAbsenceRequest($leonJackson[EMP_ID], "2015-03-10", "2015-03-15", $sickness[ABS_TYPE_ID]);
        $request = CreateAdHocAbsenceRequest($alexandraBurke[EMP_ID], "2015-03-10", "2015-03-15", $training[ABS_TYPE_ID]);
        $request = CreateAdHocAbsenceRequest($joeMcElderry[EMP_ID], "2015-03-10", "2015-03-15", $training[ABS_TYPE_ID]);
        $request = CreateAdHocAbsenceRequest($mattCardle[EMP_ID], "2015-03-10", "2015-03-15", $training[ABS_TYPE_ID]);
        $request = CreateAdHocAbsenceRequest($jamesArthur[EMP_ID], "2015-03-10", "2015-03-15", $training[ABS_TYPE_ID]);
        $request = CreateAdHocAbsenceRequest($samBailey[EMP_ID], "2015-03-10", "2015-03-15", $compasionate[ABS_TYPE_ID]);
        $request = CreateAdHocAbsenceRequest($benHaenow[EMP_ID], "2015-03-10", "2015-03-15", $compasionate[ABS_TYPE_ID]);
    }
}

?>