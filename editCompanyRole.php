<?php
include 'sessionmanagement.php';
include 'databaseFunctions.php';

if (!$isAdministrator)
{
   header('Location: index.php');
   exit();
}

if ($_GET["roleID"] <> NULL)
{
    $role = RetrieveCompanyRoleByID($_GET["roleID"]);
}

if (isset($_POST["cancel"])) {   
    $url = "Location:adminCompanyRoles.php";   
    header($url);
}

if (isset($_POST["update"])) {
    $role[COMP_ROLE_ID]       =   $_GET["roleID"];
    $role[COMP_ROLE_NAME]     =   $_POST["roleName"];
    $role[COMP_ROLE_MIN_STAFF]=   $_POST["minStaff"];
    UpdateCompanyRole($role);

    $url = "Location:adminCompanyRoles.php";   
    header($url);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Amend Company Role</title>
    </head>
 
    <body>

        <form method="post">
            <label for="roleName">Company Role Name</label>
            <input type="text" name="roleName" id="roleName" value="<?php echo $role[COMP_ROLE_NAME];?>"/> 

            <br/>    

            <label for="minStaff">Minimum Staff Level</label>
            <input type="range" name="minStaff" min="0" max="30" value="<?php echo $role[COMP_ROLE_MIN_STAFF];?>" step="1" 
                   oninput="updateMinStaff(value)"  id="minStaff" /> 
            <output for="minStaff" id="staffNumber"><?php echo $role[COMP_ROLE_MIN_STAFF];?></output>
            <br/>
            <input type="submit" name="update" id="submit" value="Update"/> 
            <input type="submit" name="cancel" id="cancel" value="Cancel"/> 

            <script>
                function updateMinStaff(level)
                {
                    document.querySelector('#staffNumber').value = level;
                }
            </script>
        </form>

    </body>

</html>