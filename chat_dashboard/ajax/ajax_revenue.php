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

require_once("../php_classes/class_plan.php");
if(isset($_POST['todo']))
$todo = $_POST['todo'];

	if($todo =='get_revenue_table')
	{
		$all_plans = Plan::all_plans();
		 
	//	$ntd[1] = '';
		?>
         <div style="display:table;width:100%;bo rder:1px solid #ccc;margin-bottom:10px;" class="table_view">
        <?php
		echo get_tbl_head_html($all_plans);
		$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
		$rt_reach_cur = 50;
		$rt_all_student_omitted_ary = array();
		$rt_cur_year = get_japan_time('Y');
		$rt_cur_month = get_japan_time('m');
		$rt_row_month = date('2014-01-01'); // 
		
		$rtmrow_ary = array();
		// $rt_p_i = $rt_cur_month-1 ;
		//$rt_ii = 0;
		//$rt_addi = 0;
		$rt_total_im = $rt_cur_month+0;
		//$rt_im_to_show = 0;
		$will_omitted = array();
		 
		$calcelled_ary = array();
			// total calcelled by month
			$sql_calcelled = "SELECT count( id ) AS total_omitted ,plan, month( cancellation_date ) AS
						month , year( `cancellation_date` ) AS year 
						FROM `users_list`
						WHERE  `cancellation_date` != '0000-00-00 00:00:00'
						GROUP BY month( cancellation_date ),plan ORDER BY month( cancellation_date ) ASC ";
			$q_sql_calcelled = $db->doQuery($sql_calcelled);
			while($cr = mysql_fetch_assoc($q_sql_calcelled))
			{
				$calcelled_ary[] =$cr;
			}
			//echo '<div style="clear:both;" class="clear"></div>';
 //echo '<pre style="font-size:11px;line-height:1">'; print_r($calcelled_ary); echo '</pre>';
  
  	//echo '<pre>';print_r($rtmrows_ary);echo '</pre>';
			
			
			for($rt_im = 0;$rt_im < $rt_total_im;$rt_im++)
			{	$rtmrows_ary = array();
				
				   $sql_started_rt = "SELECT count( id ) AS total,plan,  year(`first_paid_date`) AS year
							FROM `users_list`
							WHERE first_paid_date != '0000-00-00 00:00:00'
							AND first_paid_date >= '".$rt_cur_year."-01-01 00:00:00'
							AND first_paid_date <= '".date('Y-m',strtotime($rt_row_month))."-31 23:59:59' 
							GROUP BY plan
							ORDER BY plan ASC ";
							
		$q_sql_started_rt = $db->doQuery($sql_started_rt);
			
			while($rtmrow = mysql_fetch_assoc($q_sql_started_rt))
			{
				$rtmrows_ary[] =$rtmrow;
			}
			
		 //echo '<pre style="font-size:11px;line-height:1">'; print_r($rtmrows_ary); echo '</pre>';	
			
		?>
        <div style="clear:both;" class="clear"></div>
    	<div style="color:#000;font-size:11px;" >
            <div class="rt-col " style="width:158px;text-align:right;"><?php echo date('F',strtotime($rt_row_month)).'&nbsp;'.date('Y',strtotime($rt_row_month));?>&nbsp;&nbsp;</div>
            
            <?php
			$curent_plan_i = 0 ;
		    $curent_month = date('m',strtotime($rt_row_month))+0;
			$t_number_of_transaction = 0;$total_cancelled = 0;$t_revenue=0;
			$list_ary = list_ary($all_plans,$calcelled_ary,$curent_month);
			//echo '<pre style="font-size:11px;line-height:1">'; print_r($list_ary); echo '</pre>';	
            for($i=0;$i<count($all_plans);$i++)
	{
		$curent_plan = $all_plans[$i]['id'];
		
		if(isset($rtmrows_ary[$curent_plan_i]['plan']) && $rtmrows_ary[$curent_plan_i]['plan'] ==  $curent_plan)
		{
			$number_of_transaction = (isset($rtmrows_ary[$curent_plan_i]['total']))?$rtmrows_ary[$curent_plan_i]['total']:0;
			$curent_plan_i++;
		}
		else 
		$number_of_transaction = 0;
		
		
		if(isset($list_ary[$curent_month][$curent_plan]))
		{
			//echo $list_ary[$curent_month][$curent_plan].'-';
			$ntd[$curent_plan] = (isset($ntd[$curent_plan]))?$ntd[$curent_plan]:0;
			$ntd[$curent_plan] = $ntd[$curent_plan] + $list_ary[$curent_month][$curent_plan];
			$number_of_transaction = $number_of_transaction - $ntd[$curent_plan] ;
		}
		//echo $need_to_omitted = (isset($total_omitted[$curent_month][$curent_plan]))?$total_omitted[$curent_month][$curent_plan]:0;
		//echo $need_to_omitted = $total_omitted[2][1];
		//$can
		  //$total_cancelled = $total_cancelled + $calcelled_ary[date('m',strtotime($rt_row_month))+0]['total_omitted'];
		
		  $revenue = $number_of_transaction *  $all_plans[$i]['price'];
		  $t_revenue =  $t_revenue + $revenue;
		  $t_number_of_transaction = $t_number_of_transaction + $number_of_transaction;
		  
		echo  '<div style="float:left">
        			
      
					<div>
						<div class="rt-col" style="line-height:1.5;background:#6FB98C">'.$number_of_transaction.'</div>
						<div class="rt-col">'.$revenue.'</div>
					</div>
			    </div>';
				
				//$number_of_transaction = $number_of_transaction - $total_cancelled ;
				
		
	}
	
			?>
            <div class="rt-col" style="backgro und:#0CC"><?php echo $t_number_of_transaction;?></div>
            <div class="rt-col rt-c-0" ><?php echo  $t_revenue;?></div>
            
            
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
		} ?>
		<?php
	}	


	
