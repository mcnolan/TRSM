<?php
$cookieexptime = time() + $cookie_expires;
$version = '1.7d';

if (isset($HTTP_COOKIE_VARS[$cookiename])) $minimalistBB=$HTTP_COOKIE_VARS[$cookiename]; else $minimalistBB='';

function user_logged_in ($user) {

global $minimalistBB;
global $user_usr, $user_pwd;
global $admin_usr, $admin_pwd;
global $username, $userpassword;
global $cookiename, $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure, $cookie_renew;
global $logged_admin;

$returned = FALSE;

$cookievalue = $minimalistBB;
if($cookievalue) {
$cookievalue = explode ("|", $cookievalue);
$username = $cookievalue[0]; $userpassword = $cookievalue[1]; $exptime = $cookievalue[2];
if ($user_usr!='' and $username!=$user_usr) { $returned=FALSE; return; }

$nowtime = time();
$pasttime = $exptime - $nowtime;

if ($user == 'admin') {
    //if(!isset($user_usr) && !isset($user_pwd)) { $user_usr = $username; $user_pwd = $userpassword; }
//if ($username == $admin_usr and $userpassword == md5($admin_pwd)) {
if(!isset($user_usr)) { $user_usr = $username; }
$row = DB_query(1,0);
if($row) { //Check to see if the row exists in the database.
    $ad_usr = $row[0]; $ad_pwd = $row[1]; $ad_ul = $row[3];
    if($ad_usr == $username && $ad_pwd == $userpassword) {
        //This person is a level 5 admin
        if($ad_ul == 5 and $_COOKIE['admin'] == "rar") {
            $returned = TRUE;
        } else {
            //This person is a user, but not an admin
            $returned = FALSE;
        }
    } else {
        //Username/password combo incorrect
        $returned = FALSE;
    }
} else {
    //No row exists in the database
    $returned = FALSE;
}
if ($pasttime <= $cookie_renew) {
/* if expiration time of cookie is less than defined in setup, we redefine it below
$cook = $admin_usr."|".md5($admin_pwd)."|".$cookieexptime;
setcookie($cookiename,'',(time() - 2592000),$cookiepath,$cookiedomain,$cookiesecure);
setcookie($cookiename, $cook, $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
*/
}
//} 
//else { $returned=FALSE; }
} elseif ($user == 'user' and $logged_admin == 0) { //$user_usr !=$admin_usr) {
$row = DB_query(1,0);
if ($row == TRUE) 
{
// It means that username exists in database; so let's check a password
$username = $row[0]; $userpassword = $row[1];
if ($username == $user_usr and $userpassword == $user_pwd) 
{
//if username and password applies, we renew cookie if necessary
$returned = TRUE;

if ($pasttime <= $cookie_renew) 
{
/* if expiration time of cookie is less than defined in setup, we redefine it below
$cook = $user_usr."|".$user_pwd."|".$cookieexptime;
setcookie($cookiename,'',(time() - 2592000),$cookiepath,$cookiedomain,$cookiesecure);
setcookie($cookiename, $cook, $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
*/
}
}
// username and password not apply
else { 
$returned = FALSE;
}
}
//username is not in database; it means we have Anonymous user and need to set up it
else 
{ 
if ($pasttime <= $cookie_renew) {
/*$cook = $user_usr."||".$cookieexptime;
setcookie($cookiename,'',(time() - 2592000),$cookiepath,$cookiedomain,$cookiesecure);
setcookie($cookiename, $cook, $cookieexptime, $cookiepath, $cookiedomain, $cookiesecure);
*/
}
$returned = FALSE; 
}
}
} else {
$returned = FALSE;
}
//echo $returned;
return $returned;
} //user_logged_in function

//--------------->
function makeUp($name) {
global $l_meta, $indexphp;
if (substr($name, 0, 5)=='email') $ext = 'txt'; else $ext = 'html';

if (file_exists ('./templates/'.$name.'.'.$ext)) { 
$fd = fopen ('./templates/'.$name.'.'.$ext, 'r');
$tpl = fread ($fd, filesize ('./templates/'.$name.'.'.$ext));
fclose ($fd);
}
else die ("FATAL: NOT FOUND $name");
return $tpl;
}

