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
require_once("../php_classes/class_plan.php");

//$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);

if(isset($_POST['todo']))
$todo = $_POST['todo'];
	if($todo =='update_plan_price')
	{
		Plan::update_plan_price();
	}
	
	if($todo =='delete_plan')
	{
		Plan::delete_plan();
	}
	?>