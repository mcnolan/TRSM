<?php
if (!defined('INCLUDED776')) die ('Fatal error.');

function deSlice($lsTopics,$id){
if($lsTopics){
global $user_sort, $viewmaxtopic;
$user_sort+=0;
if($user_sort==1) $i=DB_query(76,$id);
else $i=DB_query(77,$id);
return intval(($i-1)/$viewmaxtopic);
}
else{
global $viewmaxreplys;
$i=DB_query(78,$id);
$pageAnchor[0]=intval(($i-1)/$viewmaxreplys);
$a=$i-intval($i/$viewmaxreplys)*$viewmaxreplys;
if($i>0&&$a==0) $a=$viewmaxreplys;
$pageAnchor[1]='#'.$a;
return $pageAnchor;
}
}

//--------------->
function matchGen($column,$i){
global $exploded, $exact;
if(!$exact) return "$column LIKE '%$exploded[$i]%'";
return "( $column LIKE '% $exploded[$i]' OR $column LIKE '$exploded[$i]' OR $column LIKE '$exploded[$i] %' OR $column LIKE '% $exploded[$i] %' )";
}

//--------------->
if(!isset($searchWhere)) $searchWhere=0;
if(!isset($searchHow)) $searchHow=0;
if(!isset($searchFor)) $searchFor='';
if(!isset($days)) { $days=0; $flag=1; }

$searchWhere+=0;$searchHow+=0;$word=0;$min=2;$i=0;
$searchFor=textFilter($searchFor,100,$post_word_maxlength,0,1,0,0);

if($searchWhere==0) $whereGenAr=array("$Tp.post_text","$Tt.topic_title");
elseif($searchWhere==1) $whereGenAr=array('topic_title','');
elseif($searchWhere==2) $whereGenAr=array("$Tp.poster_name",'');

$days=substr($days,0,4)+0;

if((isset($exact)&&$exact) OR (isset($eMatch)&&$eMatch=='on')) {$exact=1;$eMatch='checked';} else {$exact=0;$eMatch='';}

if(!isset($clForumsUsers)) $clForumsUsers=array();
$closedForums=getAccess($clForums, $clForumsUsers, $user_id);
if ($closedForums!='n') $extra=1; else $extra=0;

$SHchk=array('','','');
$SHchk[$searchHow]='selected';
$SWchk=array('','','');
$SWchk[$searchWhere]='selected';

$exploded=explode(' ',$searchFor);

if(strlen($searchFor)<$min&&$days<=$defDays&&(!isset($flag) or !$flag)){
$word=1;$exact=1;$searchHow=3;$searchWhere=1;$searchString='';
if($searchFor!='') $searchString=matchGen($whereGenAr[0],$i);
}
elseif(strlen($searchFor)<$min&&$days>$defDays) $warning=$l_search[12].' '.$defDays.' '.$l_days.'.';

if($searchHow==0){
if(strlen($exploded[0])>$min) $word=1;
$searchString=matchGen($whereGenAr[0],$i);
$searchString2=matchGen($whereGenAr[1],$i);
for($i=1;$i<sizeof($exploded);$i++){
if(!$word&&strlen($exploded[$i])>$min) $word=1;
if($searchWhere==0){
$searchString.=' AND '.matchGen($whereGenAr[0],$i);
$searchString2.=' AND '.matchGen($whereGenAr[1],$i);
}
else $searchString.=' AND '.matchGen($whereGenAr[0],$i);
}
}
elseif($searchHow==1){
$word=1;
if(strlen($exploded[0])>$min){
$searchString=matchGen($whereGenAr[0],$i);
$searchString2=matchGen($whereGenAr[1],$i);
for($i=1;$i<sizeof($exploded);$i++){
if($word&&strlen($exploded[$i])<=$min) {$word=0; break;}
if($searchWhere==0){
$searchString.=' OR '.matchGen($whereGenAr[0],$i);
$searchString2.=' OR '.matchGen($whereGenAr[1],$i);
}
else $searchString.=' OR '.matchGen($whereGenAr[0],$i);
}
}
else $word=0;
}
elseif($searchHow!=3){
for ($i=0;$i<sizeof($exploded);$i++){
if (strlen($exploded[$i])>$min) {$word=1; break;}
}
$exploded[$i]=$searchFor;
$searchString=matchGen($whereGenAr[0],$i);
$searchString2=matchGen($whereGenAr[1],$i);
}
unset($exploded);
if($searchWhere!=0) unset($searchString2);

