<?php
/*======================================================================**
**                                                                           
** Page:Menu , Menu control
** Created By : Bidhan Ch
**                                                                           
**======================================================================*/

$db = Database::init(DB_ACCOUNTS_HOST, DB_ACCOUNTS_USER, DB_ACCOUNTS_PWD, DB_ACCOUNTS_NAME);
$sql ="SELECT * FROM master_switch ORDER BY id DESC LIMIT 1";
$q = $db->doQuery($sql);
$r =mysql_fetch_assoc($q);
$switch = $r['value'];	
?>
<div id='cssmenu'>
<ul>
<li><a href="<?=SITE_URL;?>index.php" class="<?php if($current_pname == 'index') echo 'here';?>">Home</a></li>
<li class="menu_spra">|</li>
<?php if($_SESSION['user_role'] == 'administrator' || $_SESSION['user_role'] == 'viewer' ) { ?>


<li><a href="<?=SITE_URL;?>teachers.php" class="<?php if($current_pname == 'teachers') echo 'here';?>">Teachers</a></li>
<li class="menu_spra">|</li>
<li><a href="<?=SITE_URL;?>students.php" class="<?php if($current_pname == 'students') echo 'here';?>">Students</a></li>
<li class="menu_spra">|</li>
<?php if($_SESSION['user_role'] == 'administrator') { ?>
<li><a href="<?=SITE_URL;?>viewers.php" class="<?php if($current_pname == 'viewers') echo 'here';?>">Viewers</a></li>
<li class="menu_spra">|</li>
<?php } ?>
<li><a href="<?=SITE_URL;?>plans.php" class="<?php if($current_pname == 'plan') echo 'here';?>">All Plans</a></li>
<li class="menu_spra">|</li>

<li class='has-sub '><a href="#" class="<?php if($current_pname == 'change_log' || $current_pname == 'lus_log') echo 'here';?>">Log's</a>
	 <ul>
         <li class='active'><a href="<?=SITE_URL;?>status_change_log.php" class="<?php if($current_pname == 'change_log') echo 'here';?>">Status change logs</a></li>
          <li><a href="<?=SITE_URL;?>id_logs.php" class="<?php if($current_pname == 'id_logs') echo 'here';?>">Id Logs</a></li>
          <li class='last'><a href="<?=SITE_URL;?>lus_log.php" class="<?php if($current_pname == 'lus_log') echo 'here';?>">Line Url Shown logs</a></li>
      </ul>
</li>
<li class="menu_spra">|</li>
<li class='has-sub '><a href="#" class="<?php if($current_pname == 'lus_summary' || $current_pname == 'retention' || $current_pname == 'revenue_table' ) echo 'here';?>">Summary</a>
	 <ul>
         <li class='active'><a href="<?=SITE_URL;?>lus_summary.php" class="<?php if($current_pname == 'lus_summary') echo 'here';?>">Line Url Shown Summary</a></li>
          <li><a href="<?=SITE_URL;?>retention.php" class="<?php if($current_pname == 'retention') echo 'here';?>">Retention Table</a></li>
          <li class='last'><a href="<?=SITE_URL;?>revenue.php" class="<?php if($current_pname == 'revenue_table') echo 'here';?>">Revenue Table</a></li>
      </ul>
</li>
<li class=''></li>
<?php } ?>

<?php if($_SESSION['user_role'] == 'teacher') { ?>
<li><a href="<?=SITE_URL;?>slot.php" class="<?php if($current_pname == 'slot') echo 'here';?>">All Slots</a></li>
<li class="menu_spra">|</li>
<li><a href="<?=SITE_URL;?>search_teachers.php" class="<?php if($current_pname == 'search_teachers') echo 'here';?>">Search Teachers</a></li>
<li class="menu_spra">|</li>
<li><a href="<?=SITE_URL;?>id_logs.php" class="<?php if($current_pname == 'id_logs') echo 'here';?>">Id Logs</a></li>
<?php } ?>

<li class="menu_spra">|</li>
<li><a href="<?=SITE_URL;?>myprofile.php" class="<?php if($current_pname == 'myprofile') echo 'here';?>">My Profile</a></li>
<li class="menu_spra">|</li>
<li><a href="<?=SITE_URL;?>transcript_parser/dashboard.php" class="<?php if($current_pname == 'dashboard') echo 'here';?>">Transcript Parser</a></li>

<?php if($_SESSION['user_role'] == 'administrator') { ?>
<li class="menu_spra">|</li>
<?php if ($switch) { ?>
<li><a href="<?=SITE_URL;?>change_switch.php?value=0">Switch is on</a></li>
<?php } else {?>
<li><a href="<?=SITE_URL;?>change_switch.php?value=1">Switch is off</a></li>
<?php } ?>
<?php } ?>

<li style="float:right;"><a href="<?=SITE_URL;?>login.php?mode=logout" >Logout&nbsp;</a></li>
<li class="menu_spra " style="float:right;line-height:1.5">|</li>
<li style="float:right;"><a href="<?=SITE_URL;?>myprofile.php">Welcome <?php echo $_SESSION['user_name'];?>(<?php echo $_SESSION['user_role'];?>)</a></li>
<li style="float:right;padding:2px 6px;"><?php if($_SESSION['user_role'] == 'teacher') { User::face_photo($_SESSION['user_id'],$thumb = true , $w = 28 , $h = false);}?></li>


<?php /*?><li style="float:right;"><a href="<?=SITE_URL;?>login.php?mode=logout" >Logout</a></li>
<li class="menu_spra " style="float:right;">|</li>
<li style="float:right;">Welcome&nbsp;<?php echo $_SESSION['user_name'];?>(<?php echo $_SESSION['user_role'];?>)<?php //if($_SESSION['user_role'] == 'administrator') { echo date('Y-m-d H:i:s'); }?></li>
<li style="float:right;padding:2px 6px;"><?php if($_SESSION['user_role'] == 'teacher') { User::face_photo($_SESSION['user_id'],$thumb = true , $w = 30 , $h = false);}?></li>
<?php */?></ul>
</div>