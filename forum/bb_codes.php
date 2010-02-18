<?php
/* These 2 functions provides BB codes replacement independently for each miniBB update. Don't edit this file if you are unfamiliar with PHP and/or regular expressions. */

function enCodeBB ($msg, $admin) {

$pattern[0] = "/\[url=((f|ht)tp[s]?:\/\/[^<> \n]+?)\](.+?)\[\/url\]/i";
$replacement[0] = '<a href="\\1" target="_blank">\\3</a>';

$pattern[1] = "/\[email=([^<>(): \n]+?)\](.+?)\[\/email\]/i";
$replacement[1] = '<a href="mailto:\\1">\\2</a>';

$pattern[2] = "/\[img(left|right)?\](http:\/\/([^<> \n]+?)\.(gif|jpg|jpeg|png))\[\/img\]/i";
$replacement[2] = '<img src="\\2" border="0" align="\\1" alt="">';

$pattern[3] = "/\[[bB]\](.+?)\[\/[bB]\]/s";
$replacement[3] = '<b>\\1</b>';

$pattern[4] = "/\[[iI]\](.+?)\[\/[iI]\]/s";
$replacement[4] = '<i>\\1</i>';

$pattern[5] = "/\[[uU]\](.+?)\[\/[uU]\]/s";
$replacement[5] = '<u>\\1</u>';

if ($admin == 1) {
$pattern[6] = "/\[font(#[A-F0-9]{6})\](.+?)\[\/font\]/is";
$replacement[6] = '<font color="\\1">\\2</font>';
}

$msg = preg_replace($pattern, $replacement, $msg);

if (function_exists('smileThis') and function_exists('getSmilies')) $msg=smileThis(TRUE,FALSE,$msg);

return $msg;
}

//--------------->
function deCodeBB ($msg) {
$pattern[0] = "/<a href=\"mailto:(.+?)\">(.+?)<\/a>/i";
$replacement[0] = "[email=\\1]\\2[/email]";

$pattern[1] = "/<a href=\"(.+?)\" target=\"(_new|_blank)\">(.+?)<\/a>/i";
$replacement[1] = "[url=\\1]\\3[/url]";

$pattern[2] = "/<img src=\"(.+?)\" border=\"0\" align=\"(left|right)?\" alt=\"\">/i";
$replacement[2] = "[img\\2]\\1[/img]";

$pattern[3] = "/<[bB]>(.+?)<\/[bB]>/s";
$replacement[3] = "[b]\\1[/b]";

$pattern[4] = "/<[iI]>(.+?)<\/[iI]>/s";
$replacement[4] = "[i]\\1[/i]";

$pattern[5] = "/<[uU]>(.+?)<\/[uU]>/s";
$replacement[5] = "[u]\\1[/u]";

$pattern[6] = "/<font color=\"(#[A-F0-9]{6})\">(.+?)<\/font>/is";
$replacement[6] = '[font\\1]\\2[/font]';

$msg = preg_replace($pattern, $replacement, $msg);
$msg = str_replace ('<br>', "\n", $msg);
if (substr_count($msg, '[img\\2]')>0) $msg=str_replace('[img\\2]', '[img]', $msg);

if (function_exists('smileThis') and function_exists('getSmilies')) $msg=smileThis(FALSE,TRUE,$msg);

return $msg;
}

?>