if(!$word||strlen($searchFor)>100) {
$title=$title.$l_searchSite;$searchResults='<span class=txtSm>'.$l_search[10].'</span>';
}
else {
$i=$viewmaxsearch*$page;
if($searchWhere==0){
$numRows=DB_query(53,0);
$pageNav=pageNav($page,$numRows,"{$indexphp}action=search&amp;searchFor=$searchFor&amp;searchWhere=$searchWhere&amp;searchHow=$searchHow&amp;days=$days&amp;exact=$exact&amp;page=",$viewmaxsearch,FALSE);
$makeLim=makeLim($page,$numRows,$viewmaxsearch);
if($numRows){
$cols=DB_query(4,0);
if(!isset($searchResults)) $searchResults='';
do{
$i++;
$forum=$cols[1];
$topic=$cols[2];
$pageAnchor=deSlice(FALSE,$cols[0]);
$searchResults.='<b>'.$i.'. </b><span class=txtSm>'.$l_posted.' :: '.$cols[4].'</span> - <a href="'.$indexphp.'action=vtopic&amp;forum='.$forum.'&amp;page='.deSlice(TRUE,$topic).'">'.$cols[6].'</a> <b>&#8212;&#8250;</b> <a href="'.$indexphp.'action=vthread&amp;forum='.$forum.'&amp;topic='.$topic.'">'.$cols[5].'</a><br>'."\n".'
&nbsp;&nbsp;&nbsp; <span class=txtSm><a href="'.$indexphp.'action=vthread&amp;forum='.$forum.'&amp;topic='.$topic.'&amp;page='.$pageAnchor[0].$pageAnchor[1].'">'.substr(strip_tags($cols[3]),0,81).'...</a></span><br><br>'."\n";
}
while($cols=DB_query(4,1));
}
}
elseif($searchWhere==1){
$numRows=DB_query(54,0);
$pageNav=pageNav($page,$numRows,"{$indexphp}action=search&amp;searchFor=$searchFor&amp;searchWhere=$searchWhere&amp;searchHow=$searchHow&amp;days=$days&amp;exact=$exact&amp;page=",$viewmaxsearch,FALSE);
$makeLim=makeLim($page,$numRows,$viewmaxsearch);
if($numRows){
$cols=DB_query(52,0);
if(!isset($searchResults)) $searchResults='';
do{
$i++;
$forum=$cols[1];
$topic=$cols[2];
$searchResults.='<b>'.$i.'. </b><span class=txtSm>'.$l_posted.' :: '.$cols[3].'</span> - <a href="'.$indexphp.'action=vtopic&forum='.$forum.'&amp;page='.deSlice(TRUE,$cols[0]).'">'.$cols[4].'</a> <b>&#8212;&#8250;</b> <a href="'.$indexphp.'action=vthread&amp;forum='.$forum.'&amp;topic='.$cols[0].'">'.$topic.'</a><br><br>'."\n";
}
while($cols=DB_query(52,1));
}
}
elseif($searchWhere==2){
$numRows=DB_query(58,0);
$pageNav=pageNav($page,$numRows,"{$indexphp}action=search&amp;searchFor=$searchFor&amp;searchWhere=$searchWhere&amp;searchHow=$searchHow&amp;days=$days&amp;exact=$exact&amp;page=",$viewmaxsearch,FALSE);
$makeLim=makeLim($page,$numRows,$viewmaxsearch);
if($numRows){
$cols=DB_query(57,0);
if(!isset($searchResults)) $searchResults='';
do{
$i++;
$forum=$cols[1];
$topic=$cols[2];
$pageAnchor=deSlice(FALSE,$cols[0]);
$searchResults.='<b>'.$i.'. </b><span class=txtSm>'.$l_posted.' :: '.$cols[4].'</span> - <a href="'.$indexphp.'action=vtopic&amp;forum='.$forum.'&amp;page='.deSlice(TRUE,$topic).'">'.$cols[6].'</a> <b>&#8212;&#8250;</b> <a href="'.$indexphp.'action=vthread&amp;forum='.$forum.'&amp;topic='.$topic.'">'.$cols[5].'</a><br>'."\n".'&nbsp;&nbsp;&nbsp; <span class=txtSm><a href="'.$indexphp.'action=vthread&amp;forum='.$forum.'&amp;topic='.$topic.'&amp;page='.$pageAnchor[0].$pageAnchor[1].'">'.substr(strip_tags($cols[3]),0,81).'...</a></span><br><br>'."\n";
}
while($cols=DB_query(57,1));
}
}
$title = $title.$l_searchSite;
}

if($days<=0) $days=$defDays;

echo load_header(); echo ParseTpl(makeUp('search'));
?>