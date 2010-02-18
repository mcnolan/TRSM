<?php
if (!defined('INCLUDED776')) die ('Fatal error.');

if ($user_sort=='') $user_sort = $sortingTopics; // Sort messages default by last answer (0) desc OR 1 - by last new topics

if(isset($lastOut) and is_array($lastOut)){
foreach($lastOut as $l){
if(!in_array($l,$clForums)) $clForums[]=$l;
if(!isset($clForumsUsers[$l])) $clForumsUsers[$l]=array();
}
}

if (isset($clForumsUsers)) $closedForums=getAccess($clForums, $clForumsUsers, $user_id); else $closedForums='n';
if ($closedForums!='n') $extra=1; else $extra=0;

//Define forum icons
$fIconsRow=DB_query(16,0);
if ($fIconsRow) {
do {
$ind=$fIconsRow[0];
$fIcon[$ind]=$fIconsRow[3];
}
while($fIconsRow=DB_query(16,1));
}

if ($user_sort==1) { $cols = DB_query(14,0); $sus = 14; }
else { $cols = DB_query(15,0); $sus = 15; }

$list_topics='';

if ($cols) {

$i=0;
$tpl = makeUp('main_last_discuss_cell');

do{
$forum=$cols[6];
$topic=$cols[0];
$topic_title=$cols[1];
if ($topic_title=='') $topic_title=$l_emptyTopic;
$numViews=$cols[5];
$lastPoster=$cols[3];
if($i%2==0) $bg='tbCel1';
elseif($i%2==1) $bg='tbCel2';
$numReplies=0;
$numReplies=DB_query(6,0);

$pageNavCell=pageNav(0,$numReplies,"{$indexphp}action=vthread&amp;forum=$forum&amp;topic=$topic&amp;page=",$viewmaxreplys,TRUE);

if($numReplies>=1) $numReplies-=1;
$whenPosted = convert_date ($cols[4]);
if (trim($cols[1]) == '') $cols[1]=$l_emptyTopic;

if ($cols[3] == '') $cols[3]=$l_anonymous;

//Forum icon
$ind = $cols[6]; $forumIcon = $fIcon[$ind]; 
if ($forumIcon == '') $forumIcon='default.gif';

$list_topics.=ParseTpl($tpl);

$i++;
}
while($cols=DB_query($sus,1));
}
?>