<?php
/*======================================================================**
**                                                                           
** Main entry point.
** All PHP (mode) calls will go through this script.  
**                                                                           
**======================================================================*/
$current_pname = 'slot';
include 'inc/inc.php';

Auth::checkAuth();



include 'inc/header.php';
?>


   
    <script src="js/jquery.datetimepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
    

    <script type="text/javascript">
	$(document).ready(function(e) {
        $('#start_time').datetimepicker({
			datepicker:false,
			format:'H:i',
			step:60
		});
		$('#end_time').datetimepicker({
			datepicker:false,
			format:'H:i',
			step:60
		});
    });
        
    </script>


<?php


$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);

//print_r($_SESSION);
//n_owc start
if(isset($_POST['todo']) == 'add_new_slot')
{
	User::add_new_slot();
}
//n_owc end
?>
<?php
if($_SESSION['user_role'] == 'administrator' || $_SESSION['user_role'] == 'viewer')
{
	if(!isset($_REQUEST['teacher_id'])) exit;
	
	$teacher_id = $_REQUEST['teacher_id'];
}
else
{
	$teacher_id =$_SESSION['user_id'];
}

if(empty($teacher_id))
{
	exit;
}

?>
<!-- Bidhan added menu and filtaring  -->
<div class="menu_bar"><?php require_once("menu.php");?></div>


<script type="text/javascript">

      $(document).ready(function(e) {
		  
		  
		  
	
});

function check_time_picker_start_value()
 {
	 var check = $("#start_time").val();
	 if(check=="00:00")
	 {
		 $("#start_time").val('24:00');
	 }
	 
 }
function check_time_picker_end_value()
 {
	 var check = $("#end_time").val();
	 if(check=="00:00")
	 {
		 $("#end_time").val('24:00');
	 }
	 
 }
function check_time_picker_start_value_edit(id)
 {
	 var check = $("#start_time_"+id).val();
	 if(check=="00:00")
	 {
		 $("#start_time_"+id).val('24:00');
	 }
	 
 }
function check_time_picker_end_value_edit(id)
 {
	 var check = $("#end_time_"+id).val();
	 if(check=="00:00")
	 {
		 $("#end_time_"+id).val('24:00');
	 }
	 
 }

function no_slot_apply()
 {
 
      if(document.add_user_frm.no_slot.checked == true)
      {
           $("#start_time").addClass("slot_time_disabled");
		   $("#end_time").addClass("slot_time_disabled");
		   /*document.add_user_frm.start_time.value = "";
		   document.add_user_frm.end_time.value = "";*/
		   document.add_user_frm.start_time.disabled = true;
		   document.add_user_frm.end_time.disabled = true;
		   document.add_user_frm.no_slot_value.value=1;
		   
      }
     else // if the user unchecks the checkbox after checking it, then disable the submit again
     {
          $("#start_time").removeClass("slot_time_disabled");
		  $("#end_time").removeClass("slot_time_disabled");
		  document.add_user_frm.start_time.disabled = false;
		  document.add_user_frm.end_time.disabled = false;
		  document.add_user_frm.no_slot_value.value=0;
      }
 
 }
       
