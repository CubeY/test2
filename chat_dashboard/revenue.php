<?php
/*======================================================================**
**                                                                           
** Main entry point.
** All PHP (mode) calls will go through this script.  
**                                                                           
**======================================================================*/
$current_pname = 'revenue_table';
include 'inc/inc.php';

Auth::checkAuth();



include 'inc/header.php';
?>


   
 <div class="menu_bar"><?php require_once("menu.php");?></div>

<?php include('revenue_table.php');?>
