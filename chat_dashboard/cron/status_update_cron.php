<?php

/*======================================================================**
**                                                                           
** Main entry point.
** All PHP (mode) calls will go through this script.  
**                                                                           
**======================================================================*/

// Turn on warnings
error_reporting(E_ALL);
ini_set('display_errors', 'on');
chdir(dirname(__FILE__));
require_once("../../../const.php");
//require_once(__DIR__ . "/css/style.css");
require_once(INC_PATH."common.php");
require_once(DIR_CLASSES . "class_db_this.php");
require_once("../php_classes/class_user.php");
require_once("../php_classes/class_student.php");
require_once("../php_classes/class_status.php");
date_default_timezone_set("Asia/Tokyo");

$date = date("Y-m-d H:i:s");


	$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
	
	//Unless manually changes it,
	//the status should be automatically changed to
	// "F.T. expired"  after expiration date	
	$sql = "SELECT id FROM `users_list` WHERE DATEDIFF(`ft_expiration`,'".$date."') < 0 AND status  NOT IN (9,7,8,6,2) AND id NOT IN ( SELECT `user_id` FROM `statuses_change_log` WHERE chenged_by = '')"; //check for ft_expired 
	$q = $db->doQuery($sql);
	while($row = mysql_fetch_assoc($q)) {  
		
		update_status_on_expiry($row['id']);
	   
	}
	//----------------------------------------------------------------------------
	
	//Unless manually modified, 
	//student status should automatically changed from 
	//"initial message sent" (id=1) to "No response to initial message after 2 days" (id=2) 
	//2 days after Join Date
	$sql_day = "SELECT id,status FROM `users_list` WHERE DATEDIFF('".$date."',`created_on`) > 2 AND status = 1 AND status !=2 AND status !=9 AND id NOT IN ( SELECT `user_id` FROM `statuses_change_log`  WHERE chenged_by = '')"; //check for two days after initial msg sent 
	
	$q_day = $db->doQuery($sql_day);
	while($row_day = mysql_fetch_assoc($q_day)) {  
		
		update_status_after_two_days_of_initial_msg_sent($row_day['id']);
	   
	}
	//----------------------------------------------------------------------------
	

//the auto-status change of student to "FT expired" should happen not on
// the date of expiration date but a day after it, which is the 8th day since First response
	$sql_day = "SELECT id FROM `users_list` WHERE DATEDIFF('".$date."',`first_response`) > 6 AND status NOT IN(9,8,7,6,2) AND id NOT IN ( SELECT `user_id` FROM `statuses_change_log`  WHERE chenged_by = '')"; 
	
	$q_day = $db->doQuery($sql_day);
	while($row_day = mysql_fetch_assoc($q_day)) {  
		
		update_after_eight_day_from_first_response($row_day['id']);
	   
	}
	//----------------------------------------------------------------------------
	
	//"No response to initial message after 2 days" 
	//should be automatically sent to "F.T. expired without response" 
	//on 8th day from Initial message sent 
	//without changing row color or blinking
	$sql_day = "SELECT id,status FROM `users_list` WHERE DATEDIFF('".$date."',`created_on`) > 6 AND  status = 2 "; 
	
	$q_day = $db->doQuery($sql_day);
	while($row_day = mysql_fetch_assoc($q_day)) {  
		
		update_status_after_eight_days_of_no_response_to_initial_msg($row_day['id']);
	   
	}
	//----------------------------------------------------------------------------
	
	$sql_day = "SELECT teacher_slots.id as slot_id FROM teacher_slots JOIN teachers ON teacher_slots.teacher_id=teachers.user_id"; 
	$q_day = $db->doQuery($sql_day);
	while($row_day = mysql_fetch_assoc($q_day)) {  
		User::update_available_line_url_shows($row_day['slot_id']);	   
	}
	

function update_status_on_expiry($student_id)
{
	$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
	$student_old_status = Student::getStudentStatusId($student_id);
			 
	$sql2 = "UPDATE `users_list` SET status = 6 WHERE id = ".$student_id." AND status != 7 AND status != 8  AND status !=2"; 
	$db->doQuery($sql2);
	
	$slot_id = User::getStudentSlotId($student_id);
	User::update_available_line_url_shows($slot_id);
	User::insert_change_status_log($student_id,6,$student_old_status,$cron_update= true);
}

function update_status_after_two_days_of_initial_msg_sent($student_id)
{
	$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
	$student_old_status = Student::getStudentStatusId($student_id);
			 
	$sql2 = "UPDATE `users_list` SET status = 2 WHERE id = ".$student_id." AND status = 1 AND status !=2"; 
	
	$db->doQuery($sql2);
	$slot_id = User::getStudentSlotId($student_id);
	User::update_available_line_url_shows($slot_id);
	User::insert_change_status_log($student_id,2,$student_old_status,$cron_update= true);
}



//the auto-status change of student to "FT expired" should happen not on
// the date of expiration date but a day after it, which is the 8th day since First response
 function update_after_eight_day_from_first_response($student_id)
{
	$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
	$student_old_status = Student::getStudentStatusId($student_id);
	
	$sql= "UPDATE users_list  SET `status`= '6' WHERE id = ".$student_id." AND status != 7 AND status != 8 AND status !=2";
	$q = $db->doQuery($sql);
	$slot_id = User::getStudentSlotId($student_id);
	User::update_available_line_url_shows($slot_id);
	User::insert_change_status_log($student_id,6,$student_old_status,$cron_update= true);	
}

 function update_status_after_eight_days_of_no_response_to_initial_msg($student_id)
{
	$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
	$student_old_status = Student::getStudentStatusId($student_id);
	
	$sql= "UPDATE users_list  SET `status`= '4' WHERE id = ".$student_id." AND status != 7 AND status != 8 AND status !=6 AND status = 2 AND status !=4";
	$q = $db->doQuery($sql);
	$slot_id = User::getStudentSlotId($student_id);
	User::update_available_line_url_shows($slot_id);
	User::insert_change_status_log($student_id,4,$student_old_status,$cron_update= true);	
}