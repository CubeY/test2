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
         <div class="ov_hd ov_hd_top"></div>
         <div class="ov_hd ov_hd_top"><strong>LINE URL shown</strong></div>
         <div class="ov_hd ov_hd_top"><strong>Joined</strong></div>
         <div class="ov_hd ov_hd_top"><strong>Joined F.T</strong></div>
         <div class="ov_hd ov_hd_top"><strong>Current Paying Users</strong></div>
         <div class="ov_hd ov_hd_top"><strong>Payment info (will be) sent</strong></div>
         <div class="ov_hd ov_hd_top"><strong>FT  (wiil be) expired</strong></div>
         <div class="ov_hd ov_hd_top"><strong>Registration link sent</strong></div>
         <div class="ov_hd ov_hd_top"><strong>FT expired w/o response</strong></div>
         <div class="ov_hd ov_hd_top"><strong>Cancelled</strong></div>
     </div>
     <div style="height:190px;width:1300px;overflow:scroll;overflow-x: hidden;" class="cls_past_data">
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



<?php

$all_user = User::all_user('teacher',$active_only = true);
$tu = count($all_user);
?>
<div class="clear" style="clear:both;line-height:0;"></div>
					<div class="list_user" style="margin-top:50px;">
					<div class="table_view" style="display:table;width:100%;border:1px solid #999;">
							<div class="ov_head" onclick="showHideTSummary(0,<?php echo $tu;?>)" style="cursor:pointer;" >Teacher Summary<span class="sp_btn al_icon"><img src="media/images/1400083886_minus-sign.png"  class="top_sh" alt="hide"/></span></div>
<?php					

