<?php
/*======================================================================**
**                                                                           
** Page:Ajax validate user email , username etc
** Created By : Bidhan
**                                                                           
**======================================================================*/
?>
<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once("../../../const.php");
//require_once(__DIR__ . "/css/style.css");
require_once(INC_PATH."common.php");
require_once(DIR_CLASSES . "class_db_this.php");

$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);



	if(isset($_REQUEST['unique_user_id']) && $_REQUEST['unique_user_id']!='')
	{
		check_unique_user_id();exit;
		
	}
	
	if(isset($_REQUEST['line_url']) && $_REQUEST['line_url']!='')
	{
		check_line_url();exit;
		
	}
	
	if(isset($_REQUEST['email']) && $_REQUEST['email']!='')
	{
		$sql="select email from user_master where email='".$_REQUEST['email']."'";
		if(isset($_REQUEST['uid']) && $_REQUEST['uid'] !='')
		$sql .=" AND id != ".$_REQUEST['uid']."";
		
		$rs = $db->doQuery($sql);
		  
		  
		$cnt=mysql_num_rows($rs);
		if($cnt > 0)
		{
			echo "false";  
		}
		else
		{
			echo "true"; 
		}
		exit;
	}
	
	
	if($_REQUEST['user']!='')
	{
		$sql="select user from user_master where user='".$_REQUEST['user']."'";
		if(isset($_REQUEST['uid']) && $_REQUEST['uid'] !='')
		$sql .=" AND id != ".$_REQUEST['uid']."";
		
		  $rs = $db->doQuery($sql);
		  
		  
		$cnt=mysql_num_rows($rs);
		if($cnt > 0)
		{
			echo "false";  
		}
		else
		{
			echo "true"; 
		}
	}
	
	
	
	function check_unique_user_id()
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);

		$sql="select unique_user_id from users_list where id='".$_REQUEST['user_id']."'";
		
		$rs = $db->doQuery($sql);
		
		$res = mysql_fetch_assoc($rs);
		
		if($_REQUEST['unique_user_id']==$res['unique_user_id'])
		echo "true";
		else
		{
			$sql="select unique_user_id from users_list where unique_user_id='".$_REQUEST['unique_user_id']."'";
			
			$rs = $db->doQuery($sql);
			  
			  
			$cnt=mysql_num_rows($rs);
			if($cnt > 0)
			{
				echo "false";  
			}
			else
			{
				echo "true"; 
			}
		}
		exit;
	}
	function check_line_url()
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		if(!empty($_REQUEST['user_id']))
		{
		$sql="select line_url from teachers where teacher_id ='".$_REQUEST['user_id']."'";
		
		$rs = $db->doQuery($sql);
		
		$res = mysql_fetch_assoc($rs);
		
		if($_REQUEST['line_url']==$res['line_url'])
		echo "true";
		else
		{
			$sql="select line_url from teachers where line_url='".$_REQUEST['line_url']."'";
			
			$rs = $db->doQuery($sql);
			  
			  
			$cnt=mysql_num_rows($rs);
			if($cnt > 0)
			{
				echo "false";  
			}
			else
			{
				echo "true"; 
			}
		}//echo "false";
		}
		else
		{
		
			$sql="select line_url from teachers where line_url='".$_REQUEST['line_url']."'";
			
			$rs = $db->doQuery($sql);
			  
			  
			$cnt=mysql_num_rows($rs);
			if($cnt > 0)
			{
				echo "false";  
			}
			else
			{
				echo "true"; 
			}
		}
		exit;
	}
	
	
?>