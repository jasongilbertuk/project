<?php
include 'databaseFunctions.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Approved Absence Booking Dates</title>
    </head>
 
    <body>
            <a href="index.php">Back to Homepage</a>

        <div id="table">
            <form method="post">
            <table>
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
            </form>
        </div>

    </body>

</html>
