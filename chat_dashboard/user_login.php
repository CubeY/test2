<?php

/*define("LOG_SYS", DIR_LOG . "sys.log"); // System log file
define("DIR_LOG", INC_PATH . "logs/"); // Directory for logs
define("LOG_SAVE" , True); // Toggle logging on/off (True = Logs are on)*/
require_once("../../const.php");
function SaveLog($msg, $file=LOG_SYS) {

    if(LOG_SAVE) {
        $fp = fopen($file,'a');
        fputs($fp,"[" . date("Y/m/d H:i:s") . "] " . $msg . "\n");
        fclose($fp);
    }
}

$con=mysqli_connect("localhost","root","","okpanda_ios_users");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$con->set_charset("utf8");

$email = strtolower($_GET['email']);
$build = strtolower($_GET['build']);
$user_name = $_GET['user_name'];
$device_name = $_GET['device_name'];
$line_id = $_GET['line_id'];


// check if user is already registered
$query = mysqli_query($con,"SELECT * from users_list where line_id = '$line_id' and device_name = '$device_name'");
if(mysql_num_rows($query) > 0)
{
	$result = mysqli_query($con,"UPDATE users_list SET email = '$email' where line_id = '$line_id' and device_name = '$device_name'");
}
else
{
	$result = mysqli_query($con,"INSERT INTO users_list (email,build,user_name,device_name,line_id) VALUES ('$email','$build','$user_name','$device_name','$line_id')");
}


//echo {"success":'yes', "email":$email,"build":$build}

/*
if ($result)
{
	echo {"success":'yes', "email":$email,"version":$version}
}
else
{
	echo {"success":'yes', "email":$email,"version":$version}
}
*/

mysqli_close($con);
?>
