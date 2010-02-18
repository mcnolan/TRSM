<?php
if (!defined('INCLUDED776')) die ('Fatal error.');

$userData=array();
//DON'T CHANGE first index to 0
$userData[1] = trim($login);
if(!isset($passwd)) $passwd='';
if(!isset($passwd2)) $passwd2='';
$userData[2] = $passwd;
$userData[3] = $passwd2;
$userData[4] = trim($email);
$userData[5] = trim($icq);
$userData[6] = trim($website);
$userData[7] = htmlspecialchars(trim($occupation));
$userData[8] = htmlspecialchars(trim($from));
$userData[9] = htmlspecialchars(trim($interest));
$userData[10] = $showemail;
$userData[11] = $sorttopics;
$userData[15] = trim($signature);

if(isset($HTTP_POST_VARS) and count($HTTP_POST_VARS)>0) foreach($HTTP_POST_VARS as $k=>$v) $$k=htmlspecialchars(stripslashes($v));

?>