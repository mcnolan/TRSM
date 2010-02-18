<?php
//error_reporting(E_ALL);
/*
index.php : basic functions-calling file for miniBB.
Copyright (C) 2001-2003 miniBB.net.

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function get_microtime() {
$mtime=explode(' ',microtime());
return $mtime[1]+$mtime[0];
}

$starttime = get_microtime();

if (!get_cfg_var('register_globals')){
if (is_array($HTTP_POST_VARS) and count($HTTP_POST_VARS)>0) foreach($HTTP_POST_VARS as $key=>$ht) { $$key=$ht; }
if (is_array($HTTP_GET_VARS) and count($HTTP_GET_VARS)>0) foreach($HTTP_GET_VARS as $key=>$ht) { $$key=$ht; }
if (is_array($HTTP_COOKIE_VARS) and count($HTTP_COOKIE_VARS)>0) foreach($HTTP_COOKIE_VARS as $key=>$ht) { $$key=$ht; }
}
//Unset logged vars, just in case
if(isset($logged_user)) unset($logged_user);
if(isset($logged_admin)) unset($logged_admin);
if(isset($user_id)) unset($user_id); //New for 1.7d

define ('INCLUDED776',1); //Security Measure

include ('../settings.php'); //this points to the TRSM settings file
if (isset($HTTP_COOKIE_VARS[$cookiename.'Language']) and $langCook=${$cookiename.'Language'}) {
$langCook=str_replace(array('.','/','\\'),'',$langCook);
if (file_exists("./lang/{$langCook}.php")) $lang=$langCook;
}
include ('./setup_'.$DB.'.php');
include ('./bb_codes.php');
include ('./bb_functions.php');
include ('./bb_specials.php');
include ('./bb_plugins.php');
include ("./lang/$lang.php");
if(!isset($GLOBALS['indexphp'])) $indexphp='index.php?'; else $indexphp=$GLOBALS['indexphp']; //Set indexphp to index.php if its not defined in the settings file

/* Closed forums stuff */
if (!isset($allForums) and isset($HTTP_COOKIE_VARS[$cookiename.'allForumsPwd'])) { $allForums = $HTTP_COOKIE_VARS[$cookiename.'allForumsPwd']; $allForumsCook=1; }
else { if(!isset($allForums))$allForums=''; $allForums=md5($allForums); $allForumsCook=0; }

if ($protectWholeForum==1) {
if ($allForums != md5($protectWholeForumPwd)) {
$title = $sitename." :: ".$l_forumProtected;
echo ParseTpl(makeUp('protect_forums')); exit;
}
else {
if ($allForumsCook==0) {
setcookie($cookiename.'allForumsPwd','',(time() - 2592000),$cookiepath,$cookiedomain,$cookiesecure);
setcookie($cookiename.'allForumsPwd', $allForums);
if(isset($metaLocation)) { $meta_relocate="{$main_url}/{$indexphp}"; echo ParseTpl(makeUp($metaLocation));
exit; } else header("Location: ./{$indexphp}");
}
}
}

/* Main stuff */
$logged = 0;
$loginError = 0;
$title = $sitename." :: ";

if (!isset($user_id)) $user_id=0;
if (!isset($page)) $page=0;
if(!isset($forum)) $forum=0;
if(!isset($topic)) $topic=0;

$forum+=0;
$user_id+=0;
$topic+=0;
$page+=0;

$l_adminpanel_link = '';
$reqTxt=0;

/* Predefining variables */
$sortingTopics+=0;

if (isset($HTTP_POST_VARS['mode'])) $mode=$HTTP_POST_VARS['mode'];
elseif (isset($HTTP_GET_VARS['mode'])) $mode=$HTTP_GET_VARS['mode'];
else $mode='';
if (isset($HTTP_POST_VARS['action'])) $action=$HTTP_POST_VARS['action'];
elseif (isset($HTTP_GET_VARS['action'])) $action = $HTTP_GET_VARS['action'];
else $action='';
if (isset($HTTP_COOKIE_VARS[$cookiename])) $$cookiename=$HTTP_COOKIE_VARS[$cookiename]; else $$cookiename='';

if (isset($HTTP_GET_VARS['sortBy'])) {
$sortBy=$HTTP_GET_VARS['sortBy']; $sdef=1;
} else {
$sortBy=$sortingTopics; $sdef=0;
}

if (!($sortBy==1 or $sortBy==0)) $sortBy=$sortingTopics;

