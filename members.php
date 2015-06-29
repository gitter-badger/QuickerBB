<?php
include('init.php');

if (!isset($_SESSION['userid'])){
	header('location:index.php');
	exit();
}

$breadcrumb = '<a href="index.php">'.$lang['HOME'].'</a>&nbsp;=>&nbsp;'.$lang['MEMBERS'];

ob_start();
?>
<table>
<?php
$sql = "SELECT username,email FROM users WHERE usertype=? ORDER BY joined";
$sth = $dbh->prepare($sql);
$sth->execute(array('admin'));
foreach($sth->fetchAll(PDO::FETCH_OBJ) as $admin){
	echo '<tr><td>'.$admin->username.'&nbsp;&nbsp;</td>';
	echo '<td>admin&nbsp;&nbsp;</td><td></td></tr>'."\n";
}
$sql = "SELECT username,email,show_email FROM users WHERE usertype=? ORDER BY LOWER(username)";
$sth = $dbh->prepare($sql);
$sth->execute(array('member'));
foreach($sth->fetchAll(PDO::FETCH_OBJ) as $member){
	echo '<tr><td>'.$member->username.'&nbsp;&nbsp;</td><td>member&nbsp;&nbsp;</td>'."\n";
	if($member->show_email){
		echo '<td><a href="mailto:'.$member->email.'">'.$member->email.'</a></td></tr>'."\n";
	}else{
		echo '<td></td></tr>'."\n";
	}
}
?>
</table>
<?php
$contents = ob_get_clean();

include('finish.php');
?>