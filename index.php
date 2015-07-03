<?php
include('init.php'); //title, subtitle, menu

//breadcrumb
$breadcrumb = $lang['HOME'];

//contents
ob_start();
$sth = $dbh->prepare("SELECT * FROM forums ORDER BY display_order");
$sth->execute();
foreach($sth->fetchAll(PDO::FETCH_OBJ) as $forum){
	$sth = $dbh->prepare("SELECT lastposter,lastposted FROM topics WHERE forum_id=? ORDER BY lastposted DESC LIMIT 1");
	$sth->execute(array($forum->id));
	$latest = $sth->fetchObject();
	echo '<div class="idxa"><a href="viewforum.php?id='.$forum->id.'">'.$forum->forum_name.'</a></div>';
	echo '<div class="idxb">';
	if($latest){
		echo strftime($lang['timeformat'],$latest->lastposted).'&nbsp;'.$latest->lastposter;
	}
	echo '</div>'."\n";
	echo '<div class="clear"></div>';
	echo '<div class="idxc">'.$forum->forum_description.'</div>'."\n";
	echo '<hr />'."\n";
}
$contents = ob_get_clean();

//write template
include('finish.php');
?>