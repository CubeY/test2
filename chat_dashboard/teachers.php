<?php
/*======================================================================**
**                                                                           
** Page:User , manage all users
** Created By : Bidhan
**                                                                           
**======================================================================*/
$current_pname = 'teachers';
include 'inc/inc.php';

Auth::checkAuth();



include 'inc/header.php';


$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);

$current_pname = 'teachers';

if($_SESSION['user_role'] != 'administrator' && $_SESSION['user_role'] != 'viewer') { 	echo '<h3>You are restricted for this page!</h3>';exit;} 

if(isset($_POST['todo']) == 'add_new_user')
{
	User::add_new_user();
}


?>

<div class="menu_bar"><?php require_once("menu.php");?></div>
<script type="text/javascript">

      $(document).ready(function(e) {
    $('#add_user_frm').validate({	
		rules: {		
			email: {
				required: true,
				email: true,
				remote:
                    {
                      url: 'ajax/validate_userinfo.php',
					  async: false,
                      type: "post",
                      data:
                      {
                          email: function()
                          {
                              return $('#add_user_frm :input[name="email"]').val();
                          }
                      }
                    }
			},
			user: {
				required: true,
				remote:
                    {
                      url: 'ajax/validate_userinfo.php',
					  async: false,
                      type: "post",
                      data:
                      {
                          user: function()
                          {
                              return $('#add_user_frm :input[name="user"]').val();
                          }
                      }
                    }
			},
			line_url: {
				required: true,
				remote:
                    {
                      url: 'ajax/validate_userinfo.php',
					  async: false,
                      type: "post",
                      data:
                      {
                          user: function()
                          {
                              return $('#add_user_frm :input[name="line_url"]').val();
                          }
                      }
                    }
			},
			pass: {
				required: true,
				 minlength: 6,
				
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
                 },
			 line_url:
				 {
					remote: $.validator.format("Line URL already exist.")
				 }
			
			
		}
	});
});
       
