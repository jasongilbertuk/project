<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

include 'databaseFunctions.php';
include 'AdHocRequestTable.php';
include 'CompanyRoleTable.php';
include 'EmployeeTable.php';
include 'MainVacationRequestTable.php';
include 'AbsenceTypeTable.php';
include 'ApprovedAbsenceBookingTable.php';
include 'ApprovedAbsenceBookingDateTable.php';
include 'DateTable.php';
include 'PublicHolidayTable.php';

include 'TestAdHocRequestTable.php';
include 'TestCompanyRoleTable.php';
include 'TestEmployeeTable.php';
include 'TestMainVacationRequestTable.php';
include 'TestAbsenceTypeTable.php';
include 'TestApprovedAbsenceBookingTable.php';
include 'TestApprovedAbsenceBookingDateTable.php';
include 'TestDateTable.php';
include 'TestPublicHolidayTable.php';

function createNewDatabase(&$connection)
{
	if ($connection)
	{
		mysqli_close($connection);  
	}
	
	$connection = connectToSql("localhost","root","root");

    dropDB($connection);
    createDB($connection);
    useDB($connection);
    createDateTable($connection);
    createPublicHolidayTable($connection);
    createAbsenceTypeTable($connection);
    createCompanyRoleTable($connection);
    createEmployeeTable($connection);
    createApprovedAbsenceBookingTable($connection);
    createApprovedAbsenceDateTable($connection);
    createAdHocAbsenceRequestTable($connection);
    createMainVacationRequestTable($connection);
}


function testTables($connection)
{
	testCompanyRoleTable($connection);
	testEmployeeTable($connection);
	testMainVacationRequestTable($connection);
	testAbsenceTypeTable($connection);
	testAdHocAbsenceRequestTable($connection);
	testDateTable($connection);
	testPublicHolidayTable($connection);
	testApprovedAbsenceBookingTable($connection);
	testApprovedAbsenceBookingDateTable($connection);
}

$connection = NULL;
createNewDatabase($connection);

testTables($connection);

mysqli_close($connection);  
$connection = NULL;
?>

