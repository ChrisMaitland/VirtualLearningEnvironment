<?php

    session_start();
    include("../connections/connection.php");
    
    $email = $_SESSION['7062vle_email'];
    $role = $_SESSION['7062vle_role'];
    $name = $_SESSION['7062vle_name'];
    $id = $_SESSION['7062vle_id'];
    
    if (!isset($email)) {
        header("Location:../index.php");
    }
    
    if ($role != 'teacher' && $role != 'admin'){
        $error = "ERROR - you do not have sufficient permission.";
        header("Location:learnerhub.php?error=$error");
    }
    
    $lesson = "";
    $module = "";
    
    if (isset($_GET['lesson'])) {
        $lesson = $_GET['lesson'];
    }
    
    if (isset ($_GET['module'])) {
        $module = $_GET['module'];     
    } 
    
    if (!$lesson && !$module) {
        $error = "ERROR - insufficient details.";
        header("Location:learnerhub.php?moduleid=$moduleid&error=$error");
    }
    
    if ($lesson) {

        if ($role == 'teacher') {
            $lessonquery = "SELECT * FROM 7062vle_lessons lessons INNER JOIN 7062vle_modules modules ON lessons.ModuleID = modules.moduleid WHERE lessons.LessonID = '$lesson' AND modules.Mcreator = '$id'";
        } else if ($role == 'admin') {
            $lessonquery = "SELECT * FROM 7062vle_lessons lessons INNER JOIN 7062vle_modules modules ON lessons.ModuleID = modules.moduleid WHERE lessons.LessonID = '$lesson'";
        }

        $lessonresult = mysqli_query($conn, $lessonquery) or die (mysqli_error($conn));

        if (mysqli_num_rows($lessonresult) == 1) {
            $lessonrow = mysqli_fetch_assoc($lessonresult);
            $deletelesson = "DELETE FROM 7062vle_lessons WHERE LessonID = $lesson";
            $deletelessonresult = mysqli_query($conn, $deletelesson) or die (mysqli_error($conn));
            header("Location:learnerhub.php?moduleid=$module&lessondeleted=true");
        } else {
            $error = "ERROR - specified lesson does not exist or you do not have sufficient permission";
            header("Location:learnerhub.php?moduleid=$module&error=$error");
        }

    } else if ($module) {
        
        if ($role == 'teacher') {
            $modulequery = "SELECT * FROM 7062vle_modules WHERE moduleid = '$module' AND Mcreator = '$id'";
        } else if ($role == 'admin') {
            $modulequery = "SELECT * FROM 7062vle_modules WHERE moduleid = '$module'";
        }
        
        $moduleresult = mysqli_query($conn, $modulequery) or die (mysqli_error($conn));

        if (mysqli_num_rows($moduleresult) == 1) {
            $modulerow = mysqli_fetch_assoc($moduleresult);
            $deletemodulelessons = "DELETE FROM 7062vle_lessons WHERE ModuleID = $module";
            $deletemodulelessonsresult = mysqli_query($conn, $deletemodulelessons) or die (mysqli_error($conn));
            $deletemoduleenrols = "DELETE FROM 7062vle_enrolement WHERE ModuleID = $module";
            $deletemoduleenrolsresult = mysqli_query($conn, $deletemoduleenrols) or die (mysqli_error($conn));
            $deletemodule = "DELETE FROM 7062vle_modules WHERE moduleid = $module";
            $deletemoduleresult = mysqli_query($conn, $deletemodule) or die (mysqli_error($conn));
            header("Location:userhub.php?moduledeleted=true");
        } else {
            $error = "ERROR - specified module does not exist or you do not have sufficient permission";
            header("Location:learnerhub.php?moduleid=$module&error=$error");
        }
        
    }

    mysqli_close($conn);
    
?>