<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.02
|
| File: admin/crew.php
| Build 4
| <Changes>
| Css Revisions
| Addition of CoC link
| Addition of Rank link
|
| <Purpose>
| Crew administraton. Add crew manually, alter existing crew details, add NPC's and of course remove crew.
|
| <Access>
| Level 4 and above
|
| TRSM1.02 is (c) Nolan 2003-2006, and is covered by the GPL License (See gpl.txt for more information)
*/
if(!defined("CONSTANT_ADMIN")) { die("Access Through TRSM main site"); }
?>
<p>
    <a href="javascript: var t=window.open('admin/forms.php?form=newcrewm','posPop','width=450,height=300,scrollbars=yes')">Add Crew Member</a> | <a href="javascript: var t=window.open('admin/forms.php?form=newnpc','posPop','width=450,height=300,scrollbars=yes')">Add NPC</a> | <a href="javascript: var t=window.open('admin/forms.php?form=ranksmain','posPop','width=500,height=340,scrollbars=yes')">Edit Ranks</a> <? if($mod_coc) { ?>| <a href="javascript: var t=window.open('admin/forms.php?form=coc','posPop','width=450,height=300,scrollbars=yes')">Chain Of Command</a><? } ?> | <a href="javascript: var t=window.open('admin/forms.php?form=emailer','posPop','width=500,height=340,scrollbars=yes')">Send Email</a>
</p>
<table width="100%">
<tr class="AcrewHeader">
    <td>Status</td>
    <td>Name</td>
    <td>Rank</td>
    <td>Joined</td>
    <td>Bio</td>
</tr>
<?
$crewlist = "SELECT crew.first_n, crew.last_n, crew.joined, crew.active, crew.loa, crew.crewid, rank.name AS rankn, rank.url FROM " . $table['crew'] . " AS crew LEFT JOIN " . $table['ranks'] . " AS rank ON (crew.rankid = rank.rankid) ORDER BY active ASC";

$result = $sql->queryme($crewlist);
while($crew = $sql->sql_array($result)) {
?>
<tr class="AcrewRows">
    <td align="center"><? if($crew['active'] <> 3) { ?><a href="javascript: var t=window.open('admin/do.php?task=tgactive&crewid=<? echo $crew['crewid']; ?>','posPop','width=300,height=100,scrollbars=no')"><? if($crew['active'] == '1') { echo "Active"; } else { echo "Pending"; } ?></a> <a href="javascript: var t=window.open('admin/do.php?task=tgloa&crewid=<? echo $crew['crewid']; ?>','posPop','width=300,height=100,scrollbars=no')"><? if($crew['loa'] == '1') { echo "Loa"; } else { echo "Posting"; } ?></a><? } else { echo "NPC"; } ?></td>
    <td><? echo reformatData($crew['rankn'] . " " . $crew['first_n'] . " " . $crew['last_n']); ?> <font size="-1"><a href="javascript: var t=window.open('admin/do.php?task=deletecrew&crewid=<? echo $crew['crewid']; ?>','posPop','width=300,height=100,scrollbars=no')">Remove</a></td>
    <td><a href="javascript: var t=window.open('admin/forms.php?form=changerank&crewid=<? echo $crew['crewid']; ?>','posPop','width=300,height=210,scrollbars=no')"><img src="<? echo $rpath . $crew['url']; ?>" alt="<? echo $crew['rankn']; ?>" border="0"></a></td>
    <td align="center"><? echo date("M j Y", $crew['joined']); ?></td>
    <td align="center"><a href="javascript: var t=window.open('admin/forms.php?form=cbio&crewid=<? echo $crew['crewid']; ?>','posPop','width=450,height=300,scrollbars=yes')"><img src="images/padd.bmp" border="0"></a></td>
</tr>
<?
}
?>
</table>
