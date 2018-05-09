<?php

    include('../connections/connection.php');
    
    $regemail = $_POST['Email'];
    $regname = $_POST['Name'];
    $regpw = md5($_POST['pw1']);
    
    $checkemail = "SELECT Email FROM 7062vle_users WHERE Email='$regemail'";
    $checkresult = mysqli_query($conn, $checkemail) or die(mysqli_error($conn));
    
    if (mysqli_num_rows($checkresult) > 0 ) {
        header('Location:register.php?duplicate=true');
    } else {
        $registerquery = "INSERT INTO 7062vle_users (Email, Name, userrole, pw, ProfilePic) VALUES ('$regemail','$regname','student','$regpw','flagsbg.jpg')";
        $register = mysqli_query($conn, $registerquery) or die($conn);
        header('Location:register.php?success=true');
    }
    
