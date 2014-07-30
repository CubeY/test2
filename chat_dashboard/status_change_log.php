<?php
/*======================================================================**
**                                                                           
** Main entry point.
** All PHP (mode) calls will go through this script.  
**                                                                           
**======================================================================*/
$current_pname = 'change_log';
include 'inc/inc.php';

Auth::checkAuth();



include 'inc/header.php';
?>
   
<?php 
if($_SESSION['user_role'] != 'administrator' && $_SESSION['user_role'] != 'viewer') { 	echo '<h3>You are restricted for this page!</h3>';exit;} 

		//header('Location:teachers.php');


?>

<div class="menu_bar"><?php require_once("menu.php");?></div>

  <style>
	.label_u{width:190px;}
	</style>

<style>
.table_view {
    display:table;
	width:100%;
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
	height:40px;
	background:#E2E2E2;
	display:block;
	font-size:16px;
}
.u_t{width:30px;text-align:center;}
.u_n{width:170px;text-align:left;padding-left:8%;}
.u_os,.u_ns{width:300px;text-align:center;}
.u_cb{width:240px;text-align:center;}
.u_date{width:150px;text-align:center;}

</style>
<div class="list_user">
<div class="table_view">
  <div class="header nav">
    <div class="cell u_t">Sl</div>
    <div class="cell u_n">Name</div>
    <div class="cell u_os">Old status</div>
    <div class="cell u_ns">New status</div>
    <div class="cell u_cb">Changed by</div>
    <div class="cell u_date">Date</div>
   <!-- <div class="cell u_ac">Action</div>-->
  </div>
 <div class="clear"></div>
  <?php //echo '<pre>';
  $all_status_log = Status::all_status_change_log();
 //print_r($all_status_log);//die;
  $active_students = 0;
  for($i_status = 0;$i_status < count($all_status_log);$i_status++)
  {
	
	 ?>
     <div >
     <div class="cell u_t"><?php echo  $i_status+1;?></div>
    <div class="cell u_n l_info_name_"><?php echo $all_status_log[$i_status]['f_name'].'&nbsp;'.$all_status_log[$i_status]['l_name'];?> </div>
    <div class="cell u_os l_info_name"><?php echo $all_status_log[$i_status]['old_status_name'];?> </div>
    <div class="cell u_ns l_info_name_"><?php echo $all_status_log[$i_status]['new_status_name'];?> </div>
	<div class="cell u_cb l_info_name_"><?php echo $all_status_log[$i_status]['changed_by_name'];?> </div>
    <div class="cell u_date l_info_name_"><?php echo $all_status_log[$i_status]['chenged_date'];?> </div>
    
	
<!--<div class="cell u_ac"><img src="media/images/1389630938_edit.png" class="action_btn_img"  onclick="edit_status(<?php echo $all_status_log[$i_status]['id'];?>);" /> </div>
-->    </div>
     <div class="clear"></div>
  
	 <?php 
  }
  ?>
  
</div>
<hr />
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