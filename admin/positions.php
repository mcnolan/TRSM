<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: admin/positions.php
| Build 3
| <Changes>
| Added link to be able to alter the departments Css
| Css Revision
|
| <Purpose>
| Central roster  administration. Add, Edit and remove positions & departments. Asign crew to positions.
| 
| <Access>
| Level 4 and Above.
| 
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL License (See gpl.txt for more information)
*/
if(!defined("CONSTANT_ADMIN")) { die("Access Through SPMS main site"); }
?>
<a href="javascript: var t=window.open('admin/forms.php?form=adddept','posPop','width=300,height=150,scrollbars=no')">Add Department</a> | <a href="javascript: var t=window.open('admin/forms.php?form=orddept','posPop','width=250,height=350,scrollbars=yes')">Order Departments</a> | <a href="javascript: var t=window.open('admin/forms.php?form=deptCss','posPop','width=300,height=300,scrollbars=yes')">Edit Department Colors</a><br>
<br>
<table width="100%">
<tr class="ApositionHeader">
	<td>&nbsp;</td>
	<td>Position Name</td>
	<td>Order</td>
	<td></td>
	<td>Crew Member</td>
</tr>
<?
$get_depts = "SELECT * FROM " . $table['departments'] . " AS departments ORDER BY ord ASC";
$res_depts = $sql->queryme($get_depts);

while($department = $sql->sql_array($res_depts)) {
	//Sub Select to get positions in said department.
	$get_pos = "SELECT positions.*, crew.first_n, crew.last_n, crew.active FROM " . $table['positions'] . " AS positions LEFT JOIN " . $table['crew'] . " AS crew ON(positions.crewid = crew.crewid) WHERE deptid = '" . $department['deptid'] . "' ORDER BY positions.ord ASC";
	$res_pos = $sql->queryme($get_pos);
?>
<tr>
	<td class="<? echo $department['class']; ?>" colspan="5"><? echo reformatData($department['name']); ?> <span class="AdeptLinks"><a href="javascript: var t=window.open('admin/forms.php?form=changedp&deptid=<? echo $department['deptid']; ?>','posPop','width=300,height=150,scrollbars=no')">Edit</a> | <a href="javascript: var t=window.open('admin/do.php?task=deldept&deptid=<? echo $department['deptid']; ?>','posPop','width=300,height=100,scrollbars=no')">Delete</a></span></td>
</tr>
<?
while($position = $sql->sql_array($res_pos)) {
?>
<tr class="ApositionRows">
	<td>&nbsp;</td>
	<td><? if($position['senior'] == 1) { echo "<span class=\"seniorStaff\"> "; } if($position['enlisted'] == 1) { echo "<span class=\"enlistedCrew\"> "; } echo reformatData($position['name']); ?></td>
	<td align="center"><? echo $position['ord']; ?></td>
	<td><a href="javascript: var t=window.open('admin/forms.php?form=changepos&posid=<? echo $position['posid']; ?>','posPop','width=300,height=250,scrollbars=no')">Edit</a> | <a href="javascript: var t=window.open('admin/do.php?task=delpos&posid=<? echo $position['posid']; ?>','posPop','width=300,height=100,scrollbars=yes')">Delete</a></td>
	<td><a href="javascript: var t=window.open('admin/forms.php?form=assign&posid=<? echo $position['posid']; ?>','posPop','width=350,height=150,scrollbars=no')"><? if($position['crewid'] <> '0') { echo reformatData($position['first_n'] . " " . $position['last_n']); if($position['active'] == 3) { echo " (NPC)"; } } else { echo "Available"; } ?></a></td>
</tr>
<?
} //Position loop
?>
<tr class="ApositionFooter">
	<td colspan="2"><a href="javascript: var t=window.open('admin/forms.php?form=addpos&deptid=<? echo $department['deptid']; ?>','posPop','width=300,height=250,scrollbars=no')">Add Position</a></td>
	<td colspan="3"><a href="javascript: var t=window.open('admin/forms.php?form=orderpos&deptid=<? echo $department['deptid']; ?>','posPop','width=250,height=350,scrollbars=yes')">Change Order</a></td>
</tr>
<?
} //Department Loop
?>
</table>
<a href="javascript: var t=window.open('admin/forms.php?form=adddept','posPop','width=300,height=150,scrollbars=no')">Add Department</a>
