<?php
if (!defined('INCLUDED776')) die ('Fatal error.');
if(!isset($chstat)) die('Fatal error.'); else $status=$chstat;

if ($logged_admin==1 or $isMod==1) {

if (DB_query(27,$status)>0) $errorMSG=(($status==9 or $status==8)?$l_topicSticked:$l_topicUnsticked);
else $errorMSG=$l_itseemserror;

$correctErr="<a href=\"{$indexphp}action=vthread&amp;forum=$forum&amp;topic=$topic\">$l_back</a>";

}
else {
$errorMSG=$l_forbidden; $correctErr = "<a href=\"JavaScript:history.back(-1)\">$l_back</a>";
$title.=$l_forbidden;
}

echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
?>