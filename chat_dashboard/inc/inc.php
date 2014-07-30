<?php
ob_start();
// Turn on warnings
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require_once("../../const.php");
//require_once(__DIR__ . "/css/style.css");
require_once("../../common.php");
if( $current_pname == 'students')
date_default_timezone_set("Asia/Tokyo");
require_once(DIR_CLASSES . "class_db_this.php");
require_once("./php_classes/class_auth.php");
require_once("./php_classes/class_user.php");
require_once("./php_classes/class_student.php");
require_once("./php_classes/class_status.php");
require_once("./php_classes/class_plan.php");
