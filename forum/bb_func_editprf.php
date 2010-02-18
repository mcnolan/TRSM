<?php
if (!defined('INCLUDED776')) die ('Fatal error.');

if ($logged==1) {

if (!isset($warning)) $warning='';
$l_fillRegisterForm ='';
$editable = 'disabled';
$userTitle=$l_editPrefs;
$l_passOnceAgain.=' (<span class=txtSm>'.$l_onlyIfChangePwd.')</span>';
$actionName='editprefs';

$userData = DB_query(63,$user_id);
if ($userData) {

if ($step==1) {
$login = $userData[1]; //username
require('./bb_func_usrdat.php');

$showEmailYes = ($userData[10]==1)?'checked':'';
$showEmailNo = ($showEmailYes=='checked')?'':'checked';
$sortTopics1 = ($userData[11]==1)?'checked':'';
$sortTopics0 = ($sortTopics1=='checked')?'':'checked';
require('./bb_func_checkusr.php');
$correct = checkUserData($userData, 'upd');
$email1 = $userData[4];
$signature = $userData[15];
if (DB_query(71,0)==1 or ($email1==$admin_email and $user_id!=1)) $correct=4;

if ($correct=='ok') {
//Update db
$upd = DB_query(70,$userData);
if ($upd) {
$title.=$l_prefsUpdated;
$warning=$l_prefsUpdated;
$signature = stripslashes($signature);
if ($userData[2]!='') $warning.=', '.$l_prefsPassUpdated;
}
else {
$title.=$l_editPrefs;
$warning=$l_prefsNotUpdated;
}

}
else {
if ($l_userErrors[$correct] == '') $l_userErrors[$correct]=$l_undefined;
$warning = $l_errorUserData.": <span class=warning>{$l_userErrors[$correct]}</span>";
$title.=$l_errorUserData;
}

$tpl=makeUp('user_dataform');
//if($user_id==1) $tpl=preg_replace("#<!--PASSWORD-->(.*?)<!--/PASSWORD-->#is",'',$tpl);
echo load_header(); echo ParseTpl($tpl); return;
} else {
//step=0
$login=$userData[1];
$passwd='';
$passwd2='';
$email=$userData[4];
$icq=$userData[5];
$website=$userData[6];
$occupation=$userData[7];
$from=$userData[8];
$interest=$userData[9];
$signature = $userData[16];

$showEmailYes = ($userData[10]==1)?'checked':'';
$showEmailNo = ($showEmailYes=='checked')?'':'checked';
$sortTopics1 = ($userData[11]==1)?'checked':'';
$sortTopics0 = ($sortTopics1=='checked')?'':'checked';

$title.=$l_editPrefs;
$tpl=makeUp('user_dataform');
//if($user_id==1) $tpl=preg_replace("#<!--PASSWORD-->(.*?)<!--/PASSWORD-->#is",'',$tpl);
echo load_header(); echo ParseTpl($tpl); return;
}

}
else {
$title.=$l_mysql_error; $errorMSG=$l_mysql_error; $correctErr = '';
$tpl = makeUp('main_warning'); 
}
} else { //someone who is not logged in is trying to access this page
$title.=$l_forbidden; $errorMSG=$l_forbidden; $correctErr='';
$tpl = makeUp('main_warning');
}

echo load_header(); echo ParseTpl($tpl); return;
?>