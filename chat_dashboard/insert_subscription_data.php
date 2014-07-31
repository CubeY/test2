<?php
/*======================================================================**
**                                                                           
** Page : Insert data into okpanda_teachers_accounts » subscription_length
** Created By : BIdhan
**                                                                           
**======================================================================*/
$current_pname = 'insert_subscription_data';
include 'inc/inc.php';

Auth::checkAuth();



//include 'inc/header.php';
?>



<?php


$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);


// TRUNCATE table subscription_length
$sql_empty = "TRUNCATE subscription_length";
$db->doQuery($sql_empty);


$sql= "SELECT id , slot , converted_by ,first_paid_date , cancellation_date FROM `users_list` WHERE first_paid_date != '0000-00-00 00:00:00' ";

$q = $db->doQuery($sql);

echo mysql_num_rows($q).'<br />';
while($r= mysql_fetch_assoc($q))
{
	//echo $r['converted_by'];
	$sql_insert = "INSERT INTO `subscription_length` (`student_id`, `teacher_id`, `start_date`, `cancellation_date`) VALUES ( '".$r['id']."', '".$r['converted_by']."', '".$r['first_paid_date']."', '".$r['cancellation_date']."');";
	$db->doQuery($sql_insert);//exit;
}



		
