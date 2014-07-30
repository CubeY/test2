<?php
/*======================================================================**
**                                                                           
** Page:Search Box , search all users
** Created By : Bidhan
**                                                                           
**======================================================================*/
?>
<script type="text/javascript">
$(document).ready(function(e) {
	
	ShowStudentBySearch();
	
	 $('#sf_first_response_from').datetimepicker({
			datepicker:true,
			format:'Y-m-d H:i:00',
			step:60,
			validateOnBlur:false,
			onChangeDateTime:function(selectedDate){//alert(selectedDate)
			
			selectedDate.setDate(selectedDate.getDate()+1);
			
			
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
			
			
			$('#sf_first_response_to').val(selectedDate.getFullYear()+'-'+nmm+'-'+dd+' '+hh+':'+minut+':'+ss);	
				
				
			
			
			}
		});
	$('#sf_first_response_to').datetimepicker({
			datepicker:true,
			format:'Y-m-d H:i:00',
			step:60,
			validateOnBlur:false,
		});
	$('#sf_ft_expiration_from').datetimepicker({
			datepicker:true,
			format:'Y-m-d H:i:00',
			step:60,
			validateOnBlur:false,
			onChangeDateTime:function(selectedDate){//alert(selectedDate)
			
			selectedDate.setDate(selectedDate.getDate()+1);
			
			
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
			
			
			$('#sf_ft_expiration_to').val(selectedDate.getFullYear()+'-'+nmm+'-'+dd+' '+hh+':'+minut+':'+ss);	
				
				
			
			
			}
		});
	$('#sf_ft_expiration_to').datetimepicker({
			datepicker:true,
			format:'Y-m-d H:i:00',
			step:60,
			validateOnBlur:false,
		});
		
	$('#sf_join_date_from').datetimepicker({
			datepicker:true,
			format:'Y-m-d H:i:00',
			step:60,
			validateOnBlur:false,
		});
	$('#sf_join_date_to').datetimepicker({
			datepicker:true,
			format:'Y-m-d H:i:00',
			step:60,
			validateOnBlur:false,
		});
	$('#sf_first_paid_date_from').datetimepicker({
			datepicker:true,
			format:'Y-m-d H:i:00',
			step:60,
			validateOnBlur:false,
		});
	$('#sf_first_paid_date_to').datetimepicker({
			datepicker:true,
			format:'Y-m-d H:i:00',
			step:60,
			validateOnBlur:false,
		});
	$('#sf_cancellation_date_from').datetimepicker({
			datepicker:true,
			format:'Y-m-d H:i:00',
			step:60,
			validateOnBlur:false,
		});
	$('#sf_cancellation_date_to').datetimepicker({
			datepicker:true,
			format:'Y-m-d H:i:00',
			step:60,
			validateOnBlur:false,
		});
	
	$('#sf_dob_from').datetimepicker({ 
	format: "Y-m-d",
        timepicker:false,
       yearStart:1930,
		yearEnd:2020, });
	$('#sf_dob_to').datetimepicker({ 
	format: "Y-m-d",
        timepicker:false,
       yearStart:1930,
		yearEnd:2020,
	});	
	
	$('#sf_paid_through_from').datetimepicker({ 
		datepicker:true,
		format:'Y-m-d H:i:00',
		step:60,
		validateOnBlur:false,
	 });
	$('#sf_paid_through_to').datetimepicker({ 
		datepicker:true,
		format:'Y-m-d H:i:00',
		step:60,
		validateOnBlur:false,
	 });
		
		
		
		
    //$('#first_response').datepicker({ dateFormat: "yy-mm-dd" });
	//$('#ft_expiration').datepicker({ dateFormat: "yy-mm-dd" });
	
});   

