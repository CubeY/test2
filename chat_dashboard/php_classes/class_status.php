<?php

/*===========================================================================**
** 
** Utility functions to access MySQL database
** 
**===========================================================================*/

error_reporting(E_ALL & ~E_STRICT);
    
 class Status {
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
    
    public function add_new_status()
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$name = $_POST['name'];
		
		$sql= "INSERT INTO statuses (`name`) VALUES('$name')";
		$q = $db->doQuery($sql);
		
		if($q)
		{
			header('location:status.php?msg=success');exit;
		}
		else
		{
			header('location:status.php?msg=error');exit;
		}
		
		
		
		
	}
	
	
	
	public function all_status()
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql= "SELECT * FROM  statuses";
		$q = $db->doQuery($sql);
		
		while($row = mysql_fetch_assoc($q)) {  
			$result[] = $row;
           
        }
		return $result;
		
	}
	
	public function all_status_change_log()
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql= "SELECT S.name AS old_status_name,S2.name AS new_status_name, UM.name AS changed_by_name , UL.`f_name`, UL.`l_name` ,SCL.`id` AS log_id, `chenged_date` FROM `statuses_change_log` AS SCL 
				INNER JOIN users_list AS UL ON UL.id=SCL.user_id
				INNER JOIN user_master AS UM ON UM.id=SCL.chenged_by
				INNER JOIN statuses AS S ON S.id=SCL.old_status
				INNER JOIN statuses AS S2 ON S2.id=SCL.new_status
				ORDER BY SCL.id DESC";
		$q = $db->doQuery($sql);
		
		while($row = mysql_fetch_assoc($q)) {  
			$result[] = $row;
           
        }
		return $result;
		
	}
	
	
	public function edit_status_html()
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$id = $_POST['id'];
		$sql= "SELECT * FROM  statuses WHERE id= ".$id."";
		
		$q = $db->doQuery($sql);
		$row = mysql_fetch_assoc($q);
		
		//print_r($row);
		?>
		<form name="edit_status_frm_<?php echo $row['id'];?>" id="edit_status_frm_<?php echo $row['id'];?>" action="" method="post" >
        <input type="hidden" name="todo"  value="update_status_details"/>
         
         <div class="create_new_cls" >
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">Status Name:</div>
        <div><input type="text" class="required" name="u_name" value="<?php echo $row['name'];?>" style="width:300px;"/></div>
    </div>
    <div class="clear"></div>
     <div class="single_row">
        <div class="label_u">&nbsp;</div>
        <div><input type="submit" value="Save"  />&nbsp;<input type="button" value="Cancel" onClick="HideStatuseditBlock(<?php echo $row['id'];?>)" /></div>
    </div>
    <div class="clear"></div>
</div>


        </form>
		<?php
	}
	
	
	
	
	public function update_status_dtl()
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$id = $_POST['id'];
		$name = $_POST['name'];
		
		$sql= "UPDATE statuses  SET `name`= '".$name."' , last_modified_by = '".Status::logedin_username()."',last_modified = '".date('Y-m-d H:i:s')."' WHERE id = ".$id."";
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
        session_start();
		return $_SESSION['user_name'];
    }
	
	public function delete_status()
	{
	  $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
	     
		$id = $_POST['id'];
		echo $sql= "DELETE FROM statuses WHERE id = ".$id."";
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
	public function total_subtopic_for_stage($id)
	{
	  $db = Database::init(DB_HOST, DB_USER, DB_PWD, DB_NAME);
	  
	  $sql= "SELECT COUNT(subtopic_id) AS total FROM subtopics WHERE stage = ".$id."";
	  $q = $db->doQuery($sql);
	  $r = mysql_fetch_assoc($q);
	  return $r['total'];
	
	}
	public function status_change_log_per_student($student_id)
	{
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		$sql= "SELECT S.name AS old_status_name,S2.name AS new_status_name, UM.name AS changed_by_name , UL.`f_name`, UL.`l_name` ,SCL.`id` AS log_id, `chenged_date` FROM `statuses_change_log` AS SCL 
				INNER JOIN users_list AS UL ON UL.id=SCL.user_id
				INNER JOIN user_master AS UM ON UM.id=SCL.chenged_by
				INNER JOIN statuses AS S ON S.id=SCL.old_status
				INNER JOIN statuses AS S2 ON S2.id=SCL.new_status
				WHERE UL.id = $student_id
				ORDER BY SCL.id DESC";
		$q = $db->doQuery($sql);
		$num_rows = mysql_num_rows($q);
		if($num_rows > 0) {
		?>
        <style type="text/css">
		.u_slst{width:20px;text-align:center;height:45px;}
		.u_os,.u_ns{width:225px;text-align:center;height:45px;}
		.u_cb{width:140px;text-align:center;height:45px;}
		.u_date{width:140px;text-align:center;height:45px;}
		</style>
		<div class="table_view" style="width:760px;">
		 <div class="header">
			<div class="cell u_slst" style="height:30px;line-hight:20px;">Sl</div>
			<div class="cell u_os" style="height:30px;line-hight:20px;">Old status</div>
			<div class="cell u_ns" style="height:30px;line-hight:20px;">New status</div>
			<div class="cell u_cb" style="height:30px;line-hight:20px;">Changed by</div>
			<div class="cell u_date" style="height:30px;line-hight:20px;">Date</div>
		   <!-- <div class="cell u_ac">Action</div>-->
		  </div>
		 <div class="clear"></div>
        <?php
		$i_status = 1;
		
		while($row = mysql_fetch_assoc($q)) {  
		?>
		<div id="proTr_<?php echo $row['id'];?>">
         <div class="cell u_slst" style="background: none repeat scroll 0 0 #E2E2E2;"><?php echo  $i_status++;?></div>
        <div class="cell u_os" style="background: none repeat scroll 0 0 #E2E2E2;"><?php echo $row['old_status_name'];?> </div>
        <div class="cell u_ns" style="background: none repeat scroll 0 0 #E2E2E2;"><?php echo $row['new_status_name'];?> </div>
        <div class="cell u_cb" style="background: none repeat scroll 0 0 #E2E2E2;"><?php echo $row['changed_by_name'];?> </div>
        <div class="cell u_date" style="background: none repeat scroll 0 0 #E2E2E2;"><?php echo $row['chenged_date'];?> </div>
        
		</div>
         <div class="clear"></div>
       
    <?php       
        	}
	?>
		</div>
	<?php 
	 	}
		else
		{
			echo "No Record";
		}
		
	}
	
};



?>
