<?php
/*======================================================================**
**                                                                           
** Page:Student , manage all students
** Created By : Bidhan
**                                                                           
**======================================================================*/
$current_pname = 'students';
include 'inc/inc.php';
Auth::checkAuth();
include 'inc/header.php';
?>
<script src="js/jquery.datetimepicker.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<script src="js/jquery-ui-1.10.3.js"></script>
<?php
require_once("./email/email.php");
$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);


$avoid_teacher_selection = "false";

 //if($_SESSION['user_role'] != 'administrator') { 	echo '<h3>You are restricted for this page!</h3>';exit;} 

if(isset($_POST['todo']) == 'add_new_user')
{
	Student::add_new_student();
}

$teacher_id = '';
if($_SESSION['user_role'] == 'administrator')
{
	if(isset($_REQUEST['teacher_id']))
	{
		$teacher_id = $_REQUEST['teacher_id'];
	}
	
}
else
{
	$teacher_id =$_SESSION['user_id'];
}
?>

<div class="menu_bar"><?php require_once("menu.php");?></div>
<script type="text/javascript">

      $(document).ready(function(e) {
    $('#add_user_frm').validate({
			
		rules: {		
			teacher_id: {required: true,},
			slot_id: {
				required: {
					depends: function(element) {
						return $("#teacher_id").val() != "";
					}
			  	}
			},
			
			
		},
		 messages: {  
		 teacher_id:"Please select Teacher",
			slot_id:"Please select Slot",
		  },
	
		});
});
    
function status_cross_check(first_response,status)
{
	if(status =='')
	var status = $('#status').val();
	if(first_response =='')
	var first_response = $('#first_response').val();
	if(first_response != '' && status == 1 ){
		alert("You need to change the status to in F.T. to save \"First response\"");return false;
	}
	if(status == 3 && first_response =='')
	{
		alert("system shouldn't allow to change and save the status to \"In F.T.\" unless Fast response date is entered");return false;
	}
	
	return true;
}
 $(document).ready(function(e) {
	 $('#first_response').datetimepicker({
			datepicker:true,
			format:'Y-m-d H:i:00',
			step:60,
			validateOnBlur:false,
			onChangeDateTime:function(selectedDate){//alert(selectedDate)
			
			selectedDate.setDate(selectedDate.getDate()+<?php echo FT_EXPIRATION_DAYS;?>);
			
			
			var y = selectedDate.getFullYear();
			var mm =(selectedDate.getMonth()+1);
			var d = selectedDate.getDate();
			var h = selectedDate.getHours();
			var m = selectedDate.getMinutes();
			var s = selectedDate.getSeconds();
			
			
			var nmm = (mm>9?mm:"0"+mm);
			var dd = (d>9?d:"0"+d);
			var hh = (h>9?h:"0"+h);
			var minut = (m>9?m:"0"+m);
			var ss = (s>9?s:"0"+s);
			
			//$('#ft_expiration').val(y+'-'+m+'-'+d+' '+h+':'+h+':'+s);	
			
			
			$('#ft_expiration').val(selectedDate.getFullYear()+'-'+nmm+'-'+dd+' '+hh+':'+minut+':'+ss);	
				
				
			
			
			}
		});
	$('#ft_expiration').datetimepicker({
			datepicker:true,
			format:'Y-m-d H:i:00',
			step:60,
			validateOnBlur:false
		});
	$('#first_paid_date').datetimepicker({
			datepicker:true,
			format:'Y-m-d H:i:00',
			step:60,
			validateOnBlur:false
		});
	$('#paid_through').datetimepicker({
		datepicker:true,
		format:'Y-m-d H:i:00',
		step:60,
		validateOnBlur:false
	});
		
	$('#cancellation_date').datetimepicker({
			datepicker:true,
			format:'Y-m-d H:i:00',
			step:60,
			validateOnBlur:false
		});
    //$('#first_response').datepicker({ dateFormat: "yy-mm-dd" });
	$('#dob').datetimepicker({ 
	format: "Y-m-d",
        timepicker:false,
       yearStart:1930,
		yearEnd:2020,
		onChangeDateTime:function(selectedDate){
			convert_to_age(selectedDate,'age');
			} });
		
	$('#age').keyup(function(e) {
        convert_to_dob($(this).val(),'dob')
    });
	
});   

