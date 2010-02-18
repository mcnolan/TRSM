<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: admin/awards.php
| Build 2
| <Changes>
| Css changes
|
| <Purpose>
| Alter the layout of the custom bios fields.
|
| <Access>
| Level 5 only.
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
if(!defined("CONSTANT_ADMIN")) { die("Access Through TRSM main site"); }
?>
<br><br>
<table width="95%" align="center">
<tr>
	<td colspan="5" class="AcustomTitle">Fixed Fields (Unchangable)</td>
</tr>

<tr class="AcustomRowHeader">
	<td>Field name</td>
	<td colspan="4">Field Type</td>
</tr>
<tr>
	<td class="AcustomHeader">First Name</td>
	<td colspan="2">Line</td>
	<td colspan="2"><input type="text" disabled size="20"></td>
</tr>
<tr>
	<td class="AcustomHeader">Middle Name</td>
	<td colspan="2">Line</td>
	<td colspan="2"><input type="text" disabled size="20"></td>
</tr>
<tr>
	<td class="AcustomHeader">Last Name</td>
	<td colspan="2">Line</td>
	<td colspan="2"><input type="text" disabled size="20"></td>
</tr>
<tr>
	<td class="AcustomHeader">Email</td>
	<td colspan="2">Line</td>
	<td colspan="2"><input type="text" disabled size="20"></td>
</tr>
<tr>
	<td class="AcustomHeader">Gender</td>
	<td colspan="2">Select</td>
	<td colspan="2"><select><option>Male</option><option>Female</option><option>Other</option></select></td>
</tr>
<tr>
	<td class="AcustomHeader">Species</td>
	<td colspan="2">line_med</td>
	<td colspan="2"><input type="text" disabled size="15"></td>
</tr>
<tr>
	<td colspan="5" class="AcustomTitle"><br>Custom Fields</td>
</tr>
<?
$get_fields = "SELECT * FROM " . $table['customfields'] . " AS cust_feilds ORDER BY ord ASC";
$res = $sql->queryme($get_fields);
while($feild = $sql->sql_array($res)) {
?>
<tr class="AcustomRows">
	<td class="AcustomHeader"><? echo $feild['name']; ?></td>
	<td><? echo $feild['type']; ?></td>
	<td><a href="javascript: var t=window.open('admin/forms.php?form=editfield&fid=<? echo $feild['fid']; ?>','posPop','width=300,height=150,scrollbars=no')">Edit</a></td>
	<td>
<? switch($feild['type']) {

case "line" :
	echo "<input type=\"text\" disabled size=\"$line_size\">";
break;

case "box" :
	echo "<textarea disabled cols=\"$box_cols\" rows=\"$box_rows\"></textarea>";
break;

case "line_small" :
	echo "<input type=\"text\" disabled size=\"$linesmall_size\">";
break;

case "line_med" :
	echo "<input type=\"text\" disabled size=\"$linemed_size\">";
break;

case "box_large" :
	echo "<textarea disabled cols=\"$boxlarge_cols\" rows=\"$boxlarge_rows\"></textarea>";
break;
}
?>
	</td>
	<td><a href="javascript: var t=window.open('admin/do.php?task=delfield&fid=<? echo $feild['fid']; ?>','posPop','width=300,height=100,scrollbars=no')">Delete</a></td>
</tr>
<?
}
?>
<tr>
	<td colspan="3"><a href="javascript: var t=window.open('admin/forms.php?form=newfield','posPop','width=300,height=150,scrollbars=no')">Add New Field</a></td>
	<td colspan="2"><a href="javascript: var t=window.open('admin/forms.php?form=ordfields','posPop','width=250,height=350,scrollbars=yes')">Change Field Order</a></td>
</tr>
</table>