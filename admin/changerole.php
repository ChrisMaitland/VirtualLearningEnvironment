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
    
    if (isset($_POST['userid'])) {
        $userid = $_POST['userid'];
    } else {
        $userid = "";
    }
    
    if (isset($_POST['requestedrole'])) {
        $requestedrole = $_POST['requestedrole'];
    } else {
        $requestedrole = "";
    }
    
    if (isset($_POST['rolechoice'])) {
        $rolechoice = $_POST['rolechoice'];
    } else {
        $rolechoice = "";
    }

    if ($rolechoice && $userid && $requestedrole) {
        if ($rolechoice == 'approve') {
            $approvalquery = "UPDATE 7062vle_users SET userrole = '$requestedrole', RequestedRole = '' WHERE UserID = '$userid'";
            $runapprovalquery = mysqli_query($conn, $approvalquery) or die(mysqli_error($conn));
            $message = "Hi, 
                Your request for $requestedrole access has been approved.Regards, 
                    Admin.";
            $sendapprovedq = "INSERT INTO 7062vle_messages (SenderID, RecipientID, Title, Message, ReadMessage) VALUES ('$id', '$userid', '$requestedrole Access Approved', '$message', 'no')";
            $sendapproved = mysqli_query($conn, $sendapprovedq) or die(mysqli_error($conn));
            header("Location:userhub.php?approve=approverole");
        } else if ($rolechoice == 'reject') {
            $rejectquery = "UPDATE 7062vle_users SET RequestedRole = '' WHERE UserID = '$userid'";
            $runrejectquery = mysqli_query($conn, $rejectquery) or die(mysqli_error($conn));
            $message = "Hi, 
                Unfortunately your request for $requestedrole access has been rejected.
                    Regards, Admin.";
            $sendrejectionq = "INSERT INTO 7062vle_messages (SenderID, RecipientID, Title, Message, ReadMessage) VALUES ('$id', '$userid', '$requestedrole Access Rejected', '$message', 'no')";
            $sendrejection = mysqli_query($conn, $sendrejectionq) or die(mysqli_error($conn));
            header("Location:userhub.php?approve=rejectrole");
        }
        
    } else {
        header("Location:userhub.php");
    }
    
    mysqli_close($conn);
    
?>