<?php
if (!defined('INCLUDED776')) die ('Fatal error.');

$cols=DB_query(16,0);
$i=0;
$tpl=makeUp('main_forums_cell');
$list_forums='';
do{
if($i%2==0) $bg='tbCel1';
elseif($i%2==1) $bg='tbCel2';
$forum=$cols[0];
$forum_icon=$cols[3];
$forum_desc=$cols[2];
$forum_title=$cols[1];
$numTopics=DB_query(9,0);
$numPosts=DB_query(95,0);
$numPosts=$numPosts-$numTopics;

$list_forums.=ParseTpl($tpl);

$i++;
}
while($cols=DB_query(16,1));
$title=$sitename;

echo load_header();
echo ParseTpl(makeUp('main_forums'));
?>