<?php
if (!defined('INCLUDED776')) die ('Fatal error.');

if (isset(${$cookiename.'Update'}) and !($user_id==1 or $isMod==1)) {
$errorMSG=$l_antiSpam; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$title.=$l_antiSpam;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}

/* Check for time */
if ($row=DB_query(45,0) and (!isset($useredit) or $useredit==0 or $useredit>(strtotime(date('Y-m-d H:i:s'))-strtotime($row[2])) or $user_id==1 or $isMod==1)) {

//$postTopic='';
$userAllow=0;

if (!isset($disbbcode)) $disbbcode = 0;

if ($post) {

if ($step !=1 and $step !=0) $step = 0;
//0 - 1st step, 1-edit concrete

//Now check if admin or corresponding user are logged
if (($userAllow=DB_query(44,0) or $logged_admin==1 or $isMod==1) and $user_id!=0) {

$whoEdited=DB_query(47,0);

if (($whoEdited==2 or $whoEdited==3) and !($logged_admin==1 or $isMod==1)) {
$errorMSG=$l_onlyAdminCanEdit; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$title.=$l_onlyAdminCanEdit;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
else {

switch ($step) {
case (1):
$errorMSG='';

if (strlen(trim($postText))==0 or (isset($postTopic) and strlen(trim($postTopic))==0)) {
$title.=$l_emptyPost;
$errorMSG=$l_emptyPost;
$correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
}
else {

//Update topic title if admin is logged, if it is first post
if (($logged_admin==1 or $isMod==1) and DB_query(42,0)) {
$postTopic=textFilter($postTopic,$topic_max_length,$post_word_maxlength,0,1,0,$logged_admin);
if (DB_query(46,0)) $errorMSG.=$l_topicTitleUpdated."<br>"; 
//else $errorMSG.=$l_itseemserror."<br>";
}

if ($userAllow) $pSt=1;
elseif ($logged_admin==1) $pSt=2;
elseif ($isMod==1) $pSt=3;
else $pSt=1;

$postText=textFilter($postText,$post_text_maxlength,$post_word_maxlength,1,$disbbcode,1,$logged_admin);

$updatePost = DB_query(48,$pSt);
if ($updatePost !=0) $errorMSG.=$l_topicTextUpdated."<br>"; 
//else $errorMSG.=$l_itseemserror."<br>";

$title.=$l_editPost;
$correctErr = "<a href=\"{$indexphp}action=vthread&amp;forum=$forum&amp;topic=$topic&amp;page=$page&amp;anchor=$anchor\">$l_back</a>";
}

if ($user_id!=1 and $postRange!=0) {
setcookie($cookiename.'Update','',(time() - 2592000),$cookiepath,$cookiedomain,$cookiesecure);
setcookie ($cookiename.'Update', 1, time()+$postRange, $cookiepath, $cookiedomain, $cookiesecure); 
}

echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
break;

default:

$postText=deCodeBB($row[0]);
$postTopic=$row[1];

$l_messageABC=$l_editPost;
if (($logged_admin==1 or $isMod==1) and DB_query(42,0)) {
$mainPostForm=ParseTpl(makeUp('tools_edit_topic_title'));
} else $mainPostForm='';

$emailCheckBox='';

$mainPostForm.=ParseTpl(makeUp('main_post_form'));

$title.=$l_editPost;

echo load_header(); echo ParseTpl(makeUp('tools_edit_post'));
}

}

}
else {
$errorMSG=$l_forbidden; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$title.=$l_forbidden;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}

}
else {
$title.=$l_forbidden;
$errorMSG=$l_forbidden;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}

}
else {
$errorMSG=$l_accessDenied; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$title.=$l_accessDenied;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}

?>