function showFullComment(id)
{
	$( "#dialog-comt-txt-"+id ).dialog({open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); }, modal: true, width:500,closeOnEscape: false, dialogClass: 'no-close',buttons: {
						Ok: function(){$( this ).dialog( "close" );}}});
}
function statusChangeLog(id)
{	
	var todo = "change_status_log_user";
		$.ajax({  
			type: "POST", 
			url: 'ajax/ajax_student.php', 
			data: "todo="+todo+"&uid="+id,
			dataType: "html",

			success: function(data){ 
			
				$( "#dialog-comt-txt-status-"+id ).html(data);
			}
		}); 
	
	$( "#dialog-comt-txt-status-"+id ).dialog({open: function(event, ui) 
	{ 
		$(".ui-dialog-titlebar-close").hide();
	}, modal: true, width:800,closeOnEscape: false, dialogClass: 'no-close',buttons: {
						Ok: function(){$( this ).dialog( "close" );}}});
}
function showsSearchUrsFrm()
{

	$(".search_box").toggle();
	$(".user_form").hide();
}

function HideSearchUrsFrm()
{

	$(".search_box").hide();
}

function getTeacherSlotSearch()
{
	var teacher_id = $('select#sf_teacher_id').val();
	
	var todo = 'get_teacher_slot_html2';
	$.ajax({  
	type: "POST", 
	url: 'ajax/ajax_user.php', 
	data: "todo="+todo+"&teacher_id="+teacher_id,
	dataType: "html",

	success: function(data){
	$("#dv_teacher_slot_ajax2").html(data);
	
	}
});
}
 


