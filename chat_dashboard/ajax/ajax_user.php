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

require_once("../email/email.php");
$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);

if(isset($_POST['todo']))
$todo = $_POST['todo'];
	if($todo =='edit_user_html')
	{
		User::edit_user_html();
	}
	
	if($todo =='edit_viewers_html')
	{
		User::edit_viewers_html();
	}
	if($todo =='edit_slot_html')
	{
		User::edit_slot_html();
	}
	if($todo =='update_user_dtl')
	{
		User::update_user_dtl();
	}
	if($todo =='update_slot_dtl')
	{
		User::update_slot_dtl();
	}
	if($todo =='delete_user')
	{
		User::delete_user();
	}
	if($todo =='delete_slot')
	{
		User::delete_slot();
	}
	if($todo =='get_teacher_slot_html')
	{
		if($_POST['teacher_id'] == '')
		{
			echo 'Select teacher first';exit;
		}
		$slot = User::get_teacher_slot();
		if(count($slot) > 0)
		{
			echo '<ul class="slot-ul">';
			for($i=0; $i < count($slot); $i++)
			{
				?>
				<li><input type="radio" name="slot_id" value="<?php echo $slot[$i]['id'];?>" /><?php if($slot[$i]['empty_slot']==1) echo "No Timeslot";elseif($slot[$i]['coaching']==1) echo "Coaching";  else echo $slot[$i]['start_time'].'&nbsp;To&nbsp;'.$slot[$i]['end_time'];?></li>
				<?php 
			}
			echo '</ul>';
		}
		else
		{
			echo 'NO slot';exit;
		}
		//print_r($slot);
		
	}
	
	
	if($todo =='get_teacher_slot_html2')
	{
		if($_POST['teacher_id'] == '')
		{
			echo 'Select teacher first';exit;
		}
		$slot = User::get_teacher_slot();
		if(count($slot) > 0)
		{
			echo '<ul class="slot-ul">';
			for($i=0; $i < count($slot); $i++)
			{
				?>
				<li><input type="radio" name="sf_slot_id" value="<?php echo $slot[$i]['id'];?>" /><?php if($slot[$i]['empty_slot']==1) echo "No Timeslot";elseif($slot[$i]['coaching']==1) echo "Coaching"; else echo $slot[$i]['start_time'].'&nbsp;To&nbsp;'.$slot[$i]['end_time'];?></li>
				<?php 
			}
			echo '</ul>';
		}
		else
		{
			echo 'NO slot';exit;
		}
		//print_r($slot);
		
	}
	
	
	if($todo =='status_drop_list')
	{
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

        $std_id = $_REQUEST['student_id'];
		$status_id = $_REQUEST['status_id']; 
	?>
		<select style="font-size:12px;" id="span_change_status_<?php echo $std_id;?>" onchange="change_status_db('<?php echo $std_id;?>','<?php echo $status_id;?>')">
				 <?php
             $all_status = User::all_status();
			// print_r($all_user);
			  for($i_user = 0;$i_user < count($all_status);$i_user++)
			  {
				  ?>				 
                 <option value="<?php echo $all_status[$i_user]['id'];?>" <?php if($status_id == $all_status[$i_user]['id']) echo "selected=selected"; ?> <?php  if(($_SESSION['user_role'] == 'teacher' &&  $all_status[$i_user]['id'] >= 7 &&  $all_status[$i_user]['id'] !=9) || ($status_id == 7 && $_SESSION['user_role'] == 'teacher') || ($status_id == 8 && $_SESSION['user_role'] == 'teacher') ){ echo 'disabled="disabled"';} ?>> <?php echo $all_status[$i_user]['name'];?></option>
                  <?php
			  }
            	?>
        </select>
     <?php
		
	}
	if($todo =='change_status_db')
	{
		User::change_status_db();
	}
	if($todo =='search_teacher_html')
	{
		User::search_teacher_html();
	}
	if($todo =='update_row_bg')
	{
		User::update_row_bg();
	}
if($todo =='change_slot_status')
	{
		User::change_slot_status();
	}


?>