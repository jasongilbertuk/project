<?php
include 'sessionmanagement.php';
include 'databaseFunctions.php';
  
if (!$isAdministrator)
{
   header('Location: index.php');
   exit();
}

if (isset($_POST["submit"])) {
    
    $filter[DATE_TABLE_DATE] = $_POST["date"];
    $record = RetrieveDates($filter);
    
    $holiday = CreatePublicHoliday($_POST["name"], $record[0][DATE_TABLE_DATE_ID]);
    }

if (isset($_POST["amend"])) {   
    $url = "Location:editpublicholiday.php?ID=".$_POST["amend"];   
    header($url);
}

if (isset($_POST["delete"])) {
    DeletePublicHoliday($_POST["delete"]);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Public Holidays</title>
    </head>
 
    <body>
            <a href="index.php">Back to Homepage</a>

        <form method="post">
            <label for="name">Public Holiday</label>
            <input type="text" name="name" id="name"/> 

            <br/>    

            <label for="date">Date</label>
            <input type="date" name="date" id="date"/>
            <br/>
            <input type="submit" name="submit" id="submit" value="Add"/> 
        </form>

        <div id="table">
            <form method="post">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Public Holday Name</th>
                        <th>Date ID</th>
                        <th>Date</th>
                        <th>Amend</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $holidays = RetrievePublicHolidays();
                    if ($holidays <> NULL)
                    {
                        foreach ($holidays as $holiday) { 
                            $date = RetrieveDateByID($holiday[PUB_HOL_DATE_ID]);
                            ?>
                        <tr>
                            <td><?php echo $holiday[PUB_HOL_ID]; ?></td>
                            <td><?php echo $holiday[PUB_HOL_NAME]; ?></td>
                            <td><?php echo $holiday[PUB_HOL_DATE_ID]; ?></td>
                            <td><?php echo $date[DATE_TABLE_DATE]; ?></td>
                            <td> <button type="submit" name="amend"  value="<?php echo $holiday[PUB_HOL_ID]; ?>">Amend</button></td>
                            <td> <button type="submit" name="delete"  value="<?php echo $holiday[PUB_HOL_ID]; ?>">Delete</button></td>
                        </tr>
                        <?php }} ?>
                </tbody>
            </table>
            </form>
        </div>



    </body>

</html>
