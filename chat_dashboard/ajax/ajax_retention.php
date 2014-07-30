<?php
/*======================================================================**
**                                                                           
** Page:Ajax status , handel status ajax call 
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


if(isset($_POST['todo']))
$todo = $_POST['todo'];

	if($todo =='get_retention_table')
	{
		?>
         <div style="display:table;width:100%;bo rder:1px solid #ccc;margin-bottom:10px;" class="table_view">
        <?php
		echo get_tbl_head_html();
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$rt_reach_cur = 50;
		$rt_all_student_omitted_ary = array();
		$rt_cur_year = get_japan_time('Y');
		$rt_cur_month = get_japan_time('m');
		$rt_row_month = date('2014-01-01'); // 
		
		$rtmrow_ary = array();
		 $rt_p_i = $rt_cur_month-1 ;
		$rt_ii = 0;
		$rt_addi = 0;
		$rt_total_im = $rt_cur_month+0;
		$rt_im_to_show = 0;
		  $sql_started_rt = "SELECT count( id ) AS total, GROUP_CONCAT(id) AS ids, month( first_paid_date ) AS
							month ,year(`first_paid_date`) AS year
							FROM `users_list`
							WHERE first_paid_date != '0000-00-00 00:00:00' AND first_paid_date >= '".$rt_cur_year."-01-01 00:00:00' AND first_paid_date <= '".$rt_cur_year."-12-31 23:59:59' AND first_paid_date <= '".$rt_cur_year."-".$rt_cur_month."-31 23:59:59' 
							GROUP BY month,year
							ORDER BY month(first_paid_date)  ASC ";
							
		$q_sql_started_rt = $db->doQuery($sql_started_rt);
			
			while($rtmrow = mysql_fetch_assoc($q_sql_started_rt))
			{
				$rtmrows_ary[] =$rtmrow;
			}
			//echo '<pre>';print_r($rtmrows_ary);echo '</pre>';
			
			for($rt_im = 0;$rt_im < $rt_total_im;$rt_im++)
			{
				$rt_db_mm = (isset($rtmrows_ary[$rt_im_to_show]['month']))?$rtmrows_ary[$rt_im_to_show]['month']:0;
				if($rt_db_mm == date('m',strtotime($rt_row_month))+0)
				{
					$rtmrow_ary = $rtmrows_ary[$rt_im_to_show];
					$rt_im_to_show++;
					$rt_prevent_sql_next = false;
				}
				else
				{
					$rtmrow_ary = array();
					$rt_prevent_sql_next =  true;
				}
				
     //  print_r($rtmrow_ary);
		  $ids = (isset($rtmrow_ary['ids']) && $rtmrow_ary['ids']!='')?$rtmrow_ary['ids']:'';
		  $started_with_student =  (isset($rtmrow_ary['total']) &&  $rtmrow_ary['total']!='')?$rtmrow_ary['total']:0;
			//if($rt_row > $rt_reach_cur+1 ) break;
			
		?>
        <div style="clear:both;" class="clear"></div>
    	<div style="color:#000;font-size:11px;" >
            <div class="rt-col " style="width:158px;text-align:right;"><?php echo date('F',strtotime($rt_row_month)).'&nbsp;'.date('Y',strtotime($rt_row_month));?>&nbsp;&nbsp;</div>
            <div class="rt-col" style="backgro und:#0CC"><?php echo $started_with_student;?></div>
            <div class="rt-col rt-c-0" ><?php echo $started_with_student;?></div>
            <?php
            	//echo $ids;
				$rt_student_omitted_ary = array();
				if($rt_prevent_sql_next ==  false)
				{
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
					
				}}
				
				$rt_all_student_omitted_ary[$rt_im] = $rt_student_omitted_ary;
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
            
           
        </div>
        <?php 
		$rt_row_month = date( "Y-m-d", strtotime( "$rt_row_month +1 month" ) );
		/*if(date('m',strtotime($rt_row_month)) == date('m') && date('Y',strtotime($rt_row_month)) == date('Y')  )
		{
			$rt_reach_cur = $rt_row;
		}*/
		} ?>
		<?php
	}	


	
