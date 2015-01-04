<?php
include 'sessionmanagement.php';
include 'databasefunctions.php';

if (!$isAdministrator)
{
   header('Location: index.php');
   exit();
}
 
 function TurnMainVacationRequestIntoApprovedBooking($mainVacationRequestID,$choiceNumberToApprove)
 {
    $request  = RetrieveMainVacationRequestByID($mainVacationRequestID);
    if ($choiceNumberToApprove == 1)
    {
        $startDate = $request[MAIN_VACATION_1ST_START];
        $endDate   = $request[MAIN_VACATION_1ST_END];
    }
    else
    {
        $startDate = $request[MAIN_VACATION_2ND_START];
        $endDate   = $request[MAIN_VACATION_2ND_END];
    }
    $filter[ABS_TYPE_NAME] = ANNUAL_LEAVE;
    $absenceTypes = RetrieveAbsenceTypes($filter);
    
    if (count($absenceTypes) <> 1)
    {
        echo "ERROR";
    }
    $absenceType = $absenceTypes[0];
    
    $success = CreateApprovedAbsenceBooking($request[MAIN_VACATION_EMP_ID],
                                            $startDate,
                                            $endDate,
                                            $absenceType[ABS_TYPE_ID]);
    
    if ($success)
    {
        DeleteMainVacationRequest($mainVacationRequestID);
    }
    
 }
 
 
 
 
function SendMainVacationEmail($mainVacationRequestID,$approved1stChoice,$approved2ndChoice)
{
    
    $mainVacationRequest = RetrieveMainVacationRequestByID($mainVacationRequestID);
    $employee = RetrieveEmployeeByID($mainVacationRequest[MAIN_VACATION_EMP_ID]);
    
    $email = $employee[EMP_EMAIL];
 
    $subject = "Your Main Vacation Request";
    $msg = "Your requested a first choice of ".$mainVacationRequest[MAIN_VACATION_1ST_START]. 
               " to ".$mainVacationRequest[MAIN_VACATION_1ST_END]. 
               " and a second choice of ".$mainVacationRequest[MAIN_VACATION_2ND_START]. 
               " to ".$mainVacationRequest[MAIN_VACATION_2ND_END];
    
    if ($approved1stChoice)
    {
        $msg = $msg."\n. Your FIRST CHOICE has been approved.";
    }
    else if ($approved2ndChoice) 
    {
        $msg = $msg."\n. Your SECOND CHOICE has been approved.";
    }
    else
    {
        $msg = $msg."\n. NEITHER OF THESE PERIODS ARE AVAILABLE. PLEASE SUBMIT A NEW MAIN VACATION REQUEST.";
 
    }
  
    $result = mail($email,$subject,$msg);
    if ($result)
    {
        //todo
    }
    else 
    {
         //todo
    }
}

 

$totalEmployees = 0;
$employeesWithNoMainVacation = 0;
$result = GetEmployeeCount($totalEmployees,$employeesWithNoMainVacation); 

