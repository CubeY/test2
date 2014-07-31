<?php
/*======================================================================**
**                                                                           
** Page:Status
** Created By : Bidhan
**                                                                           
**======================================================================*/
$current_pname = 'index';
include 'inc/inc.php';
Auth::checkAuth();
include 'inc/header.php';
//print_r($_SESSION);
?>
<script src="./js/jquery.nicescroll.min.js"></script>
<?php 
if($_SESSION['user_role'] != 'administrator' && $_SESSION['user_role'] != 'viewer') { 	echo '<h3>You are restricted for this page!</h3>';exit;} 

/*//header('Location:teachers.php');
if(isset($_POST['todo']) == 'add_new_status')
{
	Status::add_new_status();
}*/

?>
<script type="text/javascript">
function showHideTSummary(t,tu)
{
	if(t==0)
	{
		if($('.top_sh').attr("alt") == 'hide')
		{
			$('.dv_t_inr').hide();
			$('.top_sh').attr("alt","show");
			$('.al_icon img').attr("src","media/images/1400083894_plus-sign.png");
		}
		else
		{
			$('.dv_t_inr').show();
			$('.top_sh').attr("alt","hide");
			$('.al_icon img').attr("src","media/images/1400083886_minus-sign.png");
		}
		/*$('.dv_t_inr').toggle();
		
		
		if($('.sp_btn'+t).is(':visible'))
		{	//alert(1);
		$('.span_icon_'+t+' img').attr("src","media/images/1400083886_minus-sign.png");}
		else
		{
			//alert(2);
			$('.span_icon_'+t+' img').attr("src","media/images/1400083894_plus-sign.png");
		}*/
		
	}
	else
	{
		$('.dv_t_inr_'+t).toggle();
		if($('.dv_t_inr_'+t).is(':visible'))
		{	//alert(1);
		$('.span_icon_'+t+' img').attr("src","media/images/1400083886_minus-sign.png");}
		else
		{
			//alert(2);
			$('.span_icon_'+t+' img').attr("src","media/images/1400083894_plus-sign.png");
		}
	}
}



