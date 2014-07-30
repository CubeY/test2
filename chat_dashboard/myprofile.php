<?php
/*======================================================================**
**                                                                           
** Main entry point.
** All PHP (mode) calls will go through this script.  
**                                                                           
**======================================================================*/
$current_pname = 'myprofile';
include 'inc/inc.php';

Auth::checkAuth();



include 'inc/header.php';
?>



<?php


$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
$msg= '';
//print_r($_SESSION);

function escapeForSQL($str) {
	return mysql_real_escape_string($str);
}

$err_email_msg = '';
$err_username_msg = '';	
if(isset($_POST['todo']) == 'update_pofile')
{
	    //print_r($_POST);
		$uid =  $_POST['user_id'];
		$email = $_POST['email'];
		$user = $_POST['user'];
		$name = $_POST['name'];
		$pass = $_POST['pass'];
		
		$profile_en = (isset($_POST['profile_en']))?$_POST['profile_en']:'';
		$profile_jp = (isset($_POST['profile_jp']))?$_POST['profile_jp']:'';
		$gender = (isset($_POST['gender']))?$_POST['gender']:'';
		$interests =(isset( $_POST['interests']))?$_POST['interests']:'';
		$line_url_http = (isset($_POST['line_url_http']))?$_POST['line_url_http']:'';
		$img_data = (isset($_POST['img_data']))?$_POST['img_data']:'';
		$qr_img_data = (isset($_POST['qr_img_data']))?$_POST['qr_img_data']:'';
		
		
		if(User::is_email_exist($email,$uid) == true)
		{
			$err_email_msg = 'Email "'.$email.'" already exist.';	
		}
		elseif(User::is_username_exist($user,$uid) == true)
		{
			$err_username_msg = 'Username "'.$user.'" already exist.';	
		}
		else
		{
			
		
		$sql= "UPDATE user_master  SET `email` = '".$email."',`user`= '".$user."',";
										 
		if($pass != '')
		{
			$sql.=" `pass`= '".$pass."',";
		}
		
		$sql.="`name`= '".$name."' WHERE id = ".$uid."";
		//echo $sql;							
		$q = $db->doQuery($sql);
		
		$msg = 'Profile Update Successfully.';		
		
		
		$sql2= "UPDATE teachers  SET `profile_en` = '".escapeForSQL($profile_en)."',
									 `profile_jp` = '".escapeForSQL($profile_jp)."',
									 `gender` = '".$gender."',
									 `interests` = '".escapeForSQL($interests)."',
									 `line_url_http` = '".$line_url_http."',
									 `face_photo` = '".$img_data."',
									 `qr_image` = '".$qr_img_data."' WHERE teacher_id = ".$uid."";
		$q = $db->doQuery($sql2);
		
		unset($_POST);
		}
}

?>
<script type="text/javascript">

      $(document).ready(function(e) {
		  $('#pass').val('');
		  var uid = $('#user_id').val();
		 // alert(uid);
    $('#update_user_frm').validate({	
		rules: {		
			email: {
				required: true,
				email: true,
				remote:
                    {
                      url: 'ajax/validate_userinfo.php?uid='+uid,
					  async: false,
                      type: "post",
                      data:
                      {
                          email: function()
                          {
                              return $('#update_user_frm :input[name="email"]').val();
                          }
                      }
                    }
			},
			user: {
				required: true,
				remote:
                    {
                      url: 'ajax/validate_userinfo.php?uid='+uid,
					  async: false,
                      type: "post",
                      data:
                      {
                          user: function()
                          {
                              return $('#update_user_frm :input[name="user"]').val();
                          }
                      }
                    }
			},
		},
		messages: {		
			email:
                 {
                    required: "Please enter email address.",
                    email: "Please enter a valid email address.",
                    remote: $.validator.format("Email already exist.")
                 },
				 user:
                 {
                    required: "Please enter username.",
                    email: "Please enter a valid email address.",
                    remote: $.validator.format("Username already exist.")
                 }
			
		}
	});
});
       

    </script>
    <div class="backdrop" style="opacity: 0; display: none;"></div>
<div class="menu_bar"><?php require_once("menu.php");?></div>

<style type="text/css">
.label_cs{width:205px;}
.create_subtopic_cls input[type=text]{width:250px;}
</style>
<div class="">
<?php
//print_r($_SESSION);
$loged_inUserDtl = User::singleUserDetails($_SESSION['user_id']);
//print_r($loged_inUserDtl);
?>
<form name="update_user_frm" id="update_user_frm" action="" method="post" >
<input type="hidden" name="todo"  value="update_pofile"/>
<input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id'];?>"/>


