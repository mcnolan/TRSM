<?php
if (!defined('INCLUDED776')) die ('Fatal error.');

if ($topic) {

if ($step !=1 and $step !=0) $step = 0;
//0 - 1st step, 1-edit concrete

if (($logged_admin==1 or $isMod==1) and DB_query(5,0) and DB_query(8,0) and DB_query(28,0)>1) {

switch($step) {
case 1:

if (isset($topic) and isset($forum) and isset($forumWhere)) {
if (DB_query(51,0)) {
$title=$l_topicMoved;
$errorMSG=$l_topicMoved;
$correctErr = "<a href=\"{$indexphp}action=vthread&amp;topic=$topic&amp;forum=$forumWhere\">$l_goTopic</a>";
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
else {
$title=$l_itseemserror;
$errorMSG=$l_itseemserror;
$correctErr = "<a href=\"{$indexphp}action=vthread&amp;topic=$topic&amp;forum=$forum&amp;page=$page\">$l_back</a>";
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
}
else {
$title=$l_forbidden;
$errorMSG=$l_forbidden;
$correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}

break;

default:
$forumsList='';
if ($row=DB_query(16,0)) {
do {
if ($row[0] != $forum) $forumsList.="<option value=\"".$row[0]."\">".$row[1]."</option>\n";
}
while ($row=DB_query(16,1));
}

$topicTitle = DB_query(5,0); $topicTitle = $topicTitle[0];

echo load_header(); echo ParseTpl(makeUp('tools_move_topic'));
}
}
else {
$title=$l_forbidden;
$errorMSG=$l_forbidden;
$correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
}
else {
$title=$l_topicnotexists;
$errorMSG=$l_topicnotexists;
$correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
?>