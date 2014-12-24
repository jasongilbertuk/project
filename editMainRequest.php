<?php
include 'databaseFunctions.php';

if ($_GET["ID"] <> NULL)
{
    $record = RetrieveMainVacationRequestByID($_GET["ID"]);
}

if (isset($_POST["cancel"])) {   
    $url = "Location:adminMainVacationRequests.php";   
    header($url);
}

if (isset($_POST["update"])) {
    $record[MAIN_VACATION_REQ_ID]       =   $_GET["ID"];
    $record[MAIN_VACATION_EMP_ID]       =   $_POST["empID"];
    $record[MAIN_VACATION_1ST_START]    =   $_POST["firstChoiceStart"];
    $record[MAIN_VACATION_1ST_END]      =   $_POST["firstChoiceEnd"];
    $record[MAIN_VACATION_2ND_START]    =   $_POST["secondChoiceStart"];
    $record[MAIN_VACATION_2ND_END]      =   $_POST["secondChoiceEnd"];
    UpdateMainVacactionRequest($record);

    $url = "Location:adminMainVacationRequests.php";   
    header($url);
}

if (isset($_POST["delete"])) {
    DeleteMainVacationRequest($_POST["delete"]);
}


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Amend Main Vacation Request</title>
    </head>
 
    <body>

        <form method="post">
           <label for="empName">Employee</label>
               <?php  
    
                $employee = RetrieveEmployeeByID($record[MAIN_VACATION_EMP_ID]);
                if ($employee <> NULL)
                {
                  echo '<input type="text" name="empID" id="empID" readonly value="'.$employee[EMP_NAME].'"/>';
                }
               ?> 
            <br />
            
            <label for="firstChoiceStart">First Choice Start </label>
             <input type="date" name="firstChoiceStart" id="firstChoiceStart" value="<?php echo $record[MAIN_VACATION_1ST_START]; ?>" /> 
            <br />
            
            <label for="firstChoiceEnd">First Choice End</label>
             <input type="date" name="firstChoiceEnd" id="firstChoiceEnd" value="<?php echo $record[MAIN_VACATION_1ST_END]; ?>"/> 
            <br />
            
            <label for="secondChoiceStart">Second Choice Start</label>
             <input type="date" name="secondChoiceStart" id="secondChoiceStart" value="<?php echo $record[MAIN_VACATION_2ND_START]; ?>" /> 
            <br />
            
            <label for="secondChoiceEnd">Second Choice End</label>
             <input type="date" name="secondChoiceEnd" id="secondChoiceEnd" value="<?php echo $record[MAIN_VACATION_2ND_END]; ?>" /> 
            <br/>
            <input type="submit" name="update" id="submit" value="Update"/> 
            <input type="submit" name="cancel" id="cancel" value="Cancel"/> 

        </form>
    </body>

</html>