function convert_to_age(birthday,fld)
{
	var ageDifMs = Date.now() - birthday.getTime();
    var ageDate = new Date(ageDifMs); // miliseconds from epoch
    var age=  Math.abs(ageDate.getUTCFullYear() - 1970);
	$('#'+fld).val(age);
}
function convert_to_dob(age,fld)
{
	if(age =='') {$('#'+fld).val('');return false }
	var d= new Date();
	var yy = d.getFullYear() - parseInt( age);
	var mm =d.getMonth()+1;
	var dd = d.getDate();
			var nmm = (mm>9?mm:"0"+mm);
			var ndd = (dd>9?dd:"0"+dd);
	$('#'+fld).val(yy+"-"+nmm+"-"+ndd);
}



function edit_user(uid)
{
	$(".user_block_"+uid).show();
	$('.user_form').hide();
	$("#edit_user_frm_dtl_html_"+uid).html('Please wait...');
	var todo = 'edit_user_html';
	$.ajax({  
			type: "POST", 
			url: 'ajax/ajax_student.php', 
			data: "todo="+todo+"&uid="+uid,
			dataType: "html",

			success: function(data){
				$("#edit_user_frm_dtl_html_"+uid).html(data);
				$('#created_on_'+uid).datetimepicker({
						datepicker:true,
						format:'Y-m-d H:i:00',
						step:60
					});
				$('#first_paid_date_'+uid).datetimepicker({
						datepicker:true,
						format:'Y-m-d H:i:00',
						step:60,
						validateOnBlur:false,
					});
				$('#paid_through_'+uid).datetimepicker({
					datepicker:true,
					format:'Y-m-d H:i:00',
					step:60,
					validateOnBlur:false,
				});
				$('#cancellation_date_'+uid).datetimepicker({
						datepicker:true,
						format:'Y-m-d H:i:00',
						step:60,
						validateOnBlur:false,
					});
				$('#first_response_'+uid).datetimepicker({
						datepicker:true,
						format:'Y-m-d H:i:00',
						step:60,
						validateOnBlur:false,
						onChangeDateTime:function(selectedDate){//alert(selectedDate)
			
			selectedDate.setDate(selectedDate.getDate()+<?php echo FT_EXPIRATION_DAYS;?>);
			
			
			var y = selectedDate.getFullYear();
			var mm =(selectedDate.getMonth()+1);
			var d = selectedDate.getDate();
			var h = selectedDate.getHours();
			var m = selectedDate.getMinutes();
			var s = selectedDate.getSeconds();
			
			
			var nmm = (mm>9?mm:"0"+mm);
			var dd = (d>9?d:"0"+d);
			var hh = (h>9?h:"0"+h);
			var minut = (m>9?m:"0"+m);
			var ss = (s>9?s:"0"+s);
			
			//$('#ft_expiration').val(y+'-'+m+'-'+d+' '+h+':'+h+':'+s);	
			
			
			$('#ft_expiration_'+uid).val(selectedDate.getFullYear()+'-'+nmm+'-'+dd+' '+hh+':'+minut+':'+ss);	
				
				
			
			
			}
					});
				$('#ft_expiration_'+uid).datetimepicker({
						datepicker:true,
						format:'Y-m-d H:i:00',
						step:60,
						validateOnBlur:false,
					});
					
					
					$('#dob_'+uid).datetimepicker({ 
	format: "Y-m-d",
        timepicker:false,
       yearStart:1930,
		yearEnd:2020,
		onChangeDateTime:function(selectedDate){
			convert_to_age(selectedDate,'age_'+uid);
			} });
	
$('#age_'+uid).keyup(function(e) {
        convert_to_dob($(this).val(),'dob_'+uid)
    });
				//$('#created_on_'+uid).datepicker({ dateFormat: "yy-mm-dd 00:00:00" });
				//$('#first_response_'+uid).datepicker({ dateFormat: "yy-mm-dd 00:00:00" });
				//$('#ft_expiration_'+uid).datepicker({ dateFormat: "yy-mm-dd 00:00:00" });
				
				//var y = $(window).scrollTop();  //your current y position on the page
				//$(window).scrollTop(y+400);
				
				var dTag = $("div[name='usrTr_"+ uid +"']");
   				 $('html,body').animate({scrollTop: dTag.offset().top},'slow');
	
				 $('#edit_usr_frm_'+uid).validate({	
		rules: {		
			//f_name: {	required: true,			},
		//	l_name: {	required: true,			},
			/*created_on: {	required: true,			},
			first_response: {	required: true,			},
			ft_expiration: {	required: true,			},
			unique_user_id: {
						required: true,
						remote:
					 	{
							 url: 'ajax/validate_userinfo.php?user_id='+uid,
							 async: false,
							 type: "post",
							 data:
								  {
									  u_user: function()
									  {
										  return $('#update_user_frm :input[name="unique_user_id"]').val();
									  }
								  }
						}
		   },*/
			
		},
		 messages: {  
			/* unique_user_id:
						 {
							remote: $.validator.format("Unique user ID already exist.")
						 }*/
		  },
		 submitHandler: function() { save_changes(uid); return false; }
	});
				
				}  
	      });
				
		function save_changes(uid)
		{
			var f_name = $('#edit_usr_frm_'+uid).find('input[name="u_f_name"]').val();
			var l_name = $('#edit_usr_frm_'+uid).find('input[name="u_l_name"]').val();
			var english_name = $('#edit_usr_frm_'+uid).find('input[name="u_english_name"]').val();
			
			var email = $('#edit_usr_frm_'+uid).find('input[name="u_email"]').val();
			var pay_email = $('#edit_usr_frm_'+uid).find('input[name="u_pay_email"]').val();
			var comment = $('#edit_usr_frm_'+uid).find('textarea[name="u_comment"]').val();
			var line_id = $('#edit_usr_frm_'+uid).find('input[name="u_line_id"]').val();
			var converted_by = $('#edit_usr_frm_'+uid).find('#converted_by_'+uid+' :selected').val();
			
			
			var pay_info_sent = $('#edit_usr_frm_'+uid).find("#pay_info_sent_id_"+uid).is(":checked");
			if(pay_info_sent==true)
			{
				pay_info_sent = 1;
			}
			else
			{
				pay_info_sent = 0;
			}
			
			var unique_user_id = $('#edit_usr_frm_'+uid).find('input[name="unique_user_id"]').val();
			var created_on = $('#edit_usr_frm_'+uid).find('input[name="created_on"]').val();
			var first_response = $('#edit_usr_frm_'+uid).find('input[name="first_response"]').val();
			var ft_expiration = $('#edit_usr_frm_'+uid).find('input[name="ft_expiration"]').val();
			var first_paid_date = $('#edit_usr_frm_'+uid).find('input[name="first_paid_date"]').val();
			var paid_through = $('#edit_usr_frm_'+uid).find('input[name="paid_through"]').val();
			var cancellation_date = $('#edit_usr_frm_'+uid).find('input[name="cancellation_date"]').val();

			var slot = $('#edit_usr_frm_'+uid).find('input:radio[name="slot_id"]:checked').val();
			var plan = $('#edit_usr_frm_'+uid).find('#plan_'+uid+' :selected').val();
			var plan_html = $('#edit_usr_frm_'+uid).find('#plan_'+uid+' :selected').text();
			var payment_method = $('#edit_usr_frm_'+uid).find('input:radio[name="payment_method"]:checked').val();
			var engagement_level = $('#edit_usr_frm_'+uid).find('input:radio[name="engagement_level"]:checked').val();
			var life_status = $('#edit_usr_frm_'+uid).find('#life_status_'+uid+' :selected').val();
			var funnel = $('#edit_usr_frm_'+uid).find('#funnel_'+uid+' :selected').val();
			var gender = $('#edit_usr_frm_'+uid).find('#gender_'+uid+' :selected').val();
			var gender_name = $('#edit_usr_frm_'+uid).find('#gender_'+uid+' :selected').text();
			var dob = $('#edit_usr_frm_'+uid).find('input[name="dob"]').val();
			var age = $('#edit_usr_frm_'+uid).find('input[name="age"]').val();
		
			var slot_time = $('#span_u_'+uid+'_slot_'+slot).html();
			var strt_time_show = $('#show_list_strt_time_'+uid+'_'+slot).val(); 
			var end_time_show = $('#show_list_end_time_'+uid+'_'+slot).val(); 
			var slot_type =$('#slot_type_'+uid+'_'+slot).val(); 
			
			if(slot_type == 'NTS') var final_slot_show = 'NTS';
			else if(slot_type == 'Coaching') var final_slot_show = 'Coach';
			else var final_slot_show =  strt_time_show+" To "+end_time_show;
			
			//alert(slot_time); return false;
			var status = $('#edit_usr_frm_'+uid).find('#status_'+uid+' :selected').val();
			var status_name = $('#edit_usr_frm_'+uid).find('#status_'+uid+' :selected').text();
			if(status_cross_check(first_response,status) == false) return false;
			
			var todo = 'update_user_dtl';
			$.ajax({  
			type: "POST", 
			url: 'ajax/ajax_student.php', 
			data: "todo="+todo+"&uid="+uid+"&f_name="+f_name+"&l_name="+l_name+"&english_name="+english_name+"&email="+email+"&line_id="+line_id+"&slot="+slot+"&first_response="+first_response+"&ft_expiration="+ft_expiration+"&status="+status+"&plan="+plan+"&payment_method="+payment_method+"&created_on="+created_on+"&unique_user_id="+unique_user_id+"&pay_email="+pay_email+"&comment="+comment+"&pay_info_sent="+pay_info_sent+"&first_paid_date="+first_paid_date+"&cancellation_date="+cancellation_date+"&engagement_level="+engagement_level+"&life_status="+life_status+"&gender="+gender+"&dob="+dob+"&age="+age+"&paid_through="+paid_through+"&converted_by="+converted_by+"&funnel="+funnel,
			dataType: "html",

			success: function(data){
				
				//var plan_html = plan.replace('_',' ');
				var life_status_html = life_status.replace('_',' ');
				var engagement_level_html = '';
				//plan_html =  plan_html.charAt(0).toUpperCase() + plan_html.slice(1);
				
				if(engagement_level == 0)  engagement_level_html = 'Unknown';
				else if(engagement_level == 1)  engagement_level_html = 'Low';
				else if(engagement_level == 5)  engagement_level_html = 'Medium';
				else if(engagement_level == 10)  engagement_level_html = 'High';
				else engagement_level_html = '';
				
				
				$('.u_info_user_'+uid).html(unique_user_id);
				$('.u_info_fname_'+uid).html(f_name);
				$('.u_info_lname_'+uid).html(l_name);
				$('.u_info_engname_'+uid).html(english_name);
				$('.u_info_em_'+uid).html(email);
				$('.u_info_pem_'+uid).html(pay_email);
				$('.u_info_ts_'+uid).html(final_slot_show);
				$('.u_info_jd_'+uid).html(created_on.substr(0,10));
				$('.u_info_fr_'+uid).html(first_response.substr(0,10));
				$('.u_info_bd_'+uid).html(ft_expiration.substr(0,10));
				$('.u_info_fp_'+uid).html(first_paid_date.substr(0,10));
				$('.u_info_pt_'+uid).html(paid_through.substr(0,10));
				//$('.u_info_dob_'+uid).html(dob);
				$('.u_info_age_'+uid).html(age);
				$('.u_info_gen_'+uid).html(gender_name);
				
				status_name_edit_html = '<span class="change_avatars_span" id="span_change_status_'+uid+'" onclick="change_status('+status+','+uid+');">'+status_name+'</span><span id="span_dplist_change_status_'+uid+'"></span><br/><br/><div id="csl_'+uid+'"><a style="text-decoration:underline; cursor:pointer; color: #00C;" title="Change Status Log" onclick="statusChangeLog('+uid+');" >Status Log</a></div>';
				
				$('.u_info_st_'+uid).html(status_name_edit_html);
				
				$('.u_p_'+uid).html(plan_html);
				$('.u_info_el_'+uid).html(engagement_level_html);
				$('.u_info_ls_'+uid).html(life_status_html.charAt(0).toUpperCase()+ life_status_html.slice(1));
				
				if(comment.length>50)
				{
					var ncomt = comment.substring(0,50);
					$('#dialog-comt-txt-'+uid).html(comment);
					$('.u_cmnt_'+uid).html(ncomt+'...<a onclick="showFullComment('+uid+');" title="'+comment+'" style="text-decoration:underline; cursor:pointer;" class="chios_tooltip">More</a>');
				}
				else
				{
					$('#dialog-comt-txt-'+uid).html(comment);
				}
				
				if(pay_info_sent==0)
				{	
					
					var todo = "check_for_background_color";
					$.ajax({  
						type: "POST", 
						url: 'ajax/ajax_student.php', 
						data: "todo="+todo+"&uid="+uid+"&ft_expiration="+ft_expiration+"&status="+status+"&pay_info_sent="+pay_info_sent,
						dataType: "html",
			
						success: function(data){ 
						
							if($.trim(data)=="whiteBG")
							{
								$('.u_info_pmnt_'+uid).html('Not Sent');
								$('#usrTr_'+uid).removeClass('yellowBG').addClass('whiteBG');
							}
							else
							{
								$('.u_info_pmnt_'+uid).html('<span class="aro_ind" style="font-size:20px;">Not Sent</span>');
								$('#usrTr_'+uid).removeClass('whiteBG').addClass('yellowBG');
							}
						}
					});
					
				}
				else
				{
					$('.u_info_pmnt_'+uid).html('Sent');
					$('#usrTr_'+uid).removeClass('yellowBG').addClass('whiteBG');
				}
				var old_status_db = $('#old_status_db').val();
				if(old_status_db != status)
				send_email_noti_new_paying_user(uid,status);
				/*if(status == "Y")
				$('.u_info_st_'+uid).html("Active");
				else
				$('.u_info_st_'+uid).html("Inactive");*/
				
				
				$("#edit_user_frm_dtl_html_"+uid).html('');
				$(".user_block_"+uid).hide();
				//$('.u_info_permission_'+uid).html(new_arr_str.substring(0, new_arr_str.length - 1));
  
			}
			});
			
			
			return false;
		}
}
function showAddNewUrsFrm()
{

	$(".user_form").show();
	$(".search_box").hide();
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
	var u_name = $('.u_info_fname_'+uid).text()+" "+$('.u_info_lname_'+uid).text();
	if(confirm('Are you sure you want to DELETE '+u_name+'?') == false ) return false;
	var todo = 'delete_user';
	$.ajax({  
	type: "POST", 
	url: 'ajax/ajax_student.php', 
	data: "todo="+todo+"&uid="+uid,
	dataType: "html",

	success: function(data){
	$("#usrTr_"+uid).remove();
	$(".user_block_"+uid).remove();
	
	}
});
}

