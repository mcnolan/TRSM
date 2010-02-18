<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: crewbio.php
| Build 2
| <Changes>
| Css changes
|
| <Purpose>
| View the bio and awards of a selected crew member
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/

$pagename = "Crew Bio";
include "header.php";
$crew = new crew;
$crew->get($_GET['cid']);
?>
<p class="bioCharName">
    Crew Biography For
<?
echo reformatData($crew->ln);
if($crew->fn != '') {
    echo ", " . reformatData($crew->fn) . " " . reformatData($crew->mn);
}
if($crew->npc) {
    echo " (npc)";
}
?>
</p>

<table width="90%" align="center">
<tr>
    <td width="20%" class="bioLabel">Full Name:</td>
    <td><? echo reformatData($crew->fn) . " " . reformatData($crew->mn) . " " . reformatData($crew->ln); ?></td>
</tr>
<tr>
    <td class="bioLabel">Current Rank</td>
    <td><img src="images/ranks/<? echo $crew->ranki; ?>" border="0"> <? echo $crew->rankn; ?></td>
</tr>
<tr>
    <td class="bioLabel">Position(s) held</td>
    <td>
<? 
$getp = "SELECT * FROM " . $table['positions'] . " AS positions WHERE positions.crewid = '" . $crew->cid . "'";
$resp = $sql->queryme($getp);
while($pos = $sql->sql_array($resp)) {
    echo reformatData($pos['name']) . "<br>"; 
}
?>
    </td>
</tr>
<tr>
    <td class="bioLabel">Species</td>
    <td><? echo reformatData($crew->species); ?></td>
</tr>
<tr>
    <td class="bioLabel">Gender</td>
    <td><? echo $crew->gender; ?></td>
</tr>
<?
$fetchcf = "SELECT * FROM " . $table['customfields'] . " AS cust_feilds LEFT JOIN " . $table['biodata'] . " AS feild_data ON(feild_data.fid = cust_feilds.fid) WHERE crewid = '" . $_GET['cid'] . "' ORDER BY ord ASC";
$rescf = $sql->queryme($fetchcf);
while($cust = $sql->sql_array($rescf)) {
?>
<tr>
    <td class="bioLabel"><? echo reformatData($cust['name']); ?></td>
    <td <? if($cust['type'] <> "box" && $cust['type'] <> "box_large") { echo 'align="left"'; } else { echo 'align="left"'; } ?>><? echo reformatData(nl2br($cust['info'])); ?></td>
</tr>
<?
}
if(!$crew->npc) {
?>
<tr>
    <td class="bioLabel">Member Since</td>
    <td><? echo date("j F Y", $crew->joined); ?></td>
</tr>
<tr>
    <td colspan="2">
<?
if($mod_awards) {
$fetch_awds = "SELECT * FROM " . $table['awarded'] . " AS awarded LEFT JOIN " . $table['awards'] ." AS awards ON(awards.awardid = awarded.awardid) WHERE crewid = '" . $crew->cid . "' AND date < '" . time() . "'";
$awd_res = $sql->queryme($fetch_awds);
$c = 1;
while($award = $sql->sql_array($awd_res)) {
?>
    <img src="<? echo $path . $awdpath . $award['image']; ?>" alt="<? echo reformatData($award['name']) . "- Awarded on " . date("M j Y", $award['date']) . " \n Reason: " . reformatData($award['reason']); ?>" border="0">&nbsp;
<?
if($c == 3) { echo "<br>"; $c = 1; } else { $c++; }
}
}
?>
    </td>
</tr>
<?
}
?>
</table>
<?
include "footer.php";
?>