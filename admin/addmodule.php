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
    
    if (isset($_GET['duplicatename'])) {
        $duplicatename = $_GET['duplicatename'];
    } else {
        $duplicatename = "";
    }
    
    if (isset($_GET['filesize'])) {
        $pictoolarge = $_GET['filesize'];
    } else {
        $pictoolarge = "";
    }
    
    if (isset($_GET['success'])) {
        $addedmodule = $_GET['success'];
    } else {
        $addedmodule = "";
    }
    
    if (isset($_GET['module'])) {
        $editmodule = $_GET['module'];
    } else {
        $editmodule = "";
    }
    
?>

<!DOCTYPE html>
<head>
    <title>Add Module</title>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="../design/style.css" />
    <link rel="icon" href="../img/logolarge.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="../js/functions.js">
    
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
    
    <?php 
    
    $error = "";
    if($editmodule != "") {
        $modulequery = "SELECT * FROM 7062vle_modules WHERE moduleid = '$editmodule'";
        $moduleresult = mysqli_query($conn, $modulequery) or die (mysqli_error($conn));

        if (mysqli_num_rows($moduleresult) == 1) {
            $modrow = mysqli_fetch_assoc($moduleresult);
            $currentMname = $modrow['Mname'];
            $currentLanguage = $modrow['Language'];
            $currentMDesc = $modrow['MDesc'];
            $currentMcreator = $modrow['Mcreator'];
            $currentMpic = $modrow['Mpic'];
            $currentAutoapprove = $modrow['Autoapprove'];
        } else {
            $error = "Module does not exist.";
        }
        
        if (!(($role == 'teacher' && $currentMcreator == $id) || $role == 'admin')) {
            $error = "You do not have permission to view this page.";
        }
        
    } else {
        $currentMname = "";
        $currentLanguage = "";
        $currentMDesc = "";
        $currentMcreator = "";
        $currentMpic = "";
        $currentAutoapprove = "";
    }   
    ?>
    
    <div class="container">
        <section class="s2">
            <div class='row'>
                    <div class='col-xs-offset-1 col-xs-12 col-sm-6 col-md-8 textoverflow'>
                        <?php 
                        if ($error != "") {
                            echo $error;
                        } else {
                            if ($editmodule != "") {
                                echo "<h3>Edit Module</h3><br />";
                            } else {
                                echo "<h3>Add a new Module</h3><br />";
                            }
                        ?>
                                <form action="addmoduleupload.php" method="POST" enctype="multipart/form-data">
                                    <div class='form-group'>
                                        <label for='Mname'>Module Name:</label>
                                        <p><input type="text" class="form-control" name="newname" placeholder="New Module Name" value="<?php echo $currentMname ?>"></p>
                                            <p class='help-block'>Must be unique.</p>
                                        <label for='Language'>Language:</label>
                                        <p><input type="text" class="form-control" name="lang" placeholder="French / German, etc" value="<?php echo $currentLanguage ?>" ></p>
                                        <label for='MDesc'>Module Description:</label>
                                        <p><input type="textarea" class="form-control" name="desc" placeholder="Description of Content" value="<?php echo $currentMDesc ?>" ></p>
                                        <label for='Autoapprove'>Students Requires Approval?</label>
                                        <p><input type="checkbox" id="inlineCheckbox1" name="Autoapprove" value="<?php echo $currentAutoapprove ?>" <?php if ($currentAutoapprove == '1') echo "checked='checked'"; ?> > Check to allow all users to register without approval.</p>
                                        <p><label for='mpicupload'>Module Cover Picture:</label></p>
                                        <p><input type="file" name="mpicupload" id="mpicupload"></p>
                                        <?php if ($editmodule == "") {
                                            echo "<p class='help-block'>Upload a jpg or png under 2MB. Set to default if left blank.</p>";
                                        } else {
                                            echo "<p class='help-block'><b>*Optional*</b> Upload a jpg or png under 2MB. Leave blank for no change.</p>";
                                        } ?>
                                        <input type='hidden' name='editmoduleid' value="<?php echo $editmodule ?>">
                                        <p><button type="submit" name="submitaction" class="btn btn-success" value="<?php if ($editmodule != ''){ echo 'editmodule';} else { echo 'addnewmodule';} ?>" >Submit</button></p>

                                        <?php
                                            if ($duplicatename) {
                                                echo "<p><div id='error'><i>Module Name Already exists!</i></div></p>";
                                            }
                                        ?>
                                        <?php
                                            if ($pictoolarge) {
                                                echo "<p><div id='error'><i>Picture size is greater than 2MB!</i></div></p>";
                                            }
                                        ?>
                                        <?php
                                            if ($addedmodule && $editmodule == "") {
                                                echo "<p><div class='text-success'><i>Module successfully added!</i></div></p>";
                                            } else if ($addedmodule) {
                                                echo "<p><div class='text-success'><i>Module successfully edited!</i></div></p>";
                                            }
                                        ?>
                                    </div>
                                </form>
                        <?php } ?>
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