function edit_user(uid)
{
	$(".user_block_"+uid).show();
	$('.user_form').hide();
	$("#edit_user_frm_dtl_html_"+uid).html('Please wait...');
	var todo = 'edit_user_html';
	$.ajax({  
			type: "POST", 
			url: 'ajax/ajax_user.php', 
			data: "todo="+todo+"&uid="+uid,
			dataType: "html",

			success: function(data){
				$("#edit_user_frm_dtl_html_"+uid).html(data);
				//var y = $(window).scrollTop();  //your current y position on the page
				//$(window).scrollTop(y+400);
				
				var dTag = $("div[name='usrTr_"+ uid +"']");
   				 $('html,body').animate({scrollTop: dTag.offset().top},'slow');
	
				$("#edit_user_frm_dtl_html_"+uid+" input[name='day_off_1[]']").change(function () {
				  var maxAllowed = 2;
				  var cnt = $("#edit_user_frm_dtl_html_"+uid+" input[name='day_off_1[]']:checked").length;
				  var opt = $("#edit_user_frm_dtl_html_"+uid+" input[name='day_off_1[]']");
				  if (cnt > maxAllowed) 
				  {
					 $(this).prop("checked", "");
					 alert('Select maximum ' + maxAllowed + ' Days!');
				 }
				 else
				 {
					var str = outputSelected(opt);
					//alert(str);
					$("#selected_days_off_edit_"+uid).val(str);
				 }
				});
				
				 $('#edit_usr_frm_'+uid).validate({	
		rules: {		
			u_email: {
				required: true,
				email: true,
				remote:
                    {
                      url: 'ajax/validate_userinfo.php?uid='+uid,
					  async: false,
                      type: "get",
                      data:
                      {
                          email: function()
                          {
                              return $('#edit_usr_frm_'+uid+' :input[name="u_email"]').val();
                          }
                      }
                    }
			},
			line_url: {
				required: true,
				remote:
                    {
                      url: 'ajax/validate_userinfo.php?user_id='+uid,
					  async: false,
                      type: "post",
                      data:
                      {
                          user: function()
                          {
                              return $('#add_user_frm :input[name="line_url"]').val();
                          }
                      }
                    }
			},
			u_user: {
				required: true,
				remote:
                    {
                      url: 'ajax/validate_userinfo.php?uid='+uid,
					  async: false,
                      type: "get",
                      data:
                      {
                          user: function()
                          {
                              return $('#edit_usr_frm_'+uid+' :input[name="u_user"]').val();
                          }
                      }
                    }
			},
			pass: {
				required: true,
				 minlength: 6,
				
			},
			
		},
		messages: {		
			u_email:
                 {
                    required: "Please enter email address.",
                    email: "Please enter a valid email address.",
                    remote: $.validator.format("Email already exist.")
                 },
				 line_url:
				 {
					remote: $.validator.format("Line URL already exist.")
				 },
				u_user:
                 {
                    required: "Please enter username.",
                    email: "Please enter a valid email address.",
                    remote: $.validator.format("Username already exist.")
                 }
		},
		 submitHandler: function() { save_changes(uid); return false; }
	});
				
				}  
	      });
				
		function save_changes(uid)
		{
			
			
			
			var name = $('#edit_usr_frm_'+uid).find('input[name="u_name"]').val();
			var email = $('#edit_usr_frm_'+uid).find('input[name="u_email"]').val();
			if(!validateEmail(email))
			{
				$('#edit_usr_frm_'+uid).find('input[name="u_email"]').focus();
				$('#edit_usr_frm_'+uid).find('label[for="u_email"]').css('display','block').html('Please enter a valid email address.');
				return false;
			}
			var pass = $('#edit_usr_frm_'+uid).find('input[name="u_pass"]').val();
			var user = $('#edit_usr_frm_'+uid).find('input[name="u_user"]').val();
			
			var line_url = $('#edit_usr_frm_'+uid).find('input[name="line_url"]').val();
			var num_line_url_shown = $('#edit_usr_frm_'+uid).find('input[name="num_line_url_shown"]').val();
			//var max_students = $('#edit_usr_frm_'+uid).find('input[name="max_students"]').val();
			//var teacher_reported_students = $('#edit_usr_frm_'+uid).find('input[name="teacher_reported_students"]').val();
			var day_off_1 = $('#edit_usr_frm_'+uid).find('#selected_days_off_edit_'+uid).val();
			//var day_off_2 = $('#edit_usr_frm_'+uid).find('input[name="day_off_2"]').val();
			var profile_en = $('#edit_usr_frm_'+uid).find('textarea[name="profile_en"]').val();
			var profile_jp = $('#edit_usr_frm_'+uid).find('textarea[name="profile_jp"]').val();
			var gender = $('#edit_usr_frm_'+uid).find('#gender_'+uid+' :selected').val();
			var interests = $('#edit_usr_frm_'+uid).find('textarea[name="interests"]').val();
			var line_url_http = $('#edit_usr_frm_'+uid).find('input[name="line_url_http"]').val();
			
			var status = $('#edit_usr_frm_'+uid).find('input:radio[name="status"]:checked').val();
			
			var day_off_1_val = day_off_1.split(",");
			var day_off_1_split="";
			var day_off_2_split="";
			if(day_off_1_val[0]==1)
			{
				day_off_1_split = "Monday";
			}
			if(day_off_1_val[0]==2)
			{
				day_off_1_split = "Tuesday";
			}
			if(day_off_1_val[0]==3)
			{
				day_off_1_split = "Wednesday";
			}
			if(day_off_1_val[0]==4)
			{
				day_off_1_split = "Thursday";
			}
			if(day_off_1_val[0]==5)
			{
				day_off_1_split = "Friday";
			}
			if(day_off_1_val[0]==6)
			{
				day_off_1_split = "Saturday";
			}
			if(day_off_1_val[0]==7)
			{
				day_off_1_split = "Sunday";
			}
			
			if(day_off_1_val[1]==1)
			{
				day_off_2_split = "Monday";
			}
			if(day_off_1_val[1]==2)
			{
				day_off_2_split = "Tuesday";
			}
			if(day_off_1_val[1]==3)
			{
				day_off_2_split = "Wednesday";
			}
			if(day_off_1_val[1]==4)
			{
				day_off_2_split = "Thursday";
			}
			if(day_off_1_val[1]==5)
			{
				day_off_2_split = "Friday";
			}
			if(day_off_1_val[1]==6)
			{
				day_off_2_split = "Saturday";
			}
			if(day_off_1_val[1]==7)
			{
				day_off_2_split = "Sunday";
			}
			/*var s = new Array();
			s = s.push(day_off_1_val.split(","));*/
			//alert(day_off_1_vals);
			
			var todo = 'update_user_dtl';
			$.ajax({  
			type: "POST", 
			url: 'ajax/ajax_user.php', 
			data: "todo="+todo+"&uid="+uid+"&name="+name+"&email="+email+"&pass="+pass+"&user="+user+"&status="+status+"&line_url="+line_url+"&num_line_url_shown="+num_line_url_shown+"&day_off_1="+day_off_1+"&profile_en="+profile_en+"&profile_jp="+profile_jp+"&gender="+gender+"&interests="+interests+"&line_url_http="+line_url_http,
			dataType: "html",

			success: function(data){
				
				if($.trim(data) == 'Email already exist')
				{
					$('#edit_usr_frm_'+uid).find('label[for="u_email"]').css('display','block').html('Email already exist.'); return false;
				}
				else if($.trim(data) == 'Username already exist')
				{
					$('#edit_usr_frm_'+uid).find('label[for="u_user"]').css('display','block').html('Username already exist.'); return false;
				}
				$('.u_info_name_'+uid).html('Name:'+name);
				$('.u_info_email_'+uid).html('Email:'+email);
				$('.u_info_user_'+uid).html('Username:'+user);
				if(status=='Y')
					$('.u_info_status_'+uid).html('Status: Active');
				else
					$('.u_info_status_'+uid).html('Status: Inactive');
				
				$('.u_info_lurl_'+uid).html('Line Url:'+line_url);
				$('.u_info_nlus_'+uid).html(num_line_url_shown);
				//$('.u_info_msdnt_'+uid).html(max_students);
				//$('.u_info_trsdnt_'+uid).html(teacher_reported_students);
				$('.u_info_df1_'+uid).html(day_off_1_split);
				$('.u_info_df2_'+uid).html(day_off_2_split);
				
				$("#edit_user_frm_dtl_html_"+uid).html('');
				$(".user_block_"+uid).hide();
  
			}
			});
			
			
			return false;
		}
}
function showAddNewUrsFrm()
{

	$(".user_form").show();
}