<div class="create_subtopic_cls" style="width:100%;" >

<h3 style="margin-left:12px;border-bottom:1px solid #666;">Profile Details:<span style="color:#090;"><?php echo $msg;?></span></h3>


<div class="clear"></div>

<div class="clear"></div>
    <div class="single_row">
        <div class="label_cs">Name:</div>
        <div><input type="text" class="required" name="name" value="<?php echo $loged_inUserDtl['name'];?>" /></div>
    </div>
	<div class="clear"></div>
    <div class="single_row">
        <div class="label_cs">Email:</div>
        <div><input type="text" class="required email" name="email" value="<?php echo $loged_inUserDtl['email'];?>"   />
         <?php if($err_email_msg != '') echo '<span class="error">'.$err_email_msg.'</span>';?> </div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_cs">Username:</div>
        <div><input type="text" class="required" name="user" value="<?php echo $loged_inUserDtl['user'];?>" />
        <?php if($err_username_msg != '') echo '<span class="error">'.$err_username_msg.'</span>';?></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_cs">New Password:</div>
        <div><input type="password" class="" name="pass" id="pass" value="" /><br /><sub style="color:#666;">Leave blank if you don't want to change your password</sub> </div>
    </div>
    <div class="clear"></div>
    <?php /*?><?php 
    if($_SESSION['user_role'] == 'teacherjjj' ){
    $teacherDtl = User::getteacherDtl($_SESSION['user_id']);
	//print_r($teacherDtl);
    ?>
    <div class="single_row">
        <div class="label_cs">Line Url:</div>
        <div><input type="text" class="required" name="line_url" value="<?php echo $teacherDtl['line_url'];?>" /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_cs">Num Line url shown:</div>
        <div><input type="text" class="required" name="num_line_url_shown" value="<?php echo $teacherDtl['num_line_url_shown'];?>" /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_cs">Max students:</div>
        <div><input type="text" class="required" name="max_students" value="<?php echo $teacherDtl['max_students'];?>" /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_cs">Teacher reported students:</div>
        <div><input type="text" class="required" name="teacher_reported_students" value="<?php echo $teacherDtl['teacher_reported_students'];?>" /></div>
    </div>
     <div class="clear"></div>
    <div class="single_row">
        <div class="label_cs">Day off 1:</div>
        <div><input type="text" class="required" name="day_off_1" value="<?php echo $teacherDtl['day_off_1'];?>" /></div>
    </div>
     <div class="clear"></div>
    <div class="single_row">
        <div class="label_cs">Day off 2:</div>
        <div><input type="text" class="required" name="day_off_2" value="<?php echo $teacherDtl['day_off_2'];?>" /></div>
    </div>
    <div class="clear"></div>
	<?php } ?><?php */?>
   
    <?php 
    if($_SESSION['user_role'] == 'teacher' || $_SESSION['user_role_second'] == 'teacher' ){
    $teacherDtl = User::getteacherDtl($_SESSION['user_id']);
	//print_r($teacherDtl);
    ?>
	
	 <h3 style="margin-left:12px;border-bottom:1px solid #666;"></h3>
	 <div class="clear"></div>
    <div class="single_row">
        <div class="label_cs">Profile English:</div>
        <div><textarea class="tx1" name="profile_en"  ><?php echo $teacherDtl['profile_en'];?></textarea></div>
    </div>
     <div class="clear"></div>
    <div class="single_row">
        <div class="label_cs">Profile Japan:</div>
        <div><textarea class="tx1" name="profile_jp"  ><?php echo $teacherDtl['profile_jp'];?></textarea></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_cs">Gender:</div>
        <div>
        <select id="gender" name="gender" >
            <option value="">Select</option>
            <option value="M" <?php if($teacherDtl['gender']=='M')  echo "selected='selected'";?> >Male</option>
            <option value="F" <?php if($teacherDtl['gender']=='F')  echo "selected='selected'";?>>Female</option>
        </select>
        </div>
    </div>
     <div class="clear"></div>
    <div class="single_row">
        <div class="label_cs">Interests:</div>
        <div><textarea class="tx1" name="interests"  ><?php echo $teacherDtl['interests'];?></textarea></div>
    </div> <div class="clear"></div>
    <div class="single_row">
        <div class="label_cs">Line url:</div>
        <div><input type="text" class="url" name="line_url_http" value="<?php echo $teacherDtl['line_url_http'];?>"  /></div>
    </div>
  
     <div class="clear"></div>
     	 <h3 style="margin-left:12px;border-bottom:1px solid #666;">Profile Picture:</h3>
<div class="clear"></div>
    <div class="single_row">
        <div class="label_cs"></div>
    </div>
    <div class="single_row">
        <div class="label_cs"> <div class="addPUpload">Add new face photo</div>
        <div class="delPUpload">Delete face photo</div></div>
        <div><?php User::face_photo($_SESSION['user_id'],$thumb = false , $w = 130 , $h = false)?></div>
    </div>
    
    
     <div class="clear"></div>
     	 <h3 style="margin-left:12px;border-bottom:1px solid #666;">QR Image:</h3>
<div class="clear"></div>
    <div class="single_row">
        <div class="label_cs"></div>
    </div>
    <div class="single_row">
        <div class="label_cs"> <div class="addPUpload_qr">Add new QR Image</div>
        <div class="delPUpload_qr">Delete QR Image</div></div>
        <div><?php User::qr_image($_SESSION['user_id'])?></div>
    </div>
    
    
     <?php } ?>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_cs">&nbsp;</div>
        <div><input type="submit" value="Save"  /> </div>
    </div>
