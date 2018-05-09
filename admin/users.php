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
    
    if (isset($_POST['selected'])) {
        $selected = $_POST['selected'];
        $chosenone = $_POST['chosenone'];
    } else {
        $selected = "";
        $chosenone = "";
    }
    
    if (isset($_POST['deleted'])) {
        $deleted = $_POST['deleted'];
    } else {
        $deleted = "";
    }
    if (isset($_POST['success'])) {
        $success = $_POST['success'];
    } else {
        $success = "";
    }
    
?>

<!DOCTYPE html>
<head>
    <title>Manage Users</title>
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
                    <div class='col-xs-offset-1 col-xs-12 col-sm-9 col-md-9'>
                        
                        <h3>All Registered Users</h3><br />
                        <form action='updateuser.php' method='POST' enctype="multipart/form-data">
                            <div class="table-responsive">
                            <table class='table table-bordered table-hover'>
                            <tr class="success">
                                            <th>#ID</th>
                                            <th>Name</th>
                                            <th>EMail</th>
                                            <th>User Role</th>
                                            <th>Profile Pic</th>
                                            <th>Delete User</th>
                                        </tr>
                                            
                                <?php
                                    $usersquery = "SELECT * FROM 7062vle_users";
                                    $resultusers = mysqli_query($conn, $usersquery);

                                    if (mysqli_num_rows($resultusers) > 0) {
                                        while ($row = mysqli_fetch_assoc($resultusers)){
                                            echo "<tr>";
                                            echo "<td>".$row['UserID']."</td>";
                                            echo "<td>".$row['Name']."</td>";
                                            echo "<td>".$row['Email']."</td>";
                                            echo "<td>".$row['userrole']."</td>";
                                            echo "<td>".$row['ProfilePic']."</td>";
                                            echo "<td><button type='submit' name='deleteuser' class='btn btn-warning btn-sm' value=".$row['UserID'].">Delete User</button></td>";
                                        }
                                    }

                                ?>
                                                
                            </table>
                            </div>
                        </form>
                    </div>
            </div>
            <div class="row">
                <div class='col-xs-offset-1 col-xs-12 col-sm-9 col-md-9'>
                        <?php
                            if ($deleted) {
                                echo "<p><div class='text-success'><i>User has been deleted!</i></div></p>";
                            }
                            if ($success) {
                                echo "<p><div class='text-success'><i>User details have been updated!</i></div></p>";
                            } ?>
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-inline">
                                        <div class='form-group'>
                                            <p><label for='userpick'>Edit Profiles</label></p>
                                            <p><h5>Select which User you wish to edit:</h5></p>
                                            <p><select class="form-control" name="chosenone" value="<?php echo '$chosenone' ?>">
                                        <?php
                                            $userspick = "SELECT * FROM 7062vle_users";
                                            $pickusers = mysqli_query($conn, $usersquery);
                                            
                                            if (mysqli_num_rows($pickusers) > 0) {
                                                if($chosenone == ""){
                                                    echo "<option selected hidden>Select ID to edit</option>";
                                                }
                                                while ($row = mysqli_fetch_assoc($pickusers)){
                                                    $userid = $row['UserID'];
                                                    if($chosenone == $userid){
                                                        $selectedstring = "selected";
                                                    } else {
                                                        $selectedstring = "";
                                                    }
                                                    echo "<option value='$userid' ".$selectedstring.">".$userid."</option>";
                                                }
                                            }
                                        ?>
                                        </select>
                                                <button type="submit" class="btn btn-success" name="selected" value="selected">Edit User</button>
                                            </p>
                                            
                                        </div>
                                    </div>
                                </form>
                                <?php if ($selected) {
                                    
                                        $usersupdatepick = "SELECT * FROM 7062vle_users WHERE UserID = '$chosenone'";
                                        $pickusersupdate = mysqli_query($conn, $usersupdatepick);
                                        
                                        $row2 = mysqli_fetch_assoc($pickusersupdate);
                                        $pickname = $row2['Name'];
                                        $pickemail = $row2['Email'];
                                        $pickuserrole = $row2['userrole'];
                                        $pickpic = $row2['ProfilePic'];
                                ?>
                                <hr>
                </div>
            </div>
                                <div class="row">
                                    <div class='col-xs-offset-1 col-xs-12 col-sm-9 col-md-9'>
                                    <form action='updateuser.php' method='POST' enctype='multipart/form-data'>
                                        <input type='hidden' name='userid' value="<?php echo $chosenone ?>">
                                        <div class='form-group'>
                                            <label for='updateuser'>Amend Below</label>
                                            <p>Name:<input type='text' class='form-control' name='updatename' value="<?php echo $pickname ?>"></p>
                                        </div>
                                        <div class='form-group'>
                                            <p>Email:<input type='email' class='form-control' name='updateemail' value="<?php echo $pickemail ?>"></p>
                                        </div>
                                        <div class='form-group'>
                                            Update Role: <select class='form-control' name='userrole' >
                                                <option <?php if($pickuserrole == 'admin'){echo("selected");}?>>admin</option>
                                                <option <?php if($pickuserrole == 'teacher'){echo("selected");}?>>teacher</option>
                                                <option <?php if($pickuserrole == 'student'){echo("selected");}?>>student</option>
                                            </select>
                                        </div>
                                        <div class='form-group'>
                                            <p>Reset Profile Pic to default:</p>
                                                <p><input type='checkbox' id='inlineCheckbox1' name='ResetPic'></p>
                                        </div>
                                        <div class='form-group'>
                                            <p><button type="submit" class="btn btn-success" name="update">Update</button></p>
                                        </div>
                                        
                                    </form>
                             <?php   
                                }
                            ?>
                        
                                    </div>
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