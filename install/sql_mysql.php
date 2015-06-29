 <?php
if (!isset($_POST['db_name'])) exit();

// Create MySQL database tables.

$sth = $dbh->prepare(
"CREATE TABLE IF NOT EXISTS forums (
	id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
	forum_name TEXT,
	forum_description TEXT,
	display_order INTEGER)");
$sth->execute();
$sth = $dbh->prepare(
"CREATE TABLE IF NOT EXISTS topics (
	id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
	subject TEXT,
	user_name TEXT,
	user_id INTEGER,
	posted INTEGER,
	firstpost_id INTEGER,
	lastpost_id INTEGER,
	lastposter TEXT,
	lastposted INTEGER,
	forum_id INTEGER)");
$sth->execute();
$sth = $dbh->prepare(
"CREATE TABLE IF NOT EXISTS posts (
	id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
	subject TEXT,
	message TEXT,
	user_name TEXT,
	user_id INTEGER,
	ip TEXT,
	posted INTEGER,
	topic_id INTEGER)");
$sth->execute();
$sth = $dbh->prepare(
"CREATE TABLE IF NOT EXISTS users (
	id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
	username TEXT,
	passhash TEXT,
	usertype TEXT,
	act_key  TEXT,
	email    TEXT,
	show_email INTEGER,
	ip       TEXT,
	joined INTEGER)");
$sth->execute();

?>