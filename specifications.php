<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: specifications.php
| Build 2
| <Changes>
| Css Change
|
| <Purpose>
| Display the ship specifications
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
$pagename = "Ship Specifications";
include "header.php";
?>
<img src="<? echo $imagepath; ?>header-specs.gif">
<table width="95%" align="center">
<tr>
    <td valign="top">
        <table cellspacing="0">
<?
$get_stat = "SELECT * FROM " . $table['specs'] . " AS stats ORDER BY sord ASC";
$res = $sql->queryme($get_stat);
while($specs = $sql->sql_array($res)) {
?>
        <tr class="specsRows">
            <td><? if($specs['value'] == "" || $specs['multil'] == 1) { echo "<br><span class=\"specsLabel\">"; } echo reformatData($specs['sname']); if($specs['multil'] == 1) { echo "</span><br>"; } if($specs['sname'] == "") { echo "&nbsp;"; } echo " " . reformatData($specs['value']); ?></td>
        </tr>
<?
}
?>
        </table>
    </td>
    <td valign="top" width="30%">

    Images Go Here

    </td>
</tr>
</table>

<?
include "footer.php";
?>