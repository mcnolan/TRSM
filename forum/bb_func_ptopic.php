<?php
if (!defined('INCLUDED776')) die ('Fatal error.');

if (sizeof($regUsrForums)>0 and in_array($forum,$regUsrForums) and $user_id==0) {
$errorMSG=$l_forbidden; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$title = $title.$l_forbidden;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}

if (!$user_usr) $user_usr=$l_anonymous;

if ($topicTitle=='') {
$errorMSG=$l_topiccannotempty; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$title.=$l_topiccannotempty;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
else {
$TT = $topicTitle;
$topicTitle=textFilter($topicTitle,$topic_max_length,$post_word_maxlength,0,1,0,$user_id);
}

$poster_ip=getIP();

if(DB_query(8,0)) {
if ($user_id==1 or DB_query(79,0)) {
$topic=DB_query(22,0); 
if($topic>0) require('./bb_func_pthread.php');
else {
$errorMSG=$l_mysql_error; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$title.=$l_mysql_error;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
}
else {
$errorMSG=$l_antiSpam; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$title.=$l_antiSpam;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}

}
else {
$errorMSG=$l_forbidden; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$title.=$l_forbidden;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
?>