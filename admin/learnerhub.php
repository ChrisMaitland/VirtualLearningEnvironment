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
    
    if (isset($_GET['moduleid'])) {
        $moduleid = $_GET['moduleid'];
    } else {
        header('Location:userhub.php');
    }
    
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
    } else {
        $error = "";
    }
    
    if (isset($_GET['lessondeleted'])) {
        $lessondeleted = $_GET['lessondeleted'];
    } else {
        $lessondeleted = "";
    }
    
?>

<!DOCTYPE html>
<head>
    <title>Learning Hub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="../design/style.css" />
    <link rel="icon" href="../img/logolarge.png">
    <link href='https://fonts.googleapis.com/css?family=Chelsea Market' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.debug.js" integrity="sha384-CchuzHs077vGtfhGYl9Qtc7Vx64rXBXdIAZIPbItbNyWIRTdG0oYAqki3Ry13Yzu" crossorigin="anonymous"></script>
    <script>

        function download() {
            
            var doc = new jsPDF();

            var fontSize = 11;

            doc.setFontSize(fontSize);
            var content = htmlDecode(document.getElementById("lessontext").innerHTML);
            var pageWidth = doc.internal.pageSize.width;
            var pageHeight = doc.internal.pageSize.height;
            var splitContent = doc.splitTextToSize(content, pageWidth - 40);
            
            var y = 30;
            for (var i = 0; i < splitContent.length; i++) {
                if (y > pageHeight - 30) {
                    doc.addPage();
                    y = 30;
                }
                doc.text(20, y, splitContent[i]);
                y = y + fontSize;
            }
            
            doc.save('test.pdf');
            
        };
        
        function htmlDecode(input){
            var e = document.createElement('div');
            e.innerHTML = input;
            return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
        };

    </script>
