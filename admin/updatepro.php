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
    
    if(isset($_POST['detailssubmit']))
    { 
        $updatename = mysqli_real_escape_string($conn, $_POST['newname']);
        $updateemail = mysqli_real_escape_string($conn, $_POST['newemail']);
        if ($role == 'teacher' || $role == 'admin') {
            $updatesubject = mysqli_real_escape_string($conn, $_POST['newsub']);
            $updatesubjectlevel = mysqli_real_escape_string($conn, $_POST['newsublvl']);
            if (isset($_POST['HideName'])) { $hidename = 1; } else { $hidename = 0; }
            if (isset($_POST['HideSub'])) { $hidesub = 1; } else { $hidesub = 0; }
            if (isset($_POST['HideSublvl'])) { $hidesublvl = 1; } else {$hidesublvl = 0; } 
            
            $selectquery = "SELECT * FROM 7062vle_users WHERE UserID = '$id'";
            $selectresult = mysqli_query($conn, $selectquery) or die (mysqli_error($conn));
            
            if (mysqli_num_rows($selectresult) == 1) {
                
                $row = mysqli_fetch_assoc($selectresult);
                
                $existingShowName = $row['ShowName'];
                $existingShowSub = $row['ShowSub'];
                $existingShowSubLvl = $row['ShowSubLvl'];
                
                if ($existingShowName != $hidename || $existingShowSub != $hidesub || $existingShowSubLvl != $hidesublvl) {
                    $showSettingsChanged = 1;
                } else {
                    $showSettingsChanged = 0;
                }
            } else {
                $showSettingsChanged = 0;
            }
            
            $updatehidequery = "UPDATE 7062vle_users SET ShowName = '$hidename', ShowSub = '$hidesub', ShowSubLvl = '$hidesublvl' WHERE UserID = '$id'"; 
            $updatehide = mysqli_query($conn, $updatehidequery) or die (mysqli_error($conn));
            
        } else {
            $showSettingsChanged == 0;
        }
        
        $selectquery = "SELECT * FROM 7062vle_users WHERE UserID = '$id'";
        $selectresult = mysqli_query($conn, $selectquery) or die (mysqli_error($conn));
        if (mysqli_num_rows($selectresult) == 1) {
            $row = mysqli_fetch_assoc($selectresult);
            
            if (empty($updatename)) {
                $errorflagname = 1;
                $setname = $row['Name'];
            } else {
                $errorflagname = 0;
                $setname = $updatename;
            }

            if (empty($updateemail)) {
                $errorflagemail = 1;
                $setemail = $row['Email'];
            } else {
                $errorflagemail = 0;
                $setemail = $updateemail;
            }

            if (empty($updatesubject)) {
                $errorflagsub = 1;
                $setsubject = $row['Subject'];
            } else {
                $errorflagsub = 0;
                $setsubject = $updatesubject;
            }

            if (empty($updatesubjectlevel)) {
                $errorflagsublvl = 1;
                $setsubjectlevel = $row['SubjectLvl'];
            } else {
                $errorflagsublvl = 0;
                $setsubjectlevel = $updatesubjectlevel;
            }
            
            if ($errorflagname == 1 && $errorflagemail == 1 && $errorflagsub == 1 && $errorflagsublvl == 1) {
                if ($showSettingsChanged == 1) {
                    header('Location:profile.php?updatedshow=true');
                } else {
                    header('Location:profile.php?updatenothing=true');
                }  
            } else {
                $updatequery = "UPDATE 7062vle_users SET Name = '$setname', Email = '$setemail', Subject = '$setsubject', SubjectLvl = '$setsubjectlevel' WHERE UserID = '$id'";
                $result = mysqli_query($conn, $updatequery) or die (mysqli_error($conn));
                header('Location:profile.php?updateall=true');
            } 
        }
    } else if (isset($_POST['submitrolereq'])) {
        
        if (isset($_POST['requestedrole'])) {
            $requestedrole = $_POST['requestedrole'];
            $updatequery = "UPDATE 7062vle_users SET RequestedRole = '$requestedrole' WHERE UserID = '$id'";
        } else if (isset($_POST['requestedadmin'])) {
            $updatequery = "UPDATE 7062vle_users SET RequestedRole = 'admin' WHERE UserID = '$id'";
        }
        
        $update = mysqli_query($conn, $updatequery) or die($conn);
        header('Location:profile.php?updaterole=true');
        
    } else if(isset($_POST['pwsubmit']))
    {  
        $pw = md5($_POST['pw1']);
        //if valid update user table to new password
        $updatequery = "UPDATE 7062vle_users SET pw = '$pw' WHERE UserID = '$id'";
        $update = mysqli_query($conn, $updatequery) or die($conn);
        header('Location:profile.php?updatepw=true');
    }
    mysqli_close($conn);
?>