//--------------->
function parseTpl($tpl){
$qs=array();
$qv=array();
$ex=explode ('{$',$tpl);
for ($i=0; $i<=sizeof($ex); $i++){
if (!empty($ex[$i]) and substr_count($ex[$i],'}')>0) {
$xx=explode('}',$ex[$i]);
if (substr_count($xx[0],'[')>0) {
$clr=explode ('[',$xx[0]); $sp=$clr[1]+0; $clr=$clr[0];
if (!in_array($clr,$qs)) {$qs[]=$clr; global ${$clr};}
$to=${$clr}[$sp];
}
else { if(!in_array($xx[0], $qv)) {$qv[]=$xx[0]; global ${$xx[0]};}
$to=${$xx[0]};
}
$tpl=str_replace('{$'.$xx[0].'}', $to, $tpl);
}
}
return $tpl;
}

//--------------->
function load_header() {
//Because of loading page title, we need to load this template separately
global $title, $sitename, $l_meta, $l_menu, $l_sepr, $l_reply, $action, $logged, $errorMSG, $adminPanel, $user_logging, $indexphp, $nTop;

if(strlen($action)>0 or $adminPanel==1) $l_menu[0] = "$l_sepr <a href=\"./{$indexphp}\">$l_menu[0]</a> "; else $l_menu[0]='';
if($nTop==1){
if($action=='vtopic') $l_menu[7] = "$l_sepr <a href=\"#newtopic\">$l_menu[7]</a> ";
elseif($action=='vthread') $l_menu[7] = "$l_sepr <a href=\"#newreply\">$l_reply</a> ";
}
else $l_menu[7]='';
if($action!='stats') $l_menu[3] = "$l_sepr <a href=\"./{$indexphp}action=stats\">$l_menu[3]</a> "; else $l_menu[3]='';
if($action!='search') $l_menu[1] = "$l_sepr <a href=\"./{$indexphp}action=search\">$l_menu[1]</a> "; else $l_menu[1]='';
if($action!='registernew'&&$logged !=1 and $adminPanel!=1) $l_menu[2] = "$l_sepr <a href=\"./{$indexphp}action=registernew\">$l_menu[2]</a> "; else $l_menu[2]='';
if($action!='manual') $l_menu[4] = "$l_sepr <a href=\"./{$indexphp}action=manual\">$l_menu[4]</a> "; else $l_menu[4]='';
if($action!='prefs'&&$logged == 1) $l_menu[5] = "$l_sepr <a href=\"./{$indexphp}action=prefs\">$l_menu[5]</a> "; else $l_menu[5]='';
if($action!='language') $l_menu[8] = "$l_sepr <a href=\"./{$indexphp}action=language\">$l_menu[8]</a> "; else $l_menu[8]='';
if($logged==1) $l_menu[6] = "$l_sepr <a href=\"./{$indexphp}mode=logout\">$l_menu[6]</a> "; else $l_menu[6]='';

if (!isset($title)) $title=$sitename;

return ParseTpl(makeUp('main_header'));
}

//--------------->
function getAccess($clForums, $clForumsUsers, $user_id){
$forb=array();
$acc='n';
if ($user_id!=1 and sizeof($clForums)>0){
foreach($clForums as $f){
if (isset($clForumsUsers[$f]) and !in_array($user_id, $clForumsUsers[$f])){
$forb[]=$f; $acc='m';
}
}
}
if ($acc=='m') return $forb; else return $acc;
}

//--------------->
function getIP(){
$ip1=getenv('REMOTE_ADDR');$ip2=getenv('HTTP_X_FORWARDED_FOR');
if ($ip2!='' and ip2long($ip2)!=-1) $finalIP=$ip2; else $finalIP=$ip1;
$finalIP=substr($finalIP,0,15);
return $finalIP;
}

//--------------->
function convert_date ($dateR) {
global $l_months, $dateFormat, $timeDiff;

if(isset($timeDiff) and $timeDiff!=0) $dateR=date('Y-m-d H:i:s',strtotime($dateR)+$timeDiff);

$months = explode (':', $l_months);
if (sizeof($months)!=12) $dateR = 'N/A';
else {
list ($currentD, $currentT) = explode (' ', $dateR);
$cAll = explode ('-', $currentD);
if(substr($cAll[2],0,1)=='0') $cAll[2]=substr($cAll[2],1,strlen($cAll[2])-1);
$dateR = str_replace ('DD', $cAll[2], $dateFormat);
$dateR = str_replace ('YYYY', $cAll[0], $dateR);
$whichMonth = $cAll[1]-1;
$dateR = str_replace ('MM', $months[$whichMonth], $dateR);

if (substr_count($dateR,'US')>0) {
if ($currentT>='12:00:00' and $currentT<='23:59:59') {
$times=explode(':',$currentT);
$times[0]=$times[0]-12; if ($times[0]<10) $times[0]='0'.$times[0];
$currentT=implode(':',$times); $m='pm';
}
else $m='am';
$dateR = str_replace ('US', $m, $dateR);
}
}
$dateR = str_replace ('T', $currentT, $dateR);
return $dateR;
}

