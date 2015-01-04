<?php
include 'sessionmanagement.php';
include 'databasefunctions.php';

if (!$isManager AND !$isAdministrator) {
    header('Location: index.php');
    exit();
}


if (isset($_POST["submit"])) {
    $request = CreateAdHocAbsenceRequest($_POST["employeeID"], $_POST["startDate"], $_POST["endDate"], $_POST["absenceType"]);
}

if (isset($_POST["amend"])) {
    $url = "Location:editAdHocAbsenceRequest.php?ID=" . $_POST["amend"] . "&back=adminAdHocAbsenceRequest.php";
    header($url);
}

if (isset($_POST["delete"])) {
    DeleteAdHocAbsenceRequest($_POST["delete"]);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin AdHoc Requests</title>
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
                    <h1>Create Ad Hoc Absence Request</h1>

                    <div class="input-group" for="employeeName">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                        <select class="form-control" name="employeeID" id="employeeID" >
                            <option value="" disabled selected>Select Employee</option>

<?php
$employees = RetrieveEmployees();
if ($employees <> NULL) {
    foreach ($employees as $employee) {
        echo '<option value="' . $employee[EMP_ID] . '">' . $employee[EMP_NAME] . '</option>';
    }
}
?>
                        </select>
                    </div>

                    <div class="input-group" for=startDate">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input type="date" class="form-control" name="startDate" id="startDate" placeholder="Start Date">
                    </div>  

                    <div class="input-group" for=endDate">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input type="date" class="form-control" name="endDate" id="endDate" placeholder="End Date">
                    </div>

                    <br />

                    <label for="absenceType">Absence Type</label>
<?php
$absenceTypes = RetrieveAbsenceTypes();
if ($absenceTypes <> NULL) {
    echo '<select class="form-control" name="absenceType">';
    foreach ($absenceTypes as $absenceType) {
        echo '<option value="' . $absenceType[ABS_TYPE_ID] . '">' . $absenceType[ABS_TYPE_NAME] . '</option>';
    }
}


echo '</select>';
?>
                    <br/>
                    <input class="btn btn-success btn-block" type="submit" name="submit" id="submit" value="Add AdHoc Request"/>
                </div>
            </div>
        </form>

<?php if ($isAdministrator) { ?>
        <div class="col-md-8 col-md-offset-2 text-center">
            <form method="post">
                <br/><br/><br/>
                <h1>Current Ad Hoc Absence Requests</h1>

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Absence Type</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$requests = RetrieveAdHocAbsenceRequests();
if ($requests <> NULL) {
    foreach ($requests as $request) {
        $employeeID = $request[AD_HOC_EMP_ID];
        $employee = RetrieveEmployeeByID($employeeID);

        $absenceTypeID = $request[AD_HOC_ABSENCE_TYPE_ID];
        $absenceType = RetrieveAbsenceTypeByID($absenceTypeID)
        ?>
                                <tr>
                                    <td><?php echo $employee[EMP_NAME]; ?></td>
                                    <td><?php echo $request[AD_HOC_START]; ?></td>
                                    <td><?php echo $request[AD_HOC_END]; ?></td>
                                    <td><?php echo $absenceType[ABS_TYPE_NAME]; ?></td>
                                    <td> <button class="btn btn-success" type="submit" name="amend"  value="<?php echo $request[AD_HOC_REQ_ID]; ?>">Amend</button></td>
                                    <td> <button class="btn btn-danger" type="submit" name="delete"  value="<?php echo $request[AD_HOC_REQ_ID]; ?>">Delete</button></td>
                                </tr>
                            <?php }
                        } ?>
                    </tbody>
                </table>
            </form>
        </div>  
<?php } ?>

    </body>
</html>