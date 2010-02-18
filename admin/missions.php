<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.02
|
| File: admin/missions.php
| Build 3
| <Changes>
| Css Changes
| Added options to edit old missions
|
| <Purpose>
| Mission administration. Add, review and edit missions. Existing missions end when a new one is created.
|
| <Access>
| Level 4 and above.
|
| TRSM1.02 is (c) Nolan 2003-2006, and is covered by the GPL Licence (See gpl.txt for more information)
*/
if(!defined("CONSTANT_ADMIN")) { die("Access Through TRSM main site"); }
?>
<a href="javascript: var t=window.open('admin/forms.php?form=addmission','posPop','width=300,height=260,scrollbars=no')">Start New Mission/Finish Current</a>
<?
$mis = new mission;
$mis->get(0);
?>
<table width="70%" align="center">
<tr class="AmissionHeader">
    <td colspan="2">Current Mission</td>
</tr>
<tr>
    <td width="20%" class="AmissionLabel">Name</td>
    <td class="missionName"><? echo reformatData($mis->name); ?></td>
</tr>
<tr>
    <td valign="top" class="AmissionLabel">Synopsis</td>
    <td class="missionContent"><? echo reformatData(nl2br($mis->desc)); ?></td>
</tr>
<tr>
    <td class="AmissionLabel">Started</td>
    <td class="missionFooter"><? echo date("j M Y", $mis->start); ?></td>
</tr>
<tr>
    <td colspan="2" align="center"><a href="javascript: var t=window.open('admin/forms.php?form=editmission','posPop','width=300,height=260,scrollbars=no')">Edit</a> <a href="javascript: var t=window.open('admin/do.php?task=delmis&mid=<? echo $mis->mid; ?>','posPop','width=300,height=100,scrollbars=no')">Delete</a></td>
</tr>
</table>

<br>

<table width="95%" align="center">
<tr class="AmissionHeader">
    <td width="20%">Mission name</td>
    <td width="">Synopsis</td>
    <td width="15%">Started</td>
    <td width="15%">Finished</td>
    <td width="10%">&nbsp;</td>
</tr>
<?
$get = "SELECT * FROM " . $table['missions'] . " AS missions WHERE finish <> '0'";
$res = $sql->queryme($get);
while($missions = $sql->sql_array($res)) {
?>
<tr class="AmissionRows">
    <td class="missionName"><? echo reformatData($missions['mname']); ?></td>
    <td class="missionContent"><? echo reformatData(nl2br($missions['desc'])); ?></td>
    <td><? echo date("j M Y", $missions['start']); ?></td>
    <td><? echo date("j M Y", $missions['finish']); ?></td>
    <td><a href="javascript: var t=window.open('admin/forms.php?form=editmission&mid=<? echo $missions['missionid']; ?>','posPop','width=300,height=260,scrollbars=no')">Edit</a> <br> <a href="javascript: var t=window.open('admin/do.php?task=delmis&mid=<? echo $missions['missionid']; ?>','posPop','width=300,height=100,scrollbars=no')">Remove</a></td>
</tr>
<?
}
?>
</table>