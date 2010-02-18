<?php

if (!defined('INCLUDED776')) die ('Fatal error.');

$tpl = makeUp('faq');
$tplTmp=str_replace('{$manual}','<!--MANUAL-->',$tpl);
$tplTmp=ParseTpl($tplTmp);
$tplTmp=explode('<!--MANUAL-->',$tplTmp);

$title.=$l_menu[4]; 
echo load_header();
echo $tplTmp[0];
if(file_exists('./templates/manual_'.$lang.'.html')) include('./templates/manual_'.$lang.'.html');
elseif(file_exists('./templates/manual_eng.html')) include('./templates/manual_eng.html');
echo $tplTmp[1];

?>