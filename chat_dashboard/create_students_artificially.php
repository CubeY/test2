<?php
/*======================================================================**
**                                                                           
** Page:Create students
** Created By : Bidhan
** 
They are from the students before we have this system but we need to add them to know true conversion rate
      
Azi	89
Dax	85
Dada	34
Antonio	4
Lei 	61
Lalaine	7
Kristine	7
Kelvin	6
Jenielle	11
Iza	63
Ellen	3
Gian	80
	
https://docs.google.com/spreadsheets/d/14orPiFjuOXnvW59nrkdGZiihOjECPrBJcRaOcX1c2OY/edit#gid=0
                                                                     
**======================================================================*/
$current_pname = 'crate_student_';
include 'inc/inc.php';

$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
//set_time_limit(0);
$cmnt = "Old Student"; // Default comment  for all
$date_val = "2014-01-01"; // Default join date for all
$life_status = 'unknown'; // Defaultlife status  for all


//------------------------------------------------------
// (1) Create 89 students (F.T. Expired) for Azi Maridul
//------------------------------------------------------
// getting "No Time Slot" id and put them all to this id
$slot_id = Student::getteacherNoSlotDtl($teacher_id = 74); // Azi Maridul
for($i=0;$i<10089;$i++)
{
	$unique_user_id = 'bidhan';//Student::generateRandomString();
	$sql = "INSERT INTO users_list(`slot`,`created_on`,`unique_user_id`, `status`,`comment`,`life_status`,`old`)  VALUES(".$slot_id.",'".$date_val."','".$unique_user_id."',6,'".$cmnt."','".$life_status."',1)";
	$db->doQuery($sql);
}
die;

//------------------------------------------------------
// (2)  Create 85 students (F.T. Expired) for Dax Casquejo
//------------------------------------------------------
// getting "No Time Slot" id and put them all to this id
$slot_id = Student::getteacherNoSlotDtl($teacher_id = 73); // Dax Casquejo
for($i=0;$i<185;$i++)
{
	$unique_user_id = Student::generateRandomString();
	$sql = "INSERT INTO users_list(`slot`,`created_on`,`unique_user_id`, `status`,`comment`,`life_status`,`old`)  VALUES(".$slot_id.",'".$date_val."','".$unique_user_id."',6,'".$cmnt."','".$life_status."',1)";
	$db->doQuery($sql);
}



//------------------------------------------------------
//(3)  Create 34 students (F.T. Expired) for Dada
//------------------------------------------------------
// getting "No Time Slot" id and put them all to this id
$slot_id = Student::getteacherNoSlotDtl($teacher_id = 58); // Dada Samson
for($i=0;$i<134;$i++)
{
	$unique_user_id = Student::generateRandomString();
	$sql = "INSERT INTO users_list(`slot`,`created_on`,`unique_user_id`, `status`,`comment`,`life_status`,`old`)  VALUES(".$slot_id.",'".$date_val."','".$unique_user_id."',6,'".$cmnt."','".$life_status."',1)";
	$db->doQuery($sql);
}



//------------------------------------------------------
// (4) Create 4 students (F.T. Expired) for Antonio Chuidian
//------------------------------------------------------
// getting "No Time Slot" id and put them all to this id
$slot_id = Student::getteacherNoSlotDtl($teacher_id = 72); // :Antonio Chuidian
for($i=0;$i<114;$i++)
{
	$unique_user_id = Student::generateRandomString();
	$sql = "INSERT INTO users_list(`slot`,`created_on`,`unique_user_id`, `status`,`comment`,`life_status`,`old`)  VALUES(".$slot_id.",'".$date_val."','".$unique_user_id."',6,'".$cmnt."','".$life_status."',1)";
	$db->doQuery($sql);
}



//------------------------------------------------------
//(5)  Create 61 students (F.T. Expired) for Lei Solito
//------------------------------------------------------
// getting "No Time Slot" id and put them all to this id
$slot_id = Student::getteacherNoSlotDtl($teacher_id = 68); // Lei Solito
for($i=0;$i<161;$i++)
{
	$unique_user_id = Student::generateRandomString();
	$sql = "INSERT INTO users_list(`slot`,`created_on`,`unique_user_id`, `status`,`comment`,`life_status`,`old`)  VALUES(".$slot_id.",'".$date_val."','".$unique_user_id."',6,'".$cmnt."','".$life_status."',1)";
	$db->doQuery($sql);
}



//------------------------------------------------------
// (6) Create 7 students (F.T. Expired) for Lalaine Fukuro
//------------------------------------------------------
// getting "No Time Slot" id and put them all to this id
$slot_id = Student::getteacherNoSlotDtl($teacher_id = 67); // Lalaine Fukuro
for($i=0;$i<117;$i++)
{
	$unique_user_id = Student::generateRandomString();
	$sql = "INSERT INTO users_list(`slot`,`created_on`,`unique_user_id`, `status`,`comment`,`life_status`,`old`)  VALUES(".$slot_id.",'".$date_val."','".$unique_user_id."',6,'".$cmnt."','".$life_status."',1)";
	$db->doQuery($sql);
}



