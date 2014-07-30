<?php
/*======================================================================**
**                                                                           
** Page:Login
** Created By : Bidhan
**                                                                           
**======================================================================*/
$current_pname = 'login';
include 'inc/inc.php';
include 'inc/header.php';
?>
   
<script type="text/javascript">
$(document).ready(function(e) {
    $('#loginFrm').validate();
});
</script>
    


<?php


$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);

if(isset($_POST['todo']) == 'login')
{
	$auth = Auth::login();
}
if(isset($_REQUEST['mode']) == 'logout')
{
	$auth = Auth::logOut();
}
?>
<div class="create_new_subtopic"><a href="#">Login Panel</a></div><br/>
<div class="login_frm">
<form name="loginFrm" id="loginFrm" action="" method="post" >
<input type="hidden" name="todo"  value="login"/>
<table width="100%" border="0">
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><?php if(isset($_GET['msg']) == 'error') echo '<span class="error">Invalid Username or Password</samp>';?></td>
  </tr>
  <tr>
    <td>Username</td>
    <td>:</td>
    <td><input type="text" name="email" class="required" placeholder="Enter username" /><br />

   <label for="email" class="error"></label>
    </td>
  </tr>
  <tr>
    <td>Password</td>
    <td>:</td>
    <td><input type="password" name="pass" class="required" placeholder="Enter password" /><br />
 <label for="pass" class="error"></label> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="submit" value="Log In" /></td>
  </tr>
</table>
</form>
</div>
</head>