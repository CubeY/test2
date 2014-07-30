<?php

/*===========================================================================**
** Class : User
** Created By ; Bidhan
** Utility functions to access MySQL database
** 
**===========================================================================*/
error_reporting(E_ALL & ~E_STRICT);
    
 class Lus {
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
    
	public function getData()
	{
		
		//session_start(); 
		date_default_timezone_set("Asia/Tokyo");
		
		$teacher_id = $_POST['teacher_id'];
		$slot = $_POST['slot'];
		//$created_on = $_POST['created_on'];
		$date_from = $_POST['date_from'];
		$date_to = $_POST['date_to'];
		
		$condition = " WHERE LUS.id!= '' ";
		$flag = 1;
		$con = " ";
		$date = date("Y-m-d H:i:s");
		
		
		if(!empty($teacher_id) && $teacher_id!="undefined" )
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." TS.`teacher_id` = ".$teacher_id."";
		}
		if(isset($slot) && $slot!="undefined" && $slot!="")
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `slot` = '".$slot."'";
		}
		
		if(!empty($date_from) && !empty($date_to))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." LUS.`shown_date` >= '".$date_from." 00:00:00' AND LUS.`shown_date` <= '".$date_to." 23:59:59'";
		}
		
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		
		$per_page = $_POST['per_page'];
		$page_num = $_POST['page'];
		$limit = " LIMIT ".($page_num-1)*$per_page.",".$per_page." ";

		
		$order = "ORDER BY shown_date DESC";
		$raw_sql = "SELECT UM.name,TS.start_time,TS.end_time, shown_date FROM `line_url_shown` AS LUS 
					LEFT JOIN `teacher_slots`  AS TS ON(TS.id=LUS.slot_id) 
					LEFT JOIN user_master AS UM ON(UM.id=TS.teacher_id) ".$condition." ";
		$sql = $raw_sql." ".$order." ".$limit;
		
		
		//echo $sql;
		$q = $db->doQuery($sql);
		
		
		$sql_count= "SELECT COUNT(LUS.id) AS total FROM line_url_shown AS LUS LEFT JOIN `teacher_slots`  AS TS ON(TS.id=LUS.slot_id) LEFT JOIN user_master AS UM ON(UM.id=TS.teacher_id)  ".$condition." ";
		$q_count = $db->doQuery($sql_count);
		
		$row_count_res = mysql_fetch_assoc($q_count);
		$row_count = $row_count_res['total'];
		
		//print_r($row);
		?>
        
      <style>
