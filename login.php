<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: login.php
| Build 4
| <Changes>
| Fixed register globals issue - lines 33 & 71
| Changed Css
| Corrected issue with file being executed directly
| 
| <Purpose>
| Login form & processor
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
if($pos == "side") {
?>
<form action="<? echo $path; ?>login.php" method="post">
<table class="loginTable">
<tr>
	<td>Username:<br> 
	<input type="text" size="15" name="user_usr"></td>
</tr>
<tr>
	<td>Password:<br>
	<input type="password" size="15" name="user_pwd"></td>
</tr>
<tr>
	<td colspan="2"><input type="submit" value="login"></td>
</tr>
</table>
<input type="hidden" name="referer" value="<? echo $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']; ?>">
</form>
<?
} else {
$pagename = "Login";
include_once "inc.inc";
if(trim($_POST['user_usr']) <> "") {
$getpla = "SELECT username, user_password, crewid, userlevel FROM " . $table['users'] . " AS minibb_users WHERE username = '" . $_POST['user_usr'] . "' LIMIT 1";
$res = $sql->queryme($getpla);
$row = $sql->sql_array($res);
// It means that username exists in database; so let's check a password
$username = $row['username']; $userpassword = $row['user_password'];
	if ($username == $_POST['user_usr'] and $userpassword == md5($_POST['user_pwd'])) {
		if($row['userlevel'] > 1) {
			$logged_admin = 1;
			setcookie('admin', "rar", $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
		} else {
			$logged_user = 1;
		}
		$cook = $_POST['user_usr']."|".md5($_POST['user_pwd'])."|".$cookieexptime;
		setcookie('userl', $row['userlevel'], $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
		setcookie($cookiename, $cook, $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
		setcookie('logged', "yes", $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
		setcookie('crewid', $row['crewid'], $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
	} else {
	//password wrong
	$loginError = 1;
	}
} else { $loginError = 1; }
include "header.php";
if(@$loginError == '1') {
	echo "Login Failed, Username and/or Password incorrect!";
	if(empty($_POST['referer'])) { $_POST['referer'] = "index.php"; }
} else {
	echo "Login Successful";
}
include "footer.php";

?>
<META HTTP-EQUIV="REFRESH" content="2;url=<? echo $_POST['referer']; ?>">
<?
}
?>