<?php
/*======================================================================**
**                                                                           
** Page:Login
** Created By : Bidhan
**                                                                           
**======================================================================*/
$current_pname = 'index';
include 'inc/inc.php';
Auth::checkAuth();


include 'inc/header.php';

    if($_SESSION['user_role'] == 'teacher' ){
	header('Location:students.php');
	}
	if($_SESSION['user_role'] == 'administrator' || $_SESSION['user_role'] == 'viewer' ){
	header('Location:status.php');
	}