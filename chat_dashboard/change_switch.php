<?php
/*======================================================================**
**                                                                           
** Page:Menu , Menu control
** Created By : Bidhan Ch
**                                                                           
**======================================================================*/
$current_pname = 'change_switch';
include 'inc/inc.php';

Auth::checkAuth();

$switch = $_REQUEST['value'];
if ($switch > 0)
	$switch=1;
else
	$switch=0;

$switched_by = $_SESSION['user_id'];	
$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
$sql ="INSERT INTO master_switch (value,switched_by) VALUES (".$switch.",".$switched_by.")";
$q = $db->doQuery($sql) or die ("<br>** Error in database table <b>".mysql_error()."</b><br>$sql");

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
