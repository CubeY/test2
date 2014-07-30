<?php

/*======================================================================**
**                                                                           
** Main entry point.
** All PHP (mode) calls will go through this script.  
**                                                                           
**======================================================================*/

// Turn on warnings

error_reporting(0);
ini_set('display_errors', 'on');
chdir(dirname(__FILE__));
require_once("../../../const.php");
//require_once(__DIR__ . "/css/style.css");
require_once(INC_PATH."common.php");
date_default_timezone_set("Asia/Tokyo");
require_once(DIR_CLASSES . "class_db_this.php");
require_once("../php_classes/class_user.php");
require_once("../php_classes/class_student.php");
require_once("../php_classes/class_status.php");

	
	$active_user_only = true;
	
	$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
	
	$sql = "SELECT email,name FROM `user_master` WHERE `email` != '' AND role = 'administrator' ";
	
	if($active_user_only)	{
		$sql .=" AND status = 'Y' ";
	}

	$q = $db->doQuery($sql);
	while($row = mysql_fetch_assoc($q)) {  
		$result[] = $row;
	   
	}
	
	$tota_puserSql = "SELECT COUNT(`id`) AS total FROM `users_list` WHERE `status` = 7 "; // 7 >> Paying USers
	$qTotalPQ = $db->doQuery($tota_puserSql);
	$resPayingU = mysql_fetch_assoc($qTotalPQ);
	$payingUser = $resPayingU['total'];
	send_mail($result,$payingUser);	
	
	
		//echo '<pre>';
		//print_r($result);
function send_mail($u_array,$payingUser)
{
	$subject = "Total number of paying users ".$payingUser;
	$msg = "Total number of paying users ".$payingUser;
	
	prepare_and_send_email("Bidhan","bidhan.ssca@gmail.com",$subject,$msg);//exit;
	
	for($i=0;$i<count($u_array);$i++)
	{
		prepare_and_send_email($u_array[$i]['name'],$u_array[$i]['email'],$subject,$msg);
	}
}
		
function sendHtmlTextEmail($to, $subject, $messageText, $messageHtml , $fromEmail)
{
	
	//Server Address
	

	
	
	
	
	
	//echo $to.$subject.$messageText.$messageHtml.$fromEmail;
	//charset="iso-8859-1"
$random_hash = md5(date('r', time())); 
$headers = "From: ".$fromEmail;
$headers .= "\r\nContent-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\""; 
$message ='
--PHP-alt-'. $random_hash .'  
Content-Type: text/plain; charset="utf-8" 
Content-Transfer-Encoding: 7bit

' . $messageText . '  	 

--PHP-alt-'. $random_hash .'
Content-Type: text/html; charset="iso-8859-1" 
Content-Transfer-Encoding: 7bit

' . $messageHtml . '  

--PHP-alt-'. $random_hash .'--
';
	//send the email
	$mail_sent = @mail( $to, $subject, $message, $headers );
	//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
	//echo $mail_sent ? "Mail sent" : "Mail failed";
}

