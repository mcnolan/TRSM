<?php

if (!defined('INCLUDED776')) die ('Fatal error.');

if (!isset($genEmailDisable) or $genEmailDisable!=1){

$newPasswd=''; $confirmCode='';

if (!isset($email) or $email==$admin_email) $email='';

if ($step == 0) {
$title.=$l_pwdWillBeSent;
echo load_header(); echo ParseTpl(makeUp('tools_send_password')); return;
}
elseif ($step == 1) {
$updID=DB_query(67,0);
if ($updID<=0) {

$title.=$l_emailNotExists;
$errorMSG=$l_emailNotExists;
$correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
else {

$newPasswd = substr(ereg_replace("[^0-9A-Za-z]", "A", md5(uniqid(rand()))),0,8);
$confirmCode = substr(md5(uniqid(rand())),0,32);

$update=DB_query(68,0);

if ($update>0) {
$msg=ParseTpl(makeUp('email_user_password'));

$sub=explode('SUBJECT>>', $msg); $sub=explode('<<', $sub[1]); $msg=trim($sub[1]); $sub=$sub[0];

sendMail($email, $sub, $msg, $admin_email, $admin_email);

$title.=$l_emailSent;
$errorMSG=$l_emailSent;
$correctErr = '';
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
else {
$title.=$l_itseemserror;
$errorMSG=$l_itseemserror;
$correctErr = '';
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
}
}

}
else {
$title.=$l_accessDenied;
$errorMSG=$l_accessDenied;
$correctErr = '';
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}

?>