<?php
include "sessionmanagement.php";

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Administration</title>
    </head>
 
    <body>

        <?php if ($isAdministrator) { ?>
        <h2>Administrator Functions</h2>
        
        <a href="adminCompanyRoles.php">Admin Company Roles</a>
        <br/>
        <a href="adminEmployeeTable.php">Admin Employees</a>
        <br/>
        <a href="adminMainVacationRequests.php">Admin Main Vacation Requests</a>
        <br/>
        <a href="adminAbsenceTypes.php">Admin Absence Types</a>
        <br/>
        <a href="adminAdHocAbsenceRequest.php">Admin Ad Hoc Requests</a>
        <br/>
        <a href="adminApprovedAbsenceBookings.php">Admin Approved Absence Bookings</a>
        <br/>
        <a href="adminApprovedAbsenceBookingDate.php">Admin Approved Absence Booking Dates</a>
        <br/>
        <a href="adminDates.php">Admin Dates</a>
        <br/>
        <a href="adminPublicHolidays.php">Admin Public Holidays</a>
        <br/>
        <a href="administerVacation.php">Admin Vacation</a>
        <br/>
        <?php } ?>
        
        
        <?php if ($isManager) { ?>
        <h2>Office Manager  Functions</h2>
        <a href="administerVacation.php">An office manager function</a>
        <br/>
        <?php } ?>
        
        <h2>All Staff Functions</h2>
        <a href="employeeMainVacationRequest.php">Main Vacation Request</a>
        <br/>
        <a href="employeeDisplayDetails.php">Display Details</a>
        <br/>
        <a href="employeeAdHocRequests.php">Ad Hoc Requests</a>
        <br/>
        <br/>
        <a href="logout.php">Log out</a>
        
    </body>
</html>