</div>
<input type="hidden" id="img_data" name="img_data" value="<?php echo $teacherDtl['face_photo'];?>"  />
<input type="hidden" id="qr_img_data" name="qr_img_data" value="<?php echo $teacherDtl['qr_image'];?>"  />

<input type="hidden" id="img_type" value=""  />
</form>
</div>
<div style="height:100px;"></div>
<input type="hidden" id="site_url" value="<?php echo SITE_URL;?>"  />


<?php
?>
<div class="addd_phpto_from_pc" style="opacity: 1; display: none;">
    <div style="width: 541px;" class="modalContent"><div class="modalModule"><div style="display: block;" class="ajax UserProfileImageUploader Module inModal"><div class="moduleMask"></div>
    <h1>Add a Photo
    <img width="26" height="26" class="Upload_close" src="<?php echo SITE_URL;?>media/images/1369401888_Gnome-Window-Close-32.png"></h1>
    <div class="ajax ui-ImageUploader Module">
<div style="position: relative; overflow: hidden; direction: ltr;" type="button" class="leftRounded hasText Button primary Module large ajax btn">
<span class="buttonText">Choose File</span>
        <input type="file" style="position: absolute; right: 0px; top: 0px; font-family: Arial; font-size: 118px; margin: 0px; padding: 0px; cursor: pointer; opacity: 0;" name="files[]" id="fileupload_from_pc"></div><div class="uploaderProgress">
    <span>Please choose a file.</span>
</div></div>
<div class="max_upload_info">Max Image upload size 1500KB.</div>
<input type="hidden" id="current_uploaded_img_from_pc">
<div class="progress progress-success progress-striped" id="progress">
        <div class="bar"></div>
    </div>
</div></div></div>
</div>

<?php //////////////////////////////////////?>
<div class="addd_qr_img_from_pc" style="opacity: 1; display: none;">
    <div style="width: 541px;" class="modalContent"><div class="modalModule"><div style="display: block;" class="ajax UserProfileImageUploader Module inModal"><div class="moduleMask"></div>
    <h1>Add QR Image
    <img width="26" height="26" class="Upload_close" src="<?php echo SITE_URL;?>media/images/1369401888_Gnome-Window-Close-32.png"></h1>
    <div class="ajax ui-ImageUploader Module">
<div style="position: relative; overflow: hidden; direction: ltr;" type="button" class="leftRounded hasText Button primary Module large ajax btn">
<span class="buttonText">Choose File</span>
        <input type="file" style="position: absolute; right: 0px; top: 0px; font-family: Arial; font-size: 118px; margin: 0px; padding: 0px; cursor: pointer; opacity: 0;" name="files[]" id="fileupload_from_pc_qr_img"></div><div class="uploaderProgress">
    <span>Please choose a file.</span>
</div></div>
<div class="max_upload_info">Max Image upload size 1500KB.</div>
<input type="hidden" id="current_uploaded_qr_img_from_pc">
<div class="progress progress-success progress-striped" id="progress">
        <div class="bar"></div>
    </div>
</div></div></div>
</div>
</head>