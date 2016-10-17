

<?php include 'config/header.php'; ?>
<html>
<form  class= "form-horizontal"action="Processes/register.php" method="post">


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
            <input class="btn btn-default" type="submit" name="submit" value="Send"/></label>
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
