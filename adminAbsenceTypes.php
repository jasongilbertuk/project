<?php
include 'sessionmanagement.php';
include 'databaseFunctions.php';

if (!$isAdministrator)
{
   header('Location: index.php');
   exit();
}

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
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">

        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    </head>
 
    <body>
        <?php include 'navbar.php'; ?>

        <form method="post" class="signUp">
            <div class="row">
                <div class="col-md-4 col-md-offset-4 text-center">
                    <h1>Create Absence Type</h1>
                    <div class="input-group" for="absenceTypeName">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                        <input type="text" class="form-control" placeholder="Absence Name" name="absenceTypeName" id="absenceTypeName">
                    </div>

                    <label for="usesAnnualLeave">Uses Annual Leave</label>
                    <input type="checkbox" name="usesAnnualLeave" id="usesAnnualLeave" /> 
                    
                    <label for="canBeDenied">&nbsp;&nbsp;Can Be Denied</label>
                    <input type="checkbox" name="canBeDenied" id="canBeDenied" /> 

                    <br /> <br />

                    <input class="btn btn-success btn-block" type="submit" name="submit" id="submit" value="Add Absence Type"/> 
                </div>
            </div>
        </form>

       <div class="col-md-8 col-md-offset-2 text-center">
            <form method="post">
                <br/><br/><br/>
                <h1>Current Absence Types</h1>
                <table class="table table-bordered table-hover">
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
                        if ($absenceTypes <> NULL) {
                            foreach ($absenceTypes as $absenceType) {
                                ?>
                                <tr>
                                    <td><?php echo $absenceType[ABS_TYPE_NAME]; ?></td>
                                    <td><?php echo $absenceType[ABS_TYPE_USES_LEAVE]; ?></td>
                                    <td><?php echo $absenceType[ABS_TYPE_CAN_BE_DENIED]; ?></td>
                                    <td> <button class="btn btn-success" type="submit" name="amend"  value="<?php echo $absenceType[ABS_TYPE_ID]; ?>">Amend</button></td>
                                    <td> <button class="btn btn-danger" type="submit" name="delete"  value="<?php echo $absenceType[ABS_TYPE_ID]; ?>">Delete</button></td>
                                </tr>
                            <?php }
                        } ?>
                    </tbody>
                </table>
            </form>
        </div>
    </body>
</html>
