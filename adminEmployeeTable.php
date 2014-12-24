<?php
include 'databaseFunctions.php';

if (isset($_POST["submit"])) 
{
    $employee = CreateEmployee($_POST["empName"], 
                               $_POST["eMail"],
                               $_POST["password"],
                               $_POST["dateJoin"], 
                               $_POST["annualLeave"],
                               NULL,
                               $_POST["companyRole"]);
}

if (isset($_POST["amend"])) {   
    $url = "Location:editEmployee.php?ID=".$_POST["amend"];   
    header($url);
}

if (isset($_POST["delete"])) 
{
    DeleteEmployee($_POST["delete"]);
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
            <label for="empName"> Employee Name </label>
            <input type="text" name="empName" id="empName" placeholder="Name" />
            
            <br />
            
            <label for="eMail"> Email </label>
            <input type="text" name="eMail" id="eMail" placeholder="E-Mail" />
            
            <br />
            
            <label for="password">Password</label>
            <input type="text" name="password" id="password" placeholder="Password" />
            
            <br />
            
            <label for="dateJoin"> Date Joined</label>
            <input type="date" name="dateJoin" id="dateJoin" />
            
            <br />
            
            <label for="annualLeave">Annual Leave Entitlement</label>
            <input type="range" name="annualLeave" min="10" max="28" value="19" step="1" 
                   oninput="updateAnnualLeave(value)"  id="annualLeave" /> 
            <output for="minStaff" id="Leave">19</output>
            
            <br/>
            
            <label for="companyRole">Company Role</label>
            <?php  
    
                $roles = RetrieveCompanyRoles();
                if ($roles <> NULL)
                {
                    echo '<select name="companyRole">';
                    foreach ($roles as $role)
                    {
                        echo '<option value="'.$role[COMP_ROLE_ID].'">'.$role[COMP_ROLE_NAME].'</option>';
                    }
                }
                
            echo '</select>';
            ?>
            <br/>
            
            <input type="submit" name="submit" id="submit" value="Add Employee"/>

            <script>
                function updateAnnualLeave(level)
                {
                    document.querySelector('#Leave').value = level;
                }
            </script>
            
        </form>
        
            <div id="table">
            <form method="post">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Date Joined</th>
                        <th>Annual Leave Entitlement</th>
                        <th>Company Role</th>
                        <th>Amend</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $employees = RetrieveEmployees();
                    if ($employees <> NULL)
                    {
                        foreach ($employees as $employee) { 
                            $role = RetrieveCompanyRoleByID($employee[EMP_COMPANY_ROLE]);
                            ?>
                        <tr>
                            <td><?php echo $employee[EMP_NAME]; ?></td>
                            <td><?php echo $employee[EMP_EMAIL]; ?></td>
                            <td><?php echo $employee[EMP_PASSWORD]; ?></td>
                            <td><?php echo $employee[EMP_DATEJOINED]; ?></td>
                            <td><?php echo $employee[EMP_LEAVE_ENTITLEMENT]; ?></td>
                            <td><?php echo $role[COMP_ROLE_NAME]; ?></td>
                            <td> <button type="submit" name="amend"  value="<?php echo $employee[EMP_ID]; ?>">Amend</button></td>
                            <td> <button type="submit" name="delete"  value="<?php echo $employee[EMP_ID]; ?>">Delete</button></td>
                        </tr>
                        <?php }} ?>
                </tbody>
            </table>
            </form>
        </div>  
      

    </body>

</html>

