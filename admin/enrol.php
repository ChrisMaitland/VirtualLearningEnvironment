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
    
    if (isset($_GET['module'])) {
        $moduleid = $_GET['module'];
    } else {
        $moduleid = "";
    }
    
    if (isset($_POST['choice'])) {
        $choice = $_POST['choice'];
    } else {
        $choice = "";
    }
    
    if (isset($_POST['enrolid'])) {
        $enrolid = $_POST['enrolid'];
    } else {
        $enrolid = "";
    }    

    if ($moduleid) {
        $modulelookup = "SELECT moduleid, Autoapprove FROM 7062vle_modules WHERE ModuleID = $moduleid";
        $runmodulelookup = mysqli_query($conn, $modulelookup) or die(mysqli_error($conn));
        if (mysqli_num_rows($runmodulelookup) > 0 ){
            $row = mysqli_fetch_assoc($runmodulelookup);
            $autoapprove = $row['Autoapprove'];
            $insertenrolement = "INSERT INTO 7062vle_enrolement (UserID, ModuleID, Approved) VALUES ('$id', '$moduleid', '$autoapprove')";
            $runinsertenrolement = mysqli_query($conn, $insertenrolement) or die(mysqli_error($conn));
            header("Location:userhub.php");
        }
    } else if ($choice && $enrolid) {
        if ($choice == 'approve') {
            $approvalquery = "UPDATE 7062vle_enrolement SET Approved = '1' WHERE EnrolID = $enrolid";
            $runapprovalquery = mysqli_query($conn, $approvalquery) or die(mysqli_error($conn));
            header("Location:userhub.php?approve=approvemodule");
        } else if ($choice == 'reject') {
            $rejectquery = "DELETE FROM 7062vle_enrolement WHERE EnrolID = $enrolid";
            $runrejectquery = mysqli_query($conn, $rejectquery) or die(mysqli_error($conn));
            header("Location:userhub.php?approve=rejectmodule");
        }
        
    } else {
        header("Location:userhub.php");
    }
    
    mysqli_close($conn);
    
?>