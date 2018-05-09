<?php

    session_start();
    include("../connections/connection.php");
    
    if (isset($_GET['upload'])) {
        $uploaded = $_GET['upload'];
    } else {
        $uploaded = "";
    }
    
    if (isset($_GET['nofile'])) {
        $notuploaded = $_GET['nofile'];
    } else {
        $notuploaded = "";
    }

    if (isset($_GET['updateall'])) {
        $updatedeverything = $_GET['updateall'];
    } else {
        $updatedeverything = "";
    }
    
    if (isset($_GET['updatenothing'])) {
        $nothingupdated = $_GET['updatenothing'];
    } else {
        $nothingupdated = "";
    }

    if (isset($_GET['updaterole'])) {
        $updaterolesuccess = $_GET['updaterole'];
    } else {
        $updaterolesuccess = "";
    }
    
    if (isset($_GET['updatepw'])) {
        $updatepwsuccess = $_GET['updatepw'];
    } else {
        $updatepwsuccess = "";
    }
    
    if (isset($_GET['updatedshow'])) {
        $updatedshow = $_GET['updatedshow'];
    } else {
        $updatedshow = "";
    }
    
    $email = $_SESSION['7062vle_email'];
    $role = $_SESSION['7062vle_role'];
    $name = $_SESSION['7062vle_name'];
    $id = $_SESSION['7062vle_id'];
    
    if (!isset($email)) {
        header('Location:logout.php');
    }
?>

