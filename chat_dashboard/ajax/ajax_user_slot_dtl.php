<?php
/*======================================================================**
**                                                                           
** Page:Ajax user , handel user ajax call 
** Created By : Bidhan
**                                                                           
**======================================================================*/
?>
<?php
// Turn on warnings
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require_once("../../../const.php");
//require_once(__DIR__ . "/css/style.css");
require_once(INC_PATH."common.php");
require_once(DIR_CLASSES . "class_db_this.php");
require_once("../php_classes/class_user.php");
require_once("../php_classes/class_student.php");
$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
?>
<style>
.hdbg2{background:#DFDFDF;}
</style>
<?php
if(isset($_POST['todo']))
$todo = $_POST['todo'];
	if($todo =='get_teacher_slot_dtl')
	{
		$teacher_id = $_REQUEST['teacher_id'];
	 $teacher_slots = User::get_teacher_slotBy_id($teacher_id);
	 $datef = User::get_japan_time_now();
					 
					 $t_ini_msg = 0;$t_in_ft = 0;$t_paying_user = 0;$t_act_stdnt = 0;$t_nupt = 0;$t_cpt = 0;$t_no_res=0;$t_cnv_rate = 0;$t_f_t_expired = 0;
					 $t_churn = 0;$t_churn_paying = 0;$t_fpd= 0;$t_f_t_expired_wo = 0;$t_r_link_sent = 0;$t_cancelled = 0;
					 $t_avl_url_shown = 0;$t_num_student = 0;$t_pay_info_sent = 0;$t_mx_student = 0;
					for($i=0; $i < count($teacher_slots); $i++)
					  {
						 if($teacher_slots[$i]['empty_slot']==1) { 
							$ts =  "NTS"; 
							}
							elseif($teacher_slots[$i]['coaching']==1) { 
							$ts =  "Coaching"; 
							}
							else {
								$ts =  $teacher_slots[$i]["start_time"].'&nbsp;To&nbsp;'.$teacher_slots[$i]["end_time"];
							}
							
							$t_avl_url_shown = User::get_teacher_num_line_url_shown($teacher_id);;
							
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
							
							$f_t_expired_wo = User::total_users_by_status_id_and_teacher_slots(4,$tslot_id);// 4 = F.T. expired without response
							$t_f_t_expired_wo = $t_f_t_expired_wo +$f_t_expired_wo;
							
							$r_link_sent = User::total_users_by_status_id_and_teacher_slots(9,$tslot_id);//  	9 	Registration link sent
							$t_r_link_sent = $t_r_link_sent +$r_link_sent;
							
							$cancelled = User::total_users_by_status_id_and_teacher_slots(8,$tslot_id);// 8 = Cancelled
							$t_cancelled = $t_cancelled +$cancelled;
							
							
							$paying_user = User::total_users_by_status_id_and_teacher_slots(7,$tslot_id); // 7 = paying
							$t_paying_user = $t_paying_user+$paying_user;
							//$cancell = User::total_users_by_status_id_and_teacher_slots(8,$tslot_id);// 6 = Cancelled 
							$act_stdnt =  $ini_msg + $in_ft + $paying_user;
							$t_act_stdnt = $t_act_stdnt+$act_stdnt;
							
							$mx_student = User::getTeacherMaxStudentBySlotId($tslot_id);
							$t_mx_student = $t_mx_student +$mx_student;
							
							
							
							$num_student = User::count_total_student_byslot($tslot_id);
							$t_num_student = $t_num_student +$num_student;
							
							$pay_info_sent = User::total_payment_Info_Sent_By_SlotId($tslot_id);
							$t_pay_info_sent = $t_pay_info_sent +$pay_info_sent;
							
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
							$churn_array_paying = User::calculate_churn_paying($tslot_id,$datef);
							$t_churn = $t_churn + $churn_array['total'];
							$t_churn_paying = $t_churn_paying + $churn_array_paying['total'];
							$churn_avg = ($cancelled > 0)?round($churn_array['total']/$cancelled,2):0;
							$churn_avg_paying_cancelled = ($paying_user+$cancelled > 0)?round(($churn_array_paying['total']+$churn_array['total'])/($paying_user+$cancelled),2):0;
							$churn_avg_paying = ($paying_user > 0)?round($churn_array_paying['total']/$paying_user,2):0;
							
							//$t_fpd = $t_fpd + $churn_array['t_fpd'];
							?>
							<div class="clear" style="clear:both;line-height:0;"></div>
							<div class="dv_t_inr dv_t_inr_<?php echo $teacher_id;?>" style="color:#000;font-size:11px;">
							<div class="teachers_inner_hd cs1" ><?php echo $ts?></div>
                            <div class="teachers_inner_hd"><?php echo $mx_student.'('.$act_stdnt.')';?></div>
                            <div class="teachers_inner_hd cs2"><?php echo $teacher_slots[$i]["num_line_url_shown"];?></div>
                            <div class="teachers_inner_hd"><?php echo $num_student;?></div>
							<div class="teachers_inner_hd cs2" ><?php echo $ini_msg?></div>
							<div class="teachers_inner_hd cs5" ><?php echo $in_ft?></div>
                            <div class="teachers_inner_hd cs2" ><?php echo $pay_info_sent;?></div>
                            <div class="teachers_inner_hd"><?php echo $f_t_expired;?></div>
                            <div class="teachers_inner_hd" style="width:65px;"><?php echo $f_t_expired_wo;?></div>
                            <div class="teachers_inner_hd cs2"><?php  echo $r_link_sent;?></div>
							<div class="teachers_inner_hd"  ><?php echo $paying_user?></div>
                            <div class="teachers_inner_hd"><?php echo $cancelled?></div>
							<div class="teachers_inner_hd" ><?php echo $act_stdnt?></div>
							<div class="teachers_inner_hd cs5" ><?php echo $nupt?></div>
							<div class="teachers_inner_hd cs2" ><?php echo $cpt?></div>
							<div class="teachers_inner_hd cs3"><?php //echo $cnv_rate?>-</div>
                            <div class="teachers_inner_hd cs4"><?php //echo $cnv_rate?>-</div>
							<div class="teachers_inner_hd" style="width:90px;"><?php echo $churn_avg?></div>
							<div class="teachers_inner_hd" style="width:100px;"><?php echo $churn_avg_paying_cancelled;?></div>
                            <div class="teachers_inner_hd" style="width:90px;"><?php echo $churn_avg_paying;?></div>
                            </div>
							<div class="clear" style="clear:both;line-height:0;"></div>
                            
                            
						<?php	
					  }
					  ?>
                      
                      <!--- PAST 30 Days -->
                             <?php
							// past 30 days record for each teachres
							$total_joined_p_30d_bt = User::total_student_by_date_and_teachers($datef,$col = 'created_on',$dayDif = 30,$teacher_id);
							//$total_ims_p_30d_bt = User::total_student_by_date_and_teachers_from_logs_tbl($datef,$col = 'created_on',$status = 1 ,$dayDif = 30,$teacher_id);
							$total_in_f_t_p_30d_bt = User::total_student_by_date_and_teachers($datef,$col = 'first_response',$dayDif = 30,$teacher_id);
							//$total_converted_to_paying_p_30d_bt = User::total_student_by_date($datef,$col = 'first_paid_date',$dayDif = 30);
							$total_payment_infosent_p_30d_bt = User::total_student_by_date_and_teachers($datef,$col = 'payment_info_sent_date',$dayDif = 30,$teacher_id);
							$total_ft_expire_p_30d_bt = User::total_student_by_date_and_teachers_from_logs_tbl($datef,$col = 'chenged_date',$status = 6 ,$dayDif = 30,$teacher_id);
							$total_ft_expire_ow_p_30d_bt = User::total_student_by_date_and_teachers_from_logs_tbl($datef,$col = 'chenged_date',$status = 4 ,$dayDif = 30,$teacher_id);
							$total_rlink_sent_p_30d_bt = User::total_student_by_date_and_teachers_from_logs_tbl($datef,$col = 'chenged_date',$status = 9 ,$dayDif = 30,$teacher_id);
							$total_paying_p_30d_bt = User::total_student_by_date_and_teachers($datef,$col = 'first_paid_date',$dayDif = 30,$teacher_id);
							$total_canceled_p_30d_bt = User::total_student_by_date_and_teachers($datef,$col = 'cancellation_date',$dayDif = 30,$teacher_id);
						    $total_active_p_30d_bt = $total_joined_p_30d_bt+$total_in_f_t_p_30d_bt+$total_paying_p_30d_bt;
						  ?>
                            <div class="dv_t_inr dv_t_inr_<?php echo $teacher_id;?>" style="color:#000;font-size:11px;">
							<div class="teachers_inner_hd hdbg2 cs1">Past 30 Days</div>
							<div class="teachers_inner_hd hdbg2"></div>
                            <div class="teachers_inner_hd hdbg2 cs2"></div>
                             <div class="teachers_inner_hd hdbg2"><?php echo $total_joined_p_30d_bt;?></div>
                            <div class="teachers_inner_hd hdbg2 cs2"><?php echo $total_joined_p_30d_bt?></div>
							<div class="teachers_inner_hd hdbg2 cs5"><?php echo $total_in_f_t_p_30d_bt?></div>
                             <div class="teachers_inner_hd hdbg2 cs2" ><?php echo $total_payment_infosent_p_30d_bt;?></div>
                             <div class="teachers_inner_hd hdbg2"><?php echo $total_ft_expire_p_30d_bt;?></div>
                             <div class="teachers_inner_hd hdbg2" style="width:65px;"><?php echo $total_ft_expire_ow_p_30d_bt;?></div>
                              <div class="teachers_inner_hd hdbg2 cs2"><?php echo $total_rlink_sent_p_30d_bt;?></div>
							<div class="teachers_inner_hd hdbg2"><?php echo $total_paying_p_30d_bt?></div>
                            <div class="teachers_inner_hd hdbg2"><?php echo $total_canceled_p_30d_bt;?></div>
							<div class="teachers_inner_hd hdbg2"><?php  echo $total_active_p_30d_bt;?></div>
							<div class="teachers_inner_hd hdbg2 cs5"><?php // echo $t_nupt?></div>
							<div class="teachers_inner_hd hdbg2 cs2"><?php // echo $t_cpt?></div>
							<div class="teachers_inner_hd hdbg2 cs3" style="line-height:1.2;"><?php //echo str_replace(array('Conversion','Resp Conv'),array('Cnv','R cnv'), User::teacher_conversion_rate($teacher_id));?></div>
							<div class="teachers_inner_hd hdbg2 cs4" style="line-height:1.2;"></div>
                            <div class="teachers_inner_hd hdbg2" style="width:90px;"><?php //echo $t_churn_avg?></div>
							<div class="teachers_inner_hd hdbg2" style="width:100px;"><?php //echo $t_churn_avg_paying;?></div>
                            <div class="teachers_inner_hd hdbg2" style="width:90px;"><?php //echo $churn_avg_paying;?></div>
                            </div>
							<div class="clear" style="clear:both;line-height:0;"></div>
							
                            <!--- Today's -->
                             <?php
							// Todays record for each teachres
							$total_joined_todays_bt = User::total_student_by_date_and_teachers($datef,$col = 'created_on',$dayDif = 0,$teacher_id);
							//$total_ims_p_30d_bt = User::total_student_by_date_and_teachers_from_logs_tbl($datef,$col = 'created_on',$status = 1 ,$dayDif = 30,$teacher_id);
							$total_in_f_t_todays_bt = User::total_student_by_date_and_teachers($datef,$col = 'first_response',$dayDif = 0,$teacher_id);
							//$total_converted_to_paying_p_30d_bt = User::total_student_by_date($datef,$col = 'first_paid_date',$dayDif = 30);
							$total_payment_infosent_todays_bt = User::total_student_by_date_and_teachers($datef,$col = 'payment_info_sent_date',$dayDif = 0,$teacher_id);
							$total_ft_expire_todays_bt = User::total_student_by_date_and_teachers_from_logs_tbl($datef,$col = 'chenged_date',$status = 6 ,$dayDif = 0,$teacher_id);
							$total_ft_expire_ow_todays_bt = User::total_student_by_date_and_teachers_from_logs_tbl($datef,$col = 'chenged_date',$status = 4 ,$dayDif = 0,$teacher_id);
							$total_rlink_sent_todays_bt = User::total_student_by_date_and_teachers_from_logs_tbl($datef,$col = 'chenged_date',$status = 9 ,$dayDif = 0,$teacher_id);
							$total_paying_todays_bt = User::total_student_by_date_and_teachers($datef,$col = 'first_paid_date',$dayDif = 0,$teacher_id);
							$total_canceled_todays_bt = User::total_student_by_date_and_teachers($datef,$col = 'cancellation_date',$dayDif = 0,$teacher_id);
						    $total_active_todays_bt = $total_joined_todays_bt+$total_in_f_t_todays_bt+$total_paying_todays_bt;
						  ?>
                          
                          
                             <div class="dv_t_inr dv_t_inr_<?php echo $teacher_id;?>" style="color:#000;font-size:11px;">
							<div class="teachers_inner_hd hdbg2 cs1">Today's</div>
							<div class="teachers_inner_hd hdbg2"></div>
                            <div class="teachers_inner_hd hdbg2 cs2"></div>
                             <div class="teachers_inner_hd hdbg2"><?php echo $total_joined_todays_bt;?></div>
                            <div class="teachers_inner_hd hdbg2 cs2"><?php echo $total_joined_todays_bt?></div>
							<div class="teachers_inner_hd hdbg2 cs5"><?php echo $total_in_f_t_todays_bt?></div>
                             <div class="teachers_inner_hd hdbg2 cs2" ><?php echo $total_payment_infosent_todays_bt;?></div>
                             <div class="teachers_inner_hd hdbg2"><?php echo $total_ft_expire_todays_bt;?></div>
                             <div class="teachers_inner_hd hdbg2" style="width:65px;"><?php echo $total_ft_expire_ow_todays_bt;?></div>
                              <div class="teachers_inner_hd hdbg2 cs2"><?php echo $total_rlink_sent_todays_bt;?></div>
							<div class="teachers_inner_hd hdbg2"><?php echo $total_paying_todays_bt?></div>
                            <div class="teachers_inner_hd hdbg2"><?php echo $total_canceled_todays_bt;?></div>
							<div class="teachers_inner_hd hdbg2"><?php  echo $total_active_todays_bt;?></div>
							<div class="teachers_inner_hd hdbg2 cs5"><?php  echo $t_nupt?></div>
							<div class="teachers_inner_hd hdbg2 cs2"><?php  echo $t_cpt?></div>
							<div class="teachers_inner_hd hdbg2 cs3" style="line-height:1.2;"><?php //echo str_replace(array('Conversion','Resp Conv'),array('Cnv','R cnv'), User::teacher_conversion_rate($teacher_id));?></div>
							<div class="teachers_inner_hd hdbg2 cs4" style="line-height:1.2;"></div>
                            <div class="teachers_inner_hd hdbg2" style="width:90px;"><?php //echo $t_churn_avg?></div>
							<div class="teachers_inner_hd hdbg2" style="width:100px;"><?php //echo $t_churn_avg_paying;?></div>
                            <div class="teachers_inner_hd hdbg2" style="width:90px;"><?php //echo $churn_avg_paying;?></div>
                            </div>
							<div class="clear" style="clear:both;line-height:0;"></div>
                            
                            <div class="clear" style="background:#37a7f1;height:2px;"></div><?php
	}
	
	