function ShowStudentBySearch(form_source)
{
			if(form_source == 'form_source')
			{
				$('#page_num').val(1);
			}
			$('.list_user').css("opacity","0.5");
			//$('.search_box').hide();
			var todo = 'search_student_html';
			
			var f_name = $('#sf_f_name').val();
			var l_name = $('#sf_l_name').val();
			var english_name = $('#sf_english_name').val();
			
			var email = $('#sf_email').val();
			var pay_email = $('#sf_pay_email').val();
			var line_id = $('#sf_line_id').val();
			
			var unique_user_id = $('#sf_unique_user_id').val();
			//var created_on = $('#search_frm').val();
			var first_response_from = $('#sf_first_response_from').val();
			var first_response_to = $('#sf_first_response_to').val();
			var ft_expiration_from = $('#sf_ft_expiration_from').val();
			var ft_expiration_to = $('#sf_ft_expiration_to').val();
			var join_date_from = $('#sf_join_date_from').val();
			var join_date_to = $('#sf_join_date_to').val();
			var first_paid_date_from = $('#sf_first_paid_date_from').val();
			var first_paid_date_to = $('#sf_first_paid_date_to').val();
			var paid_through_from = $('#sf_paid_through_from').val();
			var paid_through_to = $('#sf_paid_through_to').val();
			var cancellation_date_from = $('#sf_cancellation_date_from').val();
			var cancellation_date_to = $('#sf_cancellation_date_to').val();
			
			var teacher_id = $('select#sf_teacher_id').val();
			if(teacher_id=="" || teacher_id=="undefined" || teacher_id==null )
			{
				teacher_id = $('#sf_an_teacher_id').val();
			}
			var slot = $("input:radio[name=sf_slot_id]:checked").val();
			var plan = $("#sf_plan :selected").val();
			var payment_method = $("#sf_div_payment_method input[type='radio']:checked").val();
			var engagement_level = $("#sf_div_engagement_level input[type='radio']:checked").val();
			var life_status = $('#sf_life_status :selected').val();
			var funnel = $('#sf_funnel :selected').val();
			var status = $('#sf_status :selected').val();
			var payment_info_sent = $('#sf_payment_info :selected').val();
			var requestd_slot_id  = $('#slot_id').val();
			
			var gender = $('#sf_gender :selected').val();
			var dob_from = $('#sf_dob_from').val();
			var dob_to = $('#sf_dob_to').val();
			var age_from = $('#sf_age_from').val();
			var age_to = $('#sf_age_to').val();
			
			var page_num = $('#page_num').val();
			var order_by = $('#order_by').val();
			var order_by_col = $('#order_by_col').val();
			var show_expired = $('#show_expired').val();
			
			$.ajax({  
			type: "POST", 
			url: 'ajax/ajax_student.php', 
			data: "todo="+todo+"&f_name="+f_name+"&l_name="+l_name+"&english_name="+english_name+"&email="+email+"&pay_email="+pay_email+"&line_id="+line_id+"&unique_user_id="+unique_user_id+"&first_response_from="+first_response_from+"&first_response_to="+first_response_to+"&ft_expiration_from="+ft_expiration_from+"&ft_expiration_to="+ft_expiration_to+"&join_date_from="+join_date_from+"&join_date_to="+join_date_to+"&first_paid_date_from="+first_paid_date_from+"&first_paid_date_to="+first_paid_date_to+"&paid_through_from="+paid_through_from+"&paid_through_to="+paid_through_to+"&cancellation_date_from="+cancellation_date_from+"&cancellation_date_to="+cancellation_date_to+"&slot="+slot+"&plan="+plan+"&payment_method="+payment_method+"&payment_info_sent="+payment_info_sent+"&status="+status+"&teacher_id="+teacher_id+"&requestd_slot_id="+requestd_slot_id+"&page_num="+page_num+"&order_by_col="+order_by_col+"&order_by="+order_by+"&show_expired="+show_expired+"&engagement_level="+engagement_level+"&life_status="+life_status+"&gender="+gender+"&age_from="+age_from+"&age_to="+age_to+"&dob_from="+dob_from+"&dob_to="+dob_to+"&funnel="+funnel,
			dataType: "html",

			success: function(data){
				
				//alert(data1);
				$('.list_user').css("opacity","1");
				$('.list_user').html(data);
				if(order_by == 'ASC')
				{
					$('.'+order_by_col+'_sort').addClass('desc');
				}
				else
				{
					$('.'+order_by_col+'_sort').addClass('asc');
				}
				if(show_expired==0)
				{
					$('#show_hide_expired_student').html('Show Expired Users');
					$('#show_hide_expired_student').css('color','#FFF');
				}
				else
				{
					$('#show_hide_expired_student').html('Hide expired student');
					$('#show_hide_expired_student').css('color','#C00');
				}
				
				// Sticky nay start 
				
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
			// Sticky nay end  
			
  
			}
			});
			
			
			return false;
}
function clearFld(f)
{
	if(f == 'response')
	{
		$('#sf_first_response_from').val('');
		$('#sf_first_response_to').val('');
	}
	if(f == 'expiration')
	{
		$('#sf_ft_expiration_from').val('');
		$('#sf_ft_expiration_to').val('');
	}
	if(f == 'joindate')
	{
		$('#sf_join_date_from').val('');
		$('#sf_join_date_to').val('');
	}
	if(f == 'first_paid')
	{
		$('#sf_first_paid_date_from').val('');
		$('#sf_first_paid_date_to').val('');
	}
	if(f == 'cancellation')
	{
		$('#sf_cancellation_date_from').val('');
		$('#sf_cancellation_date_to').val('');
	}
	if(f == 'dob')
	{
		$('#sf_dob_from').val('');
		$('#sf_dob_to').val('');
	}
	
}
function disPage(NO)
{
	$('#page_num').val(NO);
	ShowStudentBySearch("");
}
function orderBY(col)
{
	$('#order_by_col').val(col);
	var order_by= $('#order_by').val();
	if(order_by == 'ASC')
	{
		$('#order_by').val('DESC');
		$('.created_on_sort').addClass('desc');
	}
	else
	{
		$('#order_by').val('ASC');
		$('.created_on_sort').addClass('asc');
	}
	ShowStudentBySearch("");
}

