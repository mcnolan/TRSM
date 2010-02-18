<?php
if (!defined('INCLUDED776')) die ('Fatal error.');
if(!isset($dy)) $dy=0;

if ($logged_admin==1 or $isMod==1) {
switch ($dy) {
case 1:
$topicsDel = DB_query(23,0);
$postsDel = DB_query(24,0)-1;
$errorMSG=$l_topicsDeleted.$topicsDel."<br>".$l_postsDeleted.$postsDel; 
if ($postsDel < 0 and $topicDel == 0) $errorMSG.="<br>".$l_itseemserror;
$correctErr = "<a href=\"{$indexphp}action=vtopic&amp;forum=$forum\">$l_back</a>";
$title.=$l_topicsDeleted;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
break;

default:
if(!$topic) $topic = -1;
$topicName = DB_query(25,0);
if(!$topicName) {
$errorMSG=$l_topicnotexists; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$title.=$l_topicnotexists;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
else {
$title.=$l_postsDeleted;
echo load_header(); echo ParseTpl(makeUp('tools_del_topic_warning')); return;
}
}
}
else {
$errorMSG=$l_forbidden; $correctErr='';
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
?>