if (isset($_POST["processmainrequests"])) 
{
    $conn = $GLOBALS["connection"];
    
    $sql = "SELECT * FROM mainVacationRequestTable JOIN EmployeeTable ".
           "WHERE mainVacationRequestTable.EmployeeID = EmployeeTable.EmployeeID ".
           "ORDER BY EmployeeTable.dateJoinedTheCompany;";
    
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        error_log("PerformSQL failed. Sql = $sql");
    }
    else {
        
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
           $mainVacationRequestID   = $row[MAIN_VACATION_REQ_ID];
           $employeeID              = $row[MAIN_VACATION_EMP_ID];
           $firstChoiceStartDate    = $row[MAIN_VACATION_1ST_START];
           $firstChoiceEndDate      = $row[MAIN_VACATION_1ST_END];
           $secondChoiceStartDate   = $row[MAIN_VACATION_2ND_START];
           $secondChoiceEndDate     = $row[MAIN_VACATION_2ND_END];
           $annualLeaveEntitlement  = $row[EMP_LEAVE_ENTITLEMENT];
           
           $filter[ABS_TYPE_NAME] = "Annual Leave";
           $absenceTypes = RetrieveAbsenceTypes($filter);
           
           $absenceType = NULL;
           if (count($absenceTypes)== 1)
           {
               $absenceType = $absenceTypes[0];
           }
            
            $daysRemaining  = CalculateRemainingAnnualLeave($employeeID);
           
           $leaveFor1stChoice = CalculateAnnualLeaveRequired($firstChoiceStartDate,
                                                             $firstChoiceEndDate,
                                                             $absenceType[ABS_TYPE_ID]);
           
           $leaveFor2ndChoice = CalculateAnnualLeaveRequired($secondChoiceStartDate,
                                                             $secondChoiceEndDate,
                                                             $absenceType[ABS_TYPE_ID]);
                
           
           $firstChoiceAvailable    = SufficentStaffInRoleToGrantRequest($employeeID,
                                                                         $firstChoiceStartDate,
                                                                         $firstChoiceEndDate);

           $secondChoiceAvailable   = SufficentStaffInRoleToGrantRequest($employeeID,
           								 $secondChoiceStartDate,
           								 $secondChoiceEndDate);

           
           $enoughDaysForFirstChoice = ($daysRemaining >= $leaveFor1stChoice);
           $enoughDaysForSecondChoice = ($daysRemaining >= $leaveFor2ndChoice);
           
           if ($firstChoiceAvailable AND $enoughDaysForFirstChoice)
           {
               SendMainVacationEmail($mainVacationRequestID,TRUE,FALSE);
               TurnMainVacationRequestIntoApprovedBooking($mainVacationRequestID,1);
           }
           else if ($secondChoiceAvailable AND $enoughDaysForSecondChoice)
           {
               SendMainVacationEmail($mainVacationRequestID,FALSE,TRUE);
               TurnMainVacationRequestIntoApprovedBooking($mainVacationRequestID,2);

           }
           else 
           {
               SendMainVacationEmail($mainVacationRequestID,FALSE,FALSE);
           }
        }
        
    }
}

if (isset($_POST["approve1st"])) {   
    TurnMainVacationRequestIntoApprovedBooking($_POST["approve1st"],1);
}

if (isset($_POST["approve2nd"])) {   
    TurnMainVacationRequestIntoApprovedBooking($_POST["approve2nd"],2);
}

if (isset($_POST["reject"])) 
{
    $ID = $_POST["reject"];
    DeleteMainVacationRequest($ID);

}


if (isset($_POST["approveadhoc"])) {   
    $ID = $_POST["approveadhoc"];
    
    $request  = RetrieveAdHocAbsenceRequestByID($ID);
    $startDate = $request[AD_HOC_START];
    $endDate   = $request[AD_HOC_END];
    $absenceTypeID = $request[AD_HOC_ABSENCE_TYPE_ID];
    
    $success = CreateApprovedAbsenceBooking($request[AD_HOC_EMP_ID],
                                            $startDate,
                                            $endDate,
                                            $absenceTypeID);
    
    if ($success)
    {
        DeleteAdHocAbsenceRequest($ID);
    }
    
}