?>
</div>


<?php
function get_japan_time($w)
{
	date_default_timezone_set("Asia/Tokyo");
	return date($w);
}

function get_tbl_head_html($all_plan)
{
	$str = '<div style="clear:both;" class="clear"></div>
	<div style="float:left">
	<div class="rt-col " style="width:158px;background:#09c;">&nbsp;</div>
	<div class="rt-col " style="width:158px;background:#09c;clear:both;">&nbsp;</div>
	</div>';
	for($i=0;$i<count($all_plan);$i++)
	{
		$str .= '<div style="float:left">
        			<div >
						<div class="rt-head-col" style=" border-right: 1px solid #09c">'.$all_plan[$i]['display_name'].'</div>
						<div class="rt-head-col" style=" border-left: 1px solid #09c">'.$all_plan[$i]['price'].'</div>
            		</div>
      
					<div>
						<div class="rt-head-col" style="line-height:1.5">Number of transaction</div>
						<div class="rt-head-col ">Revenue</div>
					</div>
			    </div>';
	}
	
	$str .= '<div style="float:left">
        			<div >
						<div class="rt-head-col" style="width:176px;">Total</div>
            		</div>
      
					<div>
						<div class="rt-head-col" style="line-height:1.5">Number of transaction</div>
						<div class="rt-head-col ">Revenue</div>
					</div>
			    </div>';
	
	//$str .= '</div>';
		return $str;
}

function list_ary($all_plans,$calcelled_ary,$curent_month)
{
		$curent_month = $curent_month-1;
			$total_omitted = array();
		for($i=0;$i<count($all_plans);$i++)
		{
			$curent_plan = $all_plans[$i]['id'];
			
			for($ci = 0;$ci<count($calcelled_ary);$ci++)
			{
				if($curent_month == $calcelled_ary[$ci]['month'])
				{
					if($calcelled_ary[$ci]['plan'] == $curent_plan)
					{
						$total_omitted[$curent_month+1][$curent_plan] = $calcelled_ary[$ci]['total_omitted'];
					}
					//else $total_omitted[$curent_month+1][$curent_plan] = 0;
				}
				//else $total_omitted[$curent_month+1][$curent_plan] = 0;
			}
		}
		//echo $curent_month;
		return $total_omitted;
}
?>