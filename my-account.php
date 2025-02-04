<?php
require('config/db.php');
include('config/auth.php');
include('templates/header-logged-in.php');
?>

<?php
// check if user_id is set and store in variable
if (isset($_SESSION['user_id'])) {

    $user_id = $_SESSION['user_id'];

    // query -> check if user exists
    $query = "SELECT * FROM `users` WHERE user_id = '" . $user_id . "'";

    // execute query
    $result = mysqli_query($con, $query) or die(mysqli_error($con));

    $rows = mysqli_fetch_assoc($result);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UsedCars | My Account</title>
</head>

<!-- Body -->
<div class="container mt-5">
    <div class="row">
        <div class="col">

        </div>
        <div class="col">
            <h1 class="text-center"> Account Information</h1>

            <p class="text-center">
                Here is your account information.
            </p>

            <p class="text-center">You can edit or delete your account here.</p>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST['username'];
                $fname = $_POST['fname'];
                $lname = $_POST['lname'];
                $phone = $_POST['phone'];
                $email = $_POST['email'];
                $password = $_POST['password'];

                if ($password == $rows['password']) {
                    $update = "UPDATE `users` set username='" . $username . "', first_name='" . $fname . "', last_name='" . $lname . "', 
        phone_number='" . $phone . "', email='" . $email . "', password='" . $password . "' WHERE user_id='$user_id';";
                } else {
                    $update = "UPDATE `users` set username='" . $username . "', first_name='" . $fname . "', last_name='" . $lname . "', 
        phone_number='" . $phone . "', email='" . $email . "', password='" . md5($password) . "' WHERE user_id='" . $user_id . "';";
                }

                $result2 = mysqli_query($con, $update);

                if ($result2) {
                    $status = "<p style='font-size:15pt; text-align:center;'>You have successfully updated your account. <a href='user-dashboard.php'>Return to Dashboard here!</a></p>";
                    echo $status;
                } else {
                    $status = "<p style='font-size:15pt text-align:center;'>Update unsuccessful.<a href='my-account.php'> Try again here.</a></p>";
                    echo $status;
                }
            } else {
            ?>

                <form id="carForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="form">
                    <label for="username" class="form-label">Username: </label>
                    <input type="text" class="form-control" name="username" value="<?php echo $rows['username'] ?>" /><br />
                    <label for="fname" class="form-label">First Name: </label>
                    <input type="text" class="form-control" name="fname" value="<?php echo $rows['first_name'] ?>" /><br />
                    <label for="fname" class="form-label">Last Name: </label>
                    <input type="text" class="form-control" name="lname" value="<?php echo $rows['last_name'] ?>" /><br />
                    <label for="phone" class="form-label">Phone Number: </label>
                    <input type="text" class="form-control" name="phone" value="<?php echo $rows['phone_number'] ?>" /><br />
                    <label for="email" class="form-label">Email: </label>
                    <input type="text" class="form-control" name="email" value="<?php echo $rows['email'] ?>" /><br />
                    <label for="password" class="form-label">Password: </label>
                    <input type="password" class="form-control" name="password" value="<?php echo $rows['password'] ?>" /><br /><br />
                    <input id="buttonMargins" type="submit" name="update" value="Update Account" class="btn btn-warning" /><br />
                    <a href="delete-account.php?user_id=<?php echo $rows['user_id'] ?>"><input id="buttonMargins"  type="button" value="Delete Account" class="btn btn-danger"></a>
                </form>
            <?php } ?>
        </div>
        <div class="col">
        </div>
    </div>

</div>