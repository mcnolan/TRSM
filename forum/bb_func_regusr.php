<?php
if (!defined('INCLUDED776')) die ('Fatal error.');

$warning=''; $editable='';
$actionName='register';

if ($logged!=1){

if (!isset($showemail)) { 
$showEmailYes = ''; $showEmailNo = 'checked'; 
}
else {
$showEmailYes = ($showemail==1)?'checked':'';
$showEmailNo = ($showEmailYes=='checked')?'':'checked';
}

if (!isset($sorttopics)) {
$sortTopics1 = 'checked'; $sortTopics0 = '';
}
else {
$sortTopics1 = ($sorttopics==1)?'checked':'';
$sortTopics0 = ($sortTopics1=='checked')?'':'checked';
}

if ($user_usr and $step==0) $login = $user_usr;
$userTitle=$l_newUserRegister;

switch($step) {
case 1:
if(isset($closeRegister) and $closeRegister==1) {
$passwd=substr(ereg_replace("[^0-9A-Za-z]", "A", md5(uniqid(rand()))),0,8);;
$passwd2=$passwd;
}

require('./bb_func_usrdat.php');

if (DB_query(60,0) and !DB_query(62,0) and $userData[1]!=$admin_usr and $userData[4]!=$admin_email) {

require('./bb_func_checkusr.php');
$correct = checkUserData($userData, 'reg');

if ($correct=="ok") {

$row=DB_query(61,$userData);
if ($row) {

if ($emailusers==1 and $genEmailDisable!=1){
$emailMsg=ParseTpl(makeUp('email_user_register'));
$sub=explode('SUBJECT>>', $emailMsg); $sub=explode('<<', $sub[1]); $emailMsg=trim($sub[1]); $sub=$sub[0];
sendMail($userData[4], $sub, $emailMsg, $admin_email, $admin_email);
}

if ($emailadmin==1 and $genEmailDisable!=1) {
$emailMsg=ParseTpl(makeUp('email_admin_userregister'));
$sub=explode('SUBJECT>>', $emailMsg); $sub=explode('<<', $sub[1]); $emailMsg=trim($sub[1]); $sub=$sub[0];
sendMail($admin_email, $sub, $emailMsg, $userData[4], $admin_email);
}

$title.=$l_userRegistered;
$errorMSG=$l_thankYouReg;
$correctErr=$l_goToLogin;
$tpl = makeUp('main_warning'); 
}
else {
$title.=$l_itseemserror;
$errorMSG=$l_itseemserror;
$correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$tpl = makeUp('main_warning');
}
}
else {
if ($l_userErrors[$correct] == '') $l_userErrors[$correct]=$l_undefined;
$warning = $l_errorUserData.": <font color=red><b>{$l_userErrors[$correct]}</b></font>";
$title.=$l_errorUserData;
$tpl = makeUp('user_dataform');
if(isset($closeRegister) and $closeRegister==1) $tpl=preg_replace("#<!--PASSWORD-->(.*)<!--/PASSWORD-->#is",'',$tpl);
}
}
else {
$title.=$l_errorUserExists;
$warning = $l_errorUserData.': <font color=red><b>'.$l_errorUserExists.'</b></font>';
$tpl = makeUp('user_dataform');
if(isset($closeRegister) and $closeRegister==1) $tpl=preg_replace("#<!--PASSWORD-->(.*)<!--/PASSWORD-->#is",'',$tpl);
}
echo load_header(); echo ParseTpl($tpl); return;
break;

default:
$title.=$l_newUserRegister;
$tpl=makeUp('user_dataform');
if(isset($closeRegister) and $closeRegister==1) $tpl=preg_replace("#<!--PASSWORD-->(.*)<!--/PASSWORD-->#is",'',$tpl);
echo load_header(); echo ParseTpl($tpl); return;
}
}
else {
$title.=$l_userRegistered;
$errorMSG=$l_userRegistered;
$correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
?>