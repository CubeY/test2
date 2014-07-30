<?php
/*======================================================================**
**                                                                           
** Page:User , manage all users
** Created By : Bidhan
**                                                                           
**======================================================================*/
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require_once("../../const.php");
require_once("../../common.php");
date_default_timezone_set("Asia/Tokyo");
require_once(DIR_CLASSES . "class_db_this.php");
require_once("./php_classes/class_student.php");

$date = date("Y-m-d H:i:s");

/*$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
$sql = "Update users_list SET unique_user_id=''";
$q =  $db->doQuery($sql);*/
$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
$sql_slct = "SELECT * FROM users_list WHERE LENGTH(unique_user_id) != 5";
$q_slct =  $db->doQuery($sql_slct);
$num_rows = mysql_num_rows($q_slct);
$found = 0;
if($num_rows > 0)
{ 
	while($row = mysql_fetch_assoc($q_slct))
	{
		$unique_user_id = Student::generateRandomString();
		$old_unique_user_id = $row['unique_user_id'];
		$user_id = $row['id'];
		$sql_updt = "UPDATE users_list SET unique_user_id='".$unique_user_id."' WHERE `id`='".$row['id']."'";
		if($db->doQuery($sql_updt))
		{
			$sql_insrt = "INSERT INTO `unique_user_id_change_log`(`user_id`, `old_id`, `new_id`, `date`) VALUES ('".$user_id."','".$old_unique_user_id."','".$unique_user_id."','".$date."')";
			$db->doQuery($sql_insrt);
			$found++;
		}
		else
		{
			echo "Query Update Failed";
		}
		
		
		
	}
	if($found > 0)
	{
		echo $found.' unique user id\'s effected';
	}
}
else
{
	echo "NO DATA UPDATED";
}

?>