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
        
    $filename = $_FILES['picupload']['name'];
    $filetemp = $_FILES['picupload']['tmp_name'];
    $errorflag = 1;
    
    function myRandom($filename){
    $newname = rand(1000,10000) . $filename;
    return $newname;
    }
    
    if ($_FILES['picupload']['size'] > 2097152) {
        echo "Sorry, your file is over 2MB";
        $errorflag = 1;
    }
    
    if (empty($filename)) {
        $errorflag = 1;
    } else {
        $errorflag = 0;
    }
    
    
    if ($errorflag == 1) {
        echo "no image file";
        header('Location:profile.php?nofile=true');
    } else {
        $filename = myRandom($filename);
        move_uploaded_file($filetemp, "../img/$filename");
        $insertquery = "UPDATE 7062vle_users SET ProfilePic = '$filename' WHERE UserID = '$id'";
        $result = mysqli_query($conn, $insertquery) or die (mysqli_error($conn));
        header('Location:profile.php?upload=true');
    } 
    
    mysqli_close($conn);
?>