<?php
include 'databaseFunctions.php';

if (isset($_POST["submit"])) 
{
    $request = CreateMainVactionRequest($_POST["employeeid"], 
                               $_POST["firstChoiceStart"],
                               $_POST["firstChoiceEnd"],
                               $_POST["secondChoiceStart"], 
                               $_POST["secondChoiceEnd"]);
}


if (isset($_POST["amend"])) {   
    $url = "Location:editMainRequest.php?ID=".$_POST["amend"];   
    header($url);
}

if (isset($_POST["delete"])) 
{
    DeleteMainVacationRequest($_POST["delete"]);
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
            <label for="empName">Employee</label>
               <?php  
    
                $employees = RetrieveEmployees();
                if ($employees <> NULL)
                {
                    echo '<select name="employeeid" id="employeeid">';
                    foreach ($employees as $employee)
                    {
                        echo '<option value="'.$employee[EMP_ID].'">'.$employee[EMP_NAME].'</option>';
                    }
                }
            echo '</select>';
            ?> 
            <br />
            
            <label for="firstChoiceStart">First Choice Start </label>
             <input type="date" name="firstChoiceStart" id="firstChoiceStart" /> 
            <br />
            
            <label for="firstChoiceEnd">First Choice End</label>
             <input type="date" name="firstChoiceEnd" id="firstChoiceEnd" /> 
            <br />
            
            <label for="secondChoiceStart">Second Choice Start</label>
             <input type="date" name="secondChoiceStart" id="secondChoiceStart" /> 
            <br />
            
            <label for="secondChoiceEnd">Second Choice End</label>
             <input type="date" name="secondChoiceEnd" id="secondChoiceEnd" /> 
            <br/>
            
            <input type="submit" name="submit" id="submit" value="Add Main Vacation Request"/>
   
        </form>
        
            <div id="table">
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
                            <td> <button type="submit" name="amend"  value="<?php echo $request[MAIN_VACATION_REQ_ID]; ?>">Amend</button></td>
                            <td> <button type="submit" name="delete"  value="<?php echo $request[MAIN_VACATION_REQ_ID]; ?>">Delete</button></td>
                        </tr>
                        <?php }} ?>
                </tbody>
            </table>
            </form>
        </div>  
      

    </body>

</html>
