<?php
//error_reporting(E_ALL);
/*
_install.php : installation file for miniBB (install from browser).
Copyright (C) 2001-2003 miniBB.net.

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
if (!get_cfg_var('register_globals')){
if (is_array($HTTP_POST_VARS) and count($HTTP_POST_VARS)>0) foreach($HTTP_POST_VARS as $key=>$ht) { $$key=$ht; }
if (is_array($HTTP_GET_VARS) and count($HTTP_GET_VARS)>0) foreach($HTTP_GET_VARS as $key=>$ht) { $$key=$ht; }
if (is_array($HTTP_COOKIE_VARS) and count($HTTP_COOKIE_VARS)>0) foreach($HTTP_COOKIE_VARS as $key=>$ht) { $$key=$ht; }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD><title>miniBB installation</title>
<LINK href="bb_style.css" type="text/css" rel="STYLESHEET">
</HEAD>
<body bgcolor="white">


<?
include ('./setup_options.php');
include ("./setup_$DB.php");
if(!isset($GLOBALS['indexphp'])) $indexphp='index.php?'; else $indexphp=$GLOBALS['indexphp'];
if(!isset($step)) $step='';

if (file_exists("./_install_$DB.sql")) {

switch ($step) {
case 'install':

$sql = join ('', file("./_install_$DB.sql"));


$errors = 0;
$warn = '';

if ($DB=='mysql') {
$sql = str_replace('minibb_forums', $Tf, $sql);
$sql = str_replace('minibb_posts', $Tp, $sql);
$sql = str_replace('minibb_topics', $Tt, $sql);
$sql = str_replace('minibb_users', $Tu, $sql);
$sql = str_replace('minibb_send_mails', $Ts, $sql);
$sql = str_replace('minibb_banned', $Tb, $sql);


$sql = preg_replace ("/#(.*?)\n/i", "", $sql);

$stringsSQL = explode (');', $sql);

for ($i=0; $i<sizeof($stringsSQL); $i++) {

if (trim($stringsSQL[$i])!='') {
$stringsSQL[$i] = str_replace ("\r\n", '', $stringsSQL[$i]);
$stringsSQL[$i] = str_replace ("\n", '', $stringsSQL[$i]);

$rs = mysql_query($stringsSQL[$i].');');
if (mysql_error()) {
$errors++;
$warn.="<div>Creating table ".($i+1)." failed... (".mysql_error().")</div>";
}
else $warn.="<div>Table ".($i+1)." successfully created...</div>";
}
}
if ($errors==0) {
$rs = mysql_query("INSERT INTO $Tu (user_id, username, user_password, user_email, user_viewemail, user_regdate) values (1, '$admin_usr', '".md5($admin_pwd)."', '$admin_email', 0, now())");
if (!mysql_error()) {
$warn.="<p>Admin data successfully added...</div>";
}
}
}


elseif($DB=='postgresql'||$DB=='postgresql72x') {
$sql = str_replace('minibb_forums', $Tf, $sql);
$sql = str_replace('minibb_posts', $Tp, $sql);
$sql = str_replace('minibb_topics', $Tt, $sql);
$sql = str_replace('minibb_users', $Tu, $sql);
$sql = str_replace('minibb_send_mails', $Ts, $sql);
$sql = str_replace('minibb_banned', $Tb, $sql);


$sql = preg_replace ("/#(.*?)\n/i", '', $sql);

$stringsSQL = explode (');', $sql);

for ($i=0; $i<sizeof($stringsSQL); $i++) {

if (trim($stringsSQL[$i])!='') {
$stringsSQL[$i] = str_replace ("\r\n", '', $stringsSQL[$i]);
$stringsSQL[$i] = str_replace ("\n", '', $stringsSQL[$i]);

$rs = pg_exec($stringsSQL[$i].');');
if (pg_errormessage()) {
$errors++;
$warn.="<div>Creating table ".($i+1)." failed... (".pg_errormessage().")</div>";
}
else $warn.="<div>Table ".($i+1)." successfully created...</div>";
}
}
if ($errors==0) {
$rs = pg_exec("INSERT INTO $Tu (user_id, username, user_password, user_email, user_viewemail, user_regdate) values (nextval('".$Tu."_user_id_seq'), '$admin_usr', '".md5($admin_pwd)."', '$admin_email', 0, now())");
if (!pg_errormessage()) {
$warn.="<p>Admin data successfully added...</div>";
}
}
}


elseif ($DB=='mssql') {
$sql = str_replace('minibb_forums', $Tf, $sql);
$sql = str_replace('minibb_posts', $Tp, $sql);
$sql = str_replace('minibb_topics', $Tt, $sql);
$sql = str_replace('minibb_users', $Tu, $sql);
$sql = str_replace('minibb_send_mails', $Ts, $sql);
$sql = str_replace('minibb_banned', $Tb, $sql);


$sql = preg_replace ("/#(.*?)\n/i", "", $sql);

$stringsSQL = explode (';', $sql);

for ($i=0; $i<sizeof($stringsSQL); $i++) {

if (trim($stringsSQL[$i])!='') {
$stringsSQL[$i] = str_replace ("\r\n", '', $stringsSQL[$i]);
$stringsSQL[$i] = str_replace ("\n", '', $stringsSQL[$i]);

$rs = mssql_query($stringsSQL[$i].';');
//if(trim(mssql_get_last_message())!='') $errors++;
}
}

$rs = mssql_query("INSERT INTO $Tu (username, user_password, user_email, user_viewemail) values ('$admin_usr', '".md5($admin_pwd)."', '$admin_email', 0)");
//if(trim(mssql_get_last_message())!='') $errors++;
}


if ($errors==0) {
$warn.="
<p>All tables successfully created! Now you can:
<li><p>Continue with miniBB options (see setup_options.php file)
<li><p><a href=\"$bb_admin?action=addforum1\">Create forums</a>
<li><p><a href=\"$bb_admin\">Go to admin panel</a>...
<p>...<a href=\"{$indexphp}\">and use your miniBB right now!</a> :)
<p><b>Don't forget to DELETE _install.php file also as _install_$DB.sql file from your directory!
<p>DO IT RIGHT NOW!!!
";
}
else {
$warn.="
<p>There were problems via setup! Possible reasons:
<li><p>It is disallowed for your DB-account to create tables;
<li><p>Login/password for database were incorrect;
<li><p>You have not created database you have entered in options (possibly, you need to do it manually);
<li><p>Tables are already created and that's why you can directly <a href=\"{$indexphp}\">go to forums now</a>.
<p>Please, refer to manual for more questions, check your setup files, or use \"handly\" creating of tables.
<p><b>Don't forget to DELETE _install.php file also as _install_$DB.sql file from your directory!
<p>DO IT RIGHT NOW!!!
";

}

echo $warn;

break;

default:
echo "<p>Welcome to miniBB setup! It takes only 1 step to create all necessary database tables. <br>Be sure you have correctly setup your \"options\" file! Refer to manual if you are having problems. <br>Also, before installing, copying or modifying miniBB, please, read the <b><a href=\"COPYING\">GPL license agreement.</a></b><p><a href=\"_install.php?step=install\">Continue setup</a>&gt;&gt;&gt;";
}

}
else {
echo "<p>Installation file is missing. Please, check your directory for _install_$DB.sql file!";
}

?>

</body>
</html>