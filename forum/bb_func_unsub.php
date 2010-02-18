<?php
if (!defined('INCLUDED776')) die ('Fatal error.');

if ($topic and $usrid and $usrid==$user_id and DB_query(80,0)) {
$topicU=$topic;

$op=DB_query(96,0);
if ($op>0) {
$errorMSG=$l_completed; $title.=$l_completed;
}
else {
$errorMSG=$l_itseemserror; $title.=$l_itseemserror;
}

}
else {
$title.=$l_accessDenied; $errorMSG=$l_accessDenied;
}

$correctErr = '';
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
?>