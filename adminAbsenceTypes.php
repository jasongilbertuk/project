<?php
include 'databaseFunctions.php';
  
if (isset($_POST["submit"])) {

    
    $usesAnnualLeave = "0";
    if (isset($_POST["usesAnnualLeave"]))
    {
        $usesAnnualLeave = "1";
    }
    $canBeDenied = "0";
    if (isset($_POST["canBeDenied"]))
    {
        $canBeDenied = "1";
    }
    $role = CreateAbsenceType($_POST["absenceTypeName"], 
                              $usesAnnualLeave,
                              $canBeDenied);
}

if (isset($_POST["amend"])) {   
    $url = "Location:editabsencetype.php?ID=".$_POST["amend"];   
    header($url);
}

if (isset($_POST["delete"])) {
    DeleteAbsenceType($_POST["delete"]);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Absence Types</title>
    </head>
 
    <body>
            <a href="index.php">Back to Homepage</a>

        <form method="post">
            <label for="absenceTypeName">Absence Type Name</label>
            <input type="text" name="absenceTypeName" id="absenceTypeName" /> 
            <br/>

            <label for="usesAnnualLeave">Uses Annual Leave</label>
            <input type="checkbox" name="usesAnnualLeave" id="usesAnnualLeave" /> 
            <br/>
           <label for="canBeDenied">Can Be Denied</label>
            <input type="checkbox" name="canBeDenied" id="canBeDenied" /> 
            
            <input type="submit" name="submit" id="submit" value="Add Absence Type"/> 
        </form>

        <div id="table">
            <form method="post">
            <table>
                <thead>
                    <tr>
                        <th>Absence Type Name</th>
                        <th>Uses Annual Leave</th>
                        <th>Can Be Denied</th>
                        <th>Amend</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $absenceTypes = RetrieveAbsenceTypes();
                    if ($absenceTypes <> NULL)
                    {
                        foreach ($absenceTypes as $absenceType) { ?>
                        <tr>
                            <td><?php echo $absenceType[ABS_TYPE_NAME]; ?></td>
                            <td><?php echo $absenceType[ABS_TYPE_USES_LEAVE]; ?></td>
                            <td><?php echo $absenceType[ABS_TYPE_CAN_BE_DENIED]; ?></td>
                            <td> <button type="submit" name="amend"  value="<?php echo $absenceType[ABS_TYPE_ID]; ?>">Amend</button></td>
                            <td> <button type="submit" name="delete"  value="<?php echo $absenceType[ABS_TYPE_ID]; ?>">Delete</button></td>
                        </tr>
                        <?php }} ?>
                </tbody>
            </table>
            </form>
        </div>



    </body>

</html>
