<?php
if (file_exists('../config.php'))
	exit('Already installed<br />config.php exists.');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body style="background:#99aabb;"><div style="background:#cceeff;width:626px;margin:auto;padding:10px 10px">
<h2>INSTALL step 1</h2>
<form action="install_step2.php" method="post">
<fieldset>
	<input type="text" name="title"    size="40" required> Website Name = TITLE<br />
	<input type="text" name="subtitle" size="40" required> Website Short Description<br />
	<select name="database">
		<option value="sqlite" selected>SQLite</option>
		<option value="mysql">MySQL</option>
	</select> Database<br /><br />
	<input type="submit" value="SUBMIT"><br />
</fieldset>
</form>