<?php

/*======================================================================**
**                                                                           
** Main entry point.
** All PHP (mode) calls will go through this script.  
**                                                                           
**======================================================================*/
function send_mail_notify_max_students_per_slot($slot_id,$total_activeUser)
{
	$slotDtls = Student::slotDtl($slot_id);
	$activeUser = $total_activeUser;
	$max_stdnt = $slotDtls['max_students'];
	$strt_time = $slotDtls['start_time'];
	$end_time = $slotDtls['end_time'];
	$teacher_id = $slotDtls['id'];
	$teacher_name = $slotDtls['name'];
		
	$subject = "Maximum Students Limit Exceeded For Slot ".$strt_time." To ".$end_time."" ;
	$msg = "Maximum Students Limit Exceeded For Slot ".$strt_time." To ".$end_time."" ;
	
	$slotTime = "".$strt_time." - ".$end_time."" ;
	//prepare_and_send_email("Bidhan","bidhan.ssca@gmail.com",$subject,$msg); exit;
	
	$admin_only = true;
	
	$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
	
	$sql = "SELECT email,name FROM `user_master` WHERE `email` != '' AND `id` = '".$teacher_id."'";
	if($admin_only)	{
		$sql .=" OR role = 'administrator' ";
	}
	$q = $db->doQuery($sql);
	while($row = mysql_fetch_assoc($q)) {  
		$result[] = $row;
	   
	}
	
	for($i=0;$i<count($result);$i++)
	{
		prepare_and_send_email($result[$i]['name'],$result[$i]['email'],$subject,$msg,$total_activeUser,$max_stdnt,$slotTime,$teacher_name);
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

function prepare_and_send_email($user_name,$email,$subject,$msg,$total_activeUser,$max_stdnt,$slotTime,$teacher_name)
{
		$date = date('Y-m-d');
		
		$body = "<div style='border:3px solid #CCC;-moz-border-radius:5px;-webkit-border-radius:6px;border-radius:6px'><table width='100%' cellspacing='0' cellpadding='0' border='0' bgcolor='#FFFFFF' style='vertical-align:top;margin:0 auto'>
					<tbody><tr>
						<td valign='top' height='9' style='vertical-align:top;min-height:9px;line-height:0px'></td>
						</tr>
						<tr>
						<td style='vertical-align:top;color:#ff7f27;font-family:Arial Rounded MT,Arial,Helvetica,sans-serif;line-height:15px;font-size:24px;padding:20px 0 10px 15px;font-weight:bold'>
						Hi ".$user_name."
						</td>
						</tr>
						<tr>
						<td style='vertical-align:top;color:#333231;font-family:Arial,Helvetica,sans-serif;line-height:26px;font-size:12px;padding:0 0 15px 15px'>
						<p>".$msg."</p>
						<p> Teacher name : ".$teacher_name."</p>
						<p> Slot : ".$slotTime."</p>
						<p> Maximum Students For This Slot : ".$max_stdnt."</p>
						<p> Current Students In This Slot : ".$total_activeUser."</p>
						<p>Date:".$date."</p>
						</p>
						<p>Thanks!<br>
						Adam</p>
						</td>
						</tr>
						<tr>
						<td valign='top' height='9' style='vertical-align:top;min-height:9px;line-height:0px'>&nbsp;</td>
					</tr></tbody></table></div>";
										
		
		$to =$email;
		$subject = $subject;
		$messageText = '';
		$messageHtml = $body ;
		$fromEmail = 'info@domain.com';
		sendHtmlTextEmail($to, $subject, $messageText, $messageHtml , $fromEmail);
	}
	
	
function send_email_noti_new_paying_user($uid,$new_status_id)
{
	
	
	$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
	$sql= "SELECT unique_user_id ,  DATEDIFF( `cancellation_date` , `first_paid_date` ) AS df FROM users_list WHERE id = ".$uid." ";
	$q = $db->doQuery($sql);
	$r =mysql_fetch_assoc($q);
	$student_unique_id = $r['unique_user_id'];
	$df = $r['df']+1;
	
	$teacherDtl = Student::getStudentTeachersDtl($uid);
	if($new_status_id == 7)
	{
		$subject = "Student ID ".$student_unique_id." is converted to a paying user";		
		$msg = "Following user has converted to a paying user";
	}
	if($new_status_id == 8)
	{
		$subject = "Student ID ".$student_unique_id." cancelled the service";		
		$msg = "Following user cancelled the service<br />Student was subscribed for ".$df." days";
	}
	
	prepare_send_email_noti_new_paying_user($subject,$msg ,'Bidhan' , 'bidhan.ssca@gmail.com',$student_unique_id,$teacherDtl['name']);//exit;
	prepare_send_email_noti_new_paying_user($subject,$msg ,'Nir' , 'nir@okpanda.com',$student_unique_id,$teacherDtl['name']);
	prepare_send_email_noti_new_paying_user($subject,$msg ,'Yo' , 'imaeda@okpanda.com',$student_unique_id,$teacherDtl['name']);
	prepare_send_email_noti_new_paying_user($subject,$msg ,'Dada' , 'imelda@okpanda.com',$student_unique_id,$teacherDtl['name']);
	prepare_send_email_noti_new_paying_user($subject,$msg,'Adam' , 'adam@okpanda.com',$student_unique_id,$teacherDtl['name']);
	prepare_send_email_noti_new_paying_user($subject,$msg ,$teacherDtl['name'] , $teacherDtl['email'],$student_unique_id,$teacherDtl['name']);
	
	 
			
}

function prepare_send_email_noti_new_paying_user($subject,$msg ,$user_name , $email,$student_unique_id,$teacher_name)
{
	$date = date('Y-m-d');
	$body = "<div style='border:3px solid #CCC;-moz-border-radius:5px;-webkit-border-radius:6px;border-radius:6px'><table width='100%' cellspacing='0' cellpadding='0' border='0' bgcolor='#FFFFFF' style='vertical-align:top;margin:0 auto'>
					<tbody><tr>
						<td valign='top' height='9' style='vertical-align:top;min-height:9px;line-height:0px'></td>
						</tr>
						<tr>
						<td style='vertical-align:top;color:#ff7f27;font-family:Arial Rounded MT,Arial,Helvetica,sans-serif;line-height:15px;font-size:24px;padding:20px 0 10px 15px;font-weight:bold'>
						Hi ".$user_name."
						</td>
						</tr>
						<tr>
						<td style='vertical-align:top;color:#333231;font-family:Arial,Helvetica,sans-serif;line-height:26px;font-size:12px;padding:0 0 15px 15px'>
						<p>".$msg."</p>
						<p>Student ID: ".$student_unique_id."</p>
						<p>Teacher: ".$teacher_name."</p>
						<p>Date:".$date."</p>
						</p>
						<p>Thanks!<br>
						Adam</p>
						</td>
						</tr>
						<tr>
						<td valign='top' height='9' style='vertical-align:top;min-height:9px;line-height:0px'>&nbsp;</td>
					</tr></tbody></table></div>";
										
		
		$to = $email;
		$subject = $subject;
		$messageText = '';
		$messageHtml = $body ;
		$fromEmail = 'info@domain.com';
		sendHtmlTextEmail($to, $subject, $messageText, $messageHtml , $fromEmail);
}