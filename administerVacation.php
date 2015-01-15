<?php
include 'sessionmanagement.php';
include 'databasefunctions.php';

if (!$isAdministrator)
{
   header('Location: index.php');
   exit();
}

$totalEmployees = 0;
$employeesWithNoMainVacation = 0;
$result = GetEmployeeCount($totalEmployees,$employeesWithNoMainVacation); 

if (isset($_POST["processmainrequests"])) 
{
	processMainVacationRequests();
}

if (isset($_POST["processadhocrequests"])) 
{
	processAdHocRequests();
}

if (isset($_POST["approve1st"])) { 
    $statusBar = "";
    $requestID = $_POST["approve1st"];
    
    $absenceType = GetAnnualLeaveAbsenceTypeID();
    
    $request = RetrieveMainVacationRequestByID($requestID);
    if ($request <> NULL)
    {
        $result = ProcessAbsenceRequest($request[MAIN_VACATION_EMP_ID],
                                        $request[MAIN_VACATION_1ST_START],
                                        $request[MAIN_VACATION_1ST_END],
                                        $absenceType);
        if ($result == TRUE)
        {
            $statusBar = "Request Approved.";
            
        
          
        }
        else 
        {
            $statusBar = "Unable to approve request";
        }
 
    }
    else
    {
        $statusBar = "Error: Unable to process your request.".
                     "The MainVacationRequest ID of $requestID ".
                     "could not be found in the database. Please ".
                     "contact your system administrator.";
    }
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
  		<span class="input-group-addon">Employees &nbsp; With&nbsp; Main &nbsp; Vacation&nbsp; Requests</span>
  		<input type="text" class="form-control" name="withCount" id="withCount" readonly value="<?php echo $totalEmployees;?>">
	    </div>
            
            <div class="input-group" for="StaffWithoutRequest">
  		<span class="input-group-addon">Employees Without Main Vacation Requests</span>
  		<input type="text" class="form-control" name="withoutCount" id="withCount" readonly value="<?php echo $employeesWithNoMainVacation;?>">
	    </div>

                        
            <input class="btn btn-success btn-block" type="submit" name="processmainrequests" id="submit" value="Process Main Requests"/>
            <input class="btn btn-success btn-block" type="submit" name="processadhocrequests" id="submit" value="Process Ad Hoc Requests"/>
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