function edit_slot(slot_id)
{
	$(".user_block_"+slot_id).show();
	$('.user_form').hide();
	$("#edit_user_frm_dtl_html_"+slot_id).html('Please wait...');
	var todo = 'edit_slot_html';
	$.ajax({  
			type: "POST", 
			url: 'ajax/ajax_user.php', 
			data: "todo="+todo+"&slot_id="+slot_id,
			dataType: "html",

			success: function(data){
				$("#edit_user_frm_dtl_html_"+slot_id).html(data);
				var no_slot_value  = $('#edit_usr_frm_'+slot_id).find('input[name="empty_slot_value"]').val();
				//alert(no_slot_value); return false;
				//var y = $(window).scrollTop();  //your current y position on the page
				//$(window).scrollTop(y+400);
				
				var dTag = $("div[name='usrTr_"+ slot_id +"']");
   				 $('html,body').animate({scrollTop: dTag.offset().top},'slow');
			if(no_slot_value==0)
			{
				 $('#start_time_'+slot_id).datetimepicker({
				datepicker:false,
				format:'H:i',
				step:60,
				validateOnBlur:false,
				});
				$('#end_time_'+slot_id).datetimepicker({
					datepicker:false,
					format:'H:i',
					step:60,
					validateOnBlur:false,
				});
		
			
							 $('#edit_usr_frm_'+slot_id).validate({	
					rules: {		
						start_time: {
							required: true,
						},
						end_time: {
							required: true,
							
						},
					},
					messages: {		
						start_time:
							 {
								required: "Please enter Start Time.",
							 },
						end_time:
							 {
								required: "Please enter End Time.",
							 }
						
					},
					 submitHandler: function() { save_changes(slot_id); return false; }
				});
			}
			else
			{
				$('#edit_usr_frm_'+slot_id).validate({	
					rules: {		
						max_students: {
							required: true,
						},
						
					},
					
					 submitHandler: function() { save_changes(slot_id); return false; }
				});
			}
	
				
				}  
	      });
				
		function save_changes(slot_id)
		{
			var no_slot_value  = $('#edit_usr_frm_'+slot_id).find('input[name="empty_slot_value"]').val();
			var todo = 'update_slot_dtl';
			if(no_slot_value==0)
			{
				var start_time = $('#edit_usr_frm_'+slot_id).find('input[name="start_time"]').val();
				var end_time = $('#edit_usr_frm_'+slot_id).find('input[name="end_time"]').val();
				var max_students = $('#edit_usr_frm_'+slot_id).find('input[name="max_students"]').val();
				var status = $('#edit_usr_frm_'+slot_id).find('input:radio[name="status"]:checked').val();
				var data = "todo="+todo+"&slot_id="+slot_id+"&start_time="+start_time+"&end_time="+end_time+"&status="+status+"&max_students="+max_students+"&no_slot_value="+no_slot_value;
			}
			else
			{
				var max_students = $('#edit_usr_frm_'+slot_id).find('input[name="max_students"]').val();
				var status = $('#edit_usr_frm_'+slot_id).find('input:radio[name="status"]:checked').val();
				var data = "todo="+todo+"&slot_id="+slot_id+"&status="+status+"&max_students="+max_students+"&no_slot_value="+no_slot_value;
			}
			
			$.ajax({  
			type: "POST", 
			url: 'ajax/ajax_user.php', 
			data: data,
			dataType: "html",

			success: function(data){
				
				if(no_slot_value==0)
				{
					$('.u_info_name_'+slot_id).html(start_time+":00");
					$('.u_info_email_'+slot_id).html(end_time+":00");
				}
				$('.u_info_max_students_'+slot_id).html(max_students);
				if(status==1)
				$('.u_info_status_'+slot_id).html("Active");
				else
				$('.u_info_status_'+slot_id).html("Inactive");
				
				$("#edit_user_frm_dtl_html_"+slot_id).html('');
				$(".user_block_"+slot_id).hide();
				/*$('.u_info_permission_'+uid).html(new_arr_str.substring(0, new_arr_str.length - 1));*/
  
			}
			});
			
			
			return false;
		}
}
function showAddNewUrsFrm()
{

	$(".user_form").show();
}

