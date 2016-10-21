
<link rel="stylesheet" type="text/css" href="assets\css\main.css">
<html>
<head>
<div id ="heading">
     <nav class="navbar navbar-default navbar-static-top">


        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class=><a href="index.php">Home</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">

<?php
session_start();
if(isset($_SESSION['priv'])){
    echo " <li> Hello ". $_SESSION['name']."</li> <br>";
    if($_SESSION['priv'] == '0'){
        echo '<li> <a href = "profile.php"> View Profile </a></li> ';
    }
	else{
		echo '<li> <a href ="admin.php"> Admin Page </a></li>';
		
	}
	echo '<br> <li> <a href = "log_out.php"> Log out </a></li> ';
}

else{
	echo '<li> <a href = "user_login.php"> Sign in </a></li> ';
	echo '<br> <li> <a href = "user_register.php"> Sign up </a></li>';
	
	
}

?>
               
            </ul>
        </div><!--/.nav-collapse -->
    </nav>


</div>
</head>



</html>





