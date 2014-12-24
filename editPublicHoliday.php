<?php
include 'databaseFunctions.php';

if ($_GET["ID"] <> NULL)
{
    $record = RetrievePublicHolidayByID($_GET["ID"]);
    $date = RetrieveDateByID($record[PUB_HOL_DATE_ID]);
}

if (isset($_POST["cancel"])) {   
    $url = "Location:adminPublicHolidays.php";   
    header($url);
}

if (isset($_POST["update"])) {
    $record[PUB_HOL_ID]       =   $_GET["ID"];
    $record[PUB_HOL_NAME]     =   $_POST["name"];
    
    $filter[DATE_TABLE_DATE]= $_POST["date"];
    $dates = RetrieveDates($filter);
    
    $date = $dates[0];
    
    $record[PUB_HOL_DATE_ID]=   $date[DATE_TABLE_DATE_ID];
    UpdatePublicHoliday($record);

    $url = "Location:adminPublicHolidays.php";   
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
            <label for="roleName">Public Holiday Name</label>
            <input type="text" name="name" id="name" value="<?php echo $record[PUB_HOL_NAME];?>"/> 

            <br/>    

               <label for="date"> Date Joined</label>
            <input type="date" name="date" id="date" 
                   value="<?php echo $date[DATE_TABLE_DATE]; ?>"/>
            
            <br />
            
            <input type="submit" name="update" id="submit" value="Update"/> 
            <input type="submit" name="cancel" id="cancel" value="Cancel"/> 
        </form>

    </body>

</html>