function getTeacherSlot()
{
	//alert(11); create student
	var teacher_id = $('select#teacher_id').val();
	
	var todo = 'get_teacher_slot_html';
	$.ajax({  
	type: "POST", 
	url: 'ajax/ajax_user.php', 
	data: "todo="+todo+"&teacher_id="+teacher_id,
	dataType: "html",

	success: function(data){
	$("#dv_teacher_slot").html(data);
	//alert(1);
	}
});
}

function show_expired_students()
{
	/*if($('.hide_student').css('display') == 'none')
			{ 
				//$(".hide_student").show();
				var show_expired = "show";
				$('#show_hide_expired_student').html('Hide expired student');
				$('#show_hide_expired_student').css('color','#C00');
			}
			else
			{ 
				//$(".hide_student").hide();
				var show_expired = "show";
				$('#show_hide_expired_student').html('Show expired student');
				$('#show_hide_expired_student').css('color','#FFF');
			}*/
	if($('#show_expired').val() == '0')
	{	
		$('#show_expired').val('1'); //show all
		ShowStudentBySearch();
		
	}
	else
	{
		$('#show_expired').val('0'); //hide expired and who have sent payment info
		ShowStudentBySearch();
	}
	/*var todo = 'show_hide_expired_student';
	$.ajax({  
	type: "POST", 
	url: 'ajax/ajax_user.php', 
	data: "todo="+todo+"&show_expired="+show_expired,
	dataType: "html",

	success: function(data){}
	
});*/
}

 
function getTeacherSlotAjax(u_id)
{
	//alert(1); list student
	var teacher_id = $('select#teacher_id_'+u_id).val();
	var todo = 'get_teacher_slot_html';
	$.ajax({  
	type: "POST", 
	url: 'ajax/ajax_user.php', 
	data: "todo="+todo+"&teacher_id="+teacher_id,
	dataType: "html",

	success: function(data){
	$("#dv_teacher_slot_"+u_id).html(data);
	
	}
});
}

