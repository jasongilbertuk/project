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
    </head>
 
    <body>

        <form method="post">
            <label for="employeeName">Employee Name</label>
            <?php  
    
                $employees = RetrieveEmployees();
                if ($employees <> NULL)
                {
                    echo '<select name="employeeID">';
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
            
            <label for="startDate">Start Date</label>
            <input type="date" name="startDate" id="startDate" value="<?php echo $request[APPR_ABS_START_DATE]?>"/> 

            <br/>    

            <label for="endDate">End Date</label>
            <input type="date" name="endDate" id="endDate" value="<?php echo $request[APPR_ABS_END_DATE]?>"/>
            <br/>                
            
            <label for="absenceType">Absence Type</label>
            <?php  
                $absenceTypes = RetrieveAbsenceTypes();
                if ($absenceTypes <> NULL)
                {
                    echo '<select name="absenceType">';
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
            
            <input type="submit" name="update" id="submit" value="Edit Request"/>
            <input type="submit" name="cancel" id="cancel" value="Cancel"/>
        </form>
    </body>
</html>