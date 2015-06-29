<?php

if (!file_exists('config.php')){
	header('location:install/index.php');
	exit();
}
include('config.php');
include('lang/lang_'.$language.'.php');
setlocale(LC_TIME,$lang['locale']);

session_start();
if(!isset($_SESSION['userid']))
	$menu = '<a href="login.php">'.$lang['LOGIN'].'</a>
	&nbsp;&nbsp;&nbsp;<a href="register.php">'.$lang['REGISTER'].'</a>';
else{
	$menu = "\n".'<a href="logout.php">'.$lang['LOGOUT'].'</a> '.$_SESSION['username']."&nbsp;&nbsp;&nbsp;\n".
			'<a href="members.php">'.$lang['MEMBERS'].'</a>'."&nbsp;&nbsp;\n";
	if ($_SESSION['usertype'] == 'admin'){
		$menu .= '<a href="newforum.php">'.$lang['ADDFORUM'].'</a>&nbsp;&nbsp;'."\n";
		$menu .= '<a href="displayorder.php">'.$lang['DISPLAYORDER'].'</a>&nbsp;&nbsp;'."\n";
		$menu .= '<a href="admin_forums.php">'.$lang['ADMIN'].'</a>&nbsp;&nbsp;'."\n";
		$menu .= '<a href="admin_members.php">'.$lang['ADMINMEMBERS'].'</a>'."\n";
	}
}

?>