</head>
<body id='bg'>

    <nav class="navbar navbar-inverse navbar-fixed-top foot ">

        <div class="container foot">

            <div class="navbar-header">


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
            </div>

        </div>
    </nav><!--/.navbar-collapse -->
    <section class="s1">
        <div class="container">
            <div class='row'>
                <?php 
                    if ($role == 'admin') {
                        $initialmodulequery = "SELECT * FROM 7062vle_modules WHERE moduleid = '$moduleid'";
                    } else if ($role == 'teacher') {
                        $initialmodulequery = "SELECT * FROM 7062vle_modules WHERE moduleid = '$moduleid' AND Mcreator = '$id'";
                    } else if ($role == 'student') {
                        $initialmodulequery = "SELECT modules.* FROM 7062vle_modules modules INNER JOIN 7062vle_enrolement enrol ON modules.moduleid = enrol.ModuleID WHERE modules.moduleid = '$moduleid' AND enrol.Approved = 1";
                    } else {
                        header("Location:userhub.php");
                    }
                    $initialmodule = mysqli_query($conn, $initialmodulequery) or die(mysqli_error($conn));

                    if (mysqli_num_rows($initialmodule) > 0 ) {
                        $row = mysqli_fetch_assoc($initialmodule);
                            $mname = $row['Mname'];
                            $lang = $row['Language'];
                            $Mcrea = $row['Mcreator'];
                            $mdesc = $row['MDesc'];
                            $mpic = $row['Mpic'];

                    $fetchlessonquery = "SELECT * FROM 7062vle_lessons lessons INNER JOIN 7062vle_modules modules ON modules.moduleid = lessons.ModuleID WHERE lessons.ModuleID = '$moduleid'";
                    $fetchlesson = mysqli_query($conn, $fetchlessonquery) or die(mysqli_error($conn));

                ?>
                <img src="../img/lngs.png" alt="quote" class="img-responsive" style="object-fit: cover; display: block; width: 60%; min-height: 100%; margin-left: auto; margin-right: auto;">
                        <div class="col-md-2 col-sm-1 col-xs-1 sidenav img-responsive">

                            <?php 

                            if (mysqli_num_rows($fetchlesson) > 0 ) {
                                if (isset($_GET['lesson'])) {
                                    $selectedlesson = $_GET['lesson'];
                                } else {
                                    $selectedlesson = "";
                                }
                                echo "<ol class='ol'>";
                                while ($row2 = mysqli_fetch_assoc($fetchlesson)) {
                                    $lessonid = $row2['LessonID'];
                                    $Lname = $row2['Lname'];
                                    $Ldesc = $row2['Ldesc'];
                                    $Lform = $row2['LessonFormat'];
                                    $Lesson = $row2['Lesson'];
                                    echo "<li><a href='learnerhub.php?moduleid=$moduleid&lesson=$lessonid'>$Lname</a></li>";
                                    if ($selectedlesson == $lessonid) {
                                        $selectedLname = $Lname;
                                        $selectedLdesc = $Ldesc;
                                        $selectedLform = $Lform;
                                        $selectedLesson = $Lesson;
                                    } 
                                } echo "</ol>";
                            } else {
                                echo 'No Lessons currently.';
                            }
                            ?>
                        </div>
                
                <?php
                $fetchteacherquery = "SELECT * FROM 7062vle_users users INNER JOIN 7062vle_modules modules ON modules.Mcreator = users.UserID WHERE modules.ModuleID = '$moduleid'";
                $fetchteacher = mysqli_query($conn, $fetchteacherquery) or die(mysqli_error($conn));
                if (mysqli_num_rows($fetchteacher) > 0 ) {
                $row3 = mysqli_fetch_assoc($fetchteacher);
                            $tname = $row3['Name'];
                            $temail = $row3['Email'];
                            $tpic = $row3['ProfilePic'];
                            $tsub = $row3['Subject'];
                            $tsublvl = $row3['SubjectLvl'];
                            $showname = $row3['ShowName'];
                            $showsub = $row3['ShowSub'];
                            $showsublvl = $row3['ShowSubLvl'];
                    ?>

                        <div class='col-md-2 col-sm-1 col-xs-1  othersidenav'>
                            <ul style='list-style-type: none;'>
                            <?php if ($showname == 0) { 
                                echo "<li>$tname</li>";
                            }
                            echo "<li><a href='messages.php?toemail=$temail'>$temail</a></li>";
                            if ($showsub == 0) {
                                echo "<li>$tsub</li>";
                            }
                            if ($showsublvl == 0) {
                                echo "<li>$tsublvl</li>";
                            }
                            ?>
                                <?php if($id == $Mcrea) {
                                echo "<li><a href='messages.php?mailmoduleid=$moduleid&mailmodulename=$mname'><button class='btn btn-success btn-sm' type='button' name='mailallusers'>Mail Students</button></a></li>";
                                } ?>
                            </ul>

                        </div> <?php
                }
                ?>
            </div>
        </div>
    </section>
    
    
    

    
    
            
    
    <div class="container">
        <section class="s2">
            <div class='row textoverflow'>
                <span class='col-xs-3 col-sm-2 col-md-2'>
                    <?php
                        if (($role == 'teacher' && $Mcrea == $id) || $role == 'admin') {
                            echo "<a href='addlesson.php?module=$moduleid'><button type='button' class='btn btn-success' name='addlesson'>Add New Lesson</button></a>";
                            echo "<a href='addmodule.php?module=$moduleid'><button type='button' class='btn btn-info' name='editmodule'>Edit Module</button></a>";
                            echo "<a href='delete.php?module=$moduleid'><button type='button' class='btn btn-danger' name='deletemodule'>Delete Module</button></a>";
                        }
                    ?>
                </span>
                <span class='col-xs-12 col-sm-10 col-md-9'>

                    <?php
                        if ($error) {
                            echo "<p><div id='error'><i>$error</i></div></p>";
                        }
                        if ($lessondeleted) {
                            echo "<p><div class='text-success'><i>Lesson has been deleted!</i></div></p>";
                        }
                    ?>
                    <div id='thumbnailcolumn'>
                        <img id='thumbnail' src='../img/<?php echo $mpic?>' alt='<?php echo $mname ?>'/>
                    </div>
                    <h3><?php echo $mname ?></h3>
                    <p><?php echo $mdesc ?></p>
                    <hr>
                </span>
            </div>
            <div class='row'>
                <?php if (isset($_GET['lesson'])) { ?>
                <span class='col-xs-3 col-sm-2 col-md-2'>
                    <?php
                        if (($role == 'teacher' && $Mcrea == $id) || $role == 'admin') {
                            echo "<a href='addlesson.php?lesson=$selectedlesson'><button type='button' class='btn btn-success' name='lesson'>Edit this Lesson</button></a>";
                            echo "<a href='delete.php?module=$moduleid&lesson=$selectedlesson'><button type='button' class='btn btn-danger' name='deletelesson'>Delete this Lesson</button></a>";
                        }
                    ?>
                </span>
                <span class='col-xs-12 col-sm-10 col-md-9'>
                    
                    <p><label><?php echo $selectedLname ?></label></p>
                    <p><?php echo $selectedLdesc ?></p>

                    <hr>
                    <?php if ($selectedLform == 'text') {
                        echo "<div class='textareabox'>
                            <pre id='lessontext'>$selectedLesson</pre>
                            <button type='button' value='download' onclick='download();' class='btn btn-success' name='download'>Download</button>
                        </div>
                        ";
                    } else if ($selectedLform == 'file') {
                        $fileextn = pathinfo($selectedLesson, PATHINFO_EXTENSION);
                        if ($fileextn == 'pdf') {
                            echo "<iframe width='800' height='600' src='../lessons/$selectedLesson'></iframe>";
                        } else {
                            echo "See this file for lesson: <a href='../lessons/$selectedLesson'>$selectedLesson</a>";
                        }
                    } else if ($selectedLform == 'videofile') {
                        $fileextn = pathinfo($selectedLesson, PATHINFO_EXTENSION);
                        echo "<video width='800' controls>
                            <source src='../lessons/$selectedLesson' type='video/$fileextn'>
                            HTML5 video is not supported by your browser
                          </video>";
                    } else if ($selectedLform == 'videolink') {
                        echo "<div class='embed-responsive embed-responsive-4by3'>";
                        echo "<iframe class='embed-responsive-item' width='420' height='315' src='$selectedLesson'></iframe></div>";
                    } else if ($selectedLform == 'audio') {
                        $fileextn = pathinfo($selectedLesson, PATHINFO_EXTENSION);
                        echo "<audio controls>
                            <source src='$selectedLesson' type='audio/$fileextn'>
                            Your browser does not support the audio element.
                            </audio>";
                    } else {
                        echo "No lesson content currently available. Please contact the Teacher.";
                    }
                    ?>
                    <p></p>
                </span>
                <?php } ?>
            </div>
        </section>
    </div>
    <?php } else {
        header("Location:userhub.php");
    }
?>
     
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