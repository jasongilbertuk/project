<?php
include 'sessionmanagement.php';
include 'databaseFunctions.php';

$returnURL = "index.php";
if (isset($_GET["back"]))
{
    $returnURL = $_GET["back"];
}


if ($_GET["ID"] <> NULL)
{
    $record = RetrieveMainVacationRequestByID($_GET["ID"]);
    
    if (!$isAdministrator)
    {
        if ($record[MAIN_VACATION_EMP_ID] <> $userID)
        {
            header('Location: index.php');
            exit();
        }
    }
    $employee = RetrieveEmployeeByID($record[MAIN_VACATION_EMP_ID]);

}

if (isset($_POST["cancel"])) {   
    header("Location:".$returnURL);
}

if (isset($_POST["update"])) {
    $record[MAIN_VACATION_REQ_ID]       =   $_GET["ID"];
    $record[MAIN_VACATION_EMP_ID]       =   $employee[EMP_ID];
    $record[MAIN_VACATION_1ST_START]    =   $_POST["firstChoiceStart"];
    $record[MAIN_VACATION_1ST_END]      =   $_POST["firstChoiceEnd"];
    $record[MAIN_VACATION_2ND_START]    =   $_POST["secondChoiceStart"];
    $record[MAIN_VACATION_2ND_END]      =   $_POST["secondChoiceEnd"];
    UpdateMainVacactionRequest($record);

    header("Location:".$returnURL);
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
                if ($employee <> NULL)
                {
                  echo '<input type="text" name="empName" id="empName" readonly value="'.$employee[EMP_NAME].'"/>';
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