function change_status(status_id,student_id){
	
	//alert(1);

                var todo = "status_drop_list";
				//var data = {"type":type,"subtopic_id":subtopic_id,"existing_cell_id":cell_id};
				//$.pos
				$.ajax({
					type: "POST",
					url: 'ajax/ajax_user.php',
					data: "todo="+todo+"&status_id="+status_id+"&student_id="+student_id,
					dataType: "text",
					success: function(data){
						
						
							$('#span_dplist_change_status_'+student_id).html(data);
							//$('#span_avatar_dplist_phrase_'+phrase+'_'+phrase_id_1).attr('onclick','').unbind("onclick");
							$('#span_change_status_'+student_id).hide();
						
						
						}
				}); 
				
                }
				
				function change_status_db(student_id,old_status)
				{
					
					var new_status_id = $("select#span_change_status_"+student_id).val();
					var new_status_name = $("select#span_change_status_"+student_id+" :selected").text();
					
					var todo = "change_status_db";
				$.ajax({
					type: "POST",
					url: 'ajax/ajax_user.php',
					data: "todo="+todo+"&new_status_id="+new_status_id+"&student_id="+student_id+"&old_status="+old_status,
					dataType: "text",
					success: function(data){
						
						
						
							$('#span_change_status_'+student_id).show();
					   		$('#span_dplist_change_status_'+student_id).html('');
							$('#span_change_status_'+student_id).removeAttr("onclick", null).attr("onclick", "change_status("+new_status_id+","+student_id+")")
							$('#span_change_status_'+student_id).html(new_status_name);
							
							send_email_noti_new_paying_user(student_id,new_status_id);
							
							// Update row color 
							$.ajax({
							type: "POST",
							url: 'ajax/ajax_user.php',
							data: "todo=update_row_bg&student_id="+student_id,
							dataType: "text",
							success: function(data){
								
								if($.trim(data)=="whiteBG")
								{
									$('.u_info_pmnt_'+student_id).html('Not Sent');
									$('#usrTr_'+student_id).removeClass('yellowBG').addClass('whiteBG');
								}
								else
								{
									$('.u_info_pmnt_'+student_id).html('<span class="aro_ind" style="font-size:20px;">Not Sent</span>');
									$('#usrTr_'+student_id).removeClass('whiteBG').addClass('yellowBG');
								}
								
							}});
						
						}
				}); 
				
				
					
					
				}
 
  
    </script>
    
    <style type="text/css">
    .label_u{width:160px;}
    </style>
