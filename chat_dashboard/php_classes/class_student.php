<?php

/*===========================================================================**
** Class : User
** Created By ; Bidhan
** Utility functions to access MySQL database
** 
**===========================================================================*/
error_reporting(E_ALL & ~E_STRICT);
    
 class Student {
    // private
    private $initialized = false;
    private $conn;
    private $debugOn = false;
    private function __construct($server, $username, $password, $dbName) {
        $this->conn = @mysql_connect($server, $username, $password, true) or die(mysql_error());
        // Make sure that everything is UTF8 encoded so we don't have any character gibberish in the DB
        $charsetSQL = "SET NAMES utf8;set character_set_server = utf8;";
        mysql_set_charset("UTF8", $this->conn);
        mysql_query($charsetSQL, $this->conn);

        @mysql_select_db($dbName,$this->conn)or die(mysql_error());
        $this->initialized = true;
		
    }
    private static function escapeForSQL($str) {
        return mysql_real_escape_string($str);
    }
    
    public function add_new_student()
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$f_name = $_POST['f_name'];
		$l_name = $_POST['l_name'];
		$email = $_POST['email'];
		$pay_email = $_POST['pay_email'];
		$line_id = $_POST['line_id'];
		
		$slot_id =  $_POST['slot_id'];
		$first_response =  $_POST['first_response'];
		$first_response==''?$first_response='0000-00-00 00:00:00':0;
		$ft_expiration =  $_POST['ft_expiration'];
		$ft_expiration==''?$ft_expiration='0000-00-00 00:00:00':0;
		$created_on  = date('Y-m-d H:i:s');
		$teacher_id  = $_POST['teacher_id'];
		//$unique_user_id = $_POST['unique_user_id'];
		$status = $_POST['status'];
		$plan = $_POST['plan'];
		$english_name = $_POST['english_name'];
		$payment_method = $_POST['payment_method'];
		$comment = $_POST['comment'];
		$pay_info_sent = isset($_POST['pay_info_sent']) ? $_POST['pay_info_sent'] : 0;
		$first_paid_date =  $_POST['first_paid_date'];
		$first_paid_date==''?$first_paid_date='0000-00-00 00:00:00':0;
		$paid_through = $_POST['paid_through'];
		$paid_through == ''?$paid_through = '0000-00-00 00:00:00':0;
		$engagement_level =  $_POST['engagement_level'];
		$life_status =  $_POST['life_status'];
		$gender =  $_POST['gender'];
		$dob =  $_POST['dob'];
		$funnel = $_POST['funnel'];
		//$age =  $_POST['age'];
    
	
		
		/*print_r($_POST);
		die;*/

		$unique_user_id = Student::generateRandomString();

		$sql= "INSERT INTO users_list (`slot`,`email`,`paypal_email`,`f_name`, `l_name`,`english_name`,`line_id`,`created_on`, `first_response`, `ft_expiration`,`unique_user_id`,`status`,`plan`,`payment_method`,`comment`,`payment_info_sent`,`first_paid_date`,`paid_through`,`engagement_level`,`life_status`,`gender`,`dob`,`funnel`) VALUES('$slot_id','$email','$pay_email','$f_name','$l_name','$english_name','$line_id','$created_on','$first_response','$ft_expiration','$unique_user_id','$status','$plan','$payment_method','$comment','$pay_info_sent','$first_paid_date','$paid_through','$engagement_level','$life_status','$gender','$dob','$funnel')";
		$q = $db->doQuery($sql);
		$user_id = mysql_insert_id();
		
		//$sql_p= "INSERT INTO user_permission (`user_id`, `permission`) VALUES('$user_id','$permission')";
		//$db->doQuery($sql_p);
		User::update_available_line_url_shows($slot_id);
		Student::checkMaxStudent_CurrentStudent($slot_id);
		
		$ur = 'teacher_student.php?slot='.$slot_id.'&teacher_id='.$teacher_id;
		
		if(isset($_POST['action_from']) && $_POST['action_from'] == 'admin')
		$ur = 'students.php?t=er';
		//die;
		if($q)
		{
			header('location:'.$ur.'&msg=success');exit;
		}
		else
		{
			header('location:'.$ur.'&msg=error');exit;
		}
		
		
		
		
	}
	
	
	
	public function all_student($slot=false,$teacher_id = false)
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$result = array();
		
		$sql= "SELECT * FROM  users_list";
		if($slot)
		{
			$sql .=" WHERE slot = ".$slot."";
		}
		if($teacher_id)
		{
			$slots = Student::getteacherSlotDtl($teacher_id);
			if(count($slots) > 0)
			{
				for($si=0;$si<count($slots);$si++)
				{
					$sid[]= $slots[$si]['id'];
				}
				$sidStr = implode(',',$sid);
				$sql .=" WHERE slot IN( ".$sidStr.")";
			}
			else{
				return $result;
			}
			
		}
		//die;
		$sql .=" ORDER BY id DESC LIMIT 100";
		//echo $sql;
		$q = $db->doQuery($sql);
		
		while($row = mysql_fetch_assoc($q)) {  
			$result[] = $row;
           
        }
		return $result;
		
	}
	
	public function all_studentCount($col = false,$status = false)
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$result = array();
		$sql= "SELECT COUNT(id) AS total FROM  users_list ";
		if($col)
		{
			if($col == 'payment_info_sent')
			$sql .= " WHERE `".$col."` = '1'  ";
			else
			$sql .= " WHERE `".$col."` != '0000-00-00 00:00:00'  ";
		}
		elseif($status)
		{
			$sql .= " WHERE `status` = ".$status." ";
		}
		//echo $sql;
		$q = $db->doQuery($sql);
		$row = mysql_fetch_assoc($q);
		return $result = $row['total'];
		
	}
	
	
	
	
	public function slotDtl($slotId)
	{
		if($slotId > 0) {
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$sql= "SELECT TS.start_time,TS.end_time ,TS.active,TS.max_students,TS.num_line_url_shown,TS.available_line_url_shows,TS.empty_slot,TS.coaching,U.id,U.name FROM user_master AS U LEFT JOIN teacher_slots AS TS  ON (U.id=TS.teacher_id) WHERE TS.id = ".$slotId;
		//echo $sql;
		$q = $db->doQuery($sql);
		
		$row = mysql_fetch_assoc($q);
           
       
		return $row;
		}
		return false;
		
	}
	
	
	public function singleUserDetails($uid)
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$sql= "SELECT * FROM  users_list WHERE id = ".$uid."";
		$q = $db->doQuery($sql);
		
		$row = mysql_fetch_assoc($q);
		return $row;
		
	}
	
	public function getStudentSlotId($student_id)
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$sql= "SELECT slot FROM  users_list WHERE id = ".$student_id."";
		$q = $db->doQuery($sql);
		
		$row = mysql_fetch_assoc($q);
		return $row['slot'];
		
	}
	
	public function getStudentTeachersDtl($student_id)
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$slotId = Student::getStudentSlotId($student_id);
		$sql= "SELECT name,email FROM user_master AS um INNER JOIN teacher_slots as ts ON um.id=ts.teacher_id WHERE ts.id = ".$slotId." ";
		$q = $db->doQuery($sql);
		$row = mysql_fetch_assoc($q);
		
		return $row;
		
	}
	
	
	public function getStudentStatusId($student_id)
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$sql= "SELECT status FROM  users_list WHERE id = ".$student_id."";
		$q = $db->doQuery($sql);
		
		$row = mysql_fetch_assoc($q);
		return $row['status'];
		
	}
	
	
	public function getteacherDtl($uid)
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$sql= "SELECT * FROM  teachers WHERE teacher_id = ".$uid."";
		$q = $db->doQuery($sql);
		
		$row = mysql_fetch_assoc($q);
		return $row;
		
	}
	
	public function getteacherSlotDtl($uid)
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		 $sql= "SELECT `id`,`start_time`, `end_time`, `active`  FROM  teacher_slots WHERE teacher_id = ".$uid."";
		
		$q = $db->doQuery($sql);
		$result = array();
		while($row = mysql_fetch_assoc($q)) {  
			$result[] = $row;
           
        }
		
		return $result;
		
		
	}
	
	public function getteacherNoSlotDtl($teacher_id)
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		 $sql= "SELECT `id`  FROM  teacher_slots WHERE teacher_id = ".$teacher_id." AND empty_slot = 1 ";
		
		$q = $db->doQuery($sql);
		$result = array();
		$row = mysql_fetch_assoc($q);
		$res = $row['id'];
		return $res;
		
		
	}
	
	public function all_funnels()
	{
		$funnels_array = array(1=>"App", 2=>"Web", 3=>"Other", 4=>"Unknown", 5=>"Old app");
		return $funnels_array;
	}
	public function edit_user_html()
	{
		session_start();
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		  
		$uid = $_POST['uid'];
		$sql= "SELECT * FROM  users_list WHERE id = ".$uid."";
		
		$q = $db->doQuery($sql);
		$row = mysql_fetch_assoc($q);
		
		//print_r($row);
		?>
        
      <form name="edit_usr_frm_<?php echo $row['id'];?>" id="edit_usr_frm_<?php echo $row['id'];?>" action="" method="post" >
        <input type="hidden" name="todo"  value="update_user_details"/>
<div class="create_new_cls" >
    <div class="single_row">
        <div class="label_u">First Name:</div>
        <div><input type="text" class="" name="u_f_name" value="<?php echo $row['f_name'];?>" /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Last name:</div>
        <div><input type="text" class="" name="u_l_name" value="<?php echo $row['l_name'];?>" /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">English name:</div>
        <div><input type="text" class="" name="u_english_name" value="<?php echo $row['english_name'];?>" /></div>
    </div>
    <?php if($_SESSION['user_role'] == 'administrator'){ ?>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Contact Email:</div>
        <div><input type="text" class="" name="u_email"  value="<?php echo $row['email'];?>" /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Paypal Email:</div>
        <div><input type="text" class="" name="u_pay_email" value="<?php if(!empty($row['paypal_email'])) echo $row['paypal_email'];?>" /></div>
    </div>
    <?php } ?>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Line ID:</div>
        <div><input type="text" class="" name="u_line_id"  value="<?php echo $row['line_id'];?>" /></div>
    </div>
    <!--<div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Unique User ID:</div>
        <div><input type="text" class="required " name="unique_user_id"  value="<?php echo $row['unique_user_id'];?>" /></div>
    </div>-->
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Select Teacher:</div>
        <div>
        <?php  $slotDtl = Student::slotDtl($row['slot']);
		//print_r($slotDtl);
		?>
        <select id="teacher_id_<?php echo $row['id'];?>"  onchange="getTeacherSlotAjax(<?php  echo $row['id'];?>);" >
        <option value="">All Teachers</option>
         <?php
		  $all_user = User::all_user('teacher','',$show_all=true);
		 // print_r($all_user);
		  for($i_user = 0;$i_user < count($all_user);$i_user++)
		  {
			  ?>
			 <option value=" <?php echo $all_user[$i_user]['id'];?>" <?php if($slotDtl['id'] ==$all_user[$i_user]['id']) echo 'selected="selected"';?> > <?php echo $all_user[$i_user]['name'];?></option>
			  <?php
		  }
		?>
        </select>
        </div>
    </div>
   <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Select Slot:</div>
        <div id="dv_teacher_slot_<?php  echo $row['id'];?>">
        <?php
       if($row['slot'] > 0){
	    $slot = Student::get_teacher_slot($slotDtl['id']);
		if(count($slot) > 0)
		{
			echo '<ul class="slot-ul">';
			for($i=0; $i < count($slot); $i++)
			{
				?>
				<li><input type="radio" name="slot_id" value="<?php echo $slot[$i]['id'];?>" <?php if($slot[$i]['id']==$row['slot']) echo 'checked="checked"';?> /><span id="span_u_<?php echo $uid; ?>_slot_<?php echo $slot[$i]['id'];?>"><?php if($slot[$i]['empty_slot']==1) echo "No Timeslot";elseif($slot[$i]['coaching']==1) echo "Coaching"; else echo $slot[$i]['start_time'].'&nbsp;To&nbsp;'.$slot[$i]['end_time'];?></span></li>
                <input type="hidden" id="show_list_strt_time_<?php echo $uid; ?>_<?php echo $slot[$i]['id'];?>" value="<?php echo substr($slot[$i]['start_time'],0,5); ?>">
                <input type="hidden" id="show_list_end_time_<?php echo $uid; ?>_<?php echo $slot[$i]['id'];?>" value="<?php echo substr($slot[$i]['end_time'],0,5); ?>">
                <input type="hidden" id="slot_type_<?php echo $uid; ?>_<?php echo $slot[$i]['id'];?>" value="<?php if($slot[$i]['empty_slot']==1) echo "NTS";elseif($slot[$i]['coaching']==1) echo "Coaching"; else echo 'TIME'?>">
                
				<?php 
			}
			echo '</ul>';
		}
		else
		{
			echo 'NO slot';
		}
	   }
	   else 
	   {
		   echo 'NO slot';
	   }
		
		?>
        
        </div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Join Date:</div>
        <div><input type="text" class="" name="created_on"  value="<?php echo $row['created_on'];?>" id="created_on_<?php echo $row['id'];?>"/></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">First response:</div>
        <div><input type="text" class="" name="first_response"  value="<?php  if($row['first_response']!="0000-00-00 00:00:00") { echo $row['first_response']; } else echo '';?>" id="first_response_<?php echo $row['id'];?>"/></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Last day of F.T.:</div>
        <div><input type="text" class="" name="ft_expiration" value="<?php if($row['first_response']!="0000-00-00 00:00:00") { echo $row['ft_expiration'];  } else echo '';?>" id="ft_expiration_<?php echo $row['id'];?>" /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">First Paid Date:</div>
        <div><input type="text" class="" name="first_paid_date" value="<?php if($row['first_paid_date']!="0000-00-00 00:00:00") { echo $row['first_paid_date'];  } else echo '';?>" id="first_paid_date_<?php echo $row['id'];?>" /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Paid through:</div>
        <div><input type="text" class="" name="paid_through" id="paid_through_<?php echo $row['id'];?>" value="<?php if($row['paid_through']!="0000-00-00 00:00:00") { echo $row['paid_through'];  } else echo '';?>" /></div>
    </div>
    <?php if($_SESSION['user_role'] == 'administrator')
	{ ?>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Cancellation Date:</div>
        <div><input type="text" class="" name="cancellation_date"  value="<?php if($row['cancellation_date']!="0000-00-00 00:00:00") { echo $row['cancellation_date'];  } else echo '';?>"id="cancellation_date_<?php echo $row['id'];?>" /></div>
    </div>
    <?php } ?>
    <div class="clear"></div>
     <!--<div class="single_row">
        <div class="label_u">Status:</div>
        <div>Active<input type="radio" <?php if($row['status'] == 'Y' ) echo 'checked="checked"';?>  name="status" value="Y"/>&nbsp;&nbsp;Inactive<input type="radio" <?php if($row['status'] == 'N' ) echo 'checked="checked"';?>name="status" value="N" /></div>
    </div>-->
    <input type="hidden"  id="old_status_db" value="<?php echo $row['status']?>" />
    <div class="single_row">
        <div class="label_u">Status:</div>
        <div>
        <select id="status_<?php  echo $row['id'];?>" name="status">
         <?php
     $all_status = User::all_status();
 // print_r($all_user);
  for($i_user = 0;$i_user < count($all_status);$i_user++)
  {
	  ?>
	 <option value="<?php echo $all_status[$i_user]['id'];?>" <?php if($row['status'] == $all_status[$i_user]['id']) echo 'selected="selected"'; else echo '';?> <?php  if(($_SESSION['user_role'] == 'teacher' &&  $all_status[$i_user]['id'] >= 7  && $all_status[$i_user]['id'] != 9) || ($row['status'] == 7 && $_SESSION['user_role'] == 'teacher') || ($row['status'] == 8 && $_SESSION['user_role'] == 'teacher')){ echo 'disabled="disabled"';} ?>> <?php echo $all_status[$i_user]['name'];?></option>
	  <?php
  }
	?>
        </select>
        </div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Payment info Sent:</div>
        <div ><input type="checkbox" id="pay_info_sent_id_<?php echo $row['id'];?>" name="pay_info_sent" <?php if($row['payment_info_sent']==1) echo 'checked="checked"' ;?> value="1" /></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Plan:</div>
		 <!--<div><input type="radio" name="plan" value="all_week" <?php if($row['plan']=='all_week') echo 'checked="checked"';?> />All week &nbsp;<input type="radio" name="plan" value="weekend" <?php if($row['plan']=='weekend') echo 'checked="checked"';?> />Weekend &nbsp;<input type="radio" name="plan" value="midweek" <?php if($row['plan']=='midweek') echo 'checked="checked"';?> />Midweek</div>-->
        <div><?php echo Plan::populate_plans_select_option('','plan_'.$row['id'],$row['plan']);?></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Payment method:</div>
        <div><input type="radio" name="payment_method" value="Paypal" <?php if($row['payment_method']=='Paypal') echo 'checked="checked"';?>/>Paypal &nbsp;<input type="radio" name="payment_method" value="Braintree" <?php if($row['payment_method']=='Braintree') echo 'checked="checked"';?> />Braintree</div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Engagement Level:</div>
        <div><input type="radio" name="engagement_level" value="0" <?php if($row['engagement_level']==0) echo 'checked="checked"';?>/>Unknown &nbsp;<input type="radio" name="engagement_level" value="1" <?php if($row['engagement_level']==1) echo 'checked="checked"';?>/>Low &nbsp;<input type="radio" name="engagement_level" value="5"  <?php if($row['engagement_level']==5) echo 'checked="checked"';?>/>Medium &nbsp;<input type="radio" name="engagement_level" value="10" <?php if($row['engagement_level']==10) echo 'checked="checked"';?>/>High</div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Life Status:</div>
        <div>
        <select id="life_status_<?php  echo $row['id'];?>" name="life_status">
        	<option value="unknown" <?php if($row['life_status'] == "unknown") echo 'selected="selected"';?>>Unknown</option>
            <option value="high_school" <?php if($row['life_status'] == "high_school") echo 'selected="selected"';?>>High School</option>
            <option value="college" <?php if($row['life_status'] == "college") echo 'selected="selected"';?>>College</option>
            <option value="working" <?php if($row['life_status'] == "working") echo 'selected="selected"';?>>Working</option>
        </select>
        </div>
    </div>
    <?php if($_SESSION['user_role'] == 'administrator'){ ?>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Funnel:</div>
        <div>
        <select id="funnel_<?php  echo $row['id'];?>" name="funnel">
            <option value="">Select</option>
			<?php 
            $funnels_array = Student::all_funnels();
			for($fnl_i=1;$fnl_i <= count($funnels_array);$fnl_i++)
            {
                ?><option value="<?php echo $fnl_i;?>" <?php if($row['funnel'] == $fnl_i) echo 'selected="selected"';?>><?php echo $funnels_array[$fnl_i];?></option><?php
            }
            ?>  
        </select>
        </div>
    </div>
    <?php } ?>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Gender:</div>
        <div>
        <select name="gender" id="gender_<?php  echo $row['id'];?>">
            <option value="">Select</option>
            <option value="M" <?php if($row['gender'] == "M") echo 'selected="selected"';?>>Male</option>
            <option value="F" <?php if($row['gender'] == "F") echo 'selected="selected"';?>>Female</option>
        </select>
        </div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Age:</div>
        <div>
        <input type="text" name="age" id="age_<?php echo $row['id'];?>" value="<?php echo Student::calculate_Age($row['dob']);?>"  style="width:90px;"/>
        &nbsp;&nbsp;Or DOB: <input type="text" name="dob" id="dob_<?php echo $row['id'];?>" value="<?php  echo $row['dob'];?>"  style="width:120px;"/>
        </div>
    </div>
    
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Memo / Comment:</div>
        <div><textarea name="u_comment" id="u_comment" style="width:300px;"><?php if(!empty($row['comment'])) echo $row['comment'];?></textarea></div>
    </div>
    <?php if($_SESSION['user_role'] == 'administrator')
	{ ?> <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Converted By:</div>
        <div>
        <select id="converted_by_<?php echo $row['id'];?>">
        <option value="">Select Teacher</option>
         <?php
		// $all_user = User::all_user('teacher');
		 // print_r($all_user);
		  for($i_user = 0;$i_user < count($all_user);$i_user++)
		  {
			  ?>
			 <option value="<?php echo $all_user[$i_user]['id'];?>" <?php  if($row['converted_by'] ==$all_user[$i_user]['id']) echo 'selected="selected"';?> > <?php echo $all_user[$i_user]['name'];?></option>
			  <?php
		  }
		?>
        </select>
        </div>
    </div><?php } ?>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">&nbsp;</div>
        <div><input type="submit" value="Save"  />&nbsp;<input type="button" value="Cancel" onclick="HideUsereditBlock(<?php  echo $row['id'];?>);"  /></div>
    </div>
    <div class="clear"></div>
</div>
</form>



		
		<?php
	}
	
	
	
	public function get_teacher_slot($teacher_id)
	{
	    if($teacher_id=='') return false;
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		//$sql= "SELECT `id`, `start_time`, `end_time` FROM  teacher_slots WHERE teacher_id = ".$teacher_id." AND active = '1'";
		$sql= "SELECT `id`, `start_time`, `end_time`,`empty_slot`,`coaching` FROM  teacher_slots WHERE teacher_id = ".$teacher_id."  ORDER BY `empty_slot`,`start_time` ASC";
		$q = $db->doQuery($sql);
		$result = array();
		while($row = mysql_fetch_assoc($q)) {  
			$result[] = $row;
           
        }
		return $result;
		
		exit;
	}
	
	public function update_user_dtl()
	{
		date_default_timezone_set("Asia/Tokyo");
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$uid = $_POST['uid'];
		$f_name = $_POST['f_name'];
		$l_name = $_POST['l_name'];
		$email = $_POST['email'];
		$pay_email = $_POST['pay_email'];
		$line_id = $_POST['line_id'];
		$comment = mysql_real_escape_string($_POST['comment']);
		
		$slot = $_POST['slot'];
		$created_on = $_POST['created_on'];
		$created_on == ''?$created_on = '0000-00-00 00:00:00':0;
		$first_response = $_POST['first_response'];
		$first_response == ''?$first_response = '0000-00-00 00:00:00':0;
		$ft_expiration = $_POST['ft_expiration'];
		$ft_expiration == ''?$ft_expiration = '0000-00-00 00:00:00':0;
		$status = $_POST['status'];
		$plan = $_POST['plan'];
		$english_name = $_POST['english_name'];
		$payment_method = $_POST['payment_method'];
		$pay_info_sent = isset($_POST['pay_info_sent']) ? $_POST['pay_info_sent'] : 0;
		($pay_info_sent==1)?$payment_info_sent_date = date('Y-m-d H:i:s'):$payment_info_sent_date= '0000-00-00 00:00:00';
		$first_paid_date = $_POST['first_paid_date'];
		$first_paid_date == ''?$first_paid_date = '0000-00-00 00:00:00':0;
		$paid_through = $_POST['paid_through'];
		$paid_through == ''?$paid_through = '0000-00-00 00:00:00':0;
		$cancellation_date = $_POST['cancellation_date'];
		($cancellation_date == '' || $cancellation_date =='undefined')?$cancellation_date = '0000-00-00 00:00:00':0;
		$engagement_level = $_POST['engagement_level'];
		$life_status = $_POST['life_status'];
		$gender = $_POST['gender'];
		$dob = $_POST['dob'];
		$funnel = $_POST['funnel'];
		//$age = $_POST['age'];
		$converted_by = $_POST['converted_by'];
		
		
		$student_cur_status = Student::getStudentStatusId($uid);
		Subscription::update_subscription_lenght($uid,$slot);
		//unique_user_id is Not Editable
		//$unique_user_id = $_POST['unique_user_id'];
		/*$sql= "UPDATE users_list  SET `email` = '".$email."',
										`paypal_email` = '".$pay_email."',
										`comment` = '".$comment."',
										`f_name` = '".$f_name."',
										 `l_name`= '".$l_name."',
										  `english_name`= '".$english_name."',
										 `line_id`= '".$line_id."',
										 `slot`= '".$slot."',
										 `created_on`= '".$created_on."',
										 `first_response`= '".$first_response."',
										 `ft_expiration`= '".$ft_expiration."',
										 `status`= '".$status."',
										 `plan`= '".$plan."',
										  `payment_method`= '".$payment_method."',
										 `unique_user_id`= '".$unique_user_id."'
										 WHERE id = ".$uid."";*/
		  $sql= "UPDATE users_list  SET `comment` = '".$comment."',
										`f_name` = '".$f_name."',
										`l_name`= '".$l_name."',
										`english_name`= '".$english_name."'";
										
										if(isset($email) && $email != '' && $email != 'undefined')
										$sql .=", `email` = '".$email."'";
										
										if(isset($pay_email) && $pay_email != '' && $pay_email != 'undefined')
										$sql .=", `paypal_email` = '".$pay_email."'";
										
										
										
										$sql .=", `line_id`= '".$line_id."',
										`slot`= '".$slot."',
										`created_on`= '".$created_on."',
										`first_response`= '".$first_response."',
										`ft_expiration`= '".$ft_expiration."',
										`status`= '".$status."',
										`plan`= '".$plan."',
										`payment_method`= '".$payment_method."',
										`first_paid_date`= '".$first_paid_date."',
										`paid_through`= '".$paid_through."',
										`cancellation_date`= '".$cancellation_date."',
										`engagement_level`= '".$engagement_level."',
										`life_status`= '".$life_status."',
										`gender`= '".$gender."',
										`dob`= '".$dob."',
										`payment_info_sent`= '".$pay_info_sent."',
										`payment_info_sent_date` = '".$payment_info_sent_date."' ";
										
										if(isset($converted_by) && $converted_by != '' && $converted_by != 'undefined')
										$sql .=", `converted_by` = '".$converted_by."'";
										
										if(isset($funnel) && $funnel != '' && $funnel != 'undefined')
										$sql .=", `funnel` = '".$funnel."'";
										
										$sql .=" WHERE id = ".$uid."";
		$q = $db->doQuery($sql);
		if($q)
		{
			if($student_cur_status != $status)
			{
				User::update_available_line_url_shows($slot);
				User::insert_change_status_log($uid,$status,$student_cur_status);
			}
			echo 'success';
		}
		else
		{
			echo 'error';
		}
		exit;
		
	}
	
	
	public function delete_user()
	{
	    $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$uid = $email = $_POST['uid'];
		
		$user_slot = Student::getStudentSlotId($uid);
		
		
		$sql= "DELETE FROM users_list WHERE id = ".$uid."";
		$q = $db->doQuery($sql);
		
		User::update_available_line_url_shows($user_slot);
		if($q)
		{
			echo 'success';
		}
		else
		{
			echo 'error';
		}
		exit;
	}
	
	public function generateRandomString($length = 5) {
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
	if( Student::checkRandomStringExist($randomString) )
    return $randomString;
	}
	
	public function checkRandomStringExist($random) {
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql= "SELECT id FROM users_list WHERE `unique_user_id` = '".$random."'";
		$q = $db->doQuery($sql);
		$r = mysql_num_rows($q);
		if($r > 0)
		{
			generateRandomString();
		}
		else
		{
			$random_value = true;
		}
		
		return $random_value;
	}
	
	/*public function update_available_line_url_shows($slot_id)
	{
		//echo $slot_id;
		
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		if($slot_id > 0)
		{
			
		$sql= "SELECT COUNT(id) AS total FROM users_list WHERE slot = ".$slot_id."";
		$q = $db->doQuery($sql);
		$r =mysql_fetch_assoc($q);
		$total_activeUser = $r['total'];
		
		$slotDtl = Student::slotDtl($slot_id);
		$max_students = $slotDtl['max_students'];
		
		$round = Student::update_active_students_per_slot();
		$total_activeUser = $total_activeUser + $round;
		
		
		//max_students - ACTIVE STUDENTS (get that from the users_list table for the GIVEN SLOT) * 10 = available_line_url_shows
		$available_line_url_shows = ( $max_students - $total_activeUser ) *10;
		//print_r($slotDtl);
		if($available_line_url_shows >= 0)
		{
			$update  = "UPDATE `teacher_slots` SET `available_line_url_shows` = '$available_line_url_shows' WHERE id = '$slot_id'";
			$db->doQuery($update);
		}
		
		}
		
	}
	
	public function update_active_students_per_slot()
	{
		//echo $slot_id;
		
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		// number of  student has no timeslot 
		$sql = "SELECT COUNT(U.id) AS total FROM users_list AS U LEFT JOIN teacher_slots AS TS ON(U.slot=TS.id) WHERE TS.empty_slot = 1";
		$q = $db->doQuery($sql);
		$r =mysql_fetch_assoc($q);
		$students_with_no_timeslot = $r['total'];
		
		
		// total numbers  slot 
		$total_slotsSql = "SELECT COUNT(`id`) AS total FROM `teacher_slots`";
		$total_slotsQ = $db->doQuery($total_slotsSql);
		$total_slotsR =mysql_fetch_assoc($total_slotsQ);
		$total_slots = $total_slotsR['total'];
		
		$round = round( $students_with_no_timeslot / $total_slots );
		return $round;
		
		
	}*/
	
	
	public function checkMaxStudent_CurrentStudent($slot_id)
	{
		//echo $slot_id;
		
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		if($slot_id > 0)
		{
			
			$sql= "SELECT COUNT(id) AS total FROM users_list WHERE life_status IN ('working','unknown') AND slot = ".$slot_id." AND status IN (1,3,7)";
			$q = $db->doQuery($sql);
			$r =mysql_fetch_assoc($q);
			$total_activeUser = $r['total'];
			
			$slotDtl = Student::slotDtl($slot_id);
			$max_students = $slotDtl['max_students'];
			$empty_slot = $slotDtl['empty_slot'];
			if($empty_slot==0 && $max_students < $total_activeUser)
			{
				send_mail_notify_max_students_per_slot($slot_id,$total_activeUser);
			}
		
		}
	}
	
	public function display_format_age_gen($dob,$gen)
	{
		$age = Student:: calculate_Age($dob);
		if($gen == 'M') $g = 'Male';
		elseif($gen == 'F') $g ='Female';
		else $g = '';
		return ($age > 0)?$age.'/'.$g:$g;
	}
	public function calculate_Age($dob)
	{
		date_default_timezone_set("Asia/Tokyo");
		if('0000-00-00' == $dob) return 0; //date in mm/dd/yyyy format; or it can be in other formats as well
		$birthDate = date('m-d-Y',strtotime($dob));//"12/17/1983";
		//explode the date to get month, day and year
		$birthDate = explode("-", $birthDate);
		//get age from date or birthdate
		$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
		? ((date("Y") - $birthDate[2]) - 1)
		: (date("Y") - $birthDate[2]));
		return $age;
	}
	
	public function search_student_html()
	{
		
		session_start(); 
		date_default_timezone_set("Asia/Tokyo");
		$f_name = $_POST['f_name'];
		$l_name = $_POST['l_name'];
		$email = $_POST['email'];
		$pay_email = $_POST['pay_email'];
		$line_id = $_POST['line_id'];
		//$comment = $_POST['comment'];
		
		$teacher_id = $_POST['teacher_id'];
		$slot = $_POST['slot'];
		//$created_on = $_POST['created_on'];
		$first_response_from = $_POST['first_response_from'];
		$first_response_to = $_POST['first_response_to'];
		$ft_expiration_from = $_POST['ft_expiration_from'];
		$ft_expiration_to = $_POST['ft_expiration_to'];
		$join_date_from = $_POST['join_date_from'];
		$join_date_to = $_POST['join_date_to'];
		$first_paid_date_from = $_POST['first_paid_date_from'];
		$first_paid_date_to = $_POST['first_paid_date_to'];
		$paid_through_from = $_POST['paid_through_from'];
		$paid_through_to = $_POST['paid_through_to'];
		$cancellation_date_from = $_POST['cancellation_date_from'];
		$cancellation_date_to = $_POST['cancellation_date_to'];
		$status = $_POST['status'];
		$plan = $_POST['plan'];
		$english_name = $_POST['english_name'];
		$payment_method = $_POST['payment_method'];
		$payment_info_sent = $_POST['payment_info_sent'];
		$engagement_level = $_POST['engagement_level'];
		$life_status = $_POST['life_status'];
		$funnel = $_POST['funnel'];
		
		$gender = $_POST['gender'];
		$dob_from = $_POST['dob_from'];
		$dob_to = $_POST['dob_to'];
		$age_from = $_POST['age_from'];
		$age_to = $_POST['age_to'];
		
		$show_expired = $_POST['show_expired'];
		
		$unique_user_id = $_POST['unique_user_id'];
		$requestd_slot_id = $_POST['requestd_slot_id'];
		
		$condition = " WHERE id!= '' ";
		$flag = 1;
		$con = " ";
		$date = date("Y-m-d H:i:s");
		
		
		if(!empty($f_name))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `f_name` LIKE '".$f_name."%'";
		}
		
		if(!empty($l_name))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `l_name` LIKE '".$l_name."%'";
		}
		if(!empty($english_name))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `english_name` LIKE '".$english_name."%'";
		}
		if(!empty($email))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `email` LIKE '".$email."%'";
		}
		if(!empty($pay_email))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `paypal_email` LIKE '".$pay_email."%'";
		}
		if(!empty($line_id))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `line_id` LIKE '".$line_id."%'";
		}
		if(!empty($unique_user_id))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `unique_user_id` = '".$unique_user_id."'";
		}
		if(!empty($teacher_id) && $teacher_id!="undefined" )
		{
			$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
			
			$sql_teacher_slot = "SELECT id FROM teacher_slots WHERE `teacher_id`='".$teacher_id."'";
			$q_tch_sl = $db->doQuery($sql_teacher_slot);
			
			while($rows_tech = mysql_fetch_assoc($q_tch_sl))
			{
				$slot_ids[] = $rows_tech['id'];
			}
			$slot_ids_srch = implode(",",$slot_ids);
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `slot` IN (".$slot_ids_srch.")";
		}
		if(isset($slot) && $slot!="undefined")
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `slot` = '".$slot."'";
		}
		elseif(isset($requestd_slot_id) && $requestd_slot_id!="undefined" && $requestd_slot_id != '')
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `slot` = '".$requestd_slot_id."'";
		}
		
		if(!empty($status) && $status!="undefined")
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `status` = ".$status."";
		}
		if(!empty($plan) && $plan!="undefined")
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `plan` ='".$plan."'";
		}
		if(!empty($life_status) && $life_status!="undefined")
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `life_status` ='".$life_status."'";
		}
		
		if(!empty($funnel) && $funnel!="undefined")
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `funnel` ='".$funnel."'";
		}
		
		
		
		if(isset($engagement_level) && $engagement_level!="undefined")
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `engagement_level` ='".$engagement_level."'";
		}
		if(!empty($payment_method) && $payment_method!="undefined")
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `payment_method` = '".$payment_method."'";
		}
		if(isset($payment_info_sent) && $payment_info_sent!="")
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `payment_info_sent` = '".$payment_info_sent."'";
		}
		if(!empty($first_response_from) && !empty($first_response_to))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `first_response` >= '".$first_response_from."' AND `first_response` <= '".$first_response_to."'";
		}
		if(!empty($ft_expiration_from) && !empty($ft_expiration_to))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `ft_expiration` >= '".$ft_expiration_from."' AND `ft_expiration` <= '".$ft_expiration_to."'";
		}
		if(!empty($join_date_from) && !empty($join_date_to))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `created_on` >= '".$join_date_from."' AND `created_on` <= '".$join_date_to."'";
		}
		if(!empty($first_paid_date_from) && !empty($first_paid_date_to))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `first_paid_date` >= '".$first_paid_date_from."' AND `first_paid_date` <= '".$first_paid_date_to."'";
		}
		if(!empty($paid_through_from) && !empty($paid_through_to))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `paid_through` >= '".$paid_through_from."' AND `paid_through` <= '".$paid_through_to."'";
		}
		
		if(!empty($cancellation_date_from) && !empty($cancellation_date_to))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `cancellation_date` >= '".$cancellation_date_from."' AND `cancellation_date` <= '".$cancellation_date_to."'";
		}
				
		if($show_expired==0)
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." ( `payment_info_sent` = '0' OR `status`!= 6 )";
		}
		if(!empty($gender) && $gender!="undefined")
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `gender` ='".$gender."'";
		}
		
		if(!empty($age_from) && !empty($age_to))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." DATE_FORMAT(FROM_DAYS(DATEDIFF('".date('Y-m-d')."',`dob`)), '%Y')+0 >= ".$age_from." AND DATE_FORMAT(FROM_DAYS(DATEDIFF('".date('Y-m-d')."',`dob`)), '%Y')+0 <= ".$age_to." ";
		}


		if(!empty($dob_from) && !empty($dob_to) && $age_from =='' && $age_to == '')
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `dob` >= '".$dob_from."' AND `dob` <= '".$dob_to."'";
		}

		
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$sqlCount= "SELECT COUNT(id) AS total FROM  `users_list` ".$condition."";
		$qCount = $db->doQuery($sqlCount);
		$num_rowCount = mysql_fetch_assoc($qCount);
		$num_row = $num_rowCount['total'];
		
		// ORDER BY
		$order_by_col = $_POST['order_by_col'];
		$order_by = $_POST['order_by'];
		if(!empty($order_by_col))
		{
			$order = " ORDER BY ".$order_by_col." ".$order_by." ";
		}
		else
		{
				/*$order = "  ORDER BY CASE `status`  
							WHEN '2' THEN 0
							WHEN '4' THEN 0
							WHEN '9' THEN 0
							ELSE 1
			 				END DESC , CASE `ft_expiration`  
							WHEN '0000-00-00 00:00:00' THEN 0
							ELSE 1
							END DESC , IF(`date_diff_expire`>1,1,0) ASC , payment_info_sent ASC";*///created_on DESC
				$order = "  ORDER BY  CASE `status`  
							WHEN '2' THEN 0
							WHEN '4' THEN 0
							WHEN '9' THEN 0
							ELSE 1
			 				END DESC , CASE `ft_expiration`  
							WHEN '0000-00-00 00:00:00' THEN 0
							ELSE 1
							END DESC , IF(`date_diff_expire`>1,1,0) ASC ,payment_info_sent ASC";
		}
		
		// LIMIT 
		$per_page = STUDENT_PER_PAGE;
		$page_num = $_POST['page_num'];
		$limit = " LIMIT ".($page_num-1)*STUDENT_PER_PAGE.",".STUDENT_PER_PAGE." ";
		
		$slNoToShow = ($page_num-1)*STUDENT_PER_PAGE;
		$raw_sql = "SELECT `id`, `slot`, `email`, `paypal_email`, `f_name`, `l_name`, `english_name`, `line_id`, `created_on`, `first_response`, `ft_expiration`, `unique_user_id`, `status`, `plan`, `payment_method`, `comment`, `payment_info_sent`, `payment_info_sent_date`, `first_paid_date`,`paid_through`,`cancellation_date`, `engagement_level`, `life_status`, `gender`, `dob`, `old`,DATEDIFF(`ft_expiration`,'".$date."') AS date_diff_expire FROM  `users_list` ".$condition." ";
		$sql = $raw_sql." ".$order." ".$limit;
		$q = $db->doQuery($sql);
		
		//echo $sql;
		//print_r($row);
		?>
        
      <style>
