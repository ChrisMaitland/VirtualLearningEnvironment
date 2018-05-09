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
    
    if (isset($_GET['Profile'])) {
        $user = $_GET['Profile'];
    } else {
        header("Location:userhub.php");
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
    
    
</head>
<body id='bg'>

    <nav class="navbar navbar-inverse navbar-fixed-top foot ">

        <div class="container foot">

            <div class="navbar-header foot">


                <a href="userhub.php" class="navbar-brand navbar-left ">
                    <img id='imgbanner' src="../img/logolarge1.png"  class="img-responsive title"></a>
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
                        
                        <a href='profile.php'><button type='button' class="btn btn-success" name='profile'>Profile</button></a>

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
                    $profilequery = "SELECT * FROM 7062vle_users WHERE Name = '$user'";
                    $resultprofile = mysqli_query($conn, $profilequery);

                    if (mysqli_num_rows($resultprofile) > 0) {
                        echo "<div class='row'><ul style='list-style-type: none'>";
                        $row = mysqli_fetch_assoc($resultprofile);
                        $uid = $row['UserID'];
                        $uname = $row['Name'];
                        $uemail = $row['Email'];
                        $urole = $row['userrole'];
                        $upw = $row['pw'];
                        $pic = $row['ProfilePic'];
                        $sub = $row['Subject'];
                        $sublvl = $row['SubjectLvl'];
                        $showname = $row['ShowName'];
                        $showsub = $row['ShowSub'];
                        $showsublvl = $row['ShowSubLvl'];
                        ?>
                        <div class='row'>
                            <h3><?php echo "\t$uname's" ?> Profile</h3><br />
                            <div class='col-md-4 col-md-offset-1'>
                                        <label for='ProfilePic'>Profile Picture</label>
                                        <div id='thumbnailcolumn'>
                                            <img id='thumbnail' src='../img/<?php echo $pic ?>' alt='<?php echo $uname ?>'>
                                        </div>
                            </div>

                            <div class='col-xs-9 col-sm-6'>
                                    <label for='profiledetails'>Profile Details</label>
                                    <?php if (($urole == 'teacher' || $urole == 'admin') && $showname == '0') {
                                        echo "<p>Name: \t$uname</p>";
                                    } ?>
                                        <p>Email: <?php echo "<a href='messages.php?toemail=$uemail'>$uemail</a>"; ?></p>
                                        <p>User Role:<?php echo "\t$urole" ?></p>
                                        <?php if (($urole == 'teacher' || $urole == 'admin') && $sub != "" && $showsub == '0') {
                                            echo "<p>Subject: \t$sub</p>";
                                        } ?>
                                        <?php if (($urole == 'teacher' || $urole == 'admin') && $sublvl != "" && $showsublvl == '0') {
                                            echo "<p>Subject Level: \t$sublvl</p>";
                                        } ?>
                            </div>
                        </div>
                    <?php
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