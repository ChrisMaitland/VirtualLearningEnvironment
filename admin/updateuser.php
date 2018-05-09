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
    
    if (isset($_POST['deleteuser'])) {
        $uservalue = $_POST['deleteuser'];
        
        $modulechecking = "SELECT Mcreator FROM 7062vle_modules WHERE Mcreator = '$uservalue'";
        $modulecheckingquery = mysqli_query($conn, $modulechecking);
        if (mysqli_num_rows($modulecheckingquery) > 0 ) {
            $updatemcreator = "UPDATE 7062vle_modules SET Mcreator = '$id' WHERE Mcreator = '$uservalue'";
            $updatemcreatorquery = mysqli_query($conn, $updatemcreator);
        }
        
        $deleteuserquery = "DELETE FROM 7062vle_users WHERE UserID = '$uservalue'";
        $deleteuser = mysqli_query($conn, $deleteuserquery) or die(mysqli_error($conn));
        mysqli_close($conn);
        header('Location:users.php?deleted=true');
    } 
    
    if (isset($_POST['update'])) {
    $userid = $_POST['userid'];
    $updatename = $_POST['updatename'];
    $updateemail = $_POST['updateemail'];
    $userrole = $_POST['userrole'];
    }
    
    $updatecheckuserquery = "SELECT * FROM 7062vle_users WHERE UserID = '$userid'";
    $userquery = mysqli_query($conn, $updatecheckuserquery);
    
    if (isset($_POST['update'])) {
        if (mysqli_num_rows($userquery) > 0 ) {
        if (isset($_POST['ResetPic'])) {
            $updateuserquery = "UPDATE 7062vle_users SET Email = '$updateemail', Name = '$updatename', userrole = '$userrole', ProfilePic = 'flagsbg.jpg' WHERE UserID = $userid";
        } else {
            $updateuserquery = "UPDATE 7062vle_users SET Email = '$updateemail', Name = '$updatename', userrole = '$userrole' WHERE UserID = $userid";
        }
        $updateuser = mysqli_query($conn, $updateuserquery) or die(mysqli_error($conn));
        mysqli_close($conn);
        header('Location:users.php?success=true');
        }
    }
    
    mysqli_close($conn);
    

