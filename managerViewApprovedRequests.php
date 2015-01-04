<?php
include 'sessionmanagement.php';
include 'databaseFunctions.php';

if (!$isManager) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>View Approved Absence Bookings</title>
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
                    <h1>View Approved Absence Bookings</h1>

                    <div class="input-group" for="startDate">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>	
                        <input type="date" class="form-control" name="startDate" id="startDate" placeholder="Start Date">
                    </div>


                    <div class="input-group" for="endDate">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>	
                        <input type="date" class="form-control" name="endDate" id="endDate" placeholder="End Date">
                    </div>
                    <br/>
                    <input class="btn btn-success btn-block" type="submit" name="submit" id="submit" value="Display Bookings"/>
                </div>
            </div>
        </form>

        <?php if (isset($_POST["submit"])){?>
        <div id="table">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <br/><br/><br/>
                    <h1>Search Results</h1>

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
    date_default_timezone_set('UTC');

    // Start date
    $startDate = $_POST["startDate"];
    $startDateTime = strtotime($startDate);
   
    // End date
    $endDate = $_POST["endDate"];
    $endDateTime = strtotime($endDate);

$bookings = RetrieveApprovedAbsenceBookings();

if ($bookings <> NULL) {
    foreach ($bookings as $booking) {
        $bookingStartTime = strtotime($booking[APPR_ABS_START_DATE]);
        $bookingEndTime = strtotime($booking[APPR_ABS_START_DATE]);
        
        if ( ($bookingStartTime >= $startDateTime) AND
             ($bookingEndTime <= $endDateTime))
        {
            $employee = RetrieveEmployeeByID($booking[APPR_ABS_EMPLOYEE_ID]);
            $absenceType = RetrieveAbsenceTypeByID($booking[APPR_ABS_ABS_TYPE_ID]);
?>
                                
                                        <tr>
                                            <td><?php echo $employee[EMP_NAME]; ?></td>
                                            <td><?php echo $booking[APPR_ABS_START_DATE]; ?></td>
                                            <td><?php echo $booking[APPR_ABS_END_DATE]; ?></td>
                                            <td><?php echo $absenceType[ABS_TYPE_NAME]; ?></td>
                                        </tr>
<?php }}} ?>
                                    
                            </tbody>
                        </table>
                    </form>
        </div> <?php }?>

                </body>
                </html>