<?php
include 'databaseFunctions.php';

$returnURL = "index.php";
if (isset($_GET["back"]))
{
    $returnURL = $_GET["back"];
}

if ($_GET["ID"] <> NULL)
{
    $request = RetrieveAdHocAbsenceRequestByID($_GET["ID"]);
    $employee = RetrieveEmployeeByID($request[AD_HOC_EMP_ID]);
}

if (isset($_POST["cancel"])) {   
    
    header("location:".$returnURL);
    exit;
}

if (isset($_POST["update"])) {
    $request[AD_HOC_REQ_ID]          =  $_GET["ID"];
    $request[AD_HOC_START]           =   $_POST["startDate"];
    $request[AD_HOC_END]             =   $_POST["endDate"];
    $request[AD_HOC_ABSENCE_TYPE_ID] =   $_POST["absenceType"];
    UpdateAdHocAbsenceRequest($request);

    header("location:".$returnURL);
    exit;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Ad Hoc Absence Requests</title>
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
                <h1> Edit Ad Hoc Request </h1>
            <label for="employeeName">Employee Name</label>

            <?php  
    
                $employees = RetrieveEmployees();
                if ($employees <> NULL)
                {
                    echo '<select class="form-control" name="employeeID">';
                    foreach ($employees as $employee)
                    if ($employee[EMP_ID]== $request[AD_HOC_EMP_ID])
                    {
                        echo '<option selected="selected" value="'.$employee[EMP_ID].'">'.$employee[EMP_NAME].'</option>';
                    }
                    else    
                    {
                        echo '<option value="'.$employee[EMP_ID].'">'.$employee[EMP_NAME].'</option>';
                    }
                }    
            echo '</select>';
            ?>
            <br />
            
            <div class="input-group" for="startDate">
		<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>	
  		<input type="date" class="form-control" name="startDate" id="startDate" 
                       value="<?php echo $request[AD_HOC_START]?>">
            </div>
  
            
            <div class="input-group" for="endDate">
		<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>	
  		<input type="date" class="form-control" name="endDate" id="endDate" 
                       value="<?php echo $request[AD_HOC_END]?>">   
            </div>
                
            <br/>                
            <p class="text-center">
            <label for="absenceType">Absence Type</label>
            <?php  
                $absenceTypes = RetrieveAbsenceTypes();
                if ($absenceTypes <> NULL)
                {
                    echo '<select class="form-control" name="absenceType">';
                    foreach ($absenceTypes as $absenceType)
                    if ($absenceType[ABS_TYPE_ID]== $request[AD_HOC_ABSENCE_TYPE_ID])
                        {
                        echo '<option selected="selected" value="'.$absenceType[ABS_TYPE_ID].'">'.$absenceType[ABS_TYPE_NAME].'</option>';                       
                        }
                        else                      
                        {
                        echo '<option value="'.$absenceType[ABS_TYPE_ID].'">'.$absenceType[ABS_TYPE_NAME].'</option>';
                    }
                }
            
                
            echo '</select>';
            ?>
            </p>
            <br />
            
            <input class="btn btn-success btn-block" type="submit" name="update" id="submit" value="Edit Request"/>
            <input class="btn btn-danger btn-block" type="submit" name="cancel" id="cancel" value="Cancel"/>
        </form>
    </body>
</html>