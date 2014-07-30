<?php


/*======================================================================**
**                                                                           
** Page:User , manage all users
** Created By : Bidhan
**                                                                           
**======================================================================*/

mail('bidhan.ssca@gmail.com','Hello','Helo world');

?><!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html lang='en-US' xmlns='http://www.w3.org/1999/xhtml'>

<head>
    <title>Chat Ops</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />      
    <link rel="stylesheet" href="css/matrix.css" type="text/css" media="all" />      
    <link rel="stylesheet" href="css/modal_style.css" type="text/css" media="all" />        
    <link rel="stylesheet" href="css/flick/jquery-ui.css" /> 
	<script src="js/jquery-1.10.2.js" type="text/javascript"></script>
    <script src="js/jquery.datetimepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
    
    <?php /*?><script src="js/jquery-ui-1.10.3.js"></script>
    <script src="js/tag-it-modified.js" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" type="text/css" href="css/jquery.tagit.css">
    <link rel="stylesheet" href="css/flick/jquery-ui.css" />
    
    <script src="js/modal_window.js" type="text/javascript"></script>
    <script src="js/common.js" type="text/javascript"></script><?php */?>
  <script src="js/jquery.validate.js"></script>
     <script src="js/jquery-ui-1.10.3.js"></script>


<?php

/*======================================================================**
**                                                                           
** Main entry point.
** All PHP (mode) calls will go through this script.  
**                                                                           
**======================================================================*/

// Turn on warnings
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require_once("../../const.php");
require_once("../../common.php");
require_once(DIR_CLASSES . "class_db_this.php");
require_once("./php_classes/class_auth.php");
require_once("./php_classes/class_user.php");
require_once("./php_classes/class_student.php");
require_once("./email/email.php");

//require_once(DIR_FUNC . "include_functions.php");
Auth::checkAuth();
$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);

$current_pname = 'students';

//date difference

   $daylen = 60*60*24;

   echo $date1 = '2014-04-26';
   echo "<br />";

   echo $date2 = date('Y-m-d');
   echo "<br />";
   $fullDays =  (1399314600-strtotime($date2))/$daylen;
   
   echo "Differernce is $fullDays days";
   echo "<br />";
   
   echo $date = date('Y-m-d');
   echo "<br />";
   echo $exWil = date('Y-m-d', strtotime($date . ' - 1 day'));
   echo "<br />";
   
   $fullDays1 =  (strtotime($date)-strtotime($exWil))/$daylen;
   
   echo "Differernce is $fullDays1 days";
   echo "<br />";
?>

</head>