<?php

    include('connections/connection.php');
    
    $email = $_POST['Email'];
    
    $checkemail = "SELECT Email FROM 7062vle_users WHERE Email='$email'";
    $checkresult = mysqli_query($conn, $checkemail) or die($conn);
    
    if (mysqli_num_rows($checkresult) == 0 ) {
        header('Location:forgotten.php?exists=false');
    } else {
       //send reset email
        //generate this randomly
        $resetKey = md5(uniqid(rand(), true));
        echo "$resetKey";
        $link = "http://cmaitland02.web.eeecs.qub.ac.uk/cater/resetPassword.php?email=$email&reset=$resetKey";
        //save to DB table with Email, random and timestamp
        echo "\n$link";
        $querypost = "INSERT INTO 7062vle_resetpw (Email, ResetKey) VALUES ('$email','$resetKey')";
        $runquery = mysqli_query($conn, $querypost);
        //send email   
        
        // the message
        $headers = 'From: <webmaster@travellingo.com>' ."\r\n";
        $headers .= "Content-type-type: text/html";
        $subject = "Travel Lingo - Password Reset Request";
        
        $msg = "Dear User,\n\nPlease click the following link to reset your password:\n\n" .$link."\n\nThanks from the Travel Lingo team.";

        // use wordwrap() if lines are longer than 70 characters
        $msg = wordwrap($msg,70);

        // send email
        mail($email,$subject,$msg,$headers);
        header("Location:index.php");
        
    }
    mysqli_close($conn);
?>