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
require_once(DIR_CLASSES . "class_db_this.php");
require_once("./php_classes/class_student.php");

$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);

echo '<pre>';
$sql_slct = "SELECT id,name FROM user_master WHERE `role` = 'teacher' OR `role` = 'administrator,teacher'";
$q_slct =  $db->doQuery($sql_slct);
echo 'TOTAL='.mysql_num_rows($q_slct);
while($row = mysql_fetch_assoc($q_slct))
{
	 $tid = $row['id'];
	echo '<br />';echo $row['id'].'---'.$row['name'].'<br />';
	$slotSql =  "SELECT id FROM `teacher_slots` WHERE `teacher_id` = ".$tid." AND `coaching`  = 1";
	$q =  $db->doQuery($slotSql);
	if(mysql_num_rows($q) > 0)
	{
		//echo '<br />';echo $tid ;
	}
	else
	{
		echo $create = "INSERT INTO `teacher_slots` (`teacher_id`, `start_time`, `end_time`, `active`, `max_students`, `available_line_url_shows`, `empty_slot`,`coaching`) VALUES('".$tid."', '00:00:00', '00:00:00','1','8','80','0','1')";
		$db->doQuery($create);
	}
	
		
		
	
}

?>