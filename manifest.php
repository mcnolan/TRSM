<?php
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: manifest.php
| Build 2
| <Changes>
| Css Changes
|
| <Purpose>
| Display crew manifest.
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL License (See gpl.txt for more information)
*/
$pagename = "Manifest";
include "header.php";
?>
<img src="<? echo $imagepath; ?>header-manifest.gif">
<br>
<br>
<table width="100%">
<tr class="manifestHeader">
	<td>Position</td>
	<td>Name</td>
	<td>Rank</td>
	<td>Species/Gender</td>
	<td><center>Bio</center></td>
	<td><center>Email</center></td>
</tr>
<?
//Begin department search
$fetch_depts = "SELECT departments.*, count(departments.deptid) AS deptcount FROM " . $table['departments'] . " AS departments LEFT JOIN " . $table['positions'] . " AS positions ON(departments.deptid = positions.deptid) GROUP BY departments.deptid ORDER BY departments.ord ASC";
$result1 = $sql->queryme($fetch_depts);
while($department = $sql->sql_array($result1)) {
?>
<tr>
	<td colspan="6" class="<? echo $department['class']; ?>"><? echo reformatData($department['name']); ?></td>
</tr>
<?
	//Fetch positions and crew for this department
	$fetch_positions = "SELECT crew.first_n, crew.last_n, crew.crewid, crew.species, crew.gender, crew.email, crew.active, crew.loa, positions.posid, positions.name, positions.senior, positions.enlisted, rank.name AS rnkn, rank.url FROM (" . $table['positions'] . " AS positions LEFT JOIN " . $table['crew'] . " AS crew ON (positions.crewid = crew.crewid)) LEFT JOIN " . $table['ranks'] . " AS rank ON (crew.rankid = rank.rankid) WHERE positions.deptid = " . $department['deptid'] . " ORDER BY positions.ord ASC";
	$result2 = $sql->queryme($fetch_positions);
	while($pos_crew = $sql->sql_array($result2)) {
            $euser = ''; $domain = '';
?>
<tr class="manifestRow">
	<td width="33%"><? if($pos_crew['senior'] == 1) { echo "<span class=\"seniorStaff\">"; } if($pos_crew['enlisted'] == 1) { echo "<span class=\"enlistedCrew\">"; } echo reformatData($pos_crew['name']); ?> </span><font size="-1"><a href="javascript: var t=window.open('info.php?info=pos&posid=<? echo $pos_crew['posid']; ?>','posPop','width=400,height=200,scrollbars=yes')">?</a></font></td>
	<?
		if(($pos_crew['crewid'] == 0) || ($pos_crew['crewid'] == "")) {
	?>
	<td colspan="5">Available - <a href="join.php">Join Now</a></td>
	<?
		} elseif($pos_crew['active'] == 0) {
	?>
	<td colspan="5">Pending Application</td>
	<?
		} else {
                $domain = str_replace('@','',stristr($pos_crew['email'], '@'));
                $euser = str_replace('@'.$domain, '', $pos_crew['email']);
	?>
	<td><? if($pos_crew['loa'] == 1) { echo "<span class=\"onLOA\">"; } elseif($pos_crew['active'] == 3) { echo "<span class=\"NPC\">"; } echo reformatData($pos_crew['first_n']) . " " . reformatData($pos_crew['last_n']); ?></td>
	<td><img src="<? echo $rpath . $pos_crew['url']; ?>" border="0" alt="<? echo $pos_crew['rnkn']; ?>"></td>
	<td><? echo reformatData($pos_crew['species']); ?>/<? echo $pos_crew['gender']; ?></td>
	<td><center><a href="crewbio.php?cid=<? echo $pos_crew['crewid']; ?>"><img src="images/padd.bmp" border="0"></a></center></td>
	<td><center><? if($pos_crew['active'] == 3) { echo "<img src=\"" . $imagepath . "comm-npc.gif\">"; } else { ?><script language="JavaScript">unScramble("<? echo $euser; ?>","<? echo $domain; ?>","","Your <? echo $shipname; ?> Character","Click To Email This CrewMember");</script><img src="images/comm.gif" border="0"></a><? } ?></center></td>
</tr>

<?
		} //End master if
	} //End pos_crew
}
//End of Department loop
?>
</table>
<?
include "footer.php";
?>