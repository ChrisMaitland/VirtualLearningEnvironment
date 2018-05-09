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
    
    if (isset ($_GET['folder'])) {
        $folder = $_GET['folder'];     
    } else {
        $folder = 'inbox';
    }
    
    if (isset ($_GET['page'])) {
        $page = $_GET['page'];     
    } else {
        $page = 1;
    }
    
    if (isset ($_GET['sent'])) {
        $sent = $_GET['sent'];
    } else if (!isset ($_GET['sent'])) {
        $sent = "";
    }
    
    if (isset($_GET['toemail'])) {
        $toemail = $_GET['toemail'];
    } else { 
        $toemail = "";
    }
    
    if (isset($_GET['mailmoduleid'])) {
        $mailmoduleid = $_GET['mailmoduleid'];
    } else {
        $mailmoduleid = "";
    }
    
    if (isset($_GET['mailmodulename'])) {
        $mailmodulename = $_GET['mailmodulename'];
    } else {
        $mailmodulename = "";
    }
    
?>

<!DOCTYPE html>
<head>
    <title>Messages</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="../design/style.css" />
    <link rel="icon" href="../img/logolarge.png" />
    <link href='https://fonts.googleapis.com/css?family=Chelsea Market' rel='stylesheet' />
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        window.onload = function() { 
            if ('<?php echo $toemail ?>' != '') {
                displayCompose('<?php echo $toemail ?>');
            } else if ('<?php echo $mailmoduleid ?>' != '' && '<?php echo $mailmodulename ?>' != '') {
                displayCompose('ALL <?php echo $mailmodulename ?> STUDENTS');
                document.getElementById('mailmoduleidspan').innerHTML = "<input class='form-control' name='mailmoduleid' id='mailmoduleid' type='hidden' value='<?php echo $mailmoduleid ?>' />";
            }
        };
        function displayCompose(to, subject) {
                var tovalue = "";
                var subvalue = "";
                if (to) {
                    tovalue = "value='" + to + "'";
                }
                if (subject) {
                    subvalue = "value='" + subject + "'";
                }
                document.getElementById('mailarea').innerHTML = "<form class='form-horizontal' action='sendmessages.php' method='POST' enctype='multipart/form-data'><label class='control-label'>"
                + "<span id='mailmoduleidspan'></span>"
                + "To:</label><input class='form-control' name='Email' placeholder='Enter Email Address' " + tovalue + "/>"
                + "<label class='control-label'>Subject:</label><input class='form-control' name='subject' placeholder='Enter Subject' " + subvalue + "/>"
                + "<textarea class='form-control' name='compose' id='compose' placeholder='Compose' rows='8' ></textarea>"
                + "<button type='submit' type='submit' class='btn btn-success bt-sm' name='sendmail'>Send</button>"
                + "</form>";
        };
        function displayMail(mailid, tofrom, subject, mailText, read) {
            document.getElementById('mailarea').innerHTML = "<p><button type='button' class='btn btn-success bt-sm' name='reply' onclick='displayCompose(\"" + tofrom + "\", \"RE: " + subject + "\");'>Reply</button></p>"
                + "<textarea class='form-control' name='mail' id='mail' placeholder='Mail' rows='8' >" + mailText + "</textarea>";
            if ('<?php echo $folder ?>' == 'inbox' && read == 'no') {
                $.ajax( {
                    type: "post",
                    url: "markasread.php", 
                    data:{mailid:mailid},
                    success: function(data) {
                        document.getElementById("r" + mailid).className = "";
                        var unreadcount = Number.parseInt(document.getElementById('unreadcountspan').innerHTML);
                        unreadcount = unreadcount - 1;
                        document.getElementById('unreadcountspan').innerHTML = unreadcount;
                    }
                } );
            }
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
                <div class="navbar-form navbar-right ">
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
                <?php 
                    $numberunreadquery = "SELECT COUNT(MessageID) AS unreadCount FROM 7062vle_messages WHERE RecipientID = '$id' AND ReadMessage = 'no'";
                                    $numberunread = mysqli_query($conn, $numberunreadquery) or die(mysqli_error($conn));

                                    if (mysqli_num_rows($numberunread) > 0) {
                                        $row = mysqli_fetch_assoc($numberunread); 
                                        $unreadcount = $row['unreadCount'];
                                    } else {
                                        $unreadcount = 0;
                                    }
                ?>
                <span class='col-sm-3 col-md-2'>          
                    <div class='form-group'>
                        <div class="sidenav img-responsive">
                            <ul style="list-style-type:none; color: white;">
                                <?php 
                                    echo "<button type='button' class='btn btn-warning btn-sm' name='submit' onclick='displayCompose();'>Compose</button><br /><hr>";
                                    echo "<li><p><a href='messages.php?folder=inbox' >Inbox ( <span id='unreadcountspan'>$unreadcount</span> )</a></p></li>
                                    <li><p><a href='messages.php?folder=sent' >Sent</a></p></li>";
                                ?>
                            </ul>
                        </div>
                    </div>
                </span>
            </div>
        </div>
    </section>
    <div class="container">
        <section class="s2 textoverflow">
                        <div class='row'>
                            <div class='col-md-offset-1 col-sm-11 col-md-11'>
                                <h3><?php echo "\t$name's" ?> Messages</h3>
                        <?php

                            $numberofmailsquery = "SELECT COUNT(MessageID) AS countMails FROM 7062vle_messages WHERE RecipientID = '$id'";
                            $numberofmails = mysqli_query($conn, $numberofmailsquery) or die(mysqli_error($conn));

                            if (mysqli_num_rows($numberofmails) > 0) {
                                $row = mysqli_fetch_assoc($numberofmails); 
                                $mailscount = $row['countMails'];
                            } else {
                                $mailscount = 0;
                            }
                            
                            $limit = 10;
                            $offset = ($page - 1) * $limit;
                            
                            $numpages = ceil($mailscount / $limit);
                            if ($page > $numpages) {
                                $page = $numpages;
                            }
                            if ($page <= 3) {
                                $shownumlower = 1;
                            } else {
                                $shownumlower = $page - 2;
                            }
                            if ($numpages > $page + 2) {
                                $shownumupper = $page + 2;
                            } else {
                                $shownumupper = $numpages;
                            }
                            echo "Page ";
                            if ($shownumlower != 1) {
                                echo "...";
                            }
                            for ($i = $shownumlower; $i <= $shownumupper; $i++) {
                                if ($i != $shownumlower) {
                                    echo ", ";
                                }
                                if ($i == $page) {
                                    echo "<b>$i</b>";
                                } else {
                                    echo "<a href='messages.php?folder=$folder&page=$i' >$i</a>";
                                }
                                
                            }
                            if ($shownumupper != $numpages) {
                                echo "...";
                            }
                            
                            echo "<hr>";
                            
                            if($folder == 'inbox') {
                                $mailquery = "SELECT users.Name, users.Email, messages.* FROM 7062vle_users users INNER JOIN 7062vle_messages messages ON users.UserID = messages.SenderID WHERE messages.RecipientID = '$id' ORDER BY messages.Timestamp DESC LIMIT $limit OFFSET $offset";
                            } else if ($folder == 'sent') {
                                $mailquery = "SELECT users.Name, users.Email, messages.* FROM 7062vle_users users INNER JOIN 7062vle_messages messages ON users.UserID = messages.RecipientID WHERE messages.SenderID = '$id' ORDER BY messages.Timestamp DESC LIMIT $limit OFFSET $offset";
                            }
                            ?>
                                
                            
                            <?php
                            $mails = mysqli_query($conn, $mailquery) or die(mysqli_error($conn));
                            if (mysqli_num_rows($mails) > 0) {
                                
                                ?>
                                <div class="table-responsive">
                                <table class='table table-hover'>
                                            <tr class='success'>
                                                <?php
                                                if($folder == 'inbox') {
                                                    echo "<th>Sender</th>";
                                                } else if ($folder == 'sent') {
                                                    echo "<th>Receiver</th>";
                                                }
                                                ?>
                                                <th>Title</th>
                                                <th>Message</th>
                                                <th>Time/Date</th>
                                            </tr>
                                <?php
                                while($row = mysqli_fetch_assoc($mails)) {

                                    $sender = $row['Name'];
                                    $MessageID = $row['MessageID'];
                                    $SenderID = $row['SenderID'];
                                    $RecipientID = $row['RecipientID'];
                                    $Email = $row['Email'];
                                    $Title = $row['Title'];
                                    $Message = $row['Message'];
                                    $Timestamp = $row['Timestamp'];
                                    $ReadMessage = $row['ReadMessage'];
                                    $previewmessage = substr($Message, 0, 20).'...';
                                
                                    echo "<tr id=\"r";
                                    echo $MessageID;
                                    echo "\" ";
                                    if ($ReadMessage == 'no') { 
                                        echo "class='info' "; 
                                    }
                                    echo "onclick='displayMail(\"$MessageID\", \"$Email\", \"$Title\", \"$Message\", \"$ReadMessage\");'>";
                        ?>          
                                           
                                                    
                                                    
                                                    <td><div class="name" style="min-width: 120px; display: inline-block;"><?php echo $Email ?></div></td>
                                                    <td><span class=""><?php echo $Title ?></span></td>
                                                    <td><span class="text-muted" style="font-size: 12px;">- <?php echo $previewmessage ?></span></td> 
                                                    <td><span class="badge"><?php echo $Timestamp ?></span></td> 
                                            </tr>
                                
                                
                            
                            
                            <?php }
                            } ?>
                                </table>
                                </div>
                                <hr>
                                
                                <p id='mailarea'><?php if ($sent == 'true') { echo "<div class='text-success'><i>Message Sent!</i></div>"; } else if ($sent == 'false') { echo "<div id='error'><i>Message could not be sent!</i></div>"; } ?></p>
                                
                            </div>
                        </div>
            <div class='row'>
                <span class="col-sm-3 col-md-2">
                </span>
                <span class="col-sm-9 col-md-10">
                    
                </span>
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