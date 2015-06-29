<?php
include('init.php'); //title, subtitle, menu

//breadcrumb
$breadcrumb = $lang['HOME'];

//contents
ob_start();
echo '<table>';
$sth = $dbh->prepare("SELECT * FROM forums ORDER BY display_order");
$sth->execute();
foreach($sth->fetchAll(PDO::FETCH_OBJ) as $forum){
	$sth = $dbh->prepare("SELECT lastposter,lastposted FROM topics WHERE forum_id=? ORDER BY lastposted DESC LIMIT 1");
	$sth->execute(array($forum->id));
	$latest = $sth->fetchObject();
	echo '<tr><td class="idxa"><a href="viewforum.php?id='.$forum->id.'">'.$forum->forum_name.'</a></td>';
	echo '<td class="idxb">';
	if($latest){
		echo strftime($lang['timeformat'],$latest->lastposted).'&nbsp;'.$latest->lastposter;
	}
	echo '</td></tr>'."\n";
	echo '<tr><td colspan="2" class="idxc">'.$forum->forum_description.'</td></tr>'."\n";
	echo '<tr><td colspan="2"><hr></td></tr>'."\n";
}
echo '</table>';
$contents = ob_get_clean();

//write template
include('finish.php');
?>