<?php
include('init.php'); //title, subtitle, menu

if (!isset($_SESSION['userid']) || $_SESSION['usertype'] != 'admin'){
	header('location:index.php');
	exit();
}

if(isset($_POST['del_id'])){
	$forum_id = $_POST['del_id'];
	$sth = $dbh->prepare("SELECT id FROM topics WHERE forum_id = ".$forum_id);
	$sth->execute();
	foreach($sth->fetchAll(PDO::FETCH_OBJ) as $topic){
		$sth = $dbh->prepare("SELECT id FROM posts WHERE topic_id = ".$topic->id);
		$sth->execute();
		foreach($sth->fetchAll(PDO::FETCH_OBJ) as $post){
			$dbh->exec("DELETE FROM posts WHERE id = ".$post->id);
		}
		$dbh->exec("DELETE FROM topics WHERE id = ".$topic->id);
	}
	$dbh->exec("DELETE FROM forums WHERE id = ".$forum_id);
}

//breadcrumb
$breadcrumb = '<a href="index.php">Index</a>';

//contents
ob_start();
echo '<table>';
$sth = $dbh->prepare("SELECT * FROM forums ORDER BY display_order");
$sth->execute();
foreach($sth->fetchAll(PDO::FETCH_OBJ) as $forum){
	echo '<tr><td><span class="idxa"><a href="admin_topics.php?id='.$forum->id.'">'.$forum->forum_name.'</a></span></td>';
	echo '<td>';
?>
<form style="margin:0px" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="del_id" value="<?php echo $forum->id ?>" />
<input type="submit" value="Delete"></form>
<?php
	echo '</td></tr>'."\n";
}
echo '</table>';
$contents = ob_get_clean();

//write template
include('finish.php');
?>