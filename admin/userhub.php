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

    if (isset($_GET['moduledeleted'])) {
        $moduledeleted = $_GET['moduledeleted'];
    } else {
        $moduledeleted = "";
    }
    
    if (isset($_GET['approve'])) {
        $approve = $_GET['approve'];
    } else {
        $approve = "";
    }
    
?>

<!DOCTYPE html>
<head>
    <title>User Hub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="../design/style.css" />
    <link rel="icon" href="../img/logolarge.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top foot ">

            <div class="container foot">

                <div class="navbar-header">

                    <a href="userhub.php" class="navbar-brand navbar-left ">
			<img id='imgbanner' src="../img/logolarge1.png" class="img-responsive"></a>
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>                        
                        </button>
                </div>  

                <div id="myNavbar" class="navbar-collapse collapse">
                    <div class="navbar-form navbar-right ">
                        
                            <?php
                                if ($role == 'admin') {
                                    echo "<a href='users.php'><button type='button' class='btn btn-success' name='users'>Manage Users</button></a>";
                                }
                            ?>

                            <a href='profile.php'><button type='button' class="btn btn-success" name='profile'>Profile</button></a>
                            
                            <a href='messages.php'><button type='button' class="btn btn-success" name='messages'><span class="glyphicon glyphicon-envelope" aria-label="Messages"></span></button></a>

                            <a href='logout.php'><button type='button' class="btn btn-success" name='logout'>Log Out</button></a>

                            <p><div id='pw'><a href="messages.php?toemail=cmaitland02@qub.ac.uk"> Problem? Contact Admin! </a></div></p>

                    </div>
                </div><!--/.navbar-collapse -->

            </div>
    </nav>
<section class="s1">
</section>
<div class="container">
    
    <?php if ($role == 'teacher' || $role == 'admin') { ?>
        <section class="s2">
            <div class='row'>
                <span class='col-xs-offset-1 col-xs-12 col-sm-9 col-md-9'>
                    <h3>Approvals</h3>
                    <hr>
                    <?php
                        if ($approve == 'approvemodule') {
                            echo "<p><div class='text-success'><i>Enrolement Approved!</i></div></p>";
                        }
                        if ($approve == 'rejectmodule') {
                            echo "<p><div class='text-success'><i>Enrolement Rejected!</i></div></p>";
                        }
                        if ($approve == 'approverole') {
                            echo "<p><div class='text-success'><i>Role change request Approved!</i></div></p>";
                        }
                        if ($approve == 'rejectrole') {
                            echo "<p><div class='text-success'><i>Role change request Rejected!</i></div></p>";
                        }
                    ?>
                    <table>
                    <?php 
                    if ($role == 'admin') {
                        $roleapprovalsquery = "SELECT * FROM 7062vle_users WHERE RequestedRole is not null AND RequestedRole != ''";
                        $roleapprovals = mysqli_query($conn, $roleapprovalsquery);
                        if (mysqli_num_rows($roleapprovals) > 0) { 
                            while ($row = mysqli_fetch_assoc($roleapprovals)) {
                                $userid = $row['UserID'];
                                $requestedrole = $row['RequestedRole'];
                                $requestor = $row['Name'];
                                $reqemail = $row['Email'];
                                $runpic = $row['ProfilePic'];

                                echo "<tr><td id='td'><b>$requestedrole access</b></td><td id='td'>Requested by: <img class='img-circle profilepict' src='../img/$runpic' alt='$runpic'/>$requestor - $reqemail</td>
                                    <form action='changerole.php' method='POST' enctype='multipart/form-data'>
                                    <input type='hidden' name='userid' value='$userid'>
                                    <input type='hidden' name='requestedrole' value='$requestedrole'>
                                    <td id='td'><button type='submit' value='approve' class='btn btn-success btn-sm' name='rolechoice'>Approve</button></td>
                                    <td id='td'><button type='submit' value='reject' class='btn btn-danger btn-sm' name='rolechoice'>Reject</button></td></form></tr>";
                            }
                        }
                    }
                    $approvalsquery = "SELECT modules.*, enrol.*, users.* FROM 7062vle_modules modules INNER JOIN 7062vle_enrolement enrol ON modules.moduleid = enrol.ModuleID INNER JOIN 7062vle_users users ON enrol.UserID = users.UserID WHERE modules.Mcreator = '$id' AND enrol.Approved = 0";
                    $runapprovalsquery = mysqli_query($conn, $approvalsquery);
                    if (mysqli_num_rows($runapprovalsquery) > 0) { 
                        while ($row = mysqli_fetch_assoc($runapprovalsquery)) {
                            $approvemodid = $row['ModuleID'];
                            $enrolid = $row['EnrolID'];
                            $mname = $row['Mname'];
                            $requestor = $row['Name'];
                            $reqemail = $row['Email'];
                            $runpic = $row['ProfilePic'];

                            echo "<tr><td id='td'><b>$mname</b></td><td id='td'>Requested by: <img class='img-circle profilepict' src='../img/$runpic' alt='$runpic'/>$requestor - $reqemail</td>
                                <form action='enrol.php' method='POST' enctype='multipart/form-data'>
                                <input type='hidden' name='enrolid' value='$enrolid'>
                                <td id='td'><button type='submit' value='approve' class='btn btn-success btn-sm' name='choice'>Approve</button></td>
                                <td id='td'><button type='submit' value='reject' class='btn btn-danger btn-sm' name='choice'>Reject</button></td></form></tr>";
                        }
                    }
                    ?>
                    </table>
                        
                </span>
            </div>
            <br />
        </section>
    <br />
    <?php
    }
    ?>
    
