<?php
if (!isset($_POST['db_name'])) exit();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body style="background:#99aabb;"><div style="background:#cceeff;width:626px;margin:auto;padding:10px 10px">
<h2>INSTALL step 3</h2>
<?php

chdir('../');
extract($_POST);

$lang_style = <<<HERE
\$language = "$language";
\$style    = "$style";\n
HERE;
file_put_contents('config.php', $lang_style, FILE_APPEND);

if(isset($db_server)){
$db = 'mysql';

$htaccess = <<<HTCODE
php_value error_reporting 32767
php_flag display_errors on
HTCODE;
file_put_contents('.htaccess',$htaccess);

$handle = <<<BEGIN
\$dbh = new PDO('mysql:host=$db_server;dbname=$db_name','$db_user','$db_pass');

?>
BEGIN;

}else{
$db = 'sqlite';

$htaccess = <<<HTCODE
<Files "$db_name">
	Require all denied
</Files>
php_value error_reporting 32767
php_flag display_errors on
HTCODE;
file_put_contents('.htaccess',$htaccess);

$handle = <<<BEGIN
\$dbh = new PDO('sqlite:$db_name');

?>
BEGIN;

}

file_put_contents('config.php',$handle,FILE_APPEND);

include('config.php');              //Connect to database
include('install/sql_'.$db.'.php'); //CREATE TABLES

// Insert configured default values.
$sth = $dbh->prepare("INSERT INTO forums VALUES (NULL, '$def_forum_name','$def_forum_desc',1)");
$sth->execute();
$admin_passhash = sha1($admin_pass);
$ip = $_SERVER['REMOTE_ADDR'];
$time = time();
$sth = $dbh->prepare("INSERT INTO users VALUES 
(null,'$admin_user','$admin_passhash','admin','','$admin_mail',0,'$ip',$time)");
$sth->execute();
$sth = $dbh = null;

echo('Install is finished. <i>config.php</i> is written.<br />
You can visit forum index.php and Log in.<br />
<a href="../index.php">Home</a>');

?>