function no_slot_edit_alert()
{

	alert("You can not Edit this one");
	return false;
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
function delete_slot(slot_id)
{
	if(confirm('Are you sure you want to DELETE this Slot?') == false ) return false;
	var todo = 'delete_slot';
	$.ajax({  
	type: "POST", 
	url: 'ajax/ajax_user.php', 
	data: "todo="+todo+"&slot_id="+slot_id,
	dataType: "html",

	success: function(data){
	$("#usrTr_"+slot_id).remove();
	$(".user_block_"+slot_id).remove();
	location.reload();
	
	}
});
}
function changeSlotStatus(status)
{
	var teacher_id = $('#teacher_id').val();
	var todo = 'change_slot_status';
	$.ajax({  
	type: "POST", 
	url: 'ajax/ajax_user.php', 
	data: "todo="+todo+"&status="+status+"&teacher_id="+teacher_id,
	dataType: "html",

	success: function(data){
	if(status == 1)
	$('.u_info_status_al').html('Active');
	else
	$('.u_info_status_al').html('Inactive');
	//location.reload();
	
	}
	});
}
    </script>
<div class="main">
<?php $teacher_dtls = User::singleUserDetails($teacher_id); //print_r($teacher_dtls); ?> 
<h3 class="hd-title">Teacher Name :<?php echo $teacher_dtls['name'];?><span style="margin:0 10px;"> <?php User::face_photo($teacher_id,$thumb = true , $w = 30 , $h = false)?></span>&nbsp;&nbsp;&nbsp; Slot List</h3> 
 <?php if($_SESSION['user_role'] == 'administrator' || $_SESSION['user_role'] =='teacher') { ?>
 <div style="margin:5px 0;"><a href="#" onclick="showAddNewUrsFrm()">Add New Slot</a><span style="float:right;"><input type="button" class="btn" value="Active all" onclick="changeSlotStatus(1);" /><input type="button"  class="btn"  value="Inactive all" onclick="changeSlotStatus(0);"  /></span></div>
<div class="user_form" style="display:none;width:auto;">
<form name="add_user_frm" id="add_user_frm" action="" method="post" >
<input type="hidden" name="todo"  value="add_new_slot"/>
<input type="hidden" name="role"  value="teacher"/>
<input type="hidden" name="teacher_id" id="teacher_id"  value="<?php echo $teacher_id?>"/>
<input type="hidden" name="no_slot_value"  value="0"/>
<div class="create_new_cls" >
    <div class="single_row">
        <div class="label_u">Start Time:</div>
        <div><input type="text" class="required" name="start_time" id="start_time" style="width:75px" onblur="check_time_picker_start_value();" /></div>
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">End Time:</div>
        <div><input type="text" class="required" name="end_time" id="end_time" style="width:75px" onblur="check_time_picker_end_value();"/></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Status:</div>
        <div>Active<input type="radio" checked="checked"  name="status" value="1"/>&nbsp;&nbsp;Inactive<input type="radio" name="status" value="0" /></div>
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
.slot_time_disabled{background:#CCCCCC !important;}
.label_u{width:160px;}
.table_view {
    display:table;
	width:1350px;
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
	height:140px;
	background:#E2E2E2;
	display:block;
	font-size:14px;
}
.rowGroup{line-height: 19px !important;}
.u_sl{width:30px;text-align:center;}
.u_name{width:100px;text-align:center}
.u_email{width:100px;text-align:center}
.u_un{width:188px;text-align:center}
.u_status{width:80px;text-align:center;}
.u_role{width:120px;}
.u_ll{width:180px;text-align:center;}
.u_ts{width:140px;text-align:center}
.u_as{width:140px;text-align:center}
.u_exs{width:300px;padding-left:5px;}
.u_ms{width:120px;text-align:center}
.u_ac{width:108px;text-align:center;}

.u_ims{width:45px;text-align:center;}
.u_nrim{width:45px;text-align:center;}
.u_inft{width:45px;text-align:center;}
.u_fte{width:45px;text-align:center;}
.u_ftewor{width:70px;text-align:center;}
.u_payng{width:45px;text-align:center;}
.u_cncl{width:60px;text-align:center;}

</style>
<div class="list_user" style="margin:0 0 10px 10px;">
<div class="table_view">
  <div class="header nav">
    <div class="cell u_sl">Sl</div>
    <div class="cell u_name">Start Time</div>
    <div class="cell u_email">End Time</div>
    <div class="cell u_status">Status</div>
    <?php if($_SESSION['user_role'] == 'administrator' || $_SESSION['user_role'] == 'viewer')
	{ ?>
    <div class="cell u_ms">Max. Students</div>
    <?php } ?>
    <div class="cell u_ts">Total Students</div>
    <div class="cell u_as">Active Students</div>
   <!-- <div class="cell u_exs">Status Wise Details</div>-->
   <div class="cell u_ims">IMS</div>
    <div class="cell u_nrim">NRIM</div>
    <div class="cell u_inft">In F.T.</div>
    <div class="cell u_ftewor">FTE w/o R</div>
    <div class="cell u_fte">FTE</div>
    <div class="cell u_payng">Paying</div>
    <div class="cell u_cncl">Cancelled</div>
    
     <?php if($_SESSION['user_role'] == 'administrator' || $_SESSION['user_role'] =='teacher' ) { ?><div class="cell u_ac">Action</div><?php } ?>
  </div>
 <div class="clear"></div>
  <?php 
  $all_slot = User::getteacherSlotDtl($teacher_id);
  
/*  echo "<pre>";
print_r($all_slot);
echo "</pre>";*/
$slot_found = false;
if(count($all_slot) > 0)
{
  for($i_user = 0;$i_user < count($all_slot);$i_user++)
  {
	//if( $all_slot[$i_user]['empty_slot'] != 1)
	 {
		$slot_found = true;
		
		//student status block data start
		
		$ini_msg = User::total_users_by_status_id_and_teacher_slots(1,$all_slot[$i_user]['id']);// 1 = Initial message sent
		$no_responseA = User::total_users_by_status_id_and_teacher_slots(2,$all_slot[$i_user]['id']); // 2 = No response to initial message after 2 days
		$in_ft = User::total_users_by_status_id_and_teacher_slots(3,$all_slot[$i_user]['id']);// 3 = In F.T.
		$no_responseB = User::total_users_by_status_id_and_teacher_slots(4,$all_slot[$i_user]['id']);// 4 = F.T. expired without response
		$f_t_expired = User::total_users_by_status_id_and_teacher_slots(6,$all_slot[$i_user]['id']);// 6 = F.T. Expired
		$paying_user = User::total_users_by_status_id_and_teacher_slots(7,$all_slot[$i_user]['id']); // 7 = paying
		$cancell = User::total_users_by_status_id_and_teacher_slots(8,$all_slot[$i_user]['id']);// 6 = Cancelled 
	 //student status block data end
	 ?>
    
    <div id="usrTr_<?php echo $all_slot[$i_user]['id'];?>" name="usrTr_<?php echo $all_slot[$i_user]['id'];?>" class="rowGroup">
    <div class="cell u_sl"><?php echo $i_user+1;?></div>
    <div class="cell u_name u_info_name_<?php echo $all_slot[$i_user]['id'];?>"><?php if($all_slot[$i_user]['empty_slot']==1) echo "No Slot";elseif($all_slot[$i_user]['coaching']==1) echo "Coaching"; else echo $all_slot[$i_user]['start_time'];?></div>
    <div class="cell u_email u_info_email_<?php echo $all_slot[$i_user]['id'];?>"><?php if($all_slot[$i_user]['empty_slot']==1) echo "No Slot";elseif($all_slot[$i_user]['coaching']==1) echo "Coaching"; else echo $all_slot[$i_user]['end_time'];?></div>
    <div class="cell u_status  u_info_status_al u_info_status_<?php echo $all_slot[$i_user]['id'];?>"><?php if( $all_slot[$i_user]['active']==1) echo "Active"; else echo "Inactive"?></div>
    <?php if($_SESSION['user_role'] == 'administrator'  || $_SESSION['user_role'] == 'viewer')
	{ ?>
    <div class="cell u_ms u_info_max_students_<?php echo $all_slot[$i_user]['id'];?>"><?php echo $all_slot[$i_user]['max_students'];?></div>
    <?php } ?>
    <div class="cell u_ts "><?php echo User::count_total_student_byslot( $all_slot[$i_user]['id']);?></div>
    <div class="cell u_as">
	<div><?php echo User::count_total_active_student_byslot($all_slot[$i_user]['id']);?></div>
   <!-- <div>Expired Students : <?php echo User::count_total_expired_student_byslot($all_slot[$i_user]['id']);?></div>-->
    </div>
    <!--<div class="cell u_exs">
    <div class="">Initial message sent: <strong><?php echo $ini_msg;?></strong></div>
     <div class="">No response to initial message after 2 days: <strong><?php echo $no_responseA;?></strong></div>
     <div class="">In F.T.: <strong><?php echo $in_ft;?></strong></div>
     <div class="">F.T. expired: <strong><?php echo $f_t_expired;?></strong></div>
      <div class="">F.T. expired without response: <strong><?php echo $no_responseB;?></strong></div>
	 <div class="">Paying: <strong><?php echo $paying_user;?></strong></div>
     <div class="">Cancelled: <strong><?php echo $cancell;?></strong></div>
	</div>-->
    <div class="cell u_ims"><strong><?php echo $ini_msg;?></strong></div>
     <div class="cell u_nrim"><strong><?php echo $no_responseA;?></strong></div>
     <div class="cell u_inft"><strong><?php echo $in_ft;?></strong></div>
      <div class="cell u_ftewor"><strong><?php echo $no_responseB;?></strong></div>
      <div class="cell u_fte"><strong><?php echo $f_t_expired;?></strong></div>
	 <div class="cell u_payng"><strong><?php echo $paying_user;?></strong></div>
     <div class="cell u_cncl"><strong><?php echo $cancell;?></strong></div>
    <?php if($_SESSION['user_role'] == 'administrator' || $_SESSION['user_role'] =='teacher') { ?> <div class="cell u_ac">
    <div style="margin-top:10px;"><img src="media/images/1389630938_edit.png" class="action_btn_img" onclick="edit_slot(<?php echo $all_slot[$i_user]['id'];?>);" /> &nbsp;&nbsp; <?php if($all_slot[$i_user]['empty_slot'] != 1) {?><img src="media/images/1389630919_cross-24.png" class="action_btn_img" onclick="delete_slot(<?php echo $all_slot[$i_user]['id'];?>);"/> <?php  } ?></div>
    <div class=""><a href="teacher_student.php?slot=<?php echo $all_slot[$i_user]['id'];?>&teacher_id=<?php echo $teacher_id?>" style="text-align:center;">Add &amp; Manage student</a></div>
    </div><?php } ?>
    </div>
     <div class="clear"></div>
	<div class="edit_single_user user_block_<?php echo $all_slot[$i_user]['id'];?>" style="display:none;">
    <div id="edit_user_frm_dtl_html_<?php echo $all_slot[$i_user]['id'];?>" ></div>
    </div>
   <div class="clear"></div>
	 <?php 
  }
  }
}
if($slot_found == false)
{
	echo 'NO record found';
}?>
</div>
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
	</script>
</head>