<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: admin/news.php
| Build 2
| <Changes>
| Css revision
|
| <Purpose>
| News administration. Add, edit and remove news items.
|
| <Access>
| Level 2 and above.
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL License (See gpl.txt for more information)
*/
if(!defined("CONSTANT_ADMIN")) { die("Access Through TRSM main site"); }
?>
<a href="javascript: var t=window.open('admin/forms.php?form=newnews','posPop','width=300,height=270,scrollbars=no')">Post News Article</a>
<br><br>
<table width="90%" align="center">
<?
$getnews = "SELECT * FROM " . $table['news'] . " AS news ORDER BY date DESC";
$resnew = $sql->queryme($getnews);
while($news = $sql->sql_array($resnew)) {
?>
<tr>
    <td class="AnewsHeader" width="60%"><? echo reformatData($news['title']); ?></td>
    <td class="AnewsPoster">Posted By <? echo reformatData($news['poster']); ?></td>
</tr>
<tr>
    <td colspan="3" class="AnewsContent"><? echo reformatData($news['content']); ?></td>
</tr>
<tr>
    <td colspan="2" class="AnewsFooter">Posted On <? echo date("M j Y", $news['date']); ?>
      <a href="javascript: var t=window.open('admin/forms.php?form=editnews&nid=<? echo $news['newsid']; ?>','posPop','width=300,height=300,scrollbars=no')">Edit</a> <a href="javascript: var t=window.open('admin/do.php?task=delnews&nid=<? echo $news['newsid']; ?>','posPop','width=300,height=100,scrollbars=no')">Delete</a></td>
</tr>
<?
}
?>
</table>