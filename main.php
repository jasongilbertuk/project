<?php

//ini_set('display_startup_errors',1);
//ini_set('display_errors',1);
//error_reporting(-1);

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

include 'TestTable.php';
include 'TestAdHocRequestTable.php';
include 'TestCompanyRoleTable.php';
include 'TestEmployeeTable.php';
include 'TestMainVacationRequestTable.php';
include 'TestAbsenceTypeTable.php';
include 'TestApprovedAbsenceBookingTable.php';
include 'TestApprovedAbsenceBookingDateTable.php';
include 'TestDateTable.php';
include 'TestPublicHolidayTable.php';

function CreateNewDatabase()
{
    DropDB();
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
}


function testTables()
{
/*	testCompanyRoleTable();
	testEmployeeTable();
	testMainVacationRequestTable();
	testAbsenceTypeTable();
	testAdHocAbsenceRequestTable();
*/	testDateTable();
/*	testPublicHolidayTable();
	testApprovedAbsenceBookingTable();
	testApprovedAbsenceBookingDateTable();

 
 */
}
$connection = connectToSql("localhost","root","root");

createNewDatabase();
testTables();

mysqli_close($connection);  
?>

