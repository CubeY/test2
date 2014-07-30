<?php

/*===========================================================================**
** Class : User
** Created By ; Bidhan
** Utility functions to access MySQL database
** 
**===========================================================================*/
error_reporting(E_ALL & ~E_STRICT);
    
 class User  {
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
    
    public function add_new_user()
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$email = $_POST['email'];
		$user = $_POST['user'];
		$pass = $_POST['pass'];
		$name = $_POST['name'];
		$role = $_POST['role'];
		$line_url = $_POST['line_url'];
		$num_line_url_shown = '';// $_POST['num_line_url_shown'];
		//$max_students = $_POST['max_students'];
		//$teacher_reported_students = $_POST['teacher_reported_students'];
		$day_off_1 = $_POST['day_off_1'];
		//$day_off_2 = $_POST['day_off_2'];
		$profile_en = $_POST['profile_en'];
		$profile_jp = $_POST['profile_jp'];
		$gender = $_POST['gender'];
		$interests = $_POST['interests'];
		$line_url_http = $_POST['line_url_http'];
		$status = $_POST['status'];
		
		//print_r($_POST);die;
		//json_encode($permission);
	
		$sql= "INSERT INTO user_master (`email`, `user`, `pass`, `name`, `role`,`status`) VALUES('$email','$user','$pass','$name','$role','$status')";
		$q = $db->doQuery($sql);
		
		if($role == 'viewer')
		{
			header('location:viewers.php?msg=success');exit;
		}
		$user_id = mysql_insert_id();
		$sql= "INSERT INTO teachers (`teacher_id`,`line_url`, `num_line_url_shown`, `day_off_1`,`day_off_2`,`profile_en`, `profile_jp`, `gender`, `interests`, `line_url_http`) VALUES('$user_id','$line_url','$num_line_url_shown','$day_off_1[0]','$day_off_1[1]','$profile_en','$profile_jp','$gender','$interests','$line_url_http')";
		$q = $db->doQuery($sql);
		//$sql_p= "INSERT INTO user_permission (`user_id`, `permission`) VALUES('$user_id','$permission')";
		//$db->doQuery($sql_p);
		
		
		// create no slot for this user
		$createNoSlot = "INSERT INTO `teacher_slots` (`teacher_id`, `start_time`, `end_time`, `active`, `max_students`,`available_line_url_shows`,`empty_slot`,`coaching`) VALUES('".$user_id."', '00:00:00', '00:00:00','1','8','80','1','0')";
		$db->doQuery($createNoSlot);
		
		// create coaching for this user
		$createNoSlot = "INSERT INTO `teacher_slots` (`teacher_id`, `start_time`, `end_time`, `active`, `max_students`,`available_line_url_shows`,`empty_slot`,`coaching`) VALUES('".$user_id."', '00:00:00', '00:00:00','1','8','80','0','1')";
		$db->doQuery($createNoSlot);
		
		
		if($role == 'student')
		$ur = 'students.php';
		else $ur = 'teachers.php';
		if($q)
		{
			header('location:'.$ur.'?msg=success');exit;
		}
		else
		{
			header('location:'.$ur.'?msg=error');exit;
		}
		
		
		
		
	}
	
	
	
	public function all_user($role = false,$active_only = true,$show_all = false)
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$result = array();
		 $sql= "SELECT * FROM  user_master WHERE id != ''";
		if($role)
		{
			$sql .=" AND role LIKE '%".$role."%'";
		}
		if($show_all)
		{
			
		}
		else
		{
			if($active_only)
			{
				$sql .=" AND status = 'Y'";
			}
			else 
			$sql .=" AND status = 'N'";
		}
		$sql .=" ORDER BY status ASC,id DESC";
		//echo $sql;
		$q = $db->doQuery($sql);
		
		while($row = mysql_fetch_assoc($q)) {  
			$result[] = $row;
           
        }
		return $result;
		
	}
	
	public function singleUserDetails($uid)
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql= "SELECT * FROM  user_master WHERE id = ".$uid."";
		$q = $db->doQuery($sql);
		
		$row = mysql_fetch_assoc($q);
		//print_r($row);
		return $row;
		
	}
	public function getteacherDtl($uid)
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql= "SELECT * FROM  teachers WHERE teacher_id = ".$uid."";
		$q = $db->doQuery($sql);
		
		$row = mysql_fetch_assoc($q);
		return $row;
		
	}
	
	public function get_teacher_num_line_url_shown()
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql= "SELECT teacher_id,num_line_url_shown FROM  teachers ";
		$q = $db->doQuery($sql);
		
		while($row = mysql_fetch_assoc($q))
			$res[$row['teacher_id']] = $row['num_line_url_shown'];
		return $res;//['num_line_url_shown'];
		
	}
	
	public function getteacherSlotDtl($uid)
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		 $sql= "SELECT `id`,`start_time`, `end_time`, `active`,`max_students`,`empty_slot`,`coaching`  FROM  teacher_slots WHERE teacher_id = ".$uid."  ORDER BY `empty_slot`,`start_time` ASC ";
		$q = $db->doQuery($sql);
		$result = array();
		while($row = mysql_fetch_assoc($q)) {  
			$result[] = $row;
           
        }
		return $result;
		
		
	}
	
	
	public function edit_user_html()
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$uid = $_POST['uid'];
		$sql= "SELECT um.id as u_id,name,um.email as u_email,user,pass,line_url,num_line_url_shown,day_off_1,day_off_2,profile_en, profile_jp, gender, interests, line_url_http,status
		 FROM  user_master AS um INNER JOIN teachers AS tech ON um.id=tech.teacher_id WHERE um.id = ".$uid."";
		
		$q = $db->doQuery($sql);
		$row = mysql_fetch_assoc($q);

		
		//print_r($row);
		?>
		<form name="edit_usr_frm_<?php echo $row['u_id'];?>" id="edit_usr_frm_<?php echo $row['u_id'];?>" action="" method="post" >
        <input type="hidden" name="todo"  value="update_user_details"/>
        
        
        <div class="create_new_cls"  >
    <div class="single_row">
        <div class="label_u" style="width:190px;">Name:</div>
        <div><input type="text" class="required" name="u_name" value="<?php echo $row['name'];?>" style="width:300px;"/></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u" style="width:190px;">Email:</div>
        <div><input type="text" class="" name="u_email" value="<?php echo $row['u_email'];?>" style="width:300px;" /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u" style="width:190px;">Username:</div>
        <div><input type="text" class="required" name="u_user" value="<?php echo $row['user'];?>"  style="width:300px;"/></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u" style="width:190px;">Password:</div>
        <div><input type="text" class="required" name="u_pass" value="<?php echo $row['pass'];?>"  style="width:300px;"/></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u" style="width:190px;">Line Url:</div>
        <div><input type="text" class="required" name="line_url" value="<?php echo $row['line_url'];?>" style="width:300px;"/></div>
    </div>
    <!--<div class="clear"></div>
    <div class="single_row">
        <div class="label_u" style="width:190px;">Num Line url shown:</div>
        <div><input type="text" class="required" name="num_line_url_shown" value="<?php echo $row['num_line_url_shown'];?>" style="width:300px;"/></div>
    </div>-->
     <?php /*?><div class="clear"></div>
    <div class="single_row">
        <div class="label_u" style="width:190px;">Max students:</div>
        <div><input type="text" class="required" name="max_students" value="<?php echo $row['max_students'];?>" style="width:300px;"/></div>
    </div>
   <div class="clear"></div>
    <div class="single_row">
        <div class="label_u" style="width:190px;">Teacher reported students:</div>
        <div><input type="text" class="required" name="teacher_reported_students" value="<?php echo $row['teacher_reported_students'];?>" style="width:300px;"/></div>
    </div><?php */?>
    <?php /*?> <div class="clear"></div>
    <div class="single_row">
        <div class="label_u" style="width:190px;">Day off 1:</div>
        <div><input type="text" class="required" name="day_off_1" value="<?php echo $row['day_off_1'];?>" style="width:300px;"/></div>
    </div>
     <div class="clear"></div>
    <div class="single_row">
        <div class="label_u" style="width:190px;">Day off 2:</div>
        <div><input type="text" class="required" name="day_off_2" value="<?php echo $row['day_off_2'];?>" style="width:300px;"/></div>
    </div><?php */?>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Days off:</div>
        <input type="hidden" name="selected_days_off_edit_<?php echo $row['u_id'];?>" id="selected_days_off_edit_<?php echo $row['u_id'];?>" value="<?php echo $row['day_off_1'].",".$row['day_off_2'];?>">
        <div class="days_off_chkbox">
        <input type="checkbox" name="day_off_1[]" value="1" <?php if(($row['day_off_1']==1) || ($row['day_off_2']==1)) echo "checked='checked'";?>/> Monday
        <input type="checkbox" name="day_off_1[]" value="2" <?php if(($row['day_off_1']==2) || ($row['day_off_2']==2)) echo "checked='checked'";?>/> Tuesday
        <input type="checkbox" name="day_off_1[]" value="3" <?php if(($row['day_off_1']==3) || ($row['day_off_2']==3)) echo "checked='checked'";?>/> Wednesday
        <input type="checkbox" name="day_off_1[]" value="4" <?php if(($row['day_off_1']==4) || ($row['day_off_2']==4)) echo "checked='checked'";?>/> Thursday
        <input type="checkbox" name="day_off_1[]" value="5" <?php if(($row['day_off_1']==5) || ($row['day_off_2']==5)) echo "checked='checked'";?>/> Friday
        <input type="checkbox" name="day_off_1[]" value="6" <?php if(($row['day_off_1']==6) || ($row['day_off_2']==6)) echo "checked='checked'";?>/> Saturday
        <input type="checkbox" name="day_off_1[]" value="7" <?php if(($row['day_off_1']==7) || ($row['day_off_2']==7)) echo "checked='checked'";?>/> Sunday
        </div>
    </div>
     <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Profile English:</div>
        <div><textarea class="tx1" name="profile_en"  ><?php echo $row['profile_en'];?></textarea></div>
    </div>
     <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Profile Japan:</div>
        <div><textarea class="tx1" name="profile_jp"  ><?php echo $row['profile_jp'];?></textarea></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Gender:</div>
        <div>
        <select id="gender_<?php echo $row['u_id'];?>" >
            <option value="">Select</option>
            <option value="M" <?php if($row['gender']=='M')  echo "selected='selected'";?> >Male</option>
            <option value="F" <?php if($row['gender']=='F')  echo "selected='selected'";?>>Female</option>
        </select>
        </div>
    </div>
     <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Interests:</div>
        <div><textarea class="tx1" name="interests"  ><?php echo $row['interests'];?></textarea></div>
    </div> <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Line url (http):</div>
        <div><input type="text" class="url" name="line_url_http" value="<?php echo $row['line_url_http'];?>"  /></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u" style="width:190px;">Status:</div>
        <div>Active<input type="radio" name="status" <?php if($row['status']=="Y") echo 'checked="checked"'; ?> value="Y"/>&nbsp;&nbsp;Inactive<input type="radio" name="status" value="N" <?php if($row['status']=="N") echo 'checked="checked"'; ?>/></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">&nbsp;</div>
        <div><input type="submit" value="Save"  />&nbsp;<input type="button" value="Cancel" onClick="HideUsereditBlock(<?php echo $row['u_id'];?>)" /></div>
    </div>
    <div class="clear"></div>