<section class="s2">
    <div class='row'>
        <div class='col-xs-3 col-sm-2 col-md-2'>
            <?php
                if ($role == 'teacher' || $role == 'admin') {
                    echo "<a href='addmodule.php'><button type='button' class='btn btn-success' name='addmodule'>Add New Module</button></a>";
                }
            ?>
        </div>
    </div>
    <div class="row">
        <div class='col-xs-offset-1 col-xs-12 col-sm-9 col-md-9'>
            <?php
            if ($moduledeleted) {
                    echo "<p><div class='text-success'><i>Module has been deleted!</i></div></p>";
                }
            ?>
            <div class="textoverflow">
            <h3>My Modules</h3>        
            <hr>
            <?php
            $modulequery = "";
            $nonefoundstring = "";
            if($role == 'student') {
                $modulequery = "SELECT modules.*, users.Name, users.ProfilePic FROM 7062vle_modules modules INNER JOIN 7062vle_enrolement enrol ON modules.moduleid = enrol.ModuleID INNER JOIN 7062vle_users users ON modules.Mcreator = users.UserID WHERE enrol.UserID = '$id' AND enrol.Approved = 1";
                $nonefoundstring = "You are not currently enrolled in any modules";
            } else if ($role == 'teacher' || $role == 'admin') { 
                $modulequery = "SELECT modules.*, users.Name, users.ProfilePic FROM 7062vle_modules modules INNER JOIN 7062vle_users users ON modules.Mcreator = users.UserID WHERE Mcreator = '$id'";
                $nonefoundstring = "You do not currently own any modules";
            }
            
            $resultmodule = mysqli_query($conn, $modulequery);

            if (mysqli_num_rows($resultmodule) > 0) {
                echo "<table>";
                while ($row = mysqli_fetch_assoc($resultmodule)) {
                    $mid = $row['moduleid'];
                    $mname = $row['Mname'];
                    $language = $row['Language'];
                    $mcreator = $row['Name'];
                    $pic = $row['Mpic'];
                    $runpic = $row['ProfilePic'];
                    
                    echo "<tr><td id='thumbnailcolumn'><a href='learnerhub.php?moduleid=$mid'><img id='thumbnail' src='../img/$pic' alt='$mname'/></a></td>";
                    echo "<td><a href='learnerhub.php?moduleid=$mid'><b>$mname</b></a> by <img class='img-circle profilepict' src='../img/$runpic' alt='$runpic'/> <a href='profileviewer.php?Profile=$mcreator'>$mcreator</a><br/>Language: $language - #$mid</td></tr>";
                }
                echo "</table>";
            } else {
                echo "$nonefoundstring";
            }
        
            if($role == 'student') {
                echo "</div></div></section><br /><section class='s2'>";
                echo "<div class='row'><div class='col-xs-offset-2 col-xs-12 col-sm-9 col-md-9'>";
                
                echo "<h3>Pending Requests</h3><hr>";

                $modulequery = "SELECT modules.*, users.Name, users.ProfilePic FROM 7062vle_modules modules INNER JOIN 7062vle_enrolement enrol ON modules.moduleid = enrol.ModuleID INNER JOIN 7062vle_users users ON modules.Mcreator = users.UserID WHERE enrol.UserID = '$id' AND enrol.Approved = 0";
            
                $resultmodule = mysqli_query($conn, $modulequery);

                if (mysqli_num_rows($resultmodule) > 0) {
                    echo "<table>";
                    while ($row = mysqli_fetch_assoc($resultmodule)) {
                        $mid = $row['moduleid'];
                        $mname = $row['Mname'];
                        $language = $row['Language'];
                        $mcreator = $row['Name'];
                        $pic = $row['Mpic'];
                        $runpic = $row['ProfilePic'];

                        echo "<tr><td id='thumbnailcolumn'><img id='thumbnail' src='../img/$pic' alt='$mname'></td>";
                        echo "<td><b>$mname</b> by <img class='img-circle profilepict' src='../img/$runpic' alt='$runpic'/> <a href='profileviewer.php?Profile=$mcreator'>$mcreator</a><br/>Language: $language - #$mid</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No requests are pending";
                }
            }
            
            if($role == 'student' || $role == 'admin') {
                echo "</div></div></section><br /><section class='s2'>";
                echo "<div class='row'><div class='col-xs-offset-1 col-xs-12 col-sm-9 col-md-9'>";
                if($role == 'student') {
                    echo "<h3>Available Modules</h3><hr>";
                } else if ($role == 'admin') {
                    echo "<h3>Other Modules</h3><hr>";
                }
                
                $modulequery = "";
                if($role == 'student') {
                    $modulequery = "SELECT modules.*, users.Name, users.ProfilePic FROM 7062vle_modules modules INNER JOIN 7062vle_users users ON modules.Mcreator = users.UserID WHERE modules.moduleid NOT IN (SELECT ModuleID FROM 7062vle_enrolement WHERE UserID = '$id')";
                } else if ($role == 'admin') { 
                    $modulequery = "SELECT modules.*, users.Name, users.ProfilePic FROM 7062vle_modules modules INNER JOIN 7062vle_users users ON modules.Mcreator = users.UserID WHERE Mcreator != '$id'";
                }
            
                $resultmodule = mysqli_query($conn, $modulequery);

                if (mysqli_num_rows($resultmodule) > 0) {
                    echo "<table>";
                    while ($row = mysqli_fetch_assoc($resultmodule)) {
                        $mid = $row['moduleid'];
                        $mname = $row['Mname'];
                        $language = $row['Language'];
                        $mcreator = $row['Name'];
                        $pic = $row['Mpic'];
                        $runpic = $row['ProfilePic'];
                        
                    if ($role == 'admin') {
                            $link = "<a href='learnerhub.php?moduleid=$mid'>";
                            $closelink = "</a>";
                        } else {
                            $link = '';
                            $closelink = '';
                        }
                        echo "<tr><td id='thumbnailcolumn'>$link<img id='thumbnail' src='../img/$pic' alt='$mname'>$closelink</td>";
                        
                        echo "<td>$link<b>$mname</b>$closelink by <img class='img-circle profilepict' src='../img/$runpic' alt='$runpic'/> <a href='profileviewer.php?Profile=$mcreator'>$mcreator</a><br/>Language: $language - #$mid</td>
                            <td>";
                            if($role == 'student') {
                                echo "<a href='enrol.php?module=$mid'><button type='button' class='btn btn-success btn-sm' name='register'>Register</button></a>";
                            }
                        echo "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No further modules are available";
                }
            }

            mysqli_close($conn);
            ?>
            
        </div>
        </div>
    </div>
</section>
    <br />
</div>
</body>
<footer class="navbar-fixed-bottom foot">
    <div class="container">Travel Lingo - Chris Maitland - 2018</div>
</footer>