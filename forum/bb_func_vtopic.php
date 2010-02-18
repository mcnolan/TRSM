<?php
if (!defined('INCLUDED776')) die ('Fatal error.');

$list_topics='';
$pageNav = '';
$forumsList = '';

if ($viewTopicsIfOnlyOneForum=='1') { $forum = DB_query(16,0); $forum = $forum[0]; }

if (!$forum or !($row=DB_query(8,0))) {
$errorMSG=$l_forumnotexists; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$title = $title.$l_forumnotexists;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}

$forumName = $row[0];$forumIcon = $row[2];

if($user_sort=='') $user_sort = $sortingTopics; /* Sort messages default by last answer (0) desc OR 1 - by last new topics */

if(!isset($showSep)){

$numRows=DB_query(9,0);

if ($numRows == 0) {
$errorMSG=$l_noTopicsInForum; $correctErr='';
$title = $title.$l_noTopicsInForum;
$warn=ParseTpl(makeUp('main_warning'));
}

else {

$warn='';
//if at least one topic exists in this forum

$pageNav=pageNav($page,$numRows,"{$indexphp}action=vtopic&amp;forum=$forum&amp;sortBy=$sortBy&amp;page=",$viewmaxtopic,FALSE);
$makeLim=makeLim($page,$numRows,$viewmaxtopic);

if ($user_sort==1) { $cols=DB_query(10,0); $sus = 10; }
else { $cols=DB_query(11,0); $sus = 11; }

$i=0;

$tpl=makeUp('main_topics_cell');

do{
if($i%2==0) $bg='tbCel1';
elseif($i%2==1) $bg='tbCel2';
$topic=$cols[0];
$topicTitle=$cols[1];
if ($topicTitle=='') $topicTitle=$l_emptyTopic;
$numReplies=0;
$numViews=$cols[5];
$lastAuthor=$cols[3];
$numReplies=DB_query(6,0);

$pageNavCell=pageNav(0,$numReplies,"{$indexphp}action=vthread&forum=$forum&topic=$topic&page=",$viewmaxreplys,TRUE);

if($numReplies>=1) $numReplies-=1;
$whenPosted = convert_date($cols[4]);
if (trim($cols[1]) == '') $cols[1]=$l_emptyTopic;

if ($cols[3]=='') $cols[3]=$l_anonymous;

if ($cols[6]==9) $tpcIcon='sticky';
elseif ($cols[6]==1) $tpcIcon='locked';
elseif ($cols[6]==8) $tpcIcon='stlock';
elseif ($numReplies<=0) $tpcIcon='empty';
elseif ($numReplies>=$viewmaxreplys) $tpcIcon='hot';
else $tpcIcon='default';

$list_topics.=ParseTpl($tpl);
$i++;

}
while($cols=DB_query($sus,1));
}
//if topics are in this forum

$newTopicLink='<a href="'.$indexphp.'action=vtopic&forum='.$forum.'&amp;showSep=1">'.$l_new_topic.'</a>';
}//if not showsep

$st=1; $frm=$forum;
include ('./bb_func_forums.php');

$l_messageABC=$l_message;

$emailCheckBox = emailCheckBox();

$mainPostForm=ParseTpl(makeUp('main_post_form'));

$title = $title.$forumName;

if(!isset($showSep)) $main=makeUp('main_topics');
else $main=makeUp('main_newtopicform');

$nTop=1;
if (((isset($poForums) and in_array($forum, $poForums) and $isMod!=1) OR (isset($regUsrForums) and in_array($forum, $regUsrForums) and $user_id==0) OR (isset($roForums) and in_array($forum, $roForums) and $isMod!=1)) and $user_id!=1) {
$main=preg_replace("/(<form.*<\/form>)/Uis", '', $main);
$nTop=0;
$newTopicLink='';
}

echo load_header(); echo $warn; echo ParseTpl($main);
?>