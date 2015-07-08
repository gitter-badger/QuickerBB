<?php
include('init.php');

if (isset($_SESSION['userid'])){
	header('location:index.php');
	exit();
}

//breadcrumb
$breadcrumb = '<a href="index.php">'.$lang['HOME'].'</a>&nbsp;=>&nbsp;'.$lang['LOGIN'];

$sql = "SELECT usertype FROM users WHERE ip=? LIMIT 1";
$sth = $dbh-> prepare($sql);
$sth->execute(array($_SERVER['REMOTE_ADDR']));
$user = $sth->fetchObject();
if($user && $user->usertype == 'banned'){
	$contents = $lang['YOUBANNED'];
	include('finish.php');
}


if(isset($_POST['submitted'])){
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$passhash = sha1($password);
	if(empty($username) || empty($password)){
		header('location:login.php');
		exit();
	}

	$sql = "SELECT * FROM users WHERE LOWER(username)=? LIMIT 1";
	$sth = $dbh->prepare($sql);
	$sth->execute(array(strtolower($username)));
	$userdata = $sth->fetchObject();
	if(!$userdata){
		$contents = '<br />'.$lang['WRONG_USER'];
		//write template
		include('finish.php');
	}
	if ($userdata->usertype == 'activate'){
		$contents = '<br />'.$lang['ACTIVATE_ACCOUNT'];
		//write template
		include('finish.php');
	}
	if ($userdata->usertype == 'banned'){
		$contents = '<br />'.$lang['YOUBANNED'];
		//write template
		include('finish.php');
	}
	if($userdata->passhash != $passhash){
		$contents = '<br />'.$lang['WRONG_USER'];
		//write template
		include('finish.php');
	}
	$_SESSION['userid']   = $userdata->id;
	$_SESSION['username'] = $userdata->username;
	$_SESSION['usertype'] = $userdata->usertype;
	header('location:index.php');
	exit();
}

//contents
ob_start();
?>
<form action='login.php' method='post' accept-charset="UTF-8">
	<br />
	<input type='text' name='username' size="32" maxlength="25" required />
	<label for='username' ><?php echo $lang['USERNAME']; ?></label>
	<br />
	<input type='password' name='password' maxlength="10" required />
	<label for='password' ><?php echo $lang['PASSWORD']; ?></label>
	<br />
	<input type='submit' value='<?php echo $lang['SUBMIT']; ?>' />
	<input type='hidden' name='submitted' value='1' />
</form>
<?php
$contents = ob_get_clean();

//write template
include('finish.php');
?>