function HideAddNewUrsFrm()
{

	$(".user_form").hide();
}
function HideUsereditBlock(uid)
{
	$("#edit_user_frm_dtl_html_"+uid).html('');
	$(".user_block_"+uid).hide();
}
function delete_user(uid)
{
	var u_name = $('.u_info_name_'+uid).text();
	if(confirm('Are you sure you want to DELETE '+u_name+'?') == false ) return false;
	var todo = 'delete_user';
	$.ajax({  
	type: "POST", 
	url: 'ajax/ajax_user.php', 
	data: "todo="+todo+"&uid="+uid,
	dataType: "html",

	success: function(data){
	$("#usrTr_"+uid).remove();
	$(".user_block_"+uid).remove();
	
	}
});
}

$(document).ready(function () {
   $("input[name='day_off_1[]']").change(function () {
      var maxAllowed = 2;
      var cnt = $("input[name='day_off_1[]']:checked").length;
	  var opt = $("input[name='day_off_1[]']");
      if (cnt > maxAllowed) 
      {
         $(this).prop("checked", "");
         alert('Select maximum ' + maxAllowed + ' Days!');
     }
	 else
	 {
		var str = outputSelected(opt);
	 }
  });
});

function getSelected(opt) {
            var selected = new Array();
            var index = 0;
            for (var intLoop = 0; intLoop < opt.length; intLoop++) {
               if ((opt[intLoop].selected) ||
                   (opt[intLoop].checked)) {
                  index = selected.length;
                  selected[index] = new Object;
                  selected[index].value = opt[intLoop].value;
                  selected[index].index = intLoop;
               }
            }
            return selected;
         }

