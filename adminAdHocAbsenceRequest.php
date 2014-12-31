<?php
include 'sessionmanagement.php';
include 'databasefunctions.php';

if (!$isAdministrator)
{
   header('Location: index.php');
   exit();
}


if (isset($_POST["submit"])) 
{
    $request = CreateAdHocAbsenceRequest($_POST["employeeID"],
                                         $_POST["startDate"],
                                         $_POST["endDate"],
                                         $_POST["absenceType"]);  
}

if (isset($_POST["amend"])) {   
    $url = "Location:editAdHocAbsenceRequest.php?ID=".$_POST["amend"]."&back=adminAdHocAbsenceRequest.php";   
    header($url);
}

if (isset($_POST["delete"])) 
{
    DeleteAdHocAbsenceRequest($_POST["delete"]);
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Company Roles</title>
    </head>
 
    <body>
            <a href="index.php">Back to Homepage</a>

        <form method="post">
            <label for="employeeName">Employee Name</label>
            <?php  
    
                $employees = RetrieveEmployees();
                if ($employees <> NULL)
                {
                    echo '<select name="employeeID">';
                    foreach ($employees as $employee)
                    {
                        echo '<option value="'.$employee[EMP_ID].'">'.$employee[EMP_NAME].'</option>';
                    }
                }
                
            echo '</select>';
            
            ?>
            
            <br />
            
            <label for="startDate">Start Date</label>
            <input type="date" name="startDate" id="startDate"/> 

            <br/>    

            <label for="endDate">End Date</label>
            <input type="date" name="endDate" id="endDate"/>
            <br/>                
            
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
            
            <input type="submit" name="submit" id="submit" value="Add AdHoc Request"/>
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
                    $requests = RetrieveAdHocAbsenceRequests();
                    if ($requests <> NULL)
                    {
                        foreach ($requests as $request) {
                             $employeeID = $request[AD_HOC_EMP_ID];
                             $employee = RetrieveEmployeeByID($employeeID);  
                              
                             $absenceTypeID = $request[AD_HOC_ABSENCE_TYPE_ID];
                             $absenceType = RetrieveAbsenceTypeByID($absenceTypeID)
                            ?>
                        <tr>
                            <td><?php echo $employee[EMP_NAME]; ?></td>
                            <td><?php echo $request[AD_HOC_START]; ?></td>
                            <td><?php echo $request[AD_HOC_END]; ?></td>
                            <td><?php echo $absenceType[ABS_TYPE_NAME]; ?></td>
                            <td> <button type="submit" name="amend"  value="<?php echo $request[AD_HOC_REQ_ID]; ?>">Amend</button></td>
                            <td> <button type="submit" name="delete"  value="<?php echo $request[AD_HOC_REQ_ID]; ?>">Delete</button></td>
                        </tr>
                        <?php }} ?>
                </tbody>
            </table>
            </form>
        </div>  
      
        
    </body>
</html>
