<?php
if (!defined('INCLUDED776')) die ('Fatal error.');

if ($confirmCode=='') {
$title.=$l_forbidden; $errorMSG=$l_forbidden; $correctErr = '';
}
else {
$upd = DB_query(69,0);
if ($upd>0) {
$title.=$l_passwdUpdate; $errorMSG=$l_passwdUpdate; $correctErr = '';
}
else {
$title.=$l_itseemserror; $errorMSG=$l_itseemserror; $correctErr = '';
}
}
echo load_header(); echo ParseTpl(makeUp('main_warning'));
?>