<?php
include('init.php');

$forum_id = $_GET['id'];
$sql = "SELECT forum_name FROM forums WHERE id=? LIMIT 1";
$sth = $dbh->prepare($sql);
$sth->execute(array($forum_id));
$forum = $sth->fetchObject();
if (!$forum){
	header('location:index.php');
	exit();
}

//breadcrumb
$breadcrumb = '<a href="index.php">'.$lang['HOME'].'</a>&nbsp;=>&nbsp;'.$forum->forum_name;

//contents
ob_start();
$sql = "SELECT * FROM topics WHERE forum_id = ? ORDER BY lastposted DESC LIMIT 40";
$sth = $dbh->prepare($sql);
$sth->execute(array($forum_id));
if (isset($_SESSION['userid'])){
	echo '<div id="newtopic"><a href="newtopic.php?id='.$forum_id.'">'.$lang['NEWTOPIC'].'</a></div>'."\n";
}
echo '<table>';
foreach($sth->fetchAll(PDO::FETCH_OBJ) as $topic){
	echo '<tr>'."\n";
	echo '<td><span class="vfma"><a href="viewtopic.php?id='.$topic->id.'">'.$topic->subject.'</a></span>'."\n";
	echo '<span class="vfmb">&nbsp;'.$lang['BY'].'&nbsp;'.$topic->user_name.'.</span></td>'."\n";
	echo '<td><span class="vfmc">&nbsp;'.strftime($lang['timeformat'],$topic->lastposted);
	echo '&nbsp;'.$topic->lastposter.'</span></td>'."\n";
	echo '</tr>';
}
echo '</table>';
$contents = ob_get_clean();

//write template
include('finish.php');
?>