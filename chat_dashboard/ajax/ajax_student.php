<?php
/*======================================================================**
**                                                                           
** Page:Ajax user , handel user ajax call 
** Created By : Bidhan
**                                                                           
**======================================================================*/
?>
<?php
// Turn on warnings
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require_once("../../../const.php");
//require_once(__DIR__ . "/css/style.css");
require_once(INC_PATH."common.php");
require_once(DIR_CLASSES . "class_db_this.php");
require_once("../php_classes/class_user.php");
require_once("../php_classes/class_student.php");
require_once("../php_classes/class_status.php");
require_once("../php_classes/class_plan.php");
require_once("../php_classes/class_subscription.php");
require_once("../email/email.php");
$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);

if(isset($_POST['todo']))
$todo = $_POST['todo'];

	if($todo =='edit_user_html')
	{
		Student::edit_user_html();
	}
	if($todo =='update_user_dtl')
	{
		Student::update_user_dtl();
	}
	if($todo =='delete_user')
	{
		Student::delete_user();
	}
	if($todo =='search_student_html')
	{
		Student::search_student_html();
	}
	if($todo =='check_student_add')
	{
		$slot_id = $_REQUEST['slot_id'];
		
		$active_student = User::count_total_active_student_byslot($slot_id);
		
		$max_student = User::getTeacherMaxStudentBySlotId($slot_id);
		
		if($max_student > $active_student)
		{
			echo "success";
		}
		else
		{
			echo "error";
		}
		
	}
	if($todo =='check_for_background_color')
	{
		$student_id = $_REQUEST['uid'];
		$ft_expiration = $_REQUEST['ft_expiration'];
		$status = $_REQUEST['status'];
		$pay_info_sent = $_REQUEST['pay_info_sent'];
		$class = 'whiteBG';
		
		if(!empty($ft_expiration))
		$class = User::check_day_before_expiration($ft_expiration,$student_id,$status,$pay_info_sent);
		
		
		echo $class;
		
		
	}
	if($todo =='change_status_log_user')
	{
		$student_id = $_REQUEST['uid'];
		
		Status::status_change_log_per_student($student_id);
		
	}
	
    if($todo =='send_email_noti_new_paying_user')
	{
		
		if($_SERVER['HTTP_HOST'] == 'fugupanda.com' || $_SERVER['HTTP_HOST'] == 'www.fugupanda.com')
		{ 
			//exit; // stop email from test server 
		

		$student_id = $_REQUEST['uid'];
		$new_status_id = $_REQUEST['status'];
		
		if($new_status_id==7 || $new_status_id == 8)
		send_email_noti_new_paying_user($student_id,$new_status_id);
		
		}
		
	}



	
?>