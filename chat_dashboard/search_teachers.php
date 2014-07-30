<?php
/*======================================================================**
**                                                                           
** Main entry point.
** All PHP (mode) calls will go through this script.  
**                                                                           
**======================================================================*/
$current_pname = 'search_teachers';
include 'inc/inc.php';

Auth::checkAuth();
include 'inc/header.php';
?>

<?php
$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
?>
<script src="js/jquery.datetimepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
   
<div class="menu_bar"><?php require_once("menu.php");?></div>
    <style>
	.label_u{width:190px;}
	</style>
<script type="text/javascript">
$(document).ready(function(e) {
	
	//ShowTeacherBySearch();
	$("#pls_wait").hide();
	
	$('#tc_start_time').datetimepicker({
			datepicker:false,
			format:'H:i:00',
			step:60,
			validateOnBlur:false
		});
		
	$('#tc_end_time').datetimepicker({
			datepicker:false,
			format:'H:i:00',
			step:60,
			validateOnBlur:false
		});	
	
	
});   
function showsSearchUrsFrm()
{

	$(".search_box").toggle();
	$("#techer_srch").hide();
	//$(".user_form").hide();
}

function HideSearchUrsFrm()
{

	$(".search_box").hide();
	$("#techer_srch").show();
	
}

function showFullComment(id)
{
	$( "#dialog-comt-txt-"+id ).dialog({open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); }, modal: true, width:440,closeOnEscape: false, dialogClass: 'no-close',buttons: {
						Ok: function(){$( this ).dialog( "close" );}}});
}
 


function ShowTeacherBySearch()
{
			$('.list_user').css("opacity","0.5");
			$("#pls_wait").hide();
			//$('.search_box').hide();
			var todo = 'search_teacher_html';
			
			var name = $('#tc_name').val();
			
			var email = $('#tc_email').val();
			
			var teacher_id = $('#tc_teacher_id').val();
			
			var start_time = $('#tc_start_time').val();
			var end_time = $('#tc_end_time').val();
			
			//var slot = $("input:radio[name=tc_slot_id]:checked").val();
			//var requestd_slot_id  = $('#slot_id').val();
			$.ajax({  
			type: "POST", 
			url: 'ajax/ajax_user.php', 
			data: "todo="+todo+"&name="+name+"&email="+email+"&start_time="+start_time+"&end_time="+end_time+"&teacher_id="+teacher_id,
			dataType: "html",

			success: function(data){
				
				//alert(data1);
				$('.search_box').hide();
				$("#techer_srch").show();
				$('.list_user').css("opacity","1");
				$('.list_user').html(data);
  
			}
			});
			
			
			return false;
}

</script>
<style type="text/css">
.label_u{width:160px;}
</style>
<div class="clear"></div>
<div id="techer_srch" style="width:auto;float:left;margin:0; padding:0; display:none;"><a href="#" onclick="showsSearchUrsFrm()">Teacher Search</a></div>
<div class="search_box" style="width:auto;float:right;">
	<form name="search_frm" id="search_frm" action="" method="post">
    
    <div class="create_new_cls" >
        <div class="single_row">
            <div class="label_u">Name:</div>
            <div><input type="text" class="" name="tc_name" id="tc_name"/></div>
        </div>
        <div class="clear"></div>
        <div class="single_row">
            <div class="label_u">Email:</div>
            <div><input type="text" class="" name="tc_email"  id="tc_email"/></div>
        </div>
        
        <div class="clear"></div>
        <input type="hidden" id="tc_teacher_id" value="<?php echo $_SESSION['user_id'];?>">
        
        <div class="single_row">
        <div class="label_u">Start Time:</div>
        <div><input type="text" class="required" name="tc_start_time" id="tc_start_time" style="width:75px"/></div>
        </div>
        <div class="clear"></div>
        <div class="single_row">
            <div class="label_u">End Time:</div>
            <div><input type="text" class="required" name="tc_end_time" id="tc_end_time" style="width:75px"/></div>
        </div>
			
         <?php /*?><div class="single_row">
            <div class="label_u">Select Slot:</div>
            <div id="dv_teacher_slot_checked">
           <?php
           $slot = Student::get_teacher_slot($_SESSION['user_id']);
            if(count($slot) > 0)
            {
                echo '<ul class="slot-ul">';
                for($i=0; $i < count($slot); $i++)
                {
                    ?>
                    <li><input type="radio" name="tc_slot_id" value="<?php echo $slot[$i]['id'];?>"  /><?php if($slot[$i]['empty_slot']==1) echo "No Timeslot"; else  echo $slot[$i]['start_time'].'&nbsp;To&nbsp;'.$slot[$i]['end_time'];?></li>
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
        </div><?php */?>
       
        
        <div class="clear"></div>
         <div class="single_row">
            <div class="label_u">&nbsp;</div>
            <div><input type="button" value="Search" onclick="ShowTeacherBySearch();" />&nbsp;<input type="button" value="Cancel" onclick="HideSearchUrsFrm();"  /></div>
        </div>
        <div class="clear"></div>
    </div>
    </form>
</div>
<div class="clear"></div>

<div class="list_user" >
<div id="pls_wait">please wait...</div>
<div style="height:100px;">
</div>
</div>

