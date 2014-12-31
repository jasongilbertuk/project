<?php
include 'sessionmanagement.php';
include 'databasefunctions.php';

if (!$isAdministrator)
{
   header('Location: index.php');
   exit();
}



 Function IsBookable($employeeID,$startDate,$endDate)
 {
     $leaveApproved = TRUE;
     
     $employee = RetrieveEmployeeByID($employeeID);
     echo "<br/>EmployeeID = $employeeID Employee Name = ".$employee[EMP_NAME];
     $role = RetrieveCompanyRoleByID($employee[EMP_COMPANY_ROLE]);
     echo "<br/>Role = ".$role[COMP_ROLE_NAME];
     $minimumLevel = $role[COMP_ROLE_MIN_STAFF];
     echo "<br/>Minimum Staffing Level for Role = $minimumLevel";
     $filter[EMP_COMPANY_ROLE] = $employee[EMP_COMPANY_ROLE];
     $employeesInRole = RetrieveEmployees($filter);
     foreach ($employeesInRole as $employeeInRole)
     {
        echo "<br/>Also in same role is ".$employeeInRole[EMP_NAME];
         
     }
     
     
     $totalEmployeesInRole = count($employeesInRole);
     echo "<br/>Total Employees in Role = $totalEmployeesInRole";
         
    $startTime = strtotime($startDate);
    $endTime = strtotime($endDate);

    // Loop between timestamps, 24 hours at a time
    for ($i = $startTime; $i <= $endTime; $i = $i + 86400) 
    {
        $thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
        echo "<br/>Checking for date = $thisDate";
        $staffInOffice = $totalEmployeesInRole;
        
        if (!isPublicHoliday($thisDate) AND !isWeekend($thisDate))
        {
            unset($filter);
            $filter[DATE_TABLE_DATE] = $thisDate;
            $dateRecords = RetrieveDates($filter);
            if ($dateRecords <> NULL)
            {
                unset($filter);
                $filter[APPR_ABS_BOOK_DATE_DATE_ID] = $dateRecords[0][DATE_TABLE_DATE_ID];
                $absenceBookingDates = RetrieveApprovedAbsenceBookingDates($filter);
                if ($absenceBookingDates <> NULL)
                {
                    $count = count($absenceBookingDates);
                    echo "<br/>Checking existing absence bookings for $thisDate. There are $count";
                    foreach ($absenceBookingDates as $absenceBookingDate)
                    {
                        $absenceBooking = RetrieveApprovedAbsenceBookingByID($absenceBookingDate[APPR_ABS_BOOK_DATE_ABS_BOOK_ID]);
                        $staffMember = RetrieveEmployeeByID($absenceBooking[APPR_ABS_EMPLOYEE_ID]);
                        if ($staffMember[EMP_COMPANY_ROLE]==$employee[EMP_COMPANY_ROLE])
                        {
                            echo "<br/>This day is also being taken by ".$staffMember[EMP_NAME];
                            $staffInOffice = $staffInOffice - 1;
                        }
                    }
                    
                    echo "<br/>Staff in office on this day = $staffInOffice";
                    
                    if ($staffInOffice <= $minimumLevel)
                    {
                        echo "<br/>Approving this would mean being below the minimum staffing level";
                        $leaveApproved = FALSE;
                        break;
                    }
                }
            }
        }
        else
        {
            echo "<br/>skipped this day as its a public holiday or weekend";

        }
    }

     return $leaveApproved;
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
        echo "Mail send worked";
    }
    else 
    {
        echo "Mail send failed";
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
            echo "<br/><br/>";
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
            
           $annualLeaveAlreadyBooked  = CalculateEmployeeLeaveTaken($employeeID);
           
           $leaveFor1stChoice = CalculateAnnualLeaveRequired($firstChoiceStartDate,
                                                             $firstChoiceEndDate,
                                                             $absenceType);
           
           $leaveFor2ndChoice = CalculateAnnualLeaveRequired($secondChoiceStartDate,
                                                             $secondChoiceEndDate,
                                                             $absenceType);
                
           
           echo "<br/>FIRST CHOICE CHECK";
           $firstChoiceAvailable    = IsBookable($employeeID,$firstChoiceStartDate,$firstChoiceEndDate);
           echo "<br/>SECOND CHOICE CHECK";
           $secondChoiceAvailable   = IsBookable($employeeID,$secondChoiceStartDate,$secondChoiceEndDate);
           echo "<br/>------------------------";
           
           $daysRemaining = $annualLeaveEntitlement - $annualLeaveAlreadyBooked;
           echo "<br/>Days Remaining for Employee = $daysRemaining";
           $enoughDaysForFirstChoice = ($daysRemaining >= $leaveFor1stChoice);
           if ($enoughDaysForFirstChoice)
           {
               echo "<br/>Employee has enough Days for their first choice";
           }
           else
           {
               echo "<br/>Employee does not have enough Days for their first choice";
           }
          
           $enoughDaysForSecondChoice = ($daysRemaining >= $leaveFor2ndChoice);
           if ($enoughDaysForFirstChoice)
           {
               echo "<br/>Employee has enough Days for their second choice";
           }
           else
           {
               echo "<br/>Employee does not have enough Days for their second choice";
           }
                   
           
           if ($firstChoiceAvailable AND $enoughDaysForFirstChoice)
           {
               echo "<br/>First Choice has been granted";
               
               SendMainVacationEmail($mainVacationRequestID,TRUE,FALSE);
               TurnMainVacationRequestIntoApprovedBooking($mainVacationRequestID,1);
           }
           else if ($secondChoiceAvailable AND $enoughDaysForSecondChoice)
           {
               echo "<br/>Second Choice would have been granted";
               SendMainVacationEmail($mainVacationRequestID,FALSE,TRUE);
               TurnMainVacationRequestIntoApprovedBooking($mainVacationRequestID,2);

           }
           else 
           {
               echo "<br/>Neither Choice would have been granted";
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
                        
            <input type="submit" name="processmainrequests" id="submit" value="Process Main Requests"/>
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
