<?php
if (!defined('INCLUDED776')) die ('Fatal error.');

//0 - 1st step, 1-delete concrete

if (($logged_admin==1 or $isMod==1) and !DB_query(42,0)) {

switch($step) {
case 1:
if($row=DB_query(41,0) and DB_query(50,0)) {
$errorMSG=$l_postDeleted; $correctErr = "<a href=\"{$indexphp}action=vthread&amp;forum=$forum&amp;topic=$topic&amp;page=$page\">$l_back</a>";
}
else {
$errorMSG=$l_itseemserror; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
}
break;

default:
$msgData=DB_query(40,0);
if($msgData){
$Poster = $msgData[0];
$title.=$l_areYouSureDeletePost;
echo load_header(); echo ParseTpl(makeUp('tools_delete_msg_warning')); return;
}
else {
$errorMSG=$l_postNotExist; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$title.=$l_postNotExist;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
}
}
else {
$errorMSG=$l_forbidden; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$title.=$l_forbidden;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
$title.=$errorMSG;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
?>