</div>

          
        </form>
		<?php
	}
	
	
	public function edit_viewers_html()
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$uid = $_POST['uid'];
		$sql= "SELECT um.id as u_id,name,um.email as u_email,user,pass,status,role
		 FROM  user_master AS um  WHERE um.id = ".$uid."";
		
		$q = $db->doQuery($sql);
		$row = mysql_fetch_assoc($q);

		
		//print_r($row);
		?>
		<form name="edit_usr_frm_<?php echo $row['u_id'];?>" id="edit_usr_frm_<?php echo $row['u_id'];?>" action="" method="post" >
        <input type="hidden" name="todo"  value="update_user_details"/>
        
        <input type="hidden" name="role" id="role" value="<?php echo $row['role'];?>"/>
        <div class="create_new_cls"  >
    <div class="single_row">
        <div class="label_u" style="width:190px;">Name:</div>
        <div><input type="text" class="required" name="u_name" value="<?php echo $row['name'];?>" style="width:300px;"/></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u" style="width:190px;">Email:</div>
        <div><input type="text" class="" name="u_email" value="<?php echo $row['u_email'];?>" style="width:300px;" /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u" style="width:190px;">Username:</div>
        <div><input type="text" class="required" name="u_user" value="<?php echo $row['user'];?>"  style="width:300px;"/></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u" style="width:190px;">Password:</div>
        <div><input type="text" class="required" name="u_pass" value="<?php echo $row['pass'];?>"  style="width:300px;"/></div>
    </div>
    
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u" style="width:190px;">Status:</div>
        <div>Active<input type="radio" name="status" <?php if($row['status']=="Y") echo 'checked="checked"'; ?> value="Y"/>&nbsp;&nbsp;Inactive<input type="radio" name="status" value="N" <?php if($row['status']=="N") echo 'checked="checked"'; ?>/></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">&nbsp;</div>
        <div><input type="submit" value="Save"  />&nbsp;<input type="button" value="Cancel" onClick="HideUsereditBlock(<?php echo $row['u_id'];?>)" /></div>
    </div>
    <div class="clear"></div>