if (isset($_POST["rejectadhoc"])) 
{
    $ID = $_POST["reject"];
    DeleteAdHocAbsenceRequest($ID);

}


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Administer Vacations</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">

      	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    </head>
 
    <body>
        <?php include 'navbar.php'; ?>
        
        <form method="post">
            <div class="row">
            <div class="col-md-4 col-md-offset-4 text-center">
                <h1> Current Processed Requests </h1>
            <div class="input-group" for="StaffWithRequest">
  		<span class="input-group-addon">With Main Vacation<span class="glyphicon glyphicon-user"></span></span>
  		<input type="text" class="form-control" name="withCount" id="withCount" readonly value="<?php echo $totalEmployees;?>">
	    </div>
            
            <div class="input-group" for="StaffWithoutRequest">
  		<span class="input-group-addon">Without Main Vacation<span class="glyphicon glyphicon-user"></span></span>
  		<input type="text" class="form-control" name="withoutCount" id="withCount" readonly value="<?php echo $employeesWithNoMainVacation;?>">
	    </div>

                        
            <input class="btn btn-success btn-block" type="submit" name="processmainrequests" id="submit" value="Process Main Requests"/>
            </div>
            </div>
        </form>
        
         <div id="table">
            
            <form method="post">
                
            <div class="row">
            <div class="col-md-8 col-md-offset-2 text-center">
            <table class="table table-bordered table-hover">
                <br/> <br/> <br/> 
                <thead>
                    <h1>Current Main Vacation Requests</h1>
                    <tr>
                        <th>Name</th>
                        <th>First Choice Start</th>
                        <th>First Choice End</th>
                        <th>Second Choice Start</th>
                        <th>Second Choice End</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $requests = RetrieveMainVacationRequests();
                    if ($requests <> NULL)
                    {
                        foreach ($requests as $request) { 
                            $employee = RetrieveEmployeeByID($request[MAIN_VACATION_EMP_ID]);
                            ?>
                        <tr>
                            <td><?php echo $employee[EMP_NAME]; ?></td>
                            <td><?php echo $request[MAIN_VACATION_1ST_START]; ?></td>
                            <td><?php echo $request[MAIN_VACATION_1ST_END]; ?></td>
                            <td><?php echo $request[MAIN_VACATION_2ND_START]; ?></td>
                            <td><?php echo $request[MAIN_VACATION_2ND_END]; ?></td>
                            <td> <button class="btn btn-success" type="submit" name="approve1st"  value="<?php echo $request[MAIN_VACATION_REQ_ID]; ?>">Approve 1st Choice</button></td>
                            <td> <button class="btn btn-success" type="submit" name="approve2nd"  value="<?php echo $request[MAIN_VACATION_REQ_ID]; ?>">Approve 2nd Choice</button></td>
                            <td> <button class="btn btn-danger" type="submit" name="reject"  value="<?php echo $request[MAIN_VACATION_REQ_ID]; ?>">Reject</button></td>
                        </tr>
                        <?php }} ?>
                </tbody>
            </table>
            </div>
            </div>
            </form>
        </div>  
      
         <div id="table">
             <H2>Current Ad Hoc Absence Requests </H2>
            <form method="post">
            <div class="row">
            <div class="col-md-8 col-md-offset-2 text-center">    
            <table class="table table-bordered table-hover">
                <br/> <br/> <br/>
                <thead>
                    <h1> Current Ad Hoc Requests </h1>
                    <tr>
                        <th>Name</th>
                        <th>Start</th>
                        <th>End</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $requests = RetrieveAdHocAbsenceRequests();
                    if ($requests <> NULL)
                    {
                        foreach ($requests as $request) { 
                            $employee = RetrieveEmployeeByID($request[AD_HOC_EMP_ID]);
                            ?>
                        <tr>
                            <td><?php echo $employee[EMP_NAME]; ?></td>
                            <td><?php echo $request[AD_HOC_START]; ?></td>
                            <td><?php echo $request[AD_HOC_END]; ?></td>
                            <td> <button class="btn btn-success" type="submit" name="approveadhoc"  value="<?php echo $request[AD_HOC_REQ_ID]; ?>">Approve</button></td>
                            <td> <button class="btn btn-danger" type="submit" name="rejectadhoc"  value="<?php echo $request[AD_HOC_REQ_ID]; ?>">Reject</button></td>
                        </tr>
                        <?php }} ?>
                </tbody>
            </table>
            </div>
            </div>
            </form>
        </div>  
        
    </body>
</html>
