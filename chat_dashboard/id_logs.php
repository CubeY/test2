<?php
/*======================================================================**
**                                                                           
** Main entry point.
** All PHP (mode) calls will go through this script.  
**                                                                           
**======================================================================*/
$current_pname = 'id_logs';
include 'inc/inc.php';

Auth::checkAuth();



include 'inc/header.php';
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
.u_status{width:20%;text-align:center;}
.u_t{width:10%;text-align:center;}
.u_ac{width:15%;text-align:center;}
</style>
<div class="list_user">
<div class="table_view">
  <div class="header">
    <div class="cell u_t">Sl</div>
    <div class="cell u_status">Student Name</div>
    <div class="cell u_status">Teacher Name</div>
    <div class="cell u_ac">Old Unique User Id</div>
    <div class="cell u_ac">New Unique User Id</div>
    <div class="cell u_ac">Date</div>
  </div>
 <div class="clear"></div>
  <?php 
  $all_log = Student::all_changed_unique_id_log();
  //print_r($all_status);
  if(count($all_log) > 0)
  {
  $i_user =0;
  for($i_log = 0;$i_log < count($all_log);$i_log++)
  {
	 $i_user++;
	 ?>
     <div id="proTr_<?php echo $all_log[$i_log]['id'];?>">
     <div class="cell u_t"><?php echo  $i_user;?></div>
    <div class="cell u_status"><?php echo $all_log[$i_log]['f_name']." ".$all_log[$i_log]['l_name'] ;?> </div>
    <div class="cell u_status"><?php User::getTeacherNameBySlotId($all_log[$i_log]['slot']);?> </div>
    <div class="cell u_ac"><?php echo $all_log[$i_log]['old_id'];?></div>
    <div class="cell u_ac"><?php echo $all_log[$i_log]['new_id'];?></div>
    <div class="cell u_ac"><?php echo $all_log[$i_log]['date'];?></div>
    </div>
     <div class="clear"></div>
  
	 <?php 
  }
  }
  else
  {
	  echo '<h3>No record</h3>';
  }
  ?>
  
  
</div>
</div>
<?php

?>
</head>	