<?php
include 'databaseFunctions.php';

if ($_GET["ID"] <> NULL)
{
    $absenceType = RetrieveAbsenceTypeByID($_GET["ID"]);
    $usesLeave = false;
    if ($absenceType[ABS_TYPE_USES_LEAVE] == 1)
    {
        $usesLeave = true;
    }

    $canBeDenied = false;
    if ($absenceType[ABS_TYPE_CAN_BE_DENIED] == 1)
    {
        $canBeDenied = true;
    }
}

if (isset($_POST["cancel"])) {   
    $url = "Location:adminAbsenceTypes.php";   
    header($url);
}

if (isset($_POST["update"])) {
    $absenceType[ABS_TYPE_ID]       =   $_GET["ID"];
    $absenceType[ABS_TYPE_NAME]     =   $_POST["name"];
    
    $usesLeave = "0";
    if (isset($_POST["usesLeave"]))
    {
        $usesLeave = "1";
    }
    $canBeDenied = "0";
    if (isset($_POST["canBeDenied"]))
    {
        $canBeDenied = "1";
    }
    
    
    
    $absenceType[ABS_TYPE_USES_LEAVE]= $usesLeave;
    $absenceType[ABS_TYPE_CAN_BE_DENIED]= $canBeDenied;
    
    UpdateAbsenceType($absenceType);

    $url = "Location:adminAbsenceTypes.php";   
    header($url);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Amend Absence Type</title>
    </head>
 
    <body>

        <form method="post">
            <label for="name">Absence Type Name</label>
            <input type="text" name="name" id="name" value="<?php echo $absenceType[ABS_TYPE_NAME];?>"/> 
            <br/>    

            <label for="usesLeave">Uses Annual Leave</label>
            <input type="checkbox" name="usesLeave" id="usesLeave" 
                   <?php if ($usesLeave) {echo 'checked="true"';} ?>/>
                   
            <br/>
            <label for="canBeDenied">Can Be Denied</label>
            <input type="checkbox" name="canBeDenied" id="canBeDenied"
                      <?php if ($canBeDenied) {echo 'checked="true"';} ?>/>
            <br/>
            <input type="submit" name="update" id="submit" value="Update"/> 
            <input type="submit" name="cancel" id="cancel" value="Cancel"/> 
        </form>

    </body>
</html>