if (($action == 'deltopic' or $action == 'delmsg2' or $action == 'movetopic2') and isset($dy) and $dy==2) $action = 'vthread';

if ($mode == 'login') { //Begin login sequence
$user_usr=trim($user_usr);
/*
if ($user_usr == $admin_usr) { //Check that the user is the admin
if ($user_pwd == $admin_pwd) { //Check that the admin pw is correct
$logged_admin = 1;
$cook = $user_usr."|".md5($user_pwd)."|".$cookieexptime;
setcookie($cookiename,'',(time() - 2592000),$cookiepath,$cookiedomain,$cookiesecure);
setcookie($cookiename, $cook, $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
if ($action=='') {
if(isset($metaLocation)) { $meta_relocate="{$main_url}/{$indexphp}"; echo ParseTpl(makeUp($metaLocation)); exit; } else header("Location: ./{$indexphp}");
}
}
else { //admin password incorrect
$errorMSG = $l_loginpasswordincorrect; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_correctLoginpassword</a>";
$loginError=1;
echo load_header(); echo ParseTpl(makeUp('main_warning'));
}
// if this is not admin, this is anonymous or registered user; check registered first
}
else {
    Rewrite of the central login system to include dynamic admins ;)*/

if ($row = DB_query(1,0)) 
{
// It means that username exists in database; so let's check a password
$username = $row[0]; $userpassword = $row[1]; $crewid=$row[2]; $userlevel=$row[3]; 
if ($username == $user_usr and $userpassword == md5($user_pwd)) { //user/pass check
    if($userlevel == 5) {
        $logged_admin = 1; //A level 5 admin!
        //Set SPMS2 admin stuff
        setcookie('admin', "rar", $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
    } else {
        $logged_user = 1; //just a plain ol user
    }
    setcookie('userl', $userlevel, $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
    $cook = $user_usr."|".md5($user_pwd)."|".$cookieexptime;
    setcookie($cookiename,'',(time() - 2592000),$cookiepath,$cookiedomain,$cookiesecure);
    setcookie($cookiename, $cook, $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
    //SPMS2 cookies
    setcookie('logged', "yes", $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
    setcookie('crewid', $crewid, $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);

if ($action=='') { //Redirect to index page?
if(isset($metaLocation)) { $meta_relocate="{$main_url}/{$indexphp}"; echo ParseTpl(makeUp($metaLocation)); exit; } else header("Location: ./{$indexphp}");
}

} else { //user/pass incorrect, display error
$errorMSG = $l_loginpasswordincorrect; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_correctLoginpassword</a>";
$loginError = 1;
echo load_header(); echo ParseTpl(makeUp('main_warning'));
}
} else { 
// There are no result rows - this is Anonymous
require('./bb_func_txt.php');$reqTxt=1;
$user_usr=textFilter($user_usr,40,20,0,0,0,0);
$user_usr=str_replace('|', '', $user_usr);
if ($minimalistBB != FALSE) {
$cookievalue = explode ("|", $minimalistBB);
$user_usrOLD = $cookievalue[0];
} else { $user_usrOLD = ""; }
if ($user_usr != $user_usrOLD) {
// We don't need to set a cookie if the same 'anonymous name' specified
$cook = $user_usr."||".$cookieexptime;
setcookie($cookiename,'',(time() - 2592000),$cookiepath,$cookiedomain,$cookiesecure);
setcookie($cookiename, $cook, $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
}
}
//}
} //End the login sequence

if ($loginError == 0) {

if($mode == 'logout') {
setcookie($cookiename,'',(time () - 2592000),$cookiepath,$cookiedomain,$cookiesecure);
//Unset SPMS2 cookies while your at it
setcookie('logged', "", $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
setcookie('admin', "", $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
setcookie('crewid', "", $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);

if(isset($metaLocation)) { $meta_relocate="{$main_url}/{$indexphp}"; echo ParseTpl(makeUp($metaLocation)); exit; } else { header("Location: ./{$indexphp}"); exit; }
}

if ($minimalistBB != FALSE and !$mode) {
$cookievalue = explode ("|", $minimalistBB);
$user_usr = $cookievalue[0]; $user_pwd = $cookievalue[1];
}

if(!isset($logged_admin)) $logged_admin = (user_logged_in("admin")?1:0);  //if no logged var exists, check on login status and set login var
if(!isset($logged_user)) $logged_user = (user_logged_in("user")?1:0);

//echo $logged_admin;
if ($logged_user==1 or $logged_admin==1) $logged = 1; //check to see if someone is logged in

if ($logged==1) { //Person logged in?
$loginLogout = ParseTpl(makeUp('user_logged_in'));
$user_logging = $loginLogout;

//Getting user sort and ID
$row = DB_query(2,0);
if ($row == TRUE) $user_data=array ($row[0],$row[1]); else $user_data=array(0,0);

$user_id=$user_data[0];
if ($sdef==0) $user_sort=$user_data[1]; else $user_sort=$sortBy;
if ($user_sort == 1) { $sortByNew = 0; $sortedByT = $l_newTopics; $sortByT = $l_newAnswers; }
if ($user_sort == 0) { $sortByNew = 1; $sortedByT = $l_newAnswers; $sortByT = $l_newTopics; }
}
else { //Noones logged in
if ($sdef==0) $user_sort=$sortingTopics; else $user_sort = $sortBy;
if ($user_sort == 1) { $sortByNew = 0; $sortedByT = $l_newTopics; $sortByT = $l_newAnswers; }
if ($user_sort == 0) { $sortByNew = 1; $sortedByT = $l_newAnswers; $sortByT = $l_newTopics; }

$loginLogout=ParseTpl(makeUp('user_login_form'));

$user_logging=ParseTpl(makeUp('user_login_only_form'));
} //end login check

if ($user_sort==0) $l_author=$l_lastAuthor;

if ($logged_admin==1) {
$l_adminpanel_link = "<p><a href=\"$bb_admin\">".$l_adminpanel."</a><br><br>";
//$user_id = 1;
}
else {
$l_adminpanel_link = '';
}

$isMod=(isset($mods) and in_array($user_id,$mods) and !(isset($modsOut) and in_array($user_id.'>'.$forum,$modsOut)))?1:0;

/* Private, archive and post-only forums stuff */
$forb=0;

if ($user_id!=1 and $forum!=0) {
if (isset($clForums) and in_array($forum, $clForums)) {
if (isset($clForumsUsers[$forum]) and !in_array($user_id,$clForumsUsers[$forum])) $forb=1;
}
if (isset($roForums) and in_array($forum, $roForums) and $isMod!=1) {
$disallowAction=array('pthread', 'ptopic', 'editmsg', 'editmsg2', 'delmsg', 'delmsg2', 'locktopic', 'unlocktopic', 'deltopic', 'movetopic', 'movetopic2', 'sticky', 'unsticky');
if (in_array($action, $disallowAction)) $forb=1;
}
if (isset($poForums) and in_array($forum, $poForums) and $isMod!=1){
$allowAction=array('vthread', 'vtopic', 'pthread', 'editmsg', 'editmsg2');
if (!in_array($action,$allowAction)) $forb=1;
}
}

if ($forb==1) {
$title.=$l_accessDenied;
echo load_header();
$errorMSG = $l_privateForum; $l_returntoforums = ""; $correctErr="";

echo ParseTpl(makeUp('main_warning'));
$l_loadingtime='';

echo ParseTpl(makeUp('main_footer'));
exit;
}
/* End stuff */

/* Banned IPs/IDs stuff */
$thisIp = getIP();
$cen = explode ('.', $thisIp);

if(isset($cen[0]) and isset($cen[1]) and isset($cen[2])){ 
$thisIpMask[0]=$cen[0].'.'.$cen[1].'.'.$cen[2].'.+'; 
$thisIpMask[1]=$cen[0].'.'.$cen[1].'.+'; 
} 
else { 
$thisIpMask[0]='0.0.0.+'; 
$thisIpMask[1]='0.0.0.+'; 
}

if (DB_query(89,$user_id)) {
$title = $sitename." :: ".$l_accessDenied;
echo ParseTpl(makeUp('main_access_denied')); exit;
}

if($action=='pthread') {if($reqTxt!=1)require('./bb_func_txt.php');require('./bb_func_pthread.php');}
elseif($action=='ptopic') {if($reqTxt!=1)require('./bb_func_txt.php');require('./bb_func_ptopic.php');}


if($action=='pthread') {
$page=-1;
if (!isset($errorMSG)) {
$metaLocation = 'go';
if(isset($metaLocation)) {
$meta_relocate="{$main_url}/{$indexphp}action=vthread&forum=$forum&topic=$topic&page=$page#{$anchor}"; echo ParseTpl(makeUp($metaLocation)); exit; } else header("Location: ./{$indexphp}action=vthread&forum=$forum&topic=$topic&page=$page#$anchor");
}
}

elseif($action=='vthread') require('./bb_func_vthread.php');

elseif($action=='vtopic') {
if(isset($redthread) and is_array($redthread) and isset($redthread[$forum])) {
if(isset($metaLocation)) {
$meta_relocate="{$main_url}/{$indexphp}action=vthread&forum=$forum&topic={$redthread[$forum]}"; echo ParseTpl(makeUp($metaLocation)); exit;
} else 
header("Location: ./{$indexphp}action=vthread&forum=$forum&topic={$redthread[$forum]}");
}
else require('./bb_func_vtopic.php');
}

elseif($action=='ptopic') {
$page=0;
$metaLocation = 'go';
if (!isset($errorMSG)) {
if(isset($metaLocation)) {
$meta_relocate="{$main_url}/{$indexphp}action=vthread&forum={$forum}&topic={$topic}"; echo ParseTpl(makeUp($metaLocation)); exit; } else 
header("Location: ./{$indexphp}action=vthread&forum=$forum&topic=$topic");
}
}

elseif($action=='search') {if($reqTxt!=1)require('./bb_func_txt.php');require('./bb_func_search.php');}

elseif($action=='deltopic') require('./bb_func_deltopic.php');

elseif($action=='locktopic') require('./bb_func_locktop.php');

elseif($action=='editmsg') {$step=0;require('./bb_func_editmsg.php');}

elseif($action=='editmsg2') {require('./bb_func_txt.php');$step=1;require('./bb_func_editmsg.php');}

elseif($action=='delmsg') {$step=0;require('./bb_func_delmsg.php');}

elseif($action=='delmsg2') {$step=1;require('./bb_func_delmsg.php');}

elseif($action=='movetopic') {$step=0;require('./bb_func_movetpc.php');}

elseif($action=='movetopic2') {$step=1;require('./bb_func_movetpc.php');}

elseif($action=='userinfo') require('./bb_func_usernfo.php');

elseif($action=='sendpass') {$step=0;require('./bb_func_sendpwd.php');}

elseif($action=='sendpass2') {$step=1;require('./bb_func_sendpwd.php');}

elseif($action=='confirmpasswd') {
if (!isset($confirmCode)) $confirmCode='';
require('./bb_func_confpwd.php');
}

elseif($action=='stats') require('./bb_func_stats.php');

elseif($action=='manual') require('./bb_func_man.php');

elseif($action=='registernew') {$step=0;require('./bb_func_regusr.php');}

elseif($action=='register') {$step=1;require('./bb_func_regusr.php');}

elseif($action=='prefs') {$step=0;require('./bb_func_editprf.php');}

elseif($action=='editprefs') {$step=1;require('./bb_func_editprf.php');}

elseif($action=='language') {$step=0;require('./bb_func_lng.php');}

elseif($action=='language2') {$step=1;require('./bb_func_lng.php');}

elseif($action=='unsubscribe') require('./bb_func_unsub.php');

elseif($action=='sticky') {$status=9;require('./bb_func_sticky.php');}

elseif($action=='unsticky') {$status=0;require('./bb_func_sticky.php');}

elseif($action=='viewipuser') {require('./bb_func_viewip.php');}

elseif($action=='tpl') {
if (isset($tplName) and $tplName!='' and file_exists ('./templates/'.$tplName.'.html')){
echo load_header(); echo ParseTpl(makeUp($tplName));
}
else {
if(isset($metaLocation)) { $meta_relocate="{$main_url}/{$indexphp}"; echo ParseTpl(makeUp($metaLocation)); exit; } else header("Location: ./{$indexphp}");
}
}

elseif(DB_query(28,0)>=1){
if ($viewTopicsIfOnlyOneForum!=1) {
require('./bb_func_vforum.php');
if (DB_query(38,0) and $viewlastdiscussions!=0) {
require('./bb_func_ldisc.php');
$listTopics=$list_topics;
if($list_topics!='') echo ParseTpl(makeUp('main_last_discussions'));
}
}
else require('./bb_func_vtopic.php');
}
else{
$errorMSG = $l_stillNoForums; $l_returntoforums = ""; $correctErr="";
echo load_header(); echo ParseTpl(makeUp('main_warning'));
} //End action finding
} //login error == 0 (ie no login problems??)

//Loading footer
$endtime = get_microtime();
$totaltime = sprintf ("%01.3f", ($endtime - $starttime));
echo ParseTpl(makeUp('main_footer'));

?>