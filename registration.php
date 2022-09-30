<!-- Header -->
<?php
require('config/db.php');
include('templates/header-logged-out.php');
?>

<?php
$usernameErr = $fnameErr = $lnameErr = $phoneErr = $emailErr = $passwordErr = "";
$username = $fname = $lname = $phone = $email = $password = "";

$message = "";

// ------------------ TEST INPUT FUNCTION ------------------
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// ------------------ FORM VALIDATION ------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['username'])) {
        $usernameErr = "&nbsp Username is required. &nbsp";
        //  checks whether username already exists, throws error if so. If not, continue.
    } else {
        $usernameQuery = mysqli_query($con, "SELECT * FROM `users` WHERE username = '" . $_POST['username'] . "'");
        if (mysqli_num_rows($usernameQuery)) {
            $usernameErr = "&nbsp This Username already exists. &nbsp";
        } else {
            $usernameErr = "";
            $username = test_input($_POST['username']);
            //  regex: alphanumerics allowed, min/max set from 2 to db constraint
            if (!preg_match("/^[a-zA-Z0-9]{5,20}$/", $username)) {
                $usernameErr = "&nbsp Only letters & numbers, minimum 5 characters, maxiumum 20 allowed. &nbsp";
            }
        }
    }


    if (empty($_POST['fname'])) {
        $fnameErr = "&nbsp First Name is required. &nbsp";
    } else {
        $fnameErr = "";
        $fname = test_input($_POST['fname']);
        //  regex: min 2 chars, max 50 as per db constraint, only letters allowed
        if (!preg_match("/^[a-zA-Z]{2,50}$/", $fname)) {
            $fnameErr = "&nbsp Only letters, minimum 2 characters, maxiumum 50 allowed.&nbsp";
        }
    }

    if (empty($_POST['lname'])) {
        $lnameErr = "&nbsp Last Name is required. &nbsp";
    } else {
        $lnameErr = "";
        $lname = test_input($_POST['lname']);
        //  regex: min 2 chars, max 50 as per db constraint, only letters allowed
        if (!preg_match("/^[a-zA-Z -]{2,50}$/", $lname)) {
            $lnameErr = "&nbsp Only letters, minimum 2 characters, maxiumum 50 allowed.&nbsp";
        }
    }

    if (empty($_POST['phone'])) {
        $phoneErr = "&nbsp Phone Number is Required. &nbsp";
    } else {
        $phoneErr = "";
        $phone = test_input($_POST['phone']);
        //  regex: numbers only, min 10, max 11 as per db constraint. North American numbers only.
        if (!preg_match("/^[0-9]{10,11}$/", $phone)) {
            $phoneErr = "&nbsp Minimum of 10 digits, maximum 11, no dashes between required. &nbsp";
        }
    }

    if (empty($_POST['email'])) {
        $emailErr = "&nbsp Email is required. &nbsp";
    } else {
        $emailErr = "";
        $email = test_input($_POST['email']);
        //  regex: standard email format, must have @ and domain suffix
        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) {
            $emailErr = "&nbsp Invalid email format. &nbsp";
        }
    }

    if (empty($_POST['password'])) {
        $passwordErr = "&nbsp Password is required. &nbsp";
    } else {
        $passwordErr = "";
        $password = test_input($_POST['password']);
        //  regex: as the error states, but limited to 20 here and 100 in db because .md5 needs that space for the hashed password values after processing
        if (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,20}$/", $password)) {
            $passwordErr = "&nbsp Password must be between 8 & 20 characters with at least one special character, one uppercase letter and one digit. &nbsp";
        }
    }

    // ------------------ SENDING TO DATABASE ------------------
    if (
        empty($usernameErr) &&
        empty($fnameErr) &&
        empty($lnameErr) &&
        empty($phoneErr) &&
        empty($emailErr) &&
        empty($passwordErr)
    ) {

        //  first name and last name are converted to lowercase then thier first chars are capitalized, even if words are separated by space(s), new lines, tabs, dashes, return carriages or apostrophes (delimiters provided in arguments of ucwords()). Email is lowercased as well.
        $username = $_POST['username'];

        $fname = $_POST['fname'];
        $fname = ucwords(strtolower($fname), " \t\r\n'-");

        $lname = $_POST['lname'];
        $lname = ucwords(strtolower($lname), " \t\r\n'-");

        $phone = $_POST['phone'];

        $email = $_POST['email'];
        $email = strtolower($email);

        $password = $_POST['password'];

        $query = "INSERT into `users` (username, first_name, last_name, phone_number, email, `password`) VALUES ('" .
            $username . "', '" . $fname . "', '" . $lname . "', '" . $phone . "', '" . $email . "', '" . md5($password) . "')";

        $result = mysqli_query($con, $query);

        $success = "You have registered successfully! <a href='login.php'> Log In here!</a>";
        $fail = "Unsuccessful registration, try again. If issue persists, contact the Admin.";

        $message = $result ? $success : $fail;
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>UsedCars | Register</title>
</head>

<div class="form container text-center">
    <h1>Registration</h1>
    <br>
    <!-- Form -->
    <form name="registration" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST"
        onsubmit="return validateUserRegForm()">
        <!-- User Inputs -->
        <label for="username">Username: </label>
        <input id="username" type="text" name="username" placeholder="Ex: Skipper33"
            value="<?= (isset($username)) ? $username : ''; ?>" />
        <span id="usernameErr" class="error"><?php echo $usernameErr ?></span>
        <br>
        <label for="fname">First Name: </label>
        <input type="text" name="fname" placeholder="Ex: John" value="<?= (isset($fname)) ? $fname : ''; ?>" />
        <span id="fnameErr" class="error"><?php echo $fnameErr ?></span>
        <br>
        <label for="lname">Last Name: </label>
        <input type="text" name="lname" placeholder="Ex: Kimble" value="<?= (isset($lname)) ? $lname : ''; ?>" />
        <span id="lnameErr" class="error"><?php echo $lnameErr ?></span>
        <br>
        <label for="phone">Phone Number: </label>
        <input type="text" name="phone" placeholder="Ex: 5556667777" value="<?= (isset($phone)) ? $phone : ''; ?>" />
        <span id="phoneErr" class="error"><?php echo $phoneErr ?></span>
        <br>
        <label for="email">Email: </label>
        <input type="text" name="email" placeholder="Ex: jkimble@website.ca"
            value="<?= (isset($email)) ? $email : ''; ?>" />
        <span id="emailErr" class="error"><?php echo $emailErr ?></span>
        <br>
        <label for="password">Password: </label>
        <input type="password" name="password" />
        <span id="passwordErr" class="error"><?php echo $passwordErr ?></span>
        <br>
        <input id="userRegSubmitBtn" type="submit" name="submit" value="Register" />
    </form>
    <p><?php echo $message ?></p>

</div>

<!-- Footer -->
<?php include "templates/footer.php" ?>