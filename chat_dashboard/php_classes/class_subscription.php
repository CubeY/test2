<?php

/*===========================================================================**
** Class : Subscription
** Created By ; Bidhan
** Utility functions to access MySQL database
** 
**===========================================================================*/
error_reporting(E_ALL & ~E_STRICT);
    
 class Subscription {
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
    
	public function update_subscription_lenght($student_id,$new_slot)
	{
	    $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		date_default_timezone_set("Asia/Tokyo");
		$sql ="SELECT slot FROM `users_list`  WHERE id = ".$student_id;
		
				
		
		$q = $db->doQuery($sql);
		$r = mysql_fetch_assoc($q);
		
		$exist_slot = $r['slot'];
		if($new_slot != $exist_slot) // Student tranfer
		{
			//
			$new_teacher_id  = Subscription::getTeacherIdBySlotId($new_slot);
			$old_teacher_id  = Subscription::getTeacherIdBySlotId($exist_slot);
			
			$sql_update = "UPDATE subscription_length SET cancellation_date = '".date('Y-m-d H:i:s')."' WHERE 
							student_id = ".$student_id." AND teacher_id =".$old_teacher_id." ORDER BY id DESC LIMIT 1";
			$db->doQuery($sql_update);
			
			$sql_insert = "INSERT INTO(`student_id`, `teacher_id`, `start_date`) VALUES ('$student_id','$new_teacher_id','".date('Y-m-d H:i:s')."')";
			$db->doQuery($sql_insert);
			
		}
		
		
		

	}
	
	
	public function getTeacherIdBySlotId($slot_id)
	{
	    $db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		  
		$sql= "SELECT teacher_id FROM teacher_slots WHERE id = ".$slot_id."";
		$q = $db->doQuery($sql);
		$row = mysql_fetch_assoc($q);
		
		echo $row['teacher_id'];
		
	}
	
};
?>