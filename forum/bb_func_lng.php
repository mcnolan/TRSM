<?php
if (!defined('INCLUDED776')) die ('Fatal error.');
$menuTitle=$l_menu[8];
$title=$title.$l_menu[8];

if ($step==0) {
$langList='';
$ss=0;
$glang=array();
$handle=@opendir('./lang');
if ($handle) {
while (($file = readdir($handle))!=false) {
if ($file != "." && $file != ".." && substr($file, -4)=='.php') {

chdir ('./lang');
$fd = fopen('./'.$file, 'r'); 
$getLang = fread ($fd, filesize ('./'.$file));
fclose($fd);
chdir ('..');

$key=substr($file,0,3);
$getLang=explode('$Lang:',$getLang); $getLang=explode(':$', $getLang[1]); $glang[$key]=$getLang[0];
}
}
closedir($handle);

asort($glang);

foreach($glang as $k=>$getLang){
$langList.='<input type=radio name=selLang value="'.$k.'"';
if ($k==$lang) $langList.=' checked';
$langList.='>'.$getLang.'</option><br>'."\n";
$ss++;
}

if ($ss>1) { echo load_header(); echo ParseTpl(makeUp('main_language')); return; }
else {
if(isset($metaLocation)) { $meta_relocate="{$main_url}/{$indexphp}"; echo ParseTpl(makeUp($metaLocation));
exit; } else header("Location: {$indexphp}");
}
}
else { 
if(isset($metaLocation)) { $meta_relocate="{$main_url}/{$indexphp}"; echo ParseTpl(makeUp($metaLocation));
exit; } else header("Location: {$indexphp}");
}
}

else {
if(!isset($deleteLang))$deleteLang='';
if ($deleteLang=='on') setcookie($cookiename.'Language','',(time() - 2592000),$cookiepath,$cookiedomain,$cookiesecure);
else {
setcookie($cookiename.'Language','',(time() - 2592000),$cookiepath,$cookiedomain,$cookiesecure);
setcookie ($cookiename.'Language', $selLang, time()+$cookielang_exp, $cookiepath, $cookiedomain, $cookiesecure);
}

if(isset($metaLocation)) { $meta_relocate="{$main_url}/{$indexphp}lng"; echo ParseTpl(makeUp($metaLocation));
exit; } else header("Location: {$indexphp}lng");

}
?>