<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: admin/awards.php
| Build 2
| <Changes>
| Css Changes
|
| <Purpose>
| Awards administration. Add, remove and Edit Player awards. Also assign awards to players.
|
| <Access>
| Level 4 and above
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
if(!defined("CONSTANT_ADMIN")) { die("Access Through TRSM main site"); }
?>
<p>
    <a href="javascript: var t=window.open('admin/forms.php?form=addawd','posPop','width=400,height=300,scrollbars=no')">Add New Award</a> | <a href="javascript: var t=window.open('admin/forms.php?form=presentawd','posPop','width=400,height=200,scrollbars=no')">Present Award To Player</a>
</p>

<table width="90%" align="center">
<?
$awards = "SELECT * FROM " . $table['awards'] . " AS awards";
$resawd = $sql->queryme($awards);
while($awards = $sql->sql_object($resawd)) {
?>
<tr>
    <td width="40%" class="AawardName"><img src="<? echo $awdpath . $awards->image; ?>"> <br> <? echo reformatData($awards->name); ?>
    <br><a href="javascript: var t=window.open('admin/forms.php?form=editawd&awdid=<? echo $awards->awardid; ?>','posPop','width=400,height=220,scrollbars=no')">Edit</a> <a href="javascript: var t=window.open('admin/do.php?task=delawd&awd=<? echo $awards->awardid; ?>','posPop','width=300,height=100,scrollbars=no')">Delete</a></td>
</tr>
<tr>
    <td colspan="2" class="AawardInfo"><? echo reformatData($awards->desc); ?></td>
</tr>
<tr>
    <td></td>
</tr>
<?
}
?>
</table>