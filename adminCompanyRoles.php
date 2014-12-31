<?php
include 'sessionmanagement.php';
include 'databaseFunctions.php';

if (!$isAdministrator)
{
   header('Location: index.php');
   exit();
}


if (isset($_POST["submit"])) {
    $role = CreateCompanyRole($_POST["roleName"], $_POST["minStaff"]);
    }

if (isset($_POST["amend"])) {   
    $url = "Location:editcompanyrole.php?roleID=".$_POST["amend"];   
    header($url);
}

if (isset($_POST["delete"])) {
    DeleteCompanyRole($_POST["delete"]);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Company Roles</title>
    </head>
 
    <body>
            <a href="index.php">Back to Homepage</a>

        <form method="post">
            <label for="roleName">Company Role Name</label>
            <input type="text" name="roleName" id="roleName" placeholder="Enter name"/> 

            <br/>    

            <label for="minStaff">Minimum Staff Level</label>
            <input type="range" name="minStaff" min="0" max="30" value="1" step="1" 
                   oninput="updateMinStaff(value)"  id="minStaff" /> 
            <output for="minStaff" id="staffNumber">1</output>
            <br/>
            <input type="submit" name="submit" id="submit" value="Add Role"/> 
            
            
            <script>
                function updateMinStaff(level)
                {
                    document.querySelector('#staffNumber').value = level;
                }
            </script>
        </form>

        <div id="table">
            <form method="post">
            <table>
                <thead>
                    <tr>
                        <th>Role Name</th>
                        <th>Minimum Staffing Level</th>
                        <th>Amend</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $roles = RetrieveCompanyRoles();
                    if ($roles <> NULL)
                    {
                        foreach ($roles as $role) { ?>
                        <tr>
                            <td><?php echo $role[COMP_ROLE_NAME]; ?></td>
                            <td><?php echo $role[COMP_ROLE_MIN_STAFF]; ?></td>
                            <td> <button type="submit" name="amend"  value="<?php echo $role[COMP_ROLE_ID]; ?>">Amend</button></td>
                            <td> <button type="submit" name="delete"  value="<?php echo $role[COMP_ROLE_ID]; ?>">Delete</button></td>
                        </tr>
                        <?php }} ?>
                </tbody>
            </table>
            </form>
        </div>



    </body>

</html>
