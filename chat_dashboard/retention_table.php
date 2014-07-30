<div style="margin-top:20px;width:1335px;" class="list_user">
    <div style="display:table;width:100%;bor der:1px solid #ccc;margin-bottom:10px;" class="table_view">
         <div class="ov_head">Retention table&nbsp; </div>
        </div>
         
        <?php
		/*$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$rt_reach_cur = 50;
		
		$rt_cur_year = get_japan_time('Y');
		$rt_cur_month = get_japan_time('m');
		//$rt_row_month = date('2014-01-01'); // 
		
		
		$rt_p_i = $rt_cur_month-1 ;
		$rt_ii = 0;
		$rt_addi = 0;
		 $sql_started_rt = "SELECT count( id ) AS total, GROUP_CONCAT(id) AS ids, monthname( first_paid_date ) AS
							month ,year(`first_paid_date`) AS year
							FROM `users_list`
							WHERE first_paid_date != '0000-00-00 00:00:00' AND first_paid_date >= '".$rt_cur_year."-01-01 00:00:00' AND first_paid_date <= '".$rt_cur_year."-12-31 23:59:59' AND first_paid_date <= '".$rt_cur_year."-".$rt_cur_month."-31 23:59:59' 
							GROUP BY month,year
							ORDER BY month(first_paid_date)  ASC ";
							
		$q_sql_started_rt = $db->doQuery($sql_started_rt);
			
			while($rtmrow = mysql_fetch_assoc($q_sql_started_rt))
			{
				
       // print_r($rtmrow);
		  $ids = $rtmrow['ids'];
			//if($rt_row > $rt_reach_cur+1 ) break;
			*/
		?>
        <div style="clear:both;" class="clear"></div>
        <div class="retention_data" style=""></div>
        
        
        <div class="retention_data_2" style=""><div align="center" style="padding:20px 0;"><img src="media/images/iconWorking.gif"  /></div></div>
    	<?php /*?><div style="color:#000;font-size:11px;" >
            <div class="rt-col " style="width:158px;text-align:right;"><?php echo $rtmrow['month'].' '.$rtmrow['year'];?>&nbsp;&nbsp;</div>
            <div class="rt-col" style="backgro und:#0CC"><?php echo $started_with_student =  $rtmrow['total'];?></div>
            <div class="rt-col rt-c-0" ><?php echo $rtmrow['total'];?></div>
            <?php
            
				$rt_student_omitted_ary = array();
				 $sql_omitted = "SELECT count( id ) AS total_omitted , month( cancellation_date ) AS
						month , year( `cancellation_date` ) AS year 
						FROM `users_list`
						WHERE id IN ( ".$ids." ) AND `cancellation_date` != '0000-00-00 00:00:00'
						GROUP BY month( cancellation_date ) ORDER BY month( cancellation_date ) ASC  ";
				$q_sql_omitted = $db->doQuery($sql_omitted);
				while($tr_omtd = mysql_fetch_assoc($q_sql_omitted))
				{
					$rt_student_omitted_ary[] = array( 'month'=>$tr_omtd['month'],'year'=>$tr_omtd['year'],'total_omitted'=>$tr_omtd['total_omitted']);
					//$rt_student_omitted_ary[]['year'] = $tr_omtd['year'];
					//$rt_student_omitted_ary[]['total_omitted'] = $tr_omtd['total_omitted'];
					
				}
				//echo '<br /><br /><br /><br />';
				//echo $rt_omitted_m = $rt_student_omitted_ary[0];
				//echo $rt_omitted_y = $rt_student_omitted_ary[1];
				//echo $rt_omitted_total_student = $rt_student_omitted_ary[2];
				
					
				$rt_omitted_i = 0;
				$rt_c_bg = 0;
				for( $rt_ii = 0 ;$rt_ii< $rt_p_i;$rt_ii++)
				{
					$rt_cur_students='';
					$rt_this_m = $rt_ii+$rt_addi+2;
					
					$rt_omitted_m = (isset($rt_student_omitted_ary[$rt_omitted_i]['month']))?$rt_student_omitted_ary[$rt_omitted_i]['month']:15;
					$rt_omitted_y =(isset($rt_student_omitted_ary[$rt_omitted_i]['year']))?$rt_student_omitted_ary[$rt_omitted_i]['year']:1970;
					if($rt_omitted_m+1 == $rt_this_m && $rt_omitted_y == $rt_cur_year)
					{
						//echo 'xxx';
						$rt_cur_students = $started_with_student - $rt_student_omitted_ary[$rt_omitted_i]['total_omitted'];	
						$started_with_student = $rt_cur_students;
						$rt_omitted_i ++;
						$rt_c_bg++;
					}
					else
					{
						$rt_cur_students = $started_with_student;
					}
					echo '<div class="rt-col rt-c-'.$rt_c_bg.'">'.$rt_cur_students.'</strong></div>';
					//echo '<div class="rt-col">'.$rt_this_m.'-'.$rt_cur_year.'*<strong style="color:red">'.$rt_cur_students.'</strong></div>';
				}
				//echo '<pre>';				print_r($rt_student_omitted_ary);echo '</pre>';
			$rt_p_i--;
			$rt_addi++;
			?>
            
            <!--<div class="rt-col">-</div>
            <div class="rt-col">-</div>
            <div class="rt-col">-</div>
            <div class="rt-col">-</div>
            <div class="rt-col">-</div>
            <div class="rt-col">-</div>
            <div class="rt-col">-</div>
            <div class="rt-col">-</div>
            <div class="rt-col">-</div>
            <div class="rt-col">-</div>
            <div class="rt-col">-</div>-->
        </div><?php */?>
        <?php 
		/*$rt_row_month = date( "Y-m-d", strtotime( "$rt_row_month +1 month" ) );
		if(date('m',strtotime($rt_row_month)) == date('m') && date('Y',strtotime($rt_row_month)) == date('Y')  )
		{
			$rt_reach_cur = $rt_row;
		}*/
		//}
		 ?>
	 </div>
</div>
<br />

<script type="text/javascript">
$(document).ready(function(e) {
   var todo = 'get_retention_table';
	$.ajax({  
	type: "POST", 
	url: 'ajax/ajax_retention.php', 
	data: "todo="+todo+"&thumb=1&w=22&h=",
	dataType: "html",
	success: function(data){
		$('.retention_data_2').html(data);
		
		}
	});
	
	/*$.ajax({  
	type: "POST", 
	url: 'ajax/copy_ajax_retention.php', 
	data: "todo="+todo+"&thumb=1&w=22&h=",
	dataType: "html",
	success: function(data){
		$('.retention_data_2').html(data);
		
		}
	});*/
});


</script>