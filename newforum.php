<?php
include('init.php');

if (!isset($_SESSION['userid']) || $_SESSION['usertype'] != 'admin'){
	header('location:index.php');
	exit();
}
$breadcrumb = '<a href="index.php">'.$lang['HOME'].'</a>&nbsp;=>&nbsp;'.$lang['ADD_NEW_FORUM'];

if (isset($_POST['forum_name'])){
	$forum_name = trim($_POST['forum_name']);
	$forum_desc = trim($_POST['forum_desc']);
	if (empty($forum_name) || empty($forum_desc)){
		$contents = $lang['FORUM_NOT_EMPTY'];
	}else{
		$sql = "INSERT INTO forums
		(forum_name,forum_description) VALUES (?,?)";
		$sth = $dbh->prepare($sql);
		$sth->execute(array($forum_name,$forum_desc));
		$last_id = $dbh->lastinsertid();

		$sql = "UPDATE forums SET display_order = ? WHERE id = ?";
		$sth = $dbh->prepare($sql);
		$sth->execute(array($last_id,$last_id));
		$contents = $lang['FORUM_CREATED'];
	}
	include('finish.php');
}
//contents
ob_start();
?>
<form action="newforum.php" method="post" accept-charset="UTF-8">
	<?php echo $lang['FORUM_NAME']; ?>:<br />
	<input style="font-family:Arial;font-size:14px" type="text"
		size="40" maxlength="25" required name="forum_name">
	<br />
	<?php echo $lang['FORUM_DESC']; ?>:<br />
	<input style="font-family:Arial;font-size:14px" type="text"
		size="80" maxlength="50" required name="forum_desc">
	<br /><br />
	<input type="submit" value="<?php echo $lang['SUBMIT']; ?>">
</form>
<?php
$contents = ob_get_clean();
include('finish.php');
?>