</div>

          
        </form>
		<?php
	}
	
	public function update_user_dtl()
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$uid = $_POST['uid'];
		$email = $_POST['email'];
		$user = $_POST['user'];
		$pass = $_POST['pass'];
		$name = $_POST['name'];
		$profile_en = User::escapeForSQL($_POST['profile_en']);
		$profile_jp = User::escapeForSQL($_POST['profile_jp']);
		$gender = $_POST['gender'];
		$interests = User::escapeForSQL($_POST['interests']);
		$line_url_http = $_POST['line_url_http'];
		$status = $_POST['status'];

		
		
		$sql= "UPDATE user_master  SET `email` = '".$email."',
										 `user`= '".$user."',
										 `pass`= '".$pass."',
										 `name`= '".$name."',
										 `status` = '".$status."' WHERE id = ".$uid."";
		$q = $db->doQuery($sql);
		
		
		
		if(isset($_POST['role']) && $_POST['role'] == 'viewer') 
		{
			if($q){			
			echo 'success';
			}
			else{
			echo 'error';
			}
			exit;
		}
		
		$line_url = $_POST['line_url'];
		$num_line_url_shown = '';//$_POST['num_line_url_shown'];
		//$max_students =  $_POST['max_students'];
		//$teacher_reported_students = $_POST['teacher_reported_students'];
		$day_off_1 = explode(",",$_POST['day_off_1']);
		//$day_off_2 = $_POST['day_off_2'];
		
		
		$sql= "UPDATE teachers  SET `line_url` = '".$line_url."',
									 `day_off_1`='".$day_off_1[0]."',
									 `day_off_2` = '".$day_off_1[1]."',
									 `profile_en` = '".$profile_en."',
									 `profile_jp` = '".$profile_jp."',
									 `gender` = '".$gender."',
									 `interests` = '".$interests."',
									 `line_url_http` = '".$line_url_http."' WHERE teacher_id = ".$uid."";
		$q = $db->doQuery($sql);
		
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
	
	
	public function delete_user()
	{
	    $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$uid = $email = $_POST['uid'];
		$sql= "DELETE FROM user_master WHERE id = ".$uid."";
		$q = $db->doQuery($sql);
		
		if(isset($_POST['role']) && $_POST['role'] == 'viewer') 
		{
			if($q){			
			echo 'success';
			}
			else{
			echo 'error';
			}
			exit;
		}
		
		$sql2= "DELETE FROM teachers WHERE teacher_id = ".$uid."";
		$db->doQuery($sql2);
		
		
		
		$slots = User::getteacherSlotDtl($uid);
		if(count($slots) > 0 )
		{
			for($islot = 0;count($slots) > $islot; $islot++ )
			{
				$delStudent = "DELETE FROM users_list WHERE slot = ".$slots[$islot]['id'];
				$db->doQuery($delStudent);
			}
			$delSlots = "DELETE FROM teacher_slots WHERE teacher_id = ".$uid;
			$db->doQuery($delSlots);
		}
		
		
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
	
	public function face_photo($uid,$thumb = true , $w = 130 , $h = false)
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql2= "SELECT face_photo FROM teachers WHERE teacher_id = ".$uid."";
		$q = $db->doQuery($sql2);
		$row = mysql_fetch_assoc($q);
		
		$photo =  $row['face_photo'];
		if($photo != '')
		{
			if($thumb)
			$thumb = 'thumbnail/';
			echo '<img class="face_photo_prevw" src="'.SITE_URL.'/face_photo/php/files/'.$thumb.$photo.'" width="'.$w.'" height="'.$h.'" >';
		}
		else
		echo '<img class="face_photo_prevw" src="'.SITE_URL.'/media/images/no_profile_image.png" width="'.$w.'" height="'.$h.'" >';
	}
	
	public function qr_image($uid)
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql2= "SELECT qr_image FROM teachers WHERE teacher_id = ".$uid."";
		$q = $db->doQuery($sql2);
		$row = mysql_fetch_assoc($q);
		
		$qr_image =  $row['qr_image'];
		if($qr_image != '')
		echo '<img class="qr_photo_prevw" src="'.SITE_URL.'/qr_img/php/files/thumbnail/'.$qr_image.'"  >';
		else
		echo '<img class="qr_photo_prevw" src="'.SITE_URL.'/media/images/no_bio_image-150x150.gif" >';
	}
	
	
	public function get_teacher_slot()
	{
	    $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$teacher_id = $_POST['teacher_id'];
		//$sql= "SELECT `id`, `start_time`, `end_time` FROM  teacher_slots WHERE teacher_id = ".$teacher_id." AND active = '1'";
		$sql= "SELECT `id`, `start_time`, `end_time`,`empty_slot`,`coaching` FROM  teacher_slots WHERE teacher_id = ".$teacher_id." ORDER BY `empty_slot`,`start_time` ASC ";
		$q = $db->doQuery($sql);
		$result = array();
		while($row = mysql_fetch_assoc($q)) {  
			$result[] = $row;
           
        }
		return $result;
		
		exit;
	}
	
	public function get_teacher_slotBy_id($teacher_id)
	{
	    $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		//$sql= "SELECT `id`, `start_time`, `end_time` FROM  teacher_slots WHERE teacher_id = ".$teacher_id." AND active = '1'";
		$sql= "SELECT `id`, `start_time`, `end_time`,`max_students`,`num_line_url_shown`,`empty_slot`,`coaching` FROM  teacher_slots WHERE teacher_id = ".$teacher_id." ORDER BY `empty_slot`,`start_time` ASC ";
		$q = $db->doQuery($sql);
		$result = array();
		while($row = mysql_fetch_assoc($q)) {  
			$result[] = $row;
           
        }
		return $result;
		
		exit;
	}
	
	public function getTeacherNameBySlotId($slot_id)
	{
	    $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		//$sql= "SELECT `id`, `start_time`, `end_time` FROM  teacher_slots WHERE teacher_id = ".$teacher_id." AND active = '1'";
		$sql= "SELECT name FROM user_master AS um INNER JOIN teacher_slots as ts ON um.id=ts.teacher_id WHERE ts.id = ".$slot_id." ";
		$q = $db->doQuery($sql);
		$row = mysql_fetch_assoc($q);
		
		echo $row['name'];
		
	}
	
	
	 
	public function isadmin($user_id)
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql ="SELECT role FROM user_master WHERE id =".$user_id;
		$q = $db->doQuery($sql);
		$row = mysql_fetch_assoc($q);
		if($row['role'] == 'administrator')
			return true;
		else 
			return false;
	}
	
	//n_owc
	
	public function add_new_slot()
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$start_time = $_POST['start_time'];
		$end_time = $_POST['end_time'];
		$teacher_id = $_POST['teacher_id'];
		$status = $_POST['status'];
		$no_slot_value = 0;
		
		//print_r($_POST);
		//json_encode($permission);
		
		$sql= "INSERT INTO teacher_slots (`teacher_id`, `start_time`, `end_time`, `active`,`max_students`,`available_line_url_shows`,`empty_slot`) VALUES('$teacher_id','$start_time','$end_time','$status','8','80','0')";
		$q = $db->doQuery($sql);
		//$user_id = mysql_insert_id();
		//$sql_p= "INSERT INTO user_permission (`user_id`, `permission`) VALUES('$user_id','$permission')";
		//$db->doQuery($sql_p);
		
		if($role == 'student')
		$ur = 'students.php';
		else $ur = 'slot.php?teacher_id='.$teacher_id;
		if($q)
		{
			header('location:'.$ur.'&msg=success');exit;
		}
		else
		{
			header('location:'.$ur.'?msg=error');exit;
		}
	}
	
	public function delete_slot()
	{
	    $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$slot_id = $_POST['slot_id'];
		$sql= "DELETE FROM teacher_slots WHERE id = ".$slot_id."";
		$q = $db->doQuery($sql);
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
	
	public function edit_slot_html()
	{
		session_start();
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$slot_id = $_POST['slot_id'];
		$sql= "SELECT * FROM teacher_slots WHERE id = ".$slot_id."";
		
		$q = $db->doQuery($sql);
		$row = mysql_fetch_assoc($q);
		
		//print_r($row);
		//echo $row['active'];
		?>

		<form name="edit_usr_frm_<?php echo $row['id'];?>" id="edit_usr_frm_<?php echo $row['id'];?>" action="" method="post" >
        <input type="hidden" name="todo"  value="update_slot_details"/>
        <input type="hidden" name="empty_slot_value"  id="empty_slot_value_<?php echo $row['id'];?>" value="<?php echo $row['empty_slot']; ?>"/>
        
   <?php 
    $no_slot_already = $row['empty_slot'];
    if($no_slot_already != 1 )
    {
	?>  
    <div class="create_new_cls" >
    <div class="single_row">
        <div class="label_u">Start Time:</div>
        <div><input type="text" class="required" name="start_time" id="start_time_<?php echo $row['id'];?>" value="<?php echo substr($row['start_time'],0,5);?>" style="width:75px" onblur="check_time_picker_start_value_edit('<?php echo $row['id'];?>');"/></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">End Time:</div>
        <div><input type="text" class="required" name="end_time" id="end_time_<?php echo $row['id'];?>" value="<?php echo substr($row['end_time'],0,5);?>" style="width:75px" onblur="check_time_picker_end_value_edit('<?php echo $row['id'];?>');"/></div>
    </div>
    <?php } ?>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Status:</div>
        <div>Active<input type="radio" name="status" value="1" <?php if($row['active']=='1') echo 'checked="checked"'; ?> />&nbsp;&nbsp;Inactive<input type="radio" name="status" value="0" <?php if($row['active']=='0') echo 'checked="checked"' ; ?>/></div>
    </div>
    <?php if($_SESSION['user_role'] == 'administrator')
	{ ?>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Maximum Students:</div>
        <div><input type="text" class="required" name="max_students" id="max_students_<?php echo $row['id'];?>" value="<?php echo $row['max_students'];?>" style="width:75px"/></div>
    </div>
    <?php } ?>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">&nbsp;</div>
        <div><input type="submit" value="Save"  />&nbsp;<input type="button" value="Cancel" onClick="HideUsereditBlock(<?php echo $row['id'];?>)" /></div>
    </div>
    <div class="clear"></div>
</div>

          
        </form>
		<?php
	}
	
	public function update_slot_dtl()
	{
		
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$slot_id = $_POST['slot_id'];
		
		$max_students = $_POST['max_students'];
		$status = $_POST['status'];
		
			$start_time = (isset($_POST['start_time']))?$_POST['start_time']:'';
			$end_time = (isset($_POST['end_time']))?$_POST['end_time']:'';
			$sql= "UPDATE teacher_slots  SET `start_time` = '".$start_time."',
										 `end_time`= '".$end_time."',";
										 if($_SESSION['user_role'] == 'administrator' ){
										 $sql.=" `max_students`= '".$max_students."',";
										 }
										 $sql .=" `active` = '".$status."' WHERE id = ".$slot_id.""; 
		//echo $sql;
		$q = $db->doQuery($sql);
		
		User::update_available_line_url_shows($slot_id);
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
	
	
	public function update_available_line_url_shows($slot_id)
	{
		//echo $slot_id;
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		if($slot_id > 0)
		{
		
		// Active students mean  (# of paying + # of In F.T. )
		$sql= "SELECT count(id) AS total FROM users_list WHERE (life_status='working' || life_status='unknown') AND slot = ".$slot_id." AND status IN(1,3,7)"; // 1= initial message sent  3 = in FT and 7 = paying 
		$q = $db->doQuery($sql);
		$r =mysql_fetch_assoc($q);
		$total_activeUser = $r['total'];
		
		$sql= "SELECT count(id) AS total FROM users_list WHERE (life_status!='working' AND life_status!='unknown') AND slot = ".$slot_id." AND status IN(1,3,7)"; // 1= initial message sent  3 = in FT and 7 = paying 
		$q = $db->doQuery($sql);
		$r =mysql_fetch_assoc($q);
		$total_activeUser += 0.3 * $r['total'];

		$slotDtl = User::slotDtl($slot_id);
		$max_students = $slotDtl['max_students'];
		$teacherId = $slotDtl['id'];

		$total_activeUser += User::pro_rata_of_no_slot_students_for_teacher($teacherId);
		
		//max_students - ACTIVE STUDENTS (get that from the users_list table for the GIVEN SLOT) * 10 = available_line_url_shows
		$available_line_url_shows = round(( $max_students - $total_activeUser ) *10);
		//print_r($slotDtl);
		if($available_line_url_shows >= 0)
		{
			$update  = "UPDATE `teacher_slots` SET `available_line_url_shows` = '$available_line_url_shows' WHERE id = '$slot_id'";
			$db->doQuery($update);
		}
		
		}
		
	}
	
	public function pro_rata_of_no_slot_students_for_teacher($teacher_id)
	{
				
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);

		$sql= "SELECT * FROM teacher_slots WHERE empty_slot=1 AND teacher_id = ".$teacher_id; 
		$q = $db->doQuery($sql);
		$r =mysql_fetch_assoc($q);
		$not_assigned_slot_id = $r['id'];

		$sql= "SELECT count(*) as total FROM teacher_slots WHERE empty_slot=0 AND teacher_id = ".$teacher_id; 
		$q = $db->doQuery($sql);
		$r =mysql_fetch_assoc($q);
		$total_slots_for_teacher = $r['total'];

		$sql= "SELECT count(*) as total FROM users_list WHERE status IN(1,3,7) AND slot= ".$not_assigned_slot_id; 
		$q = $db->doQuery($sql);
		$r =mysql_fetch_assoc($q);
		$studnets_with_no_slot = $r['total'];

		return $studnets_with_no_slot/$total_slots_for_teacher;
	}
	
	public function slotDtl($slotId)
	{
		if($slotId > 0) {
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$sql= "SELECT TS.start_time,TS.end_time ,TS.active,TS.max_students,TS.num_line_url_shown,TS.available_line_url_shows,U.id,U.name FROM user_master AS U LEFT JOIN teacher_slots AS TS  ON (U.id=TS.teacher_id) WHERE TS.id = ".$slotId;
		//echo $sql;
		$q = $db->doQuery($sql);
		
		$row = mysql_fetch_assoc($q);
           
       
		return $row;
		}
		return false;
		
	}
	
	public function count_total_slot($user_id)
	{
	    $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$sql= "select count(id) as total  FROM teacher_slots WHERE teacher_id = ".$user_id."";
		$q = $db->doQuery($sql);
		
		
			$aa =   mysql_fetch_assoc($q);
			//print_r($aa);
			return $aa['total'];
		
		//exit;
	}
	
	public function count_total_student($user_id)
	{
	    $acdb = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		  
		$sql= "select id FROM teacher_slots WHERE teacher_id = ".$user_id."";
		$q = $acdb->doQuery($sql);
		
		if(mysql_num_rows($q) > 0)
		{
			while($res = mysql_fetch_assoc($q))
			 {
				//print_r($res);
				$slots[] =  $res['id'];
			 }
			 //print_r($slots);
			 $slots =  implode(',',$slots);
		///	echo $slots; 
		 $sql2 = "SELECT count(id) as total FROM `users_list` WHERE slot IN(".$slots.")";
		$q2 = $acdb->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];
		}
		else 
		{
			return 0;
		}
			 
			// echo $slots;
			//print_r($aa);
			//return $aa['total'];
		
		//exit;
	}
	
	public function count_total_student_by_life_status($life_status,$active_only = false)
	{
		 $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		 $sql2 = "SELECT count(id) as total FROM `users_list` WHERE life_status = '".$life_status."' ";
		
		 if($active_only)
		 $sql2 .= " AND status IN(1,3,7)";
		
		$q2 = $db->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];
	}
	
	public function count_total_student_by_engagement_level($engagement_level,$active_only = false)
	{
		 $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		 $sql2 = "SELECT count(id) as total FROM `users_list` WHERE engagement_level = '".$engagement_level."' ";
		
		 if($active_only)
		 $sql2 .= " AND status IN(1,3,7)";
		
		$q2 = $db->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];
	}
	
	
	public function count_active_student_by_life_status($life_status)
	{
		 $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		 $sql2 = "SELECT count(id) as total FROM `users_list` WHERE life_status = '".$life_status."' AND status IN (1,3,7)";
		$q2 = $db->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];
	}
	
	public function sum_of_each_teachers_max_capacity()
	{
		 $acdb = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		  
		$sql= "select SUM(`max_students`) as total FROM teacher_slots AS TS JOIN `user_master` AS U ON(U.id=TS.teacher_id) WHERE U.status = 'Y' AND TS.empty_slot =0";
		$q = $acdb->doQuery($sql);
		$res = mysql_fetch_assoc($q);
		return $res['total'];
			
	}
	
	public function get_japan_time_now()
	{
		date_default_timezone_set("Asia/Tokyo");
		return date('Y-m-d H:i:s');
	}
	
	public function change_slot_status()
	{
		$acdb = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$teacher_id = $_POST['teacher_id'];
		$status = $_POST['status'];
		$sql= "UPDATE teacher_slots SET active = ".$status." WHERE teacher_id = ".$teacher_id."";
		$q = $acdb->doQuery($sql);
	}
	
	public function count_total_active_student($teacher_id)
	{
		 $acdb = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		  
		$sql= "select id FROM teacher_slots WHERE teacher_id = ".$teacher_id."";
		$q = $acdb->doQuery($sql);
		
		if(mysql_num_rows($q) > 0)
		{
			while($res = mysql_fetch_assoc($q))
			 {
				//print_r($res);
				$slots[] =  $res['id'];
			 }
			 //print_r($slots);
			 $slots =  implode(',',$slots);
		///	echo $slots; 
		 $sql2 = "SELECT count(id) as total FROM `users_list` WHERE slot IN(".$slots.") AND status IN(1,3,7)";// 1 = initial message sent 3 = in FT and 7 = paying 
		$q2 = $acdb->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];
		}
		else 
		{
			return 0;
		}
	}
	
	public function total_active_student()
	{
		$acdb = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql2 = "SELECT count(id) as total FROM `users_list` WHERE status IN(1,3,7)";// 1 = initial message sent 3 = in FT and 7 = paying 
		$q2 = $acdb->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];
	}
	public function count_total_payingUsers($user_id)
	{
	    $acdb = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		  
		$sql= "select id FROM teacher_slots WHERE teacher_id = ".$user_id."";
		$q = $acdb->doQuery($sql);
		
		if(mysql_num_rows($q) > 0)
		{
			while($res = mysql_fetch_assoc($q))
			 {
				//print_r($res);
				$slots[] =  $res['id'];
			 }
			 //print_r($slots);
			 $slots =  implode(',',$slots);
		///	echo $slots; 
		 $sql2 = "SELECT count(id) as total FROM `users_list` WHERE slot IN(".$slots.") AND status = 7 ";
		$q2 = $acdb->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];
		}
		else 
		{
			return 0;
		}
			 
			// echo $slots;
			//print_r($aa);
			//return $aa['total'];
		
		//exit;
	}
	
	public function imploded_teacher_slots($teacher_id)
	{
		$acdb = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql= "select id FROM teacher_slots WHERE teacher_id = ".$teacher_id."";
		$q = $acdb->doQuery($sql);
		
		if(mysql_num_rows($q) > 0)
		{
			while($res = mysql_fetch_assoc($q))
			 {
				//print_r($res);
				$slots[] =  $res['id'];
			 }
			 //print_r($slots);
			 $slots =  implode(',',$slots);
		}
		return $slots;
	}
	
	
	public function pay_info_not_sent_students_list($teacher_slots )
	{
		$acdb = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql= "select unique_user_id FROM users_list WHERE slot IN(".$teacher_slots.") AND payment_info_sent = 0 AND status = 6 ";
		$q = $acdb->doQuery($sql);
		
		$r_ary = array();
		if(mysql_num_rows($q) > 0)
		{
			while($res = mysql_fetch_assoc($q))
			 {
				//print_r($res);
				$r_ary[] =  $res['unique_user_id'];
			 }
		}
		return $r_ary;
	}
	
	
	
	public function total_after_FT_users_by_teacher_id($teacher_id = false)
	{
	    $acdb = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$sql2 = "SELECT count(users_list.id) as total FROM users_list join teacher_slots on users_list.slot=teacher_slots.id WHERE users_list.status IN (4,6,7,8)";
		if($teacher_id)
		$sql2 .= "AND teacher_slots.teacher_id = ".$teacher_id;
		$q2 = $acdb->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];
		
	}

	public function total_responsive_after_FT_users_by_teacher_id($teacher_id = false)
	{
	    $acdb = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$sql2 = "SELECT count(users_list.id) as total FROM users_list join teacher_slots on users_list.slot=teacher_slots.id WHERE users_list.status IN (6,7,8)"; 
		if($teacher_id)
		$sql2 .= "AND teacher_slots.teacher_id = ".$teacher_id;
		$q2 = $acdb->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];
		
	}

	public function converted_users_by_teacher_id($teacher_id = false)
	{
	    $acdb = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$sql2 = "SELECT count(users_list.id) as total FROM users_list join teacher_slots on users_list.slot=teacher_slots.id WHERE users_list.status IN (7,8) ";
		if($teacher_id)
		$sql2 .= "AND teacher_slots.teacher_id = ".$teacher_id;
		$q2 = $acdb->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];
		
	}

	public function total_users_by_status_id($status)
	{
	    $acdb = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$sql2 = "SELECT count(id) as total FROM `users_list` WHERE status = ".$status;
		$q2 = $acdb->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];
		
	}
	
	public function total_users_in_array()
	{
	    $acdb = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$tt= 0 ;
		$sw_teacher_id = '';
		$total = 0;
		$sw_status = '';
		$sql2 = "SELECT TS.teacher_id AS teacher_id, slot,status , COUNT( UL.id ) AS total FROM `users_list` AS UL	
					LEFT JOIN teacher_slots AS TS ON ( UL.slot = TS.id ) WHERE UL.slot !=0
					GROUP BY UL.slot, UL.status ORDER BY `TS`.`teacher_id` ASC  ,  `UL`.`status` ASC";
		$q2 = $acdb->doQuery($sql2);
		$i=0;
		$sw_i = 0;
		while($res = mysql_fetch_assoc($q2))
		{
			if($sw_teacher_id != $res['teacher_id'])
			{
				$sw_teacher_id = $res['teacher_id'];
				$sw_status = $res['status'];
				
				for($si=1;$si<=9;$si++)
				{
					if($sw_status == $si)
					$st_ary[$si] = $res['total'];
					else
					$st_ary[$si] = 0;
				}
				$teachers[$sw_teacher_id] = array('status'=>$st_ary);
				//$slots[] =  array('teacher_id'=>$sw_teacher_id);
			$sw_i = $i;
			}
			else
			{	
				if($sw_status == $res['status'])
				{
					//echo '<pre>';
					//print_r($teachers) ;
					$teachers[$sw_teacher_id]['status'][$res['status']] = $teachers[$sw_teacher_id]['status'][$res['status']]+$res['total'];
				}
				else
				{
					$teachers[$sw_teacher_id]['status'][$res['status']] = $res['total']  ;
					$sw_status = $res['status'];
				}
				//$sw_total = 0;
				
				//$teachers[$i]['teacher_id']=>$sw_teacher_id['slot']=$res['slot'];
			}
			$i++;
		}
		//echo $tt;
		return  $teachers;
		
	}
	
	
	public function total_users_by_status_id_and_teacher_slots($status,$slots)
	{
	    $acdb = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$sql2 = "SELECT count(id) as total FROM `users_list` WHERE status = ".$status." AND slot IN(".$slots.") ";
		$q2 = $acdb->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];
		
	}
	
	public function total_payment_Info_Sent_By_SlotId($slot_id)
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql ="SELECT COUNT(id)  as total FROM `users_list`  WHERE slot = ".$slot_id." AND payment_info_sent = 1 " ;
		$q = $db->doQuery($sql);
		$r = mysql_fetch_assoc($q);
		return $r['total'];
		
		
	}
	
	public function count_total_student_byslot($slot)
	{
	  $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		 
		$sql2 = "SELECT count(id) as total FROM `users_list` WHERE slot = $slot";
		$q2 = $db->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];
		
			 
			// echo $slots;
			//print_r($aa);
			//return $aa['total'];
		
		//exit;
	}
	public function count_total_active_student_byslot($slot)
	{
	  $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		 
		$sql2 = "SELECT count(id) as total FROM `users_list` WHERE `slot` = $slot  AND `status` IN(1,3,7)";//1 = initial message sent 3 = in FT and 7 = paying 
		$q2 = $db->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];
		
			 
			// echo $slots;
			//print_r($aa);
			//return $aa['total'];
		
		//exit;
	}
	
	public function new_user_paying_today_by_slot($slot,$date)
	{
	  $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql2 = "SELECT count(id) as total FROM `users_list` WHERE `slot` IN(".$slot.")  AND DATEDIFF(`first_paid_date`,'".$date."') = 0";//1 = initial message sent 3 = in FT and 7 = paying 
		$q2 = $db->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];

	}
	
	public function new_user_paying_today_in_array($date)
	{
	  $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$res = array();
		$sql = "SELECT TS.teacher_id AS teacher_id , COUNT( UL.id ) AS total FROM `users_list` AS UL	
					LEFT JOIN teacher_slots AS TS ON ( UL.slot = TS.id ) WHERE DATEDIFF(`first_paid_date`,'".$date."') = 0 AND TS.teacher_id != ''
					GROUP BY TS.teacher_id ORDER BY  TS.teacher_id ASC";//1 = initial message sent 3 = in FT and 7 = paying 
		$q = $db->doQuery($sql);
		while($r = mysql_fetch_assoc($q))
		$res[$r['teacher_id']] = $r['total'];
		return $res;

	}
	
	
	public function total_payment_info_sent()
	{
	  $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql2 = "SELECT count(id) as total FROM `users_list` WHERE `payment_info_sent` =1 ";
		$q2 = $db->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];

	}
	
	public function cancelations_of_payment_today_by_slot($slot,$date)
	{
	  $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql2 = "SELECT count(id) as total FROM `users_list` WHERE `slot` IN(".$slot.")  AND DATEDIFF(`cancellation_date`,'".$date."') = 0";//1 = initial message sent 3 = in FT and 7 = paying 
		$q2 = $db->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];

	}
	
	public function cancelations_of_payment_today_in_array($date)
	{
	   $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$res = array();
		$sql = "SELECT TS.teacher_id AS teacher_id , COUNT( UL.id ) AS total FROM `users_list` AS UL	
					LEFT JOIN teacher_slots AS TS ON ( UL.slot = TS.id ) WHERE DATEDIFF(`cancellation_date`,'".$date."') = 0 AND TS.teacher_id != ''
					GROUP BY TS.teacher_id ORDER BY  TS.teacher_id ASC";
		$q = $db->doQuery($sql);
		while($r = mysql_fetch_assoc($q))
		$res[$r['teacher_id']] = $r['total'];
		return $res;
		
	}
	
	
	public function get_total_no_responded_student($slot)
	{
	  $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql2 = "SELECT count(id) as total FROM `users_list` WHERE `slot` IN(".$slot.") AND id IN(
					SELECT `user_id` FROM `statuses_change_log` WHERE user_id IN(
						SELECT id FROM `users_list` WHERE `slot` IN(".$slot.")) AND (old_status= 3 OR new_status = 3)) ";
		$q2 = $db->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];

	}
	
	public function calculate_churn($slot,$datef)
	{
	    $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$ar =array();
		$sql2 = "SELECT DATEDIFF(IF(cancellation_date='0000-00-00 00:00:00','".$datef."',cancellation_date ),first_paid_date)  AS dif FROM`users_list` WHERE  `slot` IN(".$slot.") AND `first_paid_date` != '0000-00-00 00:00:00' AND status = 8 ";
		$q2 = $db->doQuery($sql2);
		$t= 0;
		while($res2 = mysql_fetch_assoc($q2))
		{
			$ar[]= $res2['dif'];
			$t= $t+$res2['dif'];
		}
		//if($slot == 83)
		//print_r($ar);
		
		$r['total']= $t;
		//$r['t_fpd']= count($ar);
		//$r['avg']= (count($ar)>0)?round($t/count($ar),2):0;
		
		return  $r;

	}
	
	public function calculate_churn_in_array($datef)
	{
	    $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$res = array();
		$sql = "SELECT TS.teacher_id AS teacher_id , SUM(DATEDIFF(IF(cancellation_date='0000-00-00 00:00:00','".$datef."',cancellation_date ),first_paid_date))  AS total FROM `users_list` AS UL	
					LEFT JOIN teacher_slots AS TS ON ( UL.slot = TS.id ) WHERE `first_paid_date` != '0000-00-00 00:00:00' AND status = 8 AND TS.teacher_id != ''
					GROUP BY TS.teacher_id ORDER BY  TS.teacher_id ASC";
		$q = $db->doQuery($sql);
		while($r = mysql_fetch_assoc($q))
		$res[$r['teacher_id']] = $r['total'];
		return $res;
		
		
		

	}
	
	
	public function calculate_churn_paying($slot,$datef)
	{
	    $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$ar =array();
		$sql2 = "SELECT DATEDIFF(IF(cancellation_date='0000-00-00 00:00:00','".$datef."',cancellation_date ),first_paid_date)  AS dif FROM`users_list` WHERE  `slot` IN(".$slot.") AND `first_paid_date` != '0000-00-00 00:00:00' AND status = 7 ";
		$q2 = $db->doQuery($sql2);
		$t= 0;
		while($res2 = mysql_fetch_assoc($q2))
		{
			$ar[]= $res2['dif'];
			$t= $t+$res2['dif'];
		}
		//if($slot == 83)
		//print_r($ar);
		
		$r['total']= $t;
		//$r['t_fpd']= count($ar);
		//$r['avg']= (count($ar)>0)?round($t/count($ar),2):0;
		
		return  $r;

	}
	
	public function calculate_churn_paying_in_array($datef)
	{
	    $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$res = array();
		$sql = "SELECT TS.teacher_id AS teacher_id , SUM(DATEDIFF(IF(cancellation_date='0000-00-00 00:00:00','".$datef."',cancellation_date ),first_paid_date))  AS total FROM `users_list` AS UL	
					LEFT JOIN teacher_slots AS TS ON ( UL.slot = TS.id ) WHERE `first_paid_date` != '0000-00-00 00:00:00' AND status = 7 AND TS.teacher_id != ''
					GROUP BY TS.teacher_id ORDER BY  TS.teacher_id ASC";
		$q = $db->doQuery($sql);
		while($r = mysql_fetch_assoc($q))
		$res[$r['teacher_id']] = $r['total'];
		return $res;
	}
	
	
	public function total_student_by_date($datef,$col,$dayDif)
	{
	   $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$sql2 = "SELECT  COUNT(`id`) AS total , DATEDIFF('".$datef."',".$col." ) AS df FROM`users_list` WHERE DATEDIFF('".$datef."',".$col." ) <= $dayDif";
		if($dayDif == 0)
		$sql2 .= " AND  DATEDIFF('".$datef."',".$col." ) >= 0 ";
		else 
		$sql2 .= " AND  DATEDIFF('".$datef."',".$col." ) > 0 ";
		
		if($col == 'payment_info_sent_date')
		{
			$sql2.=" AND  payment_info_sent = 1";
		}
		//echo $sql2;
		$q2 = $db->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];

	}
	
	public function total_student_by_date_lus($datef,$col,$dayDif)
	{
	   $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$sql2 = "SELECT  COUNT(`id`) AS total , DATEDIFF('".$datef."',shown_date ) AS df FROM`line_url_shown` WHERE DATEDIFF('".$datef."',shown_date ) <= $dayDif";
		if($dayDif == 0)
		$sql2 .= " AND  DATEDIFF('".$datef."',shown_date ) >= 0 ";
		else 
		$sql2 .= " AND  DATEDIFF('".$datef."',shown_date ) > 0 ";
		
		
		if($dayDif == -1)
		$sql2 = "SELECT  COUNT(`id`) AS total FROM`line_url_shown`";
		
		//echo $sql2;
		$q2 = $db->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];

	}
	
	
	public function total_student_by_date_and_teachers($datef,$col,$dayDif,$teacher_id)
	{
	   $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$all_slots = User::imploded_teacher_slots($teacher_id);
		$sql2 = "SELECT  COUNT(`id`) AS total , DATEDIFF('".$datef."',".$col." ) AS df FROM`users_list` WHERE slot IN(".$all_slots.") AND DATEDIFF('".$datef."',".$col." ) <= $dayDif";
		if($dayDif == 0)
		$sql2 .= " AND  DATEDIFF('".$datef."',".$col." ) >= 0 ";
		else 
		$sql2 .= " AND  DATEDIFF('".$datef."',".$col." ) > 0 ";
		
		if($col == 'payment_info_sent_date')
		{
			$sql2.=" AND  payment_info_sent = 1";
		}
		//echo $sql2;
		$q2 = $db->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];

	}
	
	public function total_student_by_date_from_logs_tbl($datef,$col,$status,$dayDif)
	{
	  $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql2 = "SELECT  COUNT(DISTINCT `user_id`) AS total , DATEDIFF('".$datef."',".$col." ) AS df FROM `statuses_change_log`  AS SCL
		JOIN users_list AS UL ON (UL.id=SCL.user_id)
		WHERE DATEDIFF('".$datef."',".$col." ) <= $dayDif ";
		if($dayDif == 0)
		$sql2 .= " AND  DATEDIFF('".$datef."',".$col." ) >= 0 ";
		else 
		$sql2 .= " AND  DATEDIFF('".$datef."',".$col." ) > 0 ";
		
		$sql2 .= " AND new_status = ".$status." AND UL.status=".$status."";
		
	//	echo $sql2;
		$q2 = $db->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];

	}
	
	public function total_student_by_date_and_teachers_from_logs_tbl($datef,$col,$status,$dayDif,$teacher_id)
	{
	  $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$all_slots = User::imploded_teacher_slots($teacher_id);
		
		$sql2 = "SELECT  COUNT(DISTINCT `user_id`) AS total , DATEDIFF('".$datef."',".$col." ) AS df FROM `statuses_change_log`  AS SCL
		JOIN users_list AS UL ON (UL.id=SCL.user_id)
		WHERE UL.slot IN(".$all_slots.") AND DATEDIFF('".$datef."',".$col." ) <= $dayDif ";
		if($dayDif == 0)
		$sql2 .= " AND  DATEDIFF('".$datef."',".$col." ) >= 0 ";
		else 
		$sql2 .= " AND  DATEDIFF('".$datef."',".$col." ) > 0 ";
		
		$sql2 .= " AND new_status = ".$status." AND UL.status=".$status."";
		
		//echo $sql2;
		$q2 = $db->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];

	}
	
	
	public function total_student_by_each_date_from_logs_tbl($dateUpto,$status)
	{
	  $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		 $sql2 = "SELECT  COUNT(DISTINCT `user_id`) AS total ,DATE_FORMAT( `chenged_date` , '%Y-%m-%d' ) AS chenged_date FROM`statuses_change_log`  AS SCL
		JOIN users_list AS UL ON (UL.id=SCL.user_id)
		WHERE `chenged_date` > '".$dateUpto."' AND  new_status = ".$status." AND UL.status=".$status."
		GROUP BY DATE_FORMAT( `chenged_date` , '%Y-%m-%d' )
					ORDER BY `chenged_date` DESC
					";
		
		
		$q = $db->doQuery($sql2);
		$ary = array();
		if(mysql_num_rows($q) > 0){
		while($r = mysql_fetch_assoc($q))
		{
			$ary[] = array('chenged_date'=>$r['chenged_date'],'total'=>$r['total']);
		}}
		else{$ary[] = array('chenged_date'=>'','total'=>0);}
		return $ary;

	}
	
	
	public function total_student_each_date_cal($dateUpto,$col,$order_By = false)
	{
		 $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		 $ary = array();
		 $sql = "SELECT COUNT( id ) AS total, DATE_FORMAT( `".$col."` , '%Y-%m-%d' ) AS ".$col."
					FROM `users_list` WHERE `".$col."` > '".$dateUpto."'
					GROUP BY DATE_FORMAT( `".$col."` , '%Y-%m-%d' )
					ORDER BY `users_list`.`".$col."`";
					if($order_By)
					 $sql .= $order_By;
					else 
					$sql .= " DESC ";
					$sql .= " LIMIT 0 , 30";
					
		$q = $db->doQuery($sql);
		$ary = array();
		if(mysql_num_rows($q) > 0){
		while($r = mysql_fetch_assoc($q))
		{
			$ary[] = array($col=>$r[$col],'total'=>$r['total']);
		}}
		else{$ary[] = array($col=>'','total'=>0);}
		return $ary;
	}
	
	public function total_student_each_date_line_url_shown($dateUpto)
	{
		 $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		 $ary = array();
		 $sql = "SELECT COUNT( id ) AS total, DATE_FORMAT( `shown_date` , '%Y-%m-%d' ) AS shown_date
					FROM `line_url_shown` WHERE `shown_date` > '".$dateUpto."'
					GROUP BY DATE_FORMAT( `shown_date` , '%Y-%m-%d' )
					ORDER BY `line_url_shown`.`shown_date` DESC  LIMIT 0 , 30";
					
		$q = $db->doQuery($sql);
		$ary = array();
		if(mysql_num_rows($q) > 0){
		while($r = mysql_fetch_assoc($q))
		{
			$ary[] = array('shown_date'=>$r['shown_date'],'total'=>$r['total']);
		}}
		else{$ary[] = array('shown_date'=>'','total'=>0);}
		return $ary;
	}
	
	
	
	public function total_student_each_date($dateUpto)
	{
		 $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  $ary = array();
		 // Join date ======================================
		 $ary['created_on'] = User:: total_student_each_date_cal($dateUpto,'created_on');
		
		// Join FT ============================================
		$ary['first_response'] = User:: total_student_each_date_cal($dateUpto,'first_response');
		
		// Converted to paying =======================================
		$ary['first_paid_date'] = User:: total_student_each_date_cal($dateUpto,'first_paid_date');
		
		
		// payment info sent ==================================================
		$ary['payment_info_sent_date'] = User:: total_student_each_date_cal($dateUpto,'payment_info_sent_date');
		
		
		// FT ft_expiration ========================================================
		//$ary['ft_expiration'] = User:: total_student_each_date_cal($dateUpto,'ft_expiration');
		$ary['ft_expiration'] = User::total_student_by_each_date_from_logs_tbl($dateUpto,$status=6);
		
		//Registration link sent====================================================
		$ary['reg_link_sent'] = User::total_student_by_each_date_from_logs_tbl($dateUpto,$status=9);
		
		//FT expired w/o response===========================================
		$ary['ft_exp_ow_res'] = User::total_student_by_each_date_from_logs_tbl($dateUpto,$status=4);
		
		// Cancelled=======================================================
		$ary['cancellation_date'] = User:: total_student_each_date_cal($dateUpto,'cancellation_date');
		
		// Line Url Shown (From line_url_shown)=======================================================
		$ary['line_url_shown'] = User:: total_student_each_date_line_url_shown($dateUpto);
		
		
		return json_encode( $ary );
	}
	public function total_student_each_future_date($dateUpto)
	{
		 $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  $ary = array();
		
		// FT ft_expiration ========================================================
		$ary['ft_expiration'] = User:: total_student_each_date_cal($dateUpto,'ft_expiration',$order_By = 'ASC');
		
		
		return json_encode( $ary );
	}
	
	public function total_student_each_future_date_exp_wo($datef)
	{
		 $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		//$sql_day = "SELECT id,status FROM `users_list` WHERE DATEDIFF('".$date."',`created_on`) > 6
		// AND status != 7 AND status != 8  AND status !=6 AND status = 2 AND status !=4 AND id NOT IN ( SELECT `user_id` FROM `statuses_change_log`)"; 
	$futureDate = date('Y-m-d', strtotime($datef . ' + 7 day'));
	
	  $sql = "SELECT COUNT( id ) AS total, DATE_FORMAT( `created_on` , '%Y-%m-%d' ) AS created_on
					FROM `users_list` WHERE DATEDIFF('".$datef."', `created_on` ) < 6 AND status = 2 
					GROUP BY DATE_FORMAT( `created_on` , '%Y-%m-%d' )
					ORDER BY `users_list`.`created_on` ASC  LIMIT 0 , 8";
		
		$q = $db->doQuery($sql);
		$ary = array();
		if(mysql_num_rows($q) > 0){
		while($r = mysql_fetch_assoc($q))
		{
			$ary['exp_wo'][] = array('exp_wo'=>$r['created_on'],'total'=>$r['total']);
		}}
		else{$ary['exp_wo'][] = array('exp_wo'=>'','total'=>0);}
		return json_encode( $ary );
					
	}
	
	
	public function count_total_expired_student_byslot($slot)
	{
	  $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		 
		$sql2 = "SELECT count(id) as total FROM `users_list` WHERE slot = $slot  AND status = 6";// 6 = F.T. expired
		$q2 = $db->doQuery($sql2);
		$res2 = mysql_fetch_assoc($q2);
		//print_r($res2);
		return  $res2['total'];
			// echo $slots;
			//print_r($aa);
			//return $aa['total'];
		
		//exit;
	}
	
	
	
	public function all_status()
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql= "SELECT * FROM  statuses";
		$sql .=" ORDER BY id ASC";
		//echo $sql;
		$q = $db->doQuery($sql);
		
		while($row = mysql_fetch_assoc($q)) {  
			$result[] = $row;
           
        }
		return $result;
		
	}
	
	public function getStatusNameByID($id)
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql= "SELECT name FROM  statuses WHERE id=".$id."";
		//echo $sql;
		$q = $db->doQuery($sql);
		$row = mysql_fetch_assoc($q);
		return $row['name'];
		
	}
	public function checkForNoSlot($id)
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql= "SELECT id FROM teacher_slots WHERE `empty_slot` = '1' AND teacher_id = ".$id."";
		//echo $sql;
		$q = $db->doQuery($sql);
		$row = mysql_num_rows($q);
		return $row;
		
	}
	public function check_ft_expiration($ft_expiration,$student_id,$status)
	{
		$date = date('Y-m-d'); // Current Date
		$exWil = date('Y-m-d', strtotime($date . ' + 1 day')); // Next Date
		$exWarn = date('Y-m-d', strtotime($date . ' - 1 day')); // Previous Date
		
		$exWilActual = strtotime($date); // Current Date Timestamp
		
		$exWarnD = strtotime($exWarn); // Previous Date Timestamp
		
		$exWilD = strtotime($exWil); // Next Date Timestamp
		
		$ft_expirationD = strtotime(date('Y-m-d',strtotime($ft_expiration))); // FT Expiration Date Timestamp
		
		$expiry_in_days_tmstmp = $ft_expirationD - $exWilActual; 
		$expiry_in_days = floor(($expiry_in_days_tmstmp / (3600*24))); // expiry in days
		
		if($expiry_in_days<=1 && $status != 7 && $status != 8 && $status != 9 && $status != 2)
		{
			echo '<span style="color:red;">'. substr($ft_expiration,0,-8).'<span>';
		}
		else
		echo substr($ft_expiration,0,-8);
		
		//this code block moved to status_update_corn.php page -- start
		
		/*if($expiry_in_days < 0 ){
			$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
			  
			 
			$sql2 = "UPDATE `users_list` SET status = 6 WHERE id = $student_id AND status != 7 AND status != 8 "; 
			
			$db->doQuery($sql2);
			
			$slot_id = User::getStudentSlotId($student_id);
			User::update_available_line_url_shows($slot_id);
		}*/
		
		// -- end
		
		//previous functionality modified by n_owc
		
		/*$date = date('Y-m-d H:i:s');
		$exWil = date('Y-m-d H:i:s', strtotime($date . ' + 1 day'));
		
		$exWilActual = strtotime($date);
		
		$exWilD = strtotime($exWil);
		$ft_expirationD = strtotime($ft_expiration);
		
		$expiry_in_days_tmstmp = $ft_expirationD - $exWilActual; 
		$expiry_in_days = ceil(($expiry_in_days_tmstmp / 3600));
		
		
		if($exWilD > $ft_expirationD && $status != 7)
		{
			echo '<span style="color:red;">'. substr($ft_expiration,0,-8).'<span>';
		}
		else
		echo substr($ft_expiration,0,-8);
		
		
		if($expiry_in_days <= 0 ){
			$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
			  
			 
			$sql2 = "UPDATE `users_list` SET status = 6 WHERE id = $student_id AND status != 7 AND status != 8 "; 
			
			$db->doQuery($sql2);
			
			$slot_id = User::getStudentSlotId($student_id);
			User::update_available_line_url_shows($slot_id);
		}*/
		
		
		
	}
	
	public function getStudentDtl($student_id )
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$sql= "SELECT `id`, `slot`, `email`, `paypal_email`, `f_name`, `l_name`, `english_name`, `line_id`, `created_on`, `first_response`, `ft_expiration`, `unique_user_id`, `status`, `plan`, `payment_method`, `comment`, `payment_info_sent`, `first_paid_date`, `cancellation_date`, `engagement_level`, `life_status` FROM  users_list WHERE id = ".$student_id."";
		$q = $db->doQuery($sql);
		$row = mysql_fetch_assoc($q);
		return $row;
	}
	
	public function update_row_bg()
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$student_id = $_POST['student_id'];
		$stdentDtl = User::getStudentDtl($student_id);
		//print_r($stdentDtl);
		$ft_expiration = $stdentDtl['ft_expiration'];
		$status = $stdentDtl['status'];
		$pay_info_sent = $stdentDtl['payment_info_sent'];
		$res = User::check_day_before_expiration($ft_expiration,$student_id,$status,$pay_info_sent);
		echo $res;
	}
	public function check_day_before_expiration($ft_expiration,$student_id,$status,$pay_info_sent)
	{
		
	    $date = date('Y-m-d');
		//$exWil = date('Y-m-d H:i:s', strtotime($date . ' + 1 day'));
		//echo $ft_expiration;
		$exWilActual = strtotime($date);
		
		//$exWilD = strtotime($exWil);
		$ft_expirationD = strtotime(date('Y-m-d',strtotime($ft_expiration)));
		$cls = "whiteBG";
		
		if($pay_info_sent!=1 && $ft_expiration != '0000-00-00 00:00:00' && $status != 4 && $status != 9 && $status != 2)
		{
			$expiry_in_days_tmstmp = $ft_expirationD - $exWilActual; 
			$expiry_in_days = floor(($expiry_in_days_tmstmp / (3600*24)));
			//($expiry_in_days == 1 || $expiry_in_days == 0) A day before expiration date to till the current date, 
			//the background color of the student row needs to be automatically changed to yellow
			if($expiry_in_days <=1) 
			{
				$cls="yellowBG";
			}
		}
		
		return $cls;
	}
	
	public function check_five_day_after_first_response($first_response,$student_id)
	{
		//$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
	    $date = date('Y-m-d');
		//$exWil = date('Y-m-d H:i:s', strtotime($date . ' + 1 day'));
		//echo $ft_expiration;
		$exWilActual = strtotime($date);
		
		//$exWilD = strtotime($exWil);
		$first_responseD = strtotime(date('Y-m-d',strtotime($first_response)));
		$cls = "";
		
			$days_interval_tmstmp = $first_responseD - $exWilActual; 
			$days_interval = floor(($days_interval_tmstmp / (3600*24)));
			if($days_interval ==(-5) && $first_response !='' && $first_response != '0000-00-00 00:00:00' ) 
			{
				$cls="blink";
			}
			/*if($days_interval ==(-7))
			{
				$sql= "UPDATE users_list  SET `status`= '6' WHERE id = ".$student_id." AND status != 7 AND status != 8 AND status != 2";
				$q = $db->doQuery($sql);
				$slot_id = User::getStudentSlotId($student_id);
				User::update_available_line_url_shows($slot_id);
			}*/
		
		return $cls;
	}
	public function check_life_status_blinking($status,$life_status)
	{
		if($status ==  3 && $life_status == 'unknown')
		{
			return 'aro_ind';
		}
		else return '';
	}
	
	public function change_status_db()
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$uid = $_POST['student_id'];
		
		$status = $_POST['new_status_id'];
		$old_status = $_POST['old_status'];
		$sql= "UPDATE users_list  SET `status`= '".$status."' WHERE id = ".$uid."";
		$q = $db->doQuery($sql);
		
		$slot_id = User::getStudentSlotId($uid);
		
		
		User::update_available_line_url_shows($slot_id);
		User::insert_change_status_log($uid,$status,$old_status);
		
		
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
	
	public function insert_change_status_log($uid,$new_status_id,$old_status_id,$cron_update = false)
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		if($cron_update)
			$chenged_by = '';
		else 
			$chenged_by = User::loged_in_userID();
		
		
		
		$sql= "INSERT INTO statuses_change_log (`user_id`, `old_status`, `new_status`, `chenged_by`, `chenged_date`) VALUES(".$uid.",".$old_status_id.",".$new_status_id.",'".$chenged_by."','".User::last_modified()."')";
		//echo $sql.'<br />';
		$q = $db->doQuery($sql);
		
		
	}
	public function loged_in_username()
	{
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		return  $_SESSION['user_name'];
	}
	
	public function loged_in_userID()
	{
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		return  $_SESSION['user_id'];
	}
	
	private static function last_modified() {
		date_default_timezone_set("Asia/Tokyo");
		return date("Y-m-d H:i:s");
    }
	
	
	public function getStudentSlotId($uid)
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$sqlUserSlot = "SELECT slot FROM users_list WHERE id = ".$uid."";
		$slotQ = $db->doQuery($sqlUserSlot);
		$slotR = mysql_fetch_assoc($slotQ);
		$slot_id = $slotR['slot'];
		return $slot_id;
	}
	
	public function teacher_days_off_1($day_id)
	{
		
		switch ($day_id)
		{
		case 1:
		  echo "Monday";
		  break;
		case 2:
		  echo "Tuesday";
		  break;
		case 3:
		  echo "Wednesday";
		  break;
		case 4:
		  echo "Thursday";
		  break;
		case 5:
		  echo "Friday";
		  break;
		case 6:
		  echo "Saturday";
		  break;
		case 7:
		  echo "Sunday";
		  break;
		default:
		  echo "";
		}
		
	}
	
	public function teacher_days_off_2($day_id)
	{
		
		switch ($day_id)
		{
		case 1:
		  echo "Monday";
		  break;
		case 2:
		  echo "Tuesday";
		  break;
		case 3:
		  echo "Wednesday";
		  break;
		case 4:
		  echo "Thursday";
		  break;
		case 5:
		  echo "Friday";
		  break;
		case 6:
		  echo "Saturday";
		  break;
		case 7:
		  echo "Sunday";
		  break;
		default:
		  echo "";
		}
		
	}
	
	public function getTeacherMaxStudent($teacher_id)
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql ="SELECT SUM(`max_students`)  as total FROM `teacher_slots`  WHERE teacher_id = ".$teacher_id." AND empty_slot = 0";
		$q = $db->doQuery($sql);
		$r = mysql_fetch_assoc($q);
		return $r['total'];
		
		
	}
	
	public function getTeacherMaxStudentBySlotId($slot_id)
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql ="SELECT `max_students`  as total FROM `teacher_slots`  WHERE id = ".$slot_id;
		$q = $db->doQuery($sql);
		$r = mysql_fetch_assoc($q);
		return $r['total'];
		
		
	}
	public function getTeacherMaxStudent_in_array()
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$res = array();
		$sql ="SELECT teacher_id,sum(`max_students`)  as total FROM `teacher_slots` GROUP BY teacher_id
				ORDER BY `teacher_slots`.`teacher_id` ASC";
		$q = $db->doQuery($sql);
		while($r = mysql_fetch_assoc($q))
		$res[$r['teacher_id']] = $r['total'];
		return $res;
		
		
	}
	
	public function total_payment_Info_Sent_in_array()
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$res = array();
		$sql ="SELECT TS.teacher_id AS teacher_id , COUNT( UL.payment_info_sent ) AS total FROM `users_list` AS UL	
					LEFT JOIN teacher_slots AS TS ON ( UL.slot = TS.id ) WHERE UL.payment_info_sent !=0 AND TS.teacher_id != ''
					GROUP BY TS.teacher_id  ORDER BY  TS.teacher_id ASC" ;
		$q = $db->doQuery($sql);
		while($r = mysql_fetch_assoc($q))
		$res[$r['teacher_id']] = $r['total'];
		return $res;
		
	}
	
	public function teacher_conversion_rate($teacher_id)
	{

		$total = User::total_after_FT_users_by_teacher_id($teacher_id);
		if($total == 0) return '0 % Conversion';
		
		$total_responsive = User::total_responsive_after_FT_users_by_teacher_id($teacher_id);
		$converted = User::converted_users_by_teacher_id($teacher_id);

		
		$cnv = round( 100*($converted / $total ) , 2).'% Conversion';
		if($total_responsive > 0)
		$res_cnv = '<br>'.round( 100*($converted / $total_responsive ) , 2).'% Resp Conv';
		else
		$res_cnv = '<br> 0 % Resp Conv';
		return $cnv.$res_cnv;
	}
	
	public function teacher_true_conversion_rate($t_paying_user,$t_cancelled,$t_avl_url_shown)
	{
		//" true conversion" = (Paying+cancelled) / LINE URL shown
		
		$total = $t_paying_user+$t_cancelled;
		if($total <= 0) return '0 %';
		if($t_avl_url_shown <= 0) return '0 %';
		
		$true_cnv = round( 100*($total / $t_avl_url_shown ) , 2).'%';
		return $true_cnv;
	}
	
	
	public function overall_teachers_conversion_rate()
	{

		$total = User::total_after_FT_users_by_teacher_id($teacher_id = false);
		if($total == 0) return '0 % Conversion';
		
		$total_responsive = User::total_responsive_after_FT_users_by_teacher_id($teacher_id  = false);
		$converted = User::converted_users_by_teacher_id($teacher_id  = false);

		if($total == 0) return '0 % Conversion';
		$cnv = round( 100*($converted / $total ) , 2).'% Conversion';
		if($total_responsive > 0)
		$res_cnv = ' '.round( 100*($converted / $total_responsive ) , 2).'% Resp Conv';
		else
		$res_cnv = '  0 % Resp Conv';
		return $cnv.$res_cnv;
	}
	
	//Conversion rate per teacher so far.=Paying user / (Paying user + No response + F.T. expired)
	//Conversion rate overall. (Total Paying user / (Total Paying user + No Response + Total F.T. expired)
	
	
	public function search_teacher_html()
	{
		
		$name = $_POST['name'];
		$email = $_POST['email'];
		$start_time = $_POST['start_time'];
		$end_time = $_POST['end_time'];
		$teacher_id = $_POST['teacher_id'];
		//$slot = $_POST['slot'];
		
		
		$condition = " WHERE role LIKE '%teacher%'";
		$flag = 1;
		$con = " ";
		
		
		if(!empty($name))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `name` LIKE '".$name."%'";
		}
		if(!empty($email))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `email` LIKE '%".$email."%'";
		}
		/*if(!empty($teacher_id) && $teacher_id!="undefined" )
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
		}*/
		if(!empty($start_time) && !empty($end_time))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `start_time` >= '".$start_time."' AND `end_time` <= '".$end_time."'";
		}
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		
		
		$sql= "SELECT DISTINCT(ts.id) as slot_id,um.id,name,email,user,status,ts.start_time,ts.end_time FROM  `user_master` AS um INNER JOIN `teacher_slots` AS ts ON um.id = ts.teacher_id".$condition." GROUP BY ts.teacher_id";
		$q = $db->doQuery($sql);
		$num_row = mysql_num_rows($q);
		
		//print_r($row);
		?>