?>
</div>
<!----------------------------------------------------------------------------------->
<!---- % Percentage Retention table data ----------------------------------------------->
<!----------------------------------------------------------------------------------->

<div style="display:table;width:100%;bo rder:1px solid #ccc;" class="table_view">
<?php
echo get_tbl_head_html();
$rt_row_month = date('2014-01-01'); // 
$rtmrow_ary = array();
		 $rt_p_i = $rt_cur_month-1 ;
		$rt_ii = 0;
		$rt_addi = 0;
		$rt_total_im = $rt_cur_month+0;
$rt_im_to_show = 0;
for($rt_im = 0;$rt_im < $rt_total_im;$rt_im++)
			{
				$rt_db_mm = (isset($rtmrows_ary[$rt_im_to_show]['month']))?$rtmrows_ary[$rt_im_to_show]['month']:0;
				if($rt_db_mm == date('m',strtotime($rt_row_month))+0)
				{
					$rtmrow_ary = $rtmrows_ary[$rt_im_to_show];
					$rt_im_to_show++;
					$rt_prevent_sql_next = false;
				}
				else
				{
					$rtmrow_ary = array();
					$rt_prevent_sql_next =  true;
				}
				
     //  print_r($rtmrow_ary);
		  $ids = (isset($rtmrow_ary['ids']) && $rtmrow_ary['ids']!='')?$rtmrow_ary['ids']:'';
		  $started_with_student =  (isset($rtmrow_ary['total']) &&  $rtmrow_ary['total']!='')?$rtmrow_ary['total']:0;
			//if($rt_row > $rt_reach_cur+1 ) break;
			
		?>
        <div style="clear:both;" class="clear"></div>
    	<div style="color:#000;font-size:11px;" >
            <div class="rt-col " style="width:158px;text-align:right;"><?php echo date('F',strtotime($rt_row_month)).'&nbsp;'.date('Y',strtotime($rt_row_month));?>&nbsp;&nbsp;</div>
            <div class="rt-col" style="backgro und:#0CC"><?php echo $started_with_student_ac = $started_with_student;?></div>
            <div class="rt-col rt-c-0" ><?php if($started_with_student_ac > 0 ) echo round( (100 * $started_with_student) / $started_with_student_ac , 2).'%'; else echo '-';?></div>
            <?php
            	//echo $ids;
				$rt_student_omitted_ary = array();
				/*if($rt_prevent_sql_next ==  false)
				{
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
					
				}}*/
				$rt_student_omitted_ary  = $rt_all_student_omitted_ary[$rt_im];
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
					?><div class="rt-col rt-c-<?php echo $rt_c_bg?>"> <?php if($started_with_student_ac > 0 ) echo round(($rt_cur_students*100)/$started_with_student_ac , 2).'%'; else echo '-';?></strong></div><?php
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
        </div>
        <?php 
		$rt_row_month = date( "Y-m-d", strtotime( "$rt_row_month +1 month" ) );
		/*if(date('m',strtotime($rt_row_month)) == date('m') && date('Y',strtotime($rt_row_month)) == date('Y')  )
		{
			$rt_reach_cur = $rt_row;
		}*/
		}

?>
</div>
<?php
function get_japan_time($w)
{
	date_default_timezone_set("Asia/Tokyo");
	return date($w);
}

function get_tbl_head_html()
{
	$str = '<div style="clear:both;" class="clear"></div>
        <div class="dv_t _inr ">
             <div class="rt-col " style="width:158px;background:#09c;">   &nbsp;</div>
             <div class="rt-head-col">Conversion</div>
             <div class="rt-head-col ">Month 0</div>
             <div class="rt-head-col ">Month 1</div>
             <div class="rt-head-col ">Month 2</div>
             <div class="rt-head-col ">Month 3</div>
             <div class="rt-head-col ">Month 4</div>
             <div class="rt-head-col ">Month 5</div>
             <div class="rt-head-col ">Month 6</div>
             <div class="rt-head-col ">Month 7</div>
             <div class="rt-head-col ">Month 8</div>
             <div class="rt-head-col ">Month 9</div>
             <div class="rt-head-col ">Month 10</div>
             <div class="rt-head-col ">Month 11</div>
        </div>';
		return $str;
}


?>