<!-- Header -->
<?php
require('config/db.php');
include('config/auth.php');
include('templates/header-logged-in.php');

$car_id = $_REQUEST['car_id'];

$query = "SELECT * FROM `cars` WHERE `car_id`='$car_id'";

$result = mysqli_query($con, $query);

$rows = mysqli_fetch_assoc($result);

?>

<head>
    <title>UsedCars | Dashboard</title>
</head>

<body>
    <h2 align="center" style="padding:10px;">Update Your Car Listing</h2>
    <div class="container">
        <?php
        $status = "";
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $make = $_POST['make'];
            $model = $_POST['model'];
            $year = $_POST['year'];
            $mileage = $_POST['mileage'];
            $color = $_POST['color'];
            $condition = $_POST['condition'];
            $price = $_POST['price'];
            $posted_date = date("Y-m-d");

            $update = "UPDATE `cars` set make= '" . $make . "', model= '" . $model . "', year= '" . $year . "', mileage= '" . $mileage . "', 
            color= '" . $color . "', car_condition= '" . $condition . "', asking_price= '" . $price . "', date_posted= '" . $posted_date . "' WHERE car_id='". $car_id . "';";

            $result2 = mysqli_query($con, $update);

            if ($result2) {
                $status = "<p style='font-size:15pt; text-align:center;'>You have successfully updated your listing. <a href='user-dashboard.php'>View updated listing here!</a></p>";
                echo $status;
            } else {
                $status = "<p style='font-size:15pt text-align:center;'>Update unsuccessful. Please try again.</p>";
                echo $status;
            }

        } else {
        ?>
        <form id="carForm2" name="form" method="post" action="">
            <input type="hidden" name="new" value="1" />

            <label for="make" class="form-label">Make:</label>
            <input type="text" class="form-control" name="make" required value="<?php echo $rows['make']; ?>" />
            <span class="error"><?php  ?></span>
            <br>
            <label for="model" class="form-label">Model:</label>
            <input type="text" class="form-control" name="model" required value="<?php echo $rows['model']; ?>" />
            <span class="error"><?php  ?></span>
            <br>
            <label for="year" class="form-label">Year:</label>
            <input type="text" class="form-control" name="year" required value="<?php echo $rows['year']; ?>" />
            <span class="error"><?php  ?></span>
            <br>
            <label for="mileage" class="form-label">Mileage:</label>
            <input type="text" class="form-control" name="mileage" required value="<?php echo $rows['mileage']; ?>" />
            <span class="error"><?php  ?></span>
            <br>
            <label for="color" class="form-label">Color:</label>
            <input type="text" class="form-control" name="color" required value="<?php echo $rows['color']; ?>" />
            <span class="error"><?php  ?></span>
            <br>
            <label for="condition" class="form-label">Condition:</label>
            <select name="condition">
                <optgroup label="--Select Condition--">
                    <option value="<?php echo $rows['car_condition'] ?>" hidden><?php echo $rows['car_condition'] ?></option>
                    <option value="Like New">Like New</option>
                    <option value="Very Good">Very Good</option>
                    <option value="Good">Good</option>
                    <option value="Fair">Fair</option>
                    <option value="Poor">Poor</option>
                    <option value="Very Poor">Very Poor</option>
                </optgroup>
            </select>
            <br>
            <label for="price" class="form-label">Asking Price</label>
            <input type="text" class="form-control" name="price" required value="<?php echo $rows['asking_price']; ?>" />
            <span class="error"><?php  ?></span>
            <br>

            <input name="submit" type="submit" value="Update" />
            <a href="user-dashboard.php"><input type="button" value="Go Back" class="bottom-btn back"></a>
            <br />
        </form>
        <?php } ?>
    </div>
</body>

<!-- Footer -->
<?php include "templates/footer.php" ?>