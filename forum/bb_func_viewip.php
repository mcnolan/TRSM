<?php
if (!defined('INCLUDED776')) die ('Fatal error.');
$title.=$l_userIP;

if ($logged_admin==1 or $isMod==1) {
$listUsers='';
$l_usersIPs = $l_usersIPs." ".$postip;
if ($row=DB_query(59,0)) {
$listUsers.="<ul>";
do {
$star = ($row[1]!=0?"<a href=\"{$indexphp}action=userinfo&user={$row[1]}\">*</a>":"");
$listUsers.="<li><p>{$row[0]}{$star}";
}
while ($row=DB_query(59,1));
$listUsers.="</ul>";
}
else {
$listUsers=$l_userNoIP;
}
}
echo load_header(); echo ParseTpl(makeUp('tools_userips')); return;
?>