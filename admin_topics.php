<?php
include('init.php');

if (!isset($_SESSION['userid']) || $_SESSION['usertype'] != 'admin'){
	header('location:index.php');
	exit();
}

$forum_id = isset($_GET['id'])? $_GET['id'] : 0;
$sql = "SELECT forum_name FROM forums WHERE id=? LIMIT 1";
$sth = $dbh->prepare($sql);
$sth->execute(array($forum_id));
$forum = $sth->fetchObject();
if (!$forum){
	header('location:admin_forums.php');
	exit();
}

if(isset($_POST['del_id'])){
	$topic_id = $_POST['del_id'];
	$sth = $dbh->prepare("SELECT id FROM posts WHERE topic_id = ".$topic_id);
	$sth->execute();
	foreach($sth->fetchAll(PDO::FETCH_OBJ) as $post){
		$dbh->exec("DELETE FROM posts WHERE id = ".$post->id);
	}
	$dbh->exec("DELETE FROM topics WHERE id = ".$topic_id);
}

//breadcrumb
$breadcrumb = '<a href="admin_forums.php">'.$lang['HOME'].'</a>&nbsp;=>&nbsp;'.$forum->forum_name;

//contents
ob_start();
$sql = "SELECT * FROM topics WHERE forum_id = ? ORDER BY lastposted DESC LIMIT 40";
$sth = $dbh->prepare($sql);
$sth->execute(array($forum_id));
echo '<table>';
foreach($sth->fetchAll(PDO::FETCH_OBJ) as $topic){
	echo '<tr><td><span class="vfma"><a href="admin_posts.php?id='.$topic->id.'">'.$topic->subject.'</a></span></td>'."\n";
	echo '<td>';
?>
<form action="<?php echo $_SERVER['PHP_SELF'].'?id='.$forum_id; ?>" method="post">
<input type="hidden" name="del_id" value="<?php echo $topic->id ?>" />
<input type="submit" value="Delete"></form>
<?php	
	echo '</td></tr>'."\n";
}
echo '</table>';
$contents = ob_get_clean();

//write template
include('finish.php');
?>