.table_view {
    display:table;
	width:1340px;
}
.hide_student {
    display:none;
}
.header {
    di splay:table-header-group;
    font-weight:bold;
	text-align:center;
}
.header .cell{height:45px;background:#666;color:#FFF;float:left;font-size:13px;}
.cell {
    dis play:table-cell;
    width:auto;
	m ax-width:100px;
	backg round-color:#069;
	ma rgin:2px;
	border:1px solid #FFFFFF;
	float:left;
	height:120px;
	back ground:#E2E2E2;
	display:block;
	font-size:13px;
	line-height:20px;
}
.u_sl{width:23px;text-align:center;}
.u_ts{width:45px;text-align:center;}
.u_status{width:52px;text-align:center;}

.u_usi{width:130px;}
.ufn,.uln{width:80px;}
.u_t{width:60px;}
.u_jd{width:69px;text-align:center;}
.u_fp{width:69px;text-align:center;}
.u_fr{width:68px;text-align:center;}
.u_cdt{width:75px;text-align:center;}
.u_bd{width:75px;text-align:center;}
.u_ll{width:180px;text-align:center;}
.u_ac{width:44px;text-align:center;}
.u_st{width:69px;text-align:center;}
.u_p{width:50px;text-align:center;}
.u_cmnt{width:120px;text-align:center;}
.u_em{width:200px;text-align:center;}
.u_pem{width:200px;text-align:center;}
.u_dtl{width:200px;padding-left:5px;}
.u_pmnt{width:60px;text-align:center;}
.uid-t2{color:#06C;font-size:14px;}
.yellow{background:#FF0 !important;}
.u_el{width:80px;text-align:center;}
.u_ls{width:65px;text-align:center;}

</style>
 <?php if($_SESSION['user_role'] == 'administrator'){ ?> <style>.cell{height:120px;} </style>   <?php } else {?> <style>.u_jd,.u_fr{width:100px;} </style> <?php } ?>
<div class="table_view">

<div class="pagina">
<div style="padding:0 70px 0 5px ;">Total : <?php echo $num_row;?></div>
<div><?php if($num_row > STUDENT_PER_PAGE ) { Student::pagination($num_row,$page_num); }?></div>
</div>
<div class="clear"></div>
  <div class="header nav">
    <div class="cell u_sl">Sl</div>
    <div class="cell u_dtl">Student Details</div>
   <!-- <div class="cell u_usi">Unique Student ID</div>
    <div class="cell ufn">First Name</div>
    <div class="cell uln">Last Name</div>-->
    <?php if($_SESSION['user_role'] == 'administrator')
	{ ?>
    <!--<div class="cell u_em">Contact Email</div>
    <div class="cell u_pem">Paypal Email</div>-->
    <?php } ?>
    <div class="cell u_ts" >Time Slot</div>
    <div class="cell u_status">Slot Status</div>
      <?php if($_SESSION['user_role'] == 'administrator')
	{ ?>
     <div class="cell u_t">Teacher</div>
     <?php } ?>
    <div class="cell u_jd sortable created_on_sort" onclick="orderBY('created_on');">Join date</div>
    <div class="cell u_fr sortable first_response_sort" onclick="orderBY('first_response');" style="text-align:left">First response</div>
     <div class="cell u_bd sortable ft_expiration_sort" onclick="orderBY('ft_expiration');" style="text-align:left">Last day of F.T.</div>
     <div class="cell u_fp sortable first_paid_date_sort" onclick="orderBY('first_paid_date');" >First Paid Date</div>
     <div class="cell u_fp sortable paid_through_sort" onclick="orderBY('paid_through');" >Paid Through</div>
     <div class="cell u_cdt sortable cancellation_date_sort" onclick="orderBY('cancellation_date');" >Cancellation</div>
     <div class="cell u_st sortable status_sort" onclick="orderBY('status');" style="text-align:left">Student Status</div>
      <div class="cell u_p sortable plan_sort" onclick="orderBY('plan');">Plan</div>
      <div class="cell u_el">Engagement Level</div>
      <div class="cell u_ls">Life Status</div>
      <div class="cell u_cmnt">Memo/Comment</div>
      <div class="cell u_pmnt">Payment Info</div>
      <?php if($_SESSION['user_role'] != 'viewer') { ?><div class="cell u_ac">Action</div><?php } ?>
  </div>
 <div class="clear"></div>
  <?php 
  /*$ary_status = array(
					1=>'Initial message sent',
					2=>'No response to initial message after 2 days',
					3=>'In F.T.',
					4=>'F.T. expired without response',
					6=>'F.T. expired',
					7=>'Paying',
					8=>'Cancelled',
					9=>'Registration link sent'
					);*/
					
 // print_r($all_user);
// print_r($ary_status);
  if($num_row > 0 ){ $i_user = 0;
  $stdnt_ary = array();$stdnt_ary_yellow = array();
 // $_SESSION['shown'] = array_unique($_SESSION['shown']);
  while( $row = mysql_fetch_assoc($q) )
  {
	  if(empty($order_by_col))
	  {
		  $class = User::check_day_before_expiration($row['ft_expiration'],$row['id'],$row['status'],$row['payment_info_sent']);
		  if($class == "yellowBG")
		  {
			  $stdnt_ary_yellow[] = $row;
			 // $_SESSION['shown'][] = $row['id'];
		  }
	  }
	  else
	  {
	  	 $stdnt_ary[] = $row;
	  }
	  
  }
  $t_stdnt_yellow = count( $stdnt_ary_yellow);
 
  
  
	// calculate total yellow rows
	$sql_ylwo_rows = "SELECT `id` FROM  `users_list` ".$condition." AND `payment_info_sent` = 0 AND ft_expiration != '0000-00-00 00:00:00' AND status NOT IN(4,9,2) AND DATEDIFF(`ft_expiration`,'".$date."') <=1";
	$sql_ylwo_rowsQ = $db->doQuery($sql_ylwo_rows);
	if(mysql_num_rows($sql_ylwo_rowsQ))
	{
		while($r = mysql_fetch_assoc($sql_ylwo_rowsQ))
		{
			$shown[] = $r['id'];
		}
		$t_shown = count($shown);
	}else
	{
		$t_shown = 0;
	}
	 
	
	
 // echo $t_stdnt_yellow.'++';
 //echo count($_SESSION['shown']);
 //echo '++'.$page_num;
  if(empty($order_by_col))
  {
	  if( $t_stdnt_yellow < STUDENT_PER_PAGE && $num_row > $t_shown )
	  {
		 $byDate = $raw_sql;
		 if($t_shown > 0 )
		 {
			 $impld_shown_ids = implode(',', $shown);
			 $byDate .= " AND id NOT IN(".$impld_shown_ids.")";
		 }
		 $byDate .= " ORDER BY created_on DESC ";
		
		 if($t_stdnt_yellow == 0){
		 $lm1 = ((($page_num-1)*STUDENT_PER_PAGE)-$t_shown).",".STUDENT_PER_PAGE." ";
		 }
		 
		 else if($t_stdnt_yellow > 0 && $page_num > 1){
		 $lm1 = "0,".(STUDENT_PER_PAGE-$t_stdnt_yellow)." ";
		 }
		 
		 else if($t_stdnt_yellow > 0 && $page_num == 1){
		 $lm1 = "0,".(STUDENT_PER_PAGE-$t_stdnt_yellow)." ";
		 }
		 
		 $byDate .= " LIMIT ".$lm1;
		// echo $byDate;
		 $byDateQ = $db->doQuery($byDate);
		 while( $row = mysql_fetch_assoc($byDateQ) )
		  {
			 $stdnt_ary[] = $row;
		  }
		 
	  }
  }
 $stdnt_ary = array_merge((array)$stdnt_ary_yellow, (array)$stdnt_ary);
 
// print_r($_SESSION['shown']);
  $all_plans_ary = Plan::all_plans_ary();//print_r($all_plans_ary);
  
  for( $i_user = 0 ; $i_user < count($stdnt_ary); $i_user++)
  {
	$slotDtl = Student::slotDtl($stdnt_ary[$i_user]['slot']);
	/*echo "<pre>";
	print_r($slotDtl);
	echo "</pre>";*/
	$class = User::check_day_before_expiration($stdnt_ary[$i_user]['ft_expiration'],$stdnt_ary[$i_user]['id'],$stdnt_ary[$i_user]['status'],$stdnt_ary[$i_user]['payment_info_sent']);
	$first_response_blinker = User::check_five_day_after_first_response($stdnt_ary[$i_user]['first_response'],$stdnt_ary[$i_user]['id']);
	
	
	 ?>
	 
    <div id="usrTr_<?php echo $stdnt_ary[$i_user]['id'];?>" name="usrTr_<?php echo $stdnt_ary[$i_user]['id'];?>" class="rowGroup <?php echo $class; ?>" >
    <div class="cell u_sl"><?php echo $slNoToShow+1;$slNoToShow++;// $i_user;?></div>
    <div class="u_dtl cell">
    <div class="u_info_user_<?php echo $stdnt_ary[$i_user]['id'];?>">Student ID:<span class="uid-t2"><?php echo $stdnt_ary[$i_user]['unique_user_id'];?></span></div>
    <div><span>First name:</span><span class="u_info_fname_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php echo $stdnt_ary[$i_user]['f_name'];?></span></div>
     <div><span>Last name:</span><span class="u_info_lname_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php echo $stdnt_ary[$i_user]['l_name'];?></span></div>
     <div><span>English name:</span><span class="u_info_engname_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php echo $stdnt_ary[$i_user]['english_name'];?></span></div>
    
     <?php /*?><?php if($_SESSION['user_role'] == 'administrator')
	{ ?>
    <div><span>Contact Email:</span><span class="u_info_em_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php echo $stdnt_ary[$i_user]['email'];?></span></div>
    <div><span>Paypal Email:</span><span class="u_info_pem_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php echo $stdnt_ary[$i_user]['paypal_email'];?></span></div>
    <?php } ?><?php */

    $life_status = $stdnt_ary[$i_user]['life_status'];
    if ($life_status == "high_school" || $life_status == "college")
    {
    	$url_path = "http://okpanda.com/trial/?student=".$stdnt_ary[$i_user]['unique_user_id']; 
    }
    else
    {
        $url_path = "http://okpanda.com/s/".$stdnt_ary[$i_user]['unique_user_id']; 
    }
    ?>
    <div><?php echo $url_path; ?></div>
    </div>
   
    <div class="cell u_ts u_info_ts_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php if($slotDtl['empty_slot'] == 1) echo 'NTS';elseif($slotDtl['coaching'] == 1) echo 'Coach';else { if($slotDtl['start_time']) echo substr($slotDtl['start_time'],0,5).'<br />To<br />'.substr($slotDtl['end_time'],0,5);}?></div>
    <div class="cell u_status u_info_status_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php if($slotDtl['active'] == 1) echo 'Active'; else echo 'Inactive';?></div>
     <?php if($_SESSION['user_role'] == 'administrator')
	{ ?>
    <div class="cell u_t u_info_name_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php if($slotDtl['name']) echo $slotDtl['name'];?></div>
    <?php } ?>
    <div class="cell u_jd u_info_jd_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php echo substr($stdnt_ary[$i_user]['created_on'],0,-8);?></div>
    <div class="cell u_fr u_info_fr_<?php echo $stdnt_ary[$i_user]['id'];?> <?php //if($first_response_blinker=="blink") echo "aro_ind";?>"><?php if($stdnt_ary[$i_user]['first_response']!="0000-00-00 00:00:00") { echo substr($stdnt_ary[$i_user]['first_response'],0,-8); } else echo '';?></div>
    <div class="cell u_bd u_info_bd_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php if($stdnt_ary[$i_user]['first_response']!="0000-00-00 00:00:00") { echo User::check_ft_expiration($stdnt_ary[$i_user]['ft_expiration'],$stdnt_ary[$i_user]['id'],$stdnt_ary[$i_user]['status']); } ?></div>
    <div class="cell u_fp u_info_fp_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php if($stdnt_ary[$i_user]['first_paid_date']!="0000-00-00 00:00:00") echo substr($stdnt_ary[$i_user]['first_paid_date'],0,-8);?></div>
    <div class="cell u_fp u_info_pt_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php if($stdnt_ary[$i_user]['paid_through']!="0000-00-00 00:00:00") echo substr($stdnt_ary[$i_user]['paid_through'],0,-8);?></div>
    <div class="cell u_cdt u_info_cdt_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php if($stdnt_ary[$i_user]['cancellation_date']!="0000-00-00 00:00:00") echo substr($stdnt_ary[$i_user]['cancellation_date'],0,-8);?></div>

<div class="cell u_st u_info_st_<?php echo $stdnt_ary[$i_user]['id'];?>">
    	<span class="change_avatars_span" id="span_change_status_<?php echo $stdnt_ary[$i_user]['id'];?>"  <?php if($_SESSION['user_role'] != 'viewer') { ?> onclick="change_status(<?php echo $stdnt_ary[$i_user]['status'];?>,<?php echo $stdnt_ary[$i_user]['id'];?>);" <?php } ?>>
			<?php echo User::getStatusNameByID($stdnt_ary[$i_user]['status']);?>
        </span>
        <span id="span_dplist_change_status_<?php echo $stdnt_ary[$i_user]['id'];?>"></span><br/><br/>
        <div id="csl_<?php echo $stdnt_ary[$i_user]['id'];?>"><a style="text-decoration:underline; cursor:pointer; color: #00C;" title="Change Status Log" onclick="statusChangeLog('<?php echo $stdnt_ary[$i_user]['id']; ?>');" >Status Log</a></div>
    </div>
    <div id="dialog-comt-txt-status-<?php echo $stdnt_ary[$i_user]['id'];?>" title="Status Change Log For <?php echo $stdnt_ary[$i_user]['f_name']." ".$stdnt_ary[$i_user]['l_name'];?>"  style="display:none;"></div>
    
            <div class="cell u_p u_p_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php if($stdnt_ary[$i_user]['plan'] != '' && $stdnt_ary[$i_user]['plan'] != 'undefined') echo $all_plans_ary[$stdnt_ary[$i_user]['plan']];//ucfirst(str_replace('_',' ', $stdnt_ary[$i_user]['plan']));?></div>
            <div class="cell u_el u_info_el_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php if($stdnt_ary[$i_user]['engagement_level'] == 0) echo 'Unknown';elseif($stdnt_ary[$i_user]['engagement_level'] == 1) echo 'Low'; elseif($stdnt_ary[$i_user]['engagement_level'] == 5) echo 'Medium'; elseif($stdnt_ary[$i_user]['engagement_level'] == 10) echo 'High'; else echo '';?></div>
            <div class="cell u_ls">
                <div class=" u_ls u_info_ls_<?php echo $stdnt_ary[$i_user]['id'];?>"><span class="<?php echo User::check_life_status_blinking($stdnt_ary[$i_user]['status'],$stdnt_ary[$i_user]['life_status']);?>" style="font-size:13px;"><?php  echo ucfirst(str_replace('_',' ', $stdnt_ary[$i_user]['life_status']));?></span></div>
                <?php /*?>   <div style="margin:30px 0 0 0;border-top:1px solid #FFFFFF;line-height:1.5;" class=" u_ls ">DOB:<br /><span class="u_info_dob_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php if(isset($stdnt_ary[$i_user]['dob']) && $stdnt_ary[$i_user]['dob'] != '0000-00-00')echo $stdnt_ary[$i_user]['dob'];else echo 'UN';?></span></div><?php */?>       
                <div style="margin:30px 0 0 0;border-top:1px solid #FFFFFF;line-height:1.5;" class=""><span class="u_info_age_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php $d2age = Student::calculate_Age($stdnt_ary[$i_user]['dob']); if($d2age >0)echo $d2age;else echo 'UN';?></span>/<span class="u_info_gen_<?php echo $stdnt_ary[$i_user]['id'];?>"><?php if($stdnt_ary[$i_user]['gender'] == 'M') echo 'Male';elseif($stdnt_ary[$i_user]['gender'] == 'F') echo 'Female'; else echo 'UN'; ?></span></div>
            </div>
            <div class="cell u_cmnt u_cmnt_<?php echo $stdnt_ary[$i_user]['id'];?>"> 
			<?php if(strlen($stdnt_ary[$i_user]['comment']) > 70) { echo substr($stdnt_ary[$i_user]['comment'],0,50).'...<a class="chios_tooltip" style="text-decoration:underline; cursor:pointer;" title="'.$stdnt_ary[$i_user]['comment'].'" onclick="showFullComment('.$stdnt_ary[$i_user]['id'].');" >More</a>'; } else echo $stdnt_ary[$i_user]['comment']; ?>
            </div>
			<div id="dialog-comt-txt-<?php echo $stdnt_ary[$i_user]['id'];?>" title="Memo / Comment"  style="display:none;"><?php echo $stdnt_ary[$i_user]['comment'];?></div>
            
         <div class="cell u_pmnt u_info_pmnt_<?php echo $stdnt_ary[$i_user]['id'];?>">
		 <?php 
		 if($stdnt_ary[$i_user]['payment_info_sent']!=0) { echo "Sent"; } 
		 else 
		 { 
		 	if($class == "yellowBG")
		 	echo '<span class="aro_ind" style="font-size:20px;">Not Sent</span>';
			else 
			echo 'Not Sent';
		 }
		 ?>
         </div>  

<?php if($_SESSION['user_role'] != 'viewer') { ?><div class="cell u_ac"><img src="media/images/1389630938_edit.png" class="action_btn_img"  onclick="edit_user(<?php echo $stdnt_ary[$i_user]['id'];?>);" style="margin:14px 0;"/> &nbsp;&nbsp;<img src="media/images/1389630919_cross-24.png" class="action_btn_img" onclick="delete_user(<?php echo $stdnt_ary[$i_user]['id'];?>);" /> </div><?php } ?>
    </div>
     <div class="clear"></div>
	<div class="edit_single_user user_block_<?php echo $stdnt_ary[$i_user]['id'];?>" style="display:none;">
    <div id="edit_user_frm_dtl_html_<?php echo $stdnt_ary[$i_user]['id'];?>" ></div>
    </div>
   <div class="clear"></div>
	 <?php 
  } }
  else
  {
	  echo '<h3>No record</h3>';
  }
}

	public function pagination($count,$pageNo)
		{
			//$pageNo++;
			//echo site_url(); 
			 $noOfPages = ceil($count/STUDENT_PER_PAGE);
			if($pageNo == "") $pageNo = 1;
		?>
		  <div class="pagination" id="pagination">
			 <div style="m argin:0 0 0 135px;float:left;cursor:pointer;" >
				<?php if($pageNo!=1){
					echo '<img src="media/images/arrow_left3.gif" alt="" onclick="disPage(1);"/>';
					echo '<img src="media/images/arrow_left4.gif" alt="" onclick="disPage('.($pageNo-1).');"/>';
				
				} else {
				echo '<img src="media/images/arrow_left1.gif" alt="" />';
				echo '<img src="media/images/arrow_left2.gif" alt="" />';
				?> 
				<!--<a href="javascript:disPage(<?php echo $pageNo-1; ?>);" onmouseout="javascript:window.status='Done';" onmousemove="javascript:window.status='Go to Previous Page';"><img src="images/arrow_left.gif" alt="" /></a>-->
				<?php
				}
				?>
				</div>
				<div style="float:left;">
				<?php ####### script to display no of pages #########
					//condition where no of pages is less than display limit
					$displayPageLmt = 8; #holds no of page links to display
					if($noOfPages <= $displayPageLmt){
						for($pgLink = 1; $pgLink <= $noOfPages; $pgLink++){
							if($pgLink==$pageNo){
								echo "<a class=\"here\">".$pgLink."</a>&nbsp;";						
							}
							else{
								echo "<a href=\"javascript:disPage($pgLink)\"  onmouseout=\"javascript:window.status='Done';\" onmousemove=\"javascript:window.status='Go to this Page';\">$pgLink</a>&nbsp;";
							}	
							if($pgLink<>$noOfPages) echo "";
						} #end of for loop
		
					} #end of if
					//condition for no of pages greater than display limit
					if($noOfPages > $displayPageLmt){
						if(($pageNo+($displayPageLmt-1)) <= $noOfPages){
							for($pgLink = $pageNo; $pgLink <= ($pageNo+$displayPageLmt-1); $pgLink++){
								if($pgLink==$pageNo){
									echo "<a class=\"here\">".$pgLink."</a>&nbsp;";
								}
								else{
									echo "<a href=\"javascript:disPage($pgLink)\"  onmouseout=\"javascript:window.status='Done';\" onmousemove=\"javascript:window.status='Go to this Page';\">$pgLink</a>&nbsp;";
								}
								if($pgLink<>($pageNo+$displayPageLmt-1)) echo "";
							}#end of for loop						
						}#end of inner if
						else{
							for($pgLink = ($noOfPages - ($displayPageLmt-1)); $pgLink <= $noOfPages; $pgLink++){
								if($pgLink==$pageNo){
											echo "<a class=\"here\">".$pgLink."</a>&nbsp;";
								}
								else{
									echo "<span id=\"dFunctionButton\"><a href=\"javascript:disPage($pgLink)\"  onmouseout=\"javascript:window.status='Done';\" onmousemove=\"javascript:window.status='Go to this Page';\">$pgLink</a></span>&nbsp;";
								}
								if($pgLink<>$noOfPages) echo "";
							}#end of for loop
						}					
					}#end of if noOfPage>displayPageLmt
		
				?>
					</div>
				<div style="float:left;cursor:pointer;">
				<?php if($pageNo != $noOfPages){
				echo '<img src="media/images/arrow_right1.gif" alt="" onclick="disPage('.($pageNo+1).');"/>';
				echo '<img src="media/images/arrow_right2.gif" alt="" onclick="disPage('.($noOfPages).');"/>';
				?>
				<!--<a href="javascript:disPage(<?php echo $pageNo+1; ?>)"  onmouseout="javascript:window.status='Done';" onmousemove="javascript:window.status='Go to Next Page';"><img src="images/arrow_right.gif" alt="" /></a>-->
				<?php
				} else {
					echo '<img src="media/images/arrow_right3.gif" alt="" />';
					echo '<img src="media/images/arrow_right4.gif" alt="" />';
				}
				?>
				</div>
			  <div class="clear"></div>
		   </div>	
		<?php	
}

	public function all_changed_unique_id_log()
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$result = array();
		
		$condition = "";
		
		if($_SESSION['user_role']=='teacher')
		{
			
			$condition = "";
			
			$slots = Student::getteacherSlotDtl($_SESSION['user_id']);
			if(count($slots) > 0)
			{
				for($si=0;$si<count($slots);$si++)
				{
					$sid[]= $slots[$si]['id'];
				}
				$sidStr = implode(',',$sid); 
				$condition =" WHERE slot IN( ".$sidStr.")";
			}
			else{
				return $result;
			}
		}
		
		$sql= "SELECT * FROM  unique_user_id_change_log AS log INNER JOIN users_list AS ul ON ul.id=log.user_id ".$condition." ";
		
		
		//die;
		$sql .=" ORDER BY log.id DESC LIMIT 100";
		//echo $sql;
		$q = $db->doQuery($sql);
		
		while($row = mysql_fetch_assoc($q)) {  
			$result[] = $row;
           
        }
		return $result;
		
	}
	 
	
};
?>