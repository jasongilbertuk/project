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
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <meta charset="UTF-8">
        <title>Display Employee Details</title>

    </head>

    <body>
         <?php include 'navbar.php'; ?>
        
        <div class="col-md-4 col-md-offset-4 text-center">
            <h1>Employee Details</h1>
            <ul class="list-group ">
                <li class="list-group-item "><?php echo "ID: " . $employee[EMP_ID]; ?></li>
                <li class="list-group-item "><?php echo "Name: " . $employee[EMP_NAME]; ?></li>
                <li class="list-group-item "><?php echo "Email: " . $employee[EMP_EMAIL]; ?></li>
                <li class="list-group-item "><?php echo "Date Joined: " . $employee[EMP_DATEJOINED]; ?></li>
                <li class="list-group-item "><?php echo "Company Role: " . $companyRole[COMP_ROLE_NAME]; ?></li>
                <li class="list-group-item "><?php echo "Is Admin: " . $employee[EMP_ADMIN_PERM]; ?></li>
                <li class="list-group-item "><?php echo "Is Manager: " . $employee[EMP_MANAGER_PERM]; ?></li>
                <li class="list-group-item "><?php echo "Leave Entitlement: " . $employee[EMP_LEAVE_ENTITLEMENT]; ?></li>
                <li class="list-group-item "><?php echo "Annual leave remaining:".CalculateRemainingAnnualLeave($employee[EMP_ID]); ?></li>
            </ul>
        </div>
       
        <form method="POST">
        <div class="row">
        <div class="col-md-8 col-md-offset-2 text-center">
        <table class="table table-bordered table-hover ">
            <h1> Approved Absence Requests </h1>
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
                            <td> <button class="btn btn-danger" type="submit" name="deleteApproved"  value="<?php echo $booking[APPR_ABS_BOOKING_ID]; ?>">Delete</button></td>

                        </tr>
                    <?php }} ?>
            </tbody>
        </div>
        </div>    
        </table>
        </form>

        <form method="POST">
        <div class="row">
        <div class="col-md-8 col-md-offset-2 text-center">
        <table class="table table-bordered table-hover">
            <h1>Pending AdHoc Requests</h1>
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
                            <td> <button class="btn btn-success" type="submit" name="amendAdHoc"  value="<?php echo $request[AD_HOC_REQ_ID]; ?>">Amend</button></td>
                            <td> <button class="btn btn-danger" type="submit" name="deleteAdHoc"  value="<?php echo $request[AD_HOC_REQ_ID]; ?>">Delete</button></td>
 
                        </tr>
    <?php }
} ?>
            </tbody>
        </table> 
        </div>
        </div>
        </form>    
        
        <form method="POST">
        <div class="row">
        <div class="col-md-8 col-md-offset-2 tect-center">
            <h1>Pending Main Vacation Requests</h1>
            <table class="table table-bordered table-hover">
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
                            <td> <button class="btn btn-success" type="submit" name="amendMain"  value="<?php echo $mainVacationRequest[MAIN_VACATION_REQ_ID]; ?>">Amend</button></td>
                            <td> <button class="btn btn-danger" type="submit" name="deleteMain"  value="<?php echo $mainVacationRequest[MAIN_VACATION_REQ_ID]; ?>">Delete</button></td>
                        </tr>
                        <?php } ?>
                </tbody>
            </table>
        </div>
        </div>
        </form>


    </body>
</html>
