<?php
include('init.php');

if (!isset($_SESSION['userid']) || $_SESSION['usertype'] != 'admin'){
	header('location:index.php');
	exit();
}

$breadcrumb = '<a href="index.php">'.$lang['HOME'].'</a>&nbsp;=>&nbsp;'.$lang['DISPLAY_ORDER'];

if(isset($_POST['disp_ord'])){
	$neworder = $_POST['in'];
	foreach($neworder as $id=>$disp_order){
		$neworder[$id] = trim($disp_order);
		if (!is_numeric($neworder[$id]) || empty($neworder[$id])){
			$contents = $lang['DISP_NUMERIC'];
			include('finish.php');
		}
	}
	foreach($neworder as $id=>$disp_ord ){
		$sql = "UPDATE forums SET display_order= ? WHERE id= ?";
		$sth = $dbh->prepare($sql);
		$sth->execute(array($disp_ord,$id));
	}
header('location:index.php');
exit();
}

$sql = "SELECT * FROM forums";
$sth = $dbh->prepare($sql);
$sth->execute();
//contents
ob_start();
?>
<form action="admin_displayorder.php" method="post">
<table>
<?php
foreach($sth->fetchAll(PDO::FETCH_OBJ) as $row){
	echo "<tr>\n";
	echo "<td>".$row->forum_name."</td>";
	echo '<td><input size="1" type="text" maxlength=2" name="in['.$row->id.']"
			value="'.$row->display_order.'" required />'."</td>\n";
	echo "</tr>\n";
}
?>
</table>
<input type="hidden" name="disp_ord">
<input type="submit" value="<?php echo $lang['SUBMIT']; ?>">
</form>
<?php
$contents = ob_get_clean();
include('finish.php');
?>