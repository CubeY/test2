<?php

/*======================================================================**
**                                                                           
** Main entry point.
** All PHP (mode) calls will go through this script.  
**                                                                           
**======================================================================*/

// Turn on warnings
error_reporting(E_ALL);
ini_set('display_errors', 'on');
chdir(dirname(__FILE__));
require_once("../../../const.php");
//require_once(__DIR__ . "/css/style.css");
require_once(INC_PATH."common.php");
require_once(DIR_CLASSES . "class_db_this.php");
require_once("../php_classes/class_user.php");
require_once("../php_classes/class_student.php");
require_once("../php_classes/class_status.php");
date_default_timezone_set("Asia/Tokyo");

$date = date("Y-m-d H:i:s");
if($_SERVER['HTTP_HOST'] == '54.204.144.132')
{ 
	die; // stop email from test server 
}


	$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
	
	$teacher_dtls = '';
					
$all_user = User::all_user('teacher',$active_only = true);
$at_slots = 0;$at_t_cnv_rate_d_by = 0;$at_paying_user = 0; 	$at_fpd = 0;$at_churn = 0;$at_cpt = 0;
  for($i_user = 0;$i_user < count($all_user);$i_user++)
  {
    
	 $teacher_slots = User::imploded_teacher_slots($all_user[$i_user]['id']);
	 $payInfonotsentStudents = User::pay_info_not_sent_students_list($teacher_slots );
	// print_r($payInfonotsentStudents);
		//echo count($payInfonotsentStudents);
			$teacher_dtls='';			 
		if(count($payInfonotsentStudents) >0){
					for($i=0; $i < count($payInfonotsentStudents); $i++)
					  {
						 
							$teacher_dtls .= '<div class="clear" style="clear:both;line-height:0;"></div>
							<div class="clear" style="color:#000;font-size:11px;">
							<div class="cell u_st" style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:60px;text-align:left;padding-left:2%;">'.($i+1).'</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#F0F0F0;display:block;width:50px;text-align:left;padding-left:2%;">'.$payInfonotsentStudents[$i].'</div>
							</div>
							<div class="clear" style="clear:both;line-height:0;"></div>
							'; 
					  }
					  
	// prepare_send_email_noti_to_teachers('Bidhan','bidhan.ssca@gmail.com',$teacher_dtls);exit;				
	 prepare_send_email_noti_to_teachers($all_user[$i_user]['name'],$all_user[$i_user]['email'],$teacher_dtls);
	 		
		}
  }
  
 function prepare_send_email_noti_to_teachers($user_name,$email,$teacher_dtls)
 { 
 
 	$body = "<div style='border:3px solid #CCC;-moz-border-radius:5px;-webkit-border-radius:6px;border-radius:6px'>						
						<div style='vertical-align:top;color:#ff7f27;font-family:Arial Rounded MT,Arial,Helvetica,sans-serif;line-height:15px;font-size:24px;padding:20px 0 10px 15px;font-weight:bold'>
						Hi ".$user_name."
						</div>
						<div style='vertical-align:top;color:#333231;font-family:Arial,Helvetica,sans-serif;line-height:26px;font-size:12px;padding:0 0 15px 15px'>
						<p style='font: bold 20px/38px arial;color:#50505B;margin:0 22px 0 0;height: 40px;'>You have not sent Payment info to following students:</p>
						<p>&nbsp;</p>
						<p>".$teacher_dtls."</p>
						<p>Thanks!<br>Adam</p>
						</div>
						</div>
						";
										
		
		$to =$email;
		$subject = 'Send payment info ASAP';
		$messageText = '';
		$messageHtml = $body ;
		$fromEmail = 'info@domain.com';
		sendHtmlTextEmail($to, $subject, $messageText, $messageHtml , $fromEmail);
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