<div style="width:auto;">
<span style="float:left;">
 <?php if($_SESSION['user_role'] != 'viewer') { ?><a href="#" onclick="showAddNewUrsFrm()">Add New Student</a><?php } ?>
</span>
<span id="show_hide_expired_student" style="background: none repeat scroll 0 0 #999999; color:#FFF; cursor: pointer;float: right; padding: 0 5px; text-align: center; width: 170px;" onclick="show_expired_students();" >
Hide expired student
</span>
</div>
<?php include("search_box.php");?> 
<?php if($_SESSION['user_role'] != 'viewer') { ?>
<div class="user_form" style="display:none;width:auto;">
<form name="add_user_frm" id="add_user_frm" action="" method="post" onsubmit="return status_cross_check('','');" > <!--onsubmit="return checkstudentaddpossible();"-->
<input type="hidden" name="todo"  value="add_new_user"/>
<input type="hidden" name="role"  value="student"/>
<input type="hidden" name="action_from"  value="admin"/>

<div class="create_new_cls" >
    <div class="single_row">
        <div class="label_u">First Name:</div>
        <div><input type="text" class="" name="f_name" /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Last name:</div>
        <div><input type="text" class="" name="l_name"  /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">English name:</div>
        <div><input type="text" class="" name="english_name"  /></div>
    </div>
     <?php if($_SESSION['user_role'] == 'administrator')
	{ ?>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Contact Email:</div>
        <div><input type="text" class="" name="email"  /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Paypal Email:</div>
        <div><input type="text" class="" name="pay_email"  /></div>
    </div>
    <?php } ?>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Line ID:</div>
        <div><input type="text" class="" name="line_id"  /></div>
    </div>
    <!--<div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Unique User ID:</div>
        <div><input type="text" class="required " name="unique_user_id" id="unique_user_id"  /></div>
    </div>-->
    
    <div class="clear"></div>
    <?php if($_SESSION['user_role'] == 'administrator')
{ ?>
    <div class="single_row">
        <div class="label_u">Select Teacher:</div>
        <div>
        <select id="teacher_id" name="teacher_id" onchange="getTeacherSlot();" >
        <option value="">All Teachers</option>
         <?php
     $all_user = User::all_user('teacher','',$show_all=true);
 // print_r($all_user);
  for($i_user = 0;$i_user < count($all_user);$i_user++)
  {
	  ?>
	 <option value="<?php echo $all_user[$i_user]['id'];?>"> <?php echo $all_user[$i_user]['name'];?></option>
	  <?php
  }
	?>
        </select>
        </div>
    </div>
    
   <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Select Slot:</div>
        <div id="dv_teacher_slot">Select teacher first</div>
    </div>
    <?php } else{ ?>
     <div class="single_row">
        <div class="label_u">Select Slot:</div>
        <div id="">
       <?php
       $slot = Student::get_teacher_slot($teacher_id);
		if(count($slot) > 0)
		{
			echo '<ul class="slot-ul">';
			for($i=0; $i < count($slot); $i++)
			{
				?>
				<li><input type="radio" name="slot_id" value="<?php echo $slot[$i]['id'];?>" <?php if($i==0) echo 'checked="checked"';?> /><?php if($slot[$i]['empty_slot']==1) echo "No Timeslot";elseif($slot[$i]['coaching']==1) echo "Coaching"; else echo $slot[$i]['start_time'].'&nbsp;To&nbsp;'.$slot[$i]['end_time'];?></li>
				<?php 
			}
			echo '</ul>';
		}
		else
		{
			echo 'NO slot';
		}
		
	   ?>
        </div>
    </div>
    
    <?php } ?>
    <div class="clear"></div>
	<div style="margin-left:205px;"><label class="error" for="slot_id" generated="true"></label></div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">First response:</div>
        <div><input type="text" class="" name="first_response"  id="first_response"/></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Last day of F.T.:</div>
        <div><input type="text" class="" name="ft_expiration" id="ft_expiration" /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">First Paid Date:</div>
        <div><input type="text" class="" name="first_paid_date" id="first_paid_date" /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Paid through:</div>
        <div><input type="text" class="" name="paid_through" id="paid_through" /></div>
    </div>
     <?php if($_SESSION['user_role'] == 'administrator')
	{ ?>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Cancellation Date:</div>
        <div><input type="text" class="" name="cancellation_date" id="cancellation_date" /></div>
    </div>
    <?php } ?>
    <div class="clear"></div>
     <!--<div class="single_row">
        <div class="label_u">Status:</div>
        <div>Active<input type="radio" checked="checked"  name="status" value="Y"/>&nbsp;&nbsp;Inactive<input type="radio" name="status" value="N" /></div>
    </div>-->
    <div class="single_row">
        <div class="label_u">Status:</div>
        <div>
        <select id="status" name="status">
         <?php
     $all_status = User::all_status();
 // print_r($all_user);
  for($i_user = 0;$i_user < count($all_status);$i_user++)
  {
	  ?>
	 <option value="<?php echo $all_status[$i_user]['id'];?>" <?php  if($_SESSION['user_role'] == 'teacher' &&  $all_status[$i_user]['id'] >= 7 && $all_status[$i_user]['id'] !=9 ){ echo 'disabled="disabled"';} ?> > <?php echo $all_status[$i_user]['name'];?></option>
	  <?php
  }
	?>
        </select>
        </div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Payment info Sent:</div>
        <div id="pay_info_sent_id"><input type="checkbox" name="pay_info_sent" value="1" /></div>
    </div>
     <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Plan:</div>
        <!--<div><input type="radio" name="plan" value="all_week" checked="checked"/>All week &nbsp;<input type="radio" name="plan" value="weekend" />Weekend &nbsp;<input type="radio" name="plan" value="midweek" />Midweek</div>-->
        <div><?php echo Plan::populate_plans_select_option('plan','plan',false);?></div>
    </div>
     <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Payment method:</div>
        <div><input type="radio" name="payment_method" value="Paypal" checked="checked"/>Paypal &nbsp;<input type="radio" name="payment_method" value="Braintree" />Braintree</div>
    </div>
     <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Engagement Level:</div>
        <div><input type="radio" name="engagement_level" value="0"/>Unknown &nbsp;<input type="radio" name="engagement_level" value="1"/>Low &nbsp;<input type="radio" name="engagement_level" value="5"  checked="checked"/>Medium &nbsp;<input type="radio" name="engagement_level" value="10" />High</div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Life Status:</div>
        <div>
        <select id="life_status" name="life_status">
        	<option value="unknown">Unknown</option>
            <option value="high_school">High School</option>
            <option value="college">College</option>
            <option value="working">Working</option>
        </select>
        </div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Funnel:</div>
        <div>
        <select id="funnel" name="funnel">
            <option value="">Select</option>
			<?php 
			$funnels_array = Student::all_funnels();
            for($fnl_i=1;$fnl_i <= count($funnels_array);$fnl_i++)
            {
               ?><option value="<?php echo $fnl_i?>" <?php if($fnl_i == 1) echo 'selected="selected"';?>><?php echo $funnels_array[$fnl_i]?></option><?php
            }
            ?>  
        </select>
        </div>
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
        <div class="label_u">Age:</div>
        <div>
        <input type="text" name="age" id="age" style="width:80px;" /> &nbsp;&nbsp; Or DOB: <input type="text" name="dob" id="dob" style="width:130px;" />
        </div>
    </div>
    
     <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Memo / Comment:</div>
        <div><textarea name="comment" id="comment" style="width:300px;"></textarea></div>
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
<div class="list_user" >
please wait...
</div>
<div style="height:100px;">
</div>
<?php
?>
<script src="./js/jquery-scrolltofixed-min.js" type="text/javascript"></script>

</head>