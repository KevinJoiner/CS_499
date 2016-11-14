
<link rel="stylesheet" type="text/css" href="assets\css\main.css">
<html>
<head>
    <div id ="heading">
        <nav class="navbar navbar-default navbar-static-top">

            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="index.php"><img src="assets/images/logo.png" alt="SQS logo" width="42" height="42"></a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">

                    <?php
                    session_start();
                    if(isset($_SESSION['priv'])){
                        echo " <li> Hello ". $_SESSION['name']."</li> <br>";
                        echo '<li> <a href = "profile.php"> View Profile </a></li> ';
                        if($_SESSION['priv'] == '1'){

                            echo '<li> <a href ="admin_user_info.php"> Admin Page </a></li>';

                        }
                        echo '<li> <a href = "log_out.php"> Log out </a></li> ';
                    }

                    else{
                        echo '<li> <a href = "user_login.php"> Sign in </a></li> ';
                        echo '<li> <a href = "user_register.php"> Sign up </a></li>';


                    }

                    ?>

                </ul>
            </div><!--/.nav-collapse -->
            <div class="sqs_blue_line">
                <br>
            </div><!--sqs_blue_line-->
        </nav>


    </div>
</head>



</html>





