<?php
//$st: 1 - dont show included forum, 0 - show all (select included)

if ($viewTopicsIfOnlyOneForum!=1) {
$row=DB_query(16,0);
if ($row) {
$i=0;
$listForums = '';
$tpl = makeUp('main_forums_list');
do {
$sel='';

if ($st==1) {
if ($row[0]!=$frm) $listForums.='<option value='.$row[0].'>'.$row[1].'</option>'."\n";
}
else {
if ($row[0]==$frm) $sel = ' selected';
$listForums.='<option value='.$row[0].$sel.'>'.$row[1].'</option>'."\n";
}
$i++;
}
while ($row=DB_query(16,1));

if ($i>1) {
$forumsList=ParseTpl($tpl);
}
else $forumsList='';
}
else $forumsList='';
}
else $forumsList='';
?>