function outputSelected(opt) {
            var sel = getSelected(opt);
            var strSel = "";
            for (var item in sel)       
               strSel += sel[item].value+",";
			return strSel;
         }
    </script>
    <style>
	.label_u{width:190px;}
	</style>
<?php if($_SESSION['user_role'] == 'administrator') { ?>
<div style="margin:5px 0 20px 0;"><a href="#" onclick="showAddNewUrsFrm()">Add New Teacher</a><span class="ss-all ss-all2" onclick="show_inactive_teacher('show');"  >Show All<img src="media/images/loading-s.gif" style="display:none;" class="ss-all-l-img" /></span></div>
<div class="user_form" style="display:none;width:auto;">
<form name="add_user_frm" id="add_user_frm" action="" method="post" >
<input type="hidden" name="todo"  value="add_new_user"/>
<input type="hidden" name="role"  value="teacher"/>
<div class="create_new_cls" >
    <div class="single_row">
        <div class="label_u">Name:</div>
        <div><input type="text" class="required" name="name" /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Email:</div>
        <div><input type="text" class="" name="email"  /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Username:</div>
        <div><input type="text" class="required" name="user" /></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Password:</div>
        <div><input type="text" class="required" name="pass" /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Line Url:</div>
        <div><input type="text" class="required" name="line_url" /></div>
    </div>
    <!--<div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Num Line url shown:</div>
        <div><input type="text" class="required" name="num_line_url_shown"  /></div>
    </div>-->
     <!--<div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Max students:</div>
        <div><input type="text" class="required" name="max_students" /></div>
    </div>
   <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Teacher reported students:</div>
        <div><input type="text" class="required" name="teacher_reported_students"  /></div>
    </div>-->
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Days off:</div>
        <div class="days_off_chkbox">
        <input type="checkbox" name="day_off_1[]" value="1" /> Monday
        <input type="checkbox" name="day_off_1[]" value="2" /> Tuesday
        <input type="checkbox" name="day_off_1[]" value="3" /> Wednesday
        <input type="checkbox" name="day_off_1[]" value="4" /> Thursday
        <input type="checkbox" name="day_off_1[]" value="5" /> Friday
        <input type="checkbox" name="day_off_1[]" value="6" /> Saturday
        <input type="checkbox" name="day_off_1[]" value="7" /> Sunday
        
        </div>
    </div>
     <!--<div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Day off 1:</div>
        <div><input type="text" class="required" name="day_off_1"  /></div>
    </div>-->
     <!--<div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Day off 2:</div>
        <div><input type="text" class="required" name="day_off_2"  /></div>
    </div>-->
     <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Profile English:</div>
        <div><textarea class="tx1" name="profile_en"  ></textarea></div>
    </div>
     <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Profile Japan:</div>
        <div><textarea class="tx1" name="profile_jp"  ></textarea></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Gender:</div>
        <div>
        <select id="gender" name="gender">
            <option value="">Select</option>
            <option value="M">Male</option>
            <option value="F">Female</option>
        </select>
        </div>
    </div>
     <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Interests:</div>
        <div><textarea class="tx1" name="interests"  ></textarea></div>
    </div> <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Line url:</div>
        <div><input type="text" class="url" name="line_url_http"  /></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Status:</div>
        <div>Active<input type="radio" checked="checked"  name="status" value="Y"/>&nbsp;&nbsp;Inactive<input type="radio" name="status" value="N" /></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">&nbsp;</div>
        <div><input type="submit" value="Save"  />&nbsp;<input type="button" value="Cancel" onclick="HideAddNewUrsFrm();"  /></div>
    </div>
    <div class="clear"></div>
</div>
</form>
</div>
<?php } ?>
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
.header .cell{height:45px;background:#666;color:#FFF;float:left;line-height:12px;}
.cell {
    dis play:table-cell;
    width:auto;
	m ax-width:100px;
	backg round-color:#069;
	ma rgin:2px;
	border:1px solid #FFFFFF;
	float:left;
	height:125px;
	background:#E2E2E2;
	display:block;
	font-size:13px;
}
.rowGroup{line-height: 19px !important;}
.u_sl{width:37px;text-align:center;}
.u_name{width:130px;}
.u_dtl{width:250px;b order:1px solid red;padding-left:5px;}
.u_email{width:200px;}
.u_un{width:120px;}
.u_status{width:40px;text-align:center;}
.u_role{width:60px;}
.u_ll{width:125px;text-align:center;}
.u_ts{width:40px;text-align:center;}
.u_tsdnt{width:80px;text-align:center;}
.u_pu{width:95px;text-align:center;}
.u_lurl{width:220px;text-align:center;}
.u_msdnt{width:55px;text-align:center;}
.u_crate{width:105px;text-align:center;}
.u_sts{width:265px;text-align:center;}
.u_studentstats{width:260px;padding-left:5px;}
.u_trsdnt{width:165px;text-align:center;}
.u_df1,.u_df2{width:70px;text-align:center;}
.u_nlus{width:70px;text-align:center;}
.u_ims{width:45px;text-align:center;}
.u_nrim{width:45px;text-align:center;}
.u_inft{width:45px;text-align:center;}
.u_fte{width:45px;text-align:center;}
.u_ftewor{width:70px;text-align:center;}
.u_payng{width:45px;text-align:center;}
.u_cncl{width:60px;text-align:center;}
.u_rls{width:38px;text-align:center;}
.u_ac{width:110px;text-align:center;}
</style>
<div class="list_user" >
<div class="table_view">
  <div class="header nav">
    <div class="cell u_sl">Sl</div>
    <div class="cell u_dtl">Teacher Details</div>
    <!--<div class="cell u_status">Status</div>
    <div class="cell u_ll">Last Login</div>-->
    <div class="cell u_ts">Total Slots</div>
    <div class="cell u_tsdnt">Total Active Students</div>
    <!--<div class="cell u_pu">Paying Users</div>-->
    <!--<div class="cell u_nlus">Total line url shown</div>-->
    <div class="cell u_msdnt">Max students</div>
    <div class="cell u_nlus">Num line url shown</div>
    
   <!-- <div class="cell u_trsdnt">Teacher reported students</div>-->
    <div class="cell u_crate">Conversion rate</div>
    <div class="cell u_ims">IMS</div>
    <div class="cell u_nrim">NRIM</div>
    <div class="cell u_inft">In F.T.</div>
    <div class="cell u_ftewor">FTE w/o R</div>
    <div class="cell u_fte">FTE</div>
    <div class="cell u_payng">Paying</div>
    <div class="cell u_cncl">Cancelled</div>
    <div class="cell u_rls">RLS</div>
    <div class="cell u_df1">Day off 1</div>
    <div class="cell u_df2">Day off 2</div>
    <div class="cell u_ac">Action</div>
  </div>
 <div class="clear"></div>
 <div class="dv_inactv_tchr"></div>
  <?php 
  $all_user = User::all_user('teacher');
 //print_r($all_user);
  for($i_user = 0;$i_user < count($all_user);$i_user++)
  {
	  
	  $slot_count = 0;
	  $slot_count = User::count_total_slot($all_user[$i_user]['id']);
	  $student_count = User::count_total_active_student($all_user[$i_user]['id']);
	  $payingUsers = User::count_total_payingUsers($all_user[$i_user]['id']);;
	 $teachersDtl = User::getteacherDtl($all_user[$i_user]['id']);
	 //student status block data start
	 	$all_slots = User::imploded_teacher_slots($all_user[$i_user]['id']);
		
		$ini_msg = User::total_users_by_status_id_and_teacher_slots(1,$all_slots);// 1 = Initial message sent
		$no_responseA = User::total_users_by_status_id_and_teacher_slots(2,$all_slots); // 2 = No response to initial message after 2 days
		$in_ft = User::total_users_by_status_id_and_teacher_slots(3,$all_slots);// 3 = In F.T.
		$no_responseB = User::total_users_by_status_id_and_teacher_slots(4,$all_slots);// 4 = F.T. expired without response
		$f_t_expired = User::total_users_by_status_id_and_teacher_slots(6,$all_slots);// 6 = F.T. Expired
		$paying_user = User::total_users_by_status_id_and_teacher_slots(7,$all_slots); // 7 = paying
		$cancelled = User::total_users_by_status_id_and_teacher_slots(8,$all_slots);// 8 = Cancelled 
		$rls = User::total_users_by_status_id_and_teacher_slots(9,$all_slots);// 9 = Registration link sent 
	 //student status block data end
	 ?>
    
    <div id="usrTr_<?php echo $all_user[$i_user]['id'];?>" name="usrTr_<?php echo $all_user[$i_user]['id'];?>" class="rowGroup">
    <div class="cell u_sl"><?php echo $i_user+1;?><?php User::face_photo($all_user[$i_user]['id'],$thumb = true , $w = 30 , $h = false)?></div>
    <div class="u_dtl cell">
     <div class="u_info_name_<?php echo $all_user[$i_user]['id'];?>">Name:<?php echo $all_user[$i_user]['name'];?></div>
     <div class="u_info_email_<?php echo $all_user[$i_user]['id'];?>">Email:<?php echo $all_user[$i_user]['email'];?></div>
     <div class="u_info_user_<?php echo $all_user[$i_user]['id'];?>">Username:<?php echo $all_user[$i_user]['user'];?></div>
     <div class="u_info_lurl_<?php echo $all_user[$i_user]['id'];?>">Line Url:<?php echo $teachersDtl['line_url'];?>&nbsp;</div>
     <div class="u_info_status_<?php echo $all_user[$i_user]['id'];?>">Status:<?php if($all_user[$i_user]['status']=="Y") echo "Active"; else echo "Inactive";?></div>
     <div class="">Last Login:<?php echo $all_user[$i_user]['last_login'];?></div>
    </div>
   
    <div class="cell u_ts"><?php echo $slot_count;?></div>
    <div class="cell u_tsdnt"><?php echo $student_count;?></div>
        <!--<div class="cell u_pu"><?php echo $payingUsers;?></div>-->
      <!--<div class="cell u_nlus u_info_nlus_<?php echo $all_user[$i_user]['id'];?>"><?php echo $teachersDtl['num_line_url_shown'];?>&nbsp;</div>-->
     <div class="cell u_msdnt u_info_msdnt_<?php echo $all_user[$i_user]['id'];?>"><?php echo User::getTeacherMaxStudent($all_user[$i_user]['id']);//?>&nbsp;</div>
    <div class="cell u_nlus"><?php echo $teachersDtl['num_line_url_shown'];?></div>
    <!-- <div class="cell u_trsdnt u_info_trsdnt_<?php echo $all_user[$i_user]['id'];?>"><?php echo $teachersDtl['teacher_reported_students'];?>&nbsp; </div>-->
     <div class="cell u_crate"><?php echo User::teacher_conversion_rate($all_user[$i_user]['id']).'<br />'.User::teacher_true_conversion_rate($paying_user,$cancelled,$teachersDtl['num_line_url_shown'],$all_user[$i_user]['id']).'True Cnv';?></div>
     <div class="cell u_ims"><strong><?php echo $ini_msg;?></strong></div>
     <div class="cell u_nrim"><strong><?php echo $no_responseA;?></strong></div>
     <div class="cell u_inft"><strong><?php echo $in_ft;?></strong></div>
      <div class="cell u_ftewor"><strong><?php echo $no_responseB;?></strong></div>
      <div class="cell u_fte"><strong><?php echo $f_t_expired;?></strong></div>
	 <div class="cell u_payng"><strong><?php echo $paying_user;?></strong></div>
     <div class="cell u_cncl"><strong><?php echo $cancelled;?></strong></div>
          <div class="cell u_rls"><strong><?php echo $rls;?></strong></div>

     <div class="cell u_df1 u_info_df1_<?php echo $all_user[$i_user]['id'];?>"><?php User::teacher_days_off_1($teachersDtl['day_off_1']);?>&nbsp;</div>
     <div class="cell u_df2 u_info_df2_<?php echo $all_user[$i_user]['id'];?>"><?php User::teacher_days_off_2($teachersDtl['day_off_2']);?>&nbsp;</div>
      <div class="cell u_ac">
      <?php if($_SESSION['user_role'] == 'administrator') { ?><div style="margin-top:10px;"><img src="media/images/1389630938_edit.png" class="action_btn_img"  onclick="edit_user(<?php echo $all_user[$i_user]['id'];?>);" /> &nbsp;&nbsp;<img src="media/images/1389630919_cross-24.png" class="action_btn_img" onclick="delete_user(<?php echo $all_user[$i_user]['id'];?>);" /> </div><?php } ?>
     <div> <span class="v iew-slot"><a class="" href="slot.php?teacher_id=<?php echo $all_user[$i_user]['id'];?>">View/Manage Slot</a></span></div>
     <div> <span class="v iew-slot"><a class="" href="teacher_student.php?teacher_id=<?php echo $all_user[$i_user]['id'];?>">View All Students</a></span></div></div>
    </div>
     <div class="clear"></div>
	<div class="edit_single_user user_block_<?php echo $all_user[$i_user]['id'];?>" style="display:none;">
    <div id="edit_user_frm_dtl_html_<?php echo $all_user[$i_user]['id'];?>" ></div>
    </div>
   <div class="clear"></div>
	 <?php 
  }
 
  ?>
  </div>
</div>
<div style="height:100px;">
</div>
<?php
?>
<script src="./js/jquery-scrolltofixed-min.js" type="text/javascript"></script>
<script type="text/javascript">
		$(document).ready(function() {
			// grab the initial top offset of the navigation 
		   	var stickyNavTop = $('.nav').offset().top;
		   	
		   	// our function that decides weather the navigation bar should have "fixed" css position or not.
		   	var stickyNav = function(){
			    var scrollTop = $(window).scrollTop(); // our current vertical position from the top
			         
			    // if we've scrolled more than the navigation, change its position to fixed to stick to top,
			    // otherwise change it back to relative
			    if (scrollTop > stickyNavTop) { 
			        $('.nav').addClass('sticky');
			    } else {
			        $('.nav').removeClass('sticky'); 
			    }
			};

			stickyNav();
			// and run it again every time you scroll
			$(window).scroll(function() {
				stickyNav();
			});
		});
		
function show_inactive_teacher(action)
{
	
	
	if(action == 'show')
	{
		if($('.dv_inactv_tchr').html()!='') 
		{
			$('.dv_inactv_tchr').show();
			$('.ss-all').html('Hide Inactive');
			//$('#t_l_'+id).attr('src','media/images/icon-minus.png');
			$('.ss-all').attr('onclick','show_inactive_teacher(\'hide\');');
			return false;
		}
		
	$('.ss-all-l-img').show();
	var todo = 'get_inactv_teacher';
	$.ajax({  
	type: "POST", 
	url: 'ajax/ajax_inactv_teacher.php', 
	data: "todo="+todo,
	dataType: "html",
	success: function(data){
		$('.dv_inactv_tchr').show();
		$('.dv_inactv_tchr').html(data)
		$('.ss-all-l-img').hide();
		$('.ss-all').attr('onclick','show_inactive_teacher(\'hide\');');
		$('.ss-all').html('Hide Inactive');
		}
	});
	}
	else
	{
		$('.dv_inactv_tchr').hide();
		$('.ss-all-l-img').hide();
		//$('#t_l_'+id).attr('src','media/images/1402921928_plus.png');
		$('.ss-all').attr('onclick','show_inactive_teacher(\'show\');');
		$('.ss-all').html('Show all');
		//$('#ld_dtl_'+id).attr('onclick','ld_dtl(\'show\','+id+');');
	}
	
}
function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

	</script>
</head>