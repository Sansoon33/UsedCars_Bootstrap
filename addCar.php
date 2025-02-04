<?php
require_once('./config/db.php');
include('./config/auth.php');
include('./templates/header-logged-in.php');


//  initializing error message variables and form entries to empty strings
$makeErr = $modelErr = $yearErr = $mileageErr = $colorErr = $carConditionErr = $askPriceErr = "";
$make = $model = $year = $mileage = $color = $carCondition = $askPrice  = "";

//  initializing a success/fail message
$message = "";

//  all inputs passed through test_input() for security
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

//  ------ Validation & Error Handling ------

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["make"])) {
        $makeErr = "&nbsp Make is required &nbsp";
    } else {
        $makeErr = "";
        $make = test_input($_POST["make"]);
        //  regex: letters, dashes and spaces allowed, not numbers
        if (!preg_match("/^[a-zA-Z -]*$/", $make)) {
            $makeErr = "&nbsp Only letters, dashes and spaces allowed &nbsp";
        }
    }

    if (empty($_POST["model"])) {
        $modelErr = "&nbsp Model is required &nbsp";
    } else {
        $modelErr = "";
        $model = test_input($_POST["model"]);
        //  regex: alphanumeric, plus sign, spaces and dashes accepted 
        if (!preg_match("/^[a-zA-Z0-9 +-]*$/", $model)) {
            $modelErr = "&nbsp Only letters, numbers, spaces, '+', '-' allowed &nbsp";
        }
    }

    if (empty($_POST["year"])) {
        $yearErr = "&nbsp Year is required &nbsp";
    } else {
        $yearErr = "";
        $year = test_input($_POST["year"]);
        //  checks for years between 1930 & current year, also whether input is numeric
        if ($year < 1930 || $year > date("Y") || !is_numeric($year)) {
            $yearErr = "&nbsp Invalid year &nbsp";
        }
    };


    if (empty($_POST["mileage"])) {
        $mileageErr = "&nbsp Mileage is required &nbsp";
    } else {
        $mileageErr = "";
        $mileage = test_input($_POST["mileage"]);
        //  regex: only digits, min 1, max 6 as per db constraint
        if (!preg_match("/^[0-9]{1,6}$/", $mileage)) {
            $mileageErr = "&nbsp Please enter only digits &nbsp";
        }
    }

    if (empty($_POST["color"])) {
        $colorErr = "&nbsp Color is required &nbsp";
    } else {
        $colorErr = "";
        $color = test_input($_POST["color"]);
        if (!preg_match("/^[a-zA-Z -]*$/", $color)) {
            $colorErr = "&nbsp Only letters, spaces and dashes allowed &nbsp";
        }
    }
    //  just-in-case check, but not necessary as this is a select/optgroup
    if (!empty($_POST["car_condition"])) {
        $carCondition = test_input($_POST["car_condition"]);
    }


    if (empty($_POST["asking_price"])) {
        $askPriceErr = "&nbsp Asking price is required &nbsp";
    } else {
        $askPriceErr = "";
        $askPrice = test_input($_POST["asking_price"]);
        // regex: only positive ints up to 7 digits long, no decimals. HTML input also has min/max constraints
        if (!preg_match("/^[0-9]{1,7}$/", $askPrice)) {
            $askPriceErr = "&nbsp Only digits allowed &nbsp";
        }
    }


    //  ------ Adding a new Car to the db ------

    if (
        empty($makeErr) &&
        empty($modelErr) &&
        empty($yearErr) &&
        empty($mileageErr) &&
        empty($colorErr) &&
        empty($$askPriceErr)
    ) {

        //  ------ Form input into variables for entry into db ------

        //  make, model, color are all converted to lowercase then thier first chars are capitalized, even if words are separated by space(s), new lines, tabs, dashes, return carriages or apostrophes (delimiters provided in arguments of ucwords()). Numbers unaffected/stay intact.

        $make = $_POST["make"];
        $make = ucwords(strtolower($make), " \t\r\n'-");

        $model = $_POST["model"];
        $model = ucwords(strtolower($model), " \t\r\n'-");

        $year = $_POST["year"];

        $color = $_POST["color"];
        $color = ucwords(strtolower($color), " \t\r\n'-");

        $carCondition = $_POST["car_condition"];

        $askPrice = $_POST["asking_price"];
        //  matches mySQL date format, adds the current actual date
        $datePosted = date("Y.m.d");

        $user_id = $_SESSION['user_id'];

        $addQuery = "INSERT INTO `cars`(user_id, make, model, `year`, mileage, color, car_condition, asking_price, date_posted) VALUES ('" . $user_id . "', '" . $make . "', '" . $model . "', '" . $year . "', '" . $mileage . "', '" . $color . "',  '" . $carCondition . "',  '" . $askPrice . "',  '" . $datePosted . "')";

        $result = mysqli_query($con, $addQuery);
        $success = "Car added successfully! <a href='user-dashboard.php'> Add another listing?</a>";
        $fail = "Unsuccessful registration, check your entries";

        $message = $result ? $success : $fail;
    }
}



