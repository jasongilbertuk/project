<?php
include 'sessionmanagement.php';
include 'databaseFunctions.php';

if (!$isAdministrator) {
    header('Location: index.php');
    exit();
}

if (isset($_POST["submit"])) {
    $booking = CreateApprovedAbsenceBooking($_POST["employeeid"], $_POST["startDate"], $_POST["endDate"], $_POST["absenceType"]);
}

if (isset($_POST["amend"])) {
    $url = "Location:editApprovedAbsenceBooking.php?ID=" . $_POST["amend"];
    header($url);
}

if (isset($_POST["delete"])) {
    DeleteApprovedAbsenceBooking($_POST["delete"]);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Approved Absence Booking</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">

        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    </head>

    <body>
<?php include 'navbar.php'; ?>

        <form method="post" class="signUp">
            <div class="row">
                <div class="col-md-4 col-md-offset-4 text-center">
                    <h1>Create Approved Absence Booking</h1>

                    <div class="input-group" for="employeeid">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                        <select class="form-control" name="employeeid" id="employeeid" >
                            <option value="" disabled selected>Select Employee</option>

<?php
$employees = RetrieveEmployees();
if ($employees <> NULL) {
    foreach ($employees as $employee) {
        echo '<option value="' . $employee[EMP_ID] . '">' . $employee[EMP_NAME] . '</option>';
    }
}
?>
                        </select>
                    </div>






                    <div class="input-group" for="startDate">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>	
                        <input type="date" class="form-control" name="startDate" id="startDate" placeholder="Start Date">
                    </div>


                    <div class="input-group" for="endDate">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>	
                        <input type="date" class="form-control" name="endDate" id="endDate" placeholder="End Date">
                    </div>

                    <br />
                    <label for="absenceType">Absence Type</label>
<?php
$absenceTypes = RetrieveAbsenceTypes();
if ($absenceTypes <> NULL) {
    echo '<select class="form-control" name="absenceType">';
    foreach ($absenceTypes as $absenceType) {
        echo '<option value="' . $absenceType[ABS_TYPE_ID] . '">' . $absenceType[ABS_TYPE_NAME] . '</option>';
    }
}


echo '</select>';
?>

                    <input class="btn btn-success btn-block" type="submit" name="submit" id="submit" value="Create Absence Booking"/>
                </div>
            </div>
        </form>

        <div id="table">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <br/><br/><br/>
                    <h1>Current Approved Absence Bookings</h1>

                    <form method="post">
                        <table class="table table-bordered table-hover ">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Absence Type</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
$bookings = RetrieveApprovedAbsenceBookings();

if ($bookings <> NULL) {
    foreach ($bookings as $booking) {
        $employeeID = $booking[APPR_ABS_EMPLOYEE_ID];
        $employee = RetrieveEmployeeByID($employeeID);

        $absenceTypeID = $booking[APPR_ABS_ABS_TYPE_ID];
        $absenceType = RetrieveAbsenceTypeByID($absenceTypeID);
        ?>
                                        <tr>
                                            <td><?php echo $employee[EMP_NAME]; ?></td>
                                            <td><?php echo $booking[APPR_ABS_START_DATE]; ?></td>
                                            <td><?php echo $booking[APPR_ABS_END_DATE]; ?></td>
                                            <td><?php echo $absenceType[ABS_TYPE_NAME]; ?></td>
                                            <td> <button class="btn btn-success" type="submit" name="amend"  value="<?php echo $booking[APPR_ABS_BOOKING_ID]; ?>">Amend</button></td>
                                            <td> <button class="btn btn-danger" type="submit" name="delete"  value="<?php echo $booking[APPR_ABS_BOOKING_ID]; ?>">Delete</button></td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    </form>
                </div>

                </body>
                </html>