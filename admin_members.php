<?php
include('init.php');

if (!isset($_SESSION['userid']) || $_SESSION['usertype'] != 'admin'){
	header('location:index.php');
	exit();
}

if(isset($_POST['adm_id'])){
	$dbh->exec( "UPDATE users SET usertype = 'admin' WHERE id = ".$_POST['adm_id'] );
}
if(isset($_POST['mem_id'])){
	$dbh->exec( "UPDATE users SET usertype = 'member' WHERE id = ".$_POST['mem_id'] );
}
if(isset($_POST['ban_id'])){
	$dbh->exec( "UPDATE users SET usertype = 'banned' WHERE id = ".$_POST['ban_id'] );
}

$breadcrumb = '<a href="index.php">'.$lang['HOME'].'</a>&nbsp;=>&nbsp;'.$lang['ADMINMEMBERS'];

ob_start();
?>
<table>
<?php
$sql = "SELECT * FROM users ORDER BY usertype, LOWER(username)";
$sth = $dbh->prepare($sql);
$sth->execute();

foreach($sth->fetchAll(PDO::FETCH_OBJ) as $user){
	echo "<tr><td>".$user->username."</td>\n";
	echo '<td><a href="mailto:'.$user->email.'">'.$user->email.'</a></td>'."\n";
	echo '<td>'.$user->usertype.'</td>'."\n";
	echo '<td>'.$user->ip.'</td>'."\n";
	echo '<td>'."\n";
?>
<form style="margin:0px" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="adm_id" value="<?php echo $user->id ?>" />
<input type="submit" value="Admin"></form>
<?php
	echo '</td><td>'."\n";
?>
<form style="margin:0px" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="mem_id" value="<?php echo $user->id ?>" />
<input type="submit" value="Member"></form>
<?php
	echo '</td><td>'."\n";
?>
<form style="margin:0px" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="ban_id" value="<?php echo $user->id ?>" />
<input type="submit" value="Banned"></form>
<?php
	echo '</td></tr>'."\n";
}

?>
</table>
<?php
$contents = ob_get_clean();

include('finish.php');
?>