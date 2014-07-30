<?php
/*======================================================================**
**                                                                           
** Page: handel mysql file
** Created By : Bidhan
**                                                                           
**======================================================================*/
?>
<?php

if(isset($_POST['todo']))
$todo = $_POST['todo'];
	
	
	if($todo =='delete_dump')
	{
		$fn_gz = $_POST['file'];
		$fn_sql = str_replace('.gz','',$fn_gz);
		unlink('dump/'.$fn_gz);
		unlink('dump/'.$fn_sql);
		exit;
	}
	?>