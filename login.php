<!-- Header -->
<?php
require('config/db.php');
include('templates/header-logged-out.php');
?>
<?php
session_start();

$error = "";
$username = $password = "";

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// check if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);

    $query = "SELECT * FROM `users` WHERE username='" . $username . "' and password='" . md5($password) . "';";

    $result = mysqli_query($con, $query);

    $count = mysqli_num_rows($result);

    if ((isset($_POST['submit'])) && $count == 0) {
        $error = "Username/Password invalid.";
    }
    if ((isset($_POST['submit'])) && $count > 0) {
        $row = mysqli_fetch_assoc($result);

        if ($row['usertype'] == "admin") {
            // define the $_SESSION['user_id']
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['usertype'] = $row['usertype'];
            header("Location: admin-dashboard.php");
        } else if ($row['usertype'] == "user") {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['usertype'] = $row['usertype'];
            header('Location: user-dashboard.php');
        }
    }
}

?>

<head>
    <title>UsedCars | Log In</title>
</head>

<div class="form container text-center">
    <div class="row">
        <div class="col">

        </div>
        <div class="col">
            <h1>Used Cars Portal</h1>
            <h2>Log In</h2>
            <br>
            <!-- FORM -->
            <form id="carForm" action="" method="post" name="login">
                <!-- User Inputs -->
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required />
                </div>
                <span class="error"><?php echo $error ?></span>
                <input name="submit" type="submit" value="Login" />
            </form>
            <br>
            <p class="notreg">&nbsp Not registered yet? <a href='registration.php'>Register Here</a> &nbsp</p>
        </div>
        <div class="col">

        </div>
    </div>

</div>

<!-- Footer -->

<?php include "templates/footer.php" ?>