function checkstudentaddpossible()
{
	var check_slot_id = $("input:radio[name=slot_id]:checked").val();
	var todo = "check_student_add";
	if(check_slot_id=="" || check_slot_id=="undefined" || check_slot_id==null)
	{
		return false;
	}
	else
	{
		$.ajax({  
				type: "POST", 
				url: 'ajax/ajax_student.php', 
				data: "todo="+todo+"&slot_id="+check_slot_id,
				dataType: "html",
	
				success: function(data){
					if(data=="success")
					{
						document.getElementById("add_user_frm").submit();
						return true;
						//$("#add_user_frm").submit(); 
					}
					else
					{
						alert("Student can not be added to this slot. \n\nMaximum Students Limit for this Slot Exceed.");
						return false;
					}
					
				}
			});
	}
	return false;
	
}
</script>
<style type="text/css">
.label_u{width:160px;}
</style>
<input type="hidden" value='1' name="page_num" id="page_num"  />
<input type="hidden" value='ASC' name="order_by" id="order_by"  />
<input type="hidden" value='' name="order_by_col" id="order_by_col"  />
<input type="hidden" name="show_expired"  id="show_expired" value='1'/>

<div class="clear"></div>
<div style="width:auto;float:left;margin:0; padding:0;"><a href="#" onclick="showsSearchUrsFrm()">Student Search</a></div>
<div class="search_box" style="display:none;width:auto;float:right;">
	<form name="search_frm" id="search_frm" action="" method="post">
    
    <div class="create_new_cls" >
        <div class="single_row">
            <div class="label_u">First Name:</div>
            <div><input type="text" class="" name="sf_f_name" id="sf_f_name"/></div>
        </div>
        <div class="single_row">
            <div class="label_u">Last name:</div>
            <div><input type="text" class="" name="sf_l_name"  id="sf_l_name"/></div>
        </div>
        <div class="single_row">
            <div class="label_u">English name:</div>
            <div><input type="text" class="" name="sf_english_name"  id="sf_english_name"/></div>
        </div>
        <div class="clear"></div>
        <div class="single_row">
            <div class="label_u">Contact Email:</div>
            <div><input type="text" class="" name="sf_email"  id="sf_email"/></div>
        </div>
        <div class="single_row">
            <div class="label_u">Paypal Email:</div>
            <div><input type="text" class="" name="sf_pay_email" id="sf_pay_email" /></div>
        </div>
        <div class="single_row">
            <div class="label_u">Line ID:</div>
            <div><input type="text" class="" name="sf_line_id" id="sf_line_id" /></div>
        </div>
        <div class="single_row">
            <div class="label_u">Unique User ID:</div>
            <div><input type="text" class="" name="sf_unique_user_id" id="sf_unique_user_id"  /></div>
        </div>
        <div class="single_row">
            <div class="label_u">Plan:</div>
            <!--<div id="sf_div_plan"><input type="radio" name="sf_plan" value="all_week"/>All week &nbsp;<input type="radio" name="sf_plan" value="weekend" />Weekend &nbsp;<input type="radio" name="sf_plan" value="midweek" />Midweek</div>-->
            <div id="sf_div_plan"><?php echo Plan::populate_plans_select_option('sf_plan','sf_plan',false);?></div>
        </div>
        <div class="clear"></div>
        <?php if(($_SESSION['user_role'] == 'administrator' || $_SESSION['user_role'] == 'viewer' ) && $avoid_teacher_selection !="true")
    { ?>
        <div class="single_row" >
            <div class="label_u">Select Teacher:</div>
            <div>
            <select id="sf_teacher_id" name="sf_teacher_id" onchange="getTeacherSlotSearch();" >
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
        <div class="single_row" >
            <div class="label_u">Select Slot:</div>
            <div id="dv_teacher_slot_ajax2">Select teacher first</div>
        </div>
        <?php } else{
			if($_SESSION['user_role'] == 'teacher')
			{
			 ?>
             <input type="hidden" id="sf_an_teacher_id" value="<?php echo $_SESSION['user_id'];?>">
             <?php
			}
			else
			echo '<input type="hidden" id="sf_an_teacher_id" value="'.$_REQUEST['teacher_id'].'">';
			?>
         <div class="single_row">
            <div class="label_u">Select Slot:</div>
            <div id="dv_teacher_slot_checked">
           <?php
           $slot = Student::get_teacher_slot($teacher_id);
            if(count($slot) > 0)
            {
                echo '<ul class="slot-ul">';
                for($i=0; $i < count($slot); $i++)
                {
                    ?>
                    <li><input type="radio" name="sf_slot_id" value="<?php echo $slot[$i]['id'];?>"  /><?php if($slot[$i]['empty_slot']==1) echo "No Timeslot"; elseif($slot[$i]['coaching']==1) echo "Coaching"; else  echo $slot[$i]['start_time'].'&nbsp;To&nbsp;'.$slot[$i]['end_time'];?></li>
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
         <div class="single_row">
            <div class="label_u">Join Date:</div>
            <div><span><input type="text" class="" name="sf_join_date_from"  id="sf_join_date_from"/><span>&nbsp;&nbsp;TO&nbsp;&nbsp;<span><input type="text" class="" name="sf_join_date_to"  id="sf_join_date_to"/></span><span onclick="clearFld('joindate');" class="clearFld">Clear</span></div>
        </div>
        <div class="single_row">
            <div class="label_u">First Paid Date:</div>
            <div><span><input type="text" class="" name="sf_first_paid_date_from"  id="sf_first_paid_date_from"/><span>&nbsp;&nbsp;TO&nbsp;&nbsp;<span><input type="text" class="" name="sf_first_paid_date_to"  id="sf_first_paid_date_to"/></span><span onclick="clearFld('first_paid');" class="clearFld">Clear</span></div>
        </div>
        <div class="clear"></div>
         <div class="single_row">
            <div class="label_u">First response:</div>
            <div><span><input type="text" class="" name="sf_first_response_from"  id="sf_first_response_from"/><span>&nbsp;&nbsp;TO&nbsp;&nbsp;<span><input type="text" class="" name="sf_first_response_to"  id="sf_first_response_to"/></span><span onclick="clearFld('response');" class="clearFld">Clear</span></div>
        </div>
        <div class="single_row">
            <div class="label_u">Last day of F.T.:</div>
            <div><span><input type="text" class="" name="sf_ft_expiration_from"  id="sf_ft_expiration_from"/><span>&nbsp;&nbsp;TO&nbsp;&nbsp;<span><input type="text" class="" name="sf_ft_expiration_to"  id="sf_ft_expiration_to"/></span><span onclick="clearFld('expiration');" class="clearFld">Clear</span></div>
        </div>
        <div class="clear"></div>
         <!--<div class="single_row">
            <div class="label_u">Status:</div>
            <div>Active<input type="radio" checked="checked"  name="status" value="Y"/>&nbsp;&nbsp;Inactive<input type="radio" name="status" value="N" /></div>
        </div>-->
        <div class="single_row">
            <div class="label_u">Status:</div>
            <div>
            <select id="sf_status" name="sf_status">
            <option value="0">Select Status</option>
             <?php
         $all_status = User::all_status();
     // print_r($all_user);
      for($i_user = 0;$i_user < count($all_status);$i_user++)
      {
          ?>
         <option value="<?php echo $all_status[$i_user]['id'];?>"> <?php echo $all_status[$i_user]['name'];?></option>
          <?php
      }
        ?>
            </select>
            </div>
        </div>
        <div class="single_row"  style="margin-left:80px;">
            <div class="label_u">Payment method:</div>
            <div id="sf_div_payment_method"><input type="radio" name="sf_payment_method" value="Paypal" />Paypal &nbsp;<input type="radio" name="sf_payment_method" value="Braintree" />Braintree</div>
        </div>
        <div class="clear"></div>
        <div class="single_row">
            <div class="label_u">Payment Info:</div>
            <div id="sf_div_payment_info">
            <select id="sf_payment_info" name="sf_payment_info">
            <option value="">Select Payment Info Sent / Not Sent</option>
            <option value="1">Payment Info Sent</option>
            <option value="0">Payment Info Not Sent</option>
            </select>
            </div>
        </div>
        <div class="single_row" style="margin-left:129px;">
            <div class="label_u">Cancellation Date:</div>
            <div><span><input type="text" class="" name="sf_cancellation_date_from"  id="sf_cancellation_date_from"/><span>&nbsp;&nbsp;TO&nbsp;&nbsp;<span><input type="text" class="" name="sf_cancellation_date_to"  id="sf_cancellation_date_to"/></span><span onclick="clearFld('cancellation');" class="clearFld">Clear</span></div>
        </div>
        <div class="clear"></div>
         <div class="single_row">
        <div class="label_u">Engagement Level:</div>
        <div id="sf_div_engagement_level"><input type="radio" name="sf_engagement_level" value="0"/>Unknown &nbsp;<input type="radio" name="sf_engagement_level" value="1"/>Low &nbsp;<input type="radio" name="sf_engagement_level" value="5" />Medium &nbsp;<input type="radio" name="sf_engagement_level" value="10" />High</div>
    </div>
    <div class="single_row" style="margin-left:205px;">
        <div class="label_u">Life Status:</div>
        <div>
        <select id="sf_life_status" name="sf_life_status">
        	<option value="">Select Life Status</option>
        	<option value="unknown">Unknown</option>
            <option value="high_school">High School</option>
            <option value="college">College</option>
            <option value="working">Working</option>
        </select>
        </div>
    </div>
     <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Gender:</div>
        <div>
        <select id="sf_gender" name="sf_gender">
            <option value="">Select</option>
            <option value="M">Male</option>
            <option value="F">Female</option>
        </select>
        </div>
    </div>
     <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Paid Through:</div>
        <div><input type="text" name="sf_paid_through_from" id="sf_paid_through_from"/>To<input type="text" name="sf_paid_through_to" id="sf_paid_through_to" /><span onclick="clearFld('paid_through');" class="clearFld">Clear</span></div>
    </div>
   <div class="single_row" >
            <div class="label_u" style="margin: 7px 39px;width: 66px;">DOB:</div>
            <div><input type="text" style="width:100px;" name="sf_dob_from" id="sf_dob_from"/>To<input type="text" name="sf_dob_to" id="sf_dob_to" style="width:100px;"/><span onclick="clearFld('dob');" class="clearFld">Clear</span>&nbsp;&nbsp; Or Age:<input type="text" style="width:50px;" name="sf_age_from" id="sf_age_from"/>To<input type="text" name="sf_age_to" id="sf_age_to" style="width:50px;"/> 
            </div>
            
        </div>
         <!--<div class="clear"></div>
         <div class="single_row">
            <div class="label_u">Memo / Comment:</div>
            <div><textarea name="sf_comment" id="sf_comment" style="width:300px;"></textarea></div>
        </div>-->
        <div class="clear"></div>
       
     <div class="single_row">
        <div class="label_u">Funnel:</div>
        <div>
        <select id="sf_funnel" name="sf_funnel">
            <option value="">Select</option>
			<?php 
			$funnels_array = Student::all_funnels();
            for($fnl_i=1;$fnl_i <= count($funnels_array);$fnl_i++)
            {
                echo '<option value="'.$fnl_i.'">'.$funnels_array[$fnl_i].'</option>';
            }
            ?>  
        </select>
        </div>
    </div>
     <div class="clear"></div>
         <div class="single_row">
            <div class="label_u">&nbsp;</div>
            <div><input type="button" value="Search" onclick="ShowStudentBySearch('form_source');" />&nbsp;<input type="button" value="Cancel" onclick="HideSearchUrsFrm();"  /></div>
        </div>
        <div class="clear"></div>
    </div>
    </form>
</div>
<div class="clear"></div>
