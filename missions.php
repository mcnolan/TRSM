<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: missions.php
| Build 2
| <Changes>
| Css/Layout changes
|
| <Purpose>
| View current and previous Missions.
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
$pagename = "Missions";
include "header.php";
$cmis = new mission;
$cmis->get(0);
if($cmis->mid == '') {
    $cmis->name = "No Missions have been started";
    $cmis->desc = "Contact the Co for more information";
    $cmis->start = time();
}
?>
<img src="<? echo $imagepath; ?>header-mission.gif">
<br><br>
<table width="60%" align="center">
<tr>
    <td class="missionHeader">Current Mission</td>
</tr>
<tr>
    <td class="missionName">"<? echo reformatData($cmis->name); ?>"</td>
</tr>
<tr>
    <td class="missionTitle">Synopsis</td>
</tr>
<tr>
    <td class="missionContent"><? echo reformatData(nl2br($cmis->desc)); ?></td>
</tr>
<tr>
    <td class="missionFooter">Started: <? echo date("j M Y", $cmis->start); ?></td>
</tr>
</table>

<br>

<table width="90%" align="center">
<tr>
    <td colspan="4" class="missionHeader">Previous Missions</td>
</tr>
<tr class="missionHeader">
    <td width="25%">Mission Name</td>
    <td>Synopsis</td>
    <td width="15%" align="Center">Started</td>
    <td width="15%" align="center">Ended</td>
</tr>
<?
$getall = "SELECT * FROM " . $table['missions'] . " AS missions WHERE finish <> '0' ORDER BY finish DESC";
$resall = $sql->queryme($getall);
while($allm = $sql->sql_array($resall)) {
?>
<tr class="missionRows">
    <td class="missionName"><? echo reformatData($allm['mname']); ?></td>
    <td class="missionContent"><? echo reformatData(nl2br($allm['desc'])); ?></td>
    <td valign="top"><? echo date("j M Y", $allm['start']); ?></td>
    <td valign="top"><? echo date("j M Y", $allm['finish']); ?></td>
</tr>
<?
}
?>
</table>

<?
include "footer.php";
?>