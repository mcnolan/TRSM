<?php
if (!defined('INCLUDED776')) die ('Fatal error.');

//Check if topic is not locked
$lckt=DB_query(43,0);
if ((sizeof($regUsrForums)>0 and in_array($forum,$regUsrForums) and $user_id==0) or $lckt==1 or $lckt==8) {
$errorMSG=$l_forbidden; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$title = $title.$l_forbidden;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
else {
if (!$user_usr) $user_usr=$l_anonymous;
if(!isset($TT)) $TT='';
if ($postText=='') $postText = $TT;

if ($postText=='') {
$errorMSG=$l_emptyPost; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$title = $title.$l_emptyPost;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}

if(!isset($disbbcode)) $disbbcode=FALSE;
$postText=textFilter($postText,$post_text_maxlength,$post_word_maxlength,1,$disbbcode,1, $user_id);
$poster_ip=getIP();

//Posting query with anti-spam protection

if(DB_query(19,0)) {

if ($user_id==1 or DB_query(79,0)) { 
$query=DB_query(20,0);

if ($emailusers==1 or (isset($emailadmposts) and $emailadmposts==1)) {
$topicTitle = DB_query(5,0);
$topicTitle = $topicTitle[0];
$postTextSmall = strip_tags(substr(str_replace('<br>', "\n", $postText), 0, 200)).'...';
$msg=ParseTpl(makeUp('email_reply_notify'));
$sub=explode('SUBJECT>>', $msg); $sub=explode('<<', $sub[1]); $msg=trim($sub[1]); $sub=$sub[0];
}

//Email all users about this reply if allowed
if ($emailusers==1) {

if ($row=DB_query(83,0)){
do {
if ($row[0]!='') {
sendMail ($row[0], $sub, $msg, $admin_email, $admin_email);
}
}
while ($row=DB_query(83,1));
}
}

//Email admin if allowed
if (isset($emailadmposts) and $emailadmposts==1 and $user_id!=1) {
sendMail ($admin_email, $sub, $msg, $admin_email, $admin_email);
}

//Insert user into email notifies if allowed
$user_email = DB_query(63,$user_id); $user_email = $user_email[4];
if (isset($CheckSendMail) and emailCheckBox()!='' and substr(emailCheckBox(),0,8)!='<!--U-->' and $user_email!='') DB_query(81,0);
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

$totalPosts = (integer) DB_query(6,0);
$vmax = (integer) $viewmaxreplys;
$anchor = $totalPosts;
if ($anchor>$vmax) { $anchor = $totalPosts-((floor($totalPosts/$vmax))*$vmax); if ($anchor==0) $anchor=$vmax;}
}
?>