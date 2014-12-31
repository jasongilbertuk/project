<?php
include 'sessionmanagement.php';
include 'databaseFunctions.php';
if (isset($_POST["deleteApproved"])) {
    DeleteApprovedAbsenceBooking($_POST["deleteApproved"]);
}

if (isset($_POST["amendAdHoc"])) {   
    $url = "Location:editAdHocAbsenceRequest.php?ID=".$_POST["amendAdHoc"]."&back=employeeDisplayDetails.php";   
    header($url);
}

if (isset($_POST["deleteAdHoc"])) 
{
    DeleteAdHocAbsenceRequest($_POST["deleteAdHoc"]);
}


if (isset($_POST["amendMain"])) {   
    $url = "Location:editMainRequest.php?ID=".$_POST["amendMain"]."&back=employeeDisplayDetails.php";   
    header($url);
}

if (isset($_POST["deleteMain"])) 
{
    DeleteMainVacationRequest($_POST["deleteMain"]);
}



$employee = RetrieveEmployeeByID($userID);

$mainVacationRequest = RetrieveMainVacationRequestByID($employee[EMP_MAIN_VACATION_REQ_ID]);
$companyRole = RetrieveCompanyRoleByID($employee[EMP_COMPANY_ROLE]);

$filter[AD_HOC_EMP_ID] = $userID;
$adHocRequests = RetrieveAdHocAbsenceRequests($filter);

unset($filter);
$filter[APPR_ABS_EMPLOYEE_ID] = $userID;
$bookings = RetrieveApprovedAbsenceBookings($filter);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Display Employee Details</title>

    </head>

    <body>
        <a href="index.php">Back to Homepage</a>

        
        <h2>Employee Details</h2>
        <?php
        echo "ID               : " . $employee[EMP_ID] . "<br/>";
        echo "Name             : " . $employee[EMP_NAME] . "<br/>";
        echo "Email            : " . $employee[EMP_EMAIL] . "<br/>";
        echo "Password         : " . $employee[EMP_PASSWORD] . "<br/>";
        echo "Date Joined      : " . $employee[EMP_DATEJOINED] . "<br/>";
        echo "Leave Entitlement: " . $employee[EMP_LEAVE_ENTITLEMENT]. "<br/>";
        echo "Company Role     : " . $companyRole[COMP_ROLE_NAME]. "<br/>";
        echo "Is Admin         : " . $employee[EMP_ADMIN_PERM] . "<br/>";
        echo "Is Manager       : " . $employee[EMP_MANAGER_PERM] . "<br/>";
        echo "<br/>";
        echo "Annual Leave Aleady Booked:".CalculateEmployeeLeaveTaken($employee[EMP_ID]);
        ?>        

        <h2>Approved Bookings</h2>
        <form method="POST">
        <table>
            <thead>
                <tr>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Absence Type</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($bookings <> NULL) {
                    foreach ($bookings as $booking) {
                        $absenceTypeID = $booking[APPR_ABS_ABS_TYPE_ID];
                        $absenceType = RetrieveAbsenceTypeByID($absenceTypeID);
                        ?>
                        <tr>
                            <td><?php echo $booking[APPR_ABS_START_DATE]; ?></td>
                            <td><?php echo $booking[APPR_ABS_END_DATE]; ?></td>
                            <td><?php echo $absenceType[ABS_TYPE_NAME]; ?></td>
                            <td> <button type="submit" name="deleteApproved"  value="<?php echo $booking[APPR_ABS_BOOKING_ID]; ?>">Delete</button></td>

                        </tr>
                    <?php }} ?>
            </tbody>
        </table>
        </form>


        <h2>Pending AdHoc Requests</h2>
        <form method="POST">
        <table>
            <thead>
                <tr>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Absence Type</th>
                </tr>
            </thead>
            <tbody>
            <?php
           if ($adHocRequests <> NULL) {
    foreach ($adHocRequests as $request) {
        $absenceTypeID = $request[AD_HOC_ABSENCE_TYPE_ID];
        $absenceType = RetrieveAbsenceTypeByID($absenceTypeID)
        ?>
                        <tr>
                            <td><?php echo $request[AD_HOC_START]; ?></td>
                            <td><?php echo $request[AD_HOC_END]; ?></td>
                            <td><?php echo $absenceType[ABS_TYPE_NAME]; ?></td>
                            <td> <button type="submit" name="amendAdHoc"  value="<?php echo $request[AD_HOC_REQ_ID]; ?>">Amend</button></td>
                            <td> <button type="submit" name="deleteAdHoc"  value="<?php echo $request[AD_HOC_REQ_ID]; ?>">Delete</button></td>
 
                        </tr>
    <?php }
} ?>
            </tbody>
        </table> 
        </form>    
        <h2>Pending Main Vacation Requests</h2>
        <form method="POST">
                    <table>
                <thead>
                    <tr>
                        <th>First Choice Start</th>
                        <th>First Choice End</th>
                        <th>Second Choice Start</th>
                        <th>Second Choice End</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($mainVacationRequest <> NULL)
                    {?>
                        <tr>
                            <td><?php echo $mainVacationRequest[MAIN_VACATION_1ST_START]; ?></td>
                            <td><?php echo $mainVacationRequest[MAIN_VACATION_1ST_END]; ?></td>
                            <td><?php echo $mainVacationRequest[MAIN_VACATION_2ND_START]; ?></td>
                            <td><?php echo $mainVacationRequest[MAIN_VACATION_2ND_END]; ?></td>
                            <td> <button type="submit" name="amendMain"  value="<?php echo $mainVacationRequest[MAIN_VACATION_REQ_ID]; ?>">Amend</button></td>
                            <td> <button type="submit" name="deleteMain"  value="<?php echo $mainVacationRequest[MAIN_VACATION_REQ_ID]; ?>">Delete</button></td>
                        </tr>
                        <?php } ?>
                </tbody>
            </table>
        </form>


    </body>
</html>
