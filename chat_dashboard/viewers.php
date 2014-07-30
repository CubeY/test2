<?php
/*======================================================================**
**                                                                           
** Main entry point.
** All PHP (mode) calls will go through this script.  
**                                                                           
**======================================================================*/
$current_pname = 'viewers';
include 'inc/inc.php';

Auth::checkAuth();



include 'inc/header.php';
?>




<?php
$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);

$current_pname = 'viewers';

if($_SESSION['user_role'] != 'administrator') { 	echo '<h3>You are restricted for this page!</h3>';exit;} 

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
	var todo = 'edit_viewers_html';
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
	
				
				 $('#edit_usr_frm_'+uid).validate({	
		rules: {		
			email: {
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
                              return $('#edit_usr_frm_'+uid+' :input[name="email"]').val();
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
			user: {
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
                              return $('#edit_usr_frm_'+uid+' :input[name="user"]').val();
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
				 line_url:
				 {
					remote: $.validator.format("Line URL already exist.")
				 },
				 user:
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
			var pass = $('#edit_usr_frm_'+uid).find('input[name="u_pass"]').val();
			var user = $('#edit_usr_frm_'+uid).find('input[name="u_user"]').val();
			var role = $('#edit_usr_frm_'+uid).find('input[name="role"]').val();
			var status = $('#edit_usr_frm_'+uid).find('input:radio[name="status"]:checked').val();
			
		
			var todo = 'update_user_dtl';
			$.ajax({  
			type: "POST", 
			url: 'ajax/ajax_user.php', 
			data: "todo="+todo+"&uid="+uid+"&name="+name+"&email="+email+"&pass="+pass+"&user="+user+"&status="+status+"&role="+role,
			dataType: "html",

			success: function(data){
				
				$('.u_info_name_'+uid).html(name);
				$('.u_info_email_'+uid).html(email);
				$('.u_info_user_'+uid).html(user);
				if(status=='Y')
					$('.u_info_status_'+uid).html('Active');
				else
					$('.u_info_status_'+uid).html('Inactive');
				
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
function delete_user(uid,role)
{
	var u_name = $('.u_info_name_'+uid).text();
	if(confirm('Are you sure you want to DELETE '+u_name+'?') == false ) return false;
	var todo = 'delete_user';
	$.ajax({  
	type: "POST", 
	url: 'ajax/ajax_user.php', 
	data: "todo="+todo+"&uid="+uid+"&role="+role,
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
<div><a href="#" onclick="showAddNewUrsFrm()">Add New Viewers</a></div>
<div class="user_form" style="display:none;width:auto;">
<form name="add_user_frm" id="add_user_frm" action="" method="post" >
<input type="hidden" name="todo"  value="add_new_user"/>
<input type="hidden" name="role"  value="viewer"/>
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
    <!--<div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Line Url:</div>
        <div><input type="text" class="required" name="line_url" /></div>
    </div>
    <div class="clear"></div>
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
    </div>
    <div class="clear"></div>
    <div class="single_row">
        <div class="label_u">Days off:</div>
        <div class="days_off_chkbox">
        <input type="checkbox" name="day_off_1[]" value="1" /> Monday
        <input type="checkbox" name="day_off_1[]" value="2" /> Tuesday
        <input type="checkbox" name="day_off_1[]" value="3" /> Wednesday
        <input type="checkbox" name="day_off_1[]" value="4" /> Thursday
        <input type="checkbox" name="day_off_1[]" value="5" /> Friday
        </div>
    </div>-->
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
	font-size:13px;
}
.rowGroup{line-height: 19px !important;}
.u_sl{width:40px;text-align:center;}
.u_uname{width:230px;}
.u_em{width:250px;b order:1px solid red;padding-left:5px;}
.u_email{width:250px;}
.u_un{width:180px;}
.u_status{width:100px;text-align:center;}
.u_ll{width:200px;text-align:center;}
.u_ac{width:310px;text-align:center;}
</style>
<div class="list_user" >
<div class="table_view">
  <div class="header nav">
    <div class="cell u_sl">Sl</div>
    <div class="cell u_uname">Name </div>
    <div class="cell u_email">Email</div>
    <div class="cell u_un">Username</div>
    <div class="cell u_status">Status</div>
    <div class="cell u_ll">Last Login</div>
    
    <div class="cell u_ac">Action</div>
  </div>
 <div class="clear"></div>
  <?php 
  $all_user = User::all_user('viewer');
 //print_r($all_user);
 if(count($all_user) > 0)
 {
  for($i_user = 0;$i_user < count($all_user);$i_user++)
  {
	  ?>
    
    <div id="usrTr_<?php echo $all_user[$i_user]['id'];?>" name="usrTr_<?php echo $all_user[$i_user]['id'];?>" class="rowGroup">
    <div class="cell u_sl"><?php echo $i_user+1;?></div>
     <div class="cell u_uname u_info_name_<?php echo $all_user[$i_user]['id'];?>"><?php echo $all_user[$i_user]['name'];?></div>
     
   
    <div class="cell u_email u_info_email_<?php echo $all_user[$i_user]['id'];?>"><?php echo $all_user[$i_user]['email'];?></div>
    <div class="cell u_un u_info_user_<?php echo $all_user[$i_user]['id'];?>"><?php echo $all_user[$i_user]['user'];?></div>
        <div class="cell u_status u_info_status_<?php echo $all_user[$i_user]['id'];?>"><?php if($all_user[$i_user]['status']=="Y") echo "Active"; else echo "Inactive";?></div>
     
      <div class="cell u_ll"><?php echo $all_user[$i_user]['last_login'];?></div>
      <div class="cell u_ac">
      <div style="margin-top:5px;"><img src="media/images/1389630938_edit.png" class="action_btn_img"  onclick="edit_user(<?php echo $all_user[$i_user]['id'];?>);" /> &nbsp;&nbsp;<img src="media/images/1389630919_cross-24.png" class="action_btn_img" onclick="delete_user(<?php echo $all_user[$i_user]['id'];?>,'<?php echo $all_user[$i_user]['role'];?>');" /> </div>
   </div>
    </div>
     <div class="clear"></div>
	<div class="edit_single_user user_block_<?php echo $all_user[$i_user]['id'];?>" style="display:none;">
    <div id="edit_user_frm_dtl_html_<?php echo $all_user[$i_user]['id'];?>" ></div>
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