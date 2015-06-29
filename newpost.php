<?php
include('init.php');

if (!isset($_SESSION['userid'])) exit('Nej!');

if (isset($_POST['topic_id'])){
	$subject  = "";
	$message  = trim($_POST['message']);
	$user_name = $_SESSION['username'];
	$user_id   = $_SESSION['userid'];
	$ip       = $_SERVER['REMOTE_ADDR'];
	$posted   = time();
	$topic_id = $_POST['topic_id'];
	if (empty($message)){
		header('location:viewtopic.php?id='.$topic_id);
		exit();
	}
	$subject = strip_tags($subject);
	$subject = htmlspecialchars($subject,ENT_QUOTES);
	$message = strip_tags($message);
	$message = htmlspecialchars($message,ENT_QUOTES);
	$message = preg_replace("@www\.@",           "http://www.",$message);
	$message = preg_replace("@http://http://@",  "http://",    $message);
	$message = preg_replace("@https://http://@", "https://",   $message);
	$message = preg_replace("@(http|https)://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(/\S*)?@",
		'<a href="$0" target="_blank">$0</a>',$message);
	$message = preg_replace("@(http|https)://(\d{1,3}\.){3}\d{1,3}(/\S*)?@",
		'<a href="$0" target="_blank">$0</a>',$message);
	$message = nl2br($message);

	$sql = "INSERT INTO posts
	(subject,message,user_name,user_id,ip,posted,topic_id) VALUES (?,?,?,?,?,?,?)";
	$sth = $dbh->prepare($sql);
	$sth->execute(array($subject,$message,$user_name,$user_id,$ip,$posted,$topic_id));
	$post_id = $dbh->lastinsertid();
	
	$sql = "UPDATE topics SET lastpost_id=?, lastposter=?, lastposted=? WHERE id=?";
	$sth = $dbh->prepare($sql);
	$sth->execute(array($post_id,$user_name,$posted,$topic_id));

	header("location:viewtopic.php?id=".$topic_id);
	exit();
}
?>