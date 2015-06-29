<?php
include('init.php');

if (!isset($_SESSION['userid']) || $_SESSION['usertype'] != 'admin'){
	header('location:index.php');
	exit();
}

$topic_id = isset($_GET['id'])? $_GET['id'] : 0;
$sql = "SELECT * FROM topics WHERE id=? LIMIT 1";
$sth = $dbh->prepare($sql);
$sth->execute(array($topic_id));
$topic = $sth->fetchObject();
if (!$topic){
	header('location:admin_forums.php');
	exit();
}

$sql = "SELECT forum_name FROM forums WHERE id=? LIMIT 1";
$sth = $dbh->prepare($sql);
$sth->execute(array($topic->forum_id));
$forum = $sth->fetchObject();

if(isset($_POST['del_id'])){
	$post_id = $_POST['del_id'];
	$dbh->exec("DELETE FROM posts WHERE id = ".$post_id);
}

//breadcrumb
$breadcrumb = '<a href="admin_forums.php">'.$lang['HOME'].'</a>&nbsp;=>
<a href="admin_topics.php?id='.$topic->forum_id.'">'.
	$forum->forum_name.'</a>&nbsp;=>&nbsp;'.$lang['TOPIC'];

//contents
ob_start();
$sql = "SELECT * FROM posts WHERE id=? LIMIT 1";
$sth = $dbh->prepare($sql);
$sth->execute(array($topic->firstpost_id));
$post = $sth->fetchObject();
	echo '<div class="postbit">';
	echo '<div class="postleft">';
	echo $post->user_name.'<br>';
	echo strftime($lang['timeformat'],$post->posted);
	echo '</div>'."\n";
	echo '<div class="postright">';
	echo '<span class="postsubj">'.$post->subject.'</span></br>';
	echo $post->message;
	echo '</div>'."\n";
	echo '<div class="clearer"></div>';
	echo '</div>'."\n";

$sql = "SELECT * FROM posts WHERE (id BETWEEN ? AND ?) AND topic_id=?";
$sth = $dbh->prepare($sql);
$sth->execute(array(++$topic->firstpost_id,$topic->lastpost_id,$topic_id));
$count = 1;
foreach($sth->fetchAll(PDO::FETCH_OBJ) as $post){
	++$count;
	echo '<div class="postbit">';
	echo '<div class="postleft">';
	echo $post->user_name.'<br>';
?>
<form style="margin:0px" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$topic_id; ?>" method="post">
<input type="hidden" name="del_id" value="<?php echo $post->id ?>" />
<input type="submit" value="Delete"></form>
<?php
	echo '</div>'."\n";
	echo '<div class="postright">';
	echo $post->message;
	echo '</div>'."\n";
	echo '<div class="clearer"></div>';
	echo '</div>'."\n";
}


$contents = ob_get_clean();
include('finish.php');
?>