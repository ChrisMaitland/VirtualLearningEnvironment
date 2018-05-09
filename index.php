<?php
    
    include('connections/connection.php');
    
    if (isset($_GET['loginerror'])) {
        $loginerror = $_GET['loginerror'];
    } else {
        $loginerror = "";
    }
    
    if (isset($_GET['updated'])) {
        $pwupdated = $_GET['updated'];
    } else {
        $pwupdated = "";
    }
    
?>

<!DOCTYPE html>
<head>
    <title>Travel Lingo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
    <link href='https://fonts.googleapis.com/css?family=Chelsea Market' rel='stylesheet'>
    <link rel="stylesheet" href="design/style.css" />
    <link rel="icon" href="img/logolarge.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script>
        $('.carousel').carousel('cycle');
    </script>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top foot ">

        <div class="container foot">

            <div class="navbar-header">


                <a href="index.php" class="navbar-brand navbar-left ">
                            <img id='imgbanner' src="img/logolarge1.png" class="img-responsive title"></a>
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>                        
                                </button>
            </div>

            <div id="myNavbar" class="navbar-collapse collapse">
                <div class="navbar-form navbar-right ">
                    <form action='admin/signin.php' method='POST'>

                            
                            <span class="form-group ">
                                <input type="email" placeholder="Email" class="form-control" name="Email" required/>
                            </span>

                            <span class="form-group">
                                <input type="password" name="pw" placeholder="Password" class="form-control"/>
                            </span>

                            <button type="submit" class="btn btn-success" name='sign'>Sign in</button>

                            <a href="admin/register.php"><button type='button' class="btn btn-success" name='register'>Register</button></a>
                            
                    </form>
                    <div id='pw'><a href="forgotten.php"> Forgotten password? </a></div>
                        <?php
                            if ($loginerror) {
                                echo "<p><div id='error'><i>Email or Password incorrect!</i></div></p>";
                            }
                            if ($pwupdated) {
                                echo "<p><div class='text-success'><i>Password Updated!</i></div></p>";
                            }
                        ?>
                </div>
            </div>

        </div>
    </nav><!--/.navbar-collapse -->
    
  <!--  <section class="s1"> -->
        <div class="container s1">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12" >
                <div id="carousel-example-generic" class="carousel slide " data-ride="carousel">
                    <ol class="carousel-indicators" style="overflow: auto;">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                    </ol>
                    
                    <div class="carousel-inner " role="listbox" >
                        <div class="item active">
                            <img class="img-responsive" src="img/logolarge.png" alt="TravelLingo">
                            <div class="carousel-caption">
                                <h3 style="float: right;">Welcome to Travel Lingo</h3>
                            </div>
                        </div>
                        <div class="item">
                            <img src="img/languages.jpg" alt="Languages" style="object-fit: cover; min-width: 100%; min-height: 100%;">
                            <div class="carousel-caption">
                                
                            </div>
                        </div>
                        <div class="item">
                            <img src="img/wood.jpg" alt="bkg" style="object-fit: cover; min-width: 100%; min-height: 100%;">
                            <div class="carousel-caption">
                                <h2>Sign up today and learn at your own pace!</h2>
                                <ul>
                                    <li>Qualified Teachers</li>
                                    <li>Easy to pick up Lessons</li>
                                    <li>Completely free!</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
        </div>
   <!-- </section>-->
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
            <img src="img/learningbg1.jpg" class="img-responsive" style="object-fit: cover; size: 100%;" />

            </div>
            <div class="col-xs-12 col-sm-6">
            <img src="img/travel1.jpg" class="img-responsive"  style="object-fit: cover; size: 100%;"/>
            </div>
        </div>
    </div>
	
    <footer class="navbar-fixed-bottom foot">
        <div class="container pad pad2">Travel Lingo - Chris Maitland - 2018</div>
    </footer>
</body>