<?php

/*===========================================================================**
** Class : User
** Created By ; Bidhan
** Utility functions to access MySQL database
** 
**===========================================================================*/
error_reporting(E_ALL & ~E_STRICT);
    
 class Plan {
    // private
    private $initialized = false;
    private $conn;
    private $debugOn = false;
    private function __construct($server, $username, $password, $dbName) {
        $this->conn = @mysql_connect($server, $username, $password, true) or die(mysql_error());
        // Make sure that everything is UTF8 encoded so we don't have any character gibberish in the DB
        $charsetSQL = "SET NAMES utf8;set character_set_server = utf8;";
        mysql_set_charset("UTF8", $this->conn);
        mysql_query($charsetSQL, $this->conn);

        @mysql_select_db($dbName,$this->conn)or die(mysql_error());
        $this->initialized = true;
		
    }
    private static function escapeForSQL($str) {
        return mysql_real_escape_string($str);
    }
    
	
	public function add_new_plan()
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);  
		
		$price = $_POST['price'];
		$display_name = trim($_POST['name']);
		$name =  trim(strtolower(str_replace(' ','_',$_POST['name'])));
	
		$sql= "INSERT INTO all_plans (`name`, `display_name`, `price`, `last_modified_by`, `last_modified`) VALUES('$name', '$display_name', '$price', '".Plan::logedin_username()."','".LAST_MODIFIED_DATE."' )";
		$q = $db->doQuery($sql);
		if($q)
		{
			header('location:plans.php?msg=success');exit;
		}
		else
		{
			header('location:plans.php?msg=error');exit;
		}
	}
	
	public function all_plans()
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);

		$sql= "SELECT * FROM  all_plans";
		$q = $db->doQuery($sql);
		
		while($row = mysql_fetch_assoc($q)) {  
			$result[] = $row;
           
        }
		return $result;
		
	}
	
	
	
	public function edit_plan_html()
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);

		  
		$id = $_POST['id'];
		$sql= "SELECT * FROM  all_plans WHERE id = ".$id."";
		
		$q = $db->doQuery($sql);
		$row = mysql_fetch_assoc($q);
		
		//print_r($row);
		?>
		<form name="edit_plan_frm_<?php echo $row['id'];?>" id="edit_plan_frm_<?php echo $row['id'];?>" action="" method="post" >
        <input type="hidden" name="todo"  value="update_user_details"/>
         <div class="create_new_cls" >
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Topic Name:</div>
        <div><input type="text" class="required" name="u_name" value="<?php echo $row['name'];?>" /></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">&nbsp;</div>
        <div><input type="submit" value="Save"  />&nbsp;<input type="button" value="Cancel" onClick="HideTopiceditBlock(<?php echo $row['id'];?>)" /></div>
    </div>
    <div class="clear"></div>
</div>
        </form>
		<?php
	}
	
	
	public function update_plan_price()
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);

		  
		$id = $_POST['pid'];
		
		$new_price = $_POST['new_price'];
		$display_name = trim($_POST['new_name']);
		$new_name = trim(strtolower(str_replace(' ','_',$_POST['new_name'])));
		$sql= "UPDATE all_plans  SET `name` = '".$new_name."' ,`display_name` = '".$display_name."', `price`= '".$new_price."' , last_modified_by = '".Plan::logedin_username()."',last_modified = '".LAST_MODIFIED_DATE."' WHERE id = ".$id."";
		$q = $db->doQuery($sql);
		if($q)
		{
			echo 'success';
		}
		else
		{
			echo 'error';
		}
		exit;
		
	}
	
	private static function logedin_username() {
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		return $_SESSION[LAST_MODIFIED_BY];
    }
	
	public function delete_plan()
	{
	   $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);

	     
		$id = $_POST['pid'];
		echo $sql= "DELETE FROM all_plans WHERE id = ".$id."";
		$q = $db->doQuery($sql);
		if($q)
		{
			echo 'success';
		}
		else
		{
			echo 'error';
		}
		exit;
	}
	
	
	public function populate_plans_select_option($name , $id , $selected = false)
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$sql= "SELECT id,display_name FROM  all_plans";
		$q = $db->doQuery($sql);
		$str = '<select name="'.$name.'" id="'.$id.'" class="" >';
		 $str .= '<option value=""  >Select plan</option>';
		while($row = mysql_fetch_assoc($q)) {  
			//$result[] = $row;
		   $selected_opt = '';
		   if($selected > 0 && $selected == $row['id']) $selected_opt = 'selected="selected"';
           $str .= '<option value="'.$row['id'].'" '.$selected_opt.' >'.$row['display_name'].'</option>';
        }
		$str .= '</select>';
		return $str;
		
	}
	
	public function all_plans_ary()
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);

		$sql= "SELECT id,display_name FROM  all_plans";
		$q = $db->doQuery($sql);
		
		while($row = mysql_fetch_assoc($q)) {  
			$result[$row['id']] = $row['display_name'];
           
        }
		return $result;
		
	}

};
?>