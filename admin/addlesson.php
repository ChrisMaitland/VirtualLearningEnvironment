<?php
session_start();
include("../connections/connection.php");

$email = $_SESSION['7062vle_email'];
$role = $_SESSION['7062vle_role'];
$name = $_SESSION['7062vle_name'];
$id = $_SESSION['7062vle_id'];

if (!isset($email)) {
    header('Location:logout.php');
} else if ($role == 'student') {
    header('Location:userhub.php');
}

$action = "";
$actionlabel = 'Add Lesson';

if (isset($_GET['module'])) {
    $module = $_GET['module'];
    $action = 'add';
    $actionlabel = 'Add Lesson';
} else {
    $module = "";
}

if (isset($_GET['lesson'])) {
    $editlesson = $_GET['lesson'];
    $action = 'edit';
    $actionlabel = 'Edit Lesson';
} else {
    $editlesson = "";
}

if (isset($_GET['result'])) {
    $result = $_GET['result'];
} else {
    $result = "";
}

if (isset($_POST['selected'])) {
        $selected = $_POST['selected'];
    } else {
        $selected = "";
    }

?>

<!DOCTYPE html>
<head>
    <title><?php echo "$actionlabel" ?></title>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="../design/style.css" />
    <link rel="icon" href="../img/logolarge.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        function displayLessonInput() {
            var currentLesson = document.getElementById('existinglesson').value;
            if (document.getElementById('lessonformat').value == 'text') {
                document.getElementById('lessonInput').innerHTML = "<p class='help-block'>Enter text or use html to provide formatted content.</p>"
                + "<textarea type='hidden' class='form-control' name='lessontext' id='lessontext' placeholder='Enter the Lesson' rows='8' >" + currentLesson + "</textarea>";
            } else if (document.getElementById('lessonformat').value == 'videolink') {
                document.getElementById('lessonInput').innerHTML = "<p class='help-block'>For YouTube links, use 'https://www.youtube.com/embed/' and the final 11 characters of your video.</p>"
                    + "<input type='text' class='form-control' name='lessonvideo' placeholder='https://www.youtube.com/' value='" + currentLesson + "'>";
            } else if (document.getElementById('lessonformat').value == 'videofile') {
                document.getElementById('lessonInput').innerHTML = "<input type='file' name='lessonvideoupload' id='lessonvideoupload' value='" + currentLesson + "'>";
            } else if (document.getElementById('lessonformat').value == 'file') {
                document.getElementById('lessonInput').innerHTML = "<input type='file' name='lessonfileupload' id='lessonfileupload' value='" + currentLesson + "'>";
            } else if (document.getElementById('lessonformat').value == 'audio') {
                document.getElementById('lessonInput').innerHTML = "<input type='file' name='lessonaudioupload' id='lessonaudioupload' value='" + currentLesson + "'>";
            }
        };
        
        window.onload = function()
        {
            displayLessonInput();
        };
    </script>
</head>
<body id='bg'>
    <nav class="navbar navbar-inverse navbar-fixed-top foot ">

        <div class="container foot">

            <div class="navbar-header foot">

                
                <a href="userhub.php" class="navbar-brand navbar-left "><img id='imgbanner' src="../img/logolarge1.png"  class="img-responsive title"></a>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                </button>
            </div>  

            <div id="myNavbar" class="navbar-collapse collapse">
                <div class="navbar-form navbar-right">
                    <form action='admin/signin.php' method='POST'>