?>

<head>
    <title>UsedCars | Add Car</title>
</head>

<div class="container">
    <h1 class="text-center"> Add Car Listing</h1>

    <p class="text-center">
        Add a new car for sale to your account.
    </p>
    <form id="carForm2" name="addCar" onsubmit="return validateAddCarForm()" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">

        <label for="make" class="form-label">Car Make: </label><span id="makeErr" class="error"><?= $makeErr ?></span>
        <input type="text" class="form-control" name="make" placeholder="Ex: Honda" value="<?= (isset($make)) ? $make : ''; ?>"><br>



        <label for="model" class="form-label">Car Model: </label><span id="modelErr" class="error"><?= $modelErr ?></span>
        <input type="text" class="form-control" name="model" placeholder="Ex: Accord" value="<?= (isset($model)) ? $model : ''; ?>"><br>


        <label for="year" class="form-label">Year: </label><span id="yearErr" class="error"><?= $yearErr ?></span>
        <input type="text" class="form-control" name="year" placeholder="Ex: 1998" value="<?= (isset($year)) ? $year : ''; ?>"><br>
        


        <label for="mileage" class="form-label">Mileage: </label><span id="mileageErr" class="error"><?= $mileageErr ?></span>
        <input type="text" class="form-control" name="mileage" placeholder="Ex: 210000" value="<?= (isset($mileage)) ? $mileage : ''; ?>">
        <br>


        <label for="color" class="form-label">Color: </label><span id="colorErr" class="error"><?= $colorErr ?></span>
        <input type="text" class="form-control" name="color" placeholder="Ex: Blue" value="<?= (isset($color)) ? $color : ''; ?>"><br>
        


        <label for="car_condition" class="form-label">Condition:</label><span id="carConditionErr" class="error"><?= $carConditionErr ?></span>
        <select id="buttonMarginsSelect" class="btn btn-secondary" name="car_condition" value="<?= (isset($carCondition)) ? $carCondition : ''; ?>">
            <option value="Like New">Like New</option>
            <option value="Very Good">Very Good</option>
            <option value="Good">Good</option>
            <option value="Fair">Fair</option>
            <option value="Poor">Poor</option>
            <option value="Very Poor">Very Poor</option>
        </select>
        <!-- error technically not needed: -->
        
        <br>

        <label for="asking_price" class="form-label">Asking Price: </label><span id="askPriceErr" class="error"><?= $askPriceErr ?></span>
        <input type="text" class="form-control" name="asking_price" placeholder="Ex: 2400" value="<?= (isset($askPrice)) ? $askPrice : ''; ?>" min="1" max="9999999"><br>
        

        <br>
        <!-- bottom-of-form buttons -->
        <input id="buttonMargins" type="submit" value="Add Car" class="btn btn-success"><br>

        <a href="user-dashboard.php"><input id="buttonMargins" type="button" value="Go Back" class="btn btn-primary"></a>

    </form>
    <p class='addcarMsg'><?php echo $message; ?></p>

</div>

<?php
include('./templates/footer.php');
?>