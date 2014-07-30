<?php
/*======================================================================**
**                                                                           
** Page:Ajax get_face_photo , get_face_photo
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
$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
		
if(isset($_POST['todo']))
$todo = $_POST['todo'];

	if($todo =='get_face_photo')
	{
		$thumb = $_POST['thumb'];
		$w = $_POST['w'];
		$h = $_POST['h'];
		$data = array();
		$sql2= "SELECT teacher_id,face_photo FROM teachers";
		$q = $db->doQuery($sql2);
		
		while($row = mysql_fetch_assoc($q))
		{		
			$photo =  $row['face_photo'];
			if($photo != '')
			{
				if($thumb)
				$thumb = 'thumbnail/';
				$img =  '<img class="face_photo_prevw" src="'.SITE_URL.'/face_photo/php/files/'.$thumb.$photo.'" width="'.$w.'" height="'.$h.'" >';
			}
			else
			$img =  '<img class="face_photo_prevw" src="'.SITE_URL.'/media/images/no_profile_image.png" width="'.$w.'" height="'.$h.'" >';
			
			
			$data['t_face_'.$row['teacher_id']] = $img;
			
		}
		echo json_encode($data);
	}