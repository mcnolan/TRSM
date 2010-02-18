<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: admin/records.php
| Build 2
| <Changes>
| Changes in Css
|
| <Purpose>
| Player records administration - View present records & add new ones.
|
| <Access>
| Level 4 and above.
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
if(!defined("CONSTANT_ADMIN")) { die("Access Through TRSM main site"); }
if($_GET['view'] != "view") {
?>
<p>
    <a href="javascript: var t=window.open('admin/forms.php?form=addrec','posPop','width=350,height=230,scrollbars=no')">Add Custom Record Entry</a>
</p>
<table width="90%" align="Center">
<tr class="ArecordHeader">
    <td>Crew Member</td>
    <td>Last Record Entry</td>
    <td></td>
</tr>
<?
$query = "SELECT crew.crewid, first_n, last_n, name AS rankn, comment FROM " . $table['crew'] . " AS crew LEFT JOIN " . $table['ranks'] . " AS rank ON (crew.rankid = rank.rankid) LEFT JOIN " . $table['records'] . " AS record ON (record.crewid = crew.crewid) WHERE active = '1' ORDER BY joined ASC, date DESC";
$result = $sql->queryme($query);
while($crew = $sql->sql_array($result)) {
if($last != $crew['last_n']) {
    if($crew['comment'] == "") {
        $crew['comment'] = "No Previous Records";
    }
?>
<tr class="ArecordRows">
    <td><? echo reformatData($crew['rankn'] . " " . $crew['first_n'] . " " . $crew['last_n']); ?></td>
    <td><? echo reformatData($crew['comment']); ?></td>
    <td><a href="admin.php?page=records.php&view=view&crewid=<? echo $crew['crewid']; ?>">View Records</a></td>
</tr>
<?
}
$last = $crew['last_n'];
}
?>
</table>
<?
} else {
$c = new crew;
$c->get($_GET['crewid']);
?>
<p class="ArecordTitle">Records for <? echo reformatData($c->rankn . " " . $c->fn . " " . $c->ln); ?></p>

<table width="90%" align="center">
<tr class="ArecordHeader">
    <td>Comment</td>
    <td>Reason</td>
    <td>Date</td>
    <td>Points</td>
</tr>
<?
$fetchrec = "SELECT * FROM " . $table['records'] . " AS record WHERE crewid = '" . $_GET['crewid'] . "' ORDER BY date DESC";
$res = $sql->queryme($fetchrec);
while($records = $sql->sql_array($res)) {
?>
<tr class="ArecordRows">
    <td><? echo reformatData($records['comment']); ?></td>
    <td><? echo reformatData($records['reason']); ?></td>
    <td><? echo date("M j Y", $records['date']); ?></td>
    <td align="center"><? echo $records['pchange']; ?></td>
</tr>
<?
}
?>
</table>
<?
}
?>