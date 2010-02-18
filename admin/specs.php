<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: admin/specs.php
| Build 2
| <Changes>
| Css changes
|
| <Purpose>
| Ship specifications administration. Add, Edit and Remove individual stats.
|
| <Access>
| Level 5 only.
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL License (See gpl.txt for more information)
*/
if(!defined("CONSTANT_ADMIN")) { die("Access Through TRSM main site"); }
?>
<a href="javascript: var t=window.open('admin/forms.php?form=new_stat','posPop','width=300,height=330,scrollbars=no')">Add New Specification</a>
<?
if(@$_GET['task'] == 'statord') {
    $u = $_GET['ord'];
    if($_GET['way'] == "up") {
	$u--;
    } else {
	$u++;
    }
    $query1 = "UPDATE " . $table['specs'] . " SET sord = '" . $_GET['ord'] . "' WHERE sord = '$u'";
    $query2 = "UPDATE " . $table['specs'] . " SET sord = '$u' WHERE statid = '" . $_GET['sid'] . "'";
    $sql->queryme($query1);
    $sql->queryme($query2);
    $ref = "specs";
    $message = "<span class=\"Amessage\">Order Updated</span>";
}
?>

<? echo @$message; ?><br>
<table width="100%" cellspacing="0">
<?
$get_stat = "SELECT * FROM " . $table['specs'] . " AS stats ORDER BY sord ASC";
$res = $sql->queryme($get_stat);
while($specs = $sql->sql_array($res)) {
?>
<tr class="AspecsRows">

    <td align="right" valign="bottom" width="8%"><? if($specs['sord'] <> '1') { ?><a href="admin.php?page=specs.php&task=statord&way=up&ord=<? echo $specs['sord'];?>&sid=<? echo $specs['statid']; ?>"><img src="<? echo $imagepath; ?>up.gif" border="0" alt="Move Up"></a><? } ?><a href="admin.php?page=specs.php&task=statord&way=down&ord=<? echo $specs['sord'];?>&sid=<? echo $specs['statid']; ?>"><img src="<? echo $imagepath; ?>down.gif" border="0" alt="Move Down"></a>&nbsp;</td>

    <td valign="top"><? if($specs['value'] == "" || $specs['multil'] == 1) { echo "<br><span class=\"SpecsLabel\">"; } echo reformatData($specs['sname']); if($specs['multil'] == 1) { echo "</span><br>"; } if($specs['sname'] == "") { echo "&nbsp;"; } echo " " . reformatData($specs['value']); ?></td>

    <td width="15%"><a href="javascript: var t=window.open('admin/forms.php?form=editstat&stid=<? echo $specs['statid']; ?>','posPop','width=300,height=350,scrollbars=no')">Edit</a> <a href="javascript: var t=window.open('admin/do.php?task=delstat&stat=<? echo $specs['statid']; ?>','posPop','width=300,height=100,scrollbars=no')">Remove</a>

</tr>
<?
}
?>
</table>
