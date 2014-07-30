<?php
/*======================================================================**
**                                                                           
** Page:Create mysql dump
** Created By : Bidhan
**                                                                           
**======================================================================*/
?>
<?php
// Turn on warnings
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 'on');
chdir(dirname(__FILE__));
require_once("../../../const.php");
echo $dir =  dirname(__FILE__);
$fn = 'dump_'.date('YmdHis').'.sql';
chmod($dir.'/dump/',0777);
$fileperms = fileperms($dir.'/dump/');
//echo $fileperms;

echo '<br />Permission:'.$dir.'/dump/@'.substr(sprintf('%o', $fileperms), -4);
$fp = fopen($dir.'/dump/'.$fn, 'w');
//fwrite($fp, $content);
//fclose($fp);
//chmod($file, 0777); 
chmod($dir.'/dump/'.$fn,0777);

echo '<br />Permission:'.$dir.'/dump/'.$fn.'@'.substr(sprintf('%o', fileperms($dir.'/dump/'.$fn)), -4);

//echo '<pre>';
//print_r($_SERVER);


include_once(dirname(__FILE__) . '/src/Mysqldump.php');
$dump = new Fugu\Mysqldump\Mysqldump( DB_ACCOUNTS_NAME, DB_ACCOUNTS_USER , DB_ACCOUNTS_PWD, DB_ACCOUNTS_HOST, 'mysql', array() );
$dump->start('./dump/'.$fn);