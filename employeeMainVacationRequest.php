<?php
include 'sessionmanagement.php';  //sets $userID,$isAdministrator and $isManager
include 'databaseFunctions.php';

$employee = RetrieveEmployeeByID($userID);
$requestID = $employee[EMP_MAIN_VACATION_REQ_ID];

$today = date("Y-m-d");
$firstChoiceStart   = $today;
$firstChoiceEnd     = $today;
$secondChoiceStart  = $today;
$secondChoiceEnd    = $today;

if ( $requestID <> NULL)
{
    $mainVacationRequest = RetrieveMainVacationRequestByID($requestID);
    $firstChoiceStart    = $mainVacationRequest[MAIN_VACATION_1ST_START];
    $firstChoiceEnd      = $mainVacationRequest[MAIN_VACATION_1ST_END];
    $secondChoiceStart   = $mainVacationRequest[MAIN_VACATION_2ND_START];
    $secondChoiceEnd     = $mainVacationRequest[MAIN_VACATION_2ND_END];
}



if (isset($_POST["submit"])) 
{
    $request = CreateMainVactionRequest($userID, 
                               $_POST["firstChoiceStart"],
                               $_POST["firstChoiceEnd"],
                               $_POST["secondChoiceStart"], 
                               $_POST["secondChoiceEnd"]);

    $url = "Location:index.php";   
    header($url);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Employees</title>
        
    </head>
 
    <body>
        <a href="index.php">Back to Homepage</a>

        <form method="post">
            <label for="firstChoiceStart">First Choice Start </label>
             <input type="date" name="firstChoiceStart" id="firstChoiceStart" value="<?php echo $firstChoiceStart;?>"/> 
            <br />
            
            <label for="firstChoiceEnd">First Choice End</label>
             <input type="date" name="firstChoiceEnd" id="firstChoiceEnd" value="<?php echo $firstChoiceEnd;?>"/> 
            <br />
            
            <label for="secondChoiceStart">Second Choice Start</label>
             <input type="date" name="secondChoiceStart" id="secondChoiceStart" value="<?php echo $secondChoiceStart;?>"/> 
            <br />
            
            <label for="secondChoiceEnd">Second Choice End</label>
             <input type="date" name="secondChoiceEnd" id="secondChoiceEnd" value="<?php echo $secondChoiceEnd;?>"/> 
            <br/>
            
            <input type="submit" name="submit" id="submit" value="Submit Main Vacation Request"/>
   
        </form>
    </body>

</html>