</script>
<div class="menu_bar"><?php require_once("menu.php");?></div>
<script type="text/javascript">
$(document).ready(function(e) {
    $('.cls_past_data').animate({ scrollTop: $('.cls_past_data').offset().top+200 }, 'slow');
	$(".cls_past_data").niceScroll();
});
</script>
<style>.dbg{background:#09C;color:#FFF;}</style>
<div class="home_dsh_brd">
<?php
  $weighted_active_students = round(User::count_active_student_by_life_status('unknown') + User::count_active_student_by_life_status('working') + 0.3 * User::count_active_student_by_life_status('high_school') + 0.3 * User::count_active_student_by_life_status('college'));
  ?>
<div class="bx">
<div class="bx_head">Overall</div>
<div class="bx_rw" style="color:#090;">Total spots: <?php  echo  User::sum_of_each_teachers_max_capacity()?></div>
<div class="bx_rw" style="color:#F00;">Active Students : <?php  echo  $active_students = User::total_active_student();?></div>
<div class="bx_rw" style="color:#F00;">Weighted Active Students : <?php  echo  $weighted_active_students;?></div>
<div class="bx_rw" style="color:#090;">Available spots: <?php  echo  User::sum_of_each_teachers_max_capacity() - $weighted_active_students;?></div>
</div>
<div class="bx">
<div class="bx_head">Life statuses</div>
<div class="bx_rw">Unknown  : <?php  echo  User::count_total_student_by_life_status('unknown');?>&nbsp;(Active:<?php  echo  User::count_total_student_by_life_status('unknown',$active_only = true);?>)</div>
<div class="bx_rw">High School: <?php  echo  User::count_total_student_by_life_status('high_school');?>&nbsp;(Active:<?php  echo  User::count_total_student_by_life_status('high_school',$active_only = true);?>)</div>
<div class="bx_rw">College : <?php  echo User::count_total_student_by_life_status('college');?>&nbsp;(Active:<?php  echo  User::count_total_student_by_life_status('college',$active_only = true);?>)</div>
<div class="bx_rw">Working : <?php  echo User::count_total_student_by_life_status('working');?>&nbsp;(Active:<?php  echo  User::count_total_student_by_life_status('working',$active_only = true);?>)</div>
</div>
<div class="bx">
<div class="bx_head">Engagement level</div>
<div class="bx_rw">Unknown  : <?php  echo  User::count_total_student_by_engagement_level(0);?>&nbsp;(Active:<?php  echo  User::count_total_student_by_engagement_level(0,$active_only = true);?>)</div>
<div class="bx_rw">Low  : <?php  echo  User::count_total_student_by_engagement_level(1);?>&nbsp;(Active:<?php  echo  User::count_total_student_by_engagement_level(1,$active_only = true);?>)</div>
<div class="bx_rw">Medium : <?php  echo  User::count_total_student_by_engagement_level(5);?>&nbsp;(Active:<?php  echo  User::count_total_student_by_engagement_level(5,$active_only = true);?>)</div>
<div class="bx_rw">High : <?php  echo User::count_total_student_by_engagement_level(10);?>&nbsp;(Active:<?php  echo  User::count_total_student_by_engagement_level(10,$active_only = true);?>)</div>
</div>
</div>
 <div class="clear" style="height:25px;"></div>
 
<div style="border:1px solid #999;width:1335px;">

	<div >
         <div class="ov_hd ov_hd_top dbg"></div>
         <div class="ov_hd ov_hd_top dbg"><strong>LINE URL shown</strong></div>
         <div class="ov_hd ov_hd_top dbg"><strong>Joined</strong></div>
         <div class="ov_hd ov_hd_top dbg"><strong>Joined F.T</strong></div>
         <div class="ov_hd ov_hd_top dbg"><strong>Current Paying Users</strong></div>
         <div class="ov_hd ov_hd_top dbg"><strong>Payment info (will be) sent</strong></div>
         <div class="ov_hd ov_hd_top dbg"><strong>FT  (wiil be) expired</strong></div>
         <div class="ov_hd ov_hd_top dbg"><strong>Registration link sent</strong></div>
         <div class="ov_hd ov_hd_top dbg"><strong>FT expired w/o response</strong></div>
         <div class="ov_hd ov_hd_top dbg"><strong>Cancelled</strong></div>
     </div>
     <div style="height:190px;width:1325px;overflow:scroll;overflow-x: hidden;" class="cls_past_data">
     <?php
     $datef = User::get_japan_time_now();
	 $dateUpto = date('Y-m-d', strtotime($datef . ' - 15 day'));
	 $dataByDate = User::total_student_each_date($dateUpto);
	 $dataByDate = json_decode($dataByDate);
	 
	 $dataByDateCountLUS = count($dataByDate->line_url_shown);
	 $iLUS = $dataByDateCountLUS - 1;
	 
	 $dataByDateCountJoined = count($dataByDate->created_on);
	 $iJoined = $dataByDateCountJoined - 1;
	 
	 $dataByDateCountJoinedFT = count($dataByDate->first_response);
	 $iJoinedFT = $dataByDateCountJoinedFT - 1;
	 
	 $dataByDateCountCnvrtedToPaying = count($dataByDate->first_paid_date);
	 $iCnvrtedToPaying = $dataByDateCountCnvrtedToPaying - 1;
	
	 $dataByDateCountpInfoSent = count($dataByDate->payment_info_sent_date);
	 $ipInfoSent = $dataByDateCountpInfoSent - 1;
	 
	 $dataByDateFTexpired = count($dataByDate->ft_expiration);
	 $iFTexpired = $dataByDateFTexpired - 1;
	 
	 $dataByDateRLsent= count($dataByDate->reg_link_sent);
	 $iRLsent = $dataByDateRLsent - 1;
	 
	 $dataByDateFTexpiredWOR = count($dataByDate->ft_exp_ow_res);
	 $iFTexpiredWOR = $dataByDateFTexpiredWOR - 1;
	 
	 
	 $dataByDateCancelled= count($dataByDate->cancellation_date);
	 $iCancelled = $dataByDateCancelled - 1;
				
	//echo '<pre>'; print_r($dataByDate);echo '</pre>'; 
	 for($past_i = 15;$past_i > 0;$past_i--)
	 {
		
		 $pastdate =  date('Y-m-d', strtotime($datef . ' - '.$past_i.' day'));
		 $day = date('D',strtotime($pastdate));
		  if($day =='Sat' || $day =='Sun') $style ="plg"; else $style = "";
	 ?>
     <div style="color:#000;font-size:11px;display:table;" class="clear <?php echo $style;?>">
        <div class="ov_hd"><?php echo $pastdate ; ?></div>
        <div class="ov_hd"><?php if($iLUS < 0) echo 0;elseif($pastdate == $dataByDate->line_url_shown[$iLUS]->shown_date) {echo $dataByDate->line_url_shown[$iLUS]->total; $iLUS--;}else echo 0; ?></div>
        <div class="ov_hd"><?php if($iJoined < 0) echo 0;elseif($pastdate == $dataByDate->created_on[$iJoined]->created_on) {echo $dataByDate->created_on[$iJoined]->total; $iJoined--;}else echo 0; ?></div>
        <div class="ov_hd"><?php if($iJoinedFT < 0) echo 0;elseif($pastdate == $dataByDate->first_response[$iJoinedFT]->first_response) {echo $dataByDate->first_response[$iJoinedFT]->total; $iJoinedFT--;}else echo 0; ?></div>
        <div class="ov_hd"><?php if($iCnvrtedToPaying < 0) echo 0;elseif($pastdate == $dataByDate->first_paid_date[$iCnvrtedToPaying]->first_paid_date) {echo $dataByDate->first_paid_date[$iCnvrtedToPaying]->total; $iCnvrtedToPaying--;}else echo 0; ?></div>
        <div class="ov_hd"><?php if($ipInfoSent < 0) echo 0;elseif($pastdate == $dataByDate->payment_info_sent_date[$ipInfoSent]->payment_info_sent_date) {echo $dataByDate->payment_info_sent_date[$ipInfoSent]->total; $ipInfoSent--;}else echo 0; ?></div>
        <div class="ov_hd"><?php if($iFTexpired < 0) echo 0;elseif($pastdate == $dataByDate->ft_expiration[$iFTexpired]->chenged_date) {echo $dataByDate->ft_expiration[$iFTexpired]->total; $iFTexpired--;}else echo 0; ?></div>
        <div class="ov_hd"><?php if($iRLsent < 0) echo 0;elseif($pastdate == $dataByDate->reg_link_sent[$iRLsent]->chenged_date) {echo $dataByDate->reg_link_sent[$iRLsent]->total; $iRLsent--;}else echo 0; ?></div>
        <div class="ov_hd"><?php if($iFTexpiredWOR < 0) echo 0;elseif($pastdate == $dataByDate->ft_exp_ow_res[$iFTexpiredWOR]->chenged_date) {echo $dataByDate->ft_exp_ow_res[$iFTexpiredWOR]->total; $iFTexpiredWOR--;}else echo 0; ?></div>
        <div class="ov_hd"><?php if($iCancelled < 0) echo 0;elseif($pastdate == $dataByDate->cancellation_date[$iCancelled]->cancellation_date) {echo $dataByDate->cancellation_date[$iCancelled]->total; $iCancelled--;}else echo 0; ?></div>
      </div>
      <?php } ?>
      </div>
      <?php
		// current day
		$total_lus_p_cd = User::total_student_by_date_lus($datef,$col = '',$dayDif = 0);
		$total_joined_p_cd = User::total_student_by_date($datef,$col = 'created_on',$dayDif = 0);
		$total_started_f_t_p_cd = User::total_student_by_date($datef,$col = 'first_response',$dayDif = 0);
		$total_converted_to_paying_p_cd = User::total_student_by_date($datef,$col = 'first_paid_date',$dayDif = 0);
		$total_payment_infosent_p_cd = User::total_student_by_date($datef,$col = 'payment_info_sent_date',$dayDif = 0);
		$total_ft_expire_p_cd = User::total_student_by_date_from_logs_tbl($datef,$col = 'chenged_date',$status = 6 ,$dayDif = 0);
		$total_rlink_sent_p_cd = User::total_student_by_date_from_logs_tbl($datef,$col = 'chenged_date',$status = 9 ,$dayDif = 0);
		$total_ft_expire_ow_p_cd = User::total_student_by_date_from_logs_tbl($datef,$col = 'chenged_date',$status = 4 ,$dayDif = 0);
		$total_canceled_p_cd = User::total_student_by_date($datef,$col = 'cancellation_date',$dayDif = 0);
      ?>
      <div style="color:#000;font-size:11px;" class="clear td-d">
        <div class="ov_hd"><?php echo date('Y-m-d', strtotime($datef)); ?></div>
        <div class="ov_hd"><?php echo $total_lus_p_cd;?></div>
        <div class="ov_hd"><?php echo $total_joined_p_cd;?></div>
        <div class="ov_hd"><?php echo $total_started_f_t_p_cd;?></div>
        <div class="ov_hd"><?php echo $total_converted_to_paying_p_cd;?></div>
        <div class="ov_hd"><?php echo $total_payment_infosent_p_cd;?></div>
        <div class="ov_hd"><?php echo $total_ft_expire_p_cd;?></div>
        <div class="ov_hd"><?php echo $total_rlink_sent_p_cd;?></div>
        <div class="ov_hd"><?php echo $total_ft_expire_ow_p_cd;?></div>
        <div class="ov_hd"><?php echo $total_canceled_p_cd;?></div>
      </div>
       <div class="clear"></div>
      <?php
		// Weekly stat of previous 7 days
		$total_lus_p_7d = User::total_student_by_date_lus($datef,$col = '',$dayDif = 7);
		$total_joined_p_7d = User::total_student_by_date($datef,$col = 'created_on',$dayDif = 7);
		$total_started_f_t_p_7d = User::total_student_by_date($datef,$col = 'first_response',$dayDif = 7);
		$total_converted_to_paying_p_7d = User::total_student_by_date($datef,$col = 'first_paid_date',$dayDif = 7);
		$total_payment_infosent_p_7d = User::total_student_by_date($datef,$col = 'payment_info_sent_date',$dayDif = 7);
		$total_ft_expire_p_7d = User::total_student_by_date_from_logs_tbl($datef,$col = 'chenged_date',$status = 6 ,$dayDif = 7);
		$total_rlink_sent_p_7d = User::total_student_by_date_from_logs_tbl($datef,$col = 'chenged_date',$status = 9 ,$dayDif = 7);
		$total_ft_expire_ow_p_7d = User::total_student_by_date_from_logs_tbl($datef,$col = 'chenged_date',$status = 4 ,$dayDif = 7);
		$total_canceled_p_7d = User::total_student_by_date($datef,$col = 'cancellation_date',$dayDif = 7);
      ?>
       <div style="color:#000;font-size:11px;" class="clear">
        <div class="ov_hd" style="">Past 7 days</div>
        <div class="ov_hd"><?php echo $total_lus_p_7d;?></div>
        <div class="ov_hd"><?php echo $total_joined_p_7d;?></div>
        <div class="ov_hd"><?php echo $total_started_f_t_p_7d;?></div>
        <div class="ov_hd"><?php echo $total_converted_to_paying_p_7d;?></div>
        <div class="ov_hd"><?php echo $total_payment_infosent_p_7d;?></div>
        <div class="ov_hd"><?php echo $total_ft_expire_p_7d;?></div>
        <div class="ov_hd"><?php echo $total_rlink_sent_p_7d;?></div>
        <div class="ov_hd"><?php echo $total_ft_expire_ow_p_7d;?></div>
        <div class="ov_hd"><?php echo $total_canceled_p_7d;?></div>
      </div>
      <?php
		// Weekly stat of previous 30 days
		$total_lus_p_30d = User::total_student_by_date_lus($datef,$col = '',$dayDif = 30);
		$total_joined_p_30d = User::total_student_by_date($datef,$col = 'created_on',$dayDif = 30);
		$total_started_f_t_p_30d = User::total_student_by_date($datef,$col = 'first_response',$dayDif = 30);
		$total_converted_to_paying_p_30d = User::total_student_by_date($datef,$col = 'first_paid_date',$dayDif = 30);
		$total_payment_infosent_p_30d = User::total_student_by_date($datef,$col = 'payment_info_sent_date',$dayDif = 30);
		$total_ft_expire_p_30d = User::total_student_by_date_from_logs_tbl($datef,$col = 'chenged_date',$status = 6 ,$dayDif = 30);
		$total_rlink_sent_p_30d = User::total_student_by_date_from_logs_tbl($datef,$col = 'chenged_date',$status = 9 ,$dayDif = 30);
		$total_ft_expire_ow_p_30d = User::total_student_by_date_from_logs_tbl($datef,$col = 'chenged_date',$status = 4 ,$dayDif = 30);
		$total_canceled_p_30d = User::total_student_by_date($datef,$col = 'cancellation_date',$dayDif = 30);
      ?>
       <div style="color:#000;font-size:11px;" class="clear ">
        <div class="ov_hd" style="">Past 30 days</div>
        <div class="ov_hd"><?php echo $total_lus_p_30d;?></div>
        <div class="ov_hd"><?php echo $total_joined_p_30d;?></div>
        <div class="ov_hd"><?php echo $total_started_f_t_p_30d;?></div>
        <div class="ov_hd"><?php echo $total_converted_to_paying_p_30d;?></div>
        <div class="ov_hd"><?php echo $total_payment_infosent_p_30d;?></div>
        <div class="ov_hd"><?php echo $total_ft_expire_p_30d;?></div>
        <div class="ov_hd"><?php echo $total_rlink_sent_p_30d;?></div>
        <div class="ov_hd"><?php echo $total_ft_expire_ow_p_30d;?></div>
        <div class="ov_hd"><?php echo $total_canceled_p_30d;?></div>
      </div>
      
      <?php 
	    // overall
		$total_lus = User::total_student_by_date_lus($datef,$col = '',$dayDif = -1);
		$total_joined = Student::all_studentCount($col = false);
		$total_started_f_t = Student::all_studentCount($col = 'first_response');
		$total_converted_to_paying = Student::all_studentCount($col = 'first_paid_date');
		$total_payment_infosent = Student::all_studentCount($col = 'payment_info_sent');
		$total_ft_expire = Student::all_studentCount($col = false,$status = 6);
		$total_rlink_sent = Student::all_studentCount($col = false,$status = 9 );
		$total_ft_expire_ow = Student::all_studentCount($col = false,$status = 4 );
		$total_canceled = Student::all_studentCount($col = false,$status = 8 );
		
		?>
      <div style="color:#000;font-size:11px;" class="clear ov-d">
        <div class="ov_hd">Over all (cumulative)</div>
        <div class="ov_hd"><?php echo $total_lus;?></div>
        <div class="ov_hd"><?php echo $total_joined;?></div>
        <div class="ov_hd"><?php echo $total_started_f_t;?></div>
        <div class="ov_hd"><?php echo User::total_users_by_status_id(7);?></div>
        <div class="ov_hd"><?php echo $total_payment_infosent;?></div>
        <div class="ov_hd"><?php echo $total_ft_expire;?></div>
        <div class="ov_hd"><?php echo $total_rlink_sent;?></div>
        <div class="ov_hd"><?php echo $total_ft_expire_ow;?></div>
        <div class="ov_hd"><?php echo $total_canceled;?></div>
      </div>
     <?php /*?> <?php
	 $datef = User::get_japan_time_now();
	 $f_dataByDate = User::total_student_each_future_date($datef);
	 $f_dataByDate = json_decode($f_dataByDate);
	
	 
	//echo '<pre>';  print_r($f_dataByDate); echo '</pre>';
	
	 $f_dataByDate_exp_wo = User::total_student_each_future_date_exp_wo($datef);
	$f_dataByDate_exp_wo = json_decode($f_dataByDate_exp_wo);
	// echo '<pre>'; print_r( $f_dataByDate_exp_wo->exp_wo); echo '</pre>';
     $f_iFTexpired = 0;
	 $f_pInfoSent = 0;
	 $f_iexp_wo = 0;
	 $n7pnsent = 0;$n7ftexp = 0;$n7ftexp_wo = 0;
	 for($past_i =1;$past_i <=7;$past_i++)
	 {
		 if($past_i > 5 ) $style ="plg"; else $style = "";
	 ?>
     <div style="color:#000;font-size:11px;" class="clear <?php echo $style;?>">
        <div class="ov_hd"><?php echo $futureDate = date('Y-m-d', strtotime($datef . ' + '.$past_i.' day')); ?></div>
        <div class="ov_hd">-</div>
        <div class="ov_hd">-</div>
        <div class="ov_hd">-</div>
        <div class="ov_hd"><?php if(date('Y-m-d', strtotime($futureDate . ' + 1 day')) == $f_dataByDate->ft_expiration[$f_pInfoSent]->ft_expiration) {echo $f_dataByDate->ft_expiration[$f_pInfoSent]->total; $f_pInfoSent++;$n7pnsent =  $n7pnsent + $f_dataByDate->ft_expiration[$f_pInfoSent]->total;}else echo 0; ?></div>
        <div class="ov_hd"><?php if($futureDate == $f_dataByDate->ft_expiration[$f_iFTexpired]->ft_expiration) {echo $f_dataByDate->ft_expiration[$f_iFTexpired]->total; $n7ftexp =  $n7ftexp + $f_dataByDate->ft_expiration[$f_pInfoSent]->total;$f_iFTexpired++;}else echo 0; ?></div>
        <div class="ov_hd">-</div>
        <div class="ov_hd"><?php if($f_iexp_wo >= count($f_dataByDate_exp_wo->exp_wo)) echo 0; elseif(date('Y-m-d', strtotime($futureDate . ' - 8 day')) == $f_dataByDate_exp_wo->exp_wo[$f_iexp_wo]->exp_wo) {echo $f_dataByDate_exp_wo->exp_wo[$f_iexp_wo]->total;$n7ftexp_wo =  $n7ftexp_wo + $f_dataByDate_exp_wo->exp_wo[$f_iexp_wo]->total; $f_iexp_wo++;}else echo 0; ?></div>
        <div class="ov_hd">-</div>
      </div>
      <?php } ?>
      
      <div style="color:#000;font-size:11px;" class="clear">
        <div class="ov_hd">Next 7 days</div>
        <div class="ov_hd">-</div>
        <div class="ov_hd">-</div>
        <div class="ov_hd">-</div>
        <div class="ov_hd"><?php echo $n7pnsent;?></div>
        <div class="ov_hd"><?php echo $n7ftexp;?></div>
        <div class="ov_hd">-</div>
        <div class="ov_hd"><?php echo $n7ftexp_wo;?></div>
        <div class="ov_hd">-</div>
      </div>
      <?php
      
	  ?>
      <div style="color:#000;font-size:11px;" class="clear">
        <div class="ov_hd">Next 30 days</div>
        <div class="ov_hd">-</div>
        <div class="ov_hd">-</div>
        <div class="ov_hd">-</div>
        <div class="ov_hd">0</div>
        <div class="ov_hd">0</div>
        <div class="ov_hd">-</div>
        <div class="ov_hd">0</div>
        <div class="ov_hd">-</div>
      </div><?php */?>
   <div class="clear"></div>
</div>
<div class="clear">&nbsp;</div>
<?php //include('retention_table.php');?>
<?php

$all_user = User::all_user('teacher',$active_only = true);
$tu = count($all_user);
?>
<div class="clear" style="clear:both;line-height:0;"></div>
					<div class="list_user" style="margin-top:50px;width:1335px;">
					<div class="table_view" style="display:table;width:100%;border:1px solid #999;">
							<div class="ov_head" onclick="showHideTSummary(0,<?php echo $tu;?>)"  >Teacher Summary<span class="ss-all" onclick="show_inactive_teacher('show');" >Show All<img src="media/images/loading-s.gif" style="display:none;" class="ss-all-l-img" /></span><span class="sp_btn al_icon" style="display:none;"><img src="media/images/1400083886_minus-sign.png"  class="top_sh" alt="hide"/></span></div>

<div class="dv_t _inr nav ">
					 <div class="teachers_inner_hd cs1 dbg " >&nbsp;</div>
					 <div class="teachers_inner_hd dbg" title="Max active"><strong>MX(Actv)</strong></div>
                     <div class="teachers_inner_hd cs2 dbg" title="Line url shown"><strong>LUS</strong></div>
                     <div class="teachers_inner_hd dbg" title="Joined"><strong>Joined</strong></div>
                     <div class="teachers_inner_hd cs2 dbg" title="Initial message sent"><strong>IMS</strong></div>
					 <div class="teachers_inner_hd dbg cs5" title="In F.T."><strong>In F.T.</strong></div>
                     <div class="teachers_inner_hd cs2 dbg" title="Payment info sent"><strong>PIS</strong></div>
                     <div class="teachers_inner_hd dbg" title="F.T. expired"><strong>FT Exp</strong></div>
                     <div class="teachers_inner_hd dbg" style="width:65px;" title="F.T. expired without response"><strong>FT Exp W/O</strong></div>
					 <div class="teachers_inner_hd cs2 dbg" title="Registration link sent"><strong>RLS</strong></div>
					 <div class="teachers_inner_hd dbg" title="Paying"><strong>Paying</strong></div>
					 <div class="teachers_inner_hd dbg" title="Cancelled"><strong>Cancelled</strong></div>
                     <div class="teachers_inner_hd dbg" title="Active"><strong>Active</strong></div>
                     
					 <div class="teachers_inner_hd cs5 dbg" title="Number of paying user today"><strong>NPUT</strong></div>
					 <div class="teachers_inner_hd cs2 dbg" title="Cancellation of payment today"><strong>CPT</strong></div>
					 <div class="teachers_inner_hd cs3 dbg" title="Conversion Rate"><strong>Cnv Rate</strong></div>
                     <div class="teachers_inner_hd cs4 dbg" title="True conversion"><strong>True Cnv</strong></div>
					 <div class="teachers_inner_hd dbg" style="width:90px;line-height:1.2;" title="Average subscription length cancelled"><strong>Avg. Sub. length cancelled</strong></div>
                     <div class="teachers_inner_hd dbg" style="width:100px;line-height:1.2;" title="Average subscription length cancelled+paying"><strong>Avg. Sub. length cancelled+paying</strong></div>
                     <div class="teachers_inner_hd dbg" style="width:90px;line-height:1.2;" title="Average subscription length paying"><strong>Avg. Sub. length paying</strong></div>
					 </div>
					  <div class="clear" style="clear:both;line-height:0;"></div>
					  <div class="dv_inactv_tchr"></div>
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
					// print_r($churn_array);
					// echo '</pre>';
					  				

$at_slots = 0;$at_t_cnv_rate_d_by = 0;$at_paying_user = 0; 	$at_fpd = 0;$at_churn = 0;$at_cpt = 0;$at_cancelled =0;$at_churn_paying = 0;
$at_churn_paying_app_funnel = 0;$at_churn_app_funnel =0;
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
							//$at_churn = $at_churn + $t_churn;
							//$at_churn_paying = $at_churn_paying +$t_churn_paying;
							$at_cancelled = $at_cancelled + $t_cancelled;
							
							
							?>
							
					<div class="dv_t _inr dv_ t_inr_<?php echo $all_user[$i_user]['id'];?>" style="color:#000;font-size:11px;">
							<div class="teachers_inner_hd hdbg cs1">
                            <div style="float:left;margin:1px 0;" class="sff t_face_<?php echo $all_user[$i_user]['id']?>"><?php // User::face_photo($all_user[$i_user]['id'],$thumb = true , $w = 21 , $h = false)?></div> 
                            <div style="fl oat:left;" class="t2" >
                            	<div style="line-height:1.4;height:15px;"><span><a style="text-decoration:none;color:#0E488F;" class="" href="slot.php?teacher_id=<?php echo $all_user[$i_user]['id'];?>"><?php echo  substr( $all_user[$i_user]['name'],0,16)?></a></span><span class="ld_dtl" shown="no" id="ld_dtl_<?php echo $all_user[$i_user]['id'];?>" onclick="ld_dtl('show',<?php echo $all_user[$i_user]['id'];?>);"  style="float:right;"><img src="media/images/1402921928_plus.png" style="cursor:pointer;" id="t_l_<?php echo $all_user[$i_user]['id'];?>"  /></span></div>
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
							<div class="teachers_inner_hd hdbg cs5 "><?php echo $t_nupt?></div>
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
		// $at_churn_avg_c = ($at_paying_user+$at_cpt>0)?round($at_churn / ($at_paying_user+$at_cpt),2):0;
		$at_churn = array_sum($churn_array);
		$at_churn_paying = array_sum($churn_array_paying);
		$at_churn_avg_cancelled = ($at_cancelled >0)?round($at_churn / ($at_cancelled)):0;
		$at_churn_avg_paying_cancelled = ($at_paying_user+$at_cancelled>0)?round(($at_churn +$at_churn_paying ) / ($at_paying_user +$at_cancelled)):0;
		$at_churn_avg_paying = ($at_paying_user >0)?round($at_churn_paying / ($at_paying_user)):0;
		
					
								
   ?><!--<div class="clear">
							
							
							<div class="ov_head">Overall Summary</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;width:97.8%;text-align:left;padding-left:2%;"><span><?php echo User::overall_teachers_conversion_rate(); ?></span>
                            <span style="padding-left:80px;">Avg. Sub. length <span style="color:#B34000;">Cancelled = <?php echo $at_churn_avg_cancelled?></span>&nbsp;<span style="color:#8509AC;">Cancelled+Paying = <?php echo $at_churn_avg_paying_cancelled?></span>&nbsp;<span style="color:#3B4E31;">Paying = <?php echo $at_churn_avg_paying?></span></span></div>
							<div class="clear"></div>
                            </div>-->
                            
							 </div>
</div>
<div class="clear"><br /></div>
<div class="home_dsh_brd">
<div class="bx">
<div class="bx_head">Overall Conversion</div>
<div style="color:#93210D;" class="bx_rw"><?php $overall_cnv =  User::overall_teachers_conversion_rate(); echo str_replace(array('Conversion','Resp Conv'),array('Conversion<br />','Responsive Conversion'),$overall_cnv); ?></div>
<div style="color:#93210D;" class="bx_rw"><?php echo User::overall_true_conversion_rate(); ?></div>
<div style="color:#F00;" class="bx_rw"></div>
<div style="color:#090;" class="bx_rw"></div>
</div>
<div class="bx">
<div class="bx_head">Avg. Sub. length</div>
<div class="bx_rw"><span style="color:#B34000;">Cancelled = <?php echo $at_churn_avg_cancelled?> days</span></div>
<div class="bx_rw"><span style="color:#8509AC;">Cancelled+Paying = <?php echo $at_churn_avg_paying_cancelled?> days</span></div>
<div class="bx_rw"><span style="color:#3B4E31;">Paying = <?php echo $at_churn_avg_paying?> days</span></div>
<div class="bx_rw"></div>
</div>
<?php // Calculate Avg. Sub. length(App funnel) only 
$at_churn_app_funnel = User::calculate_churn_in_array($datef,$funnel =1); // 1 = app funnel
$at_churn_paying_app_funnel = User::calculate_churn_paying_in_array($datef,$funnel =1); // 1 = app funnel

$at_churn_avg_cancelled_app_funnel = ($at_cancelled >0)?round($at_churn_app_funnel / ($at_cancelled)):0;
$at_churn_avg_paying_cancelled_app_funnel = ($at_paying_user+$at_cancelled>0)?round(($at_churn_app_funnel +$at_churn_paying_app_funnel ) / ($at_paying_user +$at_cancelled)):0;
$at_churn_avg_paying_app_funnel = ($at_paying_user >0)?round($at_churn_paying_app_funnel / ($at_paying_user)):0;
?>
<div class="bx">
<div class="bx_head">Avg. Sub. length(App funnel)</div>
<div class="bx_rw"><span style="color:#B34000;">Cancelled = <?php echo $at_churn_avg_cancelled_app_funnel ?> days</span></div>
<div class="bx_rw"><span style="color:#8509AC;">Cancelled+Paying = <?php echo $at_churn_avg_paying_cancelled_app_funnel ?> days</span></div>
<div class="bx_rw"><span style="color:#3B4E31;">Paying = <?php echo $at_churn_avg_paying_app_funnel ?> days</span></div>
<div class="bx_rw"></div>
</div>
</div>
<div class="clear"><br /><br /></div>
<script type="text/javascript">
$(document).ready(function(e) {
   //showHideTSummary(0,<?php echo $tu;?>);
   var todo = 'get_face_photo';
	$.ajax({  
	type: "POST", 
	url: 'ajax/get_face_photo.php', 
	data: "todo="+todo+"&thumb=1&w=22&h=",
	dataType: "html",
	complete: function(data){
					 var val = data.responseText;       
					var json = $.parseJSON(val);
					$.each(json,function(key,val){
					$('.'+key).removeClass('sff');
					$('.'+key).html(val);
					});
			}
	});
});

function ld_dtl(action,id)
{
	//var shown = $(this).attr('shown').val();
	//alert(shown);
	if(action == 'show')
	{
		if($('.umore_dtl_'+id).html()!='') 
		{
			$('.umore_dtl_'+id).show();
			$('#t_l_'+id).attr('src','media/images/icon-minus.png');
			$('#ld_dtl_'+id).attr('onclick','ld_dtl(\'hide\','+id+');');
			return false;
		}
		
		$('#t_l_'+id).attr('src','media/images/loading-s.gif');
	var todo = 'get_teacher_slot_dtl';
	$.ajax({  
	type: "POST", 
	url: 'ajax/ajax_user_slot_dtl.php', 
	data: "todo="+todo+"&teacher_id="+id,
	dataType: "html",
	success: function(data){
		$('.umore_dtl_'+id).show()
		$('.umore_dtl_'+id).html(data)
		$('#t_l_'+id).attr('src','media/images/icon-minus.png');
		$('#ld_dtl_'+id).attr('onclick','ld_dtl(\'hide\','+id+');');
		}
	});
	}
	else
	{
		$('.umore_dtl_'+id).hide()
		$('#t_l_'+id).attr('src','media/images/1402921928_plus.png');
		$('#ld_dtl_'+id).attr('onclick','ld_dtl(\'show\','+id+');');
	}
	
}

function show_inactive_teacher(action)
{
	
	
	if(action == 'show')
	{
		if($('.dv_inactv_tchr').html()!='') 
		{
			$('.dv_inactv_tchr').show();
			$('.ss-all').html('Hide Inactive');
			//$('#t_l_'+id).attr('src','media/images/icon-minus.png');
			$('.ss-all').attr('onclick','show_inactive_teacher(\'hide\');');
			return false;
		}
		
	$('.ss-all-l-img').show();
	var todo = 'get_inactv_teacher_dtl';
	$.ajax({  
	type: "POST", 
	url: 'ajax/ajax_inactv_teacher.php', 
	data: "todo="+todo,
	dataType: "html",
	success: function(data){
		$('.dv_inactv_tchr').show();
		$('.dv_inactv_tchr').html(data)
		$('.ss-all-l-img').hide();
		$('.ss-all').attr('onclick','show_inactive_teacher(\'hide\');');
		$('.ss-all').html('Hide Inactive');
		}
	});
	}
	else
	{
		$('.dv_inactv_tchr').hide();
		$('.ss-all-l-img').hide();
		//$('#t_l_'+id).attr('src','media/images/1402921928_plus.png');
		$('.ss-all').attr('onclick','show_inactive_teacher(\'show\');');
		$('.ss-all').html('Show all');
		//$('#ld_dtl_'+id).attr('onclick','ld_dtl(\'show\','+id+');');
	}
	
}
</script>
<script src="./js/jquery-scrolltofixed-min.js" type="text/javascript"></script>
<script type="text/javascript">
		$(document).ready(function() {
			// grab the initial top offset of the navigation 
		   	var stickyNavTop = $('.nav').offset().top;
		   	
		   	// our function that decides weather the navigation bar should have "fixed" css position or not.
		   	var stickyNav = function(){
			    var scrollTop = $(window).scrollTop(); // our current vertical position from the top
			         
			    // if we've scrolled more than the navigation, change its position to fixed to stick to top,
			    // otherwise change it back to relative
			    if (scrollTop > stickyNavTop) { 
			        $('.nav').addClass('sticky');
			    } else {
			        $('.nav').removeClass('sticky'); 
			    }
			};

			stickyNav();
			// and run it again every time you scroll
			$(window).scroll(function() {
				stickyNav();
			});
		});
		</script>
</head>	