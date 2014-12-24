<?php
include 'databaseFunctions.php';

if (isset($_POST["clear"])) {
    echo "clear";
    $sql = "DELETE * FROM dateTable;";
    performSQLDelete($sql);
}
  

if (isset($_POST["submit"])) {
    $year = $_POST["year"];
        
    // Set timezone
    date_default_timezone_set('UTC');
 
    // Start date
    $date = $year.'-01-01';
    // End date
    $end_date = $year.'-12-31';
 
    while (strtotime($date) <= strtotime($end_date)) 
    {
        CreateDate($date,NULL);
        $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
    }
}

if (isset($_POST["amend"])) {   
    $url = "Location:editdates.php?ID=".$_POST["amend"];   
    header($url);
}

if (isset($_POST["delete"])) {
    DeleteDate($_POST["delete"]);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Dates</title>
    </head>
 
    <body>
            <a href="index.php">Back to Homepage</a>

        <form method="post">
            <label for="year">Year</label>
            <input type="number" name="year" id="year" min="2014" max="2100"/> 
            <br />
               
            <input type="submit" name="submit" id="submit" value="Create Year in Database"/> 
            <input type="submit" name="clear" id="clear" value="Delete All Dates Table Records"/> 
        </form>

        <div id="table">
            <form method="post">
            <table>
                <thead>
                    <tr>
                        <th>Date ID</th>
                        <th>Date</th>
                        <th>Public Holiday ID</th>
                        <th>Public Holiday Name</th>
                        <th>Amend</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
                    $dates = RetrieveDates();
                    if ($dates <> NULL)
                    {
                        foreach ($dates as $date) { ?>
                        <tr>
                            <td><?php echo $date[DATE_TABLE_DATE_ID]; ?></td>
                            <td><?php echo $date[DATE_TABLE_DATE]; ?></td>
                            <td><?php echo $date[DATE_TABLE_PUBLIC_HOL_ID]; ?></td>
                            <?php 
                            $pubHolID = $date[DATE_TABLE_PUBLIC_HOL_ID]; 
                            $publicHolidayName = "";
                            
                            if ( $pubHolID <> NULL)
                            {
                                $publicHoliday = RetrievePublicHolidayByID($pubHolID);
                                $publicHolidayName = $publicHoliday[PUB_HOL_NAME];
                            }
                            ?>
                            
                            <td><?php echo  $publicHolidayName;?></td>
                            
                            <td> <button type="submit" name="amend"  value="<?php echo $date[DATE_TABLE_DATE_ID]; ?>">Amend</button></td>
                            <td> <button type="submit" name="delete"  value="<?php echo $date[DATE_TABLE_DATE_ID]; ?>">Delete</button></td>
                        </tr>
                        <?php }} ?>
                </tbody>
            </table>
            </form>
        </div>



    </body>

</html>