//------------------------------------------------------
// (7) Create 7 students (F.T. Expired) for Kristine Kakas
//------------------------------------------------------
// getting "No Time Slot" id and put them all to this id
$slot_id = Student::getteacherNoSlotDtl($teacher_id = 66); // Kristine Kakas
for($i=0;$i<117;$i++)
{
	$unique_user_id = Student::generateRandomString();
	$sql = "INSERT INTO users_list(`slot`,`created_on`,`unique_user_id`, `status`,`comment`,`life_status`,`old`)  VALUES(".$slot_id.",'".$date_val."','".$unique_user_id."',6,'".$cmnt."','".$life_status."',1)";
	$db->doQuery($sql);
}



//------------------------------------------------------
// (8) Create 6 students (F.T. Expired) for Kelvin Sagayap
//------------------------------------------------------
// getting "No Time Slot" id and put them all to this id
$slot_id = Student::getteacherNoSlotDtl($teacher_id = 65); // Kelvin Sagayap
for($i=0;$i<116;$i++)
{
	$unique_user_id = Student::generateRandomString();
	$sql = "INSERT INTO users_list(`slot`,`created_on`,`unique_user_id`, `status`,`comment`,`life_status`,`old`)  VALUES(".$slot_id.",'".$date_val."','".$unique_user_id."',6,'".$cmnt."','".$life_status."',1)";
	$db->doQuery($sql);
}



//------------------------------------------------------
// (9) Create 11 students (F.T. Expired) for Jenielle Gabriel
//------------------------------------------------------
// getting "No Time Slot" id and put them all to this id
$slot_id = Student::getteacherNoSlotDtl($teacher_id = 64); // Jenielle Gabriel
for($i=0;$i<111;$i++)
{
	$unique_user_id = Student::generateRandomString();
	$sql = "INSERT INTO users_list(`slot`,`created_on`,`unique_user_id`, `status`,`comment`,`life_status`,`old`)  VALUES(".$slot_id.",'".$date_val."','".$unique_user_id."',6,'".$cmnt."','".$life_status."',1)";
	$db->doQuery($sql);
}



//------------------------------------------------------
// (10) Create 63 students (F.T. Expired) for Iza Sanchez
//------------------------------------------------------
// getting "No Time Slot" id and put them all to this id
$slot_id = Student::getteacherNoSlotDtl($teacher_id = 63); // Iza Sanchez
for($i=0;$i<613;$i++)
{
	$unique_user_id = Student::generateRandomString();
	$sql = "INSERT INTO users_list(`slot`,`created_on`,`unique_user_id`, `status`,`comment`,`life_status`,`old`)  VALUES(".$slot_id.",'".$date_val."','".$unique_user_id."',6,'".$cmnt."','".$life_status."',1)";
	$db->doQuery($sql);
}



//------------------------------------------------------
// (11) Create 3 students (F.T. Expired) for Ellen Divinagracia
//------------------------------------------------------
// getting "No Time Slot" id and put them all to this id
$slot_id = Student::getteacherNoSlotDtl($teacher_id = 61); // Ellen Divinagracia
for($i=0;$i<311;$i++)
{
	$unique_user_id = Student::generateRandomString();
	$sql = "INSERT INTO users_list(`slot`,`created_on`,`unique_user_id`, `status`,`comment`,`life_status`,`old`)  VALUES(".$slot_id.",'".$date_val."','".$unique_user_id."',6,'".$cmnt."','".$life_status."',1)";
	$db->doQuery($sql);
}




//------------------------------------------------------
//(12)  Create 80 students (F.T. Expired) for Gian Descallar
//------------------------------------------------------
// getting "No Time Slot" id and put them all to this id
$slot_id = Student::getteacherNoSlotDtl($teacher_id = 60); // Gian Descallar
for($i=0;$i<180;$i++)
{
	$unique_user_id = Student::generateRandomString();
	$sql = "INSERT INTO users_list(`slot`,`created_on`,`unique_user_id`, `status`,`comment`,`life_status`,`old`)  VALUES(".$slot_id.",'".$date_val."','".$unique_user_id."',6,'".$cmnt."','".$life_status."',1)";
	$db->doQuery($sql);
}



/////////////////////////UPDATE available_line_url_shows

$sql_day = "SELECT teacher_slots.id as slot_id FROM teacher_slots JOIN teachers ON teacher_slots.teacher_id=teachers.user_id"; 
	$q_day = $db->doQuery($sql_day);
	while($row_day = mysql_fetch_assoc($q_day)) {  
		User::update_available_line_url_shows($row_day['slot_id']);	   
	}