.table_view {
    display:table;
	width:1340px;
}
.hide_student {
    display:none;
}
.header {
    di splay:table-header-group;
    font-weight:bold;
	text-align:center;
}
.header .cell{height:35px;background:#666;color:#FFF;float:left;font-size:13px;}
.cell {
    dis play:table-cell;
    width:auto;
	m ax-width:100px;
	backg round-color:#069;
	ma rgin:2px;
	border:1px solid #FFFFFF;
	float:left;
	height:30px;
	back ground:#E2E2E2;
	display:block;
	font-size:14px;
	line-height:20px;
}
.u_status{width:470px;text-align:center;}
.u_t{width:200px;text-align:center;}
.u_ac{width:328px;text-align:center;}

</style>
<div class="list_user">
<div class="table_view">
<?php if($_POST['page']==1) { ?>
   
  
  <div class="header nav">
    <div class="cell u_t">Sl</div>
    <div class="cell u_status">Teacher Name</div>
    <div class="cell u_ac">Slot</div>
    <div class="cell u_ac">Date</div>
  </div>
 <div class="clear"></div>
 <input type="hidden" id="row_count" name="row_count" value="<?php echo $row_count;?>" />

   <?php } ?>
  <?php 
  if(mysql_num_rows($q) > 0)
  {
	  $per_page = $_POST['per_page'];
	 $page_num = $_POST['page'];
	  $slNoToShow = ($page_num-1)*$per_page;
	  
  $i_log =0;
 while($row = mysql_fetch_assoc($q))
  {
	 $i_log++;
	 ?>
     <div id="proTr_<?php echo $row['id'];?>">
     <div class="cell u_t"><?php echo  $slNoToShow+$i_log;?></div>
    <div class="cell u_status"><?php echo $row['name'] ;?> </div>
    <div class="cell u_ac"><?php echo $row['start_time'].'-'.$row['start_time'];?></div>
    <div class="cell u_ac"><?php echo $row['shown_date'];?></div>
    </div>
     <div class="clear"></div>
	 <?php 
 	 }
  }
  else
  {
	  if($_POST['page']==1)
		  echo '<div class="no_rec_found">NO LOG RECORD!</div>';
		  else 
		   echo '<div class="no_rec_found">NO MORE RECORD!</div>';
  }
  ?>
</div>
</div>
  <?php }
  
  	public function getSummaryData()
	{
		
		//session_start(); 
		date_default_timezone_set("Asia/Tokyo");
		
		$teacher_id = $_POST['teacher_id'];
		$slot = $_POST['slot'];
		//$created_on = $_POST['created_on'];
		$date_from = $_POST['date_from'];
		$date_to = $_POST['date_to'];
		
		$condition = " WHERE LUS.id!= '' ";
		$flag = 1;
		$con = " ";
		$date = date("Y-m-d H:i:s");
		
		
		if(!empty($teacher_id) && $teacher_id!="undefined" )
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." TS.`teacher_id` = ".$teacher_id."";
		}
		if(isset($slot) && $slot!="undefined" && $slot!="")
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." `slot` = '".$slot."'";
		}
		
		if(!empty($date_from) && !empty($date_to))
		{
			$flag++;
			if($flag>1){ $con = " AND "; }
			$condition .= $con." LUS.`shown_date` >= '".$date_from." 00:00:00' AND LUS.`shown_date` <= '".$date_to." 23:59:59'";
		}
		
		
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$lus_ary = array();
		$min_shown_date = '';// date('Y-m-d H:i:s');
		$order = "ORDER BY shown_date ASC";
		$raw_sql = "SELECT TS.teacher_id,COUNT(LUS.id) AS total_lus,MIN(shown_date) AS shown_date FROM `line_url_shown` AS LUS 
					LEFT JOIN `teacher_slots`  AS TS ON(TS.id=LUS.slot_id) 
					 ".$condition." GROUP BY TS.teacher_id";
		$sql = $raw_sql." ".$order;
		//echo $sql;
		$q = $db->doQuery($sql);
		$ttl = 0;
		
		while($row = mysql_fetch_assoc($q)) {  
			$lus_ary[$row['teacher_id']] = $row['total_lus'];
			//$ttl = $ttl + $row['total_lus'];
          if($min_shown_date == '')
		  $min_shown_date = $row['shown_date'];
        }
		//print_r($lus_ary);
		//echo $min_shown_date;
		//$min_shown_date = date('Y-m-d',strtotime($min_shown_date));
		$sql_total_student = "SELECT TS.teacher_id AS teacher_id,  COUNT( UL.id ) AS total FROM `users_list` AS UL	
					LEFT JOIN teacher_slots AS TS ON ( UL.slot = TS.id ) WHERE UL.slot !=0 AND UL.created_on >= '".$min_shown_date."'
					";
		
		if(!empty($date_from) && !empty($date_to))
		{
			$sql_total_student .= " AND UL.`created_on` >= '".$date_from." 00:00:00' AND UL.`created_on` <= '".$date_to." 23:59:59'";
		}
		$sql_total_student .=" GROUP BY TS.teacher_id  ORDER BY `TS`.`teacher_id` ASC  ";
		//echo $sql_total_student ;
		$qts = $db->doQuery($sql_total_student);
		//print_r($row);
		while($row = mysql_fetch_assoc($qts)) {  
			$total_student_ary[$row['teacher_id']] = $row['total'];
           $ttl = $ttl + $row['total'];
        }
		
		//echo '<pre>';
		//echo $ttl;
		//print_r($total_student_ary);
		//echo '</pre>';
		
		
		$all_user = User::all_user('teacher');
		//print_r($all_user);
		?>
        
      <style>
.table_view {
    display:table;
	width:1340px;
}
.hide_student {
    display:none;
}
.header {
    di splay:table-header-group;
    font-weight:bold;
	text-align:center;
}
.header .cell{height:35px;background:#666;color:#FFF;float:left;font-size:13px;}
.cell {
    dis play:table-cell;
    width:auto;
	m ax-width:100px;
	backg round-color:#069;
	ma rgin:2px;
	border:1px solid #FFFFFF;
	float:left;
	height:30px;
	back ground:#E2E2E2;
	display:block;
	font-size:14px;
	line-height:20px;
}
.u_status{width:470px;text-align:center;}
.u_t{width:170px;text-align:center;}
.u_ac{width:228px;text-align:center;}

</style>
<div class="list_user">
<div class="table_view">
  <div class="header nav">
    <div class="cell u_t">Sl</div>
    <div class="cell u_status">Teacher Name</div>
    <div class="cell u_ac">Total LUS</div>
    <div class="cell u_ac">Total Students</div>
    <div class="cell u_ac">Percentage</div>
  </div>
 <div class="clear"></div>
  <?php 
  if(count($all_user) > 0)
  {
  for($i_user = 0;$i_user < count($all_user);$i_user++)
  {
	// $i_log++;
	$num_lus = isset($lus_ary[$all_user[$i_user]['id']])?$lus_ary[$all_user[$i_user]['id']]:0;
	$total_student = isset($total_student_ary[$all_user[$i_user]['id']])?$total_student_ary[$all_user[$i_user]['id']]:0;
	$prcntg = ($num_lus >0)?round(100*($total_student/$num_lus),2):0; 
	
	//$prcntg = round(100*($total_student/$num_lus) , 2);
	 ?>
     <div id="proTr_<?php echo $i_user+1;?>">
     <div class="cell u_t"><?php echo  $i_user+1;?></div>
    <div class="cell u_status"><?php echo $all_user[$i_user]['name'] ;?> </div>
    <div class="cell u_ac"><?php echo $num_lus;?></div>
    <div class="cell u_ac"><?php echo $total_student;?></div>
     <div class="cell u_ac"><?php echo $prcntg;?>%</div>
    </div>
     <div class="clear"></div>
	 <?php 
 	 }
  }
  else
  {
	  echo '<h3>No record</h3>';
  }
  ?>
</div>
</div>
  <?php }

};
?>