<!DOCTYPE html>
<head>
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
                <div class="navbar-form navbar-right ">
                    <form action='admin/signin.php' method='POST'>
                        
                        <?php
                                if ($role == 'admin') {
                                    echo "<a href='users.php'><button type='button' class='btn btn-success' name='users'>Manage Users</button></a>";
                                }
                            ?>

                        <a href='userhub.php'><button type='button' class="btn btn-success" name='userhub'>User Hub</button></a>
                        
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
                <?php
                    $profilequery="SELECT * FROM 7062vle_users WHERE UserID = '$id'";
                    $resultprofile=mysqli_query($conn,$profilequery) or die(mysqli_error($conn));

                    if (mysqli_num_rows($resultprofile) == 1) {
                        $row = mysqli_fetch_assoc($resultprofile);
                        
                        $_SESSION['7062vle_email'] = $row['Email'];
                        $_SESSION['7062vle_name'] = $row['Name'];
                        $email = $_SESSION['7062vle_email'];
                        $name = $_SESSION['7062vle_name'];

                        echo "<div class='row'><ul style='list-style-type: none'>";
                        $uid = $row['UserID'];
                        $uname = $row['Name'];
                        $uemail = $row['Email'];
                        $urole = $row['userrole'];
                        $upw = $row['pw'];
                        $pic = $row['ProfilePic'];
                        $sub = $row['Subject'];
                        $sublvl = $row['SubjectLvl'];
                        $hidename = $row['ShowName'];
                        $hidesub = $row['ShowSub'];
                        $hidesublvl = $row['ShowSubLvl'];
                        $urequestedrole = $row['RequestedRole'];
                        ?>
                        <div class='row textoverflow'>
                            <h3><?php echo "\t$name's" ?> Profile</h3><br />
                            <div class='col-md-4 col-md-offset-1'>
                                <form action="uploadpic.php" method="POST" enctype="multipart/form-data">
                                    <div class='form-group'>
                                        <label for='ProfilePic'>Profile Picture</label>
                                        <div id='thumbnailcolumn'>
                                            <a target='_blank' href='../img/<?php echo $pic ?>'><img id='thumbnail' src='../img/<?php echo $pic ?>' alt='<?php echo $uname ?>'></a>
                                            
                                            <?php
                                                if ($uploaded) {
                                                    echo "<p><div id='success'><i>Image uploaded!</i></div></p>";
                                                }
                                            ?>
                                            <?php
                                                if ($notuploaded) {
                                                    echo "<p><div id='error'><i>No image!</i></div></p>";
                                                }
                                            ?>
                                            <input type="file" name="picupload" id="picupload">
                                            <p class='help-block'>Upload a jpg or png under 2MB.</p>
                                            <p><button type="submit" name="uploadpicture" class="btn btn-success" value="Upload Image">Submit</button></p>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class='col-xs-9 col-sm-6'>
                                <form action="updatepro.php" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for='profiledetails'>Profile Details</label>
                                        <p>Name:<input type="text" class="form-control" name="newname" placeholder="<?php echo $uname ?>"></p>Private: <span><input type='checkbox' id='inlineCheckbox1' name='HideName' value="<?php echo $hidename ?>" <?php if ($hidename == '1') echo "checked='checked'"; ?>></span>
                                    </div>
                                    <div class="form-group">
                                        <p>Email:<input type="email" class="form-control" name="newemail" placeholder="<?php echo $uemail ?>"></p>
                                    </div>
                                        <?php if ($role == 'teacher' || $role == 'admin') { ?>
                                        <div class="form-group">
                                            <p>Subject:<input type="text" class="form-control" name="newsub" placeholder="<?php echo $sub ?>"></p>Private: <span><input type='checkbox' id='inlineCheckbox1' name='HideSub' value="<?php echo $hidesub ?>" <?php if ($hidesub == '1') echo "checked='checked'"; ?>></span>
                                        </div>
                                        <div class="form-group">
                                            <p>Subject Level:<input type="text" class="form-control" name="newsublvl" placeholder="<?php echo $sublvl ?>"></p>Private: <span><input type='checkbox' id='inlineCheckbox1' name='HideSublvl' value="<?php echo $hidesublvl ?>" <?php if ($hidesublvl == '1') echo "checked='checked'"; ?>></span>
                                        </div>
                                        <?php } ?>
                                    <?php
                                        if ($updatedeverything) {
                                            echo "<p><div class='text-success'><i>Profile updated</i></div></p>";
                                        }
                                        if ($nothingupdated) {
                                            echo "<p><div id='error'><i>No changes</i></div></p>";
                                        }
                                        if ($updatedshow) {
                                            echo "<p><div class='text-success'><i>Updated privacy settings</i></div></p>";
                                        }
                                    ?>
                                    <button type='submit' class='btn btn-success' name='detailssubmit'>Update Profile</button>
                                </form>
                                
                                <form action="updatepro.php" method="POST">
                                    <p></p>
                                    <?php
                                    echo "<p>User Role: $urole</p>";
                                        if ($role != 'admin') {
                                            if ($urequestedrole) {
                                                echo "<p>Requested Role: $urequestedrole</p>";
                                            }
                                            if ($role == 'student') {
                                            echo "<p><select class='form-control' name='requestedrole' id='requestedrole'>
                                                    <option selected hidden>Select new role</option>
                                                    <option value='teacher'>teacher</option>
                                                    <option value='admin'>admin</option>
                                                  </select></p>";
                                                echo "<p><button type='submit' class='btn btn-warning' name='submitrolereq' value='Submit'>Request Role Change</button></p>
                                                <p class='help-block'>Approval will be required<p>";
                                            } else if ($role == 'teacher' && $urequestedrole != 'admin') {
                                                echo "<input type='hidden' name='requestedadmin' id='requestedadmin' value='requestedadmin' />";
                                                echo "<p><button type='submit' class='btn btn-warning' name='submitrolereq' value='Submit'>Request Admin Access</button></p>
                                                    <p class='help-block'>Approval will be required<p>";
                                            }
                                            if ($updaterolesuccess) {
                                                echo "<p><div class='text-success'><i>New role requested</i></div></p>";
                                            }
                                        } 
                                    ?>
                                </form>

                                <form action="updatepro.php" method="POST">
                                    <div class="form-group">
                                        <label for='changepassword'>Change Password</label>
                                        <p>Choose a password:<input type="password" name="pw1" id="pw1" placeholder="Password" class="form-control" onkeyup="checkPasswordsValid();"/></p>
                                    </div>
                                    <div class="form-group">
                                        <p>Confirm password:<input type="password" name="pw2" id="pw2" placeholder="Check password" class="form-control" onkeyup="checkPasswordsValid();"/></p>
                                        <span id="message"></span>

                                    </div>
                                    <?php
                                        if ($updatepwsuccess) {
                                            echo "<p><div class='text-success'><i>Password updated</i></div></p>";
                                        }
                                    ?>
                                    <button type="submit" id="submit" class="btn btn-success" name="pwsubmit" value="Submit" disabled >Update Password</button>
                                </form>

                            </div>
                        </div>
                    <?php
                    } else {
                        header('Location:logout.php');
                    }
                ?>
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