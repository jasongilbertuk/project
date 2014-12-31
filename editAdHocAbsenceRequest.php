<?php
include 'databaseFunctions.php';

$returnURL = "index.php";
if (isset($_GET["back"]))
{
    $returnURL = $_GET["back"];
}

if ($_GET["ID"] <> NULL)
{
    $request = RetrieveAdHocAbsenceRequestByID($_GET["ID"]);
    $employee = RetrieveEmployeeByID($request[AD_HOC_EMP_ID]);
}

if (isset($_POST["cancel"])) {   
    
    header("location:".$returnURL);
    exit;
}

if (isset($_POST["update"])) {
    $request[AD_HOC_REQ_ID]          =  $_GET["ID"];
    $request[AD_HOC_START]           =   $_POST["startDate"];
    $request[AD_HOC_END]             =   $_POST["endDate"];
    $request[AD_HOC_ABSENCE_TYPE_ID] =   $_POST["absenceType"];
    UpdateAdHocAbsenceRequest($request);

    header("location:".$returnURL);
    exit;
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
            <input type="text" readonly value="<?php echo $employee[EMP_NAME]; ?>"/>
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