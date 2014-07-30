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
require_once("../php_classes/class_lus.php");

if(isset($_POST['todo']))
$todo = $_POST['todo'];

	if($todo =='getData')
	{
		Lus::getData();
	}
	if($todo =='getSummaryData')
	{
		Lus::getSummaryData();
	}
	