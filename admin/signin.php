<?php
session_start();
include('../connections/connection.php');

$email = $_POST["Email"];
$pw = md5($_POST["pw"]);

$checkuser="SELECT * FROM 7062vle_users WHERE Email='$email' AND pw='$pw'";
$logincheck=mysqli_query($conn,$checkuser) or die(mysqli_error($conn));

if (mysqli_num_rows($logincheck) > 0) {
    while ($row = mysqli_fetch_assoc($logincheck)) {
        $_SESSION['7062vle_email'] = $row['Email'];
        $_SESSION['7062vle_role'] = $row['userrole'];
        $_SESSION['7062vle_name'] = $row['Name'];
        $_SESSION['7062vle_id'] = $row['UserID'];
        echo "<p>Logged in</p>";
        header("Location:userhub.php");
    }
} else {
    header("Location:../index.php?loginerror=true");
    echo "<p>Email or password is incorrect</p>";
}
mysqli_close($conn);

