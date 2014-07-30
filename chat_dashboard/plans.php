<?php
/*======================================================================**
**                                                                           
** Page:all plan
** Created by : Bihdan
**                                                                           
**======================================================================*/
$current_pname = 'plan';
include 'inc/inc.php';

Auth::checkAuth();

include 'inc/header.php';

$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);


if($_SESSION['user_role'] != 'administrator') { 	echo '<h3>You are restricted for this page!</h3>';exit;} 


if(isset($_POST['todo']) == 'add_new_plan')
{
	Plan::add_new_plan();
}


?>

<div class="menu_bar"><?php require_once("menu.php");?></div>
<div><a href="#" onclick="showAddNewTopicFrm()">Add New Plan</a></div>
<div class="add_form" style="display:none;">
<form name="add_plan_frm" id="add_plan_frm" action="" method="post" >
<input type="hidden" name="todo"  value="add_new_plan"/>
<div class="create_new_cls" >
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Plan Name:</div>
        <div><input type="text" class="required" name="name" /></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Plan Price:</div>
        <div><input type="text" class="required number" name="price" /></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">&nbsp;</div>
        <div><input type="submit" value="Save"  />&nbsp;<input type="button" value="Cancel" onclick="HideAddNewTopicFrm();"  /></div>
    </div>
    <div class="clear"></div>
</div>
</form>
</div>
<script type="text/javascript">
 $(document).ready(function(e) {
    $('#add_plan_frm').validate({	
		rules: {		
			name: {
				required: true,
			},
			price: {
				required: true,
			},
			
		},
	});
});
function edit_plan_price(pid)
{
	$(".block_btn_edit_"+pid).hide();
	$('.block_btn_action_'+pid).show();
	$(".block_lebel_price_"+pid).hide();
	$(".block_lebel_name_"+pid).hide();
	$('.input_block_'+pid).show();
	$('.input_block_name_'+pid).show();
	
	
	$("#edit_plan_frm_dtl_html_"+pid).html('Please wait...');
	var todo = 'edit_viewers_html';
				
		
}

function save_changes(pid)
		{
			var new_price = $('#new_price_'+pid).val();
			var new_name = $('#new_name_'+pid).val();
			var todo = 'update_plan_price';
			$.ajax({  
			type: "POST", 
			url: 'ajax/ajax_plan.php', 
			data: "todo="+todo+"&pid="+pid+"&new_price="+new_price+"&new_name="+new_name,
			dataType: "html",

			success: function(data){
				
				revert_html(pid);
  $('.block_lebel_price_'+pid).html(new_price);
  $('.block_lebel_name_'+pid).html(new_name);
			}
			});
			
			
			return false;
		}

function revert_html(pid)
{

	$(".block_btn_edit_"+pid).show();
	$('.block_btn_action_'+pid).hide();
	$(".block_lebel_price_"+pid).show();
	$(".block_lebel_name_"+pid).show();
	$('.input_block_'+pid).hide();
	$('.input_block_name_'+pid).hide();
}

   function showAddNewTopicFrm()
{

	$(".add_form").show();
}

function HideAddNewTopicFrm()
{

	$(".add_form").hide();
}
function HideTopiceditBlock(id)
{
	$("#edit_topic_frm_dtl_html_"+id).html('');
	$(".topic_block_"+id).hide();
}
function delete_plan(pid)
{
	var u_name = $('.u_info_name_'+pid).text();
	if(confirm('Are you sure you want to delete Plan '+u_name+'?') == false ) return false;
	var todo = 'delete_plan';
	$.ajax({  
	type: "POST", 
	url: 'ajax/ajax_plan.php', 
	data: "todo="+todo+"&pid="+pid,
	dataType: "html",

	success: function(data){
	$("#usrTr_"+pid).remove();
	$(".user_block_"+pid).remove();
	
	}
});
}

 </script>

