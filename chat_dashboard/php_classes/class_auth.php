<?php

/*===========================================================================**
** 
** Utility functions to access MySQL database
** 
**===========================================================================*/

error_reporting(E_ALL & ~E_STRICT);
    
class Auth {
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
    
    public function login()
	{
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		$email = $_POST['email'];
		$pass = Auth::escapeForSQL($_POST['pass']);

		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
        $sql = "SELECT id,email,user,role FROM user_master WHERE  user = '$email' AND  pass = '$pass'";
     
	    $q = $db->doQuery($sql);
		$r = mysql_fetch_assoc($q);
		
        if(mysql_num_rows($q) > 0 )
		{
			$_SESSION['user_id'] = $r['id'];
			$_SESSION['user_email'] = $r['email'];
			$_SESSION['user_name'] = $r['user'];
			$role = explode(',',$r['role']);
			$_SESSION['user_role'] = $role[0];
			if(count($role) > 1)
			$_SESSION['user_role_second'] = $role[1];
			else 
			$_SESSION['user_role_second'] = '';
			
			
			// UPDATE last login
			 $sqlUpdateLogin = "UPDATE `user_master` SET last_login = '".date('Y-m-d H:i:s')."' WHERE id = ".$r['id'];
	   	     $db->doQuery($sqlUpdateLogin);
			
			header('location:index.php');exit;
		}
		else
		{
			header('location:login.php?msg=error');exit;
		}
	}
	public function logOut()
	{
		session_start();
		session_destroy();
		header('location:login.php');exit;
	}
	public function checkAuth()
	{
		//session_start();
		
		//$_SESSION['user_email'] = $r['email'];
		//	$_SESSION['user_name'] = $r['user'];
		//	$_SESSION['user_role'] = $r['role'];
		if (session_status() == PHP_SESSION_NONE) {
		   session_start();
		}

		if(isset($_SESSION['user_id']))
		Auth::isUserActive($_SESSION['user_id']);
		
		
		if(isset($_SESSION['user_id']) == '')
		{
	//		header('location:login.php');exit;
		
						header('location:login.php?mode=logout');exit;
		}
		else
		{
			return true;
		}
	}
	public function isUserActive($user_id)
	{
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
        $sql = "SELECT status FROM user_master WHERE id = '$user_id'";
	    $q = $db->doQuery($sql);
		$r = mysql_fetch_assoc($q);
		if($r['status'] == 'Y')
		{
			return true;
		}
		else
		{
			$msg = 'Sorry your account status is inactive. Please contact to Admin';
			echo '<h3 class="error_msg_h3" style="background: #FF969E;padding: 8px 26px;color: #4D4444;">'.$msg.'&nbsp;&nbsp;<a style="float:right;" class="link_toSubtopic" href="login.php?mode=logout">Logout</a></h3>';exit;
		}
	}

};



?>
