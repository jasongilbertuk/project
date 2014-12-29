<?php
include 'databasefunctions.php';

$totalEmployees = 0;
$employeesWithNoMainVacation = 0;
$result = GetEmployeeCount($totalEmployees,$employeesWithNoMainVacation); 

if (isset($_POST["submit"])) 
{
}

if (isset($_POST["approve1st"])) {   
    $ID = $_POST["approve1st"];
    
    $request  = RetrieveMainVacationRequestByID($ID);
    $startDate = $request[MAIN_VACATION_1ST_START];
    $endDate   = $request[MAIN_VACATION_1ST_END];
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
        DeleteMainVacationRequest($ID);
    }
    
}

if (isset($_POST["approve2nd"])) {   
    $ID = $_POST["approve2nd"];
    
    $request  = RetrieveMainVacationRequestByID($ID);
    $startDate = $request[MAIN_VACATION_2ND_START];
    $endDate   = $request[MAIN_VACATION_2ND_END];
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
        DeleteMainVacationRequest($ID);
    }
    
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
        <title>Current Main Vacation Requests</title>
    </head>
 
    <body>
            <a href="index.php">Back to Homepage</a>

        <form method="post">
            <label for="StaffWithRequest">Number of Staff with Main Vacation Requests</label>
            <input type="text" name="withcount" id="withcount" readonly value="<?php echo $totalEmployees;?>"/>
            <label for="StaffWithoutRequest">Number of Staff without Main Vacation Requests</label>
            <input type="text" name="withoutcount" id="withcount" readonly value="<?php echo $employeesWithNoMainVacation;?>"/>
            <br />
                        
            <input type="submit" name="submit" id="submit" value="Process Main Requests"/>
        </form>
        
         <div id="table">
             <H2>Current Main Vacation Requests</H2>
            <form method="post">
            <table>
                <thead>
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
                            <td> <button type="submit" name="approve1st"  value="<?php echo $request[MAIN_VACATION_REQ_ID]; ?>">Approve 1st Choice</button></td>
                            <td> <button type="submit" name="approve2nd"  value="<?php echo $request[MAIN_VACATION_REQ_ID]; ?>">Approve 2nd Choice</button></td>
                            <td> <button type="submit" name="reject"  value="<?php echo $request[MAIN_VACATION_REQ_ID]; ?>">Reject</button></td>
                        </tr>
                        <?php }} ?>
                </tbody>
            </table>
            </form>
        </div>  
      
         <div id="table">
             <H2>Current Ad Hoc Absence Requests </H2>
            <form method="post">
            <table>
                <thead>
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
                            <td> <button type="submit" name="approveadhoc"  value="<?php echo $request[AD_HOC_REQ_ID]; ?>">Approve</button></td>
                            <td> <button type="submit" name="rejectadhoc"  value="<?php echo $request[AD_HOC_REQ_ID]; ?>">Reject</button></td>
                        </tr>
                        <?php }} ?>
                </tbody>
            </table>
            </form>
        </div>  
        
    </body>
</html>
