<?php
if (!isset($_POST['database'])) exit();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body style="background:#99aabb;"><div style="background:#cceeff;width:626px;margin:auto;padding:10px 10px">
<h2>INSTALL step 2</h2>
<?php
$title    = $_POST['title'];
$subtitle = $_POST['subtitle'];
$database = $_POST['database'];

$contents = <<<BEGIN
<?php

\$title    = "$title";
\$subtitle = "$subtitle";\n
BEGIN;
file_put_contents('../config.php',$contents);

foreach(scandir('../lang') as $string){
	preg_match("@^lang_(.+)\.php$@",$string, $match);
	if(count($match)==2)
		$languages[] = $match[1];
	}
foreach(scandir('../style') as $string){
	preg_match("@^(.+)\.template\.php$@",$string, $match);
	if(count($match)==2)
		$styles[] = $match[1];
	}
?>
<form action="install_step3.php" method="post">
<fieldset>
<?php 
if($database=='mysql'){
?>
	MySQL:<br />
	<input type="text" name="db_server" required> Database server, often 'localhost'<br />
	<input type="text" name="db_name"   required> Database name, must have been created<br />
	<input type="text" name="db_user"   required> Database username<br />
	<input type="text" name="db_pass"   required> Database password<br />
<?php
}else{
?>
	SQLite:<br />
	<input type="text" name="db_name" required> SQLite Database file, for example: 'myforum.db'<br />
<?php	
}
?>
	Default Forum:<br />
	<input type="text" name="def_forum_name" required> Default forum name<br />
	<input type="text" name="def_forum_desc" required> Default forum description<br />
	Forum Admin:<br />
	<input type="text" name="admin_user" required> Admin username, often 'Admin'<br />
	<input type="text" name="admin_pass" required> Admin password<br />
	<input type="text" name="admin_mail" required> Admin Email address<br />
	Forum Language:<br />
<?php
	echo '<select name="language">'."\n";
	foreach($languages as $lang){
		echo '<option value="'.$lang.'">'.$lang.'</option>'."\n";
	}
	echo '</select><br />'."\n";
?>
	Forum Style:<br />
<?php
	echo '<select name="style">'."\n";
	foreach($styles as $style){
		echo '<option value="'.$style.'">'.$style.'</option>'."\n";
	}
	echo '</select><br /><br />'."\n";
?>
	<input type="submit" value="SUBMIT"><br />
</fieldset>
</form>