<?php
include('init.php');

if (!isset($_SESSION['userid'])){
	header('location:index.php');
	exit();
}

if (isset($_POST['forum_id'])){
	$subject  = trim($_POST['subject']);
	$message  = trim($_POST['message']);
	$user_name = $_SESSION['username'];
	$user_id   = $_SESSION['userid'];
	$ip       = $_SERVER['REMOTE_ADDR'];
	$posted   = time();
	$forum_id = $_POST['forum_id'];
	if (empty($subject) || empty($message)){
		header('location:newtopic.php?id='.$forum_id);
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
	(subject,message,user_name,user_id,ip,posted) VALUES (?,?,?,?,?,?)";
	$sth = $dbh->prepare($sql);
	$sth->execute(array($subject,$message,$user_name,$user_id,$ip,$posted));
	$post_id = $dbh->lastinsertid();

	$sql = "INSERT INTO topics
	(subject,user_name,user_id,posted,firstpost_id,lastpost_id,lastposter,lastposted,forum_id) VALUES (?,?,?,?,?,?,?,?,?)";
	$sth = $dbh->prepare($sql);
	$sth->execute(array($subject,$user_name,$user_id,$posted,$post_id,$post_id,$user_name,$posted,$forum_id));
	$topic_id = $dbh->lastinsertid();
	
	$sql = "UPDATE posts SET topic_id=? WHERE id=?";
	$sth = $dbh->prepare($sql);
	$sth->execute(array($topic_id,$post_id));

	header("location:viewtopic.php?id=".$topic_id);
	exit();
}

$forum_id = $_GET['id'];
$sql = "SELECT forum_name FROM forums WHERE id=? LIMIT 1";
$sth = $dbh->prepare($sql);
$sth->execute(array($forum_id));
$res = $sth->fetchObject();
if (!$res){
	header('location:index.php');
	exit();
}

//breadcrumb
ob_start();
?>
<a href="index.php"><?php echo $lang['HOME']; ?></a>&nbsp;=>&nbsp;<?php echo '<a href="viewforum.php?id='.$forum_id.'">'.
$res->forum_name.'</a>'; ?>&nbsp;=>&nbsp;<?php echo $lang['NEWTOPIC']; ?>
<?php
$breadcrumb = ob_get_clean();
//contents
ob_start();
?>
<form action="newtopic.php" method="post" accept-charset="UTF-8">
	<?php echo $lang['SUBJECT']; ?>:<br />
	<input style="font-family:Arial;font-size:14px" type="text"
		size="70" maxlength="55" required name="subject"><br />
	<?php echo $lang['MESSAGE']; ?>:<br />
	<textarea rows="10" cols="100" maxlength="2048" required name="message"></textarea>
	<br />
	<input type="hidden" name="forum_id" value="<?php echo $forum_id; ?>"></input>
	<input type="submit" value="<?php echo $lang['SUBMIT']; ?>"></input>
</form>
<?php
$contents = ob_get_clean();
include('finish.php');
?>