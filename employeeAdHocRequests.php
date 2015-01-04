<?php
include 'sessionmanagement.php';
include 'databasefunctions.php';


if (isset($_POST["submit"])) 
{
    $request = CreateAdHocAbsenceRequest($userID,
                                         $_POST["startDate"],
                                         $_POST["endDate"],
                                         $_POST["absenceType"]);
    $url = "Location:index.php";   
    header($url);

}
?>

<!DOCTYPE html>
<html>
    <head>
         <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
       <meta charset="UTF-8">
        <title>Employee Create Ad Hoc Request</title>
    </head>
 
    <body>
        <?php include 'navbar.php'; ?>
 
        <form method="post">
        
            <div class="row">
            <div class="col-md-4 col-md-offset-4 text-center">
            <h1> Create Ad Hoc Request </h1>    
            <div class="input-group" for="startDate">
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>	
            <input type="date" class="form-control" name="startDate" id="startDate" placeholder="Start Date">
            </div>
     
            
            <div class="input-group" for="endDate">
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>	
            <input type="date" class="form-control" name="endDate" id="endDate" placeholder="End Date">
            </div>    
            <p class="text-center">     
            <label for="absenceType">Absence Type</label>
            </p>
            <?php  
                $absenceTypes = RetrieveAbsenceTypes();
                if ($absenceTypes <> NULL)
                {
                    echo '<select class="form-control" name="absenceType">';
                    foreach ($absenceTypes as $absenceType)
                        {
                        echo '<option value="'.$absenceType[ABS_TYPE_ID].'">'.$absenceType[ABS_TYPE_NAME].'</option>';
                    }
                }
            echo '</select>';
            ?>
            <br />
            
            <input class="btn btn-success btn-block" type="submit" name="submit" id="submit" value="Add AdHoc Request"/>
            </div>
            </div>
        </form>
        </div>  
    </body>
</html>
