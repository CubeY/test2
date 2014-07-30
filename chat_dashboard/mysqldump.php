<?php
/*======================================================================**
**                                                                           
** Page:mysql dump
** Created By : Bidhan
**                                                                           
**======================================================================*/
$current_pname = 'mysqldump';
include 'inc/inc.php';

Auth::checkAuth();
if($_SESSION['user_role'] != 'administrator' ) exit;

include 'inc/header.php';

//

if(isset($_POST['todo']) == 'create_new_db_bkp')
{
	include('Mysqldump/create_bkp.php');
	header('location:mysqldump.php');
}




 
/*$fn = 'dump'.date('YmdHis').'.sql';
$fp = fopen('dump/'.$fn, 'w');
//fwrite($fp, $content);
//fclose($fp);
//chmod($file, 0777); 
//chmod('dump/'.$fn,0777);
//echo '<pre>';
//print_r($_SERVER);
$output = exec("mysqldump  --u ".DB_ACCOUNTS_USER." --p ".DB_ACCOUNTS_PWD." --h ".DB_ACCOUNTS_HOST." ".DB_ACCOUNTS_NAME." > dump/".$fn." ");
*/



?>

<div class="menu_bar"><?php require_once("menu.php");?></div>
<div class="add_form">
<form name="add_plan_frm" id="add_plan_frm" action="" method="post" >
<input type="hidden" name="todo"  value="create_new_db_bkp"/>

    <div class="clear"></div>
     <div class="single_row">
        <div><input type="submit" value="Create New Mysql Dump" class="cancel_small" style="padding:7px 45px;height:auto;color:#dddddd;" /></div>
    </div>
    <div class="clear"></div>
</div>
</form>
</div>
<script type="text/javascript">

function delete_dump(row,file)
{
	var u_name = $('.block_lebel_name_'+row).text();
	if(confirm('Are you sure you want to delete sql dump file '+u_name+'?') == false ) return false;
	var todo = 'delete_dump';
	$.ajax({  
	type: "POST", 
	url: 'Mysqldump/delete_dump.php', 
	data: "todo="+todo+"&file="+file,
	dataType: "html",

	success: function(data){
	$("#usrTr_"+row).remove();
	$(".user_block_"+row).remove();
	
	}
});
}

 </script>

<div class="clear"></div>

<style>
.table_view {
    display:table;
	width:1340px;
}
.header {
    di splay:table-header-group;
    font-weight:bold;
	text-align:center;
}
.header .cell{height:30px;background:#666;color:#FFF;float:left;}
.cell {
    dis play:table-cell;
    width:auto;
	m ax-width:100px;
	backg round-color:#069;
	ma rgin:2px;
	border:1px solid #FFFFFF;
	float:left;
	height:35px;
	background:#E2E2E2;
	display:block;
	font-size:17px;
	text-align:center;
	color:#1d1d1d;
	line-height:1.5;
}
.rowGroup{line-height: 19px !important;}
.u_sl{width:40px;text-align:center;}
.u_uname{width:430px;}
.u_em{width:250px;b order:1px solid red;padding-left:5px;}
.u_price{width:350px;}
.u_ll{width:200px;text-align:center;}
.u_ac{width:510px;text-align:center;}
</style>
<div class="list_plan" >
<div class="table_view">
  <div class="header nav">
    <div class="cell u_sl">Sl</div>
    <div class="cell u_uname">File Name </div>
    <div class="cell u_price">Date Created</div>
    <div class="cell u_ac">Action</div>
  </div>
 <div class="clear"></div>
  <?php 
 
 $dir = "./Mysqldump/dump/";





  $all_dump = dirToArray($dir);
 //print_r($all_dump);
 if(count($all_dump) > 0)
 {
  for($i_dump = 0;$i_dump < count($all_dump);$i_dump++)
  {
	  ?>
    
    <div id="usrTr_<?php echo $i_dump;?>" name="usrTr_<?php echo $i_dump;?>" class="rowGroup">
    <div class="cell u_sl"><?php echo $i_dump+1;?></div>
     <div class="cell u_uname block_lebel_name_<?php echo $i_dump;?>"><?php echo $all_dump[$i_dump];?></div>
      <div class="cell u_price "><?php echo date('Y-m-d H:i:s',strtotime(str_replace(array('dump_','.sql','.gz'),'', $all_dump[$i_dump])));?></div>
      <div class="cell u_ac">
      <div style="margin-top:5px;">
      	<span ><a title="Download file" href="<?=SITE_URL;?>Mysqldump/download.php?f=<?php echo $all_dump[$i_dump];?>"><img src="media/images/1404310528_download.png" width="28" class="action_btn_img"   /></a>&nbsp;&nbsp;<img src="media/images/1389630919_cross-24.png" class="action_btn_img" title="Delete file" onclick="delete_dump('<?php echo $i_dump;?>','<?php echo $all_dump[$i_dump];?>');" /> </span>
      </div>
   </div>
    </div>
   <div class="clear"></div>
	 <?php 
  }
  }
 else {
	 echo '<h2>No dump file found. </h2>';
 }
  ?>
  </div>
</div>
<div style="height:100px;">
</div>
<?php 
function dirToArray($dir) { 
   
   $result = array(); 

   $cdir = scandir($dir); //glob($dir.'*.{sql}', GLOB_BRACE);
   foreach ($cdir as $key => $value) 
   { 
    
	if( pathinfo($value, PATHINFO_EXTENSION) == 'gz'){
	  if (!in_array($value,array(".",".."))) 
      { 
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
         { 
            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
         } 
         else 
         { 
            $result[] = $value; 
         } 
      } 
	}
   } 
   
   return $result; 
} 
?> 
</head>