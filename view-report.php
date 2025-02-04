<!-- Header -->
<?php
require('config/db.php');
require('config/auth.php');
include('templates/header-logged-in.php');
?>

<head>
    <title>UsedCars | Reports</title>
</head>

<div class="container">
    <h1 class="text-center">Current Report</h1><br>
    <p class="text-center">
        Customize this Report and Export it to your computer!
    </p>
    <?php
    $add_report = "";
    $view_reports = "SELECT * FROM `reports`;";

    $listings = mysqli_query($con, $view_reports);

    $count = mysqli_num_rows($listings);

    if ($count == 0) {
        $add_report = "<p style='text-align:center;font-size:15pt;'> It looks like you do not have any active listings.<a href='add-report.php'> Add a new report here!</a></p>";
        echo $add_report;
    } else {
    ?>
        <table class="table table-striped table-hover listings">
            <thead>
                <tr>
                    <th>Contract ID</th>
                    
                    <th>Username</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Make</th>
                    <th>Mileage</th>
                    <th>Date Posted</th>
                    <th>Asking Price</th>
                    <th>Date of Sale</th>
                    <th>Final Price</th>
                    <th>Status</th>
                    <th><button class="btn btn-success"><a href="add-report.php"> Add new Contract</a></button></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT reports.contract_id, reports.date, users.username, users.phone_number, users.email, cars.make, cars.mileage, cars.date_posted, cars.asking_price, reports.final_price
                FROM (
                (reports
                INNER JOIN users ON reports.user_id = users.user_id)
                INNER JOIN cars ON reports.car_id = cars.car_id);";
                $view_reports = mysqli_query($con, $query);
                while ($row = mysqli_fetch_assoc($view_reports)) {
                ?>
                    <tr>
                        <td align="center"><?php echo $row['contract_id']; ?></td>
                        <td align="center"><?php echo $row['username']; ?></td>
                        <td align="center"><?php echo $row['phone_number']; ?></td>
                        <td align="center"><?php echo $row['email']; ?></td>
                        <td align="center"><?php echo $row['make']; ?></td>
                        <td align="center"><?php echo $row['mileage']; ?></td>
                        <td align="center"><?php echo $row['date_posted']; ?></td>
                        <td align="center"><?php echo $row['asking_price']; ?></td>
                        <td align="center"><?php echo $row['date']; ?></td>
                        <td align="center"><?php echo $row['final_price']; ?></td>
                        <td align="center">Sold</td>
                        <td align="center">
                            <button class="btn btn-danger mb-2 mt-2"><a href="delete-report.php?contract_id=<?php echo $row['contract_id']; ?>">Delete</a></button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        <?php } ?>
        </table>
        <button class="btn btn-success"><a href="export-csv.php" target="_blank">Export as CSV</a></button>

</div>

<!-- Footer -->
<?php include "templates/footer.php" ?>