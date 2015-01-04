<?php
include 'databaseFunctions.php';

if ($_GET["ID"] <> NULL)
{
    $request = RetrieveApprovedAbsenceBookingByID($_GET["ID"]);
}

if (isset($_POST["cancel"])) {   
    $url = "Location:adminApprovedAbsenceBooking.php";   
    header($url);
}

if (isset($_POST["update"])) {
    DeleteApprovedAbsenceBooking($_GET["ID"]);
    CreateApprovedAbsenceBooking($_POST["employeeID"], 
            $_POST["startDate"],$_POST["endDate"],$_POST["absenceType"]);
    
    $url = "Location:adminApprovedAbsenceBookings.php";   
    header($url);
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Approved Absence Bookings</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
           
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    </head>
 
    <body>
        <?php include 'navbar.php'; ?>
        <div class="row">
        <div class="col-md-4 col-md-offset-4 text-center">
            <h1> Edit Approved Absence Booking </h1>
        <form method="post" class="signUp">
            <label for="employeeName">Employee Name</label>
            <?php  
    
                $employees = RetrieveEmployees();
                if ($employees <> NULL)
                {
                    echo '<select class="form-control" name="employeeID">';
                    foreach ($employees as $employee)
                    if ($employee[EMP_ID]== $request[APPR_ABS_EMPLOYEE_ID])
                    {
                        echo '<option selected="selected" value="'.$employee[EMP_ID].'">'.$employee[EMP_NAME].'</option>';
                    }
                    else    
                    {
                        echo '<option value="'.$employee[EMP_ID].'">'.$employee[EMP_NAME].'</option>';
                    }
                }
                
            echo '</select>';
            
            ?>
            
            <br />
            
            <div class="input-group" for="startDate">
                <span class="input-group-addon">Start Date  <span class="glyphicon glyphicon-calendar"></span></span>	
                <input type="date" class="form-control" name="startDate" id="startDate" value="<?php echo $request[APPR_ABS_START_DATE]?>">
            </div>
               
            <div class="input-group" for="endDate">
                <span class="input-group-addon">End Date  <span class="glyphicon glyphicon-calendar"></span></span>	
                <input type="date" class="form-control" name="endDate" id="endDate" 
                       value="<?php echo $request[APPR_ABS_END_DATE]?>">
            </div>
            
            <br />
            
            <label for="absenceType">Absence Type</label>
            <?php  
                $absenceTypes = RetrieveAbsenceTypes();
                if ($absenceTypes <> NULL)
                {
                    echo '<select class="form-control" name="absenceType">';
                    foreach ($absenceTypes as $absenceType)
                    if ($absenceType[ABS_TYPE_ID]== $request[APPR_ABS_ABS_TYPE_ID])
                        {
                        echo '<option selected="selected" value="'.$absenceType[ABS_TYPE_ID].'">'.$absenceType[ABS_TYPE_NAME].'</option>';                       
                        }
                        else                      
                        {
                        echo '<option value="'.$absenceType[ABS_TYPE_ID].'">'.$absenceType[ABS_TYPE_NAME].'</option>';
                    }
                }
            
                
            echo '</select>';
            ?>
            <br />
            
            <input class="btn btn-success btn-block" type="submit" name="update" id="submit" value="Edit Request"/>
            <input class="btn btn-danger btn-block" type="submit" name="cancel" id="cancel" value="Cancel"/>
        </form>
    </body>
</html>