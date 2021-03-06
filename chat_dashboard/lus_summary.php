<?php
/*======================================================================**
**                                                                           
** Main entry point.
** All PHP (mode) calls will go through this script.  
**                                                                           
**======================================================================*/
$current_pname = 'lus_summary';
include 'inc/inc.php';

Auth::checkAuth();



include 'inc/header.php';
?>
   
<?php 
if($_SESSION['user_role'] != 'administrator' && $_SESSION['user_role'] != 'viewer') { 	echo '<h3>You are restricted for this page!</h3>';exit;} 

		//header('Location:teachers.php');


?>
<script type="text/javascript">
$(document).ready(function(e) {
	getData();
	$('#date_from').datepicker({ dateFormat: "yy-mm-dd" });
	$('#date_to').datepicker({ dateFormat: "yy-mm-dd" });
	
});
function getData()
{
	var teacher_id = $('#teacher_id').val();
	var date_to = $('#date_to').val();
	var date_from = $('#date_from').val();
	var slot = '';
		$.ajax({  
			type: "POST", 
			url: 'ajax/ajax_lus.php', 
			data: "todo=getSummaryData&teacher_id="+teacher_id+"&date_to="+date_to+"&date_from="+date_from+"&slot="+slot,
			dataType: "html",
			success: function(data){$('.list_data').html(data);		
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
			});		}
			});
}
</script>
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
	height:30px;
	background:#E2E2E2;
	display:block;
	font-size:16px;
}


</style>
<div class="filter_div">
<input type="hidden" id="u_id" value="" />
<input type="hidden" id="sub_topic_id" value="" />

<ul>
<?php /*?><li>Teacher:
<?php
$all_user = User::all_user('teacher');
//print_r($all_user);
echo '<select  name="teacher_id" id="teacher_id">';
echo '<option value="">All Teachers</option>';
foreach($all_user as $e)
{
	echo '<option value="'.$e['id'].'">'.$e['name'].'</option>';
}echo '</select>';
?>
</li><?php */?>
<li>Date From:<input type="text" name="date_from" id="date_from" value="" /></li>
<li>Date To:<input type="text" name="date_to" id="date_to" value="" /></li>
<li><input type="button" name="search" value="Search" class="btn_blk" onclick="getData();" /></li>
</ul>
</div>
<div class="list_data" >
Please wait...
</div>
<?php

?>
<script src="./js/jquery-scrolltofixed-min.js" type="text/javascript"></script>
</head>	