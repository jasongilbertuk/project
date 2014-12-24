<?php
include 'databaseFunctions.php';

if (isset($_POST["submit"])) {
    $booking = CreateApprovedAbsenceBooking($_POST["employeeid"],
                                         $_POST["startDate"],
                                         $_POST["endDate"],
                                         $_POST["absenceType"]);  
}

if (isset($_POST["amend"])) {   
    $url = "Location:editApprovedAbsenceBooking.php?ID=".$_POST["amend"];   
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
        
</head>
 
<body>
     <a href="index.php">Back to Homepage</a>

    <form method="post">
        <label for="employeeid">Employee</label>
           <?php  
    
            $employees = RetrieveEmployees();
            if ($employees <> NULL)
            {
                echo '<select name="employeeid" id="employeeid">';
                foreach ($employees as $employee)
                {                        
                echo '<option value="'.$employee[EMP_ID].'">'.$employee[EMP_NAME].'</option>';
                }
            }
            echo '</select>';
            ?> 
        <br />
        
        <label for="startDate"> Start Date </label>                    
        <input type="date" name="startDate" id="startDate"/>
            
        <br />
            
        <label for="endDate"> End Date </label>
        <input type="date" name="endDate" id="endDate" />
            
        <br />
        
        <label for="absenceType">Absence Type</label>
            <?php  
                $absenceTypes = RetrieveAbsenceTypes();
                if ($absenceTypes <> NULL)
                {
                    echo '<select name="absenceType">';
                    foreach ($absenceTypes as $absenceType)
                        {
                        echo '<option value="'.$absenceType[ABS_TYPE_ID].'">'.$absenceType[ABS_TYPE_NAME].'</option>';
                    }
                }
            
                
            echo '</select>';
            ?>
        
        <br />
            
        <input type="submit" name="submit" id="submit" value="Create Absence Booking"/>
            
        </form>
     
     <div id="table">
            <form method="post">
            <table>
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
                    
                    if ($bookings <> NULL)
                    {
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
                            <td> <button type="submit" name="amend"  value="<?php echo $booking[APPR_ABS_BOOKING_ID]; ?>">Amend</button></td>
                            <td> <button type="submit" name="delete"  value="<?php echo $booking[APPR_ABS_BOOKING_ID]; ?>">Delete</button></td>
                        </tr>
                        <?php }} ?>
                </tbody>
            </table>
            </form>
        </div>
     
    </body>
</html>