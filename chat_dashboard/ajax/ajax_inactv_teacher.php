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
	if($todo =='get_inactv_teacher_dtl')
	{
		$datef = User::get_japan_time_now();
		$all_user = User::all_user('teacher',$active_only = false);
		$tu = count($all_user);
			?>
					  <div class="clear" style="clear:both;line-height:0;"></div>
					  <?php	
					  
					 $avl_url_shown_ary = User::get_teacher_num_line_url_shown();
					 $total_users_in_array = User::total_users_in_array();
					 $mxStdnt_in_ary = User::getTeacherMaxStudent_in_array();
					 $pay_info_sent_ary = User::total_payment_Info_Sent_in_array();
					 $nupt_ary = User::new_user_paying_today_in_array($datef);
					 $cpt_ary = User::cancelations_of_payment_today_in_array($datef);
					 $churn_array = User::calculate_churn_in_array($datef);
					 $churn_array_paying = User::calculate_churn_paying_in_array($datef);
					 
					// echo '<pre>';
					// print_r($avl_url_shown_ary);
					// echo '</pre>';
					  				

$at_slots = 0;$at_t_cnv_rate_d_by = 0;$at_paying_user = 0; 	$at_fpd = 0;$at_churn = 0;$at_cpt = 0;
  for($i_user = 0;$i_user < count($all_user);$i_user++)
  {
   $teacher_id = $all_user[$i_user]['id']; 
   	?>
     <div class="clear" style="clear:both;"></div>
     <?php
	 $teacher_slots = '';//User::get_teacher_slotBy_id($all_user[$i_user]['id']);
	 				 
					 $t_in_ft = 0;$t_paying_user = 0;$t_act_stdnt = 0;$t_nupt = 0;$t_cpt = 0;$t_no_res=0;$t_cnv_rate = 0;$t_f_t_expired = 0;
					 $t_churn = 0;$t_churn_paying = 0;$t_fpd= 0;$t_f_t_expired_wo = 0;$t_r_link_sent = 0;$t_cancelled = 0;
					 $t_avl_url_shown = 0;$t_num_student = 0;$t_pay_info_sent = 0;$t_mx_student = 0;
					
					 $t_avl_url_shown = $avl_url_shown_ary[$teacher_id];
					 $t_ini_msg = isset($total_users_in_array[$teacher_id]['status'][1])?$total_users_in_array[$teacher_id]['status'][1]:0;
					 $t_nr_ini_msg_a2d = isset($total_users_in_array[$teacher_id]['status'][2])?$total_users_in_array[$teacher_id]['status'][2]:0;
					 $t_in_ft = isset($total_users_in_array[$teacher_id]['status'][3])?$total_users_in_array[$teacher_id]['status'][3]:0;
					 $t_f_t_expired_wo =isset($total_users_in_array[$teacher_id]['status'][4])?$total_users_in_array[$teacher_id]['status'][4]:0;
					 $t_f_t_expired = isset($total_users_in_array[$teacher_id]['status'][6])?$total_users_in_array[$teacher_id]['status'][6]:0;
					 $t_paying_user = isset($total_users_in_array[$teacher_id]['status'][7])?$total_users_in_array[$teacher_id]['status'][7]:0;
					 $t_cancelled = isset($total_users_in_array[$teacher_id]['status'][8])?$total_users_in_array[$teacher_id]['status'][8]:0;
					 $t_r_link_sent = isset($total_users_in_array[$teacher_id]['status'][9])?$total_users_in_array[$teacher_id]['status'][9]:0;
					  
					 $t_mx_student =  $mxStdnt_in_ary[$teacher_id];
					 $t_act_stdnt = $t_ini_msg+$t_in_ft+$t_paying_user;
					 
					 $t_num_student = $t_ini_msg +$t_nr_ini_msg_a2d+ $t_in_ft + $t_f_t_expired_wo+$t_f_t_expired+$t_paying_user+$t_cancelled+$t_r_link_sent;
					$t_pay_info_sent = 	isset($pay_info_sent_ary[$teacher_id])?$pay_info_sent_ary[$teacher_id]:0;
					
					$t_nupt = isset($nupt_ary[$teacher_id])?$nupt_ary[$teacher_id]:0;
					$t_cpt = isset($cpt_ary[$teacher_id])?$cpt_ary[$teacher_id]:0;
					
					
					$t_cnv_rate_d_by = $t_paying_user +$t_no_res +$t_f_t_expired ;
							if($t_cnv_rate_d_by <=0)
							$t_cnv_rate = 0;
							else
							$t_cnv_rate =   round( ($t_paying_user / $t_cnv_rate_d_by ), 2);
							
					   
					 //Churn per teacher so far = average number of days since the first paid date to today, or if cancelled since the first paid date to cancellation date
							//it's simply average length of students who stayed paying
							//if a student start subscribing 4/20 and cancelled on 5/2, churn is 30 days
							//if he hasn't cancelled as of today (5/6), churn is 34 days
							//$churn_array = User::calculate_churn($tslot_id,$datef);
							//$churn_array_paying = User::calculate_churn_paying($tslot_id,$datef);
							$t_churn =  isset($churn_array[$teacher_id])?$churn_array[$teacher_id]:0;
							$t_churn_paying =  isset($churn_array_paying[$teacher_id])?$churn_array_paying[$teacher_id]:0;
							//$churn_avg = ($paying_user > 0)?round($t_churn/$paying_user,2):0;
							//$churn_avg_paying = ($paying_user > 0)?round(($t_churn_paying+$t_churn)/$paying_user,2):0;
							
							$t_churn_avg = ($t_cancelled>0)?round($t_churn / ($t_cancelled)):0;
					       $t_churn_avg_paying_cancelled = ($t_paying_user+$t_cancelled>0)?round(($t_churn+$t_churn_paying ) / ($t_paying_user+$t_cancelled)):0;
							$t_churn_avg_paying  = ($t_paying_user>0)?round($t_churn_paying / ($t_paying_user)):0;
							
							$i =0;
							
							// all total 
							$at_slots = $at_slots + $i;
							$at_t_cnv_rate_d_by = $at_t_cnv_rate_d_by + $t_cnv_rate_d_by;
							$at_paying_user = $at_paying_user + $t_paying_user; 
							$at_cpt = $at_cpt + $t_cpt; 
							
							$at_fpd = $at_fpd + $t_fpd;
							$at_churn = $at_churn + $t_churn;
							?>
					<div class="dv_t _inr dv_ t_inr_<?php echo $all_user[$i_user]['id'];?>" style="color:#000;font-size:11px;">
							<div class="teachers_inner_hd hdbg cs1">
                            <div style="float:left;margin:1px 0;" class="t_face_<?php echo $all_user[$i_user]['id']?>"><?php  User::face_photo($all_user[$i_user]['id'],$thumb = true , $w = 21 , $h = false)?></div> 
                            <div style="fl oat:left;" class="t2" >
                            	<div style="line-height:1.4;height:15px;"><span><a style="text-decoration:none;color:#0E488F;color:#F32507;" class="" href="slot.php?teacher_id=<?php echo $all_user[$i_user]['id'];?>"><?php echo  substr( $all_user[$i_user]['name'],0,16)?></a></span><span class="ld_dtl" shown="no" id="ld_dtl_<?php echo $all_user[$i_user]['id'];?>" onclick="ld_dtl('show',<?php echo $all_user[$i_user]['id'];?>);"  style="float:right;"><img src="media/images/1402921928_plus.png" style="cursor:pointer;" id="t_l_<?php echo $all_user[$i_user]['id'];?>"  /></span></div>
                                <div style="line-height:1.4;height:15px;color:#656565;font-size:10px;">Total/Average</div>
                            </div>
                            </div>
							<div class="teachers_inner_hd hdbg"><?php echo $t_mx_student.'('.$t_act_stdnt.')';?></div>
                            <div class="teachers_inner_hd hdbg cs2"><?php echo $t_avl_url_shown;?></div>
                             <div class="teachers_inner_hd hdbg"><?php echo $t_num_student;?></div>
                            <div class="teachers_inner_hd hdbg cs2"><?php echo $t_ini_msg?></div>
							<div class="teachers_inner_hd hdbg cs5"><?php echo $t_in_ft?></div>
                             <div class="teachers_inner_hd hdbg cs2" ><?php echo $t_pay_info_sent;?></div>
                             <div class="teachers_inner_hd hdbg"><?php echo $t_f_t_expired;?></div>
                             <div class="teachers_inner_hd hdbg" style="width:65px;"><?php echo $t_f_t_expired_wo;?></div>
                              <div class="teachers_inner_hd hdbg cs2"><?php  echo $t_r_link_sent;?></div>
							<div class="teachers_inner_hd hdbg"><?php echo $t_paying_user?></div>
                            <div class="teachers_inner_hd hdbg"><?php echo $t_cancelled;?></div>
							<div class="teachers_inner_hd hdbg"><?php echo $t_act_stdnt?></div>
							<div class="teachers_inner_hd hdbg cs5"><?php echo $t_nupt?></div>
							<div class="teachers_inner_hd hdbg cs2"><?php echo $t_cpt?></div>
							<div class="teachers_inner_hd hdbg cs3" style="line-height:1.2;"><?php echo str_replace(array('Conversion','Resp Conv'),array('Cnv','R cnv'), User::teacher_conversion_rate($all_user[$i_user]['id']));?></div>
							<div class="teachers_inner_hd hdbg cs4" style="line-height:1.2;"><?php echo  User::teacher_true_conversion_rate($t_paying_user,$t_cancelled,$t_avl_url_shown,$all_user[$i_user]['id']);?></div>
                            <div class="teachers_inner_hd hdbg" style="width:90px;"><?php echo $t_churn_avg?></div>
							<div class="teachers_inner_hd hdbg" style="width:100px;"><?php echo $t_churn_avg_paying_cancelled;?></div>
                            <div class="teachers_inner_hd hdbg" style="width:90px;"><?php echo $t_churn_avg_paying;?></div>
                            </div>
                            <div class="clear" style="clear:both;line-height:0;"></div>
                            <div class="umore_dtl_<?php echo $all_user[$i_user]['id'];?>"></div>
							<?php
  }
 
 } 
 	
	if($todo =='get_inactv_teacher')
	{
		session_start();
		 $all_user = User::all_user('teacher',$active_only = false);
 //print_r($all_user);
  for($i_user = 0;$i_user < count($all_user);$i_user++)
  {
	  
	  $slot_count = 0;
	  $slot_count = User::count_total_slot($all_user[$i_user]['id']);
	  $student_count = User::count_total_active_student($all_user[$i_user]['id']);
	  $payingUsers = User::count_total_payingUsers($all_user[$i_user]['id']);;
	 $teachersDtl = User::getteacherDtl($all_user[$i_user]['id']);
	 //student status block data start
	 	$all_slots = User::imploded_teacher_slots($all_user[$i_user]['id']);
		
		$ini_msg = User::total_users_by_status_id_and_teacher_slots(1,$all_slots);// 1 = Initial message sent
		$no_responseA = User::total_users_by_status_id_and_teacher_slots(2,$all_slots); // 2 = No response to initial message after 2 days
		$in_ft = User::total_users_by_status_id_and_teacher_slots(3,$all_slots);// 3 = In F.T.
		$no_responseB = User::total_users_by_status_id_and_teacher_slots(4,$all_slots);// 4 = F.T. expired without response
		$f_t_expired = User::total_users_by_status_id_and_teacher_slots(6,$all_slots);// 6 = F.T. Expired
		$paying_user = User::total_users_by_status_id_and_teacher_slots(7,$all_slots); // 7 = paying
		$cancelled = User::total_users_by_status_id_and_teacher_slots(8,$all_slots);// 8 = Cancelled 
		$rls = User::total_users_by_status_id_and_teacher_slots(9,$all_slots);// 9 = Registration link sent 
	 //student status block data end
	 ?>
    <div id="usrTr_<?php echo $all_user[$i_user]['id'];?>" name="usrTr_<?php echo $all_user[$i_user]['id'];?>" class="rowGroup">
    <div class="cell u_sl"><?php echo $i_user+1;?><?php User::face_photo($all_user[$i_user]['id'],$thumb = true , $w = 30 , $h = false)?></div>
    <div class="u_dtl cell">
     <div class="u_info_name_<?php echo $all_user[$i_user]['id'];?>" style="color:#E2250A">Name:<?php echo $all_user[$i_user]['name'];?></div>
     <div class="u_info_email_<?php echo $all_user[$i_user]['id'];?>">Email:<?php echo $all_user[$i_user]['email'];?></div>
     <div class="u_info_user_<?php echo $all_user[$i_user]['id'];?>">Username:<?php echo $all_user[$i_user]['user'];?></div>
     <div class="u_info_lurl_<?php echo $all_user[$i_user]['id'];?>">Line Url:<?php echo $teachersDtl['line_url'];?>&nbsp;</div>
     <div class="u_info_status_<?php echo $all_user[$i_user]['id'];?>">Status:<?php if($all_user[$i_user]['status']=="Y") echo "Active"; else echo "Inactive";?></div>
     <div class="">Last Login:<?php echo $all_user[$i_user]['last_login'];?></div>
    </div>
   
    <div class="cell u_ts"><?php echo $slot_count;?></div>
    <div class="cell u_tsdnt"><?php echo $student_count;?></div>
        <!--<div class="cell u_pu"><?php echo $payingUsers;?></div>-->
      <!--<div class="cell u_nlus u_info_nlus_<?php echo $all_user[$i_user]['id'];?>"><?php echo $teachersDtl['num_line_url_shown'];?>&nbsp;</div>-->
     <div class="cell u_msdnt u_info_msdnt_<?php echo $all_user[$i_user]['id'];?>"><?php echo User::getTeacherMaxStudent($all_user[$i_user]['id']);//?>&nbsp;</div>
    <div class="cell u_nlus"><?php echo $teachersDtl['num_line_url_shown'];?></div>
    <!-- <div class="cell u_trsdnt u_info_trsdnt_<?php echo $all_user[$i_user]['id'];?>"><?php echo $teachersDtl['teacher_reported_students'];?>&nbsp; </div>-->
     <div class="cell u_crate"><?php echo User::teacher_conversion_rate($all_user[$i_user]['id']).'<br />'.User::teacher_true_conversion_rate($paying_user,$cancelled,$teachersDtl['num_line_url_shown'],$all_user[$i_user]['id']).'True Cnv';?></div>
     <div class="cell u_ims"><strong><?php echo $ini_msg;?></strong></div>
     <div class="cell u_nrim"><strong><?php echo $no_responseA;?></strong></div>
     <div class="cell u_inft"><strong><?php echo $in_ft;?></strong></div>
      <div class="cell u_ftewor"><strong><?php echo $no_responseB;?></strong></div>
      <div class="cell u_fte"><strong><?php echo $f_t_expired;?></strong></div>
	 <div class="cell u_payng"><strong><?php echo $paying_user;?></strong></div>
     <div class="cell u_cncl"><strong><?php echo $cancelled;?></strong></div>
          <div class="cell u_rls"><strong><?php echo $rls;?></strong></div>

     <div class="cell u_df1 u_info_df1_<?php echo $all_user[$i_user]['id'];?>"><?php User::teacher_days_off_1($teachersDtl['day_off_1']);?>&nbsp;</div>
     <div class="cell u_df2 u_info_df2_<?php echo $all_user[$i_user]['id'];?>"><?php User::teacher_days_off_2($teachersDtl['day_off_2']);?>&nbsp;</div>
      <div class="cell u_ac">
      <?php if($_SESSION['user_role'] == 'administrator') { ?><div style="margin-top:10px;"><img src="media/images/1389630938_edit.png" class="action_btn_img"  onclick="edit_user(<?php echo $all_user[$i_user]['id'];?>);" /> &nbsp;&nbsp;<img src="media/images/1389630919_cross-24.png" class="action_btn_img" onclick="delete_user(<?php echo $all_user[$i_user]['id'];?>);" /> </div><?php } ?>
     <div> <span class="v iew-slot"><a class="" href="slot.php?teacher_id=<?php echo $all_user[$i_user]['id'];?>">View/Manage Slot</a></span></div>
     <div> <span class="v iew-slot"><a class="" href="teacher_student.php?teacher_id=<?php echo $all_user[$i_user]['id'];?>">View All Students</a></span></div></div>
    </div>
     <div class="clear"></div>
	<div class="edit_single_user user_block_<?php echo $all_user[$i_user]['id'];?>" style="display:none;">
    <div id="edit_user_frm_dtl_html_<?php echo $all_user[$i_user]['id'];?>" ></div>
    </div>
   <div class="clear"></div>
	 <?php 
  }
	}
 
 
 
 ?>
	