//--------------->
function pageChk($page,$numRows,$viewMax){
if($numRows>0 and ($page>0 or $page==-1)){
$max=$numRows/$viewMax;
if(intval($max)==$max) $max=intval($max)-1; else $max=intval($max);
if ($page==-1) return $max;
elseif($page>$max) return $max;
else return $page;
}
else return 0;
}

//--------------->
function pageNav($page,$numRows,$url,$viewMax,$navCell){
global $viewpagelim;
$pageNav='';
$page=pageChk($page,$numRows,$viewMax);
$iVal=intval(($numRows-1)/$viewMax);
if($iVal>$viewpagelim){
$iVal=$viewpagelim;
if($viewpagelim>=1) $iVal-=1;
}
if($numRows>0&&$iVal>0&&$numRows<>$viewMax){
$end=$iVal;
if(!$navCell) $start=0; else $start=1;
if($page>0&&!$navCell) $pageNav=' <a href="'.$url.($page-1).'">&lt;&lt;</a>';
if($navCell&&$end>4){ $end=3;$pageNav.=' . '; }
elseif($page<9&&$end>9){ $end=9;$pageNav.=' . '; }
elseif($page>=9&&$end>9){
$start=intval($page/9)*9-1;$end=$start+10;
if($end>$iVal) $end=$iVal;
$pageNav.=' <a href="'.$url.'0">1</a> ...';
}
else $pageNav.=' . ';
for($i=$start;$i<=$end;$i++){
if($i==$page&&!$navCell) $pageNav.=' <b>'.($i+1).'</b> .';
else $pageNav.=' <a href="'.$url.$i.'">'.($i+1).'</a> .';
}
if((($navCell&&$iVal>4)||($iVal>9&&$start<$iVal-10))){
if($navCell&&$iVal<6); else $pageNav.='..';
for($n=$iVal-1;$n<=$iVal;$n++){
if($n>=$i) $pageNav.=' <a href="'.$url.$n.'">'.($n+1).'</a> .';
}
}
if($page<$iVal&&!$navCell) $pageNav.=' <a href="'.$url.($page+1).'">&gt;&gt;</a>';
return $pageNav;
}
}

//--------------->
function makeLim($page,$numRows,$viewMax){
$page=pageChk($page,$numRows,$viewMax);
if(intval($numRows/$viewMax)!=0&&$numRows>0){
if ($page>0) return ' LIMIT '.($page*$viewMax).','.$viewMax;
else return ' LIMIT '.$viewMax;
}
else return '';
}

//---------------------->
function sendMail($email, $subject, $msg, $from_email, $errors_email) {
global $genEmailDisable;
// Function sends mail with return-path (if incorrect email TO specifed. Reply-To: and Errors-To: need contain equal addresses!
if (!isset($genEmailDisable) or $genEmailDisable!=1){
$msg=str_replace("\r\n", "\n", $msg);
$php_version = phpversion();
$from_email = "From: $from_email\r\nReply-To: $errors_email\r\nErrors-To: $errors_email\r\nX-Mailer: PHP ver. $php_version";
mail($email, $subject, $msg, $from_email);
}
}

//---------------------->
function emailCheckBox() {
global $l_emailNotify, $l_unsubscribe, $indexphp;
global $genEmailDisable, $emailadmposts, $emailusers, $user_id, $forum, $topic, $action, $logged;

$checkEmail='';
if($genEmailDisable!=1){

$isInDb = DB_query(80,0);

$true0 = ($emailusers==1);
$true1 = ($logged==1);
$true2 = ($action=='vtopic' or $action == 'vthread' or $action=='ptopic' or $action=='pthread');
$true3a = ($user_id==1 and (!isset($emailadmposts) or $emailadmposts==0) and !$isInDb);
$true3b = ($user_id!=1 and !$isInDb);
$true3 = ($true3a or $true3b);

if ($true0 and $true1 and $true2 and $true3) {
$checkEmail = "<input type=checkbox name=CheckSendMail> <a href=\"{$indexphp}action=manual#emailNotifications\">$l_emailNotify</a>";
}
elseif($isInDb) $checkEmail="<!--U--><a href=\"{$indexphp}action=unsubscribe&topic={$topic}&usrid={$user_id}\">$l_unsubscribe</a>";
}
return $checkEmail;
}

?>