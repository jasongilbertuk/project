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
        <title>Admin Approved Absence Booking Dates</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">

      	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    </head>
 
    <body>
        <?php include 'navbar.php'; ?>

        <div id="table">
            <form method="post">
            <div class="row">
            <div class="col-md-8 col-md-offset-2 text-center">
                <h1> Approved Absence Booking Dates </h1>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Record ID</th>
                        <th>Date ID</th>
                        <th>Approved Absence Booking ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $bookingDates = RetrieveApprovedAbsenceBookingDates();
                    if ($bookingDates <> NULL)
                    {
                        foreach ($bookingDates as $bookingDate) { ?>
                        <tr>
                            <td><?php echo $bookingDate[APPR_ABS_BOOK_DATE_ID]; ?></td>
                            <td><?php echo $bookingDate[APPR_ABS_BOOK_DATE_DATE_ID]; ?></td>
                            <td><?php echo $bookingDate[APPR_ABS_BOOK_DATE_ABS_BOOK_ID]; ?></td>
                        </tr>
                        <?php }} ?>
                </tbody>
            </table>
            </div>
            </div>   
            </form>
        </div>

    </body>

</html>