<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');
require_once("../../const.php");
//require_once(__DIR__ . "/css/style.css");
require_once(INC_PATH."common.php");
require_once(DIR_CLASSES . "class_db_this.php");
//require_once(DIR_CLASSES . "class_auth.php");
set_include_path("db/dev");

die;
//Auth::checkAuth();
?>

<html>

    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <script src="js/jquery-1.10.2.js" type="text/javascript"></script>
    
    <script src="js/jquery-ui-1.10.3.js"></script>
    <script src="js/tag-it-modified.js" type="text/javascript" charset="utf-8"></script>
    
    <script src="js/modal_window.js" type="text/javascript"></script>
    <script src="js/common.js" type="text/javascript"></script>

	<script>
			function change_teacher_status(desired_setting){
					var teacher_name = $('#teacher_name').val();

					$.ajax({
						type: "POST",
						url: 'ajax_change_teacher_status.php',
						data: "teacher_name="+teacher_name+"&desired_setting="+desired_setting,
						dataType: "html",
						
						complete: function(data){
							var val = data.responseText;
							console.log(val);
							if(val == 1){
								$('#status_update').html('activated');	
							}else{
								$('#status_update').html('de-activated');
							}
						}
					});                         

	        }


	</script>


	<body>
		<div id="status_update"></div>
		<input type="text" id="teacher_name" />
		<button value="submit" onClick="change_teacher_status(1)">activate</button>
		<button value="submit" onClick="change_teacher_status(0)">de-activate</button>			
	</body>






</html>