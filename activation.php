<?php
include('init.php');

if (!isset($_GET['key'])){
	header('location:index.php');
	exit();
}
//breadcrumb
$breadcrumb = $lang['HOME'];

//contents
$key = $_GET['key'];
if (strlen($key) != 40)
	$contents = $lang['SOME_IS_WRONG'];
else{
	$sql = "SELECT id FROM users 
		WHERE usertype='activate' AND act_key=? LIMIT 1";
	$sth = $dbh->prepare($sql);
	$sth->execute(array($key));
	$match = $sth->fetchObject();
	if (!$match)
		$contents = $lang['SOME_WENT_WRONG'];
	else{
		$sql = "UPDATE users SET usertype=?,act_key=? WHERE id=?";
		$sth = $dbh->prepare($sql);
		$sth->execute(array('member','',$match->id));

		$contents = $lang['IS_ACTIVATED'];
		}
	}

include('finish.php');
?>