$at_slots = 0;$at_t_cnv_rate_d_by = 0;$at_paying_user = 0; 	$at_fpd = 0;$at_churn = 0;$at_cpt = 0;
  for($i_user = 0;$i_user < count($all_user);$i_user++)
  {
    ?><div id="proTr" onclick="showHideTSummary(<?php echo $all_user[$i_user]['id'];?>,'')" style="cursor:pointer;">
     <div class="teachers_hd" style=""><span style="margin:0 15px 0 0 ;"><?php User::face_photo($all_user[$i_user]['id'],$thumb = true , $w = 21 , $h = false)?></span> <strong><a class="" href="slot.php?teacher_id=<?php echo $all_user[$i_user]['id'];?>"><?php echo  $all_user[$i_user]['name']?></a></strong></div>
	 <div class="teachers_hd" style="">Email - <strong><?php echo $all_user[$i_user]['email']?></strong></div>
	<div class="teachers_hd" style="">Username - <strong><?php echo $all_user[$i_user]['user']?></strong><span class="sp_btn al_icon span_icon_<?php echo $all_user[$i_user]['id'];?>"><img src="media/images/1400083886_minus-sign.png" /></span></div>
    </div>
     <div class="clear" style="clear:both;"></div>
     <?php
	 $teacher_slots = User::get_teacher_slotBy_id($all_user[$i_user]['id']);
	 ?> <div class="clear" style="clear:both;line-height:0;"></div>
      <div class="dv_t _inr dv_ t_inr_<?php echo $all_user[$i_user]['id'];?> dv_t_inr_hd<?php echo $all_user[$i_user]['id'];?>" >
					 <div class="teachers_inner_hd cs1" ><strong>Slot Time</strong></div>
					 <div class="teachers_inner_hd"><strong>MX(Actv)</strong></div>
                     <div class="teachers_inner_hd cs2"><strong>LUS</strong></div>
                     <div class="teachers_inner_hd"><strong>Joined</strong></div>
                     <div class="teachers_inner_hd cs2"><strong>IMS</strong></div>
					 <div class="teachers_inner_hd"><strong>In F.T.</strong></div>
                     <div class="teachers_inner_hd cs2"><strong>PIS</strong></div>
                     <div class="teachers_inner_hd"><strong>FT Exp</strong></div>
                     <div class="teachers_inner_hd" style="width:65px;"><strong>FT Exp W/O</strong></div>
					 <div class="teachers_inner_hd cs2"><strong>RLS</strong></div>
					 <div class="teachers_inner_hd"><strong>Paying</strong></div>
					 <div class="teachers_inner_hd"><strong>Cancelled</strong></div>
                     <div class="teachers_inner_hd"><strong>Active</strong></div>
                     
					 <div class="teachers_inner_hd"><strong>NPUT</strong></div>
					 <div class="teachers_inner_hd cs2"><strong>CPT</strong></div>
					 <div class="teachers_inner_hd cs3"><strong>Cnv Rate</strong></div>
                     <div class="teachers_inner_hd cs4"><strong>True Cnv</strong></div>
					 <div class="teachers_inner_hd" style="width:90px;line-height:1.2;"><strong>Avg. Sub. length cancelled</strong></div>
                     <div class="teachers_inner_hd" style="width:100px;line-height:1.2;"><strong>Avg. Sub. length cancelled+paying</strong></div>
					 </div>
					  <div class="clear" style="clear:both;line-height:0;"></div>
					 <?php
					 
					 $t_ini_msg = 0;$t_in_ft = 0;$t_paying_user = 0;$t_act_stdnt = 0;$t_nupt = 0;$t_cpt = 0;$t_no_res=0;$t_cnv_rate = 0;$t_f_t_expired = 0;
					 $t_churn = 0;$t_churn_paying = 0;$t_fpd= 0;$t_f_t_expired_wo = 0;$t_r_link_sent = 0;$t_cancelled = 0;
					 $t_avl_url_shown = 0;$t_num_student = 0;$t_pay_info_sent = 0;$t_mx_student = 0;
					for($i=0; $i < count($teacher_slots); $i++)
					  {
						 if($teacher_slots[$i]['empty_slot']==1) { 
							$ts =  "NTS"; 
							}
							else {
								$ts =  $teacher_slots[$i]["start_time"].'&nbsp;To&nbsp;'.$teacher_slots[$i]["end_time"];
							}
							
							$t_avl_url_shown = User::get_teacher_num_line_url_shown($all_user[$i_user]['id']);;
							
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
							$churn_avg = ($paying_user > 0)?round($churn_array['total']/$paying_user,2):0;
							$churn_avg_paying = ($paying_user > 0)?round(($churn_array_paying['total']+$churn_array['total'])/$paying_user,2):0;
							//$t_fpd = $t_fpd + $churn_array['t_fpd'];
							?>
							<div class="clear" style="clear:both;line-height:0;"></div>
							<div class="dv_t_inr dv_t_inr_<?php echo $all_user[$i_user]['id'];?>" style="color:#000;font-size:11px;">
							<div class="teachers_inner_hd cs1" ><?php echo $ts?></div>
                            <div class="teachers_inner_hd"><?php echo $mx_student.'('.$act_stdnt.')';?></div>
                            <div class="teachers_inner_hd cs2"><?php echo $teacher_slots[$i]["num_line_url_shown"];?></div>
                            <div class="teachers_inner_hd"><?php echo $num_student;?></div>
							<div class="teachers_inner_hd cs2" ><?php echo $ini_msg?></div>
							<div class="teachers_inner_hd" ><?php echo $in_ft?></div>
                            <div class="teachers_inner_hd cs2" ><?php echo $pay_info_sent;?></div>
                            <div class="teachers_inner_hd"><?php echo $f_t_expired;?></div>
                            <div class="teachers_inner_hd" style="width:65px;"><?php echo $f_t_expired_wo;?></div>
                            <div class="teachers_inner_hd cs2"><?php  echo $r_link_sent;?></div>
							<div class="teachers_inner_hd"  ><?php echo $paying_user?></div>
                            <div class="teachers_inner_hd"><?php echo $cancelled?></div>
							<div class="teachers_inner_hd" ><?php echo $act_stdnt?></div>
							<div class="teachers_inner_hd" ><?php echo $nupt?></div>
							<div class="teachers_inner_hd cs2" ><?php echo $cpt?></div>
							<div class="teachers_inner_hd cs3"><?php //echo $cnv_rate?>-</div>
                            <div class="teachers_inner_hd cs4"><?php //echo $cnv_rate?>-</div>
							<div class="teachers_inner_hd" style="width:90px;"><?php echo $churn_avg?></div>
							<div class="teachers_inner_hd" style="width:100px;"><?php echo $churn_avg_paying;?></div>
                            </div>
							<div class="clear" style="clear:both;line-height:0;"></div>
						<?php	
					  }
					  
					$t_cnv_rate_d_by = $t_paying_user +$t_no_res +$t_f_t_expired ;
							if($t_cnv_rate_d_by <=0)
							$t_cnv_rate = 0;
							else
							$t_cnv_rate =   round( ($t_paying_user / $t_cnv_rate_d_by ), 2);
							
					   
					    $t_churn_avg = ($t_paying_user+$t_cpt>0)?round($t_churn / ($t_paying_user+$t_cpt)):0;
					$t_churn_avg_paying = ($t_paying_user+$t_cpt>0)?round(($t_churn+$t_churn_paying ) / ($t_paying_user+$t_cpt)):0;
					
							
							
							// all total 
							$at_slots = $at_slots + $i;
							$at_t_cnv_rate_d_by = $at_t_cnv_rate_d_by + $t_cnv_rate_d_by;
							$at_paying_user = $at_paying_user + $t_paying_user; 
							$at_cpt = $at_cpt + $t_cpt; 
							
							$at_fpd = $at_fpd + $t_fpd;
							$at_churn = $at_churn + $t_churn;
							
							
							?>
							
					<div class="dv_t _inr dv_ t_inr_<?php echo $all_user[$i_user]['id'];?>" style="color:#000;font-size:11px;">
							<div class="teachers_inner_hd hdbg cs1">Total/Average:<?php echo ($i)?></div>
							<div class="teachers_inner_hd hdbg"><?php echo $t_mx_student.'('.$t_act_stdnt.')';?></div>
                            <div class="teachers_inner_hd hdbg cs2"><?php echo $t_avl_url_shown;?></div>
                             <div class="teachers_inner_hd hdbg"><?php echo $t_num_student;?></div>
                            <div class="teachers_inner_hd hdbg cs2"><?php echo $t_ini_msg?></div>
							<div class="teachers_inner_hd hdbg"><?php echo $t_in_ft?></div>
                             <div class="teachers_inner_hd hdbg cs2" ><?php echo $t_pay_info_sent;?></div>
                             <div class="teachers_inner_hd hdbg"><?php echo $t_f_t_expired;?></div>
                             <div class="teachers_inner_hd hdbg" style="width:65px;"><?php echo $t_f_t_expired_wo;?></div>
                              <div class="teachers_inner_hd hdbg cs2"><?php  echo $t_r_link_sent;?></div>
							<div class="teachers_inner_hd hdbg"><?php echo $t_paying_user?></div>
                            <div class="teachers_inner_hd hdbg"><?php echo $t_cancelled;?></div>
							<div class="teachers_inner_hd hdbg"><?php echo $t_act_stdnt?></div>
							<div class="teachers_inner_hd hdbg"><?php echo $t_nupt?></div>
							<div class="teachers_inner_hd hdbg cs2"><?php echo $t_cpt?></div>
							<div class="teachers_inner_hd hdbg cs3" style="line-height:1.2;"><?php echo str_replace(array('Conversion','Resp Conv'),array('Cnv','R cnv'), User::teacher_conversion_rate($all_user[$i_user]['id']));?></div>
							<div class="teachers_inner_hd hdbg cs4" style="line-height:1.2;"><?php echo  User::teacher_true_conversion_rate($t_paying_user,$t_cancelled,$t_avl_url_shown);?></div>
                            <div class="teachers_inner_hd hdbg" style="width:90px;"><?php echo $t_churn_avg?></div>
							<div class="teachers_inner_hd hdbg" style="width:100px;"><?php echo $t_churn_avg_paying;?></div>
                            </div>
                            <div class="clear" style="clear:both;line-height:0;"></div>
                            <!--- PAST 30 Days -->
                             <?php
							// past 30 days record for each teachres
							$total_joined_p_30d_bt = User::total_student_by_date_and_teachers($datef,$col = 'created_on',$dayDif = 30,$all_user[$i_user]['id']);
							//$total_ims_p_30d_bt = User::total_student_by_date_and_teachers_from_logs_tbl($datef,$col = 'created_on',$status = 1 ,$dayDif = 30,$all_user[$i_user]['id']);
							$total_in_f_t_p_30d_bt = User::total_student_by_date_and_teachers($datef,$col = 'first_response',$dayDif = 30,$all_user[$i_user]['id']);
							//$total_converted_to_paying_p_30d_bt = User::total_student_by_date($datef,$col = 'first_paid_date',$dayDif = 30);
							$total_payment_infosent_p_30d_bt = User::total_student_by_date_and_teachers($datef,$col = 'payment_info_sent_date',$dayDif = 30,$all_user[$i_user]['id']);
							$total_ft_expire_p_30d_bt = User::total_student_by_date_and_teachers_from_logs_tbl($datef,$col = 'chenged_date',$status = 6 ,$dayDif = 30,$all_user[$i_user]['id']);
							$total_ft_expire_ow_p_30d_bt = User::total_student_by_date_and_teachers_from_logs_tbl($datef,$col = 'chenged_date',$status = 4 ,$dayDif = 30,$all_user[$i_user]['id']);
							$total_rlink_sent_p_30d_bt = User::total_student_by_date_and_teachers_from_logs_tbl($datef,$col = 'chenged_date',$status = 9 ,$dayDif = 30,$all_user[$i_user]['id']);
							$total_paying_p_30d_bt = User::total_student_by_date_and_teachers($datef,$col = 'first_paid_date',$dayDif = 30,$all_user[$i_user]['id']);
							$total_canceled_p_30d_bt = User::total_student_by_date_and_teachers($datef,$col = 'cancellation_date',$dayDif = 30,$all_user[$i_user]['id']);
						    $total_active_p_30d_bt = $total_joined_p_30d_bt+$total_in_f_t_p_30d_bt+$total_paying_p_30d_bt;
						  ?>
                            <div class="dv_t_inr dv_t_inr_<?php echo $all_user[$i_user]['id'];?>" style="color:#000;font-size:11px;">
							<div class="teachers_inner_hd hdbg cs1">Past 30 Days</div>
							<div class="teachers_inner_hd hdbg"></div>
                            <div class="teachers_inner_hd hdbg cs2"></div>
                             <div class="teachers_inner_hd hdbg"><?php echo $total_joined_p_30d_bt;?></div>
                            <div class="teachers_inner_hd hdbg cs2"><?php echo $total_joined_p_30d_bt?></div>
							<div class="teachers_inner_hd hdbg"><?php echo $total_in_f_t_p_30d_bt?></div>
                             <div class="teachers_inner_hd hdbg cs2" ><?php echo $total_payment_infosent_p_30d_bt;?></div>
                             <div class="teachers_inner_hd hdbg"><?php echo $total_ft_expire_p_30d_bt;?></div>
                             <div class="teachers_inner_hd hdbg" style="width:65px;"><?php echo $total_ft_expire_ow_p_30d_bt;?></div>
                              <div class="teachers_inner_hd hdbg cs2"><?php echo $total_rlink_sent_p_30d_bt;?></div>
							<div class="teachers_inner_hd hdbg"><?php echo $total_paying_p_30d_bt?></div>
                            <div class="teachers_inner_hd hdbg"><?php echo $total_canceled_p_30d_bt;?></div>
							<div class="teachers_inner_hd hdbg"><?php  echo $total_active_p_30d_bt;?></div>
							<div class="teachers_inner_hd hdbg"><?php // echo $t_nupt?></div>
							<div class="teachers_inner_hd hdbg cs2"><?php // echo $t_cpt?></div>
							<div class="teachers_inner_hd hdbg cs3" style="line-height:1.2;"><?php //echo str_replace(array('Conversion','Resp Conv'),array('Cnv','R cnv'), User::teacher_conversion_rate($all_user[$i_user]['id']));?></div>
							<div class="teachers_inner_hd hdbg cs4" style="line-height:1.2;"></div>
                            <div class="teachers_inner_hd hdbg" style="width:90px;"><?php //echo $t_churn_avg?></div>
							<div class="teachers_inner_hd hdbg" style="width:100px;"><?php //echo $t_churn_avg_paying;?></div>
                            </div>
							<div class="clear" style="clear:both;line-height:0;"></div>
							
                            <!--- Today's -->
                             <?php
							// Todays record for each teachres
							$total_joined_todays_bt = User::total_student_by_date_and_teachers($datef,$col = 'created_on',$dayDif = 0,$all_user[$i_user]['id']);
							//$total_ims_p_30d_bt = User::total_student_by_date_and_teachers_from_logs_tbl($datef,$col = 'created_on',$status = 1 ,$dayDif = 30,$all_user[$i_user]['id']);
							$total_in_f_t_todays_bt = User::total_student_by_date_and_teachers($datef,$col = 'first_response',$dayDif = 0,$all_user[$i_user]['id']);
							//$total_converted_to_paying_p_30d_bt = User::total_student_by_date($datef,$col = 'first_paid_date',$dayDif = 30);
							$total_payment_infosent_todays_bt = User::total_student_by_date_and_teachers($datef,$col = 'payment_info_sent_date',$dayDif = 0,$all_user[$i_user]['id']);
							$total_ft_expire_todays_bt = User::total_student_by_date_and_teachers_from_logs_tbl($datef,$col = 'chenged_date',$status = 6 ,$dayDif = 0,$all_user[$i_user]['id']);
							$total_ft_expire_ow_todays_bt = User::total_student_by_date_and_teachers_from_logs_tbl($datef,$col = 'chenged_date',$status = 4 ,$dayDif = 0,$all_user[$i_user]['id']);
							$total_rlink_sent_todays_bt = User::total_student_by_date_and_teachers_from_logs_tbl($datef,$col = 'chenged_date',$status = 9 ,$dayDif = 0,$all_user[$i_user]['id']);
							$total_paying_todays_bt = User::total_student_by_date_and_teachers($datef,$col = 'first_paid_date',$dayDif = 0,$all_user[$i_user]['id']);
							$total_canceled_todays_bt = User::total_student_by_date_and_teachers($datef,$col = 'cancellation_date',$dayDif = 0,$all_user[$i_user]['id']);
						    $total_active_todays_bt = $total_joined_todays_bt+$total_in_f_t_todays_bt+$total_paying_todays_bt;
						  ?>
                          
                          
                             <div class="dv_t_inr dv_t_inr_<?php echo $all_user[$i_user]['id'];?>" style="color:#000;font-size:11px;">
							<div class="teachers_inner_hd hdbg cs1">Today's</div>
							<div class="teachers_inner_hd hdbg"></div>
                            <div class="teachers_inner_hd hdbg cs2"></div>
                             <div class="teachers_inner_hd hdbg"><?php echo $total_joined_todays_bt;?></div>
                            <div class="teachers_inner_hd hdbg cs2"><?php echo $total_joined_todays_bt?></div>
							<div class="teachers_inner_hd hdbg"><?php echo $total_in_f_t_todays_bt?></div>
                             <div class="teachers_inner_hd hdbg cs2" ><?php echo $total_payment_infosent_todays_bt;?></div>
                             <div class="teachers_inner_hd hdbg"><?php echo $total_ft_expire_todays_bt;?></div>
                             <div class="teachers_inner_hd hdbg" style="width:65px;"><?php echo $total_ft_expire_ow_todays_bt;?></div>
                              <div class="teachers_inner_hd hdbg cs2"><?php echo $total_rlink_sent_todays_bt;?></div>
							<div class="teachers_inner_hd hdbg"><?php echo $total_paying_todays_bt?></div>
                            <div class="teachers_inner_hd hdbg"><?php echo $total_canceled_todays_bt;?></div>
							<div class="teachers_inner_hd hdbg"><?php  echo $total_active_todays_bt;?></div>
							<div class="teachers_inner_hd hdbg"><?php  echo $t_nupt?></div>
							<div class="teachers_inner_hd hdbg cs2"><?php  echo $t_cpt?></div>
							<div class="teachers_inner_hd hdbg cs3" style="line-height:1.2;"><?php //echo str_replace(array('Conversion','Resp Conv'),array('Cnv','R cnv'), User::teacher_conversion_rate($all_user[$i_user]['id']));?></div>
							<div class="teachers_inner_hd hdbg cs4" style="line-height:1.2;"></div>
                            <div class="teachers_inner_hd hdbg" style="width:90px;"><?php //echo $t_churn_avg?></div>
							<div class="teachers_inner_hd hdbg" style="width:100px;"><?php //echo $t_churn_avg_paying;?></div>
                            </div>
							<div class="clear" style="clear:both;line-height:0;"></div>
                            
                            
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
		 $at_churn_avg = ($at_paying_user+$at_cpt>0)?round($at_churn / ($at_paying_user+$at_cpt),2):0;				
						
   ?><div class="clear">
							
							
							<div class="ov_head">Overall Summary</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;width:97.8%;text-align:left;padding-left:2%;"><?php echo $at_slots?> Slot(s) <span style="padding-left:80px;"><?php echo User::overall_teachers_conversion_rate(); ?></span><span style="padding-left:80px;"><?php echo $at_churn_avg?> Avg. Sub. length</span></div>
							<div class="clear"></div>
                            </div>
                            
							<?php /*?><?php  
	// Daily stat of previous day
	$total_joined_p_day = User::total_student_by_date($datef,$col = 'created_on',$dayDif = 1);
	$total_started_f_t_p_day = User::total_student_by_date($datef,$col = 'first_response',$dayDif = 1);
	$total_converted_to_paying_p_day = User::total_student_by_date($datef,$col = 'first_paid_date',$dayDif = 1);
	$total_payment_infosent_p_day = User::total_student_by_date($datef,$col = 'payment_info_sent_date',$dayDif = 1);
	$total_ft_expire_p_day = User::total_student_by_date($datef,$col = 'ft_expiration',$dayDif = 1);
	$total_ft_expire_ow_p_day = User::total_student_by_date_from_logs_tbl($datef,$col = 'chenged_date',$status = 4 ,$dayDif = 1);
	$total_canceled_p_day = User::total_student_by_date($datef,$col = 'cancellation_date',$dayDif = 1);
	
	?><div class="clear" style="color:#FFF;font-size:13px;">
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#37A7F1;display:block;text-align:left;padding-left:2%;width:96%;">Daily stat of previous day</div>
							</div>
							<div class="clear" style="clear:both;line-height:0;"></div>
							<div class="clear" style="color:#000;font-size:11px;">
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;"><?php echo $total_joined_p_day?> students joined</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;"><?php echo $total_started_f_t_p_day?> students started F.T</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;"><?php echo $total_converted_to_paying_p_day?> students converted to paying</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;"><?php echo $total_payment_infosent_p_day?> students payment info sent</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;"><?php echo $total_ft_expire_p_day?> students FT expired </div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;"><?php echo $total_ft_expire_ow_p_day?> students FT expired w/o response</div>
							<div  style="height:30px;float:left;border:1px solid #FFFFFF;background:#BDC7C1;display:block;text-align:left;padding-left:2%;width:96%;"><?php echo $total_canceled_p_day?> students cancelled</div>
							</div>
							<div class="clear" style="clear:both;line-height:0;"></div>
							<?php */?><?php
													
	
	?><?php /*?><div class="clear" >
							<div class="ov_head">Weekly stat of previous 7 days</div>
							</div>
		
        <div class="clear" style="color:#000;">
							<div class="summary7d"><?php echo $total_joined_p_7d?> students joined</div>
							<div class="summary7d"><?php echo $total_started_f_t_p_7d?> students started F.T</div>
							<div class="summary7d"><?php echo $total_converted_to_paying_p_7d?> students converted to paying</div>
							<div class="summary7d"><?php echo $total_payment_infosent_p_7d?> students payment info sent </div>
							<div class="summary7d"><?php echo $total_ft_expire_p_7d?> students  FT expired </div>
							<div class="summary7d"><?php echo $total_ft_expire_ow_p_7d?> students FT expired w/o response</div>
							<div class="summary7d"><?php echo $total_canceled_p_7d?> students cancelled </div>
							</div><?php */?>
							 </div>
</div>

<script type="text/javascript">
$(document).ready(function(e) {
   showHideTSummary(0,<?php echo $tu;?>);
});
</script>
</head>	