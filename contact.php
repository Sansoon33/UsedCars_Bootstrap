<!-- Header -->
<?php
require('config/db.php');
include('config/auth.php');
include('templates/header-logged-in.php');
?>

<?php
// check if user_id is set and store in variable
if (isset($_REQUEST['user_id'])) {

    $user_id = $_REQUEST['user_id'];

    // query -> check if user exists
    $query = "SELECT * FROM `users` WHERE user_id = '" . $user_id . "'";

    // execute query
    $result = mysqli_query($con, $query) or die(mysqli_error($con));

    $rows = mysqli_fetch_assoc($result);
}

?>

<head>
    <title>UsedCars | Contact Seller</title>
</head>

<!-- Body -->
<div class="container mt-5">
    <!-- Echo User First Name -->
    <h1 class="text-center"> Seller Contact Information</h1>

    <p class="text-center">
        Here is the car seller's contact information.
    </p>

    <p class="text-center">Contact them if you are interested in the car! </p>

    <form id="carForm2" action="" method="post" name="form">
        <label for="name" class="form-label">Name: </label>
        <input type="text" class="form-control" name="name" value="<?php echo $rows['first_name'] . " " . $rows['last_name'] ?>" readonly /><br />
        <label for="phone" class="form-label">Phone Number: </label>
        <input type="text" class="form-control" name="phone" value="<?php echo $rows['phone_number'] ?>" readonly /><br />
        <label for="email" class="form-label">Email: </label>
        <input type="text" class="form-control" name="email" value="<?php echo $rows['email'] ?>" readonly /><br /><br />
        <a href="available-listings.php"><input type="button" class="btn btn-primary" value="Go Back"></a>
    </form>
</div>

<!-- Footer -->

<?php include "templates/footer.php" ?>