<?php
include 'sessionmanagement.php';
include 'databaseFunctions.php';

if (!$isAdministrator)
{
   header('Location: index.php');
   exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Dates</title>
         <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
         <link rel="stylesheet" href="style.css">

        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    </head>
 
    <body>
        <?php include 'navbar.php'; ?>
        
        <div id="table">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
            <form method="post">
            <table class="table table-hover table-bordered">
                <br/> <br/> <br/>
                <thead>
                    <tr>
                <h1> Current Dates </h1>
                        <th>Date ID</th>
                        <th>Date</th>
                        <th>Public Holiday ID</th>
                        <th>Public Holiday Name</th>
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
                        </tr>
                        <?php }} ?>
                </tbody>
            </table>
            </form>
                </div>
            </div>
        </div>
    </body>
</html>
