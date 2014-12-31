<?php
include 'sessionmanagement.php';
include 'databasefunctions.php';


if (isset($_POST["submit"])) 
{
    $request = CreateAdHocAbsenceRequest($userID,
                                         $_POST["startDate"],
                                         $_POST["endDate"],
                                         $_POST["absenceType"]);
    $url = "Location:index.php";   
    header($url);

}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Employee Create Ad Hoc Request</title>
    </head>
 
    <body>
            <a href="index.php">Back to Homepage</a>

        <form method="post">
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
        </div>  
    </body>
</html>
