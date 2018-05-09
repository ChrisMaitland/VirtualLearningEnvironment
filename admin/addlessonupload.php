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
    $action = $_POST['submit'];
    $lessonid = $_POST['lessonid'];
    $lessonname = mysqli_real_escape_string($conn, $_POST['lessonname']);
    $lessondesc = mysqli_real_escape_string($conn, $_POST['lessondesc']);
    $lessonmodule = $_POST['moduleoflesson'];
    $lessonformat = $_POST['lessonformat'];
    $lessontext = mysqli_real_escape_string($conn, $_POST['lessontext']);
    $lessonvideo = $_POST['lessonvideo'];
    $lvideofilename = $_FILES['lessonvideoupload']['name'];
    $lvideofiletemp = $_FILES['lessonvideoupload']['tmp_name'];
    $videofileerrorflag = 0;
    $lfilename = $_FILES['lessonfileupload']['name'];
    $lfiletemp = $_FILES['lessonfileupload']['tmp_name'];
    $fileerrorflag = 0;
    $laudioname = $_FILES['lessonaudioupload']['name'];
    $laudiotemp = $_FILES['lessonaudioupload']['tmp_name'];
    $audioerrorflag = 0;    

    echo $lvideofilename;
	echo $lvideofiletemp;
	echo $_FILES['lessonvideoupload']['size'];
    
    function myRandom($filename){
        $newfilename = rand(1000,10000) . $filename;
        return $newfilename;
        }
    
if ($action == 'add') {
    
    if ($lessonformat == 'file') {
        
        if ($_FILES['lessonfileupload']['size'] > 12097152) {
            $fileerrorflag = 1;
        } else if ($_FILES['lessonfileupload']['size'] == 0) {
            $fileerrorflag = 2;
        } else {
            $fileerrorflag = 0;
        }
    
        if ($fileerrorflag == 1) {
            header('Location:addlesson.php?result=adderrorfilesize');
        } else if ($fileerrorflag == 2) {
            header('Location:addlesson.php?result=adderrornofile');
        } else {
            $lfilename = myRandom($lfilename);
            move_uploaded_file($lfiletemp, "../lessons/$lfilename");
            $newlessonquery = "INSERT INTO 7062vle_lessons (ModuleID, Lname, Ldesc, LessonFormat, Lesson) VALUES ('$lessonmodule','$lessonname','$lessondesc','$lessonformat','$lfilename')";
            $addnewlesson = mysqli_query($conn, $newlessonquery) or die(mysqli_error($conn));
            header('Location:addlesson.php?result=added');
        }
    } else if ($lessonformat == 'audio') {
        
        if ($_FILES['lessonaudioupload']['size'] > 12097152) {
            $audioerrorflag = 1;
        } else if ($_FILES['lessonaudioupload']['size'] == 0) {
            $audioerrorflag = 2;
        } else {
            $audioerrorflag = 0;
        }
        
        if ($audioerrorflag == 1) {
            header('Location:addlesson.php?result=adderrorfilesize');
        } else if ($audioerrorflag == 2) {
            header('Location:addlesson.php?result=adderrornofile');
        } else {
            $laudioname = myRandom($laudioname);
            move_uploaded_file($laudiotemp, "../lessons/$laudioname");
            $newlessonquery = "INSERT INTO 7062vle_lessons (ModuleID, Lname, Ldesc, LessonFormat, Lesson) VALUES ('$lessonmodule','$lessonname','$lessondesc','$lessonformat','$laudioname')";
            $addnewlesson = mysqli_query($conn, $newlessonquery) or die(mysqli_error($conn));
            header('Location:addlesson.php?result=added');
        }
    } else if ($lessonformat == 'videofile') {
        
        if ($_FILES['lessonvideoupload']['size'] > 12097152) {
            $videofileerrorflag = 1;
        } else if ($_FILES['lessonvideoupload']['size'] == 0) {
            $videofileerrorflag = 2;
        } else {
            $videofileerrorflag = 0;
        }
        
        if ($videofileerrorflag == 1) {
            header('Location:addlesson.php?result=adderrorfilesize');
        } else if ($videofileerrorflag == 2) {
            header('Location:addlesson.php?result=adderrornofile');
        } else {
            $lvideofilename = myRandom($lvideofilename);
            move_uploaded_file($lvideofiletemp, "../lessons/$lvideofilename");
            $newlessonquery = "INSERT INTO 7062vle_lessons (ModuleID, Lname, Ldesc, LessonFormat, Lesson) VALUES ('$lessonmodule','$lessonname','$lessondesc','$lessonformat','$lvideofilename')";
            $addnewlesson = mysqli_query($conn, $newlessonquery) or die(mysqli_error($conn));
            header('Location:addlesson.php?result=added');
        } 
    } else if ($lessonformat == 'text') {
        $newlessonquery = "INSERT INTO 7062vle_lessons (ModuleID, Lname, Ldesc, LessonFormat, Lesson) VALUES ('$lessonmodule','$lessonname','$lessondesc','$lessonformat','$lessontext')";
        $addnewlesson = mysqli_query($conn, $newlessonquery) or die(mysqli_error($conn));
        header('Location:addlesson.php?result=added');
    } else if ($lessonformat == 'videolink') {
        $newlessonquery = "INSERT INTO 7062vle_lessons (ModuleID, Lname, Ldesc, LessonFormat, Lesson) VALUES ('$lessonmodule','$lessonname','$lessondesc','$lessonformat','$lessonvideo')";
        $addnewlesson = mysqli_query($conn, $newlessonquery) or die(mysqli_error($conn));
        header('Location:addlesson.php?result=added');
    }
    
}

