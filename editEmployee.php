<?php
include 'databaseFunctions.php';

if ($_GET["ID"] <> NULL)
{
    $employee = RetrieveEmployeeByID($_GET["ID"]);
}

if (isset($_POST["cancel"])) {   
    $url = "Location:adminEmployeeTable.php";   
    header($url);
}

if (isset($_POST["update"])) {
    $employee[EMP_NAME]       =   $_POST["empName"];
    $employee[EMP_EMAIL]      =   $_POST["eMail"];
    $employee[EMP_PASSWORD]   =   $_POST["password"];
    $employee[EMP_DATEJOINED] =   $_POST["dateJoin"];
    $employee[EMP_LEAVE_ENTITLEMENT]       =   $_POST["annualLeave"];
    $employee[EMP_COMPANY_ROLE]       =   $_POST["companyRole"];
    UpdateEmployee($employee);

    $url = "Location:adminEmployeeTable.php";   
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
        <form method="post">
            <label for="empName"> Employee Name </label>
            <input type="text" name="empName" id="empName" placeholder="Name"
                   value="<?php echo $employee[EMP_NAME]; ?>"/>
            
            <br />
            
            <label for="eMail"> Email </label>
            <input type="text" name="eMail" id="eMail" placeholder="E-Mail"
                   value="<?php echo $employee[EMP_EMAIL]; ?>"/>
            
            <br />
            
            <label for="password">Password</label>
            <input type="text" name="password" id="password" placeholder="Password"
                   value="<?php echo $employee[EMP_PASSWORD]; ?>"/>
            
            <br />
            
            <label for="dateJoin"> Date Joined</label>
            <input type="date" name="dateJoin" id="dateJoin" 
                   value="<?php echo $employee[EMP_DATEJOINED]; ?>"/>
            
            <br />
            
            <label for="annualLeave">Annual Leave Entitlement</label>
            <input type="range" name="annualLeave" min="10" max="28" value="<?php echo $employee[EMP_LEAVE_ENTITLEMENT]; ?>"
                   step="1" oninput="updateAnnualLeave(value)"  id="annualLeave" /> 
            <output for="minStaff" id="Leave"><?php echo $employee[EMP_LEAVE_ENTITLEMENT]; ?></output>
            
            <br/>
            
            <label for="companyRole">Company Role</label>
            <?php  
    
                $roles = RetrieveCompanyRoles();
                if ($roles <> NULL)
                {
                    echo '<select name="companyRole">';
                    foreach ($roles as $role)
                    {
                        if ($role[COMP_ROLE_ID]== $employee[EMP_COMPANY_ROLE])
                        {
                            echo '<option selected="selected" value="'.$role[COMP_ROLE_ID].'">'.$role[COMP_ROLE_NAME].'</option>';
                        
                        }
                        else 
                        {
                            echo '<option value="'.$role[COMP_ROLE_ID].'">'.$role[COMP_ROLE_NAME].'</option>';
                        }
                    }
                }
                
            echo '</select>';
            ?>
            <br/>
            <br/>
            
            <input type="submit" name="update" id="submit" value="Edit Employee"/>
            <input type="submit" name="cancel" id="cancel" value="Cancel Changes"/>

            <script>
                function updateAnnualLeave(level)
                {
                    document.querySelector('#Leave').value = level;
                }
            </script>
        </form>
    </body>
</html>
        