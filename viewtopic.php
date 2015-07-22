<?php
include('init.php');

if(!isset($_GET['id'])){
	header('location:index.php');
	exit();
}
$topic_id = $_GET['id'];
$sql = "SELECT * FROM topics WHERE id=? LIMIT 1";
$sth = $dbh->prepare($sql);
$sth->execute(array($topic_id));
$topic = $sth->fetchObject();
if (!$topic){
	header('location:index.php');
	exit();
}

$sql = "SELECT forum_name FROM forums WHERE id=? LIMIT 1";
$sth = $dbh->prepare($sql);
$sth->execute(array($topic->forum_id));
$forum = $sth->fetchObject();

//breadcrumb
$breadcrumb = '<a href="index.php">'.$lang['HOME'].'</a>&nbsp;=>
<a href="viewforum.php?id='.$topic->forum_id.'">'.
	$forum->forum_name.'</a>&nbsp;=>&nbsp;'.$lang['TOPIC'];

//contents
ob_start();
$sql = "SELECT * FROM posts WHERE id=? LIMIT 1";
$sth = $dbh->prepare($sql);
$sth->execute(array($topic->firstpost_id));
$post = $sth->fetchObject();
	echo '<div class="postbit">'."\n";
	echo '<div class="postleft">';
	echo $post->user_name.'<br />';
	echo strftime($lang['timeformat'],$post->posted);
	echo '</div>'."\n";
	echo '<div class="postright">';
	echo '<span class="postsubj">'.$post->subject.'</span><br />'."\n";
	echo $post->message;
	echo '</div>'."\n";
	echo '<div class="clear"></div>';
	echo '</div>'."\n";

$sql = "SELECT * FROM posts WHERE (id BETWEEN ? AND ?) AND topic_id=?";
$sth = $dbh->prepare($sql);
$sth->execute(array(++$topic->firstpost_id,$topic->lastpost_id,$topic_id));
$count = 1;
foreach($sth->fetchAll(PDO::FETCH_OBJ) as $post){
	++$count;
	echo '<div class="postbit">'."\n";
	echo '<div class="postleft">';
	echo $post->user_name.'<br />';
	echo strftime($lang['timeformat'],$post->posted);
	echo '</div>'."\n";
	echo '<div class="postright">';
	echo $post->message;
	echo '</div>'."\n";
	echo '<div class="clear"></div>';
	echo '</div>'."\n";
}

if(isset($_SESSION['userid']) && $count<40){
?>
	<form action="newpost.php" method="post" accept-charset="UTF-8">
	<?php echo $lang['REPLY']; ?>:<br />
	<textarea rows="10" cols="100" maxlength="2048" required name="message"></textarea>
	<br />
	<input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>"></input>
	<input type="submit" value="<?php echo $lang['SUBMIT']; ?>"></input>
	</form>
<?php
}
$contents = ob_get_clean();
include('finish.php');
?>