if ($action == 'edit') {
    if ($lessonformat == 'file') {
        
        if ($_FILES['lessonfileupload']['size'] > 12097152) {
            $fileerrorflag = 1;
        } else if ($_FILES['lessonfileupload']['size'] == 0) {
            $fileerrorflag = 2;
        } else {
            $fileerrorflag = 0;
        }
    
        if ($fileerrorflag == 1) {
            header('Location:addlesson.php?filesize=true');
        } else if ($fileerrorflag == 2) {
            $newlessonquery = "UPDATE 7062vle_lessons SET ModuleID='$lessonmodule', Lname='$lessonname', Ldesc='$lessondesc', LessonFormat='$lessonformat' WHERE LessonID = $lessonid";
            $addnewlesson = mysqli_query($conn, $newlessonquery) or die(mysqli_error($conn));
            header('Location:addlesson.php?result=nofile');
        } else {
            $lfilename = myRandom($lfilename);
            move_uploaded_file($lfiletemp, "../lessons/$lfilename");
            $newlessonquery = "UPDATE 7062vle_lessons SET ModuleID='$lessonmodule', Lname='$lessonname', Ldesc='$lessondesc', LessonFormat='$lessonformat', Lesson='$lfilename' WHERE LessonID = $lessonid";
            $addnewlesson = mysqli_query($conn, $newlessonquery) or die(mysqli_error($conn));
            echo "$lfilename $lfiletemp $newlessonquery $addnewlesson";
            header('Location:addlesson.php?result=edited');
        }
    } else if ($lessonformat == 'audio') {
        
        if ($_FILES['lessonaudioupload']['size'] > 12097152) {
            $audioerrorflag = 1;
        } else if ($_FILES['lessonaudioupload']['size'] == 0) {
            $audioerrorflag = 2;
        } else {
            $audioerrorflag = 0;
        }
        
        if ($audioerrorflag == 1) {
            header('Location:addlesson.php?filesize=true');
        } else if ($audioerrorflag == 2) {
            $newlessonquery = "UPDATE 7062vle_lessons SET ModuleID='$lessonmodule', Lname='$lessonname', Ldesc='$lessondesc', LessonFormat='$lessonformat' WHERE LessonID = $lessonid";
            $addnewlesson = mysqli_query($conn, $newlessonquery) or die(mysqli_error($conn));
            header('Location:addlesson.php?result=nofile');
        } else {
            $laudioname = myRandom($laudioname);
            move_uploaded_file($laudiotemp, "../lessons/$laudioname");
            $newlessonquery = "UPDATE 7062vle_lessons SET ModuleID='$lessonmodule', Lname='$lessonname', Ldesc='$lessondesc', LessonFormat='$lessonformat', Lesson='$laudioname' WHERE LessonID = $lessonid";
            $addnewlesson = mysqli_query($conn, $newlessonquery) or die(mysqli_error($conn));
            header('Location:addlesson.php?result=edited');
        }
    }  else if ($lessonformat == 'videofile') {
        
        if ($_FILES['lessonvideoupload']['size'] > 12097152) {
            $videofileerrorflag = 1;
        } else if ($_FILES['lessonvideoupload']['size'] == 0) {
            $videofileerrorflag = 2;
        } else {
            $videofileerrorflag = 0;
        }
        
        if ($videofileerrorflag == 1) {
            //header('Location:addlesson.php?result=adderrorfilesize');
        } else if ($videofileerrorflag == 2) {
            //header('Location:addlesson.php?result=adderrornofile');
        } else {
            $lvideofilename = myRandom($lvideofilename);
            move_uploaded_file($lvideofiletemp, "../lessons/$lvideofilename");
            $newlessonquery = "UPDATE 7062vle_lessons SET ModuleID='$lessonmodule', Lname='$lessonname', Ldesc='$lessondesc', LessonFormat='$lessonformat', Lesson='$lvideofilename' WHERE LessonID = $lessonid";
            $addnewlesson = mysqli_query($conn, $newlessonquery) or die(mysqli_error($conn));
            //header('Location:addlesson.php?result=added');
        }
    } else if ($lessonformat == 'text') {
        $newlessonquery = "UPDATE 7062vle_lessons SET ModuleID='$lessonmodule', Lname='$lessonname', Ldesc='$lessondesc', LessonFormat='$lessonformat', Lesson='$lessontext' WHERE LessonID = $lessonid";
        $addnewlesson = mysqli_query($conn, $newlessonquery) or die(mysqli_error($conn));
        header('Location:addlesson.php?result=edited');
    } else if ($lessonformat == 'videolink') {
        $newlessonquery = "UPDATE 7062vle_lessons SET ModuleID='$lessonmodule', Lname='$lessonname', Ldesc='$lessondesc', LessonFormat='$lessonformat', Lesson='$lessonvideo' WHERE LessonID = $lessonid";
        $addnewlesson = mysqli_query($conn, $newlessonquery) or die(mysqli_error($conn));
        header('Location:addlesson.php?result=edited');
    }
}
    
    mysqli_close($conn);
    
?>