function prepare_and_send_email($user_name,$email,$subject,$msg)
{
	
	
		$date = date('Y-m-d');
		$datef = date('Y-m-d H:i:s');
		$body = '';
		$student_dtls = '';
		$teacher_dtls = '';
		
// student details start
		$student_count = Student::all_studentCount();
		$student_dtls = '<div class="list_user">
						<div class="table_view" style="display:table;width:100%;">
						  <div class="header" style="font-weight:bold;text-align:center;height:30px;color:#000;width:100%;">
							<div class="cell u_t" style="height:30px;color:#000;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;font-size:16px;width:30%;text-align:center;">Total Students ('.$student_count.')</div>
							<div class="cell u_status" style="height:30px;color:#000;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;font-size:16px;width:60%;text-align:left;padding-left:8%;">Name</div>
						  </div>
						';
  $all_status = Status::all_status();
  //print_r($all_status);
  $active_students = 0;
  for($i_status = 0;$i_status < count($all_status);$i_status++)
  {
	 
	 $total_students = User::total_users_by_status_id($all_status[$i_status]['id']);
	 if($all_status[$i_status]['id'] == 1 || $all_status[$i_status]['id'] == 3 || $all_status[$i_status]['id'] == 7)  // 1= Initial message sent   3 = IN FT , 7= Paying
	 {
		 $active_students = $active_students + $total_students;
	 }
	 
     $student_dtls .= '<div id="proTr">
     <div class="cell u_t" style="height:30px;color:#000;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;font-size:14px;width:30%;text-align:center;">'.$total_students.'</div>
    <div class="cell u_status" style="height:30px;color:#000;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;font-size:14px;width:60%;text-align:left;padding-left:8%;">'.$all_status[$i_status]['name'].' </div>
    </div>
    ';
  }
  
  $t_total_spots= User::sum_of_each_teachers_max_capacity() ;
  $weighted_active_students = round(User::count_active_student_by_life_status('unknown') + User::count_active_student_by_life_status('working') + 0.3 * User::count_active_student_by_life_status('high_school') + 0.3 * User::count_active_student_by_life_status('college'));
  
$student_dtls .= ' 
</div>
<div style="width: 100%; margin:1px 0 12px 0;">
<hr/>
</div>


</div>
<div style="clear:both;">
	
<div class="bx" style="border: 2px solid #A5BAD1;    border-radius: 6px;    float: left;    margin: 0 5px;    min-height: 60px;    width: 30%;">
<div class="bx_head" style="background: none repeat scroll 0 0 #D7D7D7;    border-radius: 3px 3px 0 0;    color: #0066CC;    font: bold 15px/27px Arial,Helvetica,sans-serif;    height: 30px;    margin: 0 0 10px;    text-align: center;">Overall</div>
<div style="font:bold 13px/8px Arial, Helvetica, sans-serif;color:#090;padding-left:5px;">Total spots: '.$t_total_spots.'</div>
<div style="font:bold 13px/35px Arial, Helvetica, sans-serif;color:#F00;padding-left:5px;">Active Students : '.$active_students.'</div>
<div style="font:bold 13px/14px Arial, Helvetica, sans-serif;color:#F00;padding-left:5px;">Weighted Active Students : '. $weighted_active_students.'</div>
<div style="font:bold 13px/32px Arial, Helvetica, sans-serif;color:#090;padding-left:5px;">Available spots: '.($t_total_spots - $weighted_active_students).'</div>
</div>
<div class="bx" style="border: 2px solid #A5BAD1;    border-radius: 6px;    float: left;    margin: 0 5px;    min-height: 60px;    width: 30%;">
<div class="bx_head" style="background: none repeat scroll 0 0 #D7D7D7;    border-radius: 3px 3px 0 0;    color: #0066CC;    font: bold 15px/27px Arial,Helvetica,sans-serif;    height: 30px;    margin: 0 0 10px;    text-align: center;">Life statuses</div>
<div class="bx_rw" style="color: #093A40;    font: 16px/21px Arial,Helvetica,sans-serif;    padding-left: 5px;">Unknown  : '. User::count_total_student_by_life_status('unknown').'</div>
<div class="bx_rw" style="color: #093A40;    font: 16px/21px Arial,Helvetica,sans-serif;    padding-left: 5px;">High School: '. User::count_total_student_by_life_status('high_school').'</div>
<div class="bx_rw" style="color: #093A40;    font: 16px/21px Arial,Helvetica,sans-serif;    padding-left: 5px;">College : '. User::count_total_student_by_life_status('college').'</div>
<div class="bx_rw" style="color: #093A40;    font: 16px/21px Arial,Helvetica,sans-serif;    padding-left: 5px;">Working : '. User::count_total_student_by_life_status('working').'</div>
</div>

</div>

';
// student details end
// teachers details start
$teacher_dtls = '<div class="clear" style="clear:both;line-height:0;"></div>
					<div class="list_user" style="margin-top:50px;">
					<div class="table_view" style="display:table;width:100%;">
					   ';
					
$all_user = User::all_user('teacher',$active_only = true);
$at_slots = 0;$at_t_cnv_rate_d_by = 0;$at_paying_user = 0; 	$at_fpd = 0;$at_churn = 0;$at_cpt = 0;
  for($i_user = 0;$i_user < count($all_user);$i_user++)
  {
     $teacher_dtls .= '<div id="proTr">
     <div class="cell u_tn" style="height:30px;color:#000;float:left;border:1px solid #FFFFFF;background:#77CAB6;display:block;font-size:14px;width:25%;text-align:left;padding-left:8%;">Name - <strong>'.$all_user[$i_user]['name'].'</strong></div>
	 <div class="cell u_em" style="height:30px;color:#000;float:left;border:1px solid #FFFFFF;background:#77CAB6;display:block;font-size:14px;width:30%;text-align:left;padding-left:2%;">Email - <strong>'.$all_user[$i_user]['email'].'</strong></div>
	<div class="cell u_un" style="height:30px;color:#000;float:left;border:1px solid #FFFFFF;background:#77CAB6;display:block;font-size:14px;width:33%;text-align:center;">Username - <strong>'.$all_user[$i_user]['user'].'</strong></div>
    </div>
     <div class="clear" style="clear:both;"></div>';
	 $teacher_slots = User::get_teacher_slotBy_id($all_user[$i_user]['id']);
	 $teacher_dtls .= '<div class="clear" style="clear:both;line-height:0;"></div>
					 <div class="clear" style="color:#000;font-size:11px;">
					 <div class="cell u_st" style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:150px;text-align:left;padding-left:2%;"><strong>Slot Time</strong></div>
					 <div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:50px;text-align:left;padding-left:2%;"><strong>IMS</strong></div>
					 <div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:50px;text-align:left;padding-left:2%;"><strong>In F.T.</strong></div>
					 <div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:50px;text-align:left;padding-left:2%;"><strong>Paying</strong></div>
					 <div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:50px;text-align:left;padding-left:2%;"><strong>Active</strong></div>
					 <div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:50px;text-align:left;padding-left:2%;"><strong>NPUT</strong></div>
					 <div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:50px;text-align:left;padding-left:2%;"><strong>CPT</strong></div>
					 <div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:50px;text-align:left;padding-left:2%;"><strong>Cnv Rate</strong></div>
					 <div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:96px;text-align:left;padding-left:5px;"><strong>Avg. Sub. length</strong></div>
					 </div>
					  <div class="clear" style="clear:both;line-height:0;"></div>
					 ';
					 
					 $t_ini_msg = 0;$t_in_ft = 0;$t_paying_user = 0;$t_act_stdnt = 0;$t_nupt = 0;$t_cpt = 0;$t_no_res=0;$t_cnv_rate = 0;$t_f_t_expired = 0;
					 $t_churn = 0;$t_fpd= 0;
					 
					for($i=0; $i < count($teacher_slots); $i++)
					  {
						 if($teacher_slots[$i]['empty_slot']==1) { 
							$ts =  "NTS"; 
							}
							else {
								$ts =  $teacher_slots[$i]["start_time"].'&nbsp;To&nbsp;'.$teacher_slots[$i]["end_time"];
							}
							
							//$ims_student = User::count_total_active_student_byslot($teacher_slots[$i]['id']);
							//$act_stdnt = User::count_total_active_student_byslot($teacher_slots[$i]['id']);
							$tslot_id = $teacher_slots[$i]['id'];
							$ini_msg = User::total_users_by_status_id_and_teacher_slots(1,$tslot_id);// 1 = Initial message sent
							$t_ini_msg = $t_ini_msg + $ini_msg;
							//$no_responseA = User::total_users_by_status_id_and_teacher_slots(2,$tslot_id); // 2 = No response to initial message after 2 days
							$in_ft = User::total_users_by_status_id_and_teacher_slots(3,$tslot_id);// 3 = In F.T.
							$t_in_ft = $t_in_ft + $in_ft;
							//$no_responseB = User::total_users_by_status_id_and_teacher_slots(4,$tslot_id);// 4 = F.T. expired without response
							
							$f_t_expired = User::total_users_by_status_id_and_teacher_slots(6,$tslot_id);// 6 = F.T. Expired
							$t_f_t_expired = $t_f_t_expired +$f_t_expired;
							
							$paying_user = User::total_users_by_status_id_and_teacher_slots(7,$tslot_id); // 7 = paying
							$t_paying_user = $t_paying_user+$paying_user;
							//$cancell = User::total_users_by_status_id_and_teacher_slots(8,$tslot_id);// 6 = Cancelled 
							$act_stdnt =  $ini_msg + $in_ft + $paying_user;
							$t_act_stdnt = $t_act_stdnt+$act_stdnt;
							
							//New paying users today = # of user who's first paid date is today
							$nupt = User::new_user_paying_today_by_slot($tslot_id,$datef);	
							$t_nupt = $t_nupt+$nupt;
							//Cancelations of payment today = # of user who's cancellation date is the date email is issued
							$cpt = User::cancelations_of_payment_today_by_slot($tslot_id,$datef);	
							$t_cpt = $t_cpt+$cpt;
							
							//4. Conversion rate per teacher so far.=Paying user / (Paying user + no response + F.T. expired)
							$no_res = User::get_total_no_responded_student($tslot_id);
							$t_no_res = $t_no_res + $no_res;
							
							$cnv_rate_d_by = $paying_user+$no_res+$f_t_expired;
							if($cnv_rate_d_by <=0)
							$cnv_rate = 0;
							else
							$cnv_rate =   round( ( $paying_user / $cnv_rate_d_by ), 2);
							
							
							//Churn per teacher so far = average number of days since the first paid date to today, or if cancelled since the first paid date to cancellation date
							//it's simply average length of students who stayed paying
							//if a student start subscribing 4/20 and cancelled on 5/2, churn is 30 days
							//if he hasn't cancelled as of today (5/6), churn is 34 days
							$churn_array = User::calculate_churn($tslot_id,$datef);
							$t_churn = $t_churn + $churn_array['total'];
							$churn_avg = ($paying_user > 0)?round($churn_array['total']/$paying_user,2):0;
							//$t_fpd = $t_fpd + $churn_array['t_fpd'];
							
							$teacher_dtls .= '<div class="clear" style="clear:both;line-height:0;"></div>
							<div class="clear" style="color:#000;font-size:11px;">
							<div class="cell u_st" style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:150px;text-align:left;padding-left:2%;">'.$ts.'</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:50px;text-align:left;padding-left:2%;">'.$ini_msg.'</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:50px;text-align:left;padding-left:2%;">'.$in_ft.'</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:50px;text-align:left;padding-left:2%;">'.$paying_user.'</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:50px;text-align:left;padding-left:2%;">'.$act_stdnt.'</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:50px;text-align:left;padding-left:2%;">'.$nupt.'</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:50px;text-align:left;padding-left:2%;">'.$cpt.'</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:50px;text-align:left;padding-left:2%;">-</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:96px;text-align:left;padding-left:5px;">'.$churn_avg.'</div>
							</div>
							<div class="clear" style="clear:both;line-height:0;"></div>
							'; 
					  }
					  
					$t_cnv_rate_d_by = $t_paying_user +$t_no_res +$t_f_t_expired ;
							if($t_cnv_rate_d_by <=0)
							$t_cnv_rate = 0;
							else
							$t_cnv_rate =   round( ($t_paying_user / $t_cnv_rate_d_by ), 2);
							
					   
					    $t_churn_avg = ($t_paying_user+$t_cpt>0)?round($t_churn / ($t_paying_user+$t_cpt)):0;
					
							
							
							// all total 
							$at_slots = $at_slots + $i;
							$at_t_cnv_rate_d_by = $at_t_cnv_rate_d_by + $t_cnv_rate_d_by;
							$at_paying_user = $at_paying_user + $t_paying_user; 
							$at_cpt = $at_cpt + $t_cpt; 
							
							$at_fpd = $at_fpd + $t_fpd;
							$at_churn = $at_churn + $t_churn;
							
							
							
							
					 $teacher_dtls .= '<div class="clear" style="color:#000;font-size:11px;">
							<div class="cell u_st" style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;width:150px;text-align:left;padding-left:2%;">Total/Average:'.($i).'</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;width:50px;text-align:left;padding-left:2%;">'.$t_ini_msg.'</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;width:50px;text-align:left;padding-left:2%;">'.$t_in_ft.'</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;width:50px;text-align:left;padding-left:2%;">'.$t_paying_user.'</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;width:50px;text-align:left;padding-left:2%;">'.$t_act_stdnt.'</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;width:50px;text-align:left;padding-left:2%;">'.$t_nupt.'</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;width:50px;text-align:left;padding-left:2%;">'.$t_cpt.'</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;width:74px;text-align:center;padding-left: 2px;line-height: 1.3;">'.str_replace(array('Conversion','Resp Conv'),array('Cnv','R cnv'), User::teacher_conversion_rate($all_user[$i_user]['id'])).'</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;width:96px;text-align:left;padding-left:5px;">'.$t_churn_avg.'</div>
							</div>
							<div class="clear" style="clear:both;line-height:0;"></div>
							'; 
							
							 
					 //$teacher_dtls .= '<div class="cell blank" style="height:30px;color:#000;float:left;border:1px solid #FFFFFF;background:#FFF;display:block;font-size:14px;width:75.5%;text-align:center; background:#fff !important;">'.$t_no_res.'</div>';
					 // $teacher_dtls .= '<div class="clear" style="clear:both;"></div>';
					  
  }
  
  
		// over all % calculation
		//echo $at_paying_user;
		if($at_t_cnv_rate_d_by <=0)
		$at_cnv_rate = 0;
		else
		$at_cnv_rate =   round( ($at_paying_user / $at_t_cnv_rate_d_by ), 2);
		
		
		//$at_churn_avg = ($at_fpd >0)?round(($at_churn/$at_fpd),2):0;
		 $at_churn_avg = ($at_paying_user+$at_cpt>0)?round($at_churn / ($at_paying_user+$at_cpt),2):0;				
						
   $teacher_dtls .= '<div class="clear" style="color:#FFF;font-size:13px;">
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#37A7F1;display:block;text-align:left;padding-left:2%;width:96%;">Overall Summary</div>
							</div>
							<div class="clear" style="clear:both;line-height:0;"></div>
							'; 
							
    $teacher_dtls .= '<div class="clear" style="color:#000;font-size:11px;">
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;width:96%;text-align:left;padding-left:2%;">'.$at_slots.' Slot(s) <span style="padding-left:80px;">'.User::overall_teachers_conversion_rate().'</span><span style="padding-left:80px;">'.$at_churn_avg.' Avg. Sub. length</span></div>
							</div>
							<div class="clear" style="clear:both;line-height:0;"></div>
							';  
	// Daily stat of previous day
	$total_joined_p_day = User::total_student_by_date($datef,$col = 'created_on',$dayDif = 1);
	$total_started_f_t_p_day = User::total_student_by_date($datef,$col = 'first_response',$dayDif = 1);
	$total_converted_to_paying_p_day = User::total_student_by_date($datef,$col = 'first_paid_date',$dayDif = 1);
	$total_payment_infosent_p_day = User::total_student_by_date($datef,$col = 'payment_info_sent_date',$dayDif = 1);
	$total_ft_expire_p_day = User::total_student_by_date($datef,$col = 'ft_expiration',$dayDif = 1);
	$total_ft_expire_ow_p_day = User::total_student_by_date_from_logs_tbl($datef,$col = 'chenged_date',$status = 4 ,$dayDif = 1);
	$total_canceled_p_day = User::total_student_by_date($datef,$col = 'cancellation_date',$dayDif = 1);
	
	$teacher_dtls .= '<div class="clear" style="color:#FFF;font-size:13px;">
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#37A7F1;display:block;text-align:left;padding-left:2%;width:96%;">Daily stat of previous day</div>
							</div>
							<div class="clear" style="clear:both;line-height:0;"></div>
							';
							
	$teacher_dtls .= '<div class="clear" style="color:#000;font-size:11px;">
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;">'.$total_joined_p_day.' students joined</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;">'.$total_started_f_t_p_day.' students started F.T</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;">'.$total_converted_to_paying_p_day.' students converted to paying</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;">'.$total_payment_infosent_p_day.' students payment info sent</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;">'.$total_ft_expire_p_day.' students FT expired </div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;">'.$total_ft_expire_ow_p_day.' students FT expired w/o response</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;">'.$total_canceled_p_day.' students cancelled</div>
							</div>
							<div class="clear" style="clear:both;line-height:0;"></div>
							';
													
	// Weekly stat of previous 7 days
	$total_joined_p_7d = User::total_student_by_date($datef,$col = 'created_on',$dayDif = 7);
	$total_started_f_t_p_7d = User::total_student_by_date($datef,$col = 'first_response',$dayDif = 7);
	$total_converted_to_paying_p_7d = User::total_student_by_date($datef,$col = 'first_paid_date',$dayDif = 7);
	$total_payment_infosent_p_7d = User::total_student_by_date($datef,$col = 'payment_info_sent_date',$dayDif = 7);
	$total_ft_expire_p_7d = User::total_student_by_date($datef,$col = 'ft_expiration',$dayDif = 7);
	$total_ft_expire_ow_p_7d = User::total_student_by_date_from_logs_tbl($datef,$col = 'chenged_date',$status = 4 ,$dayDif = 7);
	$total_canceled_p_7d = User::total_student_by_date($datef,$col = 'cancellation_date',$dayDif = 7);
	
	$teacher_dtls .= '<div class="clear" style="color:#FFF;font-size:13px;">
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#37A7F1;display:block;text-align:left;padding-left:2%;width:96%;">Weekly stat of previous 7 days</div>
							</div>
							<div class="clear" style="clear:both;line-height:0;"></div>
							';
							
	$teacher_dtls .= '<div class="clear" style="color:#000;font-size:11px;">
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;">'.$total_joined_p_7d.' students joined</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;">'.$total_started_f_t_p_7d.' students started F.T</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;">'.$total_converted_to_paying_p_7d.' students converted to paying</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;">'.$total_payment_infosent_p_7d.' students payment info sent </div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;">'.$total_ft_expire_p_7d.' students  FT expired </div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;">'.$total_ft_expire_ow_p_7d.' students FT expired w/o response</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;">'.$total_canceled_p_7d.' students cancelled </div>
							</div>
							<div class="clear" style="clear:both;line-height:0;"></div>
							';
													
							 
$teacher_dtls .= ' </div>
</div>';
 
	$body = "<div style='border:3px solid #CCC;-moz-border-radius:5px;-webkit-border-radius:6px;border-radius:6px'>						
						<div style='vertical-align:top;color:#ff7f27;font-family:Arial Rounded MT,Arial,Helvetica,sans-serif;line-height:15px;font-size:24px;padding:20px 0 10px 15px;font-weight:bold'>
						Hi ".$user_name."
						</div>
						<div style='vertical-align:top;color:#333231;font-family:Arial,Helvetica,sans-serif;line-height:26px;font-size:12px;padding:0 0 15px 15px'>
						<p style=' background: none repeat scroll 0 0 #37A7F1;padding:0 141px 0 15px;
    font: bold 20px/38px arial;color:#EAEAF5;margin:0 22px 0 0;
    height: 40px;'><span>Aggregate Stats</span><span style='float:right'>&nbsp;&nbsp;Date:".$date."</span> </p>
						<p>".$student_dtls."</p>
						<p>&nbsp;</p>
						<p>".$teacher_dtls."</p>
						<p>Thanks!<br>Adam</p>
						</div>
						</div>
						";
										
		
		$to =$email;
		$subject = $subject;
		$messageText = '';
		$messageHtml = $body ;
		$fromEmail = 'info@domain.com';
		sendHtmlTextEmail($to, $subject, $messageText, $messageHtml , $fromEmail);
	}
	
	
