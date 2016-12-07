
<?php
/**
 * Created by PhpStorm.
 * User: Kevin Joiner
 * Date: 10/9/2016
 * Time: 2:56 AM
 */

function Sanitation_level($RawData, $level = 0, $type = normal){
    if ($level == 0)
        $data = RawData;
    elseif ($level == 1){
        $data = mysqli_real_escape_string($RawData);
    }
    elseif ($level==2){
        if ($type == "name") {
            if (preg_match('%^[A-Za-z\.\'\-]{2,15}$%', stripslashes(trim($RawData)))) {

            }
        }
        if ($type == "password") {
            if (preg_match('%^[A-Za-z\.\'\-]{2,15}$%', stripslashes(trim($RawData)))) {

            }
        }
    }

    return $data;
}


?>

