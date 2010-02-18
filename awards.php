<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: awards.php
| Build 2
| <Changes>
| Css Change
|
| <Purpose>
| Display a current list of awards in the system.
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL License (See gpl.txt for more information)
*/
$pagename = "Crew Awards";
include "header.php";
$ards = "SELECT * FROM " . $table['awards'] . " AS awards";
$res_ard = $sql->queryme($ards);
?>
<img src="<? echo $imagepath; ?>header-awards.gif">
<br><br>
<table width="100%" cellpadding="2">
<?
while($awards = $sql->sql_array($res_ard)) {
?>
<tr>
    <td><a href="javascript: var t=window.open('info.php?info=awdees&awdid=<? echo $awards['awardid']; ?>','awdPop','width=300,height=150,scrollbars=yes')"><img src="<? echo $imagepath . "awards/" . $awards['image']; ?>" border="0"></a></td>
    <td rowspan="2" class="awardContent" valign="top"><? echo reformatData(nl2br($awards['desc'])); ?></td>
</tr>
<tr>
    <td class="awardName"><? echo reformatData($awards['name']); ?></td>
</tr>
<?
}
?>
</table>
<?
include "footer.php";
?>