<?php
if ($role == 'admin') {
    echo "<a href='users.php'><button type='button' class='btn btn-success' name='users'>Manage Users</button></a>";
}
?>

                        <a href='userhub.php'><button type='button' class="btn btn-success" name='userhub'>User Hub</button></a>

                        <a href='profile.php'><button type='button' class="btn btn-success" name='profile'>Profile</button></a>
                        
                        <a href='messages.php'><button type='button' class="btn btn-success" name='messages'><span class="glyphicon glyphicon-envelope" aria-label="Messages"></span></button></a>

                        <a href='logout.php'><button type='button' class="btn btn-success" name='logout'>Log Out</button></a>
                        
                        <p><div id='pw'><a href="messages.php?toemail=cmaitland02@qub.ac.uk"> Problem? Contact Admin! </a></div></p>

                    </form>

                </div>
            </div><!--/.navbar-collapse -->

        </div>
    </nav>
    <section class="s1">
        <div class="container">
            <div class='row'>

            </div>
        </div>
    </section>
    <div class="container">
        <section class="s2">
            <div class='row'>
                <div class='col-xs-offset-1 col-xs-12 col-sm-6 col-md-8'>
<?php
$error = "";
$currentLname = "";
$currentLdesc = "";
$currentLessonFormat = "";
$currentLesson = "";
if (!($role == 'teacher' || $role == 'admin')) {
    $error = "ERROR - permissions";
} else if ($result == 'added') {
    echo "<p><div class='text-success'><i>Lesson successfully added!</i></div></p>";
} else if ($result == 'edited') {
    echo "<p><div class='text-success'><i>Lesson successfully edited!</i></div></p>";
} else if ($result == 'addError') {
    echo "<p><div id='error'><i>Lesson failed to add!</i></div></p>";
} else if ($result == 'editError') {
    echo "<p><div id='error'><i>Lesson failed to edit!</i></div></p>";
} else if ($result == 'adderrorfilesize') {
    echo "<p><div id='error'><i>Failed to create lesson. Filesize too large!</i></div></p>";
} else if ($result == 'adderrornofile') {
    echo "<p><div id='error'><i>Failed to create lesson as no file was supplied!</i></div></p>";
} else if ($result == 'filesize') {
    echo "<p><div id='error'><i>Lesson details updated successfully, but Lesson content not updated as filesize is too large.</i></div></p>";
} else if ($result == 'nofile') {
    echo "<p><div id='error'><i>Lesson details updated successfully, but Lesson content not updated as no file was supplied.</i></div></p>";
} else {

    if ($action == 'edit') {
        echo "<h3>Edit Lesson</h3><br />";
        echo "<p class='help-block'>You can only edit Lessons in Modules you own.</p>";

        if ($role == 'teacher') {
            $lessonquery = "SELECT * FROM 7062vle_lessons lessons INNER JOIN 7062vle_modules modules ON lessons.ModuleID = modules.moduleid WHERE lessons.LessonID = '$editlesson' AND modules.Mcreator = '$id'";
        } else if ($role == 'admin') {
            $lessonquery = "SELECT * FROM 7062vle_lessons lessons INNER JOIN 7062vle_modules modules ON lessons.ModuleID = modules.moduleid WHERE lessons.LessonID = '$editlesson'";
        }

        $lessonresult = mysqli_query($conn, $lessonquery) or die (mysqli_error($conn));

        if (mysqli_num_rows($lessonresult) == 1) {
            $lessonrow = mysqli_fetch_assoc($lessonresult);

            $currentModuleID = $lessonrow['moduleid'];

            if ($module != "" && $module != $currentModuleID) {
                $error = "ERROR - specified lesson does not exist for specified module or you do not have sufficient permission";
            }
            $module = $currentModuleID;
            $currentLname = $lessonrow['Lname'];
            $currentLdesc = $lessonrow['Ldesc'];
            $currentLessonFormat = $lessonrow['LessonFormat'];
            $currentLesson = $lessonrow['Lesson'];
            $mcreator = $lessonrow['Mcreator'];
        } else {
            $error = "ERROR - specified lesson does not exist or you do not have sufficient permission";
        }

    } else {
        echo "<h3>Add a new Lesson</h3><br />";
        echo "<p class='help-block'>You can only add Lessons in Modules you own.</p>";

        if ($role == 'teacher') {
            $modulequery = "SELECT * FROM 7062vle_modules WHERE moduleid = '$module' AND Mcreator = '$id'";
        } else if ($role == 'admin') {
            $modulequery = "SELECT * FROM 7062vle_modules WHERE moduleid = '$module'";
        }
        
        $moduleresult = mysqli_query($conn, $modulequery) or die (mysqli_error($conn));

        if (mysqli_num_rows($moduleresult) == 1) {
            $modulerow = mysqli_fetch_assoc($moduleresult);
            $mcreator = $modulerow['Mcreator'];
            
        } else {
            $error = "ERROR - specified module does not exist or you do not have sufficient permission";
        }
        
    }
}
if ($error) {
    echo "$error";
} else if ($result == ""){
?>
                    <form action="addlessonupload.php" method="POST" enctype="multipart/form-data" >
                        <div class='form-group'>
                            <input type='hidden' name='lessonid' value="<?php echo $editlesson ?>">
                            <label for='Lname'>Lesson Name:</label>
                            <p><input type="text" class="form-control" name="lessonname" placeholder="New Lesson Name" value="<?php echo $currentLname ?>"></p>
                            <label for='Lname'>In Module:</label>
                            <p><select class='form-control' name='moduleoflesson'>
                                    <?php
                                    if ($role == 'teacher') {
                                        $modulespickquery = "SELECT moduleid, Mname FROM 7062vle_modules WHERE Mcreator = $id";
                                    } else if ($role == 'admin') {
                                        $modulespickquery = "SELECT moduleid, Mname FROM 7062vle_modules WHERE Mcreator = $mcreator";
                                    }
                                    $modulespick = mysqli_query($conn, $modulespickquery) or die (mysqli_error($conn));
                                    
                                    if (mysqli_num_rows($modulespick) > 0) {
                                        if ($action == "add") {
                                            echo "<option selected hidden>Select which Module</option>";
                                        }
                                        while ($row = mysqli_fetch_assoc($modulespick)) {
                                            $modid = $row['moduleid'];
                                            $modname = $row['Mname'];
                                            if ($module == $modid) {
                                                $selectedstring = "selected";
                                            } else {
                                                $selectedstring = "";
                                            }
                                            echo "<option value='$modid' " . $selectedstring . ">" . $modname . "</option>";
                                        }
                                    }
                                    ?>
                                </select></p>

                                <label for='Ldesc'>Lesson Description:</label>
                                <p><textarea class="form-control" name="lessondesc" placeholder="Enter Lesson Description" rows="3"><?php echo $currentLdesc ?></textarea></p>
                                
                            <label for='LessonFormat'>Lesson Format:</label>
                            <textarea type='hidden' id='existinglesson' style='display:none;'><?php echo "$currentLesson"; ?></textarea>
                            <p><select class="form-control" name="lessonformat" id="lessonformat" onchange='displayLessonInput();'>
                                            <option value="text" <?php if ($currentLessonFormat == 'text') { echo 'selected'; } ?> >Text</option>
                                            <option value="videolink" <?php if ($currentLessonFormat == 'videolink') { echo 'selected'; } ?> >Video link</option>
                                            <option value="videofile" <?php if ($currentLessonFormat == 'videofile') { echo 'selected'; } ?> >Video file</option>
                                            <option value="file" <?php if ($currentLessonFormat == 'file') { echo 'selected'; } ?> >File</option>
                                            <option value="audio" <?php if ($currentLessonFormat == 'audio') { echo 'selected'; } ?> >Audio</option>
                                        </select></p>
                                    
                            <label for='lessondetails'>Lesson:</label>
                            
                            <p id='lessonInput'></p>

                            <p><button type="submit" class="btn btn-success" name="submit" value="<?php echo $action ?>"><?php echo "$actionlabel" ?></button></p>

                            
                        </div>
                    </form>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </section>
    </div>

    <div class='container'>
        <div class='row'>
            <div class='col-md-12'>
                <br />
                <br />
            </div>
        </div>
    </div>
</body>
<footer class="navbar-fixed-bottom foot">
    <div class="container">Travel Lingo - Chris Maitland - 2018</div>
</footer>