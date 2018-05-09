<?php

    session_start();
    include("../connections/connection.php");
    
    $email = $_SESSION['7062vle_email'];
    $role = $_SESSION['7062vle_role'];
    $name = $_SESSION['7062vle_name'];
    $id = $_SESSION['7062vle_id'];
    
    if (!isset($email)) {
        header('Location:logout.php');
    }
    
    if (isset($_POST['mailmoduleid'])) {
        
        $mailmoduleid = $_POST['mailmoduleid'];
        // select query from enrolment
        $enrolledusersquery = "SELECT UserID FROM 7062vle_enrolement WHERE ModuleID = '$mailmoduleid'";
        echo $enrolledusersquery;
        $enrolledusers = mysqli_query($conn, $enrolledusersquery) or die(mysqli_error($conn));
        
        $subject = $_POST['subject'];
        $compose = mysqli_real_escape_string($conn, $_POST['compose']);
        
        if (mysqli_num_rows($enrolledusers) > 0) {
            // for each, insert as per below, but where $recipientid is from above query
            while ($row = mysqli_fetch_assoc($enrolledusers)) {
                $recipientid = $row['UserID'];
                $sendingmailquery = "INSERT INTO 7062vle_messages (SenderID, RecipientID, Title, Message, ReadMessage) VALUES ('$id','$recipientid','$subject','$compose','no')";
                $sendingmail = mysqli_query($conn, $sendingmailquery) or die(mysqli_error($conn));
            }
            header("Location:messages.php?sent=true");
        } else {
            header("Location:messages.php?sent=false");
        }
        
    } else if (isset($_POST['sendmail'])) {
        $emailaddress = $_POST['Email'];
        $subject = $_POST['subject'];
        $compose = mysqli_real_escape_string($conn, $_POST['compose']);
        

        $emailcheckquery = "SELECT UserID, Email FROM 7062vle_users WHERE Email='$emailaddress'";
        $emailcheck = mysqli_query($conn, $emailcheckquery) or die(mysqli_error($conn));
        if (mysqli_num_rows($emailcheck) == 1) {
            $row = mysqli_fetch_assoc($emailcheck);
            $recipientid = $row['UserID'];
            $sendingmailquery = "INSERT INTO 7062vle_messages (SenderID, RecipientID, Title, Message, ReadMessage) VALUES ('$id','$recipientid','$subject','$compose','no')";
            $sendingmail = mysqli_query($conn, $sendingmailquery) or die(mysqli_error($conn));
            header("Location:messages.php?sent=true");
        } else {
            header("Location:messages.php?sent=false");
        }
    }

    
    mysqli_close($conn);
    

