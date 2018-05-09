<?php

    session_start();
    include("../connections/connection.php");
    
    $email = $_SESSION['7062vle_email'];
    $role = $_SESSION['7062vle_role'];
    $name = $_SESSION['7062vle_name'];
    $id = $_SESSION['7062vle_id'];
    
    if (isset($_POST['mailid'])) {
        $mailid = $_POST['mailid'];
        $updatemessagequery = "UPDATE 7062vle_messages SET ReadMessage = 'yes' WHERE MessageID = '$mailid' AND RecipientID = '$id'";
        $updatemessage = mysqli_query($conn, $updatemessagequery) or die(mysqli_error($conn));
    }
    
    mysqli_close($conn);
    

