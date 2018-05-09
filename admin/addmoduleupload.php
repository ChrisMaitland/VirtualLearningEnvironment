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
    
    if (isset($_POST['submitaction'])) {
        $action = $_POST['submitaction'];
    } else {
        header('Location:addmodule.php?success=false');
    }
    
    if (isset($_POST['editmoduleid'])) {
        $moduleid = $_POST['editmoduleid'];
    } else {
        $moduleid = "";
    }
    
    $newmname = $_POST['newname'];
    $newmlang = $_POST['lang'];
    $newmdesc = mysqli_real_escape_string($conn, $_POST['desc']);
    if (isset($_POST['Autoapprove'])) {
        $newmapp = 1;
    } else {
        $newmapp = 0;
    }
    $mfilename = $_FILES['mpicupload']['name'];
    $mfiletemp = $_FILES['mpicupload']['tmp_name'];
    $errorflag = 0;
    
    function myRandom($mfilename){
    $mnewname = rand(1000,10000) . $mfilename;
    return $mnewname;
    }
    
    if ($_FILES['mpicupload']['size'] > 2097152) {
        // Filesize is too large
        $errorflag = 1;
    } else if ($_FILES['mpicupload']['size'] == 0) {
        $errorflag = 2;
    } else {
        $errorflag = 0;
    }
    
    if ($action == 'addnewmodule'){
        $checkname = "SELECT Mname FROM 7062vle_modules WHERE Mname='$newmname'";
        $checkresult = mysqli_query($conn, $checkname) or die(mysqli_error($conn));
        if (mysqli_num_rows($checkresult) > 0) {
            header('Location:addmodule.php?duplicatename=true');
        } else if ($errorflag == 1) {
            header('Location:addmodule.php?filesize=true');
        } else if ($errorflag == 2) {
            $mfilename = 'logo.png';
            move_uploaded_file($mfiletemp, "../img/$mfilename");
            $newmodulequery = "INSERT INTO 7062vle_modules (Mname, Language, MDesc, Mcreator, Mpic, Autoapprove) VALUES ('$newmname','$newmlang','$newmdesc','$id','$mfilename','$newmapp')";
            $addnewmodule = mysqli_query($conn, $newmodulequery) or die(mysqli_error($conn));
            header('Location:addmodule.php?success=true');
        } else {
            move_uploaded_file($mfiletemp, "../img/$mfilename");
            $newmodulequery = "INSERT INTO 7062vle_modules (Mname, Language, MDesc, Mcreator, Mpic, Autoapprove) VALUES ('$newmname','$newmlang','$newmdesc','$id','$mfilename','$newmapp')";
            $addnewmodule = mysqli_query($conn, $newmodulequery) or die(mysqli_error($conn));
            header('Location:addmodule.php?success=true');
        }
    } else if ($action == 'editmodule') {
        if ($errorflag == 1) {
            header("Location:addmodule.php?module=$moduleid&filesize=true");
        } else if ($errorflag == 2) {
            $editmodulequery = "UPDATE 7062vle_modules SET Mname='$newmname', Language='$newmlang', MDesc='$newmdesc', Autoapprove='$newmapp' WHERE moduleid = '$moduleid'";
            $editmodule = mysqli_query($conn, $editmodulequery) or die(mysqli_error($conn));
            header("Location:addmodule.php?module=$moduleid&success=true");
        } else {
            move_uploaded_file($mfiletemp, "../img/$mfilename");
            $editmodulequery = "UPDATE 7062vle_modules SET Mname='$newmname', Language='$newmlang', MDesc='$newmdesc', Mpic='$mfilename', Autoapprove='$newmapp' WHERE moduleid = '$moduleid'";
            $editmodule = mysqli_query($conn, $editmodulequery) or die(mysqli_error($conn));
            header("Location:addmodule.php?module=$moduleid&success=true");
        }
    }
    mysqli_close($conn);
?>