<div class="clear"></div>

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
.header .cell{height:30px;background:#666;color:#FFF;float:left;}
.cell {
    dis play:table-cell;
    width:auto;
	m ax-width:100px;
	backg round-color:#069;
	ma rgin:2px;
	border:1px solid #FFFFFF;
	float:left;
	height:35px;
	background:#E2E2E2;
	display:block;
	font-size:17px;
	text-align:center;
	color:#1d1d1d;
	line-height:1.5;
}
.rowGroup{line-height: 19px !important;}
.u_sl{width:40px;text-align:center;}
.u_uname{width:430px;}
.u_em{width:250px;b order:1px solid red;padding-left:5px;}
.u_price{width:350px;}
.u_status{width:100px;text-align:center;}
.u_ll{width:200px;text-align:center;}
.u_ac{width:510px;text-align:center;}
</style>
<div class="list_plan" >
<div class="table_view">
  <div class="header nav">
    <div class="cell u_sl">Sl</div>
    <div class="cell u_uname">Name </div>
    <div class="cell u_price">Price</div>
    <div class="cell u_ac">Action</div>
  </div>
 <div class="clear"></div>
  <?php 
  $all_plan = Plan::all_plans();
 //print_r($all_plan);
 if(count($all_plan) > 0)
 {
  for($i_plan = 0;$i_plan < count($all_plan);$i_plan++)
  {
	  ?>
    
    <div id="usrTr_<?php echo $all_plan[$i_plan]['id'];?>" name="usrTr_<?php echo $all_plan[$i_plan]['id'];?>" class="rowGroup">
    <div class="cell u_sl"><?php echo $i_plan+1;?></div>
     <div class="cell u_uname u_info_name_<?php echo $all_plan[$i_plan]['id'];?>">
	 
	 
     	<span class="block_lebel_name_<?php echo $all_plan[$i_plan]['id'];?>"><?php echo $all_plan[$i_plan]['display_name'];?></span>
    <span class="input_block_name_<?php echo $all_plan[$i_plan]['id'];?>" style="display:none;"><input type="text" style="width:280px;" class="input1" value="<?php echo $all_plan[$i_plan]['display_name'];?>" id="new_name_<?php echo $all_plan[$i_plan]['id'];?>" /></span>


     </div>
     
   
    <div class="cell u_price">
	
	<span class="block_lebel_price_<?php echo $all_plan[$i_plan]['id'];?>"><?php echo $all_plan[$i_plan]['price'];?></span>
    <span class="input_block_<?php echo $all_plan[$i_plan]['id'];?>" style="display:none;"><input type="text"  class="input1" value="<?php echo $all_plan[$i_plan]['price'];?>" id="new_price_<?php echo $all_plan[$i_plan]['id'];?>" /></span>
    </div>
     
      <div class="cell u_ac">
      <div style="margin-top:5px;">
      	<span class="block_btn_edit_<?php echo $all_plan[$i_plan]['id'];?>"><img src="media/images/1389630938_edit.png" class="action_btn_img"  onclick="edit_plan_price(<?php echo $all_plan[$i_plan]['id'];?>);" />&nbsp;&nbsp;<img src="media/images/1389630919_cross-24.png" class="action_btn_img" onclick="delete_plan(<?php echo $all_plan[$i_plan]['id'];?>);" /> </span>
        <span class="block_btn_action_<?php echo $all_plan[$i_plan]['id'];?>" style="display:none;">
        	<button onclick="save_changes(<?php echo $all_plan[$i_plan]['id'];?>)" class="save_small">Save</button>
            <button onclick="revert_html(<?php echo $all_plan[$i_plan]['id'];?>);" class="cancel_small">Cancel</button>
        </span>
      </div>
   </div>
    </div>
     <div class="clear"></div>
	<div class="edit_single_plan plan_block_<?php echo $all_plan[$i_plan]['id'];?>" style="display:none;">
    <div id="edit_plan_frm_dtl_html_<?php echo $all_plan[$i_plan]['id'];?>" ></div>
    </div>
   <div class="clear"></div>
	 <?php 
  }
  }
 else {
	 echo '<h2>No Record</h2>';
 }
  ?>
  </div>
</div>
<div style="height:100px;">
</div>
</head>