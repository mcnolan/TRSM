<?php
if (!defined('INCLUDED776')) die ('Fatal error.');

function uFstats($user){
global $stats_barWidth, $stats_barWidthLim, $indexphp;
global $forum, $userID;
global $clForums, $clForumsUsers, $extra, $closedForums, $user_id;
global $key, $key2, $val;

if(!isset($clForumsUsers)) $clForumsUsers=array();
$closedForums=getAccess($clForums, $clForumsUsers, $user_id);
if ($closedForums!='n') $extra=1; else $extra=0;

$userID=$user+0;$key2='';
$list_uFstats='';
$tpl=makeUp('stats_bar');
if($cols=DB_query(72,0)){
do{
if($cols[2]){
$val=$cols[2];
if(!isset($vMax)) $vMax=$val;
$stats_barWidth=round(100*($val/$vMax));
if($stats_barWidth>$stats_barWidthLim) $key='<a href="'.$indexphp.'action=vtopic&amp;forum='.$cols[0].'">'.$cols[1].'</a>';
else{
$key2='<a href="'.$indexphp.'action=vtopic&amp;forum='.$cols[0].'">'.$cols[1].'</a>';
$key='<a href="'.$indexphp.'action=vtopic&amp;forum='.$cols[0].'">...</a>';
}
$list_uFstats.=ParseTpl($tpl);
}
else break;
}
while($cols=DB_query(72,1));
return $list_uFstats;
}
else {
return '';
}
}

//---------------------->

$USERINFO='';

if(!isset($l_sendDirect)) $usEmail=''; else $usEmail=$usEmail='<a href="'.$indexphp.'action=senddirect&amp;user='.$user.'">'.$l_sendDirect.'</a>';

$row = DB_query(63,$_GET['user']);
if ($row) {
if ($row[10]!=1) $row[4]=$usEmail; else $row[4]='<a href="mailto:'.$row[4].'">'.$row[4].'</a>';
if ($row[6]!='') $row[6]='<a href="'.$row[6].'" target="_blank">'.$row[6].'</a>'; else $row[6]='';
$row[2] = str_replace('<br>', ' ', convert_date($row[2]));

$usrCell = makeUp('main_user_info_cell');

for ($i=1; $i<10; $i++) {

if (isset($l_usrInfo[$i]) and $row[$i]!='') 
{

$what=$l_usrInfo[$i]; $whatValue=$row[$i];
$USERINFO.=ParseTpl($usrCell);
}
}

/* Topics count */
if ($lastT=DB_query(94,0)) {
$what=$l_stats_numTopics;
$whatValue=$lastT[0];
$USERINFO.=ParseTpl($usrCell);
}

/* Posts count */
if ($lastT=DB_query(93,0)) {
$what=$l_stats_numPosts;
$whatValue=$lastT[0]-$whatValue;
$USERINFO.=ParseTpl($usrCell);
}

if(!isset($clForumsUsers)) $clForumsUsers=array();
$closedForums=getAccess($clForums, $clForumsUsers, $user_id);
if ($closedForums!='n') $extra=1; else $extra=0;

/* Last topics */
if ($lastT=DB_query(90,0)) {
$what=$l_userLastTopics;
$whatValue='<ul>';
do {
$whatValue.='<li><a href="'.$indexphp.'action=vthread&amp;topic='.$lastT[0].'&amp;forum='.$lastT[1].'&amp;page=-1">' .$lastT[2].'</a>';
}
while ($lastT=DB_query(90,1));
$whatValue.='</ul>';
$USERINFO.=ParseTpl($usrCell);
}

/* Last posts */
if ($lastT=DB_query(91,0)) {
$what=$l_userLastPosts;
$whatValue='<ul>';
do {
$whatValue.='<li><a href="'.$indexphp.'action=vthread&amp;topic='.$lastT[0].'&amp;forum='.$lastT[1].'&amp;page=-1">' .$lastT[2].'</a>';
}
while ($lastT=DB_query(91,1));
$whatValue.='</ul>';
$USERINFO.=ParseTpl($usrCell);
}

/* Activities */
$what=$l_usrInfo[10]; $whatValue=uFstats($user);
$USERINFO.=ParseTpl($usrCell);

$userInfo = $l_about.' "'.$row[1].'"';
$title.=$l_about.' '.$row[1];
$tpl = makeUp('main_user_info'); 
}
else {
$title.=$l_userNotExists;
$errorMSG=$l_userNotExists;
$correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$tpl = makeUp('main_warning');
}

echo load_header(); echo ParseTpl($tpl); return;
?>