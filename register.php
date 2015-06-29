<?php
include('init.php');

if (isset($_SESSION['userid'])){
	header('location:index.php');
	exit();
}
$sql = "SELECT usertype FROM users WHERE ip = ? LIMIT 1";
$sth = $dbh-> prepare($sql);
$sth->execute(array($_SERVER['REMOTE_ADDR']));
if($sth->fetchObject() == 'banned'){
	$contents = $lang['YOUBANNED'];
	include('finish.php');
}

$breadcrumb = '<a href="index.php">'.$lang['HOME'].'</a>&nbsp;=>&nbsp;'.$lang['REGISTER'];

$error = $username = $email = "";
if(isset($_POST['register'])){
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$password2= trim($_POST['password2']);
	$email    = trim($_POST['email']);
	$show_email = $_POST['show_email'];
	if (strlen($username)<3 || strlen($password)<3
		|| $password2!=$password || strlen($email) < 9){
		$error = $lang['ERROR1'];	
	}else{
		$sql = "SELECT username FROM users WHERE LOWER(username) = ? LIMIT 1";
		$sth = $dbh->prepare($sql);
		$sth->execute(array(strtolower($username)));
		$match = $sth->fetchObject();
		if ($match){
			$error = $lang['ERROR2'];
		}else{
			$sql = "SELECT email FROM users WHERE usertype<>? AND email=? LIMIT 1";
			$sth = $dbh->prepare($sql);
			$sth->execute(array('admin',$email));
			$match = $sth->fetchObject();
			if ($match){
				$error = $lang['ERROR3'];
			}else{
				$act_key  = sha1($username.time());
				$passhash = sha1($password);
				$ip = $_SERVER['REMOTE_ADDR'];
				$joined = time();

				$sql = "INSERT INTO users VALUES (?,?,?,?,?,?,?,?,?)";
				$sth = $dbh->prepare($sql);
				$sth->execute(array(null,$username,$passhash,'activate',$act_key,$email,$show_email,$ip,$joined));

				// send mail
				$sql = "SELECT email FROM users WHERE usertype='admin' LIMIT 1";
				$sth = $dbh->prepare($sql);
				$sth->execute();
				$admin = $sth->FetchObject();
				$host    = 'http://'.$_SERVER['SERVER_NAME'].
							str_replace('register.php','activation.php',$_SERVER['PHP_SELF']);
				$to 	 = $email;
				$subject = '=?UTF-8?B?'.base64_encode($lang['ACT_SUBJ'].$title).'?=';
				$message = $lang['ACT_MSG1'].$title.'<br />'.
						   $lang['ACT_MSG2'].'<br />
							<a href="'.$host.'?key='.$act_key.'">'.$lang['ACT_MSG3'].'</a>';
				$headers = "From: hollumgollum@hotmail.com \r\n".
						   "Reply-To: no-reply@noreplay.com \r\n".
						   "MIME-Version: 1.0 \r\n".
						   "Content-type: text/html; charset=UTF-8 \r\n";
				mail($to, $subject, $message, $headers);
				$contents = '<br />'.$lang['REGISTER_DONE'].'<br />&nbsp;';
				//write template
				include('finish.php');
			}
		}
	}
}

//contents
ob_start();
?>
<form action="register.php" method="post" accept-charset="UTF-8">
	<span style="color:#ff0000"><?php echo $error; ?>&nbsp;</span>
	<table>
	<tr>
	<td><label for="username"><?php echo $lang['USERNAME']; ?></label></td>
	<td><input type="text" name="username" value="<?php echo $username; ?>"
		size="32" maxlength="25" required />
		<?php echo $lang['UNAME_3TO25']; ?></td></tr>
	<tr>
	<td><label for="password"><?php echo $lang['PASSWORD']; ?></label></td>
	<td><input type="password" name="password" maxlength="10" required />
		<?php echo $lang['PWORD_3TO10']; ?></td></tr>
	<tr>
	<td><label for="password2"><?php echo $lang['PASSWORD']; ?></label></td>
	<td><input type="password" name="password2" maxlength="10" required />
		<?php echo $lang['CONFIRM']; ?></td></tr>
	<tr>
	<td><label for="email"><?php echo $lang['EMAIL']; ?></label></td>
	<td><input type="text" name="email" value="<?php echo $email; ?>"
		size="45" maxlength="40" required />
		<?php echo $lang['NEED_FOR_REG']; ?></td></tr>
	<tr>
	<td><label for="email"><?php echo $lang['EMAIL']; ?></label></td>
	<td><select name="show_email">
		<option value="0"><?php echo $lang['NO']; ?>
		<option value="1"><?php echo $lang['YES']; ?></option>
		</option></select>
		<?php echo $lang['WANT_SHOW_EMAIL']; ?></td></tr>
	<tr>
	<td></td><td><input type="submit" value="<?php echo $lang['SUBMIT']; ?>" /></td></tr>
	</table>
	<input type="hidden" name="register" value="1" />
</form>
<?php
$contents = ob_get_clean();

//write template
include('finish.php');

?>

