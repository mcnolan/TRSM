<?php
if (!defined('INCLUDED776')) die ('Fatal error.');

if(!isset($chstat)) die('Fatal error.'); else $status=$chstat;

$topicData=DB_query(5,0);
$currStat=$topicData[1];

if ((DB_query(26,0)==TRUE and ($status==1 or ($status==0 and $userUnlock==1)) and $currStat!=9 and $currStat!=8) or $logged_admin==1 or $isMod==1) {
if (DB_query(27,$status)>0) {
$errorMSG = (($status==1||$status==8)?$l_topicLocked:$l_topicUnLocked);
}
else {
$errorMSG=$l_itseemserror;
}
$correctErr = "<a href=\"{$indexphp}action=vthread&amp;forum=$forum&amp;topic=$topic\">$l_back</a>";
}
else {
$errorMSG=$l_forbidden; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$title.=$l_forbidden;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
$title.=$errorMSG;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
?>