<style>
.table_view {
    display:table;
	width:1340px;
}
.header {
    di splay:table-header-group;
    font-weight:bold;
	text-align:center;
}
.table_view1 {
    display:table;
}
.header1 {
    font-weight:bold;
	text-align:center;
}
.header .cell{height:30px;background:#666;color:#FFF;float:left;}
.cell {
    dis play:table-cell;
    width:auto;
	m ax-width:100px;
	backg round-color:#069;
	ma rgin:2px;
	border:1px solid #FFFFFF;
	float:left;
	height:100px;
	background:#E2E2E2;
	display:block;
	font-size:13px;
}
.cell_view {
    width:auto;
	border:1px solid #FFFFFF;
	float:left;
	height:50px;
	background:#E2E2E2;
	display:block;
	font-size:13px;
}
.u_sl{width:40px;text-align:center;}
.u_name{width:130px;}
.u_dtl{width:310px;b order:1px solid red;padding-left:5px;}
.u_email{width:200px;}
.u_un{width:120px;}
.u_status{width:70px;text-align:center;}
.u_role{width:60px;}
.u_ll{width:150px;text-align:center;}
.u_ts{width:75px;text-align:center;}
.u_tsdnt{width:120px;text-align:center;}
.u_pu{width:95px;text-align:center;}
.u_lurl{width:220px;text-align:center;}
.u_nlus{width:120px;text-align:center;}
.u_msdnt{width:85px;text-align:center;}
.u_trsdnt{width:165px;text-align:center;}
.u_df1,.u_df2{width:90px;text-align:center;}
.u_sd{width:150px;text-align:center;}
.u_mas{width:120px;text-align:center;}
.u_as{width:120px;text-align:center;}

.u_ac{width:180px;text-align:center;}
</style>
<div class="table_view">
  <div class="header">
    <div class="cell u_sl">Sl</div>
    <div class="cell u_dtl">Teacher Details</div>
    <div class="cell u_status">Status</div>
    <div class="cell u_ts">Total Slots</div>
    <div class="cell u_tsdnt">Total Active Students</div>
    <div class="cell u_df1">Day off 1</div>
    <div class="cell u_df2">Day off 2</div>
    <div class="cell u_ac">Action</div>
  </div>
 <div class="clear"></div>
  <?php 
  if($num_row > 0 ){ $i_user = 0;
  while( $row = mysql_fetch_assoc($q) )
  {
	  //print_r($row);
	$i_user++;
	  $slot_count = 0;
	  $slot_count = User::count_total_slot($row['id']);
	  $student_count = User::count_total_active_student($row['id']);
	 $teachersDtl = User::getteacherDtl($row['id']);
	 $teacher_slots = User::get_teacher_slotBy_id($row['id']);
	// $pp = $pp+$student_count;
	?>
    <div class="cell u_sl"><?php echo $i_user;?></div>
    <div class="u_dtl cell">
     <div class="u_info_name_<?php echo $row['id'];?>">Name:<?php echo $row['name'];?></div>
     <div class="u_info_email_<?php echo $row['id'];?>">Email:<?php echo $row['email'];?></div>
     <div class="u_info_user_<?php echo $row['id'];?>">Username:<?php echo $row['user'];?></div>
     <div class="u_info_lurl_<?php echo $row['id'];?>">Line Url:<?php echo $teachersDtl['line_url'];;?></div>

    </div>
   
    <div class="cell u_status u_info_status_<?php echo $row['id'];?>"><?php echo $row['status'];?></div>
    <div class="cell u_ts"><?php echo $slot_count;?></div>
    <div class="cell u_tsdnt"><?php echo $student_count;?></div>
     <div class="cell u_df1 u_info_df1_<?php echo $row['id'];?>"><?php User::teacher_days_off_1($teachersDtl['day_off_1']);?>&nbsp;</div>
     <div class="cell u_df2 u_info_df2_<?php echo $row['id'];?>"><?php User::teacher_days_off_2($teachersDtl['day_off_2']);?>&nbsp;</div>
     
      <div class="cell u_ac u_ac_<?php echo $row['id'];?>">
			<a style="text-decoration:underline; cursor:pointer;" title="" onclick="showFullComment('<?php echo $row['id']; ?>');" >Slot Details</a>
      </div>
			<div id="dialog-comt-txt-<?php echo $row['id'];?>" title="Slot Details"  style="display:none;">
				<div class="table_view1" style="width:400px;">
                  <div class="header1">
                    <div class="cell_view u_sd">Slot Details</div>
                    <div class="cell_view u_as">Active Students</div>
					<div class="cell_view u_mas">Max. Students</div>
                   </div>
                   <div class="clear"></div>
                    <?php 
					  
					  for($i=0; $i < count($teacher_slots); $i++)
					  {
						//print_r($teacher_slots);
						?>
						<div class="cell_view u_sd"><?php if($teacher_slots[$i]['empty_slot']==1) echo "No Timeslot"; elseif($teacher_slots[$i]['coaching']==1) echo "Coaching"; else  echo $teacher_slots[$i]['start_time'].'&nbsp;To&nbsp;'.$teacher_slots[$i]['end_time'];?></div>
                        <div class="cell_view u_as"><?php echo User::count_total_active_student_byslot($teacher_slots[$i]['id']); ?></div>
                        <div class="cell_view u_mas"><?php  echo $teacher_slots[$i]['max_students']; ?></div>
						<?php 
					  }
					?>
                    
               </div>
            </div> 
            <div class="clear"></div>
	 <?php 
  }
  ?>
	 <?php 
	// echo $pp;
  } 
  else
  {
	  echo '<h3>No record</h3>';
  }
  ?>

		<?php
	}
	

};




?>
