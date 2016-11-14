

<?php include 'config/header.php';
require_once('../sql_connector.php');?>


<?php
if (isset($_SESSION['user'])){
    header('location:index.php');
}

if(isset($_POST['submit'])) {
    $EmailError = False;
    $passwordError = False;
    $NameError = False;

    if (preg_match('%[A-Za-z0-9\.\-\$\@\$\!\%\*\#\?\&]%', stripslashes(trim($_POST['password'])))) {
        $password = $mysqli->real_escape_string(trim($_POST['password']));
        $password  = hash("sha256", $password);
    }
    else {
        $passwordError = True;
    }
    if (preg_match('%[A-Za-z0-9]+@+[A-Za-z0-9]+\.+[A-Za-z0-9]%', stripslashes(trim($_POST['email'])))) {
        $email = $mysqli->real_escape_string(trim($_POST['email']));
    }
    else {
        $EmailError = True;
    }

    if (preg_match('%[A-Za-z]%', stripslashes(trim($_POST['name'])))) {
        $name = $mysqli->real_escape_string(trim($_POST['name']));
    }
    else {
        $NameError = True;
    }


    if ($passwordError == False and $EmailError == False and $NameError == False) {
        $query = "Insert INTO user (Name,Password,Email) VALUES (?,?,?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sss",$name,$password,$email);
        $stmt->execute();
        $results = $stmt->fetch();
        if ($mysqli->affected_rows == 1) {
            session_start();
            $stmt2 = $mysqli->prepare('SELECT UID FROM user WHERE email = ?');
            $stmt2->bind_param("s", $email);
            $stmt2->bind_result($UID);
            $stmt2->execute();
            $_SESSION['priv'] = '0';
            $_SESSION['user'] = $UID;
            $_SESSION['name'] = $name;
            header('location:index.php');
        }
        else {
            echo "Darn! that email is taken :( Try another!";
        }

    }
    else {
        echo "Invalid Credentials please try again";
    }
}
?>
<html>
<form  class= "form-horizontal"action="" method="post">


        <div class="form-group">
            <label class="control-label col-sm-5" >Name</label>
            <div class="col-sm-7">
            <input type="text" name="name" size="30" /></label>
                </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-5">Password</label>
            <div class="col-sm-7">
            <input type="password" name="password" size="30" /></label>
                </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-5">Email</label>
            <div class="col-sm-7">
            <input type="email" name="email" size="30" </label>
            </div>
        </div>


        <div class="form-group">
            <div class="control-label col-sm-6">
            <input class="btn btn-default" type="submit" name="submit" value="Register"/></label>
            </div>
        </div>



</form>






<?php
/**
 * Created by PhpStorm.
 * User: Kevin Joiner
 * Date: 4/3/2016
 * Time: 3:05 AM
 */

?>
</html>
