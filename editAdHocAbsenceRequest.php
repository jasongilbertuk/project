<?php
include 'databaseFunctions.php';

if ($_GET["ID"] <> NULL)
{
    $request = RetrieveAdHocAbsenceRequestByID($_GET["ID"]);
}

if (isset($_POST["cancel"])) {   
    $url = "Location:adminAdHocAbsenCeRequest.php";   
    header($url);
}

if (isset($_POST["update"])) {
    $request[AD_HOC_REQ_ID]          =  $_GET["ID"];
    $request[AD_HOC_EMP_ID]          =   $_POST["employeeID"];
    $request[AD_HOC_START]           =   $_POST["startDate"];
    $request[AD_HOC_END]             =   $_POST["endDate"];
    $request[AD_HOC_ABSENCE_TYPE_ID] =   $_POST["absenceType"];
    UpdateAdHocAbsenceRequest($request);

    $url = "Location:adminAdHocAbsenceRequest.php";   
    header($url);
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Company Roles</title>
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
                    if ($employee[EMP_ID]== $request[AD_HOC_EMP_ID])
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
            <input type="date" name="startDate" id="startDate" value="<?php echo $request[AD_HOC_START]?>"/> 

            <br/>    

            <label for="endDate">End Date</label>
            <input type="date" name="endDate" id="endDate" value="<?php echo $request[AD_HOC_END]?>"/>
            <br/>                
            
            <label for="absenceType">Absence Type</label>
            <?php  
                $absenceTypes = RetrieveAbsenceTypes();
                if ($absenceTypes <> NULL)
                {
                    echo '<select name="absenceType">';
                    foreach ($absenceTypes as $absenceType)
                    if ($absenceType[ABS_TYPE_ID]== $